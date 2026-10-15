<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ArticleComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleCommentController extends Controller
{
    public function index(Request $request): View
    {
        $query = ArticleComment::query()->with('article:article_id,article_title');

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('comment_name', 'like', "%{$keyword}%")
                ->orWhere('comment_email', 'like', "%{$keyword}%")
                ->orWhere('comment_detail', 'like', "%{$keyword}%"));
        }
        if (is_numeric($request->query('status'))) {
            $query->where('comment_status', (int) $request->query('status'));
        }

        $comments = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function toggle(ArticleComment $comment): RedirectResponse
    {
        $comment->forceFill(['comment_status' => $comment->comment_status ? 0 : 1])->save();

        return back()->with('success', $comment->comment_status ? 'Comment approved.' : 'Comment hidden.');
    }

    public function destroy(ArticleComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
