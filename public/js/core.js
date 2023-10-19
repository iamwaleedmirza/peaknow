function ajaxPostData(uri, post_data, postType, element, type) {
    $.ajax({
        type: postType,
        url: uri,
        data: post_data,
        cache: false,
        beforeSend: function(jqXHR, options) {
            // setting a timeout
            $('.loaderElement').show();
            var loaderbtn = $('.loaderBtn');
            loaderbtn.attr('disabled', true);
            // loaderbtn.html(loaderEl);
        },
        success: function(data) {

            if (type == 'verifyEmail') {
                Swal.fire({
                    text: data.message,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Next'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace(data.uri);
                    }
                });


            }
            if (type == 'verifyPhone') {
                Swal.fire({
                    text: data.message,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Next'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace(data.uri);
                    }
                });

                // setTimeout(function() {
                //     window.location.replace(data.uri);
                // }, 500);

            }
            if (type == 'register') {

                showToast('success', data.message);

                setTimeout(function() {
                    window.location.replace(data.uri);
                }, 500);
            }
            if (type == 'otp') {
                showToast('success', data.message);
                setTimeout(function() {
                    window.location.reload();
                }, 3500);

            }
            if (type == 'otpAjax') {
                showToast('success', data.message);
            }

            if (type == 'resend') {
                showToast('success', data.message);
            }
            if (type == 'pauseSub') {
                showToast('success', data.message);
                $('.pause-btn').hide();
                $('.resume-btn').show();
                $('.changeStatus').removeClass('status__success');
                $('.changeStatus').addClass('status__warning');
                $('.changeStatus').html('Paused');
                $('#confirmation_modal').modal('hide');
            }
            if (type == 'resumeSub') {
                showToast('success', data.message);
                $('.resume-btn').hide();
                if (data.hide_pause_btn == true) {
                    $('.pause-btn').remove();
                } else {
                    $('.pause-btn').show();
                }
                // $('.pause-btn').attr('disabled', true);
                // $('.cancel-btn').attr('disabled', true);
                $('.changeStatus').removeClass('status__warning');
                $('.changeStatus').addClass('status__success');
                $('.changeStatus').html('Active');
                $('#confirmation_modal').modal('hide');
                setTimeout(function() {
                    window.location.reload();
                }, 5000);
            }
            if (type == 'refillSub') {
                showToast('success', 'Your refill request for subscription has been successfully processed');
                $('.resume-btn').hide();
                $('.pause-btn').show();
                $('#confirmation_modal').modal('hide');
                setTimeout(function() {
                    window.location.reload();
                }, 5000);
            }
            if (type === 'checkVerification') {
                window.location.replace(element);
            }
        },
        error: function(jqXHR) { // if error occured
            var loaderEl = $('.loaderElement').html();
            var loaderbtn = $('.loaderBtn');
            loaderbtn.attr('disabled', false);
            // loaderbtn.html('Next');
            $.each(jqXHR.responseJSON.errors, function(key, value) {
                if (type == 'resumeSub') {
                    setTimeout(function() {
                        window.location.reload();
                    }, 3500);
                }
                if (type == 'checkVerification') {
                    Swal.fire({
                        text: value,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                } else {
                    showToast('error', value);
                }

            });
            if (jqXHR.responseJSON.uri !== null && jqXHR.responseJSON.uri !== undefined) {
                setTimeout(function() {
                    window.location.replace(jqXHR.responseJSON.uri);
                }, 500);
            }
            if (jqXHR.responseJSON.error_title !== null && jqXHR.responseJSON.error_title !== undefined && jqXHR.responseJSON.error_description !== null && jqXHR.responseJSON.error_description !== undefined) {
                Swal.fire({
                    title: jqXHR.responseJSON.error_title,
                    html: jqXHR.responseJSON.error_description,
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok',
                    customClass: {
                        icon: 'danger-error-icon',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // window.location.replace('{{route("account-home")}}');
                    }
                });
            }
            if (jqXHR.responseJSON.message == 'CSRF token mismatch.') {
                var hostname = window.location.origin
                window.location.replace(hostname + '/user/login');
            }
            if (jqXHR.responseJSON.message == 'Unauthenticated.') {
                var hostname = window.location.origin
                window.location.replace(hostname + '/user/login');
            }
        },
        complete: function() {
            var loaderEl = $('.loaderElement').hide();
            var loaderbtn = $('.loaderBtn');
            loaderbtn.attr('disabled', false);
            // loaderbtn.html('Next');
            // $('.ajxbtn').html('Send a new code');
            // if (type == 'otp') {

            //     loaderbtn.html('Submit');


            // } else {

            // }
        }

    });
}

function verifyDate(datevalue) {

    var done = false;

    if (datevalue != null || datevalue != '') {

        //split the date as a tmp var
        var tmp = datevalue.split('/');
        var currentTime = new Date();
        var givenDate = new Date(datevalue);
        var days = 0;
        //get the month and year
        var month = tmp[0];
        var year = tmp[2];
        var date = tmp[1];
        if (month >= 1 && month <= 12) {
            done = true;
            days = daysInMonth(month, year);
        } else {
            done = false;
            console.log('Month is invalid.');
            $('#msg').html('Month is invalid.');
        }
        if (date >= 1 && date <= days) {
            done = done;
        } else {
            done = false;
            console.log('Date is invalid.');
            $('#msg').html('Date is invalid.');
        }

        if (year >= 1900 && year <= currentTime.getFullYear()) {
            done = done;
        } else {
            done = false;
            console.log('Year is invalid.');
            $('#msg').html('Year is invalid.');
        }

        if (currentTime <= givenDate) {
            console.log('Future date is given.');
            done = false;
        } else {
            done = done;
        }
    }
    return done;
}

function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}