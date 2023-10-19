@extends('static-pages.base.main')

@section('title') Plans @endsection

@section('meta_description')
    Starting as low as $32/month. Choose a Plan and take control of your sexual health today. Get your taladafil gummies with a monthly suscription and more
@endsection

@section('css')
    <link href="{{ asset('/css/peaks/home.css') }}" rel="stylesheet"/>
@endsection

@section('content')

    <div class="">

        @include('static-pages.includes.section-plans')
        @include('static-pages.includes.section-get-started')

    </div>

@endsection
