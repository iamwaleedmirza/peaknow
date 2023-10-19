/******/
(() => { // webpackBootstrap
    /******/
    "use strict";
    var __webpack_exports__ = {};
    /*!**************************************************************!*\
      !*** ../demo8/src/js/custom/apps/ecommerce/sales/listing.js ***!
      \**************************************************************/


    // Class definition
    var KTAppEcommerceSalesListing = function() {
        // Shared variables
        var table;
        var datatable;
        var flatpickr;
        var minDate, maxDate;

        // Private functions
        var initDatatable = function() {
            // Init datatable --- more info on datatables: https://datatables.net/manual/
            datatable = $(table).DataTable({
                // responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                destroy:true,
                order:[],
                ajax: {
                    url: "/admin/promo-code",
                    "data": function ( d ) {
                     return $.extend( {}, d, {
                           "search[value]": $(".search-textbox").val(),
                           "kt_ecommerce_sales_flatpickr": $("#kt_ecommerce_sales_flatpickr").val(),
                         } );
                       }
                },
                columns: [
                    {data:'DT_RowIndex',name:'DT_RowIndex',orderable: false,searchable: false},
                    {data: 'promocode'},
                    {data: 'promo_type'},
                    {data: 'plan_name'},
                    {data: 'discount'},
                    {data: 'status'},
                    {data: 'created_at'},
                    {data: 'updated_at'},
                    {data: 'action',orderable: false,searchable: false},
                ],
            });

            // Re-init functions on datatable re-draws
            datatable.on('draw', function() {
                initToggleToolbar();
                KTMenu.createInstances();
                // handleDeleteRows();
            });
        }

        // Init flatpickr --- more info :https://flatpickr.js.org/getting-started/
        var initFlatpickr = () => {
            const element = document.querySelector('#kt_ecommerce_sales_flatpickr');
            flatpickr = $(element).flatpickr({
                altInput: true,
                altFormat: "m/d/Y",
                dateFormat: "Y-m-d",
                mode: "range",
                onChange: function(selectedDates, dateStr, instance) {

                    handleFlatpickr(selectedDates, dateStr, instance);
                },
            });
        }

        // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
        var handleSearchDatatable = () => {
            const filterSearch = document.querySelector('[data-kt-ecommerce-order-filter="search"]');
            filterSearch.addEventListener('keyup', function(e) {
                datatable.search(e.target.value).draw();
            });
        }

        // Handle status filter dropdown
        var handleStatusFilter = () => {
            const filterStatus = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
            $(filterStatus).on('change', e => {
                let value = e.target.value;
                if (value === 'all') {
                    value = '';
                }
                datatable.column(3).search(value).draw();
            });
        }

        // Handle flatpickr --- more info: https://flatpickr.js.org/events/
        var handleFlatpickr = (selectedDates, dateStr, instance) => {
            minDate = selectedDates[0] ? new Date(selectedDates[0]) : null;
            maxDate = selectedDates[1] ? new Date(selectedDates[1]) : null;

            // Datatable date filter --- more info: https://datatables.net/extensions/datetime/examples/integration/datatables.html
            // Custom filtering function which will search data in column four between two values
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = minDate;
                    var max = maxDate;
                    var dateAdded = new Date(moment($(data[6]).text(), 'MM/DD/YYYY'));
                    var dateModified = new Date(moment($(data[6]).text(), 'MM/DD/YYYY'));

                    if (
                        (min === null && max === null) ||
                        (min === null && max >= dateModified) ||
                        (min <= dateAdded && max === null) ||
                        (min <= dateAdded && max >= dateModified)
                    ) {
                        return true;
                    }
                    return false;
                }
            );
            datatable.draw();
        }

        // Handle clear flatpickr
        var handleClearFlatpickr = () => {
            const clearButton = document.querySelector('#kt_ecommerce_sales_flatpickr_clear');
            clearButton.addEventListener('click', e => {
                flatpickr.clear();
            });
        }

        // Hook export buttons
        var exportButtons = (docType) => {
                const documentTitle = docType + " Report";
                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [{
                            extend: 'copyHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6,7]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6,7]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6,7]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6,7]
                            }
                        }
                    ]
                }).container().appendTo($('#kt_datatable_example_1_export'));

                // Hook dropdown menu click event to datatable export buttons
                const exportButtons = document.querySelectorAll('#kt_datatable_example_1_export_menu [data-kt-export]');
                exportButtons.forEach(exportButton => {
                    exportButton.addEventListener('click', e => {
                        e.preventDefault();

                        // Get clicked export value
                        const exportValue = e.target.getAttribute('data-kt-export');
                        const target = document.querySelector('.dt-buttons .buttons-' + exportValue);

                        // Trigger click event on hidden datatable export buttons
                        target.click();
                    });
                });
            }


        var initToggleToolbar = function () {
            const container = document.querySelector('#kt_datatable_example_1');
        }

        // Public methods
        return {
            init: function(docType) {
                table = document.querySelector('#kt_datatable_example_1');

                // if (!table) {
                //     return;
                // }

                initToggleToolbar();
                initDatatable();
                initFlatpickr();
                exportButtons(docType);
                handleSearchDatatable();
                handleClearFlatpickr();
            }
        };
    }();

    // On document ready
    KTUtil.onDOMContentLoaded(function() {
        KTAppEcommerceSalesListing.init(docType);
    });

    $("#kt_datatable_example_1").DataTable().on('page.dt', function() {
      $('html, body').animate({
        scrollTop: $("#kt_body").offset().top
       }, 'slow');
    });

    /******/
})();

$(document).on("click", "#kt_ecommerce_sales_search_clear", function() {
    $(".search-textbox").val('');
    $("#kt_datatable_example_1").DataTable().ajax.reload();
});

$("#promocode_form").validate({
    rules: {
        promo_name: {
            required: true
        },
        promo_type: {
            required: true
        },
        product_id: {
            required: true
        },
        plan_id: {
            required: true
        },
        medicine_variant_id: {
            required: true
        },
        select_product_id: {
            required: true
        },
        select_plan_id: {
            required: true
        },
        select_medicine_variant_id: {
            required: true
        },
        promo_value: {
            required: true,
            min:1,
            max:99,
        },
    },
    messages: {
        promo_name: {
            required: "Promo code is required"
        },
        promo_type: {
            required: "Promo type is required"
        },
        product_id: {
            required: "Product name is required"
        },
        plan_id: {
            required: "Plan type is required"
        },
        medicine_variant_id: {
            required: "Medicine variant name is required"
        },
        select_product_id: {
            required: "Product name is required"
        },
        select_plan_id: {
            required: "Plan type is required"
        },
        select_medicine_variant_id: {
            required: "Medicine variant name is required"
        },
        promo_value: {
            required: "Promo value is required"
        },
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    submitHandler: function (form, event) { // for demo
        event.preventDefault();
        var formData = new FormData(form);
        blockUIOrderView.block();
        if($("#id").val()>0){
            var url = 'update-promo-code';
        } else {
            var url = 'add-promo-code';
        }
        $.ajax({
            type: "POST",
            url: "/admin/"+url,
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status){
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    });
                    $("#kt_datatable_example_1").DataTable().ajax.reload();
                    $("#kt_modal_add_promocode").modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
                blockUIOrderView.release();
            },
            error: function ()  {
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

$(document).on("click", ".add-promo", function() {
    $("#promocode_form")[0].reset();
    var $alertas = $('#promocode_form');
    $alertas.trigger('reset');
    // $alertas.find('label').remove();
    $alertas.find('.error').removeClass('error');
    $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
    $("#modal-title").text('Add Promo Code');
    $("#id").val('');
    $("input[name=promo_type][value=0]").prop('checked', true);
    $("#kt_modal_add_promocode").modal('show');
    $(".select-plan-data,.promo_status").hide();
});

$(document).on("click", ".close-modal", function() {
    $("#kt_modal_add_promocode").modal('hide');
});

$(document).on("click", ".promo-text", function() {
    var type = $(this).attr('for');
    $("input[name=promo_type][value=" + type + "]").prop('checked', true);
    $(".select-plan-data,.select-product-data,.select-plans-data,.select-medicine-data").hide();
    if (type == 1) {
        $(".select-plan-data").show();
    } else if (type == 2) {
        $(".select-product-data").show();
    } else if (type == 3) {
        $(".select-plans-data").show();
    } else if (type == 4) {
        $(".select-medicine-data").show();
    }
});

$(document).on("change", "#product_id", function() {
    getmedicine();
});

$(document).on("change", "#plan_id", function() {
    getmedicine();
});

function getmedicine(){
    var product_id = $("#product_id").val();
    var plan_id = $("#plan_id").val();
    if(product_id!='' && plan_id!=''){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        blockUIOrderView.block();
        $.ajax({
            type: "POST",
            url: "/admin/get-medicine",
            data:{product_id:product_id,plan_id:plan_id},
            dataType: "json",
            success: function (response) {
                if(response.status){
                    var html = '';
                    html += '<option value="">Select Medicine Variant</option>';
                    $.each(response.info, function(key,val) {
                        console.log(val);
                        html += '<option value="'+val.id+'">'+val.name+'</option>';
                    });
                    $("#medicine_variant_id").html(html);
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
                blockUIOrderView.release();
            },
            error: function ()  {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong',
                    showConfirmButton: true,
                });
                blockUIOrderView.release();
            }
        });
    }
}

$(document).on("click", ".updatePromoCode", function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var id = $(this).attr('data-id');
    $.ajax({
        type: "GET",
        url: "/admin/"+id+"/get-promo-code",
        data:{id:id},
        dataType: "json",
        success: function (response) {
            if(response.status){
                var html = '';
                html += '<option value="">Select Medicine Variant</option>';
                $.each(response.info.data, function(key,val) {
                    html += '<option value="'+val.id+'">'+val.name+'</option>';
                });
                if(response.info.promo_type==1){
                    $("#medicine_variant_id").html(html);    
                } else if(response.info.promo_type==4){
                    $("#select_medicine_variant_id").html(html);    
                }
                
                var $alertas = $('#promocode_form');
                $alertas.trigger('reset');
                $alertas.find('label').remove();
                $alertas.find('.error').removeClass('error');
                $alertas.find('.pac-target-input error').removeClass('pac-target-input error');
                $(".promo_status").show();
                $("#modal-title").text('Edit Promo Code');
                $("#promo_name").val(response.info.promo_name);
                $("#promo_value_by_precent").val(response.info.promo_value);
                
                
                
                $("input[name=promo_type][value=" + response.info.promo_type + "]").prop('checked', true);
                $("#promo_status").val(response.info.promo_status);
                $("#id").val(response.info.id);
                $(".select-plan-data,.select-product-data,.select-plans-data,.select-medicine-data").hide();
                if (response.info.promo_type == 1) {
                    $(".select-plan-data").show();
                } else if (response.info.promo_type == 2) {
                    $(".select-product-data").show();
                } else if (response.info.promo_type == 3) {
                    $(".select-plans-data").show();
                } else if (response.info.promo_type == 4) {
                    $(".select-medicine-data").show();
                }
                if(response.info.promo_type==1){
                    $("#medicine_variant_id").val(response.info.medicine_variant_id);
                } else if(response.info.promo_type==4){
                    $("#select_medicine_variant_id").val(response.info.medicine_variant_id);
                }
                if(response.info.promo_type==1){
                    $("#plan_id").val(response.info.plan_type_id);
                } else if(response.info.promo_type==3){
                    $("#select_plan_id").val(response.info.plan_type_id);
                }
                if(response.info.promo_type==1){
                    $("#product_id").val(response.info.product_id);
                } else if(response.info.promo_type==2){
                    $("#select_product_id").val(response.info.product_id);
                }
                $("#kt_modal_add_promocode").modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    text: response.message,
                    showConfirmButton: true,
                });
            }
            $("#modal_loader").hide();
            $("#refund_modal_body").show();
            // blockUIOrderView.release();
        },
        error: function ()  {
            Swal.fire({
                icon: 'error',
                text: 'Something went wrong',
                showConfirmButton: true,
            });
            $("#modal_loader").hide();
            $("#refund_modal_body").show();
        }
    });
});

$(document.body).on('click', '.delete', function () {
    let id = $(this).attr('data-id');
    Swal.fire({
    showLoaderOnConfirm: true,
    title: "Are you sure you want to delete this promocode",
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
        blockUIOrderView.block();
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                type: "post",
                url: "delete-promo-code",
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
  })
});  