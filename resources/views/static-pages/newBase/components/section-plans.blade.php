<style>
    .popover{
        max-width: 300px; /* Max Width of the popover (depending on the container!) */
    }

    @media screen and (min-width: 992px) {
        .popover{
            max-width: 500px;
        }
    }
</style>

<section id="plans" class="plans">
	<div class="container">
		<h2 class="sec-ttl mb-4">Choose a Plan</h2>
{{--		<h3 class="bottom-line">Let us know what plan "peaks" your interest & register to earn a PROMO code active upon launch (Q1, 2022)</h3>--}}
		<div class="row">
            @foreach ($plans as $plan)
                <div class="col-lg-6 plan-cards">
                    <div class="pksp">
                        <img src="{{ $plan->plan_image?getImage($plan->plan_image):asset('assets/pks-performer.jpg') }}" alt="Peaks Curative Gummies Products">
                        <a class="dss-btn gummy-btn">{{ $plan->sub_title }}</a>
                        <h2 class="pl-name">{{ $plan->title }}</h2>
                        <ul>
                            @if ($plan->feature_1)
                                <li>&bull; {{ $plan->feature_1 }}</li>
                            @endif
                            @if ($plan->feature_2)
                                <li>&bull; {{ $plan->feature_2 }}</li>
                            @endif
                            @if ($plan->feature_3)
                                <li data-bs-toggle="popover" data-bs-trigger="hover"
                                    data-bs-placement="top" data-bs-html="true"
                                    title="Peaks Loyalty Program Member"
                                    data-bs-content="<ul><li>New Peaks Curative Members will be automatically enrolled into our Peaks Loyalty Program.</li> <li>As a member of our loyalty program you will earn a credit of $30 upon joining.</li> <li>Your membership enrollment will occur automatically once becoming a member of Peaks Curative.</li> <li>All prescription fills (new fills or refills) ordered will earn a $5 credit applicable to your order at the time your next SmartDoctors.us powered consultation is needed.</li> <li>At the time of your prescription renewal order, the discount will be applied. Please note the discount is only applicable on prescription renewal order.</li></ul>">
                                    &bull; {{ $plan->feature_3 }}
                                </li>
                            @endif
                            @if ($plan->feature_4)
                                <li>&bull; {{ $plan->feature_4 }}</li>
                            @endif

                        </ul>
                        <div class="price">${{ $plan->price }}</div>
                        <a href="javascript:void(0)" class="dss-btn dark-btn">Choose plan</a>
                    </div>
                </div>
            @endforeach
		</div>
	</div>
</section>
