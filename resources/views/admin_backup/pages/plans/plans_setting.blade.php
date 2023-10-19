@extends('admin.master', ['type' => 'admin'])
@section('title','Plans Setting')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Plans Setting</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Site Settings</a>
                </li>
                <li class="active">
                    Plans Setting
                </li>
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
                            <div class="row">
                                @foreach($plans as $plan)
                                    <div class="col-lg-4">
                                        <div class="card-box" style="border-color: #bababa">
                                            <div>
                                                <h3>{{ $plan->title }}</h3>
                                                <h4>{{ $plan->sub_title }}</h4>
                                                <h4>
                                                    ${{ $plan->price }}
                                                    @if($plan->is_subscription_based)<span>{{ '(Subscription based)' }}</span>@endif
                                                </h4>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; align-items: center">
                                                <div>
                                                    @if($plan->is_popular)
                                                        <p style="margin-bottom: 0;"><strong>Most Popular</strong></p>
                                                    @endif
                                                </div>
                                                <a href="{{ route('admin.edit.plan', $plan->id) }}">
                                                    <button class="btn btn-primary">Edit Plan</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
