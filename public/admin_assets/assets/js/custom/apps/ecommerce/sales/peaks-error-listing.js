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
                order:[],
                destroy:true,
                ajax: {
                    url: "/admin/peaks-error-codes",
                    "data": function ( d ) {
                     return $.extend( {}, d, {
                           "search[value]": $(".search-textbox").val(),
                           "kt_ecommerce_sales_flatpickr": $("#kt_ecommerce_sales_flatpickr").val(),
                         } );
                       }
                },
                columns: [
                    {data:'DT_RowIndex',name:'DT_RowIndex',orderable: false,searchable: false},
                    {data: 'error_type'},
                    {data: 'error_code',width:"10%"},
                    {data: 'error_description'},
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
           
        }

        // Handle flatpickr --- more info: https://flatpickr.js.org/events/
        var handleFlatpickr = (selectedDates, dateStr, instance) => {
         
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
                                columns: [0, 1, 2,3]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2,3]
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2,3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: documentTitle,
                            exportOptions: {
                                columns: [0, 1, 2,3]
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
//# sourceMappingURL=listing.js.map

$(document).on("click", "#kt_ecommerce_sales_search_clear", function() {
    $(".search-textbox").val('');
    $("#kt_datatable_example_1").DataTable().ajax.reload();
});