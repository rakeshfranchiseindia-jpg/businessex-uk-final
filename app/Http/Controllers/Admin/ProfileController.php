<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ProfileRejection;
use App\Models\Admin\UserProfile;
use App\Support\Admin\ProfileTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request, string $type): View
    {
        $config = ProfileTypes::get($type);
        $tabs = ProfileTypes::statusTabs();
        $tab = array_key_exists($request->query('status'), $tabs) ? $request->query('status') : 'pending';

        $query = ProfileTypes::query($type);

        if ($statuses = $tabs[$tab]['statuses']) {
            $query->whereIn($config['status'], $statuses);
        }

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($config, $keyword) {
                $q->where((new $config['model'])->getKeyName(), $keyword)
                    ->orWhere('user_id', $keyword);

                foreach ($config['search'] as $column) {
                    $q->orWhere($column, 'like', "%{$keyword}%");
                }
            });
        }

        if ($from = $request->date('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->date('to')) {
            $query->whereDate('created_at', '<=', $to);
        }
        if (is_numeric($request->query('paid')) && $type !== 'lender') {
            $query->where('membership_paid', (int) $request->query('paid'));
        }

        $profiles = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        $counts = [];
        foreach ($tabs as $key => $definition) {
            $countQuery = ProfileTypes::query($type);
            if ($definition['statuses']) {
                $countQuery->whereIn($config['status'], $definition['statuses']);
            }
            $counts[$key] = $countQuery->count();
        }

        return view('admin.profiles.index', compact('config', 'profiles', 'tabs', 'tab', 'counts'));
    }

    public function show(string $type, int $id): View
    {
        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);
        $profile->loadMissing('user');

        $rejections = ProfileRejection::query()
            ->where('profile_id', (string) $id)
            ->whereIn('profile_type', [$type, ucfirst($type)])
            ->latest('created_at')
            ->get();

        return view('admin.profiles.show', compact('config', 'profile', 'rejections'));
    }

    public function edit(string $type, int $id): View
    {
        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);

        return view('admin.profiles.edit', compact('config', 'profile'));
    }

    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);

        $rules = [];
        foreach (array_keys($config['editable']) as $field) {
            $rules[$field] = ['nullable', 'string', 'max:5000'];
        }
        $rules[$config['email']] = ['required', 'email', 'max:100'];
        $rules[$config['name']] = ['required', 'string', 'max:100'];

        $profile->fill($request->validate($rules))->save();

        return redirect()
            ->route('admin.profiles.show', [$type, $id])
            ->with('success', "{$config['label']} profile updated.");
    }

    public function approve(string $type, int $id): RedirectResponse
    {
        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);

        $this->setStatus($config, $profile, ProfileTypes::STATUS_ACTIVE, [
            'activated_at' => now(),
            'activated_by' => $this->admin()->admin_id,
        ]);

        return back()->with('success', "{$config['label']} profile #{$id} approved and is now live.");
    }

    public function deactivate(string $type, int $id): RedirectResponse
    {
        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);

        $this->setStatus($config, $profile, ProfileTypes::STATUS_INACTIVE);

        return back()->with('success', "{$config['label']} profile #{$id} deactivated.");
    }

    public function reject(Request $request, string $type, int $id): RedirectResponse
    {
        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:190'],
        ]);

        DB::transaction(function () use ($config, $profile, $type, $id, $data) {
            ProfileRejection::query()->create([
                'profile_type' => $type,
                'profile_id' => (string) $id,
                'admin_email' => $this->admin()->admin_email,
                'rejected_reason' => $data['reason'],
            ]);

            $this->setStatus($config, $profile, ProfileTypes::STATUS_REJECTED);
        });

        return back()->with('success', "{$config['label']} profile #{$id} rejected.");
    }

    private function findProfile(string $type, int $id): Model
    {
        return ProfileTypes::query($type)->findOrFail($id);
    }

    /**
     * Update the profile row and keep user_profiles in sync so the member
     * dashboard reflects the new status.
     */
    private function setStatus(array $config, Model $profile, int $status, array $extra = []): void
    {
        DB::transaction(function () use ($config, $profile, $status, $extra) {
            $profile->forceFill([$config['status'] => $status] + $extra)->save();

            UserProfile::query()
                ->where('profile_id', $profile->getKey())
                ->where('profile_type', $config['id'])
                ->update(['profile_status' => $status]);
        });
    }
}
