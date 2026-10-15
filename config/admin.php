<?php

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
|
| Lookup tables used only by the admin panel. Values mirror the constants
| used by the legacy BusinessEx admin so existing data keeps its meaning.
|
*/

return [

    'per_page' => 20,

    // Upload folder (inside public/) for admin-managed media.
    'upload_path' => 'uploads/admin',

    'roles' => [
        1 => 'Super Admin',
        2 => 'Profile Manager',
        3 => 'Content Manager',
        4 => 'Industry Report Manager',
    ],

    // Roles allowed into each area. Super admin (1) is always allowed.
    'permissions' => [
        'profiles' => [2],
        'users' => [],
        'content' => [3],
        'reports' => [4],
        'marketing' => [3],
        'interactions' => [],
        'payments' => [],
        'enquiries' => [],
    ],

    'profile_status' => [
        0 => 'Inactive',
        1 => 'Active',
        2 => 'Pending',
        3 => 'Awaiting Approval',
        4 => 'Rejected',
    ],

    'profile_types' => [
        1 => 'Business',
        2 => 'Investor',
        3 => 'Lender',
        4 => 'Mentor',
        5 => 'Incubator',
        6 => 'Broker',
        7 => 'Startup',
    ],

    'reg_source' => [
        1 => 'BusinessEx',
        2 => 'Facebook',
        3 => 'Google',
        4 => 'LinkedIn',
        5 => 'BX Old',
        6 => 'FI.COM',
        7 => 'Events',
    ],

    'membership' => [
        0 => 'Basic',
        1 => 'Premium',
        2 => 'Gold',
        3 => 'Platinum',
        4 => 'Promoted-Event',
    ],

    'payment_status' => [
        0 => 'Pending',
        1 => 'Success',
        2 => 'Failed',
    ],

    'content_status' => [
        1 => 'Published',
        0 => 'Draft',
    ],

    'banner_page' => [
        1 => 'Home',
        2 => 'Form',
        3 => 'Static',
        4 => 'Business Listing',
        5 => 'Startup Listing',
        6 => 'Investor Listing',
        7 => 'Mentor Listing',
        8 => 'Business Landing Page',
        9 => 'Startup Landing Page',
        10 => 'Investor Landing Page',
        11 => 'Mentor Landing Page',
        12 => 'Article Listing',
        13 => 'Article Landing',
        14 => 'News Listing',
        15 => 'News Landing',
        16 => 'Industry Report',
        17 => 'My Account',
    ],

    'banner_location' => [
        1 => 'Top',
        2 => 'Left',
        3 => 'Right',
        4 => 'Middle',
        5 => 'Bottom',
        6 => 'Mobile Top',
    ],

    'banner_width' => [250, 300, 320, 468, 587, 728, 970],

    'banner_height' => [60, 90, 100, 250, 320, 600],

    'discount_type' => [
        1 => 'Percentage',
        2 => 'Flat',
    ],

    'coupon_user_type' => [
        1 => 'New User',
        2 => 'Existing User',
    ],

    'platform' => [
        1 => 'Web',
        2 => 'App',
    ],

    'newsletter_status' => [
        'S' => 'Subscribed',
        'P' => 'Pending',
        'U' => 'Unsubscribed',
    ],

];
