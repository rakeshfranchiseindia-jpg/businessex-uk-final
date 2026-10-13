<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_name' => ['required', 'string', 'min:5', 'max:100', "regex:/^[A-Za-z][A-Za-z .'-]*$/"],
            'contact_email' => ['required', 'email', 'max:100'],
            'contact_mobile' => ['required', 'string', 'regex:/^07[0-9]{9}$/'],
            'contact_comment' => ['required', 'string', 'min:15', 'max:155'],
        ], [
            'contact_name.regex' => 'Name should only contain letters and spaces.',
            'contact_mobile.regex' => 'Enter a valid UK mobile number starting with 07 (11 digits).',
        ]);

        DB::table('businessex_contactus')->insert([
            'contact_name' => trim($validated['contact_name']),
            'contact_email' => mb_strtolower(trim($validated['contact_email'])),
            'contact_mobile' => $validated['contact_mobile'],
            'contact_comment' => trim($validated['contact_comment']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('contact')->with(
            'success',
            'Thank you for contacting us. Our team will get back to you shortly.'
        );
    }
}
