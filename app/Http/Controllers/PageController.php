<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    private const PAGES = [
        'pricing' => ['pages.pricing', 'pricing', true],
        'login' => ['pages.login', 'home', false],
        'profile-details' => ['pages.profile-details', 'dashboard', false],
    ];

    public function show(Request $request): View|RedirectResponse
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
                'pages.' . $type . '-registration',
                'registration',
                true,
                [
                    'registrationProfile' => config('registration_profiles.' . $type),
                    'registrationType' => $type,
                ]
            );
        }

        if (isset(self::PAGES[$routeName])) {
            [$view, $activePage, $showHeader] = self::PAGES[$routeName];

            return $this->render($view, $activePage, $showHeader);
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
