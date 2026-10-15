<?php

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
|
| Loaded from bootstrap/app.php with the "web" middleware group. Everything
| here is prefixed with /admin, named admin.*, and authenticated through
| the separate "admin" guard (admin_user table).
|
*/

use App\Http\Controllers\Admin\ArticleCommentController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BexShortController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\IndustryReportController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileCommentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProfileImageController;
use App\Http\Controllers\Admin\ProfilePlanController;
use App\Http\Controllers\Admin\InstantResponseController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Support\Admin\ProfileTypes;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function (): void {

    // -------------------------------------------------------------------------
    // Authentication
    // -------------------------------------------------------------------------
    Route::middleware('admin.guest')->group(function (): void {
        Route::get('/', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware('admin.auth')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ---------------------------------------------------------------------
        // Profiles (business, startup, investor, mentor, lender, incubator, broker)
        // ---------------------------------------------------------------------
        Route::middleware('admin.role:profiles')
            ->prefix('profiles/{type}')
            ->whereIn('type', ProfileTypes::slugs())
            ->name('profiles.')
            ->controller(ProfileController::class)
            ->group(function (): void {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->whereNumber('id')->name('show');
                Route::get('/{id}/edit', 'edit')->whereNumber('id')->name('edit');
                Route::put('/{id}', 'update')->whereNumber('id')->name('update');
                Route::post('/{id}/approve', 'approve')->whereNumber('id')->name('approve');
                Route::post('/{id}/deactivate', 'deactivate')->whereNumber('id')->name('deactivate');
                Route::post('/{id}/reject', 'reject')->whereNumber('id')->name('reject');
            });

        Route::middleware('admin.role:profiles')
            ->prefix('profiles/{type}/{id}/images')
            ->whereIn('type', ProfileTypes::slugs())
            ->whereNumber('id')
            ->name('profiles.images.')
            ->controller(ProfileImageController::class)
            ->group(function (): void {
                Route::post('/', 'store')->name('store');
                Route::post('/{image}/toggle', 'toggle')->whereNumber('image')->name('toggle');
                Route::delete('/{image}', 'destroy')->whereNumber('image')->name('destroy');
            });

        Route::middleware('admin.role:profiles')
            ->prefix('profiles/{type}/{id}/comments')
            ->whereIn('type', ProfileTypes::slugs())
            ->whereNumber('id')
            ->name('profiles.comments.')
            ->controller(ProfileCommentController::class)
            ->group(function (): void {
                Route::post('/', 'store')->name('store');
            });

        Route::middleware('admin.role:profiles')
            ->prefix('profiles/{type}/{id}/plan')
            ->whereIn('type', ProfileTypes::slugs())
            ->whereNumber('id')
            ->name('profiles.plan.')
            ->controller(ProfilePlanController::class)
            ->group(function (): void {
                Route::post('/', 'store')->name('store');
            });

        // ---------------------------------------------------------------------
        // Users
        // ---------------------------------------------------------------------
        Route::middleware('admin.role:users')->group(function (): void {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}', [UserController::class, 'show'])->whereNumber('user')->name('users.show');
            Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->whereNumber('user')->name('users.toggle');
        });

        // ---------------------------------------------------------------------
        // Content: news, articles, comments, tags, BEX shorts, SEO
        // ---------------------------------------------------------------------
        Route::middleware('admin.role:content')->group(function (): void {
            Route::resource('news', NewsController::class)->except('show')->parameters(['news' => 'news']);
            Route::resource('articles', ArticleController::class)->except('show');
            Route::get('/comments', [ArticleCommentController::class, 'index'])->name('comments.index');
            Route::post('/comments/{comment}/toggle', [ArticleCommentController::class, 'toggle'])->whereNumber('comment')->name('comments.toggle');
            Route::delete('/comments/{comment}', [ArticleCommentController::class, 'destroy'])->whereNumber('comment')->name('comments.destroy');
            Route::resource('tags', TagController::class)->except('show');
            Route::resource('bex-shorts', BexShortController::class)->except('show')->parameters(['bex-shorts' => 'bexShort']);
            Route::resource('seo', SeoController::class)->except('show')->parameters(['seo' => 'seo']);
        });

        // ---------------------------------------------------------------------
        // Industry reports
        // ---------------------------------------------------------------------
        Route::middleware('admin.role:reports')->group(function (): void {
            Route::resource('industry-reports', IndustryReportController::class)->except('show')->parameters(['industry-reports' => 'report']);
        });

        // ---------------------------------------------------------------------
        // Marketing: banners, coupons, testimonials
        // ---------------------------------------------------------------------
        Route::middleware('admin.role:marketing')->group(function (): void {
            Route::resource('banners', BannerController::class)->except('show');
            Route::resource('testimonials', TestimonialController::class)->except('show');
        });

        Route::middleware('admin.role:payments')->group(function (): void {
            Route::resource('coupons', CouponController::class)->except('show');
            Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::get('/payments/{payment}', [PaymentController::class, 'show'])->whereNumber('payment')->name('payments.show');
        });

        // ---------------------------------------------------------------------
        // Interactions: conversations and proposals between members
        // ---------------------------------------------------------------------
        Route::middleware('admin.role:interactions')->group(function (): void {
            Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
            Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->whereNumber('conversation')->name('conversations.show');
            Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
            Route::get('/proposals/{proposal}', [ProposalController::class, 'show'])->whereNumber('proposal')->name('proposals.show');
            Route::get('/instant-responses', [InstantResponseController::class, 'index'])->name('instant-responses.index');
            Route::post('/instant-responses/{instantResponse}/toggle', [InstantResponseController::class, 'toggle'])->whereNumber('instantResponse')->name('instant-responses.toggle');
        });

        // ---------------------------------------------------------------------
        // Enquiries: contact form, newsletter, chatbot leads
        // ---------------------------------------------------------------------
        Route::middleware('admin.role:enquiries')->prefix('enquiries')->name('enquiries.')->group(function (): void {
            Route::get('/contact', [EnquiryController::class, 'contact'])->name('contact');
            Route::get('/newsletter', [EnquiryController::class, 'newsletter'])->name('newsletter');
            Route::get('/chatbot', [EnquiryController::class, 'chatbot'])->name('chatbot');
        });
    });
});
