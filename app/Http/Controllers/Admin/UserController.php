<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\UserAccount;
use App\Support\Admin\ProfileTypes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = UserAccount::query()->withCount('profiles');

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($keyword) {
                $q->where('user_id', $keyword)
                    ->orWhere('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('mobile', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        // Same split as the legacy "users with / without profile" screens.
        match ($request->query('profile')) {
            'with' => $query->has('profiles'),
            'without' => $query->doesntHave('profiles'),
            default => null,
        };

        if (is_numeric($request->query('source'))) {
            $query->where('reg_source', (int) $request->query('source'));
        }
        if ($from = $request->date('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->date('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $users = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(UserAccount $user): View
    {
        $user->load([
            'profiles' => fn ($q) => $q->latest('created_at'),
            'payments' => fn ($q) => $q->latest('created_at'),
            'memberships' => fn ($q) => $q->latest('created_at'),
        ]);

        // Resolve each user_profiles row to its profile record for display.
        $profiles = $user->profiles->map(function ($row) {
            $slug = ProfileTypes::slugForId((int) $row->profile_type);
            $config = $slug ? ProfileTypes::get($slug) : null;
            $record = $config ? ProfileTypes::query($slug)->find($row->profile_id) : null;

            return (object) [
                'row' => $row,
                'slug' => $slug,
                'label' => $config['label'] ?? 'Unknown',
                'record' => $record,
                'title' => $record && $config ? ($record->{$config['headline']} ?: $record->{$config['name']}) : null,
                'status' => $record && $config ? $record->{$config['status']} : $row->profile_status,
            ];
        });

        return view('admin.users.show', compact('user', 'profiles'));
    }

    public function toggle(UserAccount $user): RedirectResponse
    {
        $user->forceFill(['is_active' => $user->is_active ? 0 : 1])->save();

        return back()->with('success', $user->is_active ? 'User account activated.' : 'User account deactivated.');
    }
}
