$(document).ready(function () {
    // datatable js start
    new DataTable('#services-table', {
        layout: {},
        "ordering": false,
        oLanguage: {
            sLengthMenu: "_MENU_",
        }
    });
    // datatable js end

    // modal delete operation start
    $('#deleteModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget); // Button that triggered the modal
        let Id = button.data('id'); // Extract info from data-* attributes
        let Name = button.data('name');
        let form = $('#deleteForm');

        // Update form action URL
        form.attr('action', APP_URL + '/' + Name + '/' + Id);
    });
    // modal delete operation end

    // active and in active status changes start (service)
    $(document).on('change', '.change_service_status', function () {
        let dataId = $(this).data('id');
        let isChecked = $(this).is(':checked');
        let status = isChecked ? 'Active' : 'Inactive';
        $.ajax({
            url: APP_URL + '/change_service_status',
            type: 'GET',
            data: { id: dataId, status: status },
            success: function (response) {
                if (response.success) {
                    let message = response.status === 'Active' ? '<span class="text-success">Status changed</span>' : '<span class="text-success">Status changed</span>';
                    $('#status_msg_' + dataId).html(message).fadeIn().delay(1000).fadeOut();
                } else {
                    alert('Error updating status.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });
    // active and in active status changes end (testimonial)
});

