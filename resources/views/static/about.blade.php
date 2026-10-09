@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<main id="main">
<div class="container bex-main">
    <div class="row">
        <div class="col-12">
            <ul class="brunnar">
                <li><a href="#">About Us</a></li>
                <li>/</li>
                <li>About Us</li>
            </ul>
        </div>
    </div>

    <div class="page-ttl">
        <h1>About Us</h1>
    </div>

    <!-- Intro Section -->
    <div class="row">
        <div class="col-12 col-md-7">
            <strong>Business Exchange is a flagship business initiative of Franchise India, head quartered at GLOBAL OFFICE - 220, WARDS ROAD, ILFORD, ENGLAND, IG2 7DY</strong>
            <br><br>
            BusinessEx is thoughtfully conceived and created to address selling and exchanging of businesses across industry verticals. It’s a one-of-kind platform that interconnects the like-minded, gives exposure to Businesses, Start-ups, Investors, Lenders, Mentors and Incubators to find suitable business associates. Over the last 15 years we have mastered the art of creating a springboard that is capable of addressing the scopes of selling and exchanging of businesses.
            <br><br>
            Businessex.com is emerging as the fastest growing marketplace for business consultation services. It bestows the buyers & sellers an interactive and a symbiotic platform to communicate with each other. With ‘Efficiency’ as its motto, Businessex.com gives cutting-edge efficiency in terms of time, efficacy, transaction and tailored solutions to its broad spectrum of buyers and sellers of existing businesses, across industries and geographies.
        </div>

        <div class="col-12 col-md-5">
            <div class="viright">
                <iframe class="respo" width="420" height="300"
                        src="https://www.youtube.com/embed/wY4DidbzUek?loop=1;&amp;playlist=wY4DidbzUek">
                </iframe>
            </div>
        </div>
    </div>

    <div class="clr"></div>

    <!-- Mission & Vision -->
    <div class="row setbttop">
        <div class="col-12 col-md-5 modf">
            <h4 class="mhvt">Mission & Vision</h4>
            <p class="tybys">
                To provide the best online platform to connect the Businesses & Start-ups with the Investors, Lenders, Mentors and Incubator companies.
                <span>To be a single stop Business Exchange platform for the Businesses for all the business support systems & business requirements</span>
            </p>
        </div>
        <div class="col-12 col-md-7 modf">
            <div class="imgabt">
                <img src="{{ asset('assets/img/mission.png') }}" alt="Mission">
            </div>
        </div>
    </div>
</div>
    {{--@include('includes.groupcompany')--}}
    @include('includes.newsletter')
    @include('includes.categorylinkfooter')
</main>
@endsection