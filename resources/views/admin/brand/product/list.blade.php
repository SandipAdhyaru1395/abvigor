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
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title-icon {
            font-size: 24px;
            color: #667eea;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .btn-create {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 8px 20px;
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
            padding: 8px 20px;
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

        #products-table {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        #products-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white !important;
        }

        #products-table thead th {
            padding: 14px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.4px;
            border: none;
            color: #ffffff !important;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
        }

        #products-table thead th:first-child {
            border-top-left-radius: 12px;
        }

        #products-table thead th:last-child {
            border-top-right-radius: 12px;
        }

        #products-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        #products-table tbody tr:hover {
            background: linear-gradient(90deg, #f8f9ff 0%, #ffffff 100%);
            cursor: pointer;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #products-table tbody td {
            padding: 12px 12px;
            vertical-align: middle;
            color: #555;
            font-size: 14px;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
            border-radius: 4px;
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

        /* Empty State Styling */
        .dataTables_empty {
            padding: 40px !important;
            text-align: center;
            color: #999;
            font-style: italic;
        }

        .brand-filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .brand-filter-section .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-filter-section .form-label i {
            color: #667eea;
        }

        .brand-filter-section .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .brand-filter-section .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-check-label {
            font-weight: 500;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        #dragDropInfo {
            font-size: 13px;
            padding: 10px 15px;
            background: #fff3e0;
            border-left: 3px solid #ff9800;
            border-radius: 6px;
            color: #e65100;
            font-weight: 500;
        }

        #dragDropInfo i {
            color: #ff9800;
            margin-right: 8px;
            font-size: 14px;
        }

        /* Drag and Drop Styles */
        #products-table tbody tr {
            cursor: move;
        }

        #products-table tbody tr.ui-sortable-helper {
            background: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: rotate(2deg);
        }

        #products-table tbody tr.ui-sortable-placeholder {
            height: 50px;
            background: #e9ecef;
            border: 2px dashed #667eea;
            visibility: visible !important;
        }

        .drag-handle {
            cursor: move;
            color: #667eea;
            font-size: 18px;
            padding: 5px;
        }

        .drag-handle:hover {
            color: #764ba2;
        }

        @media (max-width: 768px) {
            .orders-card {
                padding: 20px;
                border-radius: 12px;
            }

            .page-title {
                font-size: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                text-align: center;
            }

            .brand-filter-section .row {
                flex-direction: column;
            }

            .brand-filter-section .col-md-8 {
                margin-top: 15px;
            }
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
                            <i class="fas fa-boxes page-title-icon"></i>
                            Brand Products
                        </h1>
                    </div>

                    <!-- Brand Filter -->
                    <div class="brand-filter-section mb-4">
                        <div id="dragDropInfo" class="mb-3" style="display: none;">
                            <i class="fas fa-info-circle"></i> <span id="dragDropInfoText"></span>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="brandFilter" class="form-label">
                                    <i class="fas fa-filter"></i> Filter by Brand
                                </label>
                                <select class="form-select" id="brandFilter" aria-label="Select brand">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 text-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enableDragDrop" checked>
                                    <label class="form-check-label" for="enableDragDrop">
                                        <i class="fas fa-arrows-alt"></i> Enable Drag & Drop Ordering
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="products-table" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;"><input type="checkbox" id="select-all"></th>
                                    <th scope="col" style="width: 50px;"><i class="fas fa-grip-vertical"></i></th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Brand</th>
                                    <th scope="col">Product Code</th>
                                    <th scope="col">Copy</th>
                                </tr>
                            </thead>
                            <tbody id="products-tbody">

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
                    <a href="{{ route('admin.brand.product.add') }}"><button class="btn btn-create">+ Create Product</button></a>
                    <button id="delete-selected" class="btn btn-delete-selected">Delete Selected</button>
                </div>`;

            $('#select-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            var table = $('#products-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.get.brand.products') }}',
                    data: function(d) {
                        d.brand_id = $('#brandFilter').val();
                    }
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
                            columns: ':visible:not(:first-child):not(:nth-child(2))'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:nth-child(2))'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:nth-child(2))'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:nth-child(2))'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child):not(:nth-child(2))'
                        }
                    }
                ],
                columns: [{
                        data: 'id',
                        width: '5%',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        },
                    },
                    {
                        data: null,
                        width: '5%',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<i class="fas fa-grip-vertical drag-handle"></i>`;
                        },
                    },
                    {
                        data: 'title',
                    },
                    {
                        data: 'brand_name',
                        width: '15%',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'product_code',
                        width: '15%',
                    },
                    {
                        data: 'id',
                        width: '15%',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-sm btn-primary text-white copy-product-btn" data-id="${data}">Copy Product</button>`;
                        },
                    }
                ],
                initComplete: function() {
                    $('.table-top-left').prepend(table_top_left_html);
                    // Initial check for drag drop info
                    checkAndInitSortable();
                },
                drawCallback: function() {
                    checkAndInitSortable();
                }
            });

            // Brand filter change
            $('#brandFilter').on('change', function() {
                table.ajax.reload();
                // Reinitialize sortable after reload if enabled and brand is selected
                setTimeout(function() {
                    checkAndInitSortable();
                }, 500);
            });

            // Enable/Disable drag and drop
            $('#enableDragDrop').on('change', function() {
                checkAndInitSortable();
            });

            // Check if sortable should be enabled and initialize/destroy accordingly
            function checkAndInitSortable() {
                var brandSelected = $('#brandFilter').val() !== '';
                var dragDropEnabled = $('#enableDragDrop').is(':checked');
                var infoDiv = $('#dragDropInfo');
                var infoText = $('#dragDropInfoText');
                
                if (brandSelected && dragDropEnabled) {
                    initSortable();
                    infoDiv.hide();
                } else {
                    var tbody = $('#products-table tbody');
                    if (tbody.hasClass('ui-sortable')) {
                        tbody.sortable('destroy');
                    }
                    
                    // Show info message
                    if (dragDropEnabled && !brandSelected) {
                        infoText.text('Please select a brand to enable drag & drop ordering');
                        infoDiv.show();
                    } else if (!dragDropEnabled) {
                        infoText.text('Drag & drop ordering is disabled');
                        infoDiv.show();
                    } else {
                        infoDiv.hide();
                    }
                }
            }

            // Initialize sortable
            function initSortable() {
                if (!$('#enableDragDrop').is(':checked')) {
                    return;
                }

                // Check if brand is selected
                if ($('#brandFilter').val() === '') {
                    var tbody = $('#products-table tbody');
                    if (tbody.hasClass('ui-sortable')) {
                        tbody.sortable('destroy');
                    }
                    return;
                }

                // Use setTimeout to ensure DataTable has rendered
                setTimeout(function() {
                    var tbody = $('#products-table tbody');
                    
                    // Destroy existing sortable if any
                    if (tbody.hasClass('ui-sortable')) {
                        tbody.sortable('destroy');
                    }

                    tbody.sortable({
                        handle: '.drag-handle',
                        placeholder: 'ui-sortable-placeholder',
                        helper: function(e, tr) {
                            var $originals = tr.children();
                            var $helper = tr.clone();
                            $helper.children().each(function(index) {
                                $(this).width($originals.eq(index).width());
                            });
                            return $helper;
                        },
                        update: function(event, ui) {
                            var productIds = [];
                            $('#products-table tbody tr').each(function() {
                                var id = $(this).find('.row-checkbox').val();
                                if (id) {
                                    productIds.push(id);
                                }
                            });

                            if (productIds.length > 0) {
                                // Save order
                                $.ajax({
                                    url: '{{ route('admin.brand.products.update.order') }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        product_ids: productIds
                                    },
                                    success: function(response) {
                                        if (response.status) {
                                            toastr.success('Product order updated successfully');
                                        } else {
                                            toastr.error('Failed to update product order');
                                            table.ajax.reload(null, false);
                                        }
                                    },
                                    error: function() {
                                        toastr.error('Failed to update product order');
                                        table.ajax.reload(null, false);
                                    }
                                });
                            }
                        }
                    });
                }, 100);
            }

            // $('.table-top-left').html($('.table-top-left').html() + table_top_left_html );

            $(document).on('click','#delete-selected', function() {
                const ids = getSelectedIds();

                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No rows selected',
                        text: 'Please select at least one product to delete.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the selected products.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.brand.products.delete') }}',
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
                                    text: 'Selected products have been deleted.',
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
                            }
                        });
                    }
                });
            });

            // Prevent row click when clicking on drag handle or checkbox
            $('#products-table tbody').on('click', 'tr td:not(:first-child):not(:nth-child(2))', function(e) {
                if ($(e.target).hasClass('drag-handle') || $(e.target).closest('.drag-handle').length) {
                    return;
                }
                if ($(e.target).hasClass('copy-product-btn') || $(e.target).closest('.copy-product-btn').length) {
                    return;
                }
                const row = $(this).closest('tr');
                const id = row.find('.row-checkbox').val();
                window.location.href = '{{ route('admin.brand.product.edit', ':id') }}'.replace(':id', id);
            });

            // table.column(1).visible(false);
        });
    </script>
@endpush
