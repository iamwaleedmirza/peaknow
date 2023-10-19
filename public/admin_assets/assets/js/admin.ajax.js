var targetCancel = document.querySelector(".refund-cancelled-order-form");
var blockUICancel = new KTBlockUI(targetCancel, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});
var targetOrderView = document.querySelector("#kt_body");
var blockUIOrderView = new KTBlockUI(targetOrderView, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});

var targetcustomerreset = document.querySelector("#customer_reset_modal_form");
var blockUIcustomerreset = new KTBlockUI(targetcustomerreset, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});
$(document).on('click', '.order_refund_modal_btn', function() {

    $('.order_data').html('<input type="text" name="order_id" value="' + $(this).attr('data-order_id') + '" hidden/>');
    $('.order_data').append('<input type="text" name="order_current_amount" value="' + $(this).attr('data-order_amount') + '" hidden/>');
});

$('#order_amount_type').on('change', function() {
    if (this.value == "total_amount") {
        $('.order_amount_data').html('<input type="text" name="order_amount" value="100%" hidden/>');
        var amount = $('input[name=order_current_amount]').val();
        $('.order_data_show').html('<br><p>Total refunded amount : <strong>$' + amount + '</strong> </p>');
    } else if (this.value == "partial_amount") {
        $('.order_amount_data').html('<input type="text" name="order_amount" value="50%" hidden/>');
        var amount = 50 / 100 * $('input[name=order_current_amount]').val();
        $('.order_data_show').html('<br><p>Total refunded amount : <strong>$' + amount + '</strong> </p>');

    } else if (this.value == "custom_amount") {
        $('.order_amount_data').html('<br><label>Enter Custom Amount *</label><input type="number" class="form-control" name="order_amount" value="" />');
        $('.order_data_show').html('')
    } else {
        $('.order_data_show').html('')
        $('.order_amount_data').html('');
    }
});
$(document).on('click', "#refund-cancelled-order-btn", function() {

    const form = $("#refund-cancelled-order-form")
    var post_data = form.serialize();
    var uri = form.attr('action');
    var reloaduri = $('#orderDataURI').val();


    ajaxPostData(uri, post_data, 'POST', '', 'refund', blockUICancel)


});
var targetDeclined = document.querySelector("#refund-declined-order-form");
var blockUIDeclined = new KTBlockUI(targetDeclined, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});
$(document).on('click', "#refund-declined-order-btn", function() {

    var order_amount_type = $('#order_amount_type').val();
    var order_amount = $('input[name=order_amount]').val();

    if (order_amount_type == "") {
        return Swal.fire('Please Select Refund Option', '', 'warning')
    }

    if (order_amount == "") {
        return Swal.fire('Custom amount cannot be empty', '', 'warning')
    }
    const form = $("#refund-declined-order-form")
    var post_data = form.serialize();
    var uri = form.attr('action');
    var reloaduri = $('#orderDataURI').val();

    ajaxPostData(uri, post_data, 'POST', '', 'refund', blockUIDeclined)

});
$(document).on('click', ".customer_reset_modalbtn", function() {

    $('#user_id').val($(this).attr('data-user-id'));
    $('#change-pass').val('');
    $('#change-pass2').val('');
    $('#user_email').val($(this).attr('data-user-email'));

});
$(document).on('click', "#customer_reset_modal_formbtn", function() {

    const form = document.getElementById("customer_reset_modal_form")
    $('.invalid-feedback').remove()
    var validator = FormValidation.formValidation(
        form, {
            fields: {

                'password': {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        callback: {
                            message: 'Please enter valid password',
                            callback: function(input) {
                                if (input.value.length > 0) {
                                    return validatePassword();
                                }
                            }
                        }
                    }
                },
                'password_confirmation': {
                    validators: {
                        notEmpty: {
                            message: 'The password confirmation is required'
                        },
                        identical: {
                            compare: function() {
                                return form.querySelector('[name="password"]').value;
                            },
                            message: 'The password and its confirm are not the same'
                        }
                    }
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                })
            }
        }
    );
    if (validator) {
        validator.validate().then(function(status) {
            console.log('validated!');
            const form = $("#customer_reset_modal_form")
            var post_data = form.serialize();
            var uri = form.attr('action');
            if (status == 'Valid') {
                ajaxPostData(uri, post_data, 'POST', '', 'customerReset', blockUIcustomerreset)
            } else {


            }
        });
    }

});

function ajaxPostData(uri, post_data, postType, element, type, blockUI, is_submitbtn = false, submitbtn_title = null, customLoader = false) {
    console.log(blockUI);
    $.ajax({
        type: postType,
        url: uri,
        data: post_data,
        cache: false,
        beforeSend: function(jqXHR, options) {
            // setting a timeout
            if (is_submitbtn == false) {
                blockUI.block();
            } else {
                $('.loader-btn').html('<span class="spinner-border" style="width: 1.5rem !important;height: 1.5rem !important;vertical-align: -0.3em !important;"></span> Loading...')
                $('.loader-btn').attr('disabled', true)
            }
            if (customLoader == true) {
                $('.loaderElement').show()
            }
            if (type == 'getAfterVal') {

                $('.kt_modal_promocode_submit').attr('disabled', true);
            }
            // setTimeout(function() {
            //     // null beforeSend to prevent recursive ajax call
            //     $.ajax($.extend(options, { beforeSend: $.noop }));
            // }, 2000);
            // return false;
        },
        success: function(data) {

            if (type == 'refund') {
                $('#order_refund_modal').modal('hide');
                $('#declined_order_refund_modal').modal('hide');
                Swal.fire({
                    title: data[1],
                    icon: 'success',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var reloaduri = $('#orderDataURI').val();
                        ajaxPostData(reloaduri, '', 'GET', '#order_details', 'orderView', blockUIOrderView)
                    }
                });
            }

            if (type == 'orderView') {
                $(element).html(data);
                KTMenu.createInstances();
            }
            if (type == 'orderRefillView') {
                $(element).html(data);
                $('#order_refill_modal').modal('show')

            }
            if (type == 'orderRefillTrackingView') {
                $(element).html(data);
                $('#order_refill_tracking_modal').modal('show')

            }
            if (type == 'updateRefillTracking') {
                $('#order_refill_tracking_modal').modal('hide')
                toastr.success(data.message);
            }
            if (type == 'deletePromoCode') {

                toastr.success(data.success);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
            if (type == 'customerView') {
                $(element).html(data);
                KTMenu.createInstances();
            }
            if (type == 'customerReset') {
                toastr.success(data.message);
                $('#customer_reset_modal').modal('hide');
            }
            if (type == 'getAfterVal') {

                $(element).html(data);
                $('.kt_modal_promocode_submit').attr('disabled', false);
            }
            if (type == 'getPromoUpdateModal') {
                $(element).html(data);
                $('#kt_modal_update_promocode').modal('show')
            }
            if (type == 'getPlanPrice') {
                $(element).append(' <input type="number" id="plan_amount" style="display: none;" class="form-control form-control-solid" value="' + data + '" />');
            }
            if (type == 'deleteSub') {
                Swal.fire({
                    text: "Popup subscriber deleted successfully",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

            }
            if (type == 'deleteCon') {
                Swal.fire({
                    text: "ContactUs subscriber deleted successfully",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                $('.modal').modal('hide')
            }
        },
        error: function(xhr) { // if error occured
            if (xhr.responseJSON.message == 'CSRF token mismatch.') {
                var hostname = window.location.origin
                window.location.replace(hostname + '/admin/login');
            }
            if (xhr.responseJSON.message == 'Unauthenticated.') {
                var hostname = window.location.origin
                window.location.replace(hostname + '/admin/login');
            }
            $.each(xhr.responseJSON.errors, function(key, value) {
                toastr.warning(value);
            });
            if (type == 'refund') {
                $('#order_refund_modal').modal('hide');
                $('#declined_order_refund_modal').modal('hide');
                Swal.fire('', xhr.responseJSON[1], xhr.responseJSON[0])
            }
            if (customLoader == true) {
                $('.loaderElement').hide()
            }
            if (is_submitbtn == false) {
                blockUI.release();
            } else {
                $('.loader-btn').html(submitbtn_title)
                $('.loader-btn').attr('disabled', false)
                $('.loader-btn').removeClass('loader-btn')
            }
            if (type == 'getAfterVal') {

                $('.kt_modal_promocode_submit').attr('disabled', false);
            }
            //Swal.fire("Error occured.please try again", '', 'warning')
        },
        complete: function() {
            if (customLoader == true) {
                $('.loaderElement').hide()
            }
            if (is_submitbtn == false) {
                blockUI.release();
            } else {
                $('.loader-btn').html(submitbtn_title)
                $('.loader-btn').attr('disabled', false)
                $('.loader-btn').removeClass('loader-btn')
            }
        }

    });
}