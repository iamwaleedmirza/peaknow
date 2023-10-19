@extends('admin.master', ['type' => 'admin'])
@section('title','Admin | Plans Setting')
@section('navbar')
@include('admin.includes.sidebar')
@endsection

@section('header')
@include('admin.includes.header')
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
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
                                    <div class="col-lg-4 mt-5">
                                        <div class="card shadow-sm ">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ $plan->title }}</h3>
                                              
                                            </div>
                                            <div class="card-body">
                                                <h4>{{ $plan->sub_title }}</h4>
                                                <h4>
                                                    ${{ $plan->price }}
                                                    @if($plan->is_subscription_based)<span>{{ '(Subscription based)' }}</span>@endif
                                                </h4>
                                               
                                            </div>
                                            <div class="card-footer">
                                                <div style="display: flex; justify-content: space-between; align-items: center">
                                                    <div>
                                                        @if($plan->is_popular)
                                                            <p style="margin-bottom: 0;"><strong>Most Popular</strong></p>
                                                        @endif
                                                    </div>
                                                    @if(in_array('admin.update.plan',$permissions) || Auth::user()->u_type=='superadmin') 
                                                    <a href="{{ route('admin.edit.plan', $plan->id) }}">
                                                        <button class="btn btn-primary">Edit Plan</button>
                                                    </a>
                                                    @endif
                                                </div>
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
@section('footer')
@include('admin.includes.footer')
@endsection
@section('js')
@if(session()->has('success'))
<script>
    Swal.fire('{{ session()->get('success') }}', '', 'success')
</script>
@endif
@if(session()->has('warning'))
<script>
    Swal.fire('{{ session()->get('warning') }}', '', 'warning')
</script>
@endif
@endsection