@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Order View')
@section('css')
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <style>
        .degu-semibold{
            text-align: justify !important;
            padding-right: 30px !important;
        }
        .mr-line {
            width: 96%;
        }
    </style>
    <!--end::Page Vendor Stylesheets-->
@endsection
@section('navbar')
    @include('admin.includes.sidebar')
@endsection

@section('header')
    @include('admin.includes.header')
@endsection
@if (URL::previous() == route('admin.login'))
<script>window.location = "{{route('admin.home')}}";</script>
@endif
@section('content')
@php
$currentPlace = explode('/',URL::previous());


if (isset($currentPlace[5]) && $currentPlace[5] == 'customers-view') {
    $currentPlace[4] = 'customers-list';
}
if (isset($currentPlace[4])) {
    $urlName = explode('?',$currentPlace[4]);
echo '<style>
    .aside-menu .menu .menu-item .menu-link.'.$urlName[0].' {
        transition: color 0.2s ease, background-color 0.2s ease;
        background-color: #3699FF;
        color: #ffffff;
    }
    .'.$currentPlace[4].' > .menu-title{
        color: #ffffff !important;
    }
    .breadcrumb-line .breadcrumb-item:after {
       content: ">" !important;
    }
</style>
';
} else {
    $currentPlace[4] = 'order';
}


$currentPlace = str_replace('-',' ',explode('/',URL::previous()));
if (isset($currentPlace[5]) && $currentPlace[5] == 'customers view') {
    $currentPlace[4] = 'customers view';
}
if (isset($currentPlace[4])) {
    $urlName = explode('?',$currentPlace[4]);
} else {
    $urlName[0]='order';
}

if (is_numeric($urlName[0])) {
    $urlName[0] = 'order';
}
@endphp

<!--begin::Order details page-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <h2>{{$title}}</h2>
        </div>

        <div class="row mx-5">
                <div class="col-6 mb-8">
                <!--begin::Card-->
                    <!--begin::Overlay-->
                    <a class="d-block overlay overlayBtn" hidden data-fslightbox="lightbox-basic"
                        href="{{ getImage($order->user->getDocumnetByType('govt_id')->document_path) }}">


                    </a>
                    <!--end::Overlay-->
                    <div class="card overlay overflow-hidden">
                        <div class="card-body p-0">
                            <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded "
                                style="min-height:138px; background-image:url('{{ getImage($order->user->getDocumnetByType('govt_id')->document_path) }}')">
                            </div>
                            <div class="overlay-layer bg-dark bg-opacity-25" style="opacity: 1;">
                                <div class="row text-center">
                                    <div class="col-12">
                                        <p class="fw-bolder text-hover-primary">
                                            <span class="badge badge-light-primary">
                                                GOVERNMENT ISSUED PHOTO ID
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <a href="{{ getImage($order->user->getDocumnetByType('govt_id')->document_path) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-8">
                <!--begin::Card-->
                    <!--begin::Overlay-->
                    <a class="d-block overlay overlayBtn" hidden data-fslightbox="lightbox-basic"
                        href="{{ getImage($order->user->getDocumnetByType('selfie')->document_path) }}">


                    </a>
                    <!--end::Overlay-->
                    <div class="card overlay overflow-hidden">
                        <div class="card-body p-0">
                            <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded "
                                style="min-height:138px; background-image:url('{{ getImage($order->user->getDocumnetByType('selfie')->document_path) }}')">
                            </div>
                            <div class="overlay-layer bg-dark bg-opacity-25" style="opacity: 1;">
                                <div class="row text-center">
                                    <div class="col-12">
                                        <p class="fw-bolder text-hover-primary">
                                            <span class="badge badge-light-primary">
                                                SELFIE
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <a href="{{ getImage($order->user->getDocumnetByType('selfie')->document_path) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="col-md-12 mb-4 mx-6">
            <div class="row px-3">
                @php
                $s_no = 1;

                $questions = [];
                $answers = [];
                foreach ($quesAns as $key => $value) {
                    if (preg_match("/^que.*$/", $key)) {
                        $questions[substr($key, 4)] = $value;
                    }
                    if (preg_match("/^ans.*$/", $key)) {
                        $answers[substr($key, 4)] = $value;
                    }
                }
            @endphp

            @foreach($questions as $keyQ => $valueQ)
                @if(str_contains($keyQ, '__1'))
                    @foreach($answers as $keyA => $valueA)
                        @if($keyQ == $keyA)
                            @if(!empty($valueA))
                                        <strong class="degu-semibold mt-2">Q. {{ $s_no++ }}: {{ $valueQ }}</strong> 
                                        <p>
                                            <strong>Response: </strong>
                                            @foreach((array)$valueA as $ans)
                                                <span>{{ $ans }}</span> <br/>
                                            @endforeach
                                        </p>
                                        @foreach($questions as $keyQ2 => $valueQ2)
                                            @if(substr($keyQ2, 0, -1) == substr($keyQ, 0, -1) && $keyQ2 != $keyQ)
                                                @foreach($answers as $keyA => $valueA)
                                                    @if($keyQ2 == $keyA)
                                                        @if(!empty($valueA))
                                                            <h5 class="degu-semibold">
                                                                {{ $valueQ2 }}
                                                            </h5>
                                                            <p>
                                                                <strong>Response: </strong>
                                                                @foreach((array)$valueA as $ans)
                                                                    {{ $ans }} <br/>
                                                                @endforeach
                                                            </p>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <div class="separator my-2 mr-line"></div>
                            @endif
                        @endif
                    @endforeach
                @elseif(!str_contains($keyQ, '__'))
                    @foreach($answers as $keyA => $valueA)
                        @if($keyQ == $keyA)
                            @if(!empty($valueA))
                                <strong class="degu-semibold mt-2">Q. {{ $s_no++ }}: {{ $valueQ }}</strong>
                                <p>
                                    <strong>Response: </strong>
                                    @foreach((array)$valueA as $ans)
                                        {{ $ans }} <br/>
                                    @endforeach
                                </p>
                                <div class="separator my-2 mr-line"></div>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach
            </div>
        </div>
        <!--end::Card body-->
    </div>
<!--end::Order details page-->
@endsection
@section('js')
    <script>
        @php
        $currentPlace = explode('/',URL::previous());
        if (isset($currentPlace[4])) {
            $urlName = explode('?',$currentPlace[4]);
        } else {
            $urlName[0]='order';
        }

        @endphp
        $('.{{$urlName[0]}}').parent().parent().parent().addClass("show here")
    </script>
@endsection
@section('footer')
    @include('admin.includes.footer')
@endsection