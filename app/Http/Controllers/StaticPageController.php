<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StaticPageController extends Controller
{
    private const PAGES = [
        'about-us' => [
            'title' => 'About BusinessX',
            'description' => 'Learn how BusinessX connects businesses, startups, investors and mentors.',
            'sections' => [
                [
                    'heading' => 'A network for business opportunities',
                    'paragraphs' => [
                        'BusinessX brings businesses, startups, investors and mentors together in one business exchange network. Members can create profiles, discover opportunities and connect with people who can help move their plans forward.',
                        'Browse business, investor, startup and mentor listings to find opportunities across industries and locations.',
                    ],
                ],
                [
                    'heading' => 'Built to help members connect',
                    'paragraphs' => [
                        'Whether you are growing a company, seeking investment, exploring an acquisition or looking for experienced guidance, BusinessX helps you find relevant profiles and start a conversation.',
                    ],
                ],
            ],
            'action' => ['label' => 'Explore the listings', 'route' => 'business-listing'],
        ],
        'disclaimer' => [
            'title' => 'Disclaimer',
            'description' => 'Important information about using BusinessX listings and website content.',
            'sections' => [
                [
                    'heading' => 'General information',
                    'paragraphs' => [
                        'BusinessX provides an online directory and business networking service. Website content and member profiles are provided for general information and do not constitute financial, investment, legal, tax or other professional advice.',
                        'You should make your own enquiries and obtain advice from an appropriately qualified professional before making a business, investment or transaction decision.',
                    ],
                ],
                [
                    'heading' => 'Member information and opportunities',
                    'paragraphs' => [
                        'Information in member profiles and listings may be supplied by members. BusinessX does not make a representation or guarantee that every listing is complete, current, accurate or suitable for your needs.',
                        'Any discussion, introduction, due diligence, negotiation or transaction between members is their responsibility. Listing a profile does not guarantee a response, funding, sale, investment or other outcome.',
                    ],
                ],
                [
                    'heading' => 'External links and availability',
                    'paragraphs' => [
                        'Links to third-party websites are provided for convenience. BusinessX does not control or endorse third-party content and is not responsible for its availability or accuracy.',
                        'We work to keep the website available and up to date, but access may occasionally be interrupted or content may change.',
                    ],
                ],
            ],
        ],
        'privacy-policy' => [
            'title' => 'Privacy Policy',
            'description' => 'How BusinessX uses information submitted through profiles and website forms.',
            'sections' => [
                [
                    'heading' => 'Information you provide',
                    'paragraphs' => [
                        'When you register, create a profile, subscribe to updates or contact us, you may provide details such as your name, email address, telephone number, business information and the content of your enquiry.',
                    ],
                ],
                [
                    'heading' => 'How we use information',
                    'paragraphs' => [
                        'We use submitted information to provide and maintain your account or listing, operate the BusinessX network, respond to enquiries, provide requested services and send communications related to your account or preferences.',
                        'Information displayed in a profile may be visible to other visitors according to the profile and service settings shown when you submit it. Please do not include information in a public profile that you do not want displayed.',
                    ],
                ],
                [
                    'heading' => 'Your choices and questions',
                    'paragraphs' => [
                        'You can manage information in your account where those controls are available. For a privacy question or a request relating to information you submitted, contact us using the details on our Contact page.',
                        'We may update this notice when our services or practices change. The current version will be published on this page.',
                    ],
                ],
            ],
            'action' => ['label' => 'Contact us about privacy', 'route' => 'contact'],
        ],
        'terms' => [
            'title' => 'Terms of Use',
            'description' => 'Terms for accessing and using the BusinessX website and member directory.',
            'sections' => [
                [
                    'heading' => 'Using BusinessX',
                    'paragraphs' => [
                        'By accessing BusinessX, you agree to use the website lawfully and in a way that does not disrupt the service or infringe the rights of others. You must provide information that is accurate to the best of your knowledge and keep it up to date.',
                    ],
                ],
                [
                    'heading' => 'Profiles and member conduct',
                    'paragraphs' => [
                        'You are responsible for the content you submit and for ensuring you have the right to share it. Do not submit misleading, unlawful, harmful or unauthorised content, impersonate another person, misuse contact details or use the directory for unsolicited bulk communications.',
                        'BusinessX may review, restrict or remove content or access where reasonably necessary to protect members, the service or compliance with these terms.',
                    ],
                ],
                [
                    'heading' => 'Introductions and transactions',
                    'paragraphs' => [
                        'BusinessX provides tools for discovery and connection. Members are responsible for verifying information, conducting due diligence and agreeing the terms of any relationship or transaction. BusinessX is not a party to agreements between members and does not guarantee a particular result.',
                    ],
                ],
                [
                    'heading' => 'Service changes and contact',
                    'paragraphs' => [
                        'We may update these terms or change parts of the service. Continued use after updated terms are published means you accept the revised terms. If you have a question about these terms, please contact us.',
                    ],
                ],
            ],
            'action' => ['label' => 'Contact us', 'route' => 'contact'],
        ],
        'contact' => [
            'title' => 'Contact BusinessX',
            'description' => 'Contact the BusinessX team with questions about the platform or your account.',
            'sections' => [
                [
                    'heading' => 'We are here to help',
                    'paragraphs' => [
                        'For questions about BusinessX, your account, a listing or our services, email our team. Please do not send passwords or sensitive financial information by email.',
                    ],
                ],
            ],
            'email' => 'info@worldtradecouncil.com',
            'address' => 'SCALE MEDIA INTERNATIONAL LTD., Global Office, 220 Wards Road, Ilford, England, IG2 7DY',
        ],
    ];

    public function show(string $page): View
    {
        abort_unless(isset(self::PAGES[$page]), 404);

        $content = self::PAGES[$page];

        return view('static.page', [
            'page' => 'static',
            'showHeader' => true,
            'showFooter' => true,
            'slug' => $page,
            'staticPage' => $content,
        ]);
    }
}
