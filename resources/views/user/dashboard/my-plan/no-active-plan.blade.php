@extends('user.dashboard.dashboard')

@section('title')
    My Plan
@endsection
@section('css')
    <style>
        .btn-small {
            height: auto;
        }
    </style>
@endsection
@section('content')

    <div class="d-flex flex-column justify-content-center align-items-center mt-0 mt-md-5">
        <div class="card d-flex flex-column justify-content-center align-items-center p-4 mb-4">
            <img src="{{ asset('images/svg/payments.svg') }}" class="img-empty-data mb-4" alt=""/>
            <h6>You have no active plans</h6>
        </div>
        <a href="{{ env('WP_URL') }}" class="w-fit-content">
            <button class="btn btn-peaks-hollow btn-small">Subscribe Now</button>
        </a>
    </div>

@endsection
@section('js')
    <script>
        $(document).ready(function () {
            @if (session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: '<span style="font-size: 22px;">Your order has been cancelled.<span>',
                text: '{{ session()->get('success') }}',
                animation: true,
                showConfirmButton: true,
            }).then((response) => {
                if (response.isConfirmed) {
                    window.location.replace('{{ route('account-home') }}');
                }
            });
            @endif

            @if (session()->has('error'))
            showToast('error', '{{ session()->get('error') }}');
            @endif
        });
    </script>
@endsection
