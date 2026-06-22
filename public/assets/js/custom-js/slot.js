$(document).ready(function () {
    const tableSelector = '#slots-table';
    if (!$(tableSelector).length || typeof DataTable === 'undefined') return;

    new DataTable(tableSelector, {
        layout: {},
        ordering: false,
        oLanguage: {
            sLengthMenu: '_MENU_',
        },
    });
});

