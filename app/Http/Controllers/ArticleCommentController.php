<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ArticleCommentController extends Controller
{
    public function store(Request $request, int $article): RedirectResponse
    {
        abort_unless(Schema::hasTable('articles_comments'), 500, 'Article comments are not configured.');

        $record = DB::table('bx_articles')
            ->where('article_id', $article)
            ->where('article_status', 1)
            ->first(['article_id']);

        abort_unless($record, 404);

        $validated = $request->validate([
            'comment_detail' => ['required', 'string', 'min:3', 'max:3000'],
        ]);
        $user = $request->user();

        DB::table('articles_comments')->insert([
            'article_id' => $record->article_id,
            'comment_name' => mb_substr($user->name, 0, 255),
            'comment_email' => $user->email,
            'comment_detail' => trim($validated['comment_detail']),
            'comment_status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('articles.show', $record->article_id)
            ->with('comment_submitted', 'Thanks for your comment. It will appear after it has been reviewed.');
    }
}
