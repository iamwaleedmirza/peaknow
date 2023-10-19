@extends('admin.master', ['type' => 'admin'])
@section('title','Edit Plan')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Update Plan</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Site Settings</a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.plans') }}">Plans Setting</a>
                </li>
                <li class="active">Edit Plan</li>
            </ol>
        </div>
    </div>
    @if(session()->has('success'))
        <script>
            Swal.fire('{{ session()->get('success') }}', '', 'success')
        </script>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            @if(count($errors) > 0 )
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{route('admin.update.plan')}}" method="post">
                                @csrf

                                <input type="hidden" name="plan_id" value="{{ $plan->id }}"/>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Plan Title</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="plan_title"
                                               value="{{ !empty($plan) ? $plan->title : '' }}"
                                               placeholder="Enter plan title" class="form-control" maxlength="50">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Plan Subtitle</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="Plan subtitle"
                                               value="{{ !empty($plan) ? $plan->sub_title : '' }}"
                                               class="form-control" maxlength="50"
                                               name="plan_subtitle">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Plan Pricing</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control" placeholder="Plan Price"
                                               name="plan_price" value="{{ !empty($plan) ? $plan->price : '' }}"
                                               maxlength="20" min="0" pattern="[0-9]*">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Plan feature 1</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="feature 1"
                                               name="feature_1" value="{{ !empty($plan) ? $plan->feature_1 : '' }}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Plan feature 2</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="feature 2"
                                               name="feature_2" value="{{ !empty($plan) ? $plan->feature_2 : '' }}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Plan feature 3</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="feature 3"
                                               name="feature_3" value="{{ !empty($plan) ? $plan->feature_3 : '' }}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Plan feature 4</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="feature 4"
                                               name="feature_4" value="{{ !empty($plan) ? $plan->feature_4 : '' }}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for=""></label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_popular" class="form-check-input"
                                                   id="isPopularCheck" @if($plan->is_popular) checked @endif>
                                            <label class="form-check-label" for="isPopularCheck">Is Popular</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-20">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for=""></label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_subscription_based" class="form-check-input"
                                                   id="isSubscriptionBasedCheck" @if($plan->is_subscription_based) checked @endif>
                                            <label class="form-check-label" for="isSubscriptionBasedCheck">Is Subscription based plan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-center m-b-0">
                                    <button class="ladda-button btn btn-primary w-lg m-t-30" data-style="slide-right"
                                            type="submit">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
