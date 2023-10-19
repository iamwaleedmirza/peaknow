@extends('static-pages.newBase.main')

@section('title') Tasty & safe ED treatment gummies @endsection

@section('meta_description')
    Sexual health and performance. Get a tasty, safe and effective Erectile Dysfunction ED treatment with our gummies. Real prescription medication online
@endsection

@section('css')
    <style>
        .iti__flag-container { top: 9px !important; }
        /*.btn { height: 50px !important; }*/
        #agreement-error { display: inline;  }
        .form-check-input { width: 1em !important; height: 1em !important;  }
    </style>
@endsection

@section('header')
<header class="peaks">
    <div class="overlay">
        @include('static-pages.newBase.components.navbar')

        <div class="container">
            <span class="fear-line">Fear Of Not Performing In The Bedroom?</span>
            <div class="sex-life">Improve Your Sex Life</div>
            <h1 class="gummy">Have a Gummy</h1>
            <div class="tag-line">No more pills! No waiting rooms, No appointments.</div>
            <div class="treat-online">New ED treatment online</div>
            <a href="#plans" class="dss-btn banner-btn">Start Today</a>
            <div class="bottom-ol"></div>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="ta-sa-ef">
	<h2 class="sec-ttl">A tasty, safe & effective ED treatment</h2>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 weight-l">
				<div class="cont">
					<picture>
						<source media="(max-width:990px)" srcset="{{ asset('assets/weight-l-mob.png') }}">
						<img src="{{asset('assets/weight-l.png')}}" alt="weight lifting">
					</picture>
				</div>
			</div>
			<div class="col-lg-6 erectile">
				<div class="cont">
					<p>Tadalafil helps increase blood flow to the penis and can help men with erectile dysfunction get and keep an erection for sexual activity when sexually stimulated. Tadalafil does not induce erections in the absence of sexual stimulation.</p>
					<p><small><b>It Can Help You With</b></small></p>
					<h3>Erectile Dysfunction</h3>
					<a href="#have-any-ques" class="dss-btn">Learn More</a>
				</div>
			</div>

			<div class="col-lg-12 peaks-spacer"></div>
		</div>
	</div>
	<div class="container">
		<div class="row reverse-on-mob">
			<div class="col-lg-6 dyk">
				<div class="cont">
					<h2>Did You Know..</h2>
					<p>Tadalafil has been known to be recommended by doctors for both men and women to treat the symptoms of pulmonary arterial hypertension to improve your ability to <u>exercise</u>.</p>
					<div class="byED">
						<img src="{{asset('assets/circle.png')}}" class="piechrt" alt="peaks curative pie chart">
						<p>18-34 year olds being affected by ED, compared to 63% of those over the age of 55. However, older people are more likely to report that they are affected regularly, which may indicate an underlying health issue.
							<span>(Source: LetsGetChecked)</span>
						</p>

					</div>
				</div>
			</div>
			<div class="col-lg-6 dyk">
				<div class="cont">
					<picture>
						<source media="(max-width:990px)" srcset="{{asset('assets/purple-mob.png')}}">
						<img src="{{asset('assets/purple.png')}}" alt="Peaks Curative Product" class="purple-cards">
					</picture>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="wms-differ">
	<h2 class="sec-ttl">What Makes Us Different?</h2>
	<img src="{{asset('assets/peak.png')}}" class="peak-only" alt="peaks logo">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-4 differs">
				<h3 class="lico_1"><span>Easy</span> to consume gummies</h3>
				<hr class="divdr-ln">
				<h3 class="lico_2"><span>Free</span> shipping w/ packaging</h3>
				<hr class="divdr-ln">
			</div>
			<div class="col-lg-4 col-md-4 arms-peaks">
				<img src="{{asset('assets/arms-peaks.png')}}" alt="differents legs">
			</div>
			<div class="col-lg-4 col-md-4 differs">
				<h3 class="lico_4"><span>Free</span> online consultation</h3>
				<hr class="divdr-ln">
				<h3 class="lico_5"><span>Safe</span> & discreet packaging</h3>
				<hr class="divdr-ln">
			</div>
			<div class="col-lg-12 differs">
				<h3 class="lico_3"><span>US</span> Licensed medical provider </h3>
			</div>
		</div>
	</div>
</section>

<section class="wu-peaks">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 up-stair">
				<picture>
					<source media="(max-width:990px)" srcset="{{asset('assets/up-stair-mob.png')}}">
					<img src="{{asset('assets/up-stair.png')}}" alt="Benefits of Peaks Curative">
				</picture>
			</div>
			<div class="col-lg-6 why-use">
				<div class="cont">
					<h3>Why Use Peaks Curative?</h3>
					<p>Peaks Curative helps connect patients with doctors to provide access to professional advice and prescription treatments for Erectile Dysfunction (ED) in the form of gummies and capsules. </p>
					<div class="icon-list">
						<div class="item">
							<span><img src="{{asset('assets/smile.svg')}}" alt="tasty icon"></span> Tasty
						</div>
						<div class="item">
							<span><img src="{{asset('assets/like.svg')}}" alt="like icon"></span> Effective
						</div>
						<div class="item">
							<span><img src="{{asset('assets/graph.svg')}}" alt="growth graph icon"></span> Convenient
						</div>
						<div class="item">
							<span><img src="{{asset('assets/safe.svg')}}" alt="user safe icon"></span> Safe
						</div>
					</div>
					<a href="{{ route('faq') }}" class="dss-btn">Explore FAQ’s</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="three-steps">
	<div class="container">
		<h2 class="sec-ttl">Start With 3 Easy Steps</h2>
		<h3 class="bottom-line">A 100% ONLINE PROCESS THAT WILL GUARANTEE A SAFE AND DISCREET EXPERIENCE. </h3>
{{--		<div class="lunch-spring">LAUNCH: SPRING 2022</div>--}}
		<div class="row reverse-on-mob">
			<div class="col-lg-5 why-use">
				<div class="steps-cont">
					<b>1</b>
					<p>Answer a few simple questions online. (Just a few, we promise ✌)</p>
				</div>
				<div class="steps-cont">
					<b>2</b>
					<p>A licensed medical provider will evaluate your answers and prescribe your best treatment option.</p>
				</div>
				<div class="steps-cont">
					<b>3</b>
					<p>Receive your prescribed medication at your doorstep with free and discreet shipping.</p>
				</div>
				<a href="{{ route('contact-us') }}" class="dss-btn">Contact Us Now</a>
			</div>
			<div class="col-lg-7 man-sea">
				<picture>
					<source media="(max-width:990px)" srcset="{{asset('assets/man-sea-mob.png')}}">
					<img src="{{asset('assets/man-sea.png')}}" alt="Benefits of Peaks Curative">
				</picture>
			</div>
		</div>
	</div>
</section>

@include('static-pages.newBase.components.section-plans')

<section class="c-way">
	<div class="container">
		<h2 class="sec-ttl">The Peaks Curative Way</h2>
		<div class="row">
			<div class="col-lg-8 offset-lg-2">
		      <div class="list-items">
		      	<img src="{{asset('assets/tick-wh.png')}}" alt="Tick Icon">
		      	<div>
		      		<h3>No more pills!</h3>
		      		<p>Peaks Curative gummies provide a tasty, easy, safe & enjoyable form of treatment for erectile dysfunction & more.</p>
		      	</div>
		      </div>
		      <div class="list-items">
		      	<img src="{{asset('assets/tick-wh.png')}}" alt="Tick Icon">
		      	<div>
		      		<h3>100% online health solution</h3>
		      		<p>Peaks Curative will facilitate online visits between patient and doctor. Once the doctor issues the RX, it will be sent to the pharmacy and our branded medicine will be sent to the patient on a monthly basis or on an as-needed basis.</p>
		      	</div>
		      </div>
		      <div class="list-items">
		      	<img src="{{asset('assets/tick-wh.png')}}" alt="Tick Icon">
		      	<div>
		      		<h3>US Brand</h3>
		      		<p>We are a US licensed, born and raised brand. Our products are manufactured in US soil, meeting the highest industry standards.</p>
		      	</div>
		      </div>
		    </div>
		</div>


		<h2 id="have-any-ques" class="sec-ttl scd-ttl">Have Any Questions?</h2>
		<div class="row">
			<div class="col-lg-8 offset-lg-2">
				<div class="accordion accordion-flush" id="accordion-peaks">
				  <div class="accordion-item">
				    <h2 class="accordion-header" id="flush-headingOne">
				      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
				         What is Peaks Curative?
				      </button>
				    </h2>
				    <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordion-peaks">
				      <div class="accordion-body">Peaks Curative helps connect patients with doctors to provide access to professional advice and prescription treatments for Erectile Dysfunction (ED) in the form of gummies and capsules.</div>
				    </div>
				  </div>
				  <div class="accordion-item">
				    <h2 class="accordion-header" id="flush-headingTwo">
				      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
				         How does it work?
				      </button>
				    </h2>
				    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordion-peaks">
				      <div class="accordion-body">Peaks Curative will facilitate asynchronous visits between patient and doctor. Once the doctor issues the RX, it will be sent to the pharmacy and our branded medicine will be sent to the patient on a monthly basis or on a as needed basis.</div>
				    </div>
				  </div>
				  <div class="accordion-item">
				    <h2 class="accordion-header" id="flush-headingThree">
				      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
				        What is Tadalafil?
				      </button>
				    </h2>
				    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordion-peaks">
				      <div class="accordion-body">Tadalafil is used to treat male sexual function problems (impotence or erectile dysfunction-ED). In combination with sexual stimulation, tadalafil works by increasing blood flow to the penis to help a man get and keep an erection.</div>
				    </div>
				  </div>
				  <div class="accordion-item">
				    <h2 class="accordion-header" id="flush-headingFour">
				      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
				        What is Erectile Dysfunction (ED)?
				      </button>
				    </h2>
				    <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordion-peaks">
				      <div class="accordion-body">Erectile dysfunction, commonly known as impotence or E.D., is the inability to get and keep an erection firm enough for sex. Having erection trouble from time to time isn't necessarily a cause for concern. However, if erectile dysfunction is an ongoing issue, it can cause stress, affect your self-confidence and contribute to relationship problems. Problems like getting or keeping an erection can also be an underlying health condition that needs treatment and a risk factor for heart disease.</div>
				    </div>
				  </div>
				</div>

		    </div>
		    <a href="{{ route('faq') }}" class="dss-btn all-qsn-btn">See All Questions</a>
		</div>
	</div>
</section>

{{--    @include('static-pages.modals.home-modal')--}}
    @include('static-pages.modals.subscription-modal')

@endsection
@section('js')
{!!  GoogleReCaptchaV3::init() !!}

<script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
<script src="{{ asset('mask/src/jquery.mask.js') }}"></script>

<script>
    var input = document.querySelector("#phone");
        errorMsg = document.querySelector("#error-msg"),
        validMsg = document.querySelector("#valid-msg");
    var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
    var iti = window.intlTelInput(input, {
        // allowDropdown: false,
        // autoHideDialCode: false,
        // autoPlaceholder: "off",
        // dropdownContainer: document.body,
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        // geoIpLookup: function(callback) {
        //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
        //     var countryCode = (resp && resp.country) ? resp.country : "";
        //     callback(countryCode);
        //   });
        // },
         hiddenInput: "full_number",
        // initialCountry: "auto",
        // localizedCountries: { 'de': 'Deutschland' },
        nationalMode: true,
        onlyCountries: ['us'],
        // placeholderNumberType: "MOBILE",
        // preferredCountries: ['cn', 'jp'],
         separateDialCode: true,
        utilsScript: "{{asset('intl-tel-input/build/js/utils.js')}}",
    });


</script>
<script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script>
<script>
    $(document).ready(function () {

        $('#home_subscription_form').validate({ // initialize the plugin
            rules: {
                email: {
                    required: true,
                    email: true
                },
                agreement: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Email is required."
                },
                agreement: {
                    required: "You must agree with our terms and conditions to continue."
                }
            },
            submitHandler: function(form) {
            $('.loaderElement').show();
            var email = $("#input_email").val();
            const input_checked = document.getElementById('agreement');
            if (email === '' || !input_checked.checked) {
                alert('{{__('Please fill the required fields.')}}');
                return false;
            }
            var form = $('#home_subscription_form');
            let url = form.attr('action');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                type: "POST",
                url,
                data: form.serialize(),
                success: (response) => {
                    $("#errors").html('');
                    location.reload();
                },
                error: (response) => {
                    $('.loaderElement').hide();
                    $("#errors").html('');
                    if(response.responseJSON.errors.email){
                        showToast('error', 'This email is already subscribed with us.')
                    }else{
                        $.each(response.responseJSON.errors, function (index, value) {
                            showToast('error', value)
                            // $("#errors").append('<p class="alert alert-danger">' + value + '</p>');
                        });
                    }

                }
            }); //End Ajax

                },
				errorPlacement: function ( error, element ) {
					// Add the `invalid-feedback` class to the error element
					error.addClass( "invalid-feedback" );
					if ( element.attr("name") == "agreement" ) {
						error.insertAfter($('.agreementLabel') );
					} else {
						error.insertAfter( element );
					}
				},
        });
    });
</script>
    <script>
        $('#home_subscription_form').on('submit', function (e) {
            e.preventDefault();

        });

        $(".no_thanks_btn").click(function () {
            $.get('{{ route('save.popup-cookie') }}', function (data) {
                // cookie saved for 20 years
            });
        });

        $(document).ready(function () {
            @if(session()->has('success'))
                showToast('success', '{{ session()->get("success") }}');
            @endif
            @if(session()->has('warning'))
                showToast('warning', '{{ session()->get("warning") }}');
            @endif
        });

        window.onload = function () {
            @php
                $cookie_exists = \Cookie::get('home-popup');
                $exists = '';
                if(Auth::user()){
                $exists = \App\Models\Subscription::where('user_id',Auth::user()->id)->first();
                }
            @endphp

            const POPUP_DELAY = 8000;

            @if(Auth::user())
            @if($exists == '' && $cookie_exists != 'done')
            setTimeout(function () {
                $('#modalSubscription').modal('show');
            }, POPUP_DELAY);
            @endif
            @elseif(!Auth::user() && $cookie_exists != 'done')
            setTimeout(function () {
                $('#modalSubscription').modal('show');
            }, POPUP_DELAY);
            @endif
        }

    </script>
@endsection
