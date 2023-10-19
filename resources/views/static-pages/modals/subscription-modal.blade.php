<div class="modal fade" id="modalSubscription" tabindex="-1" aria-labelledby="modalSubscriptionLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close no_thanks_btn px-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-md-4">

                <div class="row">
                    <img src="{{ asset('images/png/popup-product-img.png') }}" class="subscription-img"/>

                    <p class="subscription-modal-text">
                        Welcome to Peaks Curative! Submit email address below for
                        <span class="h3">15% off</span> PROMO CODE!
                    </p>

                    <form action="{{ route('home.email.subscription') }}" method="post" id="home_subscription_form">
                        @csrf
                        <x-honeypot/>

                        <div class="mb-3">
                            <div id="errors" class="mt-2"></div>

                            <div class="form-group mb-3">
                                <input id="input_email" type="email" class="input-field input-primary" name="email"
                                       placeholder="E-mail" required>
                            </div>

                            <div class="form-check checkbox-layout">
                                <input class="form-check-input" type="checkbox" value="1" name="agreement"
                                       id="agreement" required>
                                <label class="form-check-label t-color text-start" for="agreement">
                                    I confirm that I've read and agree to <a href="{{ route('terms-conditions') }}" class="t-link text-decoration-underline" target="_blank">Terms & Conditions.</a>
                                </label>
                                <br>
                                <div class="agreementLabel"></div>
                            </div>
                        </div>
                        @if(env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')
                            <div class="form-group">
                                <div class="d-flex flex-column align-items-center">
                                    {!!  GoogleReCaptchaV3::renderField('user_subscribe_id', 'user_subscribe') !!}
                                </div>
                            </div>
                        @endif
                        <div class="text-center">
                            <button class="btn newBtn btn-peaks submitBTN" type="submit">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
