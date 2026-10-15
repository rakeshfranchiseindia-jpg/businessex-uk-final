<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Proposal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProposalController extends Controller
{
    public function index(Request $request): View
    {
        $query = Proposal::query()->with(['sender', 'recipient']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$keyword}%")->orWhere('id', $keyword));
        }

        $proposals = $query->latest('created_at')->paginate($this->perPage())->withQueryString();
        $statuses = Proposal::query()->distinct()->orderBy('status')->pluck('status');

        return view('admin.proposals.index', compact('proposals', 'statuses'));
    }

    public function show(Proposal $proposal): View
    {
        $proposal->load(['sender', 'recipient', 'conversation']);

        return view('admin.proposals.show', compact('proposal'));
    }
}
