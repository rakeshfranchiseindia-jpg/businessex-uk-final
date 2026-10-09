<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->name('dashboard.')
    ->controller(DashboardController::class)
    ->middleware('auth')
    ->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::get('/profile', 'profile')->name('profile');
        Route::put('/profile', 'updateProfile')->name('profile.update');
        Route::get('/password', 'password')->name('password');
        Route::put('/password', 'updatePassword')->name('password.update');
        Route::get('/my-plan', 'myPlan')->name('my-plan');
        Route::get('/activity', 'activity')->name('activity');
        Route::post('/notifications/read-all', 'markNotificationsRead')->name('notifications.read-all');
        Route::post('/notifications/{notification}/read', 'markNotificationRead')->name('notifications.read');
        Route::get('/profiles/create', 'createProfile')->name('profiles.create');
        Route::get('/profiles/create/{type}', 'createProfileForm')
            ->whereIn('type', ['business', 'investor', 'mentor', 'startup'])
            ->name('profiles.create.form');
        Route::post('/profiles/create/{type}', [AuthController::class, 'register'])
            ->whereIn('type', ['business', 'investor', 'mentor', 'startup'])
            ->middleware('throttle:5,1')
            ->name('profiles.store');
        Route::get('/profiles/{type}/{id}', 'showMyProfile')->whereNumber('id')->name('profiles.show');
        Route::get('/profiles/{type}/{id}/edit', 'editMyProfile')->whereNumber('id')->name('profiles.edit');
        Route::put('/profiles/{type}/{id}', 'updateMyProfile')->whereNumber('id')->name('profiles.update');
        Route::get('/live-chat/conversations', 'liveChatConversations')->name('live-chat.conversations');
        Route::get('/live-chat/conversations/{conversation}/messages', 'liveChatMessages')
            ->whereNumber('conversation')
            ->name('live-chat.messages');
        Route::get('/inbox', 'inbox')->name('inbox');
        Route::get('/inbox/{conversation}', 'showConversation')->whereNumber('conversation')->name('inbox.conversation');
        Route::post('/inbox/{conversation}/reply', 'replyToConversation')
            ->whereNumber('conversation')
            ->middleware('throttle:30,1')
            ->name('inbox.reply');
        Route::post('/inbox/{conversation}/proposals', 'sendProposal')->whereNumber('conversation')->name('inbox.proposals.store');
        Route::post('/proposals', 'sendSelectedProfileProposal')->name('proposals.store');
        Route::get('/proposals/{proposal}/attachments/{attachment}', 'downloadProposalAttachment')
            ->whereNumber('proposal')
            ->whereNumber('attachment')
            ->name('proposals.attachments.download');
        Route::put('/proposals/{proposal}/status', 'updateProposalStatus')->whereNumber('proposal')->name('proposals.status');
        Route::get('/proposals/sent', 'sentProposals')->name('proposals.sent');
        Route::get('/proposals/received', 'receivedProposals')->name('proposals.received');
        Route::get('/instant-response', 'instantResponse')->name('instant-response');
        Route::post('/instant-response', 'saveInstantResponse')->name('instant-response.save');
    });

Route::redirect('/dashboard-profile', '/dashboard/profile')->name('dashboard-profile.legacy');
Route::redirect('/dashboard-password', '/dashboard/password')->name('dashboard-password.legacy');
Route::redirect('/bx-inbox', '/dashboard/inbox')->name('bx-inbox.legacy');
Route::redirect('/proposal-sent', '/dashboard/proposals/sent')->name('proposal-sent.legacy');
Route::redirect('/proposal-received', '/dashboard/proposals/received')->name('proposal-received.legacy');
Route::redirect('/instant-response', '/dashboard/instant-response')->name('instant-response.legacy');
