@extends('admin.partials.layout')
@push('styles')
    <style>
        .orders-page-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .orders-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .orders-card:hover {
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

        .orders-card .table-responsive {
            padding: 10px;
        }

        #orders-table {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Force white text color for all table headers - highest priority */
        #orders-table thead th {
            color: #ffffff !important;
        }

        #orders-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white !important;
        }

        #orders-table thead th,
        #orders-table thead th.sorting,
        #orders-table thead th.sorting_asc,
        #orders-table thead th.sorting_desc,
        #orders-table.table thead th,
        #orders-table.table thead th.sorting,
        #orders-table.table thead th.sorting_asc,
        #orders-table.table thead th.sorting_desc {
            padding: 18px 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border: none;
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
        }

        #orders-table thead th *,
        #orders-table thead th.sorting *,
        #orders-table thead th.sorting_asc *,
        #orders-table thead th.sorting_desc *,
        #orders-table.table thead th *,
        #orders-table.table thead th.sorting *,
        #orders-table.table thead th.sorting_asc *,
        #orders-table.table thead th.sorting_desc * {
            color: #ffffff !important;
        }

        #orders-table thead th input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            opacity: 0.9;
        }

        #orders-table thead th input[type="checkbox"]:hover {
            opacity: 1;
        }

        #orders-table thead th:first-child {
            border-top-left-radius: 12px;
        }

        #orders-table thead th:last-child {
            border-top-right-radius: 12px;
        }

        #orders-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        #orders-table tbody tr:hover {
            background: linear-gradient(90deg, #f8f9ff 0%, #ffffff 100%);
            cursor: pointer;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #orders-table tbody td {
            padding: 16px 15px;
            vertical-align: middle;
            color: #555;
            font-size: 14px;
        }

        #orders-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        #orders-table tbody tr:last-child td:last-child {
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
            .orders-card {
                padding: 20px;
                border-radius: 12px;
            }

            .page-title {
                font-size: 22px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }

            #orders-table {
                font-size: 12px;
            }

            #orders-table thead th,
            #orders-table tbody td {
                padding: 10px 8px;
            }
        }

        /* Empty State Styling */
        .dataTables_empty {
            padding: 40px !important;
            text-align: center;
            color: #999;
            font-style: italic;
        }

        /* Actions Column Styling */
        #orders-table tbody td:last-child {
            text-align: center !important;
            vertical-align: middle !important;
        }

        #orders-table .delete-item {
            padding: 6px 10px;
            font-size: 14px;
            background: transparent !important;
            border: 1px solid transparent !important;
            border-radius: 6px !important;
            color: #dc3545 !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 36px;
            min-height: 36px;
        }

        #orders-table .delete-item:hover {
            background: rgba(220, 53, 69, 0.1) !important;
            border-color: rgba(220, 53, 69, 0.3) !important;
            color: #c82333 !important;
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
        }

        #orders-table .delete-item:active {
            transform: scale(0.95);
        }

        #orders-table .delete-item i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        #orders-table .delete-item:hover i {
            transform: rotate(-10deg) scale(1.1);
        }
    </style>
@endpush
@section('content')
    <div class="orders-page-wrapper">
        <div class="admin container-fluid py-2">
            @include('admin.partials.sidebar')
            <div class="admin main-content p-4">
                <div class="orders-card">
                    <div class="page-header">
                        <h1 class="page-title">
                            <i class="fas fa-clipboard-list page-title-icon"></i>
                            Orders Management
                        </h1>
                    </div>

                    <div class="table-responsive">
                        <table id="orders-table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col"><input type="checkbox" id="select-all"></th>
                                    <th scope="col">Order Number</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Actions</th>
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
                    <a href="{{ route('admin.order.add') }}"><button class="btn btn-create">+ Create Order</button></a>
                    <button id="delete-selected" class="btn btn-delete-selected">Delete Selected</button>
                </div>`;

            $('#select-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
               
            });

            var table = $('#orders-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.get.orders') }}',
                },
                dom: `
                    <"row mb-3"
                        <"col-md-6 align-content-end table-top-left"l>
                        <"col-md-6 text-end"
                        <"d-inline-flex gap-2 mb-3"B>
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
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:last-child)'
                        }
                    }
                ],
                columns: [{
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        },
                    },
                    {
                        data: 'order_no',
                        name: 'order_no',
                        width: '15%',

                    },
                    {
                        data: 'order_date',
                        name: 'order_date',
                    },
                    {
                        data: 'client',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-sm delete-item text-danger delete-order-btn" data-id="${data}" title="Delete Order">
                                        <i class="fas fa-trash"></i>
                                    </button>`;
                        },
                    },

                ],
                initComplete: function() {
                    $('.table-top-left').prepend(table_top_left_html);
                }
            });

            // $('.table-top-left').html($('.table-top-left').html() + table_top_left_html );

            $(document).on('click','#delete-selected', function() {
                const ids = getSelectedIds();

                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No rows selected',
                        text: 'Please select at least one order to delete.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the selected orders.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.orders.delete') }}',
                            method: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#orders-table').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Selected orders have been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                $('#select-all').prop('checked', false);
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong while deleting.',
                                });
                            }
                        });
                    }
                });
            });

            // Handle row click to edit (exclude checkbox and actions column)
            $('#orders-table tbody').on('click', 'tr td:not(:first-child):not(:last-child)', function(e) {
                // Don't navigate if clicking on a button or link
                if ($(e.target).is('button, a, input') || $(e.target).closest('button, a, input').length) {
                    return;
                }
                const row = $(this).closest('tr');
                const id = row.find('.row-checkbox').val();
                window.location.href = '{{ route('admin.order.edit', ':id') }}'.replace(':id', id);
            });

            // Handle individual delete button click
            $(document).on('click', '.delete-order-btn', function(e) {
                e.stopPropagation(); // Prevent row click event
                const id = $(this).data('id');
                const orderNo = $(this).closest('tr').find('td:eq(1)').text().trim();

                Swal.fire({
                    title: 'Are you sure?',
                    text: `This will permanently delete order "${orderNo}".`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.orders.delete') }}',
                            method: 'POST',
                            data: {
                                ids: [id],
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#orders-table').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Order has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                $('#select-all').prop('checked', false);
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong while deleting.',
                                });
                            }
                        });
                    }
                });
            });

            // table.column(1).visible(false);
        });
    </script>
@endpush
