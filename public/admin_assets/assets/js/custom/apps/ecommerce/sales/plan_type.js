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
                    url: "/admin/plan-types",
                    "data": function ( d ) {
                     return $.extend( {}, d, {
                           "search[value]": $(".search-textbox").val(),
                           "kt_ecommerce_sales_flatpickr": $("#kt_ecommerce_sales_flatpickr").val(),
                         } );
                       }
                },
                columns: [
                    {data:'DT_RowIndex',name:'DT_RowIndex',orderable: false,searchable: false},
                    {data: 'name'},
                    {data: 'subscription_type'},
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
                                columns: [0, 1, 2, 3,4]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3,4]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3,4]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3,4]
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


var targetOrderView = document.querySelector("#kt_body");
var blockUIOrderView = new KTBlockUI(targetOrderView, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});

$(document).on("click", ".edit-modal", function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var order_no = $(this).attr('data-id');
    $("#modal_loader").show();
    $("#modal-body").hide();
    $("#add-edit-modal").modal('show');
    $.ajax({
        type: "POST",
        url: "/admin/edit-plan-type",
        data:{id:order_no},
        dataType: "json",
        success: function (response) {
            if(response.status){
                $("#name").val(response.info.name);
                $("input[name=subscription_type][value="+response.info.subscription_type+"]").prop('checked', true);
                $("#id").val(response.info.id);
            } else {
                Swal.fire({
                    icon: 'error',
                    text: response.message,
                    showConfirmButton: true,
                });
            }
            $("#modal_loader").hide();
            $("#modal-body").show();
            // blockUIOrderView.release();
        },
        error: function ()  {
            Swal.fire({
                icon: 'error',
                text: 'Something went wrong',
                showConfirmButton: true,
            });
            $("#modal_loader").hide();
            $("#modal-body").show();
        }
    });
});

$("#data_form").validate({
    rules: {
        name: {
            required: true
        },
        subscription_type:{
            required: true  
        }
    },
    messages: {
        name: {
            required: "Plan name is required"
        },
        subscription_type: {
            required: "Select a subscription type"
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
            url: "store-plan-type",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status){
                    $("#kt_datatable_example_1").DataTable().ajax.reload();
                    $("#add-edit-modal").modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
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


$(document).on("click", "#kt_ecommerce_sales_search_clear", function() {
    $(".search-textbox").val('');
    $("#kt_datatable_example_1").DataTable().ajax.reload();
});