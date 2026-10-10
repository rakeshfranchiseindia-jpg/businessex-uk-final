<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use App\Notifications\DashboardActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileContactController extends Controller
{
    private const PROFILES = [
        'business' => [
            'table' => 'profile_business',
            'id' => 'business_id',
            'status' => 'business_profile_status',
            'title' => ['advmt_headline', 'seller_company'],
            'summary' => ['company_summary', 'seller_intro', 'business_pitch'],
            'location' => ['ofc_city', 'ofc_state', 'ofc_country'],
            'image' => 'seller_prof_pic',
            'fallback' => 'assets/img/default-business-profile.png',
            'label' => 'Business',
            'locked_fields' => [
                'seller_name', 'seller_email', 'seller_mobile',
                'director_name', 'director_designation', 'director_email', 'business_website',
            ],
            'hidden_fields' => ['ofc_address'],
        ],
        'investor' => [
            'table' => 'profile_investor',
            'id' => 'investor_id',
            'status' => 'inv_profile_status',
            'title' => ['inv_headline', 'company_name', 'inv_name'],
            'summary' => ['company_summary', 'inv_abt_urself', 'inv_intro'],
            'location' => ['inv_city', 'company_city', 'inv_country'],
            'image' => 'inv_profile_pic_path',
            'fallback' => 'assets/img/default-investor-profile.png',
            'label' => 'Investor',
            'locked_fields' => ['inv_mobile', 'inv_email', 'linkedin_profile'],
        ],
        'mentor' => [
            'table' => 'profile_mentors',
            'id' => 'mentor_id',
            'status' => 'mentor_profile_status',
            'title' => ['mentor_adv_headline', 'mentor_name'],
            'summary' => ['mentor_profile_summary', 'mentor_intro'],
            'location' => ['mentor_city', 'mentor_location', 'mentor_country'],
            'image' => 'mentor_profile_pic',
            'fallback' => 'assets/img/default-mentor-profile.png',
            'label' => 'Mentor',
            'locked_fields' => ['mentor_mobile', 'mentor_email', 'mentor_linkedin'],
        ],
        'startup' => [
            'table' => 'profile_startups',
            'id' => 'startup_id',
            'status' => 'startup_profile_status',
            'title' => ['advmt_headline', 'name_of_entity', 'startup_name'],
            'summary' => ['company_summary', 'startup_intro', 'business_pitch'],
            'location' => ['ofc_city', 'ofc_state', 'ofc_country'],
            'image' => 'startup_prof_pic',
            'fallback' => 'assets/img/default-startup-profile.png',
            'label' => 'Startup',
            'locked_fields' => [
                'startup_name', 'startup_email', 'startup_mobile',
                'director_name', 'director_designation', 'director_email',
            ],
            'hidden_fields' => ['ofc_address'],
        ],
    ];

    public function show(string $type, int $id): View
    {
        [$definition, $profile] = $this->activeProfile($type, $id);
        $registrationSteps = config("registration_profiles.{$type}.steps", []);
        $fieldLabels = [];
        $fieldToStep = [];
        foreach ($registrationSteps as $stepIndex => $step) {
            foreach ($step['fields'] as $field) {
                if (isset($field['name'], $field['label'])) {
                    $fieldLabels[$field['name']] = $field['label'];
                    $fieldToStep[$field['name']] = $stepIndex;
                }
            }
        }
        $groupedStepCount = 3;
        $sections = [];
        for ($i = 0; $i < min($groupedStepCount, count($registrationSteps)); $i++) {
            $sections[$i] = ['title' => $registrationSteps[$i]['title'], 'items' => []];
        }

        $isOwner = Auth::check() && (int) Auth::id() === (int) $profile->user_id;
        $isUnlocked = $isOwner || $this->viewerHasReply($type, $id, (int) $profile->user_id);
        $lockedFields = $definition['locked_fields'] ?? [];

        $excludedFields = [
            $definition['id'], 'user_id', $definition['status'], 'created_at', 'updated_at',
            'deleted_at', 'business_profile_str', 'inv_profile_str', 'mentor_profile_str',
            'startup_profile_str', 'password', 'email_verified_at', 'membership_paid',
            'membership_plan', 'mailer_campaign', 'activated_by', 'activated_at', 'last_login_at',
            'trackid', 'utm_source', 'utm_medium', 'utm_campaign',
            ...($definition['hidden_fields'] ?? []),
        ];
        $items = [];
        foreach ((array) $profile as $name => $value) {
            $isLockedField = in_array($name, $lockedFields, true);
            if (in_array($name, $excludedFields, true) || $value === null || $value === ''
                || (!$isLockedField && preg_match('/(?:email|mobile|phone|document|attachment|password|token|_pic|_path|membership|mailer|activated|utm_|trackid|contact_)/i', $name))) {
                continue;
            }
            if (is_numeric($value) && $name === 'industry_sector'
                && Schema::hasTable('industry_categories')
                && Schema::hasColumns('industry_categories', ['cat_id', 'category_name'])) {
                $value = DB::table('industry_categories')->where('cat_id', $value)->value('category_name') ?: $value;
            }
            $locked = $isLockedField && !$isUnlocked;
            $item = [
                'label' => $fieldLabels[$name] ?? Str::headline($name),
                'value' => $locked ? '' : (string) $value,
                'is_url' => !$locked && (bool) preg_match('#^https?://#i', (string) $value),
                'locked' => $locked,
            ];

            $stepIndex = $fieldToStep[$name] ?? null;
            if ($stepIndex !== null && $stepIndex < $groupedStepCount) {
                $sections[$stepIndex]['items'][] = $item;
            } else {
                $items[] = $item;
            }
        }
        $sections = array_values(array_filter($sections, fn (array $section): bool => $section['items'] !== []));

        $visibleColumns = fn (array $columns): array => $isUnlocked
            ? $columns
            : array_values(array_diff($columns, $lockedFields));

        $profile->display_title = $this->firstValue($profile, $visibleColumns($definition['title']))
            ?: $definition['label'] . ' profile';
        $profile->display_summary = $this->firstValue($profile, $visibleColumns($definition['summary']));
        $profile->display_location = collect($visibleColumns($definition['location']))
            ->map(fn (string $column): ?string => $profile->{$column} ?? null)
            ->filter(fn (?string $value): bool => $value !== null && $value !== '')
            ->unique()
            ->implode(', ');
        $profile->display_image = $this->imageUrl($profile->{$definition['image']} ?? null, $definition['fallback']);

        $owner = Schema::hasTable('user_account') && isset($profile->user_id)
            ? DB::table('user_account')->where('user_id', $profile->user_id)->first(['user_id'])
            : null;

        return view('pages.profile-contact-detail', [
            'page' => 'listing',
            'showHeader' => true,
            'showFooter' => true,
            'profileType' => $type,
            'profileLabel' => $definition['label'],
            'profile' => $profile,
            'profileId' => $id,
            'profileItems' => $items,
            'profileSections' => $sections,
            'canContact' => $owner !== null && (!Auth::check() || (int) Auth::id() !== (int) $profile->user_id),
            'isUnlocked' => $isUnlocked,
            'isOwner' => $isOwner,
            'contactDefaults' => Auth::user() ? [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->mobile,
            ] : ['name' => '', 'email' => '', 'phone' => ''],
        ]);
    }

    /**
     * The viewer unlocks an owner's locked contact fields once the owner has
     * replied at least once inside their shared conversation about this
     * profile.
     */
    private function viewerHasReply(string $type, int $profileId, int $ownerUserId): bool
    {
        if (!Auth::check() || !Schema::hasTable('profile_contact_conversations') || !Schema::hasTable('profile_contact_messages')) {
            return false;
        }

        $conversationIds = DB::table('profile_contact_conversations')
            ->where('profile_type', $type)
            ->where('profile_id', $profileId)
            ->where('owner_user_id', $ownerUserId)
            ->where('sender_user_id', Auth::id())
            ->pluck('id');

        if ($conversationIds->isEmpty()) {
            return false;
        }

        return DB::table('profile_contact_messages')
            ->whereIn('conversation_id', $conversationIds)
            ->where('sender_user_id', $ownerUserId)
            ->exists();
    }

    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        [$definition, $profile] = $this->activeProfile($type, $id);
        abort_if((int) $profile->user_id === (int) $request->user()->user_id, 403);
        abort_unless(Schema::hasTable('user_account')
            && DB::table('user_account')->where('user_id', $profile->user_id)->exists(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:10000'],
        ]);

        $conversationId = DB::transaction(function () use ($request, $type, $id, $profile, $validated): int {
            $conversationId = DB::table('profile_contact_conversations')->insertGetId([
                'profile_type' => $type,
                'profile_id' => $id,
                'owner_user_id' => $profile->user_id,
                'sender_user_id' => $request->user()->user_id,
                'sender_name' => trim($validated['name']),
                'sender_email' => mb_strtolower(trim($validated['email'])),
                'sender_phone' => trim($validated['phone'] ?? '') ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $initialMessage = trim($validated['message']);
            DB::table('profile_contact_messages')->insert([
                'conversation_id' => $conversationId,
                'sender_user_id' => $request->user()->user_id,
                'message' => $initialMessage,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            UserAccount::query()->findOrFail((int) $profile->user_id)
                ->notify(new DashboardActivityNotification(
                    'message',
                    'New message from ' . trim($validated['name']),
                    Str::limit($initialMessage, 180),
                    route('dashboard.inbox.conversation', $conversationId),
                    (int) $conversationId,
                ));

            $instantResponse = DB::table('profile_instant_responses')
                ->where('user_id', $profile->user_id)
                ->where('enabled', true)
                ->first(['message']);
            if ($instantResponse) {
                DB::table('profile_contact_messages')->insert([
                    'conversation_id' => $conversationId,
                    'sender_user_id' => $profile->user_id,
                    'message' => $instantResponse->message,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $request->user()->notify(new DashboardActivityNotification(
                    'message',
                    'Instant reply received',
                    Str::limit($instantResponse->message, 180),
                    route('dashboard.inbox.conversation', $conversationId),
                    (int) $conversationId,
                ));
            }

            return $conversationId;
        });

        return redirect()->route('dashboard.inbox.conversation', $conversationId)
            ->with('status', "Your message was sent to the {$definition['label']} profile owner.");
    }

    public function start(string $type, int $id): RedirectResponse
    {
        $this->activeProfile($type, $id);

        return redirect()->route('profile-details', ['type' => $type, 'id' => $id])
            ->with('open_contact', true);
    }

    private function activeProfile(string $type, int $id): array
    {
        abort_unless(isset(self::PROFILES[$type]), 404);
        $definition = self::PROFILES[$type];
        abort_unless(Schema::hasTable($definition['table'])
            && Schema::hasColumns($definition['table'], [$definition['id'], 'user_id', $definition['status']]), 404);
        $profile = DB::table($definition['table'])
            ->where($definition['id'], $id)
            ->where($definition['status'], 1)
            ->first();
        abort_unless($profile, 404);

        return [$definition, $profile];
    }

    private function firstValue(object $profile, array $columns): ?string
    {
        foreach ($columns as $column) {
            $value = $profile->{$column} ?? null;
            if ($value !== null && $value !== '') {
                return (string) $value;
            }
        }

        return null;
    }

    private function imageUrl(?string $path, string $fallback): string
    {
        if ($path && preg_match('#^https?://#i', $path)) {
            return $path;
        }
        if ($path) {
            $relativePath = ltrim(str_replace('\\', '/', $path), '/');
            if (!str_contains($relativePath, '..') && is_file(public_path($relativePath))) {
                return asset($relativePath);
            }
        }

        return asset($fallback);
    }
}
