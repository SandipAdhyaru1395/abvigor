@extends('admin.partials.layout')
@push('styles')
    <style>
        .users-page-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .users-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .users-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title-icon {
            font-size: 28px;
            color: #667eea;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .btn-create {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-delete-selected {
            background: linear-gradient(135deg, #ed1c24 0%, #ff6b6b 100%);
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(237, 28, 36, 0.4);
        }

        .btn-delete-selected:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(237, 28, 36, 0.6);
            color: white;
        }

        /* Stylish bulk actions dropdown */
        .action-buttons .btn-group .dropdown-menu {
            background: #ffffff;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            padding: 6px 0;
            overflow: hidden;
        }

        .action-buttons .dropdown-item {
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            color: #34495e;
        }

        .action-buttons .dropdown-item + .dropdown-item {
            border-top: 1px solid #f1f1f1;
        }

        .action-buttons .dropdown-item:hover {
            background: linear-gradient(135deg, #ed1c24 0%, #ff6b6b 100%);
            color: #ffffff !important;
        }

        .users-card .table-responsive {
            padding: 10px;
        }

        #users-table {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            table-layout: fixed; /* keep table within screen width */
        }

        /* Force white text color for all table headers - highest priority */
        #users-table thead th {
            color: #ffffff !important;
        }

        #users-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white !important;
        }

        #users-table thead th,
        #users-table thead th.sorting,
        #users-table thead th.sorting_asc,
        #users-table thead th.sorting_desc,
        #users-table.table thead th,
        #users-table.table thead th.sorting,
        #users-table.table thead th.sorting_asc,
        #users-table.table thead th.sorting_desc {
            padding: 12px 8px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border: none;
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
        }

        #users-table thead th *,
        #users-table thead th.sorting *,
        #users-table thead th.sorting_asc *,
        #users-table thead th.sorting_desc *,
        #users-table.table thead th *,
        #users-table.table thead th.sorting *,
        #users-table.table thead th.sorting_asc *,
        #users-table.table thead th.sorting_desc * {
            color: #ffffff !important;
        }

        #users-table thead th input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            opacity: 0.9;
        }

        #users-table thead th input[type="checkbox"]:hover {
            opacity: 1;
        }

        #users-table thead th:first-child {
            border-top-left-radius: 12px;
        }

        #users-table thead th:last-child {
            border-top-right-radius: 12px;
        }

        #users-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        #users-table tbody tr:hover {
            background: linear-gradient(90deg, #f8f9ff 0%, #ffffff 100%);
            cursor: pointer;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #users-table tbody td {
            padding: 10px 8px;
            vertical-align: middle;
            color: #555;
            font-size: 13px;
            word-wrap: break-word;
            white-space: normal; /* allow wrapping so table stays within screen */
        }

        /* Explicit column widths so they always take effect */
        #users-table thead th:nth-child(1),
        #users-table tbody td:nth-child(1) {
            width: 4% !important;
        }

        #users-table thead th:nth-child(2),
        #users-table tbody td:nth-child(2) {
            width: 5% !important;
        }

        #users-table thead th:nth-child(3),
        #users-table tbody td:nth-child(3) {
            width: 10% !important; /* username */
        }

        #users-table thead th:nth-child(4),
        #users-table tbody td:nth-child(4) {
            width: 15% !important; /* name */
        }

        #users-table thead th:nth-child(5),
        #users-table tbody td:nth-child(5) {
            width: 15% !important; /* email */
        }

        #users-table thead th:nth-child(6),
        #users-table tbody td:nth-child(6) {
            width: 8% !important; /* phone */
        }

        #users-table thead th:nth-child(7),
        #users-table tbody td:nth-child(7) {
            width: 15% !important; /* dealership name */
        }

        #users-table thead th:nth-child(8),
        #users-table tbody td:nth-child(8) {
            width: 10% !important; /* registered */
        }

        #users-table thead th:nth-child(9),
        #users-table tbody td:nth-child(9) {
            width: 10% !important; /* last seen */
        }

        #users-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        #users-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        /* Custom Checkbox Styling */
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
            border-radius: 4px;
        }

        input[type="checkbox"]:checked {
            background-color: #667eea;
        }

        /* DataTables Controls Styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin: 15px 0;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 6px 12px;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Export Buttons Styling */
        .dt-buttons .btn {
            background: white;
            border: 2px solid #e0e0e0;
            color: #555;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 8px;
        }

        .dt-buttons .btn:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Pagination Styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 0 2px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #667eea;
            border-color: #667eea;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white !important;
        }

        /* Loading Spinner */
        .dataTables_processing {
            background: rgba(255, 255, 255, 0.95) !important;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            color: #667eea;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .users-card {
                padding: 20px;
                border-radius: 12px;
                overflow: visible;
            }

            .action-buttons {
                overflow: visible;
            }

            .page-title {
                font-size: 22px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .action-buttons .btn,
            .action-buttons .btn-group {
                width: 100%;
            }

            .action-buttons .btn-group {
                display: flex;
                position: relative;
            }

            .action-buttons .btn-group .btn {
                flex: 1;
            }

            .action-buttons .btn-group .btn:first-child {
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
            }

            .action-buttons .btn-group .dropdown-toggle-split {
                flex: 0 0 auto;
                width: 50px;
                min-width: 50px;
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
            }

            .action-buttons .btn-group .dropdown-menu {
                width: auto;
                min-width: 200px;
                right: 0 !important;
                left: auto !important;
                transform: none !important;
                margin-top: 8px !important;
                top: 100% !important;
            }

            #users-table {
                font-size: 11px;
                min-width: 800px;
            }

            #users-table thead th,
            #users-table tbody td {
                padding: 8px 6px;
            }

            .users-card .table-responsive {
                padding: 5px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        @media (max-width: 480px) {
            .users-card {
                padding: 12px;
                overflow: visible;
            }

            .action-buttons {
                overflow: visible;
            }

            .page-title {
                font-size: 18px;
            }

            #users-table {
                font-size: 10px;
                min-width: 720px;
            }

            #users-table thead th,
            #users-table tbody td {
                padding: 6px 4px;
                font-size: 10px;
            }

            .action-buttons .btn,
            .action-buttons .btn-group {
                width: 100%;
            }

            .action-buttons .btn-group {
                display: flex;
                position: relative;
            }

            .action-buttons .btn-group .btn {
                flex: 1;
            }

            .action-buttons .btn-group .btn:first-child {
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
            }

            .action-buttons .btn-group .dropdown-toggle-split {
                flex: 0 0 auto;
                width: 45px;
                min-width: 45px;
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
            }

            .action-buttons .btn-group .dropdown-menu {
                width: auto;
                min-width: 200px;
                right: 0 !important;
                left: auto !important;
                transform: none !important;
                margin-top: 8px !important;
                top: 100% !important;
            }

            .action-buttons .btn {
                font-size: 13px;
                padding: 8px 16px;
            }
        }

        /* Empty State Styling */
        .dataTables_empty {
            padding: 40px !important;
            text-align: center;
            color: #999;
            font-style: italic;
        }

        .dropdown-item:hover{
            background: #ed1c24;
            color : #ffffff;
        }
    </style>
@endpush
@section('content')
    <div class="users-page-wrapper">
        <div class="admin container-fluid py-2">
            @include('admin.partials.sidebar')
            <div class="admin main-content p-4">
                <div class="users-card">
                    <div class="page-header">
                        <h1 class="page-title">
                            <i class="fas fa-users page-title-icon"></i>
                            Users Management
                        </h1>
                    </div>

                    <div class="table-responsive">
                        <table id="users-table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col"><input type="checkbox" id="select-all"></th>
                                    <th scope="col">ID</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">NAME</th>
                                    <th scope="col">EMAIL</th>
                                    <th scope="col">MOBILE</th>
                                    <th scope="col">DEALERSHIP NAME</th>
                                    <th scope="col">REGISTERED</th>
                                    <th scope="col">LAST SEEN</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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

            var table_top_left_html = `<div class="action-buttons">
                    <a href="{{ route('admin.user.add') }}"><button class="btn btn-create">+ Create User</button></a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-delete-selected delete-selected">Delete Selected</button>
                        <button type="button" class="btn btn-delete-selected dropdown-toggle dropdown-toggle-split"
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
                </div>`;

            $('#select-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            var table = $('#users-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.get.users') }}",
                },
                dom: `
                    <"row mb-3"
                        <"col-md-6 align-content-end table-top-left"l>
                        <"col-md-6 text-end"
                        <"d-inline-flex gap-2 mb-3 mt-3"B>
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
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        }
                    },
                    {
                        data: 'id',
                        width: '5%',
                    },
                    {
                        data: 'username',
                        width: '10%',
                    },
                    {
                        data: 'name',
                        width: '15%',
                    },
                    {
                        data: 'email',
                        width: '15%',
                    },
                    {
                        data: 'phone',
                        width: '8%',
                    },
                    {
                        data: 'dealership_name',
                        width: '15%',
                    },
                    {
                        data: 'created_at',
                        width: '10%',
                    },
                    {
                        data: 'last_login',
                        width: '10%',
                    }

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
