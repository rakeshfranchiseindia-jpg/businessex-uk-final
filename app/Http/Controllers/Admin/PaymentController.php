<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Membership;
use App\Models\Admin\OnlinePayment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = OnlinePayment::query();

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('order_no', 'like', "%{$keyword}%")
                ->orWhere('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('phone', 'like', "%{$keyword}%"));
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
        $payments = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.payments.index', compact('payments', 'total'));
    }

    public function show(OnlinePayment $payment): View
    {
        $payment->load('user');

        $membership = Membership::query()
            ->where('order_no', $payment->order_no)
            ->orWhere(fn ($q) => $q->where('user_id', $payment->user_id)
                ->where('profile_type', $payment->profile_type)
                ->where('profile_id', $payment->profile_id))
            ->latest('created_at')
            ->first();

        return view('admin.payments.show', compact('payment', 'membership'));
    }
}
