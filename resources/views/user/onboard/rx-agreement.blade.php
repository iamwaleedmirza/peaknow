@extends('user.base.main')

@section('title') RX Agreement @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="section-login text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 col-lg-5">
                <button id="btnRXModal" class="d-none" type="button" data-bs-toggle="modal" data-bs-target="#rxAgreementModal"></button>
            </div>
        </div>
    </section>

    <div class="modal fade" id="rxAgreementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="modalRXAgreement" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form id="form-govt-id" action="{{ route('submit-rx-user-agreement') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 t-small text-center">
                        <img class="mb-5" width="100" height="100" src="{{ asset('images/png/agreement.png') }}" alt=""/>
                        <p class="text-start">
                            Peaks Curative will facilitate your doctor consultation and home delivery of our ED (Erectile Dysfunction) gummies,
                            which are exclusively produced at RxCompoundStore.com. Your completed medical questionnaire will
                            be forwarded to <a href="https://smartdoctors.us" class="t-link" target="_blank">SmartDoctors.us</a>
                            for review and, if qualified for treatment, your prescription will be sent to
                            <a href="https://rxcompoundstore.com" class="t-link" target="_blank">RxCompoundStore.com</a>.
                            By clicking "I agree" you acknowledge and approve the process described herein.
                        </p>
                        <p class="text-start">
                            Additionally by clicking "I Agree" you acknowledge awareness of the possibility to consult your
                            primary doctor about treatment for ED and you may obtain your ED medications, if prescribed,
                            from any licensed pharmacy however likely not to be found in a gummy formula.
                        </p>
                        <div class="my-4">
                            <button type="submit" class="btn btn-peaks">I AGREE</button>
                        </div>
                        <p class="text-start t-x-small">
                            Note: If you have a prescription for ED medication and would like it transferred to
                            <a href="https://rxcompoundstore.com" class="t-link" target="_blank">RxCompoundStore.com</a>
                            to be filled in gummy form, you may contact RxCompoundStore.com directly.
                        </p>

{{--                        <h6>We detected that you are from {{ $ip_location }}.</h6>--}}
{{--                        <p class="mb-0">Do you agree to send the prescription to Rx Compoundstore?</p>--}}
{{--                        <p class="t-color-red">(Required to proceed further)</p>--}}
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $('#btnRXModal').click();
    </script>
@endsection
