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
                    url: "/admin/subscriptions/cancelled",
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
                    {data: 'patient_email'},
                    {data: 'status'},
                    {data: 'created_at'},
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
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
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
var targetOrderView = document.querySelector("#kt_body");
var blockUIOrderView = new KTBlockUI(targetOrderView, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});
$(document).on("click", ".view_subscriber", function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var order_no = $(this).attr('data-id');
    blockUIOrderView.block();
    $.ajax({
        type: "POST",
        url: "/admin/view-cancellled-subscription-details",
        data:{id:order_no},
        dataType: "json",
        success: function (response) {
            if(response.status){
                $("#order_no").html(response.info.order_no);
                $("#patient_name").text(response.info.patient_name);
                $("#email").text(response.info.patient_email);
                $("#date").text(response.info.date);
                $("#reason").text(response.info.cancel_reason);
                $("#view-subscription-details").modal('show');
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
});

$(document).on("click", "#kt_ecommerce_sales_search_clear", function() {
    $(".search-textbox").val('');
    $("#kt_datatable_example_1").DataTable().ajax.reload();
});