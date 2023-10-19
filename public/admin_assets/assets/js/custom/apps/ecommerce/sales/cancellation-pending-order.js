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
                    url: "/admin/beluga-cancellation-pending-order",
                    "data": function ( d ) {
                     return $.extend( {}, d, {
                           "search[value]": $(".search-textbox").val(),
                           "kt_ecommerce_sales_flatpickr": $("#kt_ecommerce_sales_flatpickr").val(),
                         } );
                       }
                },
                columns: [
                    {data:'DT_RowIndex',name:'DT_RowIndex',orderable: false,searchable: false},
                    {data: 'order_no'},
                    {data: 'patient_name'},
                    {data: 'plan_name'},
                    {data: 'total_price'},
                    {data: 'promocode'},
                    {data: 'order_date'},
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
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
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
            // Delete cateogry
        var handleDeleteRows = () => {
            // Select all delete buttons
            const deleteButtons = table.querySelectorAll('[data-kt-ecommerce-order-filter="delete_row"]');

            deleteButtons.forEach(d => {
                // Delete button on click
                d.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Select parent row
                    const parent = e.target.closest('tr');

                    // Get category name
                    const orderID = parent.querySelector('[data-kt-ecommerce-order-filter="order_id"]').innerText;

                    // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Are you sure you want to delete order: " + orderID + "?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, delete!",
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function(result) {
                        if (result.value) {
                            Swal.fire({
                                text: "You have deleted " + orderID + "!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function() {
                                // Remove current row
                                datatable.row($(parent)).remove().draw();
                            });
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: orderID + " was not deleted.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                    });
                })
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

$(document).on("click", "#kt_ecommerce_sales_search_clear", function() {
    $(".search-textbox").val('');
    $("#kt_datatable_example_1").DataTable().ajax.reload();
});

var targetOrderView = document.querySelector("#kt_body");
var blockUIOrderView = new KTBlockUI(targetOrderView, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Processing...</div>',
});

$(document).on("click", ".mark_as_cancelled", function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    
    var order_no = $(this).attr("data-id");
    Swal.fire({
        title: 'Are you sure you want to mark as cancelled this order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            blockUIOrderView.block();
            $.ajax({
                type: "POST",
                url: "admin-mark-as-cancelled-order",
                data:{id:order_no},
                dataType: "json",
                success: function (response) {
                    if(response.status){
                        $("#kt_datatable_example_1").DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: response.message,
                            showConfirmButton: true,
                        });
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title:response.code,
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
    })
});

function refundOrder(order_id) {
    $("#order_id").val(order_id);
    $("#orderId").html(order_id);
}

$(document).on('click', "#refund-cancelled-order-btn", function() {

    const form = $("#refund-cancelled-order-form")
    var post_data = form.serialize();
    var uri = form.attr('action');
    var reloaduri = $('#orderDataURI').val();


    ajaxPostData(uri, post_data, 'POST', '', 'refund', blockUIOrderView)


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
                    $("#kt_datatable_example_1").DataTable().ajax.reload();
                });
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