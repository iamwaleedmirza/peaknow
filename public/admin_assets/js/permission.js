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
            ajax: {
                url: '/admin/permission',
                         "data": function ( d ) {
                 return $.extend( {}, d, {
                       "search[value]": $("#search").val().toLowerCase(),
                     } );
                   }
            },
            columns: [
                {data: 'DT_RowIndex',name: 'DT_RowIndex',orderable: false,searchable: false},
                {data: 'title'},
                {data: 'name'},
                {data: 'module_name'},
                {data: 'permission_for'},
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

$('#permission').click(function () {
    $("#modal_loader").hide();
    $('#id').val('0');
    $('#exampleModalLongTitle').text('Add permission');
    $('#add-edit-permission-modal').modal('show');
    $('#name').removeAttr('readonly');
    $('#Modal-btn').html('Add <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span>');
    var $alertas = $('#data_form');
    $alertas.trigger('reset');
    $alertas.find('label').remove();
    $alertas.find('.error').removeClass('error');
    $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
});

$("#data_form").validate({
    rules: {
        name: {
            required: true
        },
        permission_for:{
            required:true,
        },
        module_name:{
            required:true,
        },
        title: {
            required: true
        },
    },
    messages: {
        name: {
            required: "Permission (Route Name) is required"
        },
        permission_for:{
            required:"Permission for is required",
        },
        module_name:{
            required:"Module name is required",
        },
        title: {
            required: "Title is required"
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
            url: "permission-store",
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
                    $('#add-edit-permission-modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    });
                    $('#id').val(0);
                    $("#data_form")[0].reset();
                } else {
                    toastr.error(response.message);
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

$(document.body).on('click', '.edit', function () {
    $("#modal_loader").show();
    $(".modal_body").hide();
    $("#add-edit-permission-modal").modal('show');
    $('#name').attr('readonly','true');
    $('input').removeClass('error');
    $('#exampleModalLongTitle').text('Edit permission');
    $('#Modal-btn').html('Update <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span>');

    var $alertas = $('#data_form');
    $alertas.trigger('reset');
    $alertas.find('label').remove();
    $alertas.find('.error').removeClass('error');
    $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
    let id = $(this).attr('data-id');
    $('#id').val(id);
    $.ajax({
        type: "POST",
        url: "show-permission-detail",
        data:{id:id},
        dataType: "json",
        success: function (response) {
            if(response.status){
                $('#id').val(response.data.id);
                $('#name').val(response.data.name);
                $('#permission_for').val(response.data.permission_for);
                $('#module_name').val(response.data.module_name);
                $('#title').val(response.data.title);
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
    title: "Are you sure you want to delete this permission",
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
                url: "remove-permission",
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