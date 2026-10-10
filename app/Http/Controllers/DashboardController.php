<?php

namespace App\Http\Controllers;

use App\Events\ConversationMessageSent;
use App\Mail\VerifyAccountEmail;
use App\Models\UserAccount;
use App\Notifications\DashboardActivityNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Throwable;

class DashboardController extends Controller
{
    private const PROFILE_MEDIA_COLUMNS = [
        'business' => [
            'business_photo_1' => ['seller_prof_pic'],
            'business_photo_2' => ['seller_prof_pic1'],
            'business_photo_3' => ['seller_prof_thumb_pic'],
            'business_photo_4' => ['seller_prof_thumb_pic1'],
            'business_document_1' => ['seller_doc_path'],
            'business_document_2' => ['seller_doc_path1'],
            'business_document_3' => ['seller_doc_path2'],
            'business_document_4' => ['seller_doc_path3'],
        ],
        'investor' => [
            'investor_photo' => ['inv_profile_pic_path'],
            'investor_document' => ['inv_doc_path'],
        ],
        'mentor' => [
            'mentor_profile_image' => ['mentor_profile_pic'],
            'mentor_document' => ['mentor_doc_path'],
        ],
        'startup' => [
            'incorporation_certificate' => ['startup_doc_path'],
            'startup_document_1' => ['startup_doc_path1'],
            'startup_document_2' => ['startup_doc_path2'],
            'startup_document_3' => ['startup_doc_path3'],
            'startup_photo_1' => ['startup_prof_pic'],
            'startup_photo_2' => ['startup_prof_pic1'],
            'startup_photo_3' => ['startup_prof_thumb_pic'],
            'startup_photo_4' => ['startup_prof_thumb_pic1'],
        ],
    ];

    private const PROFILE_TYPES = [
        'business' => [
            'table' => 'profile_business',
            'id' => 'business_id',
            'key' => 'business_profile_str',
            'status' => 'business_profile_status',
            'label' => 'Business',
            'title' => ['advmt_headline', 'seller_company', 'seller_name'],
            'location' => ['ofc_city', 'ofc_country'],
            'category' => ['industry_sector'],
            'summary' => ['company_summary', 'seller_intro', 'business_pitch'],
            'image' => 'seller_prof_pic',
            'default_image' => 'assets/img/default-business-profile.png',
        ],
        'investor' => [
            'table' => 'profile_investor',
            'id' => 'investor_id',
            'key' => 'inv_profile_str',
            'status' => 'inv_profile_status',
            'label' => 'Investor',
            'title' => ['inv_headline', 'company_name', 'inv_name'],
            'location' => ['inv_city', 'company_city', 'inv_country'],
            'category' => ['sector_preference'],
            'summary' => ['company_summary', 'inv_abt_urself', 'inv_intro'],
            'image' => 'inv_profile_pic_path',
            'default_image' => 'assets/img/default-investor-profile.png',
        ],
        'mentor' => [
            'table' => 'profile_mentors',
            'id' => 'mentor_id',
            'key' => 'mentor_profile_str',
            'status' => 'mentor_profile_status',
            'label' => 'Mentor',
            'title' => ['mentor_adv_headline', 'mentor_name'],
            'location' => ['mentor_city', 'mentor_location', 'mentor_country'],
            'category' => ['mentor_designation', 'mentor_company'],
            'summary' => ['mentor_profile_summary', 'mentor_intro'],
            'image' => 'mentor_profile_pic',
            'default_image' => 'assets/img/default-mentor-profile.png',
        ],
        'startup' => [
            'table' => 'profile_startups',
            'id' => 'startup_id',
            'key' => 'startup_profile_str',
            'status' => 'startup_profile_status',
            'label' => 'Startup',
            'title' => ['advmt_headline', 'name_of_entity', 'startup_name'],
            'location' => ['ofc_city', 'ofc_country'],
            'category' => ['industry_sector'],
            'summary' => ['company_summary', 'startup_intro', 'business_pitch'],
            'image' => 'startup_prof_pic',
            'default_image' => 'assets/img/default-startup-profile.png',
        ],
    ];

    public function index(): View
    {
        $profiles = collect();
        foreach (self::PROFILE_TYPES as $type => $definition) {
            if (! Schema::hasTable($definition['table'])
                || ! Schema::hasColumn($definition['table'], 'user_id')) {
                continue;
            }

            foreach (DB::table($definition['table'])
                ->where('user_id', Auth::id())
                ->get() as $profile) {
                $profiles->push($this->profileCard($type, $definition, $profile));
            }
        }

        $selectedType = request()->query('type', 'all');
        if (isset(self::PROFILE_TYPES[$selectedType])) {
            $profiles = $profiles->where('type', $selectedType)->values();
        } else {
            $selectedType = 'all';
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $profiles = new LengthAwarePaginator(
            $profiles->forPage($currentPage, $perPage)->values(),
            $profiles->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => request()->query()]
        );

        return $this->page('dashboard', 'Dashboard')->with([
            'myProfiles' => $profiles,
            'selectedProfileType' => $selectedType,
        ])->with($this->dashboardActivityData());
    }

    public function createProfile(): View
    {
        return view('dashboard.profile-create', [
            'dashboardTitle' => 'Create Profile',
        ]);
    }

    public function createProfileForm(string $type): View
    {
        abort_unless(isset(self::PROFILE_TYPES[$type]), 404);

        $user = Auth::user();
        $defaults = [
            $this->registrationField($type, 'name') => $user->name,
            $this->registrationField($type, 'email') => $user->email,
            $this->registrationField($type, 'mobile') => $user->mobile ?? '',
            $this->registrationField($type, 'company') => $user->company_name ?? '',
        ];
        $locationFields = [
            'business' => 'ofc_city',
            'investor' => 'inv_city',
            'mentor' => 'mentor_city',
            'startup' => 'ofc_city',
        ];
        if (! empty($user->location)) {
            $defaults[$locationFields[$type]] = $user->location;
        }
        $designationFields = [
            'business' => 'seller_designation',
            'mentor' => 'mentor_designation',
            'startup' => 'startup_designation',
        ];
        if (isset($designationFields[$type]) && ! empty($user->designation)) {
            $defaults[$designationFields[$type]] = $user->designation;
        }
        if ($type === 'business' || $type === 'startup') {
            $defaults['ofc_country'] = 'United Kingdom';
        } elseif ($type === 'mentor') {
            $defaults['mentor_country'] = 'United Kingdom';
        } elseif ($type === 'investor') {
            $defaults['inv_country'] = 'United Kingdom';
        }

        return view('pages.'.$type.'-registration', [
            'page' => 'registration',
            'showHeader' => true,
            'showFooter' => true,
            'registrationProfile' => config('registration_profiles.'.$type),
            'registrationType' => $type,
            'profileDefaults' => $defaults,
            'dashboardCreateProfile' => true,
        ]);
    }

    private function registrationField(string $type, string $field): string
    {
        return match ($type) {
            'business' => ['name' => 'seller_name', 'email' => 'seller_email', 'mobile' => 'seller_mobile', 'company' => 'seller_company'][$field],
            'investor' => ['name' => 'inv_name', 'email' => 'inv_email', 'mobile' => 'inv_mobile', 'company' => 'company_name'][$field],
            'mentor' => ['name' => 'mentor_name', 'email' => 'mentor_email', 'mobile' => 'mentor_mobile', 'company' => 'mentor_company'][$field],
            'startup' => ['name' => 'startup_name', 'email' => 'startup_email', 'mobile' => 'startup_mobile', 'company' => 'name_of_entity'][$field],
        };
    }

    public function showMyProfile(string $type, int $id): View
    {
        [$definition, $profile] = $this->ownedProfile($type, $id);

        return view('dashboard.my-profile', [
            'dashboardTitle' => $definition['label'].' Profile',
            'dashboardRoute' => request()->route()->getName(),
            'profileType' => $type,
            'profileLabel' => $definition['label'],
            'profile' => $profile,
            'profileId' => $id,
            'profileItems' => $this->profileItems($type, $definition, $profile),
            'edit' => false,
        ]);
    }

    public function editMyProfile(string $type, int $id): View
    {
        [$definition, $profile] = $this->ownedProfile($type, $id);
        $profileMedia = Schema::hasTable('profile_media')
            ? DB::table('profile_media')
                ->where('user_id', Auth::id())
                ->where('profile_type', $type)
                ->where('profile_id', $id)
                ->get()
                ->groupBy('field_name')
            : collect();

        return view('dashboard.my-profile', [
            'dashboardTitle' => $type === 'business'
                ? 'Manage Business Information'
                : 'Manage '.$definition['label'].' Profile',
            'dashboardRoute' => request()->route()->getName(),
            'profileType' => $type,
            'profileLabel' => $definition['label'],
            'profile' => $profile,
            'profileId' => $id,
            'profileItems' => $this->profileItems($type, $definition, $profile),
            'profileSteps' => $this->editableProfileSteps($type, $definition, $profile),
            'profileMedia' => $profileMedia,
            'edit' => true,
        ]);
    }

    public function updateMyProfile(Request $request, string $type, int $id): RedirectResponse
    {
        [$definition, $profile] = $this->ownedProfile($type, $id);
        $profileSteps = $this->editableProfileSteps($type, $definition, $profile);
        $rules = [];

        foreach ($profileSteps as $step) {
            foreach ($step['fields'] as $field) {
                if (($field['type'] ?? null) === 'checkboxes') {
                    foreach ($field['options'] as $option) {
                        $rules[$option['name']] = ['sometimes', 'boolean'];
                    }

                    continue;
                }

                $name = $field['name'] ?? null;
                if (! $name) {
                    continue;
                }
                if (($field['type'] ?? null) === 'file') {
                    $fileRules = ['nullable', 'file', 'max:1024'];
                    $isImageField = false;
                    if (isset($field['accept'])) {
                        $extensions = array_map(
                            static fn (string $extension): string => ltrim($extension, '.'),
                            explode(',', $field['accept'])
                        );
                        $fileRules[] = 'mimes:'.implode(',', $extensions);
                        $isImageField = array_diff($extensions, ['png', 'jpg', 'jpeg']) === [];
                    }
                    if ($isImageField) {
                        $fileRules[] = 'dimensions:max_width=1800,max_height=1200';
                    }
                    if (! empty($field['multiple'])) {
                        $rules[$name] = ['nullable', 'array', 'max:10'];
                        $rules[$name.'.*'] = $fileRules;
                    } else {
                        $rules[$name] = $fileRules;
                    }

                    continue;
                }

                $fieldRules = [! empty($field['required']) ? 'required' : 'nullable'];
                if (($field['type'] ?? null) === 'email') {
                    $fieldRules[] = 'email';
                } elseif (($field['type'] ?? null) === 'url') {
                    $fieldRules[] = 'url';
                } elseif (($field['type'] ?? null) === 'number') {
                    $fieldRules[] = 'numeric';
                    $fieldRules[] = 'min:0';
                    $fieldRules[] = $name === 'inv_stake'
                        ? 'max:100'
                        : 'max:999999999999.9999';
                } else {
                    $fieldRules[] = 'string';
                    $fieldRules[] = ($field['type'] ?? null) === 'textarea'
                        ? 'max:10000'
                        : 'max:255';
                }

                if (($field['type'] ?? null) === 'select' && isset($field['options'])) {
                    $fieldRules[] = Rule::in(array_keys($field['option_values']));
                }

                $rules[$name] = $fieldRules;
            }
        }

        $validated = $request->validate($rules);
        $updates = [];
        $uploads = [];
        foreach ($profileSteps as $step) {
            foreach ($step['fields'] as $field) {
                if (($field['type'] ?? null) === 'checkboxes') {
                    foreach ($field['options'] as $option) {
                        $updates[$option['name']] = (int) $request->boolean($option['name']);
                    }

                    continue;
                }

                $name = $field['name'] ?? null;
                if (! $name) {
                    continue;
                }
                if (($field['type'] ?? null) === 'file') {
                    $files = $validated[$name] ?? [];
                    if (! is_array($files)) {
                        $files = [$files];
                    }
                    foreach ($files as $fileIndex => $file) {
                        if ($file instanceof UploadedFile) {
                            $uploads[] = [
                                'field_name' => $name,
                                'file_index' => (int) $fileIndex,
                                'file' => $file,
                            ];
                        }
                    }

                    continue;
                }
                if (! array_key_exists($name, $validated)) {
                    continue;
                }
                $value = $validated[$name];
                if (($field['type'] ?? null) === 'select'
                    && isset($field['option_values'])
                    && array_key_exists((string) $value, $field['option_values'])) {
                    $value = $field['option_values'][(string) $value];
                }
                $updates[$name] = $value === '' ? null : $value;
            }
        }

        if ($uploads !== []
            && (blank(config('filesystems.disks.s3.key')) || blank(config('filesystems.disks.s3.secret')))) {
            throw ValidationException::withMessages([
                $uploads[0]['field_name'] => 'Attachments cannot be uploaded because S3 credentials are not configured.',
            ]);
        }
        if ($uploads !== [] && ! Schema::hasTable('profile_media')) {
            throw ValidationException::withMessages([
                $uploads[0]['field_name'] => 'Attachments cannot be saved because profile media storage is unavailable.',
            ]);
        }

        $storedPaths = [];
        try {
            foreach ($uploads as $index => $upload) {
                $file = $upload['file'];
                $isImage = str_starts_with((string) $file->getMimeType(), 'image/');
                $directory = 'profiles/'.$type.'/'.($isImage ? 'images' : 'documents').'/'.now()->format('Y/m');
                $path = $directory.'/'.Str::uuid().'.'.($file->guessExtension() ?: 'bin');
                $storedPath = Storage::disk('s3')->putFileAs($directory, $file, basename($path), [
                    'visibility' => $isImage ? 'public' : 'private',
                ]);
                if (! is_string($storedPath) || $storedPath === '') {
                    throw new \RuntimeException("Unable to store profile attachment [{$fieldName}] in S3.");
                }

                $storedPaths[] = $storedPath;
                $uploads[$index] = [
                    ...$upload,
                    'path' => $storedPath,
                    'is_image' => $isImage,
                    'url' => $isImage ? $this->profileMediaUrl($storedPath) : null,
                ];
            }

            DB::transaction(function () use ($definition, $id, $request, $type, $updates, $uploads): void {
                foreach ($uploads as $upload) {
                    $file = $upload['file'];
                    $fieldName = $upload['field_name'];
                    $mediaId = null;
                    if (Schema::hasTable('profile_media')) {
                        $mediaId = DB::table('profile_media')->insertGetId([
                            'user_id' => $request->user()->user_id,
                            'profile_type' => $type,
                            'profile_id' => $id,
                            'field_name' => $fieldName,
                            'file_index' => $upload['file_index'],
                            'media_type' => $upload['is_image'] ? 'image' : 'document',
                            'disk' => 's3',
                            'object_key' => $upload['path'],
                            'url' => $upload['url'],
                            'original_name' => mb_substr($file->getClientOriginalName(), 0, 255),
                            'mime_type' => (string) $file->getMimeType(),
                            'file_size' => (int) $file->getSize(),
                            'is_public' => $upload['is_image'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $mappedColumns = self::PROFILE_MEDIA_COLUMNS[$type][$fieldName] ?? [];
                    $column = $mappedColumns[$upload['file_index']] ?? null;
                    if ($column && Schema::hasColumn($definition['table'], $column)) {
                        $updates[$column] = $upload['url']
                            ?? ($mediaId ? route('profile-media.download', ['media' => $mediaId]) : $upload['path']);
                    }
                }

                if ($updates !== []) {
                    DB::table($definition['table'])
                        ->where($definition['id'], $id)
                        ->where('user_id', $request->user()->user_id)
                        ->update($updates + ['updated_at' => now()]);
                }
            });
        } catch (Throwable $exception) {
            foreach ($storedPaths as $path) {
                try {
                    Storage::disk('s3')->delete($path);
                } catch (Throwable $cleanupException) {
                    Log::error('Unable to remove a profile attachment after profile update failed.', [
                        'path' => $path,
                        'exception' => $cleanupException->getMessage(),
                    ]);
                }
            }

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            Log::error('Profile update failed.', ['type' => $type, 'id' => $id, 'exception' => $exception]);

            throw ValidationException::withMessages([
                ($uploads[0]['field_name'] ?? 'profile') => 'We could not upload your images or documents right now. Please try again later or submit without attachments.',
            ]);
        }

        return redirect()->route('dashboard.profiles.show', ['type' => $type, 'id' => $id])
            ->with('profile_record_status', $definition['label'].' profile updated successfully.');
    }

    private function ownedProfile(string $type, int $id): array
    {
        abort_unless(isset(self::PROFILE_TYPES[$type]), 404);
        $definition = self::PROFILE_TYPES[$type];
        abort_unless(Schema::hasTable($definition['table']), 404);
        $profile = DB::table($definition['table'])
            ->where($definition['id'], $id)
            ->where('user_id', Auth::id())
            ->first();
        abort_unless($profile, 404);

        return [$definition, $profile];
    }

    private function profileCard(string $type, array $definition, object $profile): object
    {
        $title = $this->firstProfileValue($profile, $definition['title']) ?: $definition['label'].' profile';
        $location = $this->joinedProfileValues($profile, $definition['location']);
        $category = $this->joinedProfileValues($profile, $definition['category']);
        if ($category !== '' && is_numeric($category)
            && Schema::hasTable('industry_categories')
            && Schema::hasColumns('industry_categories', ['cat_id', 'category_name'])) {
            $category = DB::table('industry_categories')
                ->where('cat_id', $category)
                ->value('category_name') ?: $category;
        }
        $summary = $this->firstProfileValue($profile, $definition['summary']);

        return (object) [
            'type' => $type,
            'label' => $definition['label'],
            'id' => (int) $profile->{$definition['id']},
            'title' => $title,
            'location' => $location ?: 'Location not specified',
            'category' => $category,
            'summary' => $summary,
            'status' => (int) ($profile->{$definition['status']} ?? 0) === 1 ? 'Active' : 'Draft',
            'image' => $profile->{$definition['image']} ?? null,
            'default_image' => $definition['default_image'],
        ];
    }

    private function profileItems(string $type, array $definition, object $profile): array
    {
        $labels = [];
        foreach (config("registration_profiles.{$type}.steps", []) as $step) {
            foreach ($step['fields'] as $field) {
                if (isset($field['name'], $field['label'])) {
                    $labels[$field['name']] = $field['label'];
                }
            }
        }

        $hiddenColumns = [
            $definition['id'],
            $definition['key'],
            'user_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'activated_by',
            'activated_at',
            'last_login_at',
            'trackid',
            'utm_source',
            'utm_medium',
            'utm_campaign',
            $definition['status'],
            'membership_paid',
            'membership_plan',
            'mailer_campaign',
        ];
        $items = [];
        foreach ((array) $profile as $column => $value) {
            if (in_array($column, $hiddenColumns, true) || $value === null || $value === '') {
                continue;
            }
            $items[] = [
                'name' => $column,
                'label' => $labels[$column] ?? Str::headline($column),
                'value' => (string) $value,
                'is_url' => (bool) preg_match('#^https?://#i', (string) $value),
            ];
        }

        return $items;
    }

    private function editableProfileSteps(string $type, array $definition, object $profile): array
    {
        $steps = [];
        $columns = Schema::getColumnListing($definition['table']);
        foreach (config("registration_profiles.{$type}.steps", []) as $step) {
            $fields = [];
            foreach ($step['fields'] as $field) {
                if (($field['type'] ?? null) === 'checkboxes') {
                    $field['options'] = array_values(array_filter(
                        $field['options'],
                        fn (array $option): bool => in_array($option['name'], $columns, true)
                    ));
                    if ($field['options'] !== []) {
                        $fields[] = $field;
                    }

                    continue;
                }

                $name = $field['name'] ?? null;
                if (! $name
                    || (($field['type'] ?? null) !== 'file' && ! in_array($name, $columns, true))
                    || in_array($name, [
                        $definition['id'],
                        $definition['key'],
                        'user_id',
                        $definition['status'],
                        'created_at',
                        'updated_at',
                    ], true)) {
                    continue;
                }

                if (($field['type'] ?? null) === 'select' && isset($field['options'])) {
                    $columnType = Schema::getColumnType($definition['table'], $name);
                    $numeric = in_array($columnType, ['integer', 'bigint', 'smallint', 'tinyint'], true);
                    $field['option_values'] = [];
                    $field['form_options'] = [];
                    foreach (array_values($field['options']) as $optionIndex => $option) {
                        $optionValue = (string) $option;
                        if ($numeric && $name === 'industry_sector'
                            && Schema::hasTable('industry_categories')
                            && Schema::hasColumns('industry_categories', ['cat_id', 'category_name'])) {
                            $storedValue = DB::table('industry_categories')
                                ->where('category_name', $optionValue)
                                ->value('cat_id') ?? $optionIndex;
                        } else {
                            $storedValue = $numeric && ! is_numeric($optionValue)
                                ? $optionIndex
                                : $optionValue;
                        }
                        $field['option_values'][$optionValue] = $storedValue;
                        $field['form_options'][] = ['value' => $optionValue, 'label' => (string) $option];
                    }
                    $storedProfileValue = (string) ($profile->{$name} ?? '');
                    $selectedOption = array_search(
                        $storedProfileValue,
                        array_map('strval', $field['option_values']),
                        true
                    );
                    $field['selected_value'] = $selectedOption !== false
                        ? (string) $selectedOption
                        : $storedProfileValue;
                } else {
                    $field['selected_value'] = (string) ($profile->{$name} ?? '');
                }
                $fields[] = $field;
            }
            if ($type === 'business' && $step['title'] === 'Team & Headquarters') {
                foreach ([
                    'Team Details' => array_values(array_filter($fields, fn (array $field): bool => ! str_starts_with($field['name'] ?? '', 'ofc_'))),
                    'Headquarters' => array_values(array_filter($fields, fn (array $field): bool => str_starts_with($field['name'] ?? '', 'ofc_'))),
                ] as $title => $sectionFields) {
                    if ($sectionFields !== []) {
                        $steps[] = ['title' => $title, 'fields' => $sectionFields];
                    }
                }
            } elseif ($type === 'business' && $step['title'] === 'Requirements & Attachments') {
                $attachments = array_values(array_filter($fields, fn (array $field): bool => ($field['type'] ?? null) === 'file'));
                $requirements = array_values(array_filter($fields, fn (array $field): bool => ($field['type'] ?? null) !== 'file'));
                if ($requirements !== []) {
                    $steps[] = ['title' => 'Requirements', 'fields' => $requirements];
                }
                if ($attachments !== []) {
                    $steps[] = ['title' => 'Attachments', 'fields' => $attachments];
                }
            } elseif ($fields !== []) {
                $title = $type === 'business'
                    ? match ($step['title']) {
                        'Confidential Information' => 'Confidential Info',
                        'Advertisement Details' => 'Advert Details',
                        'Business Information' => 'Business Info',
                        default => $step['title'],
                    }
                : $step['title'];
                $steps[] = ['title' => $title, 'fields' => $fields];
            }
        }

        return $steps;
    }

    private function profileMediaUrl(string $path): string
    {
        $cdnUrl = trim((string) config('filesystems.cdn_url'));
        if ($cdnUrl !== '') {
            return rtrim($cdnUrl, '/').'/'.ltrim($path, '/');
        }

        return Storage::disk('s3')->url($path);
    }

    private function firstProfileValue(object $profile, array $columns): ?string
    {
        foreach ($columns as $column) {
            $value = $profile->{$column} ?? null;
            if ($value !== null && $value !== '') {
                return (string) $value;
            }
        }

        return null;
    }

    private function joinedProfileValues(object $profile, array $columns): string
    {
        return collect($columns)
            ->map(fn (string $column): ?string => $profile->{$column} ?? null)
            ->filter(fn (?string $value): bool => $value !== null && $value !== '')
            ->unique()
            ->implode(' · ');
    }

    public function profile(): View
    {
        return $this->page('dashboard-profile', 'Edit Profile')
            ->with('account', Auth::user());
    }

    public function myPlan(): View
    {
        $activeMemberships = collect();

        if (Schema::hasTable('profile_memberships')
            && Schema::hasColumns('profile_memberships', [
                'user_id', 'membership_type', 'profile_type', 'profile_id', 'amount',
                'interaction_credits', 'instant_responses', 'activation_date',
                'expiry_date', 'is_active', 'created_at',
            ])) {
            $memberships = DB::table('profile_memberships')
                ->where('profile_memberships.user_id', Auth::id())
                ->where('profile_memberships.is_active', 1)
                ->where(function ($query): void {
                    $query->whereNull('profile_memberships.expiry_date')
                        ->orWhereDate('profile_memberships.expiry_date', '>=', now()->toDateString());
                });

            if (Schema::hasTable('membership_plans')) {
                $memberships->leftJoin(
                    'membership_plans',
                    'membership_plans.plan_id',
                    '=',
                    'profile_memberships.membership_type'
                )->select('profile_memberships.*', 'membership_plans.plan_name');
            } else {
                $memberships->select('profile_memberships.*');
            }

            $activeMemberships = $memberships
                ->orderByDesc('profile_memberships.activation_date')
                ->get();
        }

        $availablePlans = [
            [
                'key' => 'free',
                'name' => 'Free',
                'description' => 'Get started with the essential membership features.',
                'price' => '£0',
                'period' => 'Forever',
                'benefits' => [
                    'Create a profile',
                    'Basic directory listing',
                    'Connect with members',
                ],
            ],
            [
                'key' => 'premium',
                'name' => 'Premium',
                'description' => 'Grow your business with premium tools.',
                'price' => '£29',
                'period' => 'Month',
                'benefits' => [
                    'Everything in Free',
                    'Verified member badge',
                    'Receive leads and inquiries',
                    'Featured directory listing',
                    'Access to trade opportunities',
                    'Business matchmaking',
                    'Event invitations and discounts',
                    'Email support',
                ],
            ],
            [
                'key' => 'gold',
                'name' => 'Gold',
                'description' => 'Get greater visibility and dedicated support.',
                'price' => '£89',
                'period' => 'Month',
                'benefits' => [
                    'Everything in Premium',
                    'Top placement in directory',
                    'Homepage featured listing',
                    'Priority leads and inquiries',
                    'Global exposure and promotion',
                    'Custom business page',
                    'Dedicated account manager',
                    'Priority support',
                ],
            ],
            [
                'key' => 'platinum',
                'name' => 'Platinum',
                'description' => 'Every membership feature, included.',
                'price' => 'Custom',
                'period' => 'Contact us',
                'benefits' => [
                    'Everything in Gold',
                    'All Premium features',
                    'All Gold features',
                    'Priority support and account management',
                ],
            ],
        ];

        return $this->page('my-plan', 'My Plan')->with([
            'activeMemberships' => $activeMemberships,
            'availablePlans' => $availablePlans,
            'profileTypeLabels' => [
                1 => 'Business',
                2 => 'Investor',
                3 => 'Lender',
                4 => 'Mentor',
                5 => 'Incubator',
                6 => 'Broker',
                7 => 'Startup',
            ],
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $account = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user_account', 'email')->ignore($account->user_id, 'user_id'),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'location' => ['required', 'string', 'max:60'],
            'designation' => ['required', 'string', 'max:60'],
            'company' => ['required', 'string', 'max:255'],
        ]);

        $emailChanged = mb_strtolower(trim($validated['email'])) !== mb_strtolower($account->email);
        $account->forceFill([
            'name' => trim($validated['name']),
            'email' => mb_strtolower(trim($validated['email'])),
            'mobile' => trim($validated['phone']),
            'location' => trim($validated['location']),
            'designation' => trim($validated['designation']),
            'company_name' => trim($validated['company']),
            ...($emailChanged ? ['email_verified_at' => null] : []),
        ])->save();

        if ($emailChanged) {
            try {
                Mail::to($account->email)->queue(new VerifyAccountEmail($account));
            } catch (Throwable $exception) {
                Log::error('BusinessX updated account verification email could not be queued.', [
                    'user_id' => $account->user_id,
                    'exception' => $exception,
                ]);

                return redirect()->route('dashboard.profile')->with(
                    'profile_error',
                    'Your profile was updated, but we could not queue verification for your new email. Please request a verification email from the sign-in page.'
                );
            }

            return redirect()->route('dashboard.profile')->with(
                'profile_status',
                'Your profile was updated. Please verify your new email address before signing in again.'
            );
        }

        return redirect()->route('dashboard.profile')->with(
            'profile_status',
            'Your profile was updated successfully.'
        );
    }

    public function password(): View
    {
        return $this->page('dashboard-password', 'Change Password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'oldPassword' => ['required', 'string'],
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Za-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
                'different:oldPassword',
            ],
            'confirmPassword' => ['required', 'same:newPassword'],
        ], [
            'newPassword.min' => 'The new password must be at least 8 characters.',
            'newPassword.regex' => 'The new password must contain a letter, a number, and a symbol.',
            'newPassword.different' => 'The new password must be different from your old password.',
            'confirmPassword.same' => 'The password confirmation does not match.',
        ]);

        $account = $request->user();
        if (! $account->password || ! Hash::check($validated['oldPassword'], $account->password)) {
            throw ValidationException::withMessages([
                'oldPassword' => 'The old password you entered is incorrect.',
            ]);
        }

        $account->forceFill(['password' => $validated['newPassword']])->save();

        return redirect()->route('dashboard.password')->with(
            'password_status',
            'Your password was updated successfully.'
        );
    }

    public function inbox(): View
    {
        return $this->interaction('inbox', 'BX Inbox')->with('conversations', $this->conversationsForUser());
    }

    public function activity(): JsonResponse
    {
        return response()->json($this->dashboardActivityData());
    }

    public function markNotificationRead(string $notification): JsonResponse
    {
        $record = Auth::user()->notifications()
            ->whereKey($notification)
            ->firstOrFail();
        $record->markAsRead();

        return response()->json(['marked_read' => true]);
    }

    public function markNotificationsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['marked_read' => true]);
    }

    public function liveChatConversations(): JsonResponse
    {
        $conversations = $this->conversationsForUser()->map(fn (object $conversation): array => [
            'id' => (int) $conversation->id,
            'counterpart' => $conversation->counterpart,
            'profile_label' => $conversation->profile_label,
            'last_message' => $conversation->last_message,
            'updated_at' => Carbon::parse($conversation->updated_at)->toIso8601String(),
            'messages_url' => route('dashboard.live-chat.messages', $conversation->id),
            'reply_url' => route('dashboard.inbox.reply', $conversation->id),
        ]);

        return response()->json(['conversations' => $conversations]);
    }

    public function liveChatMessages(int $conversation): JsonResponse
    {
        $thread = DB::table('profile_contact_conversations')
            ->where('id', $conversation)
            ->where(function ($query): void {
                $query->where('owner_user_id', Auth::id())
                    ->orWhere('sender_user_id', Auth::id());
            })
            ->first();
        abort_unless($thread, 404);
        $this->markConversationAsRead((int) $thread->id);

        $messages = DB::table('profile_contact_messages')
            ->join('user_account', 'user_account.user_id', '=', 'profile_contact_messages.sender_user_id')
            ->where('conversation_id', $thread->id)
            ->orderByDesc('profile_contact_messages.id')
            ->limit(100)
            ->get([
                'profile_contact_messages.id',
                'profile_contact_messages.conversation_id',
                'profile_contact_messages.sender_user_id',
                'user_account.name as sender_name',
                'profile_contact_messages.message',
                'profile_contact_messages.created_at',
            ])
            ->reverse()
            ->values()
            ->map(fn (object $message): array => [
                'id' => (int) $message->id,
                'conversation_id' => (int) $message->conversation_id,
                'sender_user_id' => (int) $message->sender_user_id,
                'sender_name' => $message->sender_name,
                'message' => $message->message,
                'created_at' => Carbon::parse($message->created_at)->toIso8601String(),
            ]);

        return response()->json(['messages' => $messages]);
    }

    private function conversationsForUser(): Collection
    {
        $latestMessages = DB::table('profile_contact_messages')
            ->selectRaw('conversation_id, MAX(id) as message_id')
            ->groupBy('conversation_id');
        $conversations = DB::table('profile_contact_conversations as conversations')
            ->leftJoinSub($latestMessages, 'latest', 'latest.conversation_id', '=', 'conversations.id')
            ->leftJoin('profile_contact_messages as messages', 'messages.id', '=', 'latest.message_id')
            ->where(function ($query): void {
                $query->where('conversations.owner_user_id', Auth::id())
                    ->orWhere('conversations.sender_user_id', Auth::id());
            })
            ->orderByDesc('conversations.updated_at')
            ->limit(100)
            ->get([
                'conversations.*',
                'messages.message as last_message',
                'messages.sender_user_id as last_sender_user_id',
            ])
            ->map(function (object $conversation): object {
                $conversation->profile_label = $this->profileConversationLabel(
                    $conversation->profile_type,
                    (int) $conversation->profile_id
                );
                if ((int) $conversation->sender_user_id === (int) Auth::id()) {
                    $conversation->counterpart = DB::table('user_account')
                        ->where('user_id', $conversation->owner_user_id)
                        ->value('company_name') ?: 'Profile owner';
                } else {
                    $conversation->counterpart = $conversation->sender_name;
                }

                return $conversation;
            });

        return $conversations;
    }

    public function showConversation(int $conversation): View
    {
        $thread = DB::table('profile_contact_conversations')
            ->where('id', $conversation)
            ->where(function ($query): void {
                $query->where('owner_user_id', Auth::id())
                    ->orWhere('sender_user_id', Auth::id());
            })
            ->first();
        abort_unless($thread, 404);
        $this->markConversationAsRead((int) $thread->id);

        $thread->is_owner = (int) $thread->owner_user_id === (int) Auth::id();
        $thread->counterpart = $thread->is_owner
            ? $thread->sender_name
            : (DB::table('user_account')->where('user_id', $thread->owner_user_id)->value('company_name') ?: 'Profile owner');
        $thread->profile_label = $this->profileConversationLabel($thread->profile_type, (int) $thread->profile_id);
        $messages = DB::table('profile_contact_messages')
            ->where('conversation_id', $thread->id)
            ->orderBy('id')
            ->get();
        $proposals = DB::table('profile_proposals')
            ->where('conversation_id', $thread->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (object $proposal): object => $this->attachProposalFiles($proposal));

        return $this->interaction('inbox', 'BX Inbox')->with([
            'conversation' => $thread,
            'messages' => $messages,
            'proposals' => $proposals,
        ]);
    }

    public function replyToConversation(Request $request, int $conversation): RedirectResponse|JsonResponse
    {
        $thread = DB::table('profile_contact_conversations')
            ->where('id', $conversation)
            ->where(function ($query) use ($request): void {
                $query->where('owner_user_id', $request->user()->user_id)
                    ->orWhere('sender_user_id', $request->user()->user_id);
            })
            ->first();
        abort_unless($thread, 404);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:10000'],
        ]);
        $messageId = DB::transaction(function () use ($request, $thread, $validated): int {
            $messageId = (int) DB::table('profile_contact_messages')->insertGetId([
                'conversation_id' => $thread->id,
                'sender_user_id' => $request->user()->user_id,
                'message' => trim($validated['message']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('profile_contact_conversations')
                ->where('id', $thread->id)
                ->update(['updated_at' => now()]);

            return $messageId;
        });

        $createdMessage = DB::table('profile_contact_messages')->where('id', $messageId)->first();
        $senderName = DB::table('user_account')
            ->where('user_id', $request->user()->user_id)
            ->value('name') ?: 'BusinessX member';
        $recipientId = (int) $thread->owner_user_id === (int) $request->user()->user_id
            ? (int) $thread->sender_user_id
            : (int) $thread->owner_user_id;
        UserAccount::query()->findOrFail($recipientId)->notify(new DashboardActivityNotification(
            'message',
            'New message from '.$senderName,
            Str::limit($createdMessage->message, 180),
            route('dashboard.inbox.conversation', $thread->id),
            (int) $thread->id,
        ));
        try {
            broadcast(new ConversationMessageSent(
                $messageId,
                (int) $thread->id,
                (int) $request->user()->user_id,
                $senderName,
                $createdMessage->message,
                Carbon::parse($createdMessage->created_at)->toIso8601String(),
            ));
        } catch (Throwable $exception) {
            Log::error('A saved inbox message could not be broadcast in real time.', [
                'conversation_id' => $thread->id,
                'message_id' => $messageId,
                'exception' => $exception,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => [
                    'id' => $messageId,
                    'conversation_id' => (int) $thread->id,
                    'sender_user_id' => (int) $request->user()->user_id,
                    'sender_name' => $senderName,
                    'message' => $createdMessage->message,
                    'created_at' => Carbon::parse($createdMessage->created_at)->toIso8601String(),
                ],
            ], 201);
        }

        return redirect()->route('dashboard.inbox.conversation', $thread->id)
            ->with('status', 'Your reply was sent.');
    }

    public function sendProposal(Request $request, int $conversation): RedirectResponse
    {
        $thread = DB::table('profile_contact_conversations')
            ->where('id', $conversation)
            ->where('owner_user_id', $request->user()->user_id)
            ->first();
        abort_unless($thread, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'amount' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,png,jpg,jpeg'],
        ]);
        $this->createProposal($request, $thread, $validated);

        return redirect()->route('dashboard.inbox.conversation', $thread->id)
            ->with('status', 'Your proposal was sent to the interested member.');
    }

    public function sendSelectedProfileProposal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile' => ['required', 'string', 'regex:/^(business|investor|mentor|startup):[1-9][0-9]*$/'],
            'recipient_user_id' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'amount' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,png,jpg,jpeg'],
        ]);

        [$type, $rawProfileId] = explode(':', $validated['profile'], 2);
        $profileId = (int) $rawProfileId;
        $definition = self::PROFILE_TYPES[$type];
        abort_unless(Schema::hasTable($definition['table'])
            && Schema::hasColumns($definition['table'], [
                $definition['id'],
                'user_id',
                $definition['status'],
            ]), 404);
        $ownedProfile = DB::table($definition['table'])
            ->where($definition['id'], $profileId)
            ->where('user_id', $request->user()->user_id)
            ->where($definition['status'], 1)
            ->first();
        abort_unless($ownedProfile, 404);

        $thread = DB::table('profile_contact_conversations')
            ->where('owner_user_id', $request->user()->user_id)
            ->where('sender_user_id', (int) $validated['recipient_user_id'])
            ->where('profile_type', $type)
            ->where('profile_id', $profileId)
            ->orderByDesc('updated_at')
            ->first();
        abort_unless($thread, 404);

        $this->createProposal($request, $thread, $validated);

        return redirect()->route('dashboard.inbox.conversation', $thread->id)
            ->with('status', 'Your proposal was sent to the selected interested member.');
    }

    public function downloadProposalAttachment(int $proposal, int $attachment): RedirectResponse
    {
        $proposalRecord = DB::table('profile_proposals')
            ->where('id', $proposal)
            ->where(function ($query): void {
                $query->where('sender_user_id', Auth::id())
                    ->orWhere('recipient_user_id', Auth::id());
            })
            ->first();
        abort_unless($proposalRecord, 404);

        $file = DB::table('profile_proposal_attachments')
            ->where('id', $attachment)
            ->where('proposal_id', $proposalRecord->id)
            ->first();
        abort_unless($file, 404);

        $url = Storage::disk($file->disk)->temporaryUrl(
            $file->object_key,
            now()->addMinutes(5),
            ['ResponseContentDisposition' => HeaderUtils::makeDisposition('attachment', $file->original_name)]
        );

        return redirect()->away($url);
    }

    public function updateProposalStatus(Request $request, int $proposal): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['accepted', 'declined'])],
        ]);
        $proposalRecord = DB::table('profile_proposals')
            ->where('id', $proposal)
            ->where('recipient_user_id', $request->user()->user_id)
            ->where('status', 'pending')
            ->first();
        abort_unless($proposalRecord, 404);

        DB::table('profile_proposals')
            ->where('id', $proposalRecord->id)
            ->where('recipient_user_id', $request->user()->user_id)
            ->where('status', 'pending')
            ->update([
                'status' => $validated['status'],
                'responded_at' => now(),
                'updated_at' => now(),
            ]);
        DB::table('profile_contact_conversations')
            ->where('id', $proposalRecord->conversation_id)
            ->update(['updated_at' => now()]);
        UserAccount::query()->findOrFail((int) $proposalRecord->sender_user_id)
            ->notify(new DashboardActivityNotification(
                'proposal_status',
                'Proposal '.$validated['status'],
                'Your proposal was '.$validated['status'].'.',
                route('dashboard.inbox.conversation', $proposalRecord->conversation_id),
                (int) $proposalRecord->conversation_id,
            ));

        return redirect()->route('dashboard.inbox.conversation', $proposalRecord->conversation_id)
            ->with('status', 'Proposal '.$validated['status'].'.');
    }

    public function sentProposals(): View
    {
        return $this->proposalList('sent', 'Proposal sent');
    }

    public function receivedProposals(): View
    {
        return $this->proposalList('received', 'Proposal received');
    }

    public function instantResponse(): View
    {
        $settings = DB::table('profile_instant_responses')
            ->where('user_id', Auth::id())
            ->first();

        return $this->interaction('instant-response', 'Instant Response')->with([
            'instantResponseEnabled' => (bool) ($settings->enabled ?? false),
            'instantResponseMessage' => $settings->message
                ?? 'Thank you for your message. We have received it and will get back to you as soon as possible.',
        ]);
    }

    public function saveInstantResponse(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'message' => ['required', 'string', 'max:500'],
        ]);
        $now = now();
        $settings = DB::table('profile_instant_responses')
            ->where('user_id', $request->user()->user_id)
            ->exists();
        $values = [
            'enabled' => $request->boolean('enabled'),
            'message' => trim($validated['message']),
            'updated_at' => $now,
        ];
        if ($settings) {
            DB::table('profile_instant_responses')
                ->where('user_id', $request->user()->user_id)
                ->update($values);
        } else {
            DB::table('profile_instant_responses')->insert($values + [
                'user_id' => $request->user()->user_id,
                'created_at' => $now,
            ]);
        }

        return redirect()->route('dashboard.instant-response')
            ->with('status', 'Your instant response settings were saved.');
    }

    private function page(string $view, string $title): View
    {
        return view("dashboard.{$view}", [
            'dashboardTitle' => $title,
            'dashboardRoute' => request()->route()->getName(),
        ]);
    }

    private function interaction(string $key, string $title): View
    {
        return view('dashboard.interaction', [
            'interactionPageKey' => $key,
            'dashboardTitle' => $title,
            'dashboardRoute' => request()->route()->getName(),
        ]);
    }

    private function dashboardActivityData(): array
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($notification): array {
                $data = $notification->data;

                return [
                    'id' => (string) $notification->id,
                    'type' => (string) ($data['type'] ?? 'activity'),
                    'title' => (string) ($data['title'] ?? 'Account activity'),
                    'message' => (string) ($data['message'] ?? ''),
                    'url' => (string) ($data['url'] ?? route('dashboard.inbox')),
                    'created_at' => Carbon::parse($notification->created_at)->toIso8601String(),
                    'read_at' => $notification->read_at
                        ? Carbon::parse($notification->read_at)->toIso8601String()
                        : null,
                    'read_url' => route('dashboard.notifications.read', $notification->id),
                ];
            })
            ->values()
            ->all();

        return [
            'notifications' => $notifications,
            'unreadNotificationCount' => $user->unreadNotifications()->count(),
            'unreadMessageCount' => $this->unreadMessageCount((int) $user->getAuthIdentifier()),
            'activityUrl' => route('dashboard.activity'),
            'markAllNotificationsReadUrl' => route('dashboard.notifications.read-all'),
        ];
    }

    private function unreadMessageCount(int $userId): int
    {
        return (int) DB::table('profile_contact_messages as messages')
            ->join('profile_contact_conversations as conversations', 'conversations.id', '=', 'messages.conversation_id')
            ->leftJoin('profile_contact_conversation_reads as reads', function ($join) use ($userId): void {
                $join->on('reads.conversation_id', '=', 'conversations.id')
                    ->where('reads.user_id', '=', $userId);
            })
            ->where(function ($query) use ($userId): void {
                $query->where('conversations.owner_user_id', $userId)
                    ->orWhere('conversations.sender_user_id', $userId);
            })
            ->where('messages.sender_user_id', '<>', $userId)
            ->whereRaw('messages.id > COALESCE(reads.last_read_message_id, 0)')
            ->count();
    }

    private function markConversationAsRead(int $conversationId): void
    {
        $lastMessageId = DB::table('profile_contact_messages')
            ->where('conversation_id', $conversationId)
            ->max('id');
        if ($lastMessageId === null) {
            return;
        }

        $read = [
            'last_read_message_id' => (int) $lastMessageId,
            'updated_at' => now(),
        ];
        $exists = DB::table('profile_contact_conversation_reads')
            ->where('conversation_id', $conversationId)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            DB::table('profile_contact_conversation_reads')
                ->where('conversation_id', $conversationId)
                ->where('user_id', Auth::id())
                ->update($read);
        } else {
            DB::table('profile_contact_conversation_reads')->insert($read + [
                'conversation_id' => $conversationId,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }
    }

    private function profileConversationLabel(string $type, int $id): string
    {
        $definition = self::PROFILE_TYPES[$type] ?? null;
        if (! $definition || ! Schema::hasTable($definition['table'])) {
            return Str::title($type).' profile #'.$id;
        }

        $profile = DB::table($definition['table'])
            ->where($definition['id'], $id)
            ->first();
        $title = $profile ? $this->firstProfileValue($profile, $definition['title']) : null;

        return $title ?: Str::title($type).' profile #'.$id;
    }

    private function proposalList(string $direction, string $title): View
    {
        $status = request()->query('status', 'all');
        if (! in_array($status, ['all', 'pending', 'accepted', 'declined'], true)) {
            $status = 'all';
        }

        $query = DB::table('profile_proposals');
        $query->where(
            $direction === 'sent' ? 'sender_user_id' : 'recipient_user_id',
            Auth::id()
        );
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        $proposals = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $proposals->getCollection()->transform(function (object $proposal) use ($direction): object {
            $otherUserId = $direction === 'sent'
                ? $proposal->recipient_user_id
                : $proposal->sender_user_id;
            $account = DB::table('user_account')->where('user_id', $otherUserId)->first([
                'name',
                'company_name',
            ]);
            $proposal->counterpart = $account
                ? ($account->company_name ?: $account->name)
                : 'BusinessX member';
            $proposal->profile_title = $this->profileConversationLabel(
                $proposal->profile_type,
                (int) $proposal->profile_id
            );

            return $this->attachProposalFiles($proposal);
        });

        $viewData = [
            'proposals' => $proposals,
            'selectedProposalStatus' => $status,
        ];
        if ($direction === 'sent') {
            [$viewData['proposalProfiles'], $viewData['proposalRecipients']] = $this->proposalComposerOptions();
        }

        return $this->interaction($direction, $title)->with($viewData);
    }

    private function createProposal(Request $request, object $thread, array $validated): int
    {
        $storedObjects = [];
        try {
            if (! empty($validated['attachments'])
                && (! config('filesystems.disks.s3.key') || ! config('filesystems.disks.s3.secret'))) {
                throw ValidationException::withMessages([
                    'attachments' => 'Proposal attachments cannot be uploaded because AWS_ACCESS_KEY_ID or AWS_SECRET_ACCESS_KEY is missing from the application environment. Add valid IAM credentials and clear the configuration cache.',
                ]);
            }

            foreach ($validated['attachments'] ?? [] as $file) {
                if (! $file instanceof UploadedFile) {
                    continue;
                }

                $extension = $file->guessExtension() ?: 'bin';
                $path = 'proposals/'.$request->user()->user_id.'/'.now()->format('Y/m').'/'.Str::uuid().'.'.$extension;
                $storedPath = Storage::disk('s3')->putFileAs(
                    dirname($path),
                    $file,
                    basename($path),
                    ['visibility' => 'private']
                );
                if (! is_string($storedPath) || $storedPath === '') {
                    throw new \RuntimeException('Unable to store proposal attachment in S3.');
                }
                $storedObjects[] = [
                    'object_key' => $storedPath,
                    'original_name' => mb_substr($file->getClientOriginalName(), 0, 255),
                    'mime_type' => (string) $file->getMimeType(),
                    'file_size' => (int) $file->getSize(),
                ];
            }

            return DB::transaction(function () use ($request, $thread, $validated, $storedObjects): int {
                $now = now();
                $proposalId = DB::table('profile_proposals')->insertGetId([
                    'conversation_id' => $thread->id,
                    'profile_type' => $thread->profile_type,
                    'profile_id' => $thread->profile_id,
                    'sender_user_id' => $request->user()->user_id,
                    'recipient_user_id' => $thread->sender_user_id,
                    'title' => trim($validated['title']),
                    'description' => trim($validated['description']),
                    'amount' => $validated['amount'] ?? null,
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($storedObjects as $storedObject) {
                    DB::table('profile_proposal_attachments')->insert([
                        'proposal_id' => $proposalId,
                        'uploaded_by_user_id' => $request->user()->user_id,
                        'disk' => 's3',
                        ...$storedObject,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                UserAccount::query()->findOrFail((int) $thread->sender_user_id)
                    ->notify(new DashboardActivityNotification(
                        'proposal',
                        'New proposal received',
                        Str::limit(trim($validated['title']), 180),
                        route('dashboard.inbox.conversation', $thread->id),
                        (int) $thread->id,
                    ));

                DB::table('profile_contact_conversations')
                    ->where('id', $thread->id)
                    ->update(['updated_at' => $now]);

                return (int) $proposalId;
            });
        } catch (Throwable $exception) {
            foreach ($storedObjects as $storedObject) {
                try {
                    Storage::disk('s3')->delete($storedObject['object_key']);
                } catch (Throwable $cleanupException) {
                    Log::error('Unable to remove a proposal attachment after proposal creation failed.', [
                        'object_key' => $storedObject['object_key'],
                        'exception' => $cleanupException->getMessage(),
                    ]);
                }
            }
            throw $exception;
        }
    }

    private function attachProposalFiles(object $proposal): object
    {
        $proposal->attachments = DB::table('profile_proposal_attachments')
            ->where('proposal_id', $proposal->id)
            ->orderBy('id')
            ->get();

        return $proposal;
    }

    private function proposalComposerOptions(): array
    {
        $profiles = collect();
        foreach (self::PROFILE_TYPES as $type => $definition) {
            if (! Schema::hasTable($definition['table'])
                || ! Schema::hasColumns($definition['table'], [
                    $definition['id'],
                    'user_id',
                    $definition['status'],
                ])) {
                continue;
            }

            foreach (DB::table($definition['table'])
                ->where('user_id', Auth::id())
                ->where($definition['status'], 1)
                ->get() as $profile) {
                $profiles->push((object) [
                    'value' => $type.':'.$profile->{$definition['id']},
                    'label' => $this->profileConversationLabel($type, (int) $profile->{$definition['id']}),
                ]);
            }
        }

        $recipients = DB::table('profile_contact_conversations as conversations')
            ->join('user_account as interested_users', 'interested_users.user_id', '=', 'conversations.sender_user_id')
            ->where('conversations.owner_user_id', Auth::id())
            ->where('interested_users.is_active', 1)
            ->orderBy('interested_users.company_name')
            ->orderBy('interested_users.name')
            ->get([
                'conversations.profile_type',
                'conversations.profile_id',
                'interested_users.user_id as recipient_user_id',
                'interested_users.name',
                'interested_users.company_name',
                'interested_users.email',
            ])
            ->unique(fn (object $recipient): string => $recipient->profile_type.':'.$recipient->profile_id.':'.$recipient->recipient_user_id)
            ->map(fn (object $recipient): object => (object) [
                'profile' => $recipient->profile_type.':'.$recipient->profile_id,
                'user_id' => (int) $recipient->recipient_user_id,
                'label' => ($recipient->company_name ?: $recipient->name).' — '.$recipient->email,
            ])
            ->values();

        return [$profiles, $recipients];
    }
}
