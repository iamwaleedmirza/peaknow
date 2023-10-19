@extends('user.dashboard.dashboard')

@section('title') Payment method @endsection

@section('content')

    <div class="row justify-content-center mx-0 px-0">
        <div class="col-10 col-md-10 px-0">
            <div class="d-flex flex-column flex-md-row">
                <div class="payment-methods">
                    <p class="t-semi">Payment methods</p>
                    <div class="credit-card mb-5">
                        <!-- Card to be added here -->
                    </div>
                    <div class="credit-card-details">
                        <div class="mb-3">
                            <p class="text-uppercase t-semi mb-1">card number</p>
                            <p>info</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-uppercase t-semi mb-1">NAME ON THE CARD</p>
                            <p>info</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-uppercase t-semi mb-1">EXPIRY DATE</p>
                            <p>info</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-uppercase t-semi mb-1">SECURITY CODE</p>
                            <p>info</p>
                        </div>
                    </div>
                </div>

                <div class="add-new-card d-flex flex-column align-items-center">
                    <img src="{{ asset('/images/svg/ic-add.svg') }}">
                    <p class="t-semi mt-4">Add another card</p>
                </div>
            </div>
        </div>
    </div>

@endsection
