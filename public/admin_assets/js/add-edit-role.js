$(function(){
    var all_checkbox = $('input[type="checkbox"][name="permission[]"]:checked').length;
    var total_check = $('input[type="checkbox"][name="permission[]"]').length;
    if(all_checkbox==total_check){
        $("#checkall").prop("checked", true);
    }


   $('.checkall').click(function(){
        var id = $(this).attr('checkbox-id');
        if (this.checked) {
            $(".checkboxes"+id).prop("checked", true);
        } else {
            $(".checkboxes"+id).prop("checked", false);
        } 
   });

    $('.checkboxes').click(function() {
        var id = $(this).attr('checkbox-input-id');
        if (!$(this).is(':checked')) {
          $("#checkall").prop("checked", false);
        }

        var all_checkbox = $('.checkboxes'+id+':checked').length;
        var total_check = $('.checkboxes'+id).length;
        if(all_checkbox==total_check){
            $("#checkall-"+id).prop("checked", true);
        } else {
            $("#checkall-"+id).prop("checked", false);
        }
    });
});


$("#data_form").validate({
    rules: {
        name: {
            required: true
        },
        "permission[]": {
            required: true
        }
    },
    messages: {
        name: {
            required: "Role name is required"
        },
        "permission[]": {
            required: "Select at least any one permission"
        },
    },
    errorPlacement: function (error, element) {
        if (element.attr("name") == "permission[]") {
            error.insertAfter($("#checkbox-error"));

        } else {
            error.insertAfter(element);    
        }
        
    },
    submitHandler: function (form, event) { // for demo
        event.preventDefault();
        var formData = new FormData(form);
        var base_url = $("#base_url").val();
        $.ajax({
            type: "POST",
            url: base_url+"/admin/role-store",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loader').show();
                $('#Modal-btn').prop('disabled', true);
            },
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    }).then(function() {
                      window.location.href= base_url+"/admin/role";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
                $('#loader').hide();
                $('#Modal-btn').prop('disabled', false);
            },
            error: function () 
            {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong',
                    showConfirmButton: true,
                });
                $('#loader').hide();
                $('#Modal-btn').prop('disabled', false);
            }
        });

    }
});