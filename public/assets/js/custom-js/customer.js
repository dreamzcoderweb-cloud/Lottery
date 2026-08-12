$(document).ready(function () {
    if ($.fn.dataTable) {
        $.fn.dataTable.ext.errMode = 'none';
    }
        if ($('#customers-table').length) {
            new DataTable('#customers-table', {
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search'
                },
                ordering: false,
                paging: true,
                info: true,
                oLanguage: {
                    sLengthMenu: "_MENU_"
                }
            });
        }
    if ($.fn.DataTable.isDataTable('#customer-ticket-winner')) {
        $('#customer-ticket-winner').DataTable().destroy();
    }

    if ($('#customer-ticket-winner').length) {
        new DataTable('#customer-ticket-winner', {
            ordering: false,
            layout: {},
            oLanguage: {
                sLengthMenu: "_MENU_",
                sEmptyTable: "No tickets found."
            }
        });
    }

    // datatable js start
    new DataTable('#walletRechargeTable', {
        layout: {},
        "ordering": false,
        oLanguage: {
            sLengthMenu: "_MENU_",
        }
    });
    // datatable js end

    // datatable js start
    new DataTable('#winnersTable', {
        layout: {},
        "ordering": false,
        oLanguage: {
            sLengthMenu: "_MENU_",
        }
    });
    // datatable js end

    // datatable js start
    new DataTable('#walletTransactionsTable', {
        layout: {},
        "ordering": false,
        oLanguage: {
            sLengthMenu: "_MENU_",
        }
    });
    // datatable js end

    // datatable js start
    new DataTable('#winnings-slots-table', {
        layout: {
            topStart: [
                'pageLength',
                {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            ],
            topEnd: 'search'
        },
        ordering: false,
        oLanguage: {
            sLengthMenu: "_MENU_"
        },
        initComplete: function () {
            this.api().rows().every(function () {
                const summaryTemplate = $(this.node()).find('.booking-summary-template');

                if (summaryTemplate.length) {
                    this.child(summaryTemplate.html(), 'booking-summary-child').show();
                }
            });
        }
    });
    // datatable js end

    // datatable for winning-tickets-table lose-tickets-table
    if ($('#tickets-table').length) {
        new DataTable('#tickets-table', {
            layout: {
                topStart: [
                    'pageLength',
                    {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                ],
                topEnd: 'search'
            },
            ordering: false,
            paging: true,
            info: true,
            oLanguage: {
                sLengthMenu: "_MENU_"
            }
        });
    }
});
