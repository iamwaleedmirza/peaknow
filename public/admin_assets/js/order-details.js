$(document).on("click", ".update-script", function() {
    $("#liberty_script_number_form")[0].reset();
    var $alertas = $('#liberty_script_number_form');
    $alertas.trigger('reset');
    $alertas.find('label').remove();
    $alertas.find('.error').removeClass('error');
    $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
    $("#liberty_script_number_modal").modal({backdrop: "static"});
    $("#liberty_script_number_modal").modal('show');
});

function IsNumeric(e) {
    var key = e.keyCode;
    var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9.]/);
            if (verified) {e.preventDefault();}
}

$("#liberty_script_number_form").validate({
    rules: {
        liberty_script_number: {
            required: true
        },
    },
    messages: {
        liberty_script_number: {
            required: "Liberty script number is required"
        },
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);        
    },
    submitHandler: function (form, event) { // for demo
        event.preventDefault();
        var formData = new FormData(form);
        blockUIOrderView.block();
        $.ajax({
            type: "POST",
            url: "/admin/update-liberty-script-number",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    $("#liberty_script_number_modal").modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    }).then(function() {
                      location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
                blockUIOrderView.release();
            },
            error: function () 
            {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong',
                    showConfirmButton: true,
                });
                blockUIOrderView.release();
            }
        });

    }
});

$(document).on("click", ".mark_as_shipped_modal", function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).attr("data-id");
    
    const date = new Date();

    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();
    $('#ship_date').val(month+'/'+day+'/'+year);
    $("#order_detail_id").val(id);
    $("#ship-with-tracking-no").prop('checked',true);
    $("#tracking_no").val('');
    $("#mark_track_number_div").show();
    $("#unship-modal").modal('show');

});

$('.unship-type').change(function() {
    console.log(this.value);
    if(this.value==1){
        $("#tracking_no").val('');
        $("#mark_track_number_div").show();
    } else {
        $("#tracking_no").val('');
        $("#mark_track_number_div").hide();
    }
});

$("#ship_date").flatpickr({
    dateFormat: "m/d/Y",
    maxDate: new Date(),
    defaultDate: [new Date()]
});


$("#data_form").validate({
    rules: {
        tracking_no: {
            required: true,
            maxlength:15
        },
        ship_date:{
            required:true,
        },
    },
    messages: {
        tracking_no: {
            required: "Tracking number is required"
        },
        ship_date:{
            required:"Shipment Date is required",
        }
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    submitHandler: function (form, event) { // for demo
        event.preventDefault();
        var formData = new FormData(form);
        blockUIOrderView.block();
        $.ajax({
            type: "POST",
            url: "/admin/update-refill-ship-details",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    $("#unship-modal").modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    }).then(function() {
                          location.reload();
                    });
                    $("#data_form")[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
                blockUIOrderView.release();
            },
            error: function () 
            {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong',
                    showConfirmButton: true,
                });
                blockUIOrderView.release();
            }
        });

    }
});