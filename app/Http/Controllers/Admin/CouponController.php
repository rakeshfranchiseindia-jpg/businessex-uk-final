<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = Coupon::query();

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where('coupon_code', 'like', "%{$keyword}%");
        }

        $coupons = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('admin.coupons.form', ['coupon' => new Coupon(['is_active' => 1, 'platform' => 1, 'discount_type' => 1])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Coupon(['redemption_number' => 0]));

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $this->save($request, $coupon);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }

    private function save(Request $request, Coupon $coupon): void
    {
        $data = $request->validate([
            'coupon_code' => ['required', 'string', 'max:255', Rule::unique('bx_coupon', 'coupon_code')->ignore($coupon->id)],
            'discount_type' => ['required', 'in:'.implode(',', array_keys(config('admin.discount_type')))],
            'discount_amount' => ['required', 'integer', 'min:1', Rule::when((int) $request->input('discount_type') === 1, ['max:100'])],
            'user_type' => ['nullable', 'in:'.implode(',', array_keys(config('admin.coupon_user_type')))],
            'platform' => ['required', 'in:'.implode(',', array_keys(config('admin.platform')))],
            'profile_type' => ['nullable', 'array'],
            'profile_type.*' => ['integer', 'in:'.implode(',', array_keys(config('admin.profile_types')))],
            'membership' => ['nullable', 'array'],
            'membership.*' => ['integer', 'in:'.implode(',', array_keys(config('admin.membership')))],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'max_redemption' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'in:0,1'],
        ]);

        // The legacy table stores multi-selects as comma separated ids.
        $data['profile_type'] = implode(',', $data['profile_type'] ?? []);
        $data['membership'] = implode(',', $data['membership'] ?? []);
        $data['coupon_code'] = strtoupper($data['coupon_code']);

        $coupon->fill($data)->save();
    }
}
