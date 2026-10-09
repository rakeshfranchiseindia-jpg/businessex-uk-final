@extends('layouts.app')

@section('title', 'Disclaimer')

@section('content')
<main id="main">
<div class="container bex-main">
    <div class="row">
        <div class="col-12">
            <ul class="brunnar">
                <li><a href="#">Home</a></li>
                <li>/</li>
                <li>Disclaimer</li>
            </ul>
        </div>
    </div>

    <div class="page-ttl">
        <h1>Disclaimer</h1>
    </div>

    <!-- Disclaimer Content -->
    <div class="row backbg">
        <div class="col-12">
            <p>
                This website contains general information only, and BusinessEx.com is not, by means of this website or any part thereof, rendering professional advice or services. Before making any decision or taking any action that might affect your finances or business, you should consult a qualified professional advisor. Your use of this website is at your own risk and you assume full responsibility and risk of loss resulting from your usage. To the fullest extent permissible pursuant to applicable law, BusinessEx.com, a business unit of Franchise India Brands Limited, disclaims all liability to you and everyone else in respect of the content on this site and all services provided through it, whether under any theory of tort, contract, warranty, strict liability or negligence or otherwise, and whether in respect of direct, indirect, consequential, special, punitive or similar damages, even if BusinessEx.com was advised, knew or should have known of the possibility of such damages.
            </p>

            <p>
                "The information contained in this website is for general information purposes only. We make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk. In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever."
            </p>

            <p>
                Through this website you are able to link to other websites which are not under the control of www.BusinessEx.com. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.
            </p>

            <p>
                BusinessEx.com takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.
            </p>
        </div>
    </div>
</div>
    {{--@include('includes.groupcompany')--}}
    @include('includes.newsletter')
    @include('includes.categorylinkfooter')
</main>
@endsection