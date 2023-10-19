<script src="{{ asset('/js/core.js') }}"></script>
<script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
<script src="{{ asset('mask/src/jquery.mask.js') }}"></script>

<script>
    var input = document.querySelector("#new-phone");
    errorMsg = document.querySelector("#error-msg"),
        validMsg = document.querySelector("#valid-msg");
    var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
    var iti = window.intlTelInput(input, {

        hiddenInput: "full_number",

        onlyCountries: ['us'],

        separateDialCode: true,
        utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
    });
</script>
<script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', "#verifyPhoneBtn", function () {
        var form = $('#phonePincodeForm');
        form.parsley().validate()

        if (form.parsley().isValid()) {
            var uri = form.attr('action');
            var post_data = form.serialize();
            ajaxPostData(uri, post_data, 'POST', '', 'otp')

        } else {
            $('#verifyPhoneBtn').attr('disabled', true);
            showToast('warning', 'Please enter valid verification code.')
        }


    });
    $(document).on('click', ".ajxbtn", function () {
        var uri = $(this).attr('data-uri');
        var token = $(this).attr('data-token');
        ajaxPostData(uri, {'_token': token}, 'POST', '', 'resend')
    });
    $(document).on('click', ".changePhoneBtn", function () {


        let form = $('#form-change-ph-number');
        let url = form.attr('action');

        $.ajax({
            type: "POST",
            url,
            data: form.serialize(),
            success: (response) => {
                $("#errors").html('');
                $('#NumberChangeModal').modal('hide');
                showToast('success', 'OTP sent to new phone number.')
            },
            error: (response) => {
                $('#ul-error').html('');
                $.each(response.responseJSON.errors, function (key, value) {
                    $('#ul-error').append('<li class="text-danger">' + value + '</li>');
                });
            }
        });
    });
    $("input[name^=phoneVerifyPin]").attr("autocomplete", "off");
    var keyCode;
    $('input[name^=phoneVerifyPin]').bind('keyup', function (event) {

        var text = $(this).val(),
            key = event.which || event.keyCode || event.charCode;
        $('#verifyPhoneBtn').removeAttr('disabled');
        if (key == 8) {
            keyCode = key;
            $(this).prev('input[name^=phoneVerifyPin]').focus();

        }

    });

    $(document).on("input", "input[name^=phoneVerifyPin]", function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        var text = $(this).val();
        if (text.length == 6) {
            for (i = 1; i <= text.length; i++) {
                $("input[name^=phoneVerifyPin]").eq(i - 1).val(text[i - 1]);
            }
        } else if (text.length > 1) {

            $(this).val(text[0]);

        }
        if (keyCode == 8) {
            keyCode = null;
        } else {
            $(this).next('input[name^=phoneVerifyPin]').focus();
        }


    });
    $("input[name^=emailVerifyPin]").attr("autocomplete", "off");
    var keyCode;
    $('input[name^=emailVerifyPin]').bind('keyup', function (event) {

        var text = $(this).val(),
            key = event.which || event.keyCode || event.charCode;
        $('#verifyEmailBtn').removeAttr('disabled');
        if (key == 8) {
            keyCode = key;
            $(this).prev('input[name^=emailVerifyPin]').focus();
            return false;
        }

    });

    $(document).on("input", "input[name^=emailVerifyPin]", function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        var text = $(this).val();
        if (text.length == 6) {
            for (i = 1; i <= text.length; i++) {
                $("input[name^=emailVerifyPin]").eq(i - 1).val(text[i - 1]);
            }
        } else if (text.length > 1) {
            $(this).val(text[0]);

        }
        if (keyCode == 8) {
            keyCode = null;
        } else {
            $(this).next('input[name^=emailVerifyPin]').focus();
        }
    });

    $(document).on('click', "#verifyEmailBtn", function () {
        var form = $('#emailPincodeForm');
        form.parsley().validate()
        if (form.parsley().isValid()) {
            var uri = form.attr('action');
            var post_data = form.serialize();
            ajaxPostData(uri, post_data, 'POST', '', 'otp')

        } else {
            $('#verifyEmailBtn').attr('disabled', true);
            showToast('warning', 'Please enter valid verification code.')
        }

    });
</script>
