$('input').attr('autocomplete','off');

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;

    // Private functions
    var initDatatable = function () {
        dt = $("#kt_datatable_example_1").DataTable({
            searchDelay: 500,
            processing: true,
            response:true,
            destroy:true,
            serverSide: true,
            destroy:true,
            order : [],
            ajax: {
                 url: '/admin/admin-user-list',
                 "data": function ( d ) {
                 return $.extend( {}, d, {
                       "search[value]": $("#search").val().toLowerCase(),
                     } );
                   }
            },
            columns: [
                {data: 'DT_RowIndex',name: 'DT_RowIndex',orderable: false,searchable: false},
                {data: 'first_name'},
                {data: 'last_name'},
                {data: 'email'},
                {data: 'u_type'},
                {data: 'action',orderable: false,searchable: false},
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
            }
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            initToggleToolbar();
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = function () {
        const container = document.querySelector('#kt_datatable_example_1');
    }

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            initToggleToolbar();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});

$("#kt_datatable_example_1").DataTable().on('page.dt', function() {
  $('html, body').animate({
    scrollTop: $("#kt_body").offset().top
   }, 'slow');
});

$(document).ready(function(){
    $("#kt_datatable_example_1").DataTable().draw();
    // Redraw the table based on the custom input
    $('#search').bind("keyup change", function(){
        $("#kt_datatable_example_1").DataTable().draw();
    });
});

$('#user-model').click(function () {
    $("#modal_loader").hide();
    $('#id').val('0');
    $('#exampleModalLongTitle').text('Add New User');
    $('#add-edit-user-modal').modal('show');
    $(".bg-active-success").removeClass('active');
    $('#permission').val('').trigger('change')
    $(".passwordbox").removeClass('password-hidden');
    $('#Modal-btn').html('Add <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span>');
    var $alertas = $('#data_form');
    $alertas.trigger('reset');
    $alertas.find('label').remove();
    $alertas.find('.error').removeClass('error');
    $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
});

$(document.body).on('click', '.change_password', function () {
    let id = $(this).attr('data-id');
    $('#user_id').val(id);
    $('#customer_reset_modal').modal('show');
    var $alertas = $('#change_password');
    $alertas.trigger('reset');
    $alertas.find('label').remove();
    $alertas.find('.error').removeClass('error');
    $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
});

$("#data_form").validate({
    rules: {
        first_name: {
            required: true
        },
        last_name: {
            required: true
        },
        email: {
            required: true,
            email:true
        },
        role_id: {
            required: true
        },
        password: {
            required: function (value,element) {
                if($("#id").val()<=0){
                    return true;
                } else {
                    return false;
                }
            },
            strong_password: function (value,element) {
                if($("#id").val()<=0 || $("#password").val()!=''){
                    return true;
                } else {
                    return false;
                }
            }
        },
        confirm_password: {
            required: function (value,element) {
                if($("#id").val()<=0 || $("#password").val()!=''){
                    return true;
                } else {
                    return false;
                }
            },
            equalTo: "#password",
        },
    },
    messages: {
        first_name: {
            required: 'First name is required',
        },
        last_name: {
            required: 'Last name is required',
        },
        email: {
            required: 'Email address is required'
        },
        role_id: {
            required: 'Select user role',
        },
        password: {
            required: 'Password is  required'
        },
        confirm_password: {
            required: 'Confirm password is required'
        },
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);         
    },
    submitHandler: function (form, event) { // for demo
        event.preventDefault();
        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            url: "add-user-store",
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
                    $("#kt_datatable_example_1").DataTable().ajax.reload();
                    $('#add-edit-user-modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    });
                    $('#id').val(0);
                    $("#data_form")[0].reset();
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

$("#customer_reset_modal_form").validate({
    rules: {
        password: {
            required: true,
            strong_password:true,
        },
        password_confirmation: {
            required: true,
            equalTo: "#change-pass",
        },
    },
    messages: {
        password: {
            required: 'Password required'
        },
        password_confirmation: {
            required: 'Confirm password required'
        },
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);         
    },
    submitHandler: function (form, event) { // for demo
        event.preventDefault();
        var formData = new FormData(form);
        $.ajax({
            type: "POST",
            url: "admin-user-change-password",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loader1').show();
                $('#customer_reset_modal_formbtn').prop('disabled', true);
            },
            success: function (response) {
                if (response.status) {
                    $('#customer_reset_modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    });
                    $('#user_id').val(0);
                    $("#customer_reset_modal_form")[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
                $('#loader1').hide();
                $('#customer_reset_modal_formbtn').prop('disabled', false);
            },
            error: function () 
            {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong',
                    showConfirmButton: true,
                });
                $('#loader1').hide();
                $('#customer_reset_modal_formbtn').prop('disabled', false);
            }
        });

    }
});

$(document).ready(function() {
    $.validator.addMethod("strong_password", function(value, element) {

        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[~!@#$%^&*+=?_-])(.{8,20}$)/.test(value);
    }, function(value, element) {
        let password = $(element).val();
        if (!(/^(.{8,20}$)/.test(password))) {
            return "Password must be between 8 characters long.";
        } else if (!(/^(?=.*[A-Z])/.test(password))) {
            return "Password must contain at least one uppercase.";
        } else if (!(/^(?=.*[a-z])/.test(password))) {
            return 'Password must contain at least one lowercase.';
        } else if (!(/^(?=.*[0-9])/.test(password))) {
            return "Password must contain at least one digit.";
        } else if (!(/^(?=.*[~!@#$%^&*+=?_-])/.test(password))) {
            return "Password must contain one special character.";
        }
        return false;
    });
});

$(document.body).on('click', '.edit', function () {
    $(".passwordbox").addClass('password-hidden');
    $("#modal_loader").show();
    $(".modal_body").hide();
    $("#add-edit-user-modal").modal('show');
    $('input').removeClass('error');
    $('#exampleModalLongTitle').text('Edit User Role');
    $('#Modal-btn').html('Update <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span>');

    var $alertas = $('#data_form');
    $alertas.trigger('reset');
    // $alertas.find('label').remove();
    $alertas.find('.error').removeClass('error');
    $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
    let id = $(this).attr('data-id');
    $('#id').val(id);
    $.ajax({
        type: "POST",
        url: "show-user-detail",
        data:{id:id},
        dataType: "json",
        success: function (response) {
            if(response.status){
                $('#id').val(response.data.id);
                $('#first_name').val(response.data.first_name);
                $('#last_name').val(response.data.last_name);
                $('#email').val(response.data.email);
                $('#role_id').val(response.data.role_id);
                $("#modal_loader").hide();
                $(".modal_body").show();
            } else {
                Swal.fire({
                    icon: 'error',
                    text: response.message,
                    showConfirmButton: true,
                });
            }
        },
        error: function () 
        {
          Swal.fire({
            icon: 'error',
            text: 'Something went wrong',
            showConfirmButton: true,
        });
        }
    });
});

$(document.body).on('click', '.delete', function () {
    let id = $(this).attr('data-id');
    Swal.fire({
    showLoaderOnConfirm: true,
    title: "Are you sure you want to delete this user",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: "Yes",
    closeOnConfirm: false,
    closeOnClickOutside: false,
    allowEscapeKey: false,
    allowOutsideClick: false,
  }).then((result) => {
    if (result.value==true) {
        Swal.fire({didOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
              const content = Swal.getContent()
              if (content) {
                const b = content.querySelector('b')
                if (b) {
                  b.textContent = Swal.getTimerLeft()
                }
              }
            }, 100)
          },
          willClose: () => {
            clearInterval(timerInterval)
          }});
            $.ajax({
                type: "post",
                url: "remove-user",
                data:{id:id},
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            text: response.message,
                            showConfirmButton: true,
                        });
                        $("#kt_datatable_example_1").DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: response.message,
                            showConfirmButton: true,
                        });
                    }
                },
                error: function () 
                {
                    Swal.fire({
                        icon: 'error',
                        text: 'Something went wrong',
                        showConfirmButton: true,
                    });
                }
            });
        }
  })
});  