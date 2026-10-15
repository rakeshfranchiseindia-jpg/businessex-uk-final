<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\InstantResponse;
use App\Models\UserAccount;
use App\Notifications\DashboardActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstantResponseController extends Controller
{
    public function index(Request $request): View
    {
        $query = InstantResponse::query()->with('user:user_id,name,email');

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('message', 'like', "%{$keyword}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")));
        }
        if ($request->query('status') !== null && $request->query('status') !== '') {
            $query->where('enabled', (bool) (int) $request->query('status'));
        }

        $rows = $query->latest('updated_at')->paginate($this->perPage())->withQueryString();

        return view('admin.instant-responses.index', compact('rows'));
    }

    /**
     * Disabling here also locks the toggle on the member's own dashboard —
     * saveInstantResponse() refuses to turn it back on while locked — so an
     * admin disable sticks until an admin re-enables it.
     */
    public function toggle(InstantResponse $instantResponse): RedirectResponse
    {
        $nowEnabled = ! $instantResponse->enabled;
        $instantResponse->forceFill([
            'enabled' => $nowEnabled,
            'locked_by_admin' => ! $nowEnabled,
        ])->save();

        $user = UserAccount::find($instantResponse->user_id);
        if ($user) {
            $user->notify(new DashboardActivityNotification(
                'message',
                $nowEnabled ? 'Instant response re-enabled by admin' : 'Instant response disabled by admin',
                $nowEnabled
                    ? 'Your instant response auto-reply has been turned back on by an admin. You can manage it from your dashboard again.'
                    : 'Your instant response auto-reply was turned off by an admin and cannot be re-enabled from your dashboard. Contact support if you believe this is a mistake.',
                route('dashboard.instant-response'),
            ));
        }

        return back()->with('success', $nowEnabled ? 'Instant response enabled.' : 'Instant response disabled and locked.');
    }
}
