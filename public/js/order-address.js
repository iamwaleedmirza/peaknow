// var edit_input = document.querySelector("#edit_phone");
// editerrorMsg = document.querySelector(".edit_error"),
//     validMsg = document.querySelector("#valid-msg");
// var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
// var edit_iti = window.intlTelInput(edit_input, {
//     hiddenInput: "edit_full_number",
//     nationalMode: true,
//     onlyCountries: ['us', 'pr'],
//     separateDialCode: true,
//     utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
// });
$(document).on('click', '.edit-address', function() {
    $("#address_order_id").val($(this).attr("data-id"));
    // $("#edit_name").val($(this).attr("data-name"));
    // $(".edit_phone").val($(this).attr("data-phone").replace('+1', ''));
    $("#edit_address_line").val($(this).attr("data-address"));
    $("#edit_address_line2").val($(this).attr("data-address2"));
    $("#edit_city").val($(this).attr("data-city"));
    $("#edit_zipcode").val($(this).attr("data-zipcode"));
    $("#edit_state").val($(this).attr("data-state"));
    // $('.edit_phone').mask('000 000-0000');

});
$(document).on('click', '.select_address', function() {
    $("#address_order_id2").val($(this).attr("data-id"));

});
var reset = function() {
    edit_input.classList.remove("error");
    editerrorMsg.innerHTML = "";
    editerrorMsg.classList.add("hide");
    // validMsg.classList.add("hide");
};

// on blur: validate
// edit_input.addEventListener('keyup', function() {
//     reset();
//     if (edit_input.value.trim()) {
//         if (edit_iti.isValidNumber()) {
//             $('.submitBTN').attr('type', 'submit')
//             $('.submitBTN').removeAttr('disabled')
//                 // validMsg.classList.remove("hide");
//         } else {
//             $('.submitBTN').attr('type', 'button')
//             $('.submitBTN').attr('disabled', 'true')

//             edit_input.classList.add("error");
//             var errorCode = edit_iti.getValidationError();
//             editerrorMsg.innerHTML = errorMap[errorCode];
//             // if (errorCode == -99) {
//             //     errorMsg.innerHTML = 'Please enter valid mobile number'
//             // }

//             editerrorMsg.classList.remove("hide");
//         }
//     }
// });
$('#form_edit_address').parsley().on('field:validated', function() {
    let ok = $('.parsley-error').length === 0;
}).on('field:submit', function() {
    console.log('submit form');
    return false;
});

$("#form_edit_address").submit(function(e) {
    e.preventDefault();
    const form = $(this);
    const postUrl = form.attr('action');
    return new Promise(((resolve, reject) => {
        $.ajax({
            method: "POST",
            url: postUrl,
            data: form.serialize(),
            beforeSend: function(xhr, options) {
                // setting a timeout
                $('.loaderElement').show();
                // loaderbtn.html(loaderEl);
            },
            success: (data) => {
                showToast('success', data.message);
                $('#editAddressModal').modal('hide');
                $('.orderShippingAddress').html(data.orderShippingAddress);

                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            },
            error: function(xhr) {
                if (xhr.responseJSON.message == 'CSRF token mismatch.') {
                    var hostname = window.location.origin
                    window.location.replace(hostname + '/user/login');
                }
                $('#error-edit-address').html('');
                $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#error-edit-address').append(
                        '<li class="text-danger">' + value + '</li>');
                });
                if (xhr.responseJSON.error_title !== null && xhr.responseJSON.error_title !== undefined && xhr.responseJSON.error_description !== null && xhr.responseJSON.error_description !== undefined) {
                    Swal.fire({
                        title: xhr.responseJSON.error_title,
                        html: xhr.responseJSON.error_description,
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
            },
            complete: function() {
                var loaderEl = $('.loaderElement').hide();
            }
        })
    }));
});
$("#form_select_address").submit(function(e) {
    e.preventDefault();
    const form = $(this);
    const postUrl = form.attr('action');
    return new Promise(((resolve, reject) => {
        $.ajax({
            method: "POST",
            url: postUrl,
            data: form.serialize(),
            beforeSend: function(xhr, options) {
                // setting a timeout
                $('.loaderElement').show();
                // loaderbtn.html(loaderEl);
            },
            success: (data) => {
                showToast('success', data.message);
                $('#selectShippingAddressModal').modal('hide');
                $('.orderShippingAddress').html(data.orderShippingAddress);

                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            },
            error: function(xhr) {
                $('#error-edit-address').html('');
                $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#error-select-address').append(
                        '<li class="text-danger">' + value + '</li>');
                });
                if (xhr.responseJSON.error_title !== null && xhr.responseJSON.error_title !== undefined && xhr.responseJSON.error_description !== null && xhr.responseJSON.error_description !== undefined) {
                    Swal.fire({
                        title: xhr.responseJSON.error_title,
                        html: xhr.responseJSON.error_description,
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
            },
            complete: function() {
                var loaderEl = $('.loaderElement').hide();
            }
        })
    }));
});