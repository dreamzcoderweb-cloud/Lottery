$(document).ready(function () {
    // datatable js start
    new DataTable('#customers-table', {
        layout: {},
        "ordering": false,
        oLanguage: {
            sLengthMenu: "_MENU_",
        }
    });
    // datatable js end

    // datatable js start
    new DataTable('#customer-ticket-winner', {
        layout: {},
        "ordering": false,
        oLanguage: {
            sLengthMenu: "_MENU_",
        }
    });
    // datatable js end

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
    if ($('#winning-tickets-table').length) {
        new DataTable('#winning-tickets-table', {
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

    if ($('#lose-tickets-table').length) {
        new DataTable('#lose-tickets-table', {
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
