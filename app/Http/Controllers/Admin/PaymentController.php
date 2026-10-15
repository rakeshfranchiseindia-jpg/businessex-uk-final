<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\AdminUser;
use App\Models\Admin\Membership;
use App\Models\Admin\OnlinePayment;
use App\Support\Admin\ProfileTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Self-service Razorpay payments (online_payments) and admin-granted
     * plans (profile_memberships, which never create a payment row) shown
     * as one list so "who paid / who assigned this" is visible in one place.
     */
    public function index(Request $request): View
    {
        $selfPayments = DB::table('online_payments')->select([
            DB::raw("'payment' as row_type"),
            'payment_id as id',
            'order_no',
            'name',
            'email',
            'profile_type',
            'profile_id',
            DB::raw("COALESCE(NULLIF(membership_plan, ''), product_details) as plan_label"),
            DB::raw('CAST(amount AS DECIMAL(15,2)) as amount'),
            'payment_mode',
            'payment_status',
            DB::raw('NULL as assigned_by'),
            'created_at',
        ]);

        $adminGrants = DB::table('profile_memberships as pm')
            ->join('user_account as ua', 'ua.user_id', '=', 'pm.user_id')
            ->where('pm.payment_source', 2)
            ->select([
                DB::raw("'grant' as row_type"),
                'pm.membership_id as id',
                DB::raw("CONCAT('ADM-', pm.membership_id) as order_no"),
                'ua.name',
                'ua.email',
                'pm.profile_type',
                'pm.profile_id',
                'pm.payment_comments as plan_label',
                DB::raw('CAST(pm.amount AS DECIMAL(15,2)) as amount'),
                DB::raw("'Admin assigned' as payment_mode"),
                DB::raw('1 as payment_status'),
                'pm.assigned_by',
                'pm.created_at',
            ]);

        $query = DB::query()->fromSub($selfPayments->unionAll($adminGrants), 'combined');

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('order_no', 'like', "%{$keyword}%")
                ->orWhere('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%"));
        }
        if (is_numeric($request->query('status'))) {
            $query->where('payment_status', (int) $request->query('status'));
        }
        if (is_numeric($request->query('profile_type'))) {
            $query->where('profile_type', (int) $request->query('profile_type'));
        }
        if ($from = $request->date('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->date('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $total = (float) (clone $query)->where('payment_status', 1)->sum('amount');
        $payments = $query->orderByDesc('created_at')->paginate($this->perPage())->withQueryString();

        $admins = AdminUser::query()
            ->whereIn('admin_id', collect($payments->items())->pluck('assigned_by')->filter()->unique())
            ->get()
            ->keyBy('admin_id');
        $profileSlugs = collect(ProfileTypes::all())->mapWithKeys(fn ($type, $slug) => [$type['id'] => $slug]);

        return view('admin.payments.index', compact('payments', 'total', 'admins', 'profileSlugs'));
    }

    public function show(OnlinePayment $payment): View
    {
        $payment->load('user');

        $membership = Membership::query()
            ->with('assignedByAdmin')
            ->where('order_no', $payment->order_no)
            ->orWhere(fn ($q) => $q->where('user_id', $payment->user_id)
                ->where('profile_type', $payment->profile_type)
                ->where('profile_id', $payment->profile_id))
            ->latest('created_at')
            ->first();

        return view('admin.payments.show', compact('payment', 'membership'));
    }
}
