<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;
use App\Mail\VerifyAccountEmail;

class AuthController extends Controller
{
    private const PROFILE_TYPES = [
        'business' => [
            'type' => 1,
            'table' => 'profile_business',
            'id' => 'business_id',
            'key' => 'business_profile_str',
            'name' => 'seller_name',
            'email' => 'seller_email',
            'mobile' => 'seller_mobile',
            'company' => 'seller_company',
            'status' => 'business_profile_status',
        ],
        'investor' => [
            'type' => 2,
            'table' => 'profile_investor',
            'id' => 'investor_id',
            'key' => 'inv_profile_str',
            'name' => 'inv_name',
            'email' => 'inv_email',
            'mobile' => 'inv_mobile',
            'company' => 'company_name',
            'status' => 'inv_profile_status',
        ],
        'mentor' => [
            'type' => 4,
            'table' => 'profile_mentors',
            'id' => 'mentor_id',
            'key' => 'mentor_profile_str',
            'name' => 'mentor_name',
            'email' => 'mentor_email',
            'mobile' => 'mentor_mobile',
            'company' => 'mentor_company',
            'status' => 'mentor_profile_status',
        ],
        'startup' => [
            'type' => 7,
            'table' => 'profile_startups',
            'id' => 'startup_id',
            'key' => 'startup_profile_str',
            'name' => 'startup_name',
            'email' => 'startup_email',
            'mobile' => 'startup_mobile',
            'company' => 'name_of_entity',
            'status' => 'startup_profile_status',
        ],
    ];

    private const PROFILE_MEDIA_COLUMNS = [
        'business' => [
            'business_photo_1' => ['seller_prof_pic'],
            'business_photo_2' => ['seller_prof_pic1'],
            'business_photo_3' => ['seller_prof_thumb_pic'],
            'business_photo_4' => ['seller_prof_thumb_pic1'],
            'business_document_1' => ['seller_doc_path'],
            'business_document_2' => ['seller_doc_path1'],
            'business_document_3' => ['seller_doc_path2'],
            'business_document_image' => ['seller_doc_path3'],
        ],
        'investor' => [
            'company_logo' => ['company_logo_path'],
            'profile_pictures' => ['inv_profile_pic_path'],
        ],
        'mentor' => [
            'mentor_profile_image' => ['mentor_profile_pic'],
        ],
        'startup' => [
            'incorporation_certificate' => ['startup_doc_path'],
            'startup_photo_1' => ['startup_prof_pic'],
            'startup_photo_2' => ['startup_prof_pic1'],
            'startup_photo_3' => ['startup_prof_thumb_pic'],
            'startup_photo_4' => ['startup_prof_thumb_pic1'],
        ],
    ];

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $email = Str::lower(trim($credentials['email']));
        $throttleKey = Str::lower($email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Too many sign-in attempts. Please try again in ' . RateLimiter::availableIn($throttleKey) . ' seconds.',
            ]);
        }

        $account = UserAccount::query()
            ->where('email', $email)
            ->where('is_active', 1)
            ->first();

        if (!$account || !$account->password || !Hash::check($credentials['password'], $account->password)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match an active account.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        if (!$account->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => 'Please verify your email address before signing in. Use the form below to send a new verification link.',
            ]);
        }

        Auth::login($account, $request->boolean('remember'));
        $account->forceFill(['last_login_at' => now()])->save();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'));
    }

    public function register(Request $request, string $type): RedirectResponse
    {
        abort_unless(isset(self::PROFILE_TYPES[$type]), 404);

        $profile = self::PROFILE_TYPES[$type];
        $profileFields = config("registration_profiles.{$type}.steps", []);
        $authenticatedAccount = $request->user();
        $emailRules = ['required', 'email', 'max:255'];
        if (! $authenticatedAccount) {
            $emailRules[] = Rule::unique('user_account', 'email');
        }
        $this->ensureRegistrationTables($profile);

        $request->merge([
            $profile['email'] => Str::lower(trim((string) $request->input($profile['email']))),
        ]);

        $rules = array_merge(
            $this->profileRules($profileFields),
            [
                $profile['name'] => ['required', 'string', 'max:100'],
                $profile['email'] => $emailRules,
            ]
        );
        $attributes = [];
        foreach ($profileFields as $step) {
            foreach ($step['fields'] as $field) {
                if (isset($field['name'], $field['label'])) {
                    $attributes[$field['name']] = strtolower($field['label']);
                }
            }
        }
        $attributes[$profile['email']] = 'email';
        $validated = $request->validate($rules, [], $attributes);
        $storedFiles = [];
        $uploadedMedia = [];

        try {
            $uploadedMedia = $this->storeProfileUploads($validated, $profileFields, $type, $storedFiles);

            $account = DB::transaction(function () use ($validated, $profile, $profileFields, $type, $uploadedMedia, $authenticatedAccount): UserAccount {
                $profileKey = $this->uniqueProfileKey($profile);
                $now = now();
                $name = trim($validated[$profile['name']]);
                $email = $validated[$profile['email']];
                $mobile = $validated[$profile['mobile']] ?? null;
                $companyName = trim((string) ($validated[$profile['company']] ?? '')) ?: $name;
                if ($authenticatedAccount) {
                    $userId = (int) $authenticatedAccount->user_id;
                } else {
                    $userId = DB::table('user_account')->insertGetId([
                        'user_rand_id' => $this->uniqueRandomId(),
                        'name' => $name,
                        'email' => $email,
                        'password' => null,
                        'mobile' => $mobile,
                        'company_name' => $companyName,
                        'is_active' => 1,
                        'reg_source' => 1,
                        'reg_profile' => $type,
                        'last_notify_at' => $now,
                        'last_login_at' => $now,
                        'email_verified_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ], 'user_id');
                }

                $profileValues = $this->profileValues(
                    $validated,
                    $profileFields,
                    $profile,
                    (int) $userId,
                    $profileKey,
                    $now
                );

                $profileId = DB::table($profile['table'])->insertGetId($profileValues, $profile['id']);
                $mediaColumns = $this->persistProfileMedia(
                    $uploadedMedia,
                    $type,
                    (int) $userId,
                    (int) $profileId,
                    $profile
                );
                if ($mediaColumns !== []) {
                    DB::table($profile['table'])
                        ->where($profile['id'], $profileId)
                        ->update($mediaColumns + ['updated_at' => $now]);
                }

                if ($type === 'business' && array_filter([
                    $validated['contact_name'] ?? null,
                    $validated['contact_designation'] ?? null,
                    $validated['contact_email'] ?? null,
                ])) {
                    DB::table('profile_business_mgmt')->insert([
                        'business_profile_id' => $profileId,
                        'user_id' => $userId,
                        'mgmt_name' => $validated['contact_name'],
                        'mgmt_designation' => $validated['contact_designation'] ?? null,
                        'mgmt_email' => $validated['contact_email'] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                $userProfileId = DB::table('user_profiles')->insertGetId([
                    'user_id' => $userId,
                    'profile_id' => $profileId,
                    'profile_type' => $profile['type'],
                    'profile_str' => $profileKey,
                    'profile_status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], 'user_prof_id');

                if (Schema::hasTable('profiles')) {
                    DB::table('profiles')->insert([
                        'user_id' => $userId,
                        'profile_type' => $type,
                        'legacy_profile_type' => $profile['type'],
                        'legacy_profile_id' => $profileId,
                        'legacy_profile_key' => $profileKey,
                        'legacy_user_profile_id' => $userProfileId,
                        'status' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                return $authenticatedAccount ?: UserAccount::query()->findOrFail($userId);
            });
        } catch (Throwable $exception) {
            if ($storedFiles !== []) {
                Storage::disk('s3')->delete($storedFiles);
            }

            throw $exception;
        }

        if ($authenticatedAccount) {
            return redirect()->route('dashboard.index')
                ->with('status', Str::title($type).' profile created successfully.');
        }

        try {
            Mail::to($account->email)->queue(new VerifyAccountEmail($account));
        } catch (Throwable $exception) {
            Log::error('BusinessX account verification email could not be queued.', [
                'user_id' => $account->user_id,
                'exception' => $exception,
            ]);

            return redirect()->route("{$type}-registration")->withInput()->with(
                'verification_error',
                'Your account was created, Please request another verification email from the sign-in page.'
            );
        }

        return redirect()->route("{$type}-registration")->withInput()->with(
            'verification_notice',
            'Registration successful. A verification email is queued for delivery. Please check your inbox and verify your email address before signing in.'
        );
    }

    public function quickRegister(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $validated = $request->validate([
            'profile_type' => ['required', 'string', Rule::in(array_keys(self::PROFILE_TYPES))],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('user_account', 'email')],
            'mobile' => ['required', 'string', 'max:20'],
            'company_name' => ['required', 'string', 'max:255'],
            '_quick_registration' => ['required', 'in:1'],
        ]);

        $type = $validated['profile_type'];
        $profile = self::PROFILE_TYPES[$type];
        $this->ensureRegistrationTables($profile);
        $now = now();

        $account = DB::transaction(function () use ($validated, $profile, $type, $now): UserAccount {
            $profileKey = $this->uniqueProfileKey($profile);
            $userId = DB::table('user_account')->insertGetId([
                'user_rand_id' => $this->uniqueRandomId(),
                'name' => trim($validated['name']),
                'email' => $validated['email'],
                'password' => null,
                'mobile' => trim($validated['mobile']),
                'company_name' => trim($validated['company_name']),
                'is_active' => 1,
                'reg_source' => 1,
                'reg_profile' => $type,
                'last_notify_at' => $now,
                'last_login_at' => $now,
                'email_verified_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ], 'user_id');

            $profileValues = [
                $profile['key'] => $profileKey,
                'user_id' => $userId,
                $profile['name'] => trim($validated['name']),
                $profile['email'] => $validated['email'],
                $profile['mobile'] => trim($validated['mobile']),
                $profile['company'] => trim($validated['company_name']),
                $profile['status'] => 0,
                'membership_paid' => 0,
                'membership_plan' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if ($type === 'business') {
                $profileValues['ofc_country'] = 'United Kingdom';
            }
            $profileColumns = Schema::getColumnListing($profile['table']);
            $profileValues = array_intersect_key($profileValues, array_flip($profileColumns));
            $profileId = DB::table($profile['table'])->insertGetId($profileValues, $profile['id']);

            $userProfileId = DB::table('user_profiles')->insertGetId([
                'user_id' => $userId,
                'profile_id' => $profileId,
                'profile_type' => $profile['type'],
                'profile_str' => $profileKey,
                'profile_status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ], 'user_prof_id');

            if (Schema::hasTable('profiles')) {
                DB::table('profiles')->insert([
                    'user_id' => $userId,
                    'profile_type' => $type,
                    'legacy_profile_type' => $profile['type'],
                    'legacy_profile_id' => $profileId,
                    'legacy_profile_key' => $profileKey,
                    'legacy_user_profile_id' => $userProfileId,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            return UserAccount::query()->findOrFail($userId);
        });

        try {
            Mail::to($account->email)->queue(new VerifyAccountEmail($account));
        } catch (Throwable $exception) {
            Log::error('BusinessX quick-registration verification email could not be queued.', [
                'user_id' => $account->user_id,
                'exception' => $exception,
            ]);

            return redirect()->route('home')->withInput([
                '_quick_registration' => '1',
                'profile_type' => $type,
                'email' => $account->email,
                'name' => $account->name,
                'mobile' => $account->mobile,
                'company_name' => $account->company_name,
            ])->with(
                'verification_error',
                'Your account was created, but we could not queue the verification email. Please request another verification email from the sign-in page.'
            );
        }

        return redirect()->route('home')->with(
            'verification_notice',
            'Your account was created. Please check your inbox and verify your email address before signing in.'
        );
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function profileRules(array $steps): array
    {
        $rules = [];

        foreach ($steps as $step) {
            foreach ($step['fields'] as $field) {
                if (($field['type'] ?? null) === 'checkboxes') {
                    foreach ($field['options'] as $option) {
                        $rules[$option['name']] = ['sometimes', 'boolean'];
                    }
                    continue;
                }

                $fieldName = $field['name'] ?? null;
                if (!$fieldName) {
                    continue;
                }

                if (($field['type'] ?? null) === 'file') {
                    $fileRules = ['file', 'max:10240'];
                    if (isset($field['accept'])) {
                        $extensions = array_map(
                            static fn (string $extension): string => ltrim($extension, '.'),
                            explode(',', $field['accept'])
                        );
                        $fileRules[] = 'mimes:' . implode(',', $extensions);
                    }
                    if (!empty($field['multiple'])) {
                        $rules[$fieldName] = ['nullable', 'array', 'max:10'];
                        $rules[$fieldName . '.*'] = $fileRules;
                    } else {
                        $rules[$fieldName] = array_merge(['nullable'], $fileRules);
                    }
                    continue;
                }

                $fieldRules = !empty($field['required']) ? ['required'] : ['nullable'];
                $fieldRules[] = ($field['type'] ?? null) === 'number' ? 'numeric' : 'string';
                if (($field['type'] ?? null) === 'number') {
                    $fieldRules[] = 'min:0';
                    $fieldRules[] = $fieldName === 'inv_stake'
                        ? 'max:100'
                        : 'max:999999999999.9999';
                } else {
                    $fieldRules[] = ($field['type'] ?? null) === 'textarea' ? 'max:10000' : 'max:255';
                }

                if (($field['type'] ?? null) === 'email') {
                    $fieldRules[] = 'email';
                } elseif (($field['type'] ?? null) === 'url') {
                    $fieldRules[] = 'url';
                } elseif (($field['type'] ?? null) === 'select' && isset($field['options'])) {
                    $fieldRules[] = Rule::in(array_map('strval', $field['options']));
                }

                $rules[$fieldName] = $fieldRules;
            }
        }

        return $rules;
    }

    private function profileValues(
        array $validated,
        array $steps,
        array $profile,
        int $userId,
        string $profileKey,
        mixed $now
    ): array {
        $columns = Schema::getColumnListing($profile['table']);
        $values = [
            $profile['key'] => $profileKey,
            'user_id' => $userId,
            $profile['status'] => 0,
            'membership_paid' => 0,
            'membership_plan' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        foreach ($steps as $step) {
            foreach ($step['fields'] as $field) {
                if (($field['type'] ?? null) === 'checkboxes') {
                    foreach ($field['options'] as $option) {
                        $fieldName = $option['name'];
                        if (in_array($fieldName, $columns, true)) {
                            $values[$fieldName] = (int) ($validated[$fieldName] ?? 0);
                        }
                    }
                    continue;
                }

                $fieldName = $field['name'] ?? null;
                if (!$fieldName || !array_key_exists($fieldName, $validated)) {
                    continue;
                }

                $value = $validated[$fieldName];
                if (($field['type'] ?? null) === 'file') {
                    continue;
                }

                if (!in_array($fieldName, $columns, true)) {
                    continue;
                }

                $columnType = Schema::getColumnType($profile['table'], $fieldName);
                if (in_array($columnType, ['integer', 'bigint', 'smallint', 'tinyint'], true)
                    && ($field['type'] ?? null) === 'select'
                    && !is_numeric($value)
                    && isset($field['options'])) {
                    $value = array_search($value, array_map('strval', $field['options']), true);
                }

                $values[$fieldName] = $value;
            }
        }

        $values[$profile['name']] = $validated[$profile['name']];
        $values[$profile['email']] = $validated[$profile['email']];
        if (isset($validated[$profile['mobile']])) {
            $values[$profile['mobile']] = $validated[$profile['mobile']];
        }

        if ($profile['table'] === 'profile_business' && empty($values['ofc_country'])) {
            throw ValidationException::withMessages([
                'ofc_country' => 'Choose the country where your business is based.',
            ]);
        }

        if ($profile['table'] === 'profile_broker') {
            foreach (['company_city', 'company_state', 'company_country', 'ofc_country'] as $requiredLocation) {
                if (empty($values[$requiredLocation])) {
                    throw ValidationException::withMessages([
                        $requiredLocation => 'Complete the required broker location information.',
                    ]);
                }
            }
        }

        return array_intersect_key($values, array_flip($columns));
    }

    private function ensureRegistrationTables(array $profile): void
    {
        $requiredTables = ['user_account', 'user_profiles', 'profile_media', $profile['table']];
        if ($profile['table'] === 'profile_business') {
            $requiredTables[] = 'profile_business_mgmt';
        }

        foreach ($requiredTables as $table) {
            if (!Schema::hasTable($table)) {
                throw new RuntimeException("Registration requires the imported BusinessX table [{$table}]. Import the database schema before creating accounts.");
            }
        }
    }

    private function storeProfileUploads(
        array $validated,
        array $steps,
        string $type,
        array &$storedFiles
    ): array
    {
        $uploads = [];
        foreach ($steps as $step) {
            foreach ($step['fields'] as $field) {
                $name = $field['name'] ?? null;
                if (($field['type'] ?? null) !== 'file' || !$name || !isset($validated[$name])) {
                    continue;
                }

                $files = !empty($field['multiple']) ? $validated[$name] : [$validated[$name]];
                foreach ($files as $index => $file) {
                    if (!$file instanceof UploadedFile) {
                        continue;
                    }

                    $isImage = str_starts_with((string) $file->getMimeType(), 'image/');
                    $kind = $isImage ? 'images' : 'documents';
                    $directory = 'profiles/' . $type . '/' . $kind . '/' . now()->format('Y/m');
                    $extension = $file->guessExtension() ?: 'bin';
                    $filename = Str::uuid()->toString() . '.' . $extension;
                    $path = Storage::disk('s3')->putFileAs(
                        $directory,
                        $file,
                        $filename,
                        ['visibility' => $isImage ? 'public' : 'private']
                    );
                    if (!is_string($path) || $path === '') {
                        throw new RuntimeException("Unable to store profile upload [{$name}] in S3.");
                    }

                    $storedFiles[] = $path;
                    $uploads[] = [
                        'field_name' => $name,
                        'file_index' => (int) $index,
                        'media_type' => $isImage ? 'image' : 'document',
                        'object_key' => $path,
                        'url' => $isImage ? $this->publicMediaUrl($path) : null,
                        'original_name' => mb_substr($file->getClientOriginalName(), 0, 255),
                        'mime_type' => (string) $file->getMimeType(),
                        'file_size' => (int) $file->getSize(),
                        'is_public' => $isImage,
                    ];
                }
            }
        }

        return $uploads;
    }

    private function persistProfileMedia(
        array $uploads,
        string $type,
        int $userId,
        int $profileId,
        array $profile
    ): array {
        $profileValues = [];
        $columns = Schema::getColumnListing($profile['table']);

        foreach ($uploads as $upload) {
            $mediaId = DB::table('profile_media')->insertGetId([
                'user_id' => $userId,
                'profile_type' => $type,
                'profile_id' => $profileId,
                'field_name' => $upload['field_name'],
                'file_index' => $upload['file_index'],
                'media_type' => $upload['media_type'],
                'disk' => 's3',
                'object_key' => $upload['object_key'],
                'url' => null,
                'original_name' => $upload['original_name'],
                'mime_type' => $upload['mime_type'],
                'file_size' => $upload['file_size'],
                'is_public' => $upload['is_public'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $url = $upload['url'] ?? route('profile-media.download', ['media' => $mediaId]);
            DB::table('profile_media')->where('id', $mediaId)->update(['url' => $url]);

            $mappedColumns = self::PROFILE_MEDIA_COLUMNS[$type][$upload['field_name']] ?? [];
            $column = $mappedColumns[$upload['file_index']] ?? null;
            if ($column && in_array($column, $columns, true)) {
                $profileValues[$column] = $url;
            }
        }

        return $profileValues;
    }

    private function publicMediaUrl(string $objectKey): string
    {
        $cdnUrl = trim((string) config('filesystems.cdn_url'));
        if ($cdnUrl !== '') {
            return rtrim($cdnUrl, '/') . '/' . ltrim($objectKey, '/');
        }

        return Storage::disk('s3')->url($objectKey);
    }

    private function uniqueProfileKey(array $profile): string
    {
        do {
            $key = Str::upper(Str::random(20));
        } while (DB::table($profile['table'])->where($profile['key'], $key)->exists());

        return $key;
    }

    private function uniqueRandomId(): string
    {
        do {
            $id = Str::upper(Str::random(20));
        } while (DB::table('user_account')->where('user_rand_id', $id)->exists());

        return $id;
    }
}
