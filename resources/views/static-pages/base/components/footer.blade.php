<footer id="footer" class="footer section-light">
    <div class="row justify-content-center mx-0 px-0">
        <div class="col-10 col-xl-8 mb-4 mb-md-0">
            <div class="row">
                <div class="col-6 col-md-3 text-md-center">
                    <h4 class="text-uppercase h-color-dark">MENU</h4>
                    <div class="footer-links d-flex flex-column">
                        <a class="footer-link" href="{{ route('about-us') }}">About Us</a>
                        <a class="footer-link" href="{{ route('plans') }}">Plans</a>
                        <a class="footer-link" href="{{ route('faq') }}">FAQ</a>
                        @if(!auth()->user())
                            <a class="footer-link" href="{{ route('user.login') }}">Log in</a>
                        @endif
                        <a class="footer-link" href="{{ route('contact-us') }}">Contact Us</a>
                    </div>
                </div>
                <div class="d-none d-md-block col-md-6">
                    <div class="container-fluid">
                        <div class="d-flex flex-column align-items-center">
                            <h4 class="text-uppercase h-color-dark">HELPFUL LINKS</h4>
                            <div class="footer-links d-flex flex-column text-center">
                                <a class="footer-link" href="{{ route('terms-conditions') }}" target="_blank">Terms & Conditions</a>
                                <a class="footer-link" href="{{ route('privacy-policy') }}" target="_blank">Privacy Policy</a>
                                <a class="footer-link" href="{{ route('refund-policy') }}" target="_blank">Refund Policy</a>
                                <p class="footer-link mb-0">We Don't sell your Information</p>
                            </div>
                            <hr class="bg-secondary w-75">
                            <p class="footer-peaks-subtitle">PEAKS CURATIVE</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 text-md-center">
                    <h4 class="text-uppercase h-color-dark">SOCIAL</h4>
                    <div class="footer-links d-flex flex-column">
                        <a class="footer-link" href="{{($site_setting)?$site_setting->facebook_link:''}}"
                           target="_blank">Facebook</a>
                        <a class="footer-link" href="{{($site_setting)?$site_setting->twitter_link:''}}"
                           target="_blank">Twitter</a>
                        <a class="footer-link" href="{{($site_setting)?$site_setting->instagram_link:''}}"
                           target="_blank">Instagram</a>
                        <a class="footer-link"
                           href="mailto:{{($site_setting)?$site_setting->support_mail:''}}">E-mail</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-11 d-md-none">
            <hr class="bg-secondary d-md-none">
            <div class="d-flex flex-column align-items-center">
                <h4 class="text-uppercase h-color-dark">HELPFUL LINKS</h4>
                <div class="footer-links d-flex flex-column text-center">
                    <a class="footer-link" href="{{ route('terms-conditions') }}" target="_blank">Terms & Conditions</a>
                    <a class="footer-link" href="{{ route('privacy-policy') }}" target="_blank">Privacy Policy</a>
                    <a class="footer-link" href="{{ route('refund-policy') }}" target="_blank">Refund Policy</a>
                    <p class="footer-link mb-0">We Don't sell your Information</p>
                </div>
                <hr class="bg-secondary w-75">
                <p class="footer-peaks-subtitle">{{($site_setting && $site_setting->footer_text)?$site_setting->footer_text:'Copyright © 2022 Peaks Curative. All rights reserved.'}}</p>
            </div>
        </div>
    </div>
</footer>
