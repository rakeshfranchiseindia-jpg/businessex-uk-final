<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StaticPageController extends Controller
{
    private const PAGES = [
        'about-us' => [
            'title' => 'About Us',
            'description' => 'Business Exchange is a flagship business initiative of Franchise India, head quartered at GLOBAL OFFICE - 220, WARDS ROAD, ILFORD, ENGLAND, IG2 7DY',
            'video' => 'https://www.youtube.com/embed/wY4DidbzUek?loop=1&playlist=wY4DidbzUek',
            'sections' => [
                [
                    'heading' => 'Who We Are',
                    'paragraphs' => [
                        'BusinessEx is thoughtfully conceived and created to address selling and exchanging of businesses across industry verticals. It’s a one-of-kind platform that interconnects the like-minded, gives exposure to Businesses, Start-ups, Investors, Lenders, Mentors and Incubators to find suitable business associates. Over the last 15 years we have mastered the art of creating a springboard that is capable of addressing the scopes of selling and exchanging of businesses.',
                        'Businessex.com is emerging as the fastest growing marketplace for business consultation services. It bestows the buyers & sellers an interactive and a symbiotic platform to communicate with each other. With ‘Efficiency’ as its motto, Businessex.com gives cutting-edge efficiency in terms of time, efficacy, transaction and tailored solutions to its broad spectrum of buyers and sellers of existing businesses, across industries and geographies.',
                    ],
                ],
            ],
        ],
        'disclaimer' => [
            'title' => 'Disclaimer',
            'description' => 'Important information about using BusinessX listings and website content.',
            'notice' => 'Please read this disclaimer carefully before using the BusinessEx website.',
            'numbered' => true,
            'sections' => [
                [
                    'heading' => 'General information',
                    'paragraphs' => [
                        'This website contains general information only, and BusinessEx.com is not, by means of this website or any part thereof, rendering professional advice or services. Before making any decision or taking any action that might affect your finances or business, you should consult a qualified professional advisor. Your use of this website is at your own risk and you assume full responsibility and risk of loss resulting from your usage. To the fullest extent permissible pursuant to applicable law, BusinessEx.com, a business unit of Franchise India Brands Limited, disclaims all liability to you and everyone else in respect of the content on this site and all services provided through it, whether under any theory of tort, contract, warranty, strict liability or negligence or otherwise, and whether in respect of direct, indirect, consequential, special, punitive or similar damages, even if BusinessEx.com was advised, knew or should have known of the possibility of such damages.',
                        'The information contained in this website is for general information purposes only. We make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk. In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever.',
                    ],
                ],
                [
                    'heading' => 'Links to other websites',
                    'paragraphs' => [
                        'Through this website you are able to link to other websites which are not under the control of www.BusinessEx.com. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.',
                        'BusinessEx.com takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.',
                    ],
                ],
            ],
        ],
        'privacy-policy' => [
            'title' => 'Privacy Policy',
            'description' => 'We value the trust you place in us. That\'s why we insist upon the highest standards for secure transactions and customer information privacy. By visiting this Website you agree to be bound by the terms and conditions of this Privacy Policy.',
            'show_nav' => true,
            'summary' => [
                [
                    'heading' => 'Collection',
                    'items' => [
                        'We collect information you give us directly — such as your name, email, phone number and business/profile details — when you register or use the Website.',
                        'We also collect technical information such as your IP address, browser and device type.',
                        'Cookies and similar tools are used to understand how you use our Website.',
                    ],
                ],
                [
                    'heading' => 'Purposes',
                    'items' => [
                        'To create and manage your account and profile listings.',
                        'To connect you with relevant Businesses, Investors, Mentors and Startups on the platform.',
                        'To communicate with you, and — with your consent — send updates about our services.',
                    ],
                ],
                [
                    'heading' => 'Sharing',
                    'items' => [
                        'We do not sell your personal information.',
                        'We may share information with our group companies and trusted service providers who help us run the Website.',
                        'Information may be disclosed where required by law.',
                    ],
                ],
                [
                    'heading' => 'Retention & Security',
                    'items' => [
                        'We keep your personal data only for as long as necessary for the purposes it was collected for, or as required by law.',
                        'We use reasonable technical and organisational measures to protect your data.',
                    ],
                ],
                [
                    'heading' => 'Your Rights',
                    'items' => [
                        'You can ask to access, correct, or delete your personal data at any time.',
                        'Where we rely on your consent, you can withdraw it at any time.',
                    ],
                ],
                [
                    'heading' => 'Questions & Changes',
                    'items' => [
                        'We may update this Policy from time to time; changes take effect once posted here.',
                        'Contact our Grievance Officer below with any questions.',
                    ],
                ],
            ],
            'sections' => [
                [
                    'id' => 'scope-controller',
                    'heading' => 'Scope & Controller',
                    'paragraphs' => [
                        'This Policy applies to your use of the BusinessEx website and services when accessed from the UK. The data controller responsible for your personal information is Scale Media International Ltd, trading as BusinessEx (details under Grievance Officer / Contact below). This Policy does not cover any third-party websites, apps or services that BusinessEx may link to, or any third parties you choose to transact with through the platform.',
                    ],
                ],
                [
                    'id' => 'collection',
                    'heading' => 'Collection of Personally Identifiable Information',
                    'paragraphs' => [
                        'When you use our Website, we collect and store personal information which is provided by you from time to time. This may include, but is not limited to:',
                        [
                            'Identity and contact details — name, email address, phone number, company/organisation name.',
                            'Account and profile information — the profile type(s) you register for (Business, Investor, Mentor, Startup, Broker, Incubation), listing details, proposals sent or received, and other information you choose to add to your profile.',
                            'Technical information — IP address, browser type, device identifiers and operating system, collected automatically when you use the Website.',
                            'Usage information — pages visited, features used and how you interact with the Website, collected via cookies and similar technologies.',
                        ],
                        'Our Website may also enable you to fill in your details and communicate with other members. We are not responsible for the privacy practices, or the content, of any such third party.',
                    ],
                ],
                [
                    'id' => 'use-of-data',
                    'heading' => 'Use of Your Information',
                    'paragraphs' => [
                        'We use personal information to provide the services you request. Specifically, we may use your information to:',
                        [
                            'Create and administer your account, and match your profile with relevant Businesses, Investors, Mentors, Startups and other members of the platform.',
                            'Respond to enquiries, process proposals, and provide customer support.',
                            'Improve our Website and services, including through aggregated, anonymised analysis of how the Website is used.',
                            'Send you service-related communications, and — where you have agreed to receive them — marketing updates about BusinessEx.',
                            'Comply with our legal and regulatory obligations.',
                        ],
                    ],
                ],
                [
                    'id' => 'cookies',
                    'heading' => 'Cookies',
                    'paragraphs' => [
                        'A “cookie” is a small piece of information stored by a web server on a web browser so it can be later read back from that browser. We use cookies on our Website for a few different purposes:',
                        [
                            '<strong>Essential cookies</strong> — required for the Website to function, for example to keep you logged in and to remember choices you make while browsing.',
                            '<strong>Functionality cookies</strong> — remember preferences from previous visits so we can tailor the Website to you.',
                            '<strong>Performance/analytics cookies</strong> — help us understand how visitors use the Website so we can improve it.',
                            '<strong>Advertising cookies</strong> — used, where applicable, to make the ads you see more relevant.',
                        ],
                        'Most browsers let you refuse or delete cookies. Please note that if you disable cookies, some parts of the Website may not work as intended.',
                    ],
                ],
                [
                    'id' => 'sharing',
                    'heading' => 'Sharing of Personal Information',
                    'paragraphs' => [
                        'We may share personal information with our other corporate entities and affiliates to help detect and prevent identity theft, fraud and other misuse of our services. We may also disclose personal information to:',
                        [
                            'Trusted third-party service providers who perform functions on our behalf, such as hosting, email delivery and analytics, under an obligation to keep it confidential and secure.',
                            'Other members of the platform, but only to the extent needed to facilitate an introduction, proposal or transaction you have initiated.',
                            'Law enforcement, regulators or other third parties where we are required to do so by law, or to protect our rights, property or the safety of our users.',
                            'A successor entity, in connection with any merger, acquisition or sale of BusinessEx’s business or assets.',
                        ],
                        'We do not sell your personal information to unrelated third parties.',
                    ],
                ],
                [
                    'id' => 'links',
                    'heading' => 'Links to Other Sites',
                    'paragraphs' => [
                        'Our Website links to other websites that may collect personally identifiable information about you. BusinessEx is not responsible for the privacy practices or the content of such other websites, and we encourage you to read the privacy policy of any third-party website you visit.',
                    ],
                ],
                [
                    'id' => 'retention-security',
                    'heading' => 'Retention & Security',
                    'paragraphs' => [
                        'Our Website has stringent security measures in place to protect against the loss, misuse and alteration of the information under our control. We use reasonable administrative, technical and physical safeguards to protect your personal data.',
                        'We keep personal data only for as long as we believe is necessary for the purpose it was collected for, or as required to meet our legal, regulatory or reporting obligations, after which it is deleted or anonymised.',
                    ],
                ],
                [
                    'id' => 'your-rights',
                    'heading' => 'Your Rights',
                    'paragraphs' => [
                        'If UK data protection law applies to you, you have certain rights in relation to your personal data, including the right to:',
                        [
                            'Access the personal data we hold about you.',
                            'Request correction of any inaccurate or incomplete data.',
                            'Request erasure of your data, in certain circumstances.',
                            'Object to, or request restriction of, certain processing of your data.',
                            'Request a copy of your data in a portable format, where technically feasible.',
                            'Withdraw your consent at any time, where we rely on consent as the basis for processing.',
                        ],
                        'We will do our best to accommodate any such request, though we may need to apply certain restrictions permitted by law. To exercise any of these rights, please contact our Grievance Officer using the details below.',
                    ],
                ],
                [
                    'id' => 'opt-out',
                    'heading' => 'Choice / Opt-Out',
                    'paragraphs' => [
                        'We provide all users with the opportunity to opt-out of receiving non-essential (promotional/marketing) communications from us. If you no longer wish to receive these communications, you may opt-out by following the unsubscribe instructions included in each such message, or by contacting us directly.',
                    ],
                ],
                [
                    'id' => 'ads',
                    'heading' => 'Advertisements on BusinessEx.com',
                    'paragraphs' => [
                        'We may use third-party companies’ services to serve ads when you visit our Website. These companies may use non-personally-identifiable information about your visits to this and other websites in order to provide advertisements about goods and services that may be of interest to you.',
                    ],
                ],
                [
                    'id' => 'consent',
                    'heading' => 'Your Consent',
                    'paragraphs' => [
                        'By using the Website and/or by providing your information, you consent to the collection and use of the information you disclose on the Website in accordance with this Privacy Policy, including but not limited to your consent for sharing your information as per this Policy.',
                    ],
                ],
                [
                    'id' => 'questions-changes',
                    'heading' => 'Questions & Changes',
                    'paragraphs' => [
                        'If you have any questions or concerns about how we process your personal data, please contact our Grievance Officer using the details below. We may modify this Policy at any time; changes will be posted on this page and will take effect from the date of posting, so please check back periodically.',
                    ],
                ],
                [
                    'id' => 'grievance-officer',
                    'heading' => 'Grievance Officer',
                    'paragraphs' => [
                        'In accordance with the Information Technology Act 2000 and rules made there under, the name and contact details of the Grievance Officer are provided below:',
                        [
                            '<strong>Name:</strong> Dharmendra Yadav',
                            '<strong>Designation:</strong> Technical Lead',
                            '<strong>Address:</strong> SCALE MEDIA INTERNATIONAL LTD., Global Office, 220 Wards Road, Ilford, England, IG2 7DY',
                            '<strong>Phone:</strong> +91.129.4228873',
                            '<strong>Email:</strong> info@worldtradecouncil.com',
                        ],
                    ],
                ],
                [
                    'id' => 'definitions',
                    'heading' => 'Definitions',
                    'paragraphs' => [
                        [
                            '<strong>Personal Data</strong> — any information relating to an identified or identifiable individual.',
                            '<strong>Processing</strong> — anything done with personal data, such as collecting, storing, using, disclosing or deleting it.',
                            '<strong>Controller</strong> — the organisation that decides why and how personal data is processed (BusinessEx, in relation to the data described in this Policy).',
                            '<strong>Processor</strong> — a third party that processes personal data on the controller’s behalf, such as one of our service providers.',
                        ],
                    ],
                ],
            ],
        ],
        'terms' => [
            'title' => 'Terms Of Use',
            'description' => 'These Terms of Use govern the web pages of www.BusinessEx.com. This document is an electronic record in terms of the Information Technology Act, 2000, published in accordance with Rule 3(1) of the Information Technology (Intermediaries Guidelines) Rules, 2011.',
            'show_nav' => true,
            'sections' => [
                [
                    'id' => 'introduction',
                    'heading' => 'Introduction',
                    'paragraphs' => [
                        'The domain name www.Businessex.com is owned by BusinessEx, a business unit of Franchise India Brands Limited.',
                        'By using this Website, you (the "User") are agreeing to these terms.',
                    ],
                ],
                [
                    'id' => 'acknowledgment',
                    'heading' => 'Acknowledgment and Acceptance of Agreement',
                    'paragraphs' => [
                        'The Services/Products provided by BusinessEx are provided to the User under the terms and conditions of this Agreement.',
                    ],
                ],
                [
                    'id' => 'services-products',
                    'heading' => 'Regarding Services/Products',
                    'paragraphs' => [
                        'BusinessEx provides free as well as paid Services/Products.',
                    ],
                ],
                [
                    'id' => 'registration-obligations',
                    'heading' => "User's Registration Obligations",
                    'paragraphs' => [
                        'The User must be at least eighteen (18) years old to register.',
                    ],
                ],
                [
                    'id' => 'user-data',
                    'heading' => 'User Data',
                    'paragraphs' => [
                        'The User acknowledges that Registration Data is to be stored with BusinessEx.',
                    ],
                ],
                [
                    'id' => 'communication',
                    'heading' => 'Communication to Users',
                    'paragraphs' => [
                        'BusinessEx may communicate with registered users via promotional and transactional emails, SMS, and calls.',
                    ],
                ],
                [
                    'id' => 'account-security',
                    'heading' => 'User Name, Member Account, Password and Security',
                    'paragraphs' => [
                        'The User may be asked to choose a username and password to access certain parts of the Website, and is responsible for keeping these confidential.',
                    ],
                ],
                [
                    'id' => 'user-conduct',
                    'heading' => 'User Conduct & Obligations',
                    'paragraphs' => [
                        [
                            'The User agrees to abide by all applicable laws while using the Website.',
                            'The User further agrees to be liable for all communications sent from their account.',
                            'The User agrees not to use the Services/Products for illegal purposes.',
                            'The User agrees not to upload or transmit unlawful or objectionable material.',
                            'The User agrees not to attempt unauthorized access to other systems.',
                            'If the User has availed Services/Products, it shall ensure to share required information accurately.',
                        ],
                    ],
                ],
                [
                    'id' => 'indemnity',
                    'heading' => 'Indemnity',
                    'paragraphs' => [
                        'The User agrees to indemnify and hold BusinessEx harmless from any claim or demand arising out of the User’s use of the Website.',
                    ],
                ],
                [
                    'id' => 'storage',
                    'heading' => 'Storage of Communications',
                    'paragraphs' => [
                        'BusinessEx assumes no responsibility for deletion or failure to store communications, personalisation settings or other data.',
                    ],
                ],
                [
                    'id' => 'termination',
                    'heading' => 'Termination',
                    'paragraphs' => [
                        [
                            'The User agrees that BusinessEx may terminate the User’s account if these terms are violated.',
                            'Termination may also occur if payments are not made, misrepresentation occurs, or obligations are breached.',
                        ],
                    ],
                ],
                [
                    'id' => 'links',
                    'heading' => 'Links',
                    'paragraphs' => [
                        'The Services/Products may provide links to other websites or resources operated by third parties.',
                    ],
                ],
                [
                    'id' => 'proprietary-rights',
                    'heading' => 'Proprietary Rights of BusinessEx',
                    'paragraphs' => [
                        'The User acknowledges and agrees that content on the Website is protected by copyrights, trademarks, and other proprietary rights.',
                    ],
                ],
                [
                    'id' => 'disclaimer-warranties',
                    'heading' => 'Disclaimer of Warranties',
                    'paragraphs' => [
                        [
                            'The Services are provided on an "as is" and "as available" basis.',
                            'BusinessEx expressly disclaims all warranties of any kind, whether express or implied.',
                            'No advice or information obtained from BusinessEx shall create any warranty not expressly stated in this Agreement.',
                        ],
                    ],
                ],
                [
                    'id' => 'limitation-liability',
                    'heading' => 'Limitation of Liability',
                    'paragraphs' => [
                        [
                            'BusinessEx shall not be liable for any indirect, incidental, or consequential damages.',
                            'In no event shall BusinessEx’s aggregate liability exceed Rs. 100/-.',
                        ],
                    ],
                ],
                [
                    'id' => 'amendment',
                    'heading' => 'Amendment',
                    'paragraphs' => [
                        'BusinessEx may modify this Agreement at any time, effective immediately upon posting of the modified Agreement on the Website.',
                    ],
                ],
                [
                    'id' => 'general',
                    'heading' => 'General',
                    'paragraphs' => [
                        'This Agreement shall be governed by the laws of India, with jurisdiction in New Delhi.',
                    ],
                ],
            ],
        ],
        'contact' => [
            'title' => 'Contact',
            'description' => 'BusinessEx.com is a networking platform that helps you find solutions for your business problems with proper connections. For more information, get connected with us by filling up the required information below.',
            'sections' => [],
            'form' => true,
            'contact_heading' => 'Reach Us',
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
