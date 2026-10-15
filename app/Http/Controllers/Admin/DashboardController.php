<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Article;
use App\Models\Admin\ContactEnquiry;
use App\Models\Admin\Conversation;
use App\Models\Admin\OnlinePayment;
use App\Models\Admin\Proposal;
use App\Models\Admin\UserAccount;
use App\Support\Admin\ProfileTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from');
        $to = $request->date('to');

        $dateFilter = function ($query) use ($from, $to) {
            if ($from) {
                $query->whereDate('created_at', '>=', $from);
            }
            if ($to) {
                $query->whereDate('created_at', '<=', $to);
            }

            return $query;
        };

        // Profile counts per type, split by status.
        $profiles = [];
        foreach (ProfileTypes::all() as $slug => $type) {
            $counts = $dateFilter(ProfileTypes::query($slug))
                ->selectRaw("{$type['status']} as status, COUNT(*) as total")
                ->groupBy($type['status'])
                ->pluck('total', 'status');

            $profiles[$slug] = [
                'label' => $type['label'],
                'total' => (int) $counts->sum(),
                'active' => (int) ($counts[ProfileTypes::STATUS_ACTIVE] ?? 0),
                'pending' => (int) (($counts[ProfileTypes::STATUS_PENDING] ?? 0) + ($counts[ProfileTypes::STATUS_AWAITING] ?? 0)),
                'rejected' => (int) ($counts[ProfileTypes::STATUS_REJECTED] ?? 0),
                'inactive' => (int) ($counts[ProfileTypes::STATUS_INACTIVE] ?? 0),
            ];
        }

        $usersBySource = $dateFilter(UserAccount::query())
            ->selectRaw('reg_source, COUNT(*) as total')
            ->groupBy('reg_source')
            ->pluck('total', 'reg_source')
            ->mapWithKeys(fn ($total, $source) => [config('admin.reg_source')[$source] ?? 'Unknown' => (int) $total]);

        // Registrations for the last 12 months.
        $start = Carbon::now()->startOfMonth()->subMonths(11);
        $monthly = UserAccount::query()
            ->where('created_at', '>=', $start)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->pluck('total', 'month');
        $registrations = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $start->copy()->addMonths($i);
            $registrations[$month->format('M Y')] = (int) ($monthly[$month->format('Y-m')] ?? 0);
        }

        $stats = [
            'users' => $dateFilter(UserAccount::query())->count(),
            'profiles' => array_sum(array_column($profiles, 'total')),
            'pending' => array_sum(array_column($profiles, 'pending')),
            'revenue' => (float) $dateFilter(OnlinePayment::query())->where('payment_status', 1)->sum('amount'),
            'conversations' => $dateFilter(Conversation::query())->count(),
            'proposals' => $dateFilter(Proposal::query())->count(),
            'articles' => Article::query()->count(),
            'enquiries' => $dateFilter(ContactEnquiry::query())->count(),
        ];

        $recentUsers = UserAccount::query()->latest('created_at')->limit(8)->get();

        return view('admin.dashboard.index', compact(
            'profiles',
            'usersBySource',
            'registrations',
            'stats',
            'recentUsers',
        ));
    }
}
