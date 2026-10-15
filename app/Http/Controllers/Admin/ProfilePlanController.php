<?php

namespace App\Http\Controllers\Admin;

use App\Services\Payments\MembershipPaymentService;
use App\Support\Admin\ProfileTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfilePlanController extends Controller
{
    public function __construct(private readonly MembershipPaymentService $memberships) {}

    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        if (! array_key_exists($type, MembershipPaymentService::PROFILE_TABLES)) {
            abort(404);
        }

        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);

        $data = $request->validate([
            'plan' => ['required', 'string', 'in:'.implode(',', array_keys(MembershipPaymentService::PLAN_CODES))],
        ]);

        if (! $profile->user_id) {
            return back()->withErrors(['plan' => 'This profile has no linked user account.']);
        }

        $this->memberships->grantPlan($type, $id, (int) $profile->user_id, $data['plan'], $this->admin()->admin_id);

        return back()->with('success', "{$config['label']} profile #{$id} was granted the ".ucfirst($data['plan']).' plan.');
    }

    private function findProfile(string $type, int $id): Model
    {
        return ProfileTypes::query($type)->findOrFail($id);
    }
}
