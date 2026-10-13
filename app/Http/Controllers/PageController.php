<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PageController extends Controller
{
    private const PAGES = [
        'pricing' => ['pages.pricing', 'pricing', true],
        'login' => ['pages.login', 'home', false],
        'profile-details' => ['pages.profile-details', 'dashboard', false],
    ];

    public function show(Request $request): View|RedirectResponse|Response
    {
        $routeName = $request->route()->getName();

        if (Auth::check() && in_array($routeName, [
            'login',
            'business-registration',
            'investor-registration',
            'mentor-registration',
            'startup-registration',
        ], true)) {
            if ($routeName === 'login') {
                return redirect()->route('dashboard.index');
            }

            $type = str_replace('-registration', '', $routeName);

            return redirect()->route('dashboard.profiles.create.form', ['type' => $type]);
        }

        if (in_array($routeName, ['business-registration', 'investor-registration', 'mentor-registration', 'startup-registration'], true)) {
            $type = str_replace('-registration', '', $routeName);

            return $this->render(
                'pages.'.$type.'-registration',
                'registration',
                true,
                [
                    'registrationProfile' => config('registration_profiles.'.$type),
                    'registrationType' => $type,
                ]
            );
        }

        if (isset(self::PAGES[$routeName])) {
            [$view, $activePage, $showHeader] = self::PAGES[$routeName];
            $response = $this->render($view, $activePage, $showHeader);

            if ($routeName === 'login') {
                // "View Profile" links for guests point straight at /login
                // (so the browser's hover preview and the address bar stay
                // clean, no ?redirect=... query string) and stash where the
                // visitor was headed in a short-lived cookie instead. Pick
                // that up here and store it the same way the `auth`
                // middleware would, so the normal post-login
                // redirect()->intended() still lands on that profile.
                $intended = $request->cookie('intended_profile');

                if (is_string($intended) && Str::startsWith($intended, url('/') . '/')) {
                    $request->session()->put('url.intended', $intended);
                }

                // A browser's back/forward cache can replay this exact page
                // after the user has since logged in elsewhere, so a stale
                // "logged out" login form stays on screen while every link
                // on it (the registration CTA included) hits the server as
                // the now-authenticated user and redirects to the
                // dashboard — looking like "it skipped login". no-store
                // keeps the page out of that cache.
                return response($response)
                    ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                    ->withCookie(Cookie::forget('intended_profile'));
            }

            return $response;
        }

        abort(404);
    }

    public function registrationRedirect(Request $request): RedirectResponse
    {
        $type = $request->query('type', 'business');
        abort_unless(in_array($type, ['business', 'investor', 'mentor', 'startup'], true), 404);

        if (Auth::check()) {
            return redirect()->route('dashboard.profiles.create.form', ['type' => $type]);
        }

        return redirect()->route("{$type}-registration");
    }

    private function render(string $view, string $page, bool $showHeader, array $data = []): View
    {
        return view($view, array_merge([
            'page' => $page,
            'showHeader' => $showHeader,
            'showFooter' => true,
        ], $data));
    }
}
