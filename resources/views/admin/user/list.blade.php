@extends('admin.partials.layout')
@push('styles')
    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
        .dropdown-item:hover{
            background: #ed1c24;
            color : #ffffff;
        }
    </style>
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            
            <table id="users-table" class="table table-hover table-responsive">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="select-all"></th>
                        <th scope="col">ID</th>
                        <th scope="col">USERNAME</th>
                        <th scope="col">NAME</th>
                        <th scope="col">EMAIL</th>
                        <th scope="col">MOBILE</th>
                        <th scope="col">REGISTERED</th>
                        <th scope="col">LAST SEEN</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function getSelectedIds() {
            let ids = [];
            $('.row-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }

        $(document).ready(function() {

            var table_top_left_html = `
            <div class="row mb-4">
                <div class="col">
                    <a href="{{ route('admin.user.add') }}"><button class="btn btn-sm bg-primary text-white">Create</button></a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger btn-sm delete-selected">Delete Selected</button>
                        <button type="button" class="btn btn-danger btn-sm dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item delete-selected" href="#" style="font-size:13px;">Delete Selected</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item activate-selected" href="#" style="font-size:13px;">Activate Selected</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item deactivate-selected" href="#" style="font-size:13px;">Deactivate Selected</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item restore-selected" href="#" style="font-size:13px;">Restore Selected</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            `;

            $('#select-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            var table = $('#users-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.get.users') }}",
                },
                dom: `
                    <"row mb-3"
                        <"col-md-6 align-content-end table-top-left"l>
                        <"col-md-6 text-end"
                        <"d-inline-flex gap-2 mb-3 mt-5"B>
                        f
                        >
                    >
                    rt
                    <"row mt-2"
                        <"col-md-6"i>
                        <"col-md-6 d-flex justify-content-end"p>
                    >
                    `,
                buttons: [{
                        extend: 'copyHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    }
                ],
                columns: [{
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        }
                    },
                    {
                        data: 'id',
                    },
                    {
                        data: 'username',
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: 'phone',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'last_login',
                    },

                ],
                initComplete: function() {
                    $('.table-top-left').prepend(table_top_left_html);
                }
            });

            $(document).on('click','.delete-selected', function() {
                
                const ids = getSelectedIds();
               
                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No rows selected',
                        text: 'Please select at least one user to delete.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the selected users.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.users.delete') }}',
                            method: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Selected users have been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong while deleting.',
                                });
                            },
                            complete: function() {
                                $('#select-all').prop('checked', false);
                            }
                        });
                    }
                });
            });

            $('#orders-table tbody').on('click', 'tr td:not(:first-child)', function() {
                const row = $(this).closest('tr');
                const id = row.find('.row-checkbox').val();
                window.location.href = '{{ route('admin.order.edit', ':id') }}'.replace(':id', id);
            });

            $(document).on('click','.restore-selected', function() {
                
                const ids = getSelectedIds();

                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No rows selected',
                        text: 'Please select at least one user to restore.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will restore the selected users.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, restore them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.users.restore') }}',
                            method: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Restored!',
                                    text: 'Selected users have been restored.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong while restoring.',
                                });
                            },
                            complete: function() {
                                $('#select-all').prop('checked', false);
                            }
                        });
                    }
                });
            });

            $(document).on('click','.deactivate-selected', function() {
                
                const ids = getSelectedIds();

                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No rows selected',
                        text: 'Please select at least one user to deactivate.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will deactivate the selected users.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, deactivate them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.users.deactivate') }}',
                            method: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deactivated!',
                                    text: 'Selected users have been deactivated.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong while restoring.',
                                });
                            },
                            complete: function() {
                                $('#select-all').prop('checked', false);
                            }
                        });
                    }
                });
            });


            $(document).on('click','.activate-selected', function() {
                
                const ids = getSelectedIds();

                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No rows selected',
                        text: 'Please select at least one user to activate.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will activate the selected users.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, activate them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.users.activate') }}',
                            method: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Activated!',
                                    text: 'Selected users have been activated.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                 $('#select-all').prop('checked', false);
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong while restoring.',
                                });
                            },
                            complete: function() {
                                $('#select-all').prop('checked', false);
                            }
                        });
                    }
                });
            });

            $('#users-table tbody').on('click', 'tr td:not(:first-child)', function() {
                const row = $(this).closest('tr');
                const id = row.find('.row-checkbox').val();
                window.location.href = '{{ route('admin.user.view', ':id') }}'.replace(':id', id);
            });
        });
    </script>
@endpush
