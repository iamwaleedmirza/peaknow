@extends('admin.master', ['type' => 'admin'])
@section('title','General Setting')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">General Setting</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Site Settings</a>
                </li>
                <li class="active">
                    General Setting
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
                            <form action="{{route('general-setting.post')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Site title</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="site_title"
                                               value="{{($setting)?$setting->site_title:''}}"
                                               placeholder="Enter site title" class="form-control">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2"><label class="p-t-10" for="">Site logo (Light)</label></div>
                                    <div class="col-md-4">
                                        <input type="file" name="site_logo_light" class="form-control">
                                        <img
                                            @if($setting && $setting->site_logo_light) src="{{getImage($setting->site_logo_light)}}"
                                            @else src="" @endif alt="logo" height="50">
                                    </div>
                                    <div class="col-md-2"><label class="p-t-10" for="">Site logo (Dark)</label></div>
                                    <div class="col-md-4">
                                        <input type="file" name="site_logo_dark" class="form-control">
                                        <img
                                            @if($setting && $setting->site_logo_dark) src="{{getImage($setting->site_logo_dark)}}"
                                            @else src="" @endif alt="logo" height="50">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">site favicon</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="site_favicon" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <img
                                            @if($setting  && $setting->site_favicon) src="{{getImage($setting->site_favicon)}}"
                                            @else src="" @endif alt="favicon" height="50">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2"><label class="p-t-10" for="">Footer text</label></div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="footer area text"
                                               value="{{($setting)?$setting->footer_text:''}}" class="form-control"
                                               name="footer_text">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2"><label class="p-t-10" for="">Facebook Link</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="facebook_link"
                                               name="facebook_link" value="{{($setting)?$setting->facebook_link:''}}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2"><label class="p-t-10" for="">Twitter Link</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Twitter link"
                                               name="twitter_link" value="{{($setting)?$setting->twitter_link:''}}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Instagram Link</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Instagram link"
                                               name="instagram_link" value="{{($setting)?$setting->instagram_link:''}}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">Support Mail:</label></div>
                                    <div class="col-md-10">
                                        <input type="email" class="form-control" placeholder="Support Mail"
                                               name="support_mail" value="{{($setting)?$setting->support_mail:''}}">
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for="">States where site will be live:</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Add states (separated by comma)"
                                               name="allowed_states" value="{{($setting) ? $setting->allowed_states : ''}}">
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
