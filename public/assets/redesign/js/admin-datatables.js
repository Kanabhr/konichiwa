(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.DataTable === 'undefined') {
            return;
        }

        document.querySelectorAll('table[data-rd-datatable]').forEach(function (table) {
            // The empty-state row uses colspan, which DataTables cannot parse. Leave those tables alone.
            if (table.querySelector('tbody td[colspan]')) {
                return;
            }

            var nosort = String(table.getAttribute('data-rd-nosort') || '')
                .split(',')
                .map(function (n) { return parseInt(n, 10); })
                .filter(function (n) { return !isNaN(n); });

            var orderCol = parseInt(table.getAttribute('data-rd-order-col'), 10);
            var orderDir = table.getAttribute('data-rd-order-dir') === 'asc' ? 'asc' : 'desc';

            new window.DataTable(table, {
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [10, 25, 50],
                order: isNaN(orderCol) ? [] : [[orderCol, orderDir]],
                columnDefs: [{ orderable: false, targets: nosort }],
                language: {
                    search: '',
                    searchPlaceholder: 'Search...',
                    lengthMenu: 'Show _MENU_',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    emptyTable: 'Nothing to show yet.',
                    zeroRecords: 'No matching records found.'
                }
            });
        });
    });
}());