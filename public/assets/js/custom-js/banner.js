$(document).ready(function () {
    // datatable js start
    new DataTable('#banners-table', {
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

    // active and in active status changes start (banner)
    $(document).on('change', '.change_banner_status', function () {
        let dataId = $(this).data('id');
        let isChecked = $(this).is(':checked');
        let status = isChecked ? 'Active' : 'Inactive';
        $.ajax({
            url: APP_URL + '/admin/change_banner_status',
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

    // active and in active status changes end (banner)

    // banner status filer start
    $("#status_filter_button").on("click", function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let banner_status = $("#status_filter").val();
        if (banner_status) {
            $.ajax({
                url: APP_URL + '/admin/banner_status',
                type: 'POST',
                data: {
                    "banner_status": banner_status,
                },
                success: function (response) {
                    if (response.success) {
                        let banners = response.banners;
                        let table = $('#banners-table').DataTable(); // Access the DataTable instance

                        // Destroy the existing DataTable
                        table.destroy();

                        // Clear the table body
                        let tableBody = $("#banners-table tbody");
                        tableBody.empty();

                        banners.forEach(function (banner) {
                            let row = `
                            <tr>
                                <td><img src="${APP_URL}/assets/img/banner/${banner.image}" alt="banner image" class="rounded" width="50" height="50"></td>
                                <td>${banner.short_title}</td>
                                <td>${banner.title}</td>
                                <td>${banner.sequence}</td>
                                <td>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input change_banner_status my-element" type="checkbox"
                                            id="flexSwitchCheckChecked" data-id="${banner.id}"
                                            ${banner.status == 'Active' ? 'checked' : ''}>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="edit_banner/${banner.id}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="${banner.id}" data-name="delete_banner">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            `;

                            tableBody.append(row);
                        });

                        // Reinitialize the DataTable
                        new DataTable('#banners-table', {
                            layout: {}
                        });

                        $("#status_filter").val('');
                        $('#basicModal').modal('toggle');

                    } else {
                        console.error("Error: Unable to fetch banners");
                    }
                },

                error: function (xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
    });
    // banner status filer end
});

