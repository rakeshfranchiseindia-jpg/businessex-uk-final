{{--
    UK Privacy Policy page.

    NOTE: This is a NEW, separate page for the UK launch — the original
    resources/views/statics/privacy.blade.php (served at /privacy-policy)
    is intentionally left untouched. This page is served at /privacy-policy-uk.

    Design is modelled on Hertz UK's privacy policy page (quick-summary
    card grid up top + a sticky section-navigation sidebar that scrolls the
    same page down to each section on click). Content blends BusinessEx's
    own existing privacy copy (introduction, Grievance Officer / contact
    block reused as-is) with generic UK GDPR-style structure/wording taken
    from that reference page (Scope & Controller, Your Rights, Definitions,
    expanded Collection/Purposes/Retention detail) where the original
    project copy was only a placeholder line.
--}}
@extends('layouts.app')

@section('title', 'Privacy Policy | BusinessEx UK')

@push('styles')
<style>
    .uk-privacy-intro p { margin-bottom: 1rem; }

    /* Quick-summary card grid (mirrors the highlight boxes at the top of
       the Hertz UK privacy page) */
    .uk-privacy-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin: 30px 0 40px;
    }
    @media (max-width: 991px) {
        .uk-privacy-summary { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575px) {
        .uk-privacy-summary { grid-template-columns: 1fr; }
    }
    .uk-privacy-summary-card {
        background: #fff;
        border: 1px solid #eef1f6;
        border-top: 3px solid #2563eb;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.08);
        padding: 20px 22px;
    }
    .uk-privacy-summary-card h3 {
        color: #2563eb;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: .03em;
        margin-bottom: 12px;
        text-transform: uppercase;
    }
    .uk-privacy-summary-card ul {
        margin: 0;
        padding-left: 18px;
    }
    .uk-privacy-summary-card li {
        color: #555;
        font-size: 13.5px;
        line-height: 1.55;
        margin-bottom: 6px;
    }

    /* Mobile section jump-menu (shown only below the layout breakpoint) */
    .uk-privacy-mobile-nav { display: none; margin-bottom: 24px; }
    .uk-privacy-mobile-nav select {
        border: 1px solid #d7dde5;
        border-radius: 6px;
        color: #2563eb;
        font-weight: 600;
        padding: 10px 14px;
        width: 100%;
    }

    /* Two-column layout: content + sticky section nav */
    .uk-privacy-layout {
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }
    .uk-privacy-content { flex: 1 1 auto; min-width: 0; }
    .uk-privacy-nav-col { flex: 0 0 260px; }
    .uk-privacy-nav {
        background: #fff;
        border: 1px solid #eef1f6;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.06);
        padding: 10px 0;
        position: sticky;
        top: 20px;
    }
    .uk-privacy-nav a {
        border-left: 3px solid transparent;
        color: #555;
        display: block;
        font-size: 13.5px;
        padding: 9px 18px;
        text-decoration: none;
        transition: all .15s ease;
    }
    .uk-privacy-nav a:hover { background: #f5faf9; color: #2563eb; }
    .uk-privacy-nav a.is-active {
        background: #f0f9f7;
        border-left-color: #2563eb;
        color: #2563eb;
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .uk-privacy-nav-col { display: none; }
        .uk-privacy-mobile-nav { display: block; }
    }

    /* Fixed navbar (nav.navbar-default.fixed-top) is ~84px tall — offset the
       scroll landing spot so a clicked section's heading lands just below
       it instead of hiding underneath it, and darken the heading itself so
       the section you land on stands out. */
    .uk-privacy-content .page-ttl.sub {
        scroll-margin-top: 100px;
        color: #231F20;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<main id="main">
<div class="container bex-main uk-privacy-page">
    <div class="row">
        <div class="col-12">
            <ul class="brunnar">
                <li><a href="/">Home</a></li>
                <li>/</li>
                <li>Privacy Policy (UK)</li>
            </ul>
        </div>
    </div>

    <div class="page-ttl">
        <h1>Privacy Policy</h1>
    </div>

    <div class="row backbg">
        <div class="col-12">

            {{-- Introduction — reused from the existing BusinessEx privacy policy --}}
            <div class="shrt-desc uk-privacy-intro">
                <p>We value the trust you place in us. That's why we insist upon the highest standards for secure transactions and customer information privacy. Please read the following statement to learn about our information gathering and dissemination practices. Note that our privacy policy is subject to change at any time without notice. To make sure you are aware of any changes, please review this policy periodically.</p>
                <p>By visiting this Website (i.e. www.BusinessEx.com) you agree to be bound by the terms and conditions of this Privacy Policy. If you do not agree please do not use or access our Website.</p>
                <p>By mere use of the Website, you expressly consent to our use and disclosure of your personal information in accordance with this Privacy Policy. This Privacy Policy is incorporated into and forms part and parcel of the End Terms of Use.</p>
            </div>

            {{-- Quick-summary grid, mirrors the highlight cards at the top of the Hertz UK reference page --}}
            <div class="uk-privacy-summary">
                <div class="uk-privacy-summary-card">
                    <h3>Collection</h3>
                    <ul>
                        <li>We collect information you give us directly — such as your name, email, phone number and business/profile details — when you register or use the Website.</li>
                        <li>We also collect technical information such as your IP address, browser and device type.</li>
                        <li>Cookies and similar tools are used to understand how you use our Website.</li>
                    </ul>
                </div>
                <div class="uk-privacy-summary-card">
                    <h3>Purposes</h3>
                    <ul>
                        <li>To create and manage your account and profile listings.</li>
                        <li>To connect you with relevant Businesses, Investors, Mentors and Startups on the platform.</li>
                        <li>To communicate with you, and — with your consent — send updates about our services.</li>
                    </ul>
                </div>
                <div class="uk-privacy-summary-card">
                    <h3>Sharing</h3>
                    <ul>
                        <li>We do not sell your personal information.</li>
                        <li>We may share information with our group companies and trusted service providers who help us run the Website.</li>
                        <li>Information may be disclosed where required by law.</li>
                    </ul>
                </div>
                <div class="uk-privacy-summary-card">
                    <h3>Retention &amp; Security</h3>
                    <ul>
                        <li>We keep your personal data only for as long as necessary for the purposes it was collected for, or as required by law.</li>
                        <li>We use reasonable technical and organisational measures to protect your data.</li>
                    </ul>
                </div>
                <div class="uk-privacy-summary-card">
                    <h3>Your Rights</h3>
                    <ul>
                        <li>You can ask to access, correct, or delete your personal data at any time.</li>
                        <li>Where we rely on your consent, you can withdraw it at any time.</li>
                    </ul>
                </div>
                <div class="uk-privacy-summary-card">
                    <h3>Questions &amp; Changes</h3>
                    <ul>
                        <li>We may update this Policy from time to time; changes take effect once posted here.</li>
                        <li>Contact our Grievance Officer below with any questions.</li>
                    </ul>
                </div>
            </div>

            {{-- Mobile jump-to-section menu --}}
            <div class="uk-privacy-mobile-nav">
                <select id="ukPrivacyMobileNav" aria-label="Jump to section">
                    <option value="">Jump to a section…</option>
                    <option value="uk-scope-controller">Scope &amp; Controller</option>
                    <option value="uk-collection">Collection of Personally Identifiable Information</option>
                    <option value="uk-use-of-data">Use of Your Information</option>
                    <option value="uk-cookies">Cookies</option>
                    <option value="uk-sharing">Sharing of Personal Information</option>
                    <option value="uk-links">Links to Other Sites</option>
                    <option value="uk-retention-security">Retention &amp; Security</option>
                    <option value="uk-your-rights">Your Rights</option>
                    <option value="uk-opt-out">Choice / Opt-Out</option>
                    <option value="uk-ads">Advertisements on BusinessEx.com</option>
                    <option value="uk-consent">Your Consent</option>
                    <option value="uk-questions-changes">Questions &amp; Changes</option>
                    <option value="uk-grievance-officer">Grievance Officer</option>
                    <option value="uk-definitions">Definitions</option>
                </select>
            </div>

            <div class="uk-privacy-layout">

                {{-- Main content column --}}
                <div class="uk-privacy-content">

                    <div class="page-ttl sub" id="uk-scope-controller">Scope &amp; Controller</div>
                    <div class="shrt-desc">
                        <p>This Policy applies to your use of the BusinessEx website and services when accessed from the UK. The data controller responsible for your personal information is Scale Media International Ltd, trading as BusinessEx (details under Grievance Officer / Contact below). This Policy does not cover any third-party websites, apps or services that BusinessEx may link to, or any third parties you choose to transact with through the platform.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-collection">Collection of Personally Identifiable Information and other Information</div>
                    <div class="shrt-desc">
                        <p>When you use our Website, we collect and store personal information which is provided by you from time to time. This may include, but is not limited to:</p>
                        <ul>
                            <li>Identity and contact details — name, email address, phone number, company/organisation name.</li>
                            <li>Account and profile information — the profile type(s) you register for (Business, Investor, Mentor, Startup, Broker, Incubation), listing details, proposals sent or received, and other information you choose to add to your profile.</li>
                            <li>Technical information — IP address, browser type, device identifiers and operating system, collected automatically when you use the Website.</li>
                            <li>Usage information — pages visited, features used and how you interact with the Website, collected via cookies and similar technologies (see Cookies below).</li>
                        </ul>
                        <p>Our Website may also enable you to fill in your details and communicate with other members. We are not responsible for the privacy practices, or the content, of any such third party.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-use-of-data">Use of Demographic / Profile Data / Your Information</div>
                    <div class="shrt-desc">
                        <p>We use personal information to provide the services you request. Specifically, we may use your information to:</p>
                        <ul>
                            <li>Create and administer your account, and match your profile with relevant Businesses, Investors, Mentors, Startups and other members of the platform.</li>
                            <li>Respond to enquiries, process proposals, and provide customer support.</li>
                            <li>Improve our Website and services, including through aggregated, anonymised analysis of how the Website is used.</li>
                            <li>Send you service-related communications, and — where you have agreed to receive them — marketing updates about BusinessEx.</li>
                            <li>Comply with our legal and regulatory obligations.</li>
                        </ul>
                    </div>

                    <div class="page-ttl sub" id="uk-cookies">Cookies</div>
                    <div class="shrt-desc">
                        <p>A "cookie" is a small piece of information stored by a web server on a web browser so it can be later read back from that browser. We use cookies on our Website for a few different purposes:</p>
                        <ul>
                            <li><strong>Essential cookies</strong> — required for the Website to function, for example to keep you logged in and to remember choices you make while browsing.</li>
                            <li><strong>Functionality cookies</strong> — remember preferences from previous visits so we can tailor the Website to you.</li>
                            <li><strong>Performance/analytics cookies</strong> — help us understand how visitors use the Website so we can improve it.</li>
                            <li><strong>Advertising cookies</strong> — used, where applicable, to make the ads you see more relevant.</li>
                        </ul>
                        <p>Most browsers let you refuse or delete cookies. Please note that if you disable cookies, some parts of the Website may not work as intended.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-sharing">Sharing of personal information</div>
                    <div class="shrt-desc">
                        <p>We may share personal information with our other corporate entities and affiliates to help detect and prevent identity theft, fraud and other misuse of our services. We may also disclose personal information to:</p>
                        <ul>
                            <li>Trusted third-party service providers who perform functions on our behalf, such as hosting, email delivery and analytics, under an obligation to keep it confidential and secure.</li>
                            <li>Other members of the platform, but only to the extent needed to facilitate an introduction, proposal or transaction you have initiated.</li>
                            <li>Law enforcement, regulators or other third parties where we are required to do so by law, or to protect our rights, property or the safety of our users.</li>
                            <li>A successor entity, in connection with any merger, acquisition or sale of BusinessEx's business or assets.</li>
                        </ul>
                        <p>We do not sell your personal information to unrelated third parties.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-links">Links to Other Sites</div>
                    <div class="shrt-desc">
                        <p>Our Website links to other websites that may collect personally identifiable information about you. BusinessEx is not responsible for the privacy practices or the content of such other websites, and we encourage you to read the privacy policy of any third-party website you visit.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-retention-security">Retention &amp; Security</div>
                    <div class="shrt-desc">
                        <p>Our Website has stringent security measures in place to protect against the loss, misuse and alteration of the information under our control. We use reasonable administrative, technical and physical safeguards to protect your personal data.</p>
                        <p>We keep personal data only for as long as we believe is necessary for the purpose it was collected for, or as required to meet our legal, regulatory or reporting obligations, after which it is deleted or anonymised.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-your-rights">Your Rights</div>
                    <div class="shrt-desc">
                        <p>If UK data protection law applies to you, you have certain rights in relation to your personal data, including the right to:</p>
                        <ul>
                            <li>Access the personal data we hold about you.</li>
                            <li>Request correction of any inaccurate or incomplete data.</li>
                            <li>Request erasure of your data, in certain circumstances.</li>
                            <li>Object to, or request restriction of, certain processing of your data.</li>
                            <li>Request a copy of your data in a portable format, where technically feasible.</li>
                            <li>Withdraw your consent at any time, where we rely on consent as the basis for processing.</li>
                        </ul>
                        <p>We will do our best to accommodate any such request, though we may need to apply certain restrictions permitted by law. To exercise any of these rights, please contact our Grievance Officer using the details below.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-opt-out">Choice/Opt-Out</div>
                    <div class="shrt-desc">
                        <p>We provide all users with the opportunity to opt-out of receiving non-essential (promotional/marketing) communications from us. If you no longer wish to receive these communications, you may opt-out by following the unsubscribe instructions included in each such message, or by contacting us directly.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-ads">Advertisements on BusinessEx.com</div>
                    <div class="shrt-desc">
                        <p>We may use third-party companies' services to serve ads when you visit our Website. These companies may use non-personally-identifiable information about your visits to this and other websites in order to provide advertisements about goods and services that may be of interest to you.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-consent">Your Consent</div>
                    <div class="shrt-desc">
                        <p>By using the Website and/or by providing your information, you consent to the collection and use of the information you disclose on the Website in accordance with this Privacy Policy, including but not limited to your consent for sharing your information as per this Policy.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-questions-changes">Questions &amp; Changes</div>
                    <div class="shrt-desc">
                        <p>If you have any questions or concerns about how we process your personal data, please contact our Grievance Officer using the details below. We may modify this Policy at any time; changes will be posted on this page and will take effect from the date of posting, so please check back periodically.</p>
                    </div>

                    <div class="page-ttl sub" id="uk-grievance-officer">Grievance Officer</div>
                    <div class="shrt-desc privacy">
                        <p>In accordance with Information Technology Act 2000 and rules made there under, the name and contact details of the Grievance Officer are provided below:</p>
                        <div class="row">
                            <div class="col-sm-3 col-md-2 bld">Name<span>:</span></div>
                            <div class="col-sm-9 col-md-10">Dharmendra Yadav</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-md-2 bld">Designation<span>:</span></div>
                            <div class="col-sm-9 col-md-10">Technical Lead</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-md-2 bld">Address<span>:</span></div>
                            <div class="col-sm-9 col-md-10">
                                SCALE MEDIA INTERNATIONAL LTD.<br>
                                GLOBAL OFFICE - 220, WARDS ROAD, ILFORD, ENGLAND, IG2 7DY
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-md-2 bld">Phone<span>:</span></div>
                            <div class="col-sm-9 col-md-10">+91.129.4228873</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-md-2 bld">Email<span>:</span></div>
                            <div class="col-sm-9 col-md-10">info@worldtradecouncil.com</div>
                        </div>
                    </div>

                    <div class="page-ttl sub" id="uk-definitions">Definitions</div>
                    <div class="shrt-desc">
                        <ul>
                            <li><strong>Personal Data</strong> — any information relating to an identified or identifiable individual.</li>
                            <li><strong>Processing</strong> — anything done with personal data, such as collecting, storing, using, disclosing or deleting it.</li>
                            <li><strong>Controller</strong> — the organisation that decides why and how personal data is processed (BusinessEx, in relation to the data described in this Policy).</li>
                            <li><strong>Processor</strong> — a third party that processes personal data on the controller's behalf, such as one of our service providers.</li>
                        </ul>
                    </div>

                </div>

                {{-- Sticky section navigation, mirrors the Hertz UK reference page's sidebar --}}
                <div class="uk-privacy-nav-col">
                    <nav class="uk-privacy-nav" aria-label="Privacy Policy sections">
                        <a href="#uk-scope-controller">Scope &amp; Controller</a>
                        <a href="#uk-collection">Collection of Information</a>
                        <a href="#uk-use-of-data">Use of Your Information</a>
                        <a href="#uk-cookies">Cookies</a>
                        <a href="#uk-sharing">Sharing</a>
                        <a href="#uk-links">Links to Other Sites</a>
                        <a href="#uk-retention-security">Retention &amp; Security</a>
                        <a href="#uk-your-rights">Your Rights</a>
                        <a href="#uk-opt-out">Choice / Opt-Out</a>
                        <a href="#uk-ads">Advertisements</a>
                        <a href="#uk-consent">Your Consent</a>
                        <a href="#uk-questions-changes">Questions &amp; Changes</a>
                        <a href="#uk-grievance-officer">Grievance Officer</a>
                        <a href="#uk-definitions">Definitions</a>
                    </nav>
                </div>

            </div>
        </div>
    </div>
</div>
    {{--@include('includes.groupcompany')--}}
    @include('includes.newsletter')
    @include('includes.categorylinkfooter')
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var navLinks = Array.prototype.slice.call(
            document.querySelectorAll('.uk-privacy-nav a')
        );
        var mobileNav = document.getElementById('ukPrivacyMobileNav');

        if (!navLinks.length) {
            return;
        }

        var sections = navLinks
            .map(function (link) {
                return document.getElementById(link.getAttribute('href').slice(1));
            })
            .filter(Boolean);

        /*
         * Clicking a sidebar (or mobile dropdown) link smooth-scrolls the
         * SAME page down to that section instead of jumping instantly.
         */
        function scrollToSection(id) {
            var target = document.getElementById(id);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        navLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                scrollToSection(this.getAttribute('href').slice(1));
                history.replaceState(null, '', this.getAttribute('href'));
            });
        });

        if (mobileNav) {
            mobileNav.addEventListener('change', function () {
                if (this.value) {
                    scrollToSection(this.value);
                }
            });
        }

        /*
         * Highlight the sidebar link for whichever section is currently in
         * view, so the nav tracks scroll position.
         */
        if ('IntersectionObserver' in window && sections.length) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    navLinks.forEach(function (link) {
                        link.classList.toggle(
                            'is-active',
                            link.getAttribute('href') === '#' + entry.target.id
                        );
                    });
                });
            }, { rootMargin: '-20% 0px -70% 0px', threshold: 0 });

            sections.forEach(function (section) {
                observer.observe(section);
            });
        }

    });
</script>
@endpush
