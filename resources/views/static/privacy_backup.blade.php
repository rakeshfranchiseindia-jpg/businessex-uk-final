@extends('layouts.app')

@section('title', 'Privacy')

@section('content')
<div class="container bex-main">
    <div class="row">
        <div class="col-12">
            <ul class="brunnar">
                <li><a href="/">Home</a></li>
                <li>/</li>
                <li>Privacy Policy</li>
            </ul>
        </div>
    </div>

    <div class="page-ttl">
        <h1>Privacy Policy</h1>
    </div>

    <div class="row backbg">
        <div class="col-12">
            <!-- Introduction -->
            <div class="shrt-desc">
                <p>We value the trust you place in us. That's why we insist upon the highest standards for secure transactions and customer information privacy. Please read the following statement to learn about our information gathering and dissemination practices. Note that our privacy policy is subject to change at any time without notice. To make sure you are aware of any changes, please review this policy periodically.</p>
                <p>By visiting this Website (i.e. www.BusinessEx.com) you agree to be bound by the terms and conditions of this Privacy Policy. If you do not agree please do not use or access our Website.</p>
                <p>By mere use of the Website, you expressly consent to our use and disclosure of your personal information in accordance with this Privacy Policy. This Privacy Policy is incorporated into and forms part and parcel of the End Terms of Use.</p>
            </div>

            <!-- Collection of Information -->
            <div class="page-ttl sub">Collection of Personally Identifiable Information and other Information</div>
            <div class="shrt-desc">
                <p>When you use our Website, we collect and store your personal information which is provided by you from time to time...</p>
                <!-- (Content continues as in your document) -->
            </div>

            <!-- Use of Data -->
            <div class="page-ttl sub">Use of Demographic / Profile Data / Your Information</div>
            <div class="shrt-desc">
                <p>We use personal information to provide the services you request...</p>
            </div>

            <!-- Cookies -->
            <div class="page-ttl sub">Cookies</div>
            <div class="shrt-desc">
                <p>A "cookie" is a small piece of information stored by a web server on a web browser...</p>
            </div>

            <!-- Sharing -->
            <div class="page-ttl sub">Sharing of personal information</div>
            <div class="shrt-desc">
                <p>We may share personal information with our other corporate entities and affiliates...</p>
            </div>

            <!-- Links -->
            <div class="page-ttl sub">Links to Other Sites</div>
            <div class="shrt-desc">
                <p>Our Website links to other websites that may collect personally identifiable information...</p>
            </div>

            <!-- Security -->
            <div class="page-ttl sub">Security Precautions</div>
            <div class="shrt-desc">
                <p>Our Website has stringent security measures in place to protect the loss, misuse, and alteration of the information...</p>
            </div>

            <!-- Opt-Out -->
            <div class="page-ttl sub">Choice/Opt-Out</div>
            <div class="shrt-desc">
                <p>We provide all users with the opportunity to opt-out of receiving non-essential communications...</p>
            </div>

            <!-- Ads -->
            <div class="page-ttl sub">Advertisements on BusinessEx.com</div>
            <div class="shrt-desc">
                <p>We use third-party companies' services to serve ads when you visit our Website...</p>
            </div>

            <!-- Consent -->
            <div class="page-ttl sub">Your Consent</div>
            <div class="shrt-desc">
                <p>By using the Website and/or by providing your information, you consent to the collection and use of the information...</p>
            </div>

            <!-- Grievance Officer -->
            <div class="page-ttl sub">Grievance Officer</div>
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
                {{--<div class="row">
                    <div class="col-sm-3 col-md-2 bld">Time to call<span>:</span></div>
                    <div class="col-sm-9 col-md-10">9.30 am to 6.30 pm</div>
                </div>--}}
            </div>
        </div>
    </div>
    {{-- @include('includes.groupcompany') --}}
    @include('includes.newsletter')
    @include('includes.categorylinkfooter')
</div>
@endsection