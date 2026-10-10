<?php

use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessListingController;
use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CitySuggestionController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvestorListingController;
use App\Http\Controllers\InvestorProfileController;
use App\Http\Controllers\MentorListingController;
use App\Http\Controllers\MentorProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileContactController;
use App\Http\Controllers\ProfileMediaController;
use App\Http\Controllers\StartupListingController;
use App\Http\Controllers\StartupProfileController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\NewsletterVerificationController;
use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/dashboard.php';

$pages = [
    '/pricing' => 'pricing',
    '/login' => 'login',
];

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/uk-cities/suggest', CitySuggestionController::class)
    ->middleware('throttle:60,1')
    ->name('uk-cities.suggest');
Route::post('/chatbot/message', [ChatbotController::class, 'respond'])
    ->middleware('throttle:20,1')
    ->name('chatbot.respond');
Route::post('/chatbot/leads', [ChatbotController::class, 'storeLead'])
    ->middleware('throttle:5,1')
    ->name('chatbot.leads.store');

Route::get('/business-listing', [BusinessListingController::class, 'index'])->name('business-listing');
Route::get('/investor-listing', [InvestorListingController::class, 'index'])->name('investor-listing');
Route::get('/startup-listing', [StartupListingController::class, 'index'])->name('startup-listing');
Route::get('/mentor-listing', [MentorListingController::class, 'index'])->name('mentor-listing');
Route::get('/about-us', [StaticPageController::class, 'show'])->defaults('page', 'about-us')->name('about-us');
Route::get('/disclaimer', [StaticPageController::class, 'show'])->defaults('page', 'disclaimer')->name('disclaimer');
Route::get('/privacy-policy', [StaticPageController::class, 'show'])->defaults('page', 'privacy-policy')->name('privacy-policy');
Route::get('/terms-and-conditions', [StaticPageController::class, 'show'])->defaults('page', 'terms')->name('terms');
Route::get('/contact-us', [StaticPageController::class, 'show'])->defaults('page', 'contact')->name('contact');

foreach ($pages as $uri => $name) {
    Route::get($uri, [PageController::class, 'show'])->name($name);
}

Route::get('/forgot-password', [PasswordResetController::class, 'forgotForm'])->name('forgot-password');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('throttle:5,1')
    ->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('reset-password');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('throttle:5,1')
    ->name('password.update');
Route::get('/account/set-password/{id}/{hash}', [PasswordResetController::class, 'setupForm'])
    ->whereNumber('id')
    ->middleware('signed')
    ->name('password.setup');
Route::post('/account/set-password/{id}/{hash}', [PasswordResetController::class, 'setPassword'])
    ->whereNumber('id')
    ->middleware(['signed', 'throttle:5,1'])
    ->name('password.setup.update');

Route::get('/profile-details/{type}/{id}', [ProfileContactController::class, 'show'])
    ->whereIn('type', ['business', 'investor', 'mentor', 'startup'])
    ->whereNumber('id')
    ->name('profile-details');
Route::post('/profile-details/{type}/{id}/contact', [ProfileContactController::class, 'store'])
    ->whereIn('type', ['business', 'investor', 'mentor', 'startup'])
    ->whereNumber('id')
    ->middleware(['auth', 'throttle:5,1'])
    ->name('profile-contact.store');
Route::get('/profile-details/{type}/{id}/contact', [ProfileContactController::class, 'start'])
    ->whereIn('type', ['business', 'investor', 'mentor', 'startup'])
    ->whereNumber('id')
    ->middleware('auth')
    ->name('profile-contact.start');

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->whereNumber('id')
    ->middleware('signed')
    ->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware('throttle:3,60')
    ->name('verification.resend');
Route::get('/profile-media/{media}/download', [ProfileMediaController::class, 'download'])
    ->whereNumber('media')
    ->middleware('auth')
    ->name('profile-media.download');
Route::post('/newsletter/subscribe', [SubscribeController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');
Route::get('/newsletter/verify/{id}/{hash}', [NewsletterVerificationController::class, 'verify'])
    ->whereNumber('id')
    ->middleware('signed')
    ->name('newsletter.verify');
Route::post('/register/{type}', [AuthController::class, 'register'])
    ->whereIn('type', ['business', 'investor', 'mentor', 'startup'])
    ->middleware('throttle:5,1')
    ->name('registration.store');
Route::post('/register/quick', [AuthController::class, 'quickRegister'])
    ->middleware('throttle:5,1')
    ->name('registration.quick-store');
Route::post('/business-profile/register', [BusinessProfileController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('business-profile.store');
Route::post('/startup-profile/register', [StartupProfileController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('startup-profile.store');
Route::post('/mentor-profile/register', [MentorProfileController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('mentor-profile.store');
Route::post('/investor-profile/register', [InvestorProfileController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('investor-profile.store');

Route::get('/article', [ArticleController::class, 'index'])->name('article');
Route::get('/article/{article}', [ArticleController::class, 'show'])
    ->whereNumber('article')
    ->name('articles.show');
Route::post('/article/{article}/comments', [ArticleCommentController::class, 'store'])
    ->whereNumber('article')
    ->middleware(['auth', 'throttle:5,1'])
    ->name('articles.comments.store');
Route::get('/article-detail', [ArticleController::class, 'legacyDetail'])->name('article-detail');

foreach (['business', 'investor', 'mentor', 'startup'] as $type) {
    Route::get("/{$type}-registration", [PageController::class, 'show'])->name("{$type}-registration");
}

Route::get('/registration', [PageController::class, 'registrationRedirect'])->name('registration');
