<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Conversation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Conversation::query()->with(['owner', 'sender'])->withCount(['messages', 'proposals']);

        if (in_array($request->query('type'), ['business', 'investor', 'mentor', 'startup'], true)) {
            $query->where('profile_type', $request->query('type'));
        }
        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('sender_name', 'like', "%{$keyword}%")
                ->orWhere('sender_email', 'like', "%{$keyword}%")
                ->orWhere('profile_id', $keyword));
        }

        $conversations = $query->latest('updated_at')->paginate($this->perPage())->withQueryString();

        return view('admin.conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $conversation->load(['owner', 'sender', 'messages.sender', 'proposals']);

        return view('admin.conversations.show', compact('conversation'));
    }
}
