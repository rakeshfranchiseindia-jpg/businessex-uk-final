<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MentorProfileController extends AuthController
{
    public function store(Request $request): RedirectResponse
    {
        return $this->register($request, 'mentor');
    }
}
