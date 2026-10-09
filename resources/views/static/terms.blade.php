@extends('layouts.app')

@section('title', 'Terms')

@section('content')
<main id="main">
<div class="container bex-main">
    <div class="row">
        <div class="col-12">
            <ul class="brunnar">
                <li><a href="#">Home</a></li>
                <li>/</li>
                <li>Terms Of Use</li>
            </ul>
        </div>
    </div>

    <div class="page-ttl">
        <h1>Terms Of Use</h1>
    </div>

    <!-- Terms Content -->
    <div class="row backbg">
        <div class="col-12">
            <div class="shrt-desc">
                <p>These Terms of Use (these "Terms") govern the web pages of the 
                    <a href="/">www.BusinessEx.com</a> ("Website"). This document is an electronic record in terms of Information Technology Act, 2000...
                </p>
                <p>This document is published in accordance with the provisions of Rule 3 (1) of the Information Technology (Intermediaries guidelines) Rules, 2011...</p>
                <p>The domain name <a href="/">www.Businessex.com</a> is owned by BusinessEx, a business unit of Franchise India Brands Limited...</p>
                <p>By using this Website, you (the "User") are agreeing to these terms...</p>

                <ul>
                    <li>
                        <div>ACKNOWLEDGMENT AND ACCEPTANCE OF AGREEMENT</div>
                        The Services/Products provided by BusinessEx are provided to the User under the terms and conditions of this Agreement...
                    </li>
                    <li>
                        <div>REGARDING SERVICES/PRODUCTS</div>
                        BusinessEx provides free as well as paid Services/Products...
                    </li>
                    <li>
                        <div>USER'S REGISTRATION OBLIGATIONS</div>
                        The User must be at least eighteen (18) years old to register...
                    </li>
                    <li>
                        <div>USER DATA</div>
                        The User acknowledges that Registration Data is to be stored with BusinessEx...
                    </li>
                    <li>
                        <div>COMMUNICATION TO USERS</div>
                        BusinessEx may communicate with registered users via promotional and transactional emails, SMS, and calls...
                    </li>
                    <li>
                        <div>USER NAME, MEMBER ACCOUNT, PASSWORD AND SECURITY</div>
                        The User may be asked to choose a username...
                    </li>
                    <li>
                        <div>USER CONDUCT &amp; OBLIGATIONS</div>
                        <ul class="sub-list">
                            <li>The User agrees to abide by all applicable laws...</li>
                            <li>The User further agrees to be liable for all communications...</li>
                            <li>The User agrees not to use the Services/Products for illegal purposes...</li>
                            <li>The User agrees not to upload or transmit unlawful or objectionable material...</li>
                            <li>The User agrees not to attempt unauthorized access to other systems...</li>
                            <li>If the User has availed Services/Products, it shall ensure to share required information...</li>
                        </ul>
                    </li>
                    <li>
                        <div>INDEMNITY</div>
                        The User agrees to indemnify and hold BusinessEx harmless from any claim or demand...
                    </li>
                    <li>
                        <div>STORAGE OF COMMUNICATIONS</div>
                        BusinessEx assumes no responsibility for deletion or failure to store communications...
                    </li>
                    <li>
                        <div>TERMINATION</div>
                        <ul class="sub-list">
                            <li>The User agrees that BusinessEx may terminate the User's account if terms are violated...</li>
                            <li>Termination may also occur if payments are not made, misrepresentation occurs, or obligations are breached...</li>
                        </ul>
                    </li>
                    <li>
                        <div>LINKS</div>
                        The Services/Products may provide links to other websites or resources...
                    </li>
                    <li>
                        <div>PROPRIETARY RIGHTS of BUSINESSEX</div>
                        The User acknowledges and agrees that content is protected by copyrights, trademarks, and other rights...
                    </li>
                    <li>
                        <div>DISCLAIMER OF WARRANTIES</div>
                        <ul class="sub-list">
                            <li>The Services are provided on an "as is" and "as available" basis...</li>
                            <li>BusinessEx expressly disclaims all warranties of any kind...</li>
                            <li>No advice or information obtained shall create any warranty not expressly stated...</li>
                        </ul>
                    </li>
                    <li>
                        <div>LIMITATION OF LIABILITY</div>
                        <ul class="sub-list">
                            <li>BusinessEx shall not be liable for indirect, incidental, or consequential damages...</li>
                            <li>In no event shall BusinessEx be liable for any aggregate amount in excess of Rs.100/-...</li>
                        </ul>
                    </li>
                    <li>
                        <div>AMENDMENT</div>
                        BusinessEx may modify this Agreement at any time, effective immediately upon posting...
                    </li>
                    <li>
                        <div>GENERAL</div>
                        This Agreement shall be governed by the laws of India, with jurisdiction in New Delhi...
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
    {{--@include('includes.groupcompany')--}}
    @include('includes.newsletter')
    @include('includes.categorylinkfooter')
</main>
@endsection