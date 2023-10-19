@extends('static-pages.base.main')

@section('title') About Us @endsection

@section('meta_description')
    Peaks Curative helps connect patients with doctors for the purpose of treating Erectile Dysfunction (ED) in men. Treatments may inclued tadalafil or sildenafil
@endsection

@section('css')
    <link href="{{ asset('/css/peaks/about-us.css') }}" rel="stylesheet"/>
@endsection

@section('content')

    <section class="section section-our-story">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="our-story-images-wrapper mb-3">
                    <div class="our-img-1">
                        <img src="{{ asset('/images/png/img-our-story-1.png') }}" alt="Our Story Image"/>
                        <div class="our-img-2">
                            <img class="d-none d-md-block" alt="Our Story Image"
                                 src="{{ asset('/images/png/img-our-story-2.png') }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-md-8 col-lg-6 text-center">
                <h2>#BeAtYourPeak</h2>
                <p>
                    #BeAtYourPeak <br>
                    We believe in a world where anybody can reach their full potential and take control of their sexual
                    life in an easy and enjoyable way. That motivates us to go the extra mile and bring you the
                    tastiest, easy, safe, and discreet way to treat ED: gummies. As a thank you to our early supporters,
                    we offer a PROMO CODE with registration, active on launch (Spring&nbsp2022)
                </p>
            </div>
        </div>
    </section>

    <section class="section section-why-you-need-us">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-12 col-xl-10">
                <div class="d-md-flex flex-row">
                    <div class="pt-md-5">
                        <div class="row justify-content-center mx-0 px-0">
                            <div class="col-10 col-md-8">
                                <p class="t-small t-semi text-head">KEY BENEFITS</p>
                                <h2 class="need-us-title h-color-light">Why use Peaks Curative?</h2>
                                <p class="need-us-desc">
                                    Peaks Curative helps connect patients with doctors to provide access to professional
                                    advice and prescription treatments for Erectile Dysfunction (ED) in the form of
                                    gummies and capsules.
                                    <br>
                                    <br>
                                    <a href="{{ route('faq') }}" class="t-link mb-0">
                                        Learn more in our FAQs
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row justify-content-center mx-0 px-0 gap-4 mb-3">
                            <div class="col-6 ethics-card">
                                <i class="fas fa-smile-beam ethics-img mt-3"></i>
                                <p class="text-uppercase t-semi">TASTY</p>
                            </div>
                            <div class="col-6 ethics-card">
                                <i class="fas fa-chart-line ethics-img mt-3"></i>
                                <p class="text-uppercase t-semi">EFFECTIVE</p>
                            </div>
                        </div>
                        <div class="row justify-content-center mx-0 px-0 gap-4">
                            <div class="col-6 ethics-card">
                                <i class="fas fa-thumbs-up ethics-img mt-3"></i>
                                <p class="text-uppercase t-semi">CONVENIENT</p>
                            </div>
                            <div class="col-6 ethics-card">
                                <i class="fas fa-user-shield ethics-img mt-3"></i>
                                <p class="text-uppercase t-semi">SAFE</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-more-benefits">
        <div class="section-body">
            <div class="section-title mb-5 mx-2">
                THE PEAKS CURATIVE WAY
            </div>
            <div class="row justify-content-center mx-0 px-0">
                <div class="col-10 col-md-6">
                    <div class="row mb-3">
                        <div class="col-2">
                            <img src="{{ asset('/images/svg/green-tick.svg') }}">
                        </div>
                        <div class="col-10">
                            <h5>No more pills!</h5>
                            <p>
                                Peaks Curative gummies provide a tasty, easy, safe & enjoyable form of treatment for
                                erectile dysfunction & more.
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <img src="{{ asset('/images/svg/green-tick.svg') }}">
                        </div>
                        <div class="col-10">
                            <h5>100% online health solution</h5>
                            <p>
                                Peaks Curative will facilitate online visits between patient and doctor. Once the doctor
                                issues the RX, it will be sent to the pharmacy and our branded medicine will be sent to
                                the patient on a monthly basis or on an as-needed basis.
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <img src="{{ asset('/images/svg/green-tick.svg') }}">
                        </div>
                        <div class="col-10">
                            <h5>US Brand</h5>
                            <p>
                                We are licensed in the US, born, and raised brand. Our products are made in the US,
                                meeting the highest industry standards.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
