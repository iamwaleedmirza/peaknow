<section id="plans" class="section section-light">
    <div class="row justify-content-center pt-5 mx-0">
        <div class="col-12 col-xl-10">
            <div class="">
                <h2 class="h-color-dark section-title">Choose a plan</h2>
                <p class="t-small text-center t-color-dark">
                    Let us know what plan "peaks" your interest and register to earn a PROMO code active upon
                    launch (Q1, 2022)
                </p>
            </div>
            @if($message = Session::get('warning'))
                <div class="alert alert-danger mt-3">
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">×</button>
                    <span>{{ $message }}</span>
                </div>
            @endif
            <div class="row justify-content-center align-items-center px-0 mx-0">

                @foreach($plans as $plan)
                    <div class="plan @if($plan->is_popular) plan-top @endif col-12 col-md-6 col-lg-3">
                        <div class="plan-card @if($plan->is_popular) plan-top-choice @endif">
                            <div class="plan-circle @if($plan->is_popular) plan-top-circle @endif">
                                <p class="plan-name text-uppercase t-semi mb-0">{{ $plan->title }}</p>
                            </div>
                            <div class="plan-card-body">
                                <div class="empty-div"></div>
                                <p class="mem-top-choice text-center mt-4">{{ $plan->sub_title }}</p>
                                <ul class="ul-list">
                                    @if ($plan->feature_1)
                                        <li>{{ $plan->feature_1 }}</li>
                                    @endif
                                    @if ($plan->feature_2)
                                        <li>{{ $plan->feature_2 }}</li>
                                    @endif
                                    @if ($plan->feature_3)
                                        <li>{{ $plan->feature_3 }}</li>
                                    @endif
                                    @if ($plan->feature_4)
                                        <li>{{ $plan->feature_4 }}</li>
                                    @endif
                                </ul>
                                <p class="plan-price" @if(str_contains($plan->sub_title, 'CAPSULES')) style="margin-left: -10px" @endif>
                                    @if(str_contains($plan->sub_title, 'CAPSULES')) <span class="h6">Starting at</span> @endif ${{ $plan->price }}
                                </p>
                            </div>
                            <div class="plan-card-footer">
                                <a href="javascript:void(0)">
                                    <button class="btn btn-peaks-secondary">SIGN UP NOW</button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
