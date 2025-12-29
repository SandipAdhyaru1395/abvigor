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

        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 10px;
        }

        .orders-card label.form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .orders-card .form-control,
        .orders-card .form-select {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }

        .orders-card .form-control:focus,
        .orders-card .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
            background-color: #ffffff;
        }

        .orders-card .error-text {
            font-size: 0.8rem;
        }

        /* Select2 Styling to match form controls height */
        .orders-card .select2-container {
            width: 100% !important;
        }

        .orders-card .select2-container--default .select2-selection--single {
            height: auto !important;
            min-height: 38px;
            border: 1px solid #adb5bd;
            border-radius: 8px;
            padding: 0;
            font-size: 0.9rem;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            background-color: #fff;
            display: flex;
            align-items: center;
        }

        .orders-card .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            padding: 8px 12px;
            color: #212529;
            width: 100%;
        }

        .orders-card .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .orders-card .select2-container--default.select2-container--focus .select2-selection--single,
        .orders-card .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
        }

        .orders-card .select2-container--default .select2-selection--single:hover {
            border-color: #adb5bd;
        }

        /* Select2 Dropdown Styling */
        .select2-dropdown {
            border: 1px solid #adb5bd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #adb5bd;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.9rem;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.15);
        }

        #order-products-table {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        #order-products-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white !important;
        }

        #order-products-table thead th {
            padding: 14px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.4px;
            border: none;
            color: #ffffff !important;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            display: table-cell !important;
            visibility: visible !important;
        }

        #order-products-table thead th:first-child {
            border-top-left-radius: 12px;
        }

        #order-products-table thead th:last-child {
            border-top-right-radius: 12px;
        }

        #order-products-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        #order-products-table tbody tr:hover {
            background: linear-gradient(90deg, #f8f9ff 0%, #ffffff 100%);
            cursor: pointer;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #order-products-table tbody td {
            padding: 12px 12px;
            vertical-align: middle;
            color: #555;
            font-size: 14px;
        }

        /* Inline editable quantity */
        .editable-qty {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
            display: inline-block;
            min-width: 40px;
            text-align: center;
        }

        /* Make the entire quantity td clickable */
        #order-products-table tbody td:nth-child(4) {
            cursor: pointer;
            position: relative;
        }

        #order-products-table tbody td:nth-child(4):hover {
            background-color: #f8f9fa;
        }

        .editable-qty.editing {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
            width: 100% !important;
            vertical-align: middle;
        }

        .qty-input {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            text-align: center;
            border: 2px solid #667eea !important;
            border-radius: 4px;
            padding: 6px 8px !important;
            font-size: 14px;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: #fff !important;
            margin: 0 !important;
            display: block !important;
            vertical-align: middle;
            box-sizing: border-box;
        }

        .qty-input:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2) !important;
            outline: none !important;
        }

        /* Ensure the td containing editable quantity allows full width input */
        #order-products-table tbody td:has(.editable-qty.editing) {
            padding: 12px 12px !important;
        }


        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn-primary,
        .btn-danger,
        .btn-secondary {
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.5);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ed1c24 0%, #ff6b6b 100%);
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(237, 28, 36, 0.5);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #6c757d;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(108, 117, 125, 0.5);
        }

        /* Checkbox styles - with high specificity to prevent overrides */
        #order-products-table input[type="checkbox"],
        #order-products-table th input[type="checkbox"],
        #order-products-table td input[type="checkbox"],
        input[type="checkbox"].row-checkbox,
        input[type="checkbox"]#select-all {
            width: 18px !important;
            height: 18px !important;
            cursor: pointer !important;
            accent-color: #667eea !important;
            border-radius: 4px !important;
        }

        /* Center checkbox in header and cells */
        #order-products-table thead th:first-child,
        #order-products-table tbody td:first-child {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Actions column styling */
        #order-products-table tbody td:last-child {
            text-align: center !important;
            vertical-align: middle !important;
        }

        #order-products-table .delete-item {
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

        #order-products-table .delete-item:hover {
            background: rgba(220, 53, 69, 0.1) !important;
            border-color: rgba(220, 53, 69, 0.3) !important;
            color: #c82333 !important;
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
        }

        #order-products-table .delete-item:active {
            transform: scale(0.95);
        }

        #order-products-table .delete-item i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        #order-products-table .delete-item:hover i {
            transform: rotate(-10deg) scale(1.1);
        }

        /* Fix datepicker calendar z-index to appear above header */
        .datepicker,
        .datepicker-dropdown {
            z-index: 1050 !important;
        }

        .product-section {
            display: none;
            margin-top: 30px;
        }

        .product-section.active {
            display: block;
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
                align-items: stretch;
            }

            .action-buttons .btn {
                width: 100%;
                text-align: center;
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
                            <i class="fas fa-plus-circle page-title-icon"></i>
                            Create Order
                        </h1>
                    </div>

                    <form class="mb-3" id="orderForm" action="{{ route('admin.order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" id="order_id" value="">
                        

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="order_date" class="form-label align-self-end">Order Date : <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" name="order_date" id="order_date"
                                    value="{{ old('order_date') }}" readonly autocomplete="off">
                                @error('order_date')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="customer_name" class="form-label align-self-end">Client : <span
                                        class="text-danger">*</span></label>
                                <select class="form-select select2" name="user_id" data-placeholder="Select Client"
                                    id="client" aria-label="Default select example">
                                    @if (old('user_id'))
                                        @php
                                            $selectedUser = \App\Models\User::find(old('user_id'));
                                        @endphp
                                        @if ($selectedUser)
                                            <option value="{{ $selectedUser->id }}" selected>{{ $selectedUser->name }} ({{ $selectedUser->email }})</option>
                                        @endif
                                    @else
                                        <option value="">Select client</option>
                                    @endif
                                </select>
                                @error('user_id')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row" id="orderInfoButtons">
                            <div class="col">
                                <div class="action-buttons">
                                    <button type="button" id="saveOrderInfo" class="btn btn-sm btn-primary text-white">Save Order Info</button>
                                    <a href="{{ route('admin.order.list') }}"
                                        class="btn btn-sm btn-secondary text-white">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Product Management Section (shown after order is created) -->
                    <div class="product-section" id="productSection">
                        <div class="table-responsive">
                            <table id="order-products-table" class="table mt-2 table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"><input type="checkbox" id="select-all"></th>
                                        <th scope="col">Part No</th>
                                        <th scope="col">Brand</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Main Product Name</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4 align-items-center">
                            <div class="col-md-8 mb-3 mb-md-0 text-left">
                                    <button type="button" id="finalizeOrder" class="btn btn-sm btn-primary text-white">Create Order</button>
                                    <button type="button" id="finalizeOrderAndClose" class="btn btn-sm btn-primary text-white">Create &amp; Close</button>
                                    <a href="{{ route('admin.order.list') }}"
                                        class="btn btn-sm btn-secondary text-white">Cancel</a>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <button type="button" id="delete-order" class="btn btn-sm btn-danger" style="display: none;">Delete Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalTitle">Create Order Product List</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mt-3">
                            <label>Brand : <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="add_brand_id" data-placeholder="Select Brand"
                                id="add_brand_id" aria-label="Default select example">
                                @if ($brands)
                                    <option selected value="">Select brand</option>
                                @endif
                                @forelse ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                @empty
                                    <option value="">No brand found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label>Product : <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="add_product_id" data-placeholder="Select Product"
                                id="add_product_id" aria-label="Default select example">
                                <option selected value="">Select product</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label>Quantity : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="add_quantity" id="add_quantity"
                                onkeypress="return /[0-9]/i.test(event.key)" autocomplete="off">
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label>Old Product Name : </label>
                            <input type="text" class="form-control" name="add_old_product_name"
                                id="add_old_product_name" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="create" class="btn btn-sm btn-primary text-white">Create</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalTitle">Update Order Product List</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mt-3">
                            <input type="hidden" name="id">
                            <label>Brand : <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="edit_brand_id" data-placeholder="Select Brand"
                                id="edit_brand_id" aria-label="Default select example">
                                @if ($brands)
                                    <option selected value="">Select brand</option>
                                @endif
                                @forelse ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                @empty
                                    <option value="">No brand found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label>Product : <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="edit_product_id" data-placeholder="Select Product"
                                id="edit_product_id" aria-label="Default select example">
                                <option selected value="">Select product</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label>Quantity : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="edit_quantity" id="edit_quantity"
                                onkeypress="return /[0-9]/i.test(event.key)" autocomplete="off">
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label>Old Product Name : </label>
                            <input type="text" class="form-control" name="edit_old_product_name"
                                id="edit_old_product_name" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="update" class="btn btn-sm btn-primary text-white">Update</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var orderId = $('#order_id').val() || null;
            
            // Show delete button if order already exists
            if (orderId) {
                $('#delete-order').show();
            }
            var table = null;
            var editModalData = null;
            var isSettingBrandProgrammatically = false;

            // Initialize client dropdown with AJAX (optimized for large datasets)
            // Wait a bit to ensure global Select2 initialization is done, then override
            setTimeout(function() {
                // Destroy default Select2 initialization first
                if ($('#client').hasClass('select2-hidden-accessible')) {
                    $('#client').select2('destroy');
                }
                
                var selectedUserId = $('#client').val();
                
                $('#client').select2({
                    placeholder: 'Select client',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 0, // Show initial results without typing
                    ajax: {
                        url: "{{ route('admin.order.search.users') }}",
                        dataType: 'json',
                        delay: 250, // Wait 250ms after user stops typing
                        data: function (params) {
                            return {
                                search: params.term || '', // Search term
                                page: params.page || 1 // Page number for pagination
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    }
                });

                // If there's a pre-selected value (from old input), load it
                if (selectedUserId) {
                    $.ajax({
                        url: "{{ route('admin.order.search.users') }}",
                        dataType: 'json',
                        data: {
                            id: selectedUserId
                        }
                    }).done(function(data) {
                        if (data.results && data.results.length > 0) {
                            var option = new Option(data.results[0].text, data.results[0].id, true, true);
                            $('#client').append(option).trigger('change');
                        }
                    });
                }
            }, 100);

            // Smart Order Number Generation
            $('#regenerateOrderNo').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.order.generate.number') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.status) {
                            $('#order_no').val(response.order_no);
                            toastr.success('New order number generated');
                        }
                    },
                    error: function() {
                        toastr.error('Error generating order number');
                    }
                });
            });

            // Save Order Info (create order first)
            $('#saveOrderInfo').on('click', function() {
                var orderDate = $('#order_date').val();
                var userId = $('#client').val();

                if (!orderDate || !userId) {
                    toastr.error('Please fill all required fields');
                    return;
                }

                // Create order via AJAX
                $.ajax({
                    url: "{{ route('admin.order.store') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_date: orderDate,
                        user_id: userId,
                        create_only: true // Flag to just create, don't redirect
                    },
                    success: function(response) {
                        // Extract order ID from redirect URL or response
                        if (response.order_id) {
                            orderId = response.order_id;
                            $('#order_id').val(orderId);
                            $('#delete-order').show();
                            $('#productSection').addClass('active');
                            // Hide order info buttons
                            $('#orderInfoButtons').hide();
                            toastr.success('Order info saved. You can now add products.');
                            // Small delay to ensure section is visible before initializing table
                            setTimeout(function() {
                                initializeProductTable();
                            }, 100);
                        } else {
                            // If response is redirect, extract ID from URL
                            toastr.success('Order created successfully');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Error creating order');
                        }
                    }
                });
            });

            // Initialize Product Table
            function initializeProductTable() {
                if (table) return; // Already initialized

                var table_top_left_html = `<div class="row">
                    <div class="col d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-secondary text-white" data-bs-toggle="modal"
                            data-bs-target="#addProductModal">Create Order Product List</button>
                        <button type="button" class="btn btn-sm btn-danger" id="delete-selected">Delete</button>
                    </div>
                </div>`;

                // Select all checkbox functionality
                // Select all checkbox functionality will be initialized in initComplete and drawCallback
                // Update select-all checkbox when individual checkboxes are clicked
                $(document).on('change', '.row-checkbox', function() {
                    var totalCheckboxes = $('.row-checkbox').length;
                    var checkedCheckboxes = $('.row-checkbox:checked').length;
                    $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
                });

                table = $('#order-products-table').DataTable({
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('admin.order.product.list') }}',
                        data: function(d) {
                            d.order_id = orderId;
                        }
                    },
                    dom: `<"row mb-3"
                        <"col-md-6 align-content-end table-top-left">
                        <"col-md-6 text-end"
                        <"d-inline-flex gap-2 mb-2">
                        f
                        >
                    >
                    rt
                    <"row mt-2"
                        <"col-md-6"i>
                        <"col-md-6 d-flex justify-content-end"p>
                    >`,
                    columns: [{
                        data: 'id',
                        name: 'id',
                        title: '<input type="checkbox" id="select-all">',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        },
                    },
                    {
                        data: 'part_no',
                        name: 'part_no',
                        title: 'Part No',
                        width: '15%',
                    },
                    {
                        data: 'brand',
                        name: 'brand',
                        title: 'Brand',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        title: 'Quantity',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span class="editable-qty" data-id="${row.id}" data-qty="${data}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'main_product_name',
                        name: 'main_product_name',
                        title: 'Main Product Name',
                    },
                    {
                        data: 'product_name',
                        name: 'product_name',
                        title: 'Product Name',
                    },
                    {
                        data: 'id',
                        name: 'id',
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-sm delete-item text-danger" data-id="${data}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                        },
                    }],
                    initComplete: function() {
                        $('.table-top-left').prepend(table_top_left_html);
                        // Re-initialize select-all checkbox after table is initialized
                        $('#select-all').on('click', function() {
                            $('.row-checkbox').prop('checked', this.checked);
                        });
                        // Redraw table to ensure headers are visible after a short delay
                        setTimeout(function() {
                            table.columns.adjust().draw();
                        }, 50);
                    },
                    drawCallback: function() {
                        // Re-initialize select-all checkbox after each draw
                        $('#select-all').off('click').on('click', function() {
                            $('.row-checkbox').prop('checked', this.checked);
                        });
                    }
                });

                // Inline quantity editing - make entire td clickable
                $(document).on('click', '#order-products-table tbody td:nth-child(4)', function(e) {
                    // Don't trigger if clicking on input or if already editing
                    if ($(e.target).hasClass('qty-input') || $(e.target).closest('.qty-input').length) {
                        return;
                    }
                    
                    var $td = $(this);
                    var $editableQty = $td.find('.editable-qty');
                    
                    // Don't edit if already editing
                    if ($editableQty.hasClass('editing')) {
                        return;
                    }
                    
                    e.stopPropagation(); // Prevent row click
                    
                    var currentQty = $editableQty.data('qty');
                    var productId = $editableQty.data('id');
                    var originalText = $editableQty.text();
                    
                    $editableQty.addClass('editing');
                    var input = $('<input>', {
                        type: 'number',
                        class: 'qty-input',
                        value: currentQty,
                        min: 1
                    });
                    
                    // Replace content with input
                    $editableQty.html(input);
                    input.focus().select();
                    
                    input.on('blur', function() {
                        var newQty = parseInt($(this).val()) || 0;
                        if (newQty != currentQty && newQty > 0) {
                            updateQuantity(productId, newQty);
                        } else {
                            $editableQty.removeClass('editing').text(originalText);
                        }
                    });
                    
                    input.on('keypress', function(e) {
                        if (e.which === 13) { // Enter key
                            e.preventDefault();
                            $(this).blur();
                        }
                        if (e.which === 27) { // Escape key
                            e.preventDefault();
                            $editableQty.removeClass('editing').text(originalText);
                            input.remove();
                        }
                    });
                    
                    // Prevent click event from bubbling
                    input.on('click', function(e) {
                        e.stopPropagation();
                    });
                });

                // Edit product on row click
                $('#order-products-table tbody').on('click', 'tr td:not(:first-child)', function(e) {
                // Don't open modal if clicking delete button or actions column
                if ($(e.target).hasClass('delete-item') || 
                    $(e.target).closest('.delete-item').length ||
                    $(e.target).closest('button').length) {
                    return;
                }
                // Don't open modal if clicking quantity column (4th column, index 3)
                if ($(this).index() === 3) {
                    return;
                }
                if ($(e.target).hasClass('editable-qty') || 
                    $(e.target).closest('.editable-qty').length || 
                    $(e.target).hasClass('qty-input') ||
                    $(e.target).closest('.qty-input').length) {
                    return; // Don't open modal if clicking quantity
                }
                    
                    const row = $(this).closest('tr');
                    const id = row.find('.row-checkbox').val();

                    $.ajax({
                        url: "{{ route('admin.order.product.edit', ':id') }}".replace(':id', id),
                        type: 'GET',
                        success: function(response) {
                            if (response.status == true) {
                                editModalData = {
                                    id: id,
                                    orderProduct: response.orderProduct,
                                    product: response.product
                                };
                                $('#editProductModal').modal('show');
                            } else {
                                toastr.error('Failed to load product data');
                            }
                        },
                        error: function() {
                            toastr.error('Error loading product data');
                        }
                    });
                });
            }

            // Update quantity inline
            function updateQuantity(productId, newQty) {
                $.ajax({
                    url: "{{ route('admin.order.product.updateQty', ':id') }}".replace(':id', productId),
                    type: 'GET',
                    data: { qty: newQty },
                    success: function() {
                        toastr.success('Quantity updated successfully');
                        if (table) table.ajax.reload();
                    },
                    error: function() {
                        toastr.error('Error updating quantity');
                        if (table) table.ajax.reload();
                    }
                });
            }

            // Load products for brand
            function loadProductsForBrand(brandId, productSelectId, selectedProductId = null) {
                if (!brandId) {
                    $(productSelectId).html('<option value="">Select product</option>').trigger('change');
                    return;
                }

                var $select = $(productSelectId);
                $select.html('<option value="">Loading...</option>').trigger('change');

                $.ajax({
                    url: "{{ route('admin.order.products.by.brand') }}",
                    type: 'GET',
                    data: { brand_id: brandId },
                    success: function(response) {
                        if (!response.data || response.data.length === 0) {
                            $select.html('<option value="">No products found</option>').trigger('change');
                            return;
                        }

                        var options = ['<option value="">Select product</option>'];
                        response.data.forEach(function(product) {
                            var selected = (selectedProductId && product.id == selectedProductId) ? ' selected' : '';
                            var displayText = (product.product_code ? product.product_code + ' - ' : '') + product.title;
                            options.push('<option value="' + product.id + '"' + selected + '>' + displayText + '</option>');
                        });
                        
                        $select.html(options.join('')).trigger('change');
                    },
                    error: function() {
                        $select.html('<option value="">Error loading products</option>').trigger('change');
                    }
                });
            }

            // Brand change handlers
            $('#edit_brand_id').on('change', function() {
                if (isSettingBrandProgrammatically) return;
                var brandId = $(this).val();
                loadProductsForBrand(brandId, '#edit_product_id');
            });

            $('#add_brand_id').on('change', function() {
                var brandId = $(this).val();
                loadProductsForBrand(brandId, '#add_product_id');
            });

            // Modal handlers
            $('#addProductModal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({ dropdownParent: $('#addProductModal') });
            });

            $('#addProductModal').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $('#add_brand_id').val(null).trigger('change');
                $('#add_product_id').html('<option value="">Select product</option>').trigger('change');
            });

            $('#editProductModal').on('shown.bs.modal', function() {
                var modal = $(this);
                
                if ($('#edit_brand_id').hasClass('select2-hidden-accessible')) {
                    $('#edit_brand_id').select2('destroy');
                }
                if ($('#edit_product_id').hasClass('select2-hidden-accessible')) {
                    $('#edit_product_id').select2('destroy');
                }
                
                modal.find('.select2').select2({ dropdownParent: modal });

                if (editModalData) {
                    var orderProduct = editModalData.orderProduct;
                    var product = editModalData.product;

                    modal.find('[name="id"]').val(editModalData.id);
                    $('#edit_quantity').val(orderProduct.qty || '');
                    $('#edit_old_product_name').val(product && orderProduct.product_name && orderProduct.product_name != product.title ? orderProduct.product_name : '');

                    var brandId = (product && product.Brand && product.Brand.id) ? product.Brand.id : (orderProduct.category_id || null);

                    if (brandId) {
                        isSettingBrandProgrammatically = true;
                        $('#edit_brand_id').val(brandId).trigger('change');
                        setTimeout(function() {
                            isSettingBrandProgrammatically = false;
                        }, 100);
                        setTimeout(function() {
                            loadProductsForBrand(brandId, '#edit_product_id', orderProduct.product_id);
                        }, 200);
                    } else {
                        $('#edit_brand_id').val(null).trigger('change');
                        $('#edit_product_id').html('<option value="">Select product</option>').trigger('change');
                    }
                    
                    editModalData = null;
                }
            });

            $('#editProductModal').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $('#edit_brand_id').val(null).trigger('change');
                $('#edit_product_id').html('<option value="">Select product</option>').trigger('change');
                editModalData = null;
            });

            // Create product
            $('#create').click(function() {
                if (!orderId) {
                    toastr.error('Please save order info first');
                    return;
                }

                var brand_id = $('#add_brand_id').val();
                var product_id = $('#add_product_id').val();
                var quantity = $('#add_quantity').val();
                var old_product_name = $('#add_old_product_name').val();

                if (!brand_id) {
                    toastr.error('Please select brand');
                    return;
                }
                if (!product_id) {
                    toastr.error('Please select product');
                    return;
                }
                if (!quantity) {
                    toastr.error('Please enter quantity');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.order.product.create') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_id: orderId,
                        brand_id: brand_id,
                        product_id: product_id,
                        quantity: quantity,
                        category_title: $('#add_brand_id option:selected').text(),
                        old_product_name: old_product_name
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            $('#addProductModal').modal('hide');
                            if (table) table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });

            // Update product
            $('#update').click(function() {
                if (!orderId) {
                    toastr.error('Please save order info first');
                    return;
                }

                var id = $('#editProductModal [name="id"]').val();
                var brand_id = $('#edit_brand_id').val();
                var product_id = $('#edit_product_id').val();
                var quantity = $('#edit_quantity').val();
                var old_product_name = $('#edit_old_product_name').val();

                if (!brand_id) {
                    toastr.error('Please select brand');
                    return;
                }
                if (!product_id) {
                    toastr.error('Please select product');
                    return;
                }
                if (!quantity) {
                    toastr.error('Please enter quantity');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.order.product.update') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        brand_id: brand_id,
                        product_id: product_id,
                        quantity: quantity,
                        category_title: $('#edit_brand_id option:selected').text(),
                        old_product_name: old_product_name
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            $('#editProductModal').modal('hide');
                            if (table) table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });

            // Delete selected products
            $(document).on('click', '#delete-selected', function() {
                var ids = [];
                $('.row-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

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
                            url: '{{ route('admin.order.products.delete') }}',
                            method: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (table) table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Selected products have been deleted.',
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

            // Finalize Order (redirect to edit page)
            $('#finalizeOrder').on('click', function() {
                if (!orderId) {
                    toastr.error('Please save order info first');
                    return;
                }
                window.location.href = "{{ route('admin.order.edit', ':id') }}".replace(':id', orderId);
            });

            // Finalize Order and Close (redirect to list)
            $('#finalizeOrderAndClose').on('click', function() {
                if (!orderId) {
                    toastr.error('Please save order info first');
                    return;
                }
                window.location.href = "{{ route('admin.order.list') }}";
            });

            // Delete Order
            // Delete individual order product
            $(document).on('click', '.delete-item', function(e) {
                e.stopPropagation(); // Prevent row click
                if (!orderId) {
                    toastr.error('No order found');
                    return;
                }
                const id = $(this).data('id');
                const ids = [id];

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete this product from the order.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.order.products.delete') }}',
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
                                    text: 'Product has been deleted from the order.',
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

            $(document).on('click','#delete-order', function() {
                if (!orderId) {
                    toastr.error('No order to delete');
                    return;
                }

                const ids = [orderId];

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the order.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete!',
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Order has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('admin.order.list') }}";
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'Failed to delete order.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
