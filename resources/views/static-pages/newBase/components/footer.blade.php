<footer class="peaks">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				<div class="ftr-cont">
					<img src="{{ asset('logo/Logo-Purple.svg') }}" alt="Website Logo Black">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="ftr-cont">
					<h3>MENU</h3>
					<a href="{{ route('about-us') }}">About Us</a>
					<a href="{{ route('faq') }}">FAQ's</a>
					<a href="{{ route('contact-us') }}">Contact Us</a>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="ftr-cont xtr-lft-pdg">
					<h3>HELPFUL LINKS</h3>
					<a href="{{ route('terms-conditions') }}" target="_blank">Terms & Conditions</a>
					<a href="{{ route('privacy-policy') }}" target="_blank">Privacy Policy</a>
					<a href="{{ route('refund-policy') }}" target="_blank">Refund Policy</a>
					<a>We Don't sell your Information</a>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ftr-cont">
					<h3>SOCIAL</h3>
					<a href="{{ ($site_setting) ? $site_setting->facebook_link : '#' }}" target="_blank">Facebook</a>
					<a href="{{ ($site_setting) ? $site_setting->twitter_link : '#' }}" target="_blank">Twitter</a>
					<a href="{{ ($site_setting) ? $site_setting->instagram_link : '#' }}" target="_blank">Instagram</a>
					<a href="mailto:{{ ($site_setting) ? $site_setting->support_mail : '#' }}" target="_blank">E-mail</a>
				</div>
			</div>
			<div class="col-lg-12">
                <div class="cptxt">Copyright © {{ now()->format('Y') }} <span>Peaks Curative.</span> All rights reserved.</div>
			</div>
		</div>
	</div>
</footer>
