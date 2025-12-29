@extends('front.partials.layout')

@section('content')
    <div class="front container my-5">
        @include('front.partials.sidebar')
        <div class="front main-content p-4">
            <div class="order-add-wrapper">
                <div class="add-header mb-4">
                    <h4 class="mb-0"><i class="fa fa-plus-circle"></i> Place New Order</h4>
                    <a href="{{ route('order.list') }}">
                        <button class="btn btn-back">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>
                    </a>
                </div>

                <!-- Brand Selection Card -->
                <div class="brand-selection-card mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label for="brand" class="form-label">
                                <i class="fa fa-truck"></i> Select Tractor Brand
                            </label>
                            <select class="form-select brand-select" id="brand" aria-label="Select brand">
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
                        <div class="col-md-6 text-end">
                            <button class="btn btn-submit-order" data-toggle="modal" data-target="#cartModal">
                                <i class="fa fa-check"></i> Submit Order
                            </button>
                        </div>
                    </div>
                    <div id="brand_label" class="brand-label mt-3"></div>
                </div>

                <!-- Products Table -->
                <div class="products-table-wrapper">
                    <div class="table-responsive">
                        <table id="products-table" class="table products-table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col" style="min-width: 120px;">Quantity</th>
                                    <th scope="col" style="min-width: 120px;">Actions</th>
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

    <!-- Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalTitle">
                        <i class="fa fa-shopping-cart"></i> Cart Items
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="cartModalBody">
                    <div class="empty-cart-message text-center py-5">
                        <i class="fa fa-shopping-cart" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                        <p class="text-muted">No products found in cart</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" id="clearCartBtn" style="display:none;">
                        <i class="fa fa-trash"></i> Clear All
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" id="submitCartBtn" class="btn btn-sm bg-base text-white" style="display:none;">
                        <i class="fa fa-check"></i> Submit Order
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .order-add-wrapper {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 24px 24px 20px;
        border-top: 4px solid #ed1c24;
    }

    .add-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .add-header h4 {
        color: #333;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .add-header h4 i {
        color: #ed1c24;
    }

    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(108, 117, 125, 0.4);
        color: #ffffff;
    }

    .brand-selection-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 25px;
        border-left: 4px solid #ed1c24;
        margin-bottom: 25px;
    }

    .brand-selection-card .form-label {
        color: #333;
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .brand-selection-card .form-label i {
        color: #ed1c24;
    }

    .brand-select {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .brand-select:focus {
        border-color: #ed1c24;
        box-shadow: 0 0 0 0.25rem rgba(237, 28, 36, 0.15);
        outline: none;
    }

    .brand-label {
        color: #ed1c24;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 10px 0;
        display: none;
    }

    .brand-label:not(:empty) {
        display: block;
    }

    .btn-submit-order {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(237, 28, 36, 0.3);
    }

    .btn-submit-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(237, 28, 36, 0.4);
        color: #ffffff;
    }

    .products-table-wrapper {
        margin-top: 40px;
        width: 100%;
    }

    /* Space between DataTables controls row and table */
    .dataTables_wrapper .row:first-of-type {
        margin-bottom: 25px !important;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    /* DataTables Page Length and Search Box Styling */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #dee2e6 !important;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #ed1c24 !important;
        box-shadow: 0 0 0 0.25rem rgba(237, 28, 36, 0.15) !important;
        outline: none;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        color: #495057;
    }

    .dataTables_wrapper .dataTables_filter input {
        margin-left: 10px;
        width: 250px;
    }

    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_filter input {
            width: 150px;
        }
    }

    .products-table {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100% !important;
    }

    /* Make DataTables full width */
    .dataTables_wrapper {
        width: 100% !important;
    }

    .dataTables_wrapper .dataTable {
        width: 100% !important;
        margin: 0 !important;
    }

    .dataTables_wrapper .dataTable thead th,
    .dataTables_wrapper .dataTable tbody td {
        width: auto;
    }

    .products-table thead {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
    }

    .products-table thead th {
        padding: 15px 20px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
        white-space: nowrap;
    }

    /* Quantity column width - same as Actions */
    .products-table thead th:nth-child(2),
    .products-table tbody td:nth-child(2) {
        width: 120px;
        min-width: 120px;
        max-width: 150px;
        text-align: center;
    }

    /* Actions column width */
    .products-table thead th:nth-child(3),
    .products-table tbody td:nth-child(3) {
        width: 120px;
        min-width: 120px;
        text-align: center;
    }

    .products-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .products-table tbody tr:hover {
        background-color: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .products-table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        color: #555;
        border: none;
    }

    .no-records {
        padding: 60px 20px !important;
        color: #999;
    }

    .no-records i {
        font-size: 3rem;
        display: block;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .no-records p {
        margin: 0;
        font-size: 1.1rem;
    }

    /* Action Buttons */
    .action-buttons-group {
        display: flex;
        gap: 6px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .btn-xs {
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 6px;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-xs i {
        font-size: 13px;
    }
    
    .btn-view-image {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    
    .btn-view-image:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
        color: white;
    }
    
    .btn-no-image {
        background: #6c757d;
        color: white;
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-add-cart {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .btn-add-cart:hover {
        background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
        color: white;
    }
    
    .btn-remove-cart {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    
    .btn-remove-cart:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        color: white;
    }
    
    .btn-xs:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .btn-xs:active {
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .order-add-wrapper {
            padding: 15px;
        }

        .add-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .add-header h4 {
            font-size: 1rem;
            flex-shrink: 1;
        }

        .btn-back {
            padding: 8px 16px;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .brand-selection-card {
            padding: 15px;
        }

        .brand-selection-card .row {
            flex-direction: column;
        }

        .brand-selection-card .col-md-6 {
            width: 100%;
        }

        .brand-selection-card .col-md-6.text-end {
            text-align: center !important;
            margin-top: 30px;
            padding-top: 20px;
        }

        .products-table thead th,
        .products-table tbody td {
            padding: 12px 10px;
            font-size: 0.85rem;
        }

        .action-buttons-group {
            gap: 4px;
        }
        
        .btn-xs {
            padding: 5px 8px;
            font-size: 11px;
            min-width: 28px;
            height: 28px;
        }
        
        .btn-xs i {
            font-size: 11px;
        }
    }
    
    @media (max-width: 576px) {
        .action-buttons-group {
            flex-direction: column;
            gap: 3px;
        }
        
        .btn-xs {
            width: 100%;
            min-width: auto;
        }
    }

    /* Cart Modal Styles */
    #cartModal.modal {
        z-index: 1060 !important;
    }

    #cartModal.modal.show {
        z-index: 1060 !important;
    }

    .modal-backdrop.show {
        z-index: 1055 !important;
    }

    #cartModal .modal-dialog {
        z-index: 1061 !important;
    }

    .modal-header {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
        border-bottom: none;
        padding: 20px 25px;
    }

    .modal-header .modal-title {
        color: #ffffff;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header .close {
        color: #ffffff;
        opacity: 0.9;
        text-shadow: none;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 25px;
    }

    .empty-cart-message {
        color: #999;
    }

    .cart-table {
        width: 100%;
        margin-bottom: 0;
    }

    .cart-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .cart-table thead th {
        padding: 12px 15px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .cart-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .cart-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .cart-table tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #555;
    }

    .cart-table tbody td:first-child {
        font-weight: 600;
        color: #ed1c24;
    }

    #cartTable .delete-item {
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

    #cartTable .delete-item:hover {
        background: rgba(220, 53, 69, 0.1) !important;
        border-color: rgba(220, 53, 69, 0.3) !important;
        color: #c82333 !important;
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
    }

    #cartTable .delete-item:active {
        transform: scale(0.95);
    }

    #cartTable .delete-item i {
        font-size: 16px;
        transition: transform 0.3s ease;
    }

    #cartTable .delete-item:hover i {
        transform: rotate(-10deg) scale(1.1);
    }

    .cart-quantity-input {
        border: 2px solid #dee2e6;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .cart-quantity-input:focus {
        border-color: #ed1c24;
        box-shadow: 0 0 0 0.25rem rgba(237, 28, 36, 0.15);
        outline: none;
    }

    .modal-footer {
        border-top: 2px solid #f0f0f0;
        padding: 20px 25px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .modal-footer {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .modal-footer .btn {
            width: 100%;
            margin: 0;
        }
    }

    .cart-quantity-badge {
        display: inline-block;
        background: #ed1c24;
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 8px;
    }
</style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('product.list') }}',
                    data: function(d) {
                        d.brand_id = $('#brand').val(); // send brand_id to backend
                    }
                },
                columns: [{
                        data: 'product_info',
                        name: 'product_info',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'quantity',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [],
            });

            $('#brand').change(function() {

                table.ajax.reload();

                if ($('#brand').val() != '') {
                    $('#products-table').show();
                    $('#brand_label').html('<i class="fa fa-truck"></i> ' + $(this).find('option:selected').text()).show();
                } else {
                    $('#brand_label').html('').hide();
                }

            });

            // Add product to cart
            $(document).on('click', '.btn-add', function() {
                var id = $(this).data('id');
                var product_code = $(this).data('product-code');
                var quantity = $('input[name="quantity[' + product_code + ']"').val();

                if (!quantity || quantity <= 0) {
                    toastr.error('Please enter quantity');
                    return;
                }

                showLoader();

                $.ajax({
                    url: '{{ route('cart.add') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: id,
                        quantity: quantity
                    },
                    success: function(response) {
                        hideLoader();
                        if (response.status) {
                            toastr.success(response.message);
                            // Reload table to show updated cart quantities
                            table.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Failed to add product to cart');
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        var errorMsg = 'Failed to add product to cart';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        toastr.error(errorMsg);
                    }
                });
            });

            // Remove product from cart (from product table)
            $(document).on('click', '.btn-remove', function() {
                var product_id = $(this).data('id');

                showLoader();

                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: product_id
                    },
                    success: function(response) {
                        hideLoader();
                        if (response.status) {
                            toastr.success(response.message);
                            // Reload table to show updated cart quantities
                            table.ajax.reload(null, false);
                            // Reload cart modal if it's open
                            if ($('#cartModal').hasClass('show')) {
                                renderCartModal();
                            }
                        } else {
                            toastr.error(response.message || 'Failed to remove product from cart');
                        }
                    },
                    error: function() {
                        hideLoader();
                        toastr.error('Failed to remove product from cart');
                    }
                });
            });

            // Function to render cart modal
            function renderCartModal() {
                $.ajax({
                    url: '{{ route('cart.get') }}',
                    type: 'GET',
                    success: function(response) {
                        var modalBody = $('#cartModalBody');
                        modalBody.empty();

                        if (response.status && response.cart && response.cart.length > 0) {
                            // Create cart table
                            var tableHtml = '<table class="table cart-table" id="cartTable">' +
                                '<thead>' +
                                '<tr>' +
                                '<th>Product Code</th>' +
                                '<th>Product Name</th>' +
                                '<th>Brand</th>' +
                                '<th>Quantity</th>' +
                                '<th>Action</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody></tbody>' +
                                '</table>';
                            
                            modalBody.append(tableHtml);
                            
                            $('#cartTable tbody').empty();

                            // Populate cart items
                            response.cart.forEach(function(item) {
                                var row = '<tr data-product-id="' + item.product_id + '">' +
                                    '<td><strong>' + (item.product_code || 'N/A') + '</strong></td>' +
                                    '<td>' + item.product_name + '</td>' +
                                    '<td>' + (item.brand_title || 'N/A') + '</td>' +
                                    '<td>' +
                                    '<input type="text" class="form-control cart-quantity-input" ' +
                                    'data-product-id="' + item.product_id + '" ' +
                                    'value="' + item.quantity + '" ' +
                                    'style="width: 80px; text-align: center; display: inline-block;" ' +
                                    'onkeypress="return /[0-9]/i.test(event.key)" />' +
                                    '</td>' +
                                    '<td>' +
                                    '<button type="button" class="btn btn-sm delete-item text-danger" data-product-id="' + item.product_id + '" title="Remove from cart">' +
                                    '<i class="fa fa-trash"></i>' +
                                    '</button>' +
                                    '</td>' +
                                    '</tr>';
                                $('#cartTable tbody').append(row);
                            });

                            // Show submit and clear buttons
                            $('#submitCartBtn').show();
                            $('#clearCartBtn').show();
                        } else {
                            // Show empty cart message
                            modalBody.append('<div class="empty-cart-message text-center py-5">' +
                                '<i class="fa fa-shopping-cart" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>' +
                                '<p class="text-muted">No products found in cart</p>' +
                                '</div>');
                            
                            $('#submitCartBtn').hide();
                            $('#clearCartBtn').hide();
                        }
                    },
                    error: function() {
                        $('#cartModalBody').html('<div class="empty-cart-message text-center py-5">' +
                            '<i class="fa fa-exclamation-triangle" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>' +
                            '<p class="text-muted">Failed to load cart</p>' +
                            '</div>');
                    }
                });
            }

            // Show cart modal
            $('#cartModal').on('show.bs.modal', function(e) {
                renderCartModal();
            });

            // Handle remove item from cart modal
            $(document).on('click', '#cartTable .delete-item', function(e) {
                e.stopPropagation();
                var product_id = $(this).data('product-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove this product from your cart.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoader();

                        $.ajax({
                            url: '{{ route('cart.remove') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                product_id: product_id
                            },
                            success: function(response) {
                                hideLoader();
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Removed!',
                                        text: 'Product has been removed from your cart.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    // Reload table to show updated cart quantities
                                    table.ajax.reload(null, false);
                                    renderCartModal();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to remove product from cart'
                                    });
                                }
                            },
                            error: function() {
                                hideLoader();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to remove product from cart'
                                });
                            }
                        });
                    }
                });
            });

            // Handle quantity update in cart modal
            $(document).on('blur', '.cart-quantity-input', function() {
                var product_id = $(this).data('product-id');
                var quantity = $(this).val();

                if (!quantity || quantity <= 0) {
                    toastr.error('Quantity must be greater than 0');
                    // Reload modal to reset value
                    renderCartModal();
                    return;
                }

                showLoader();

                $.ajax({
                    url: '{{ route('cart.add') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: product_id,
                        quantity: quantity
                    },
                    success: function(response) {
                        hideLoader();
                        if (response.status) {
                            toastr.success('Quantity updated successfully');
                            // Reload table to show updated cart quantities
                            table.ajax.reload(null, false);
                        } else {
                            toastr.error(response.message || 'Failed to update quantity');
                            // Reload modal to reset value
                            renderCartModal();
                        }
                    },
                    error: function() {
                        hideLoader();
                        toastr.error('Failed to update quantity');
                        // Reload modal to reset value
                        renderCartModal();
                    }
                });
            });

            // Handle Enter key in quantity input
            $(document).on('keypress', '.cart-quantity-input', function(e) {
                if (e.which === 13) {
                    $(this).blur();
                }
            });

            // Handle clear all cart items
            $('#clearCartBtn').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove all items from your cart.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, clear all!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoader();

                        $.ajax({
                            url: '{{ route('cart.clear') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                hideLoader();
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Cleared!',
                                        text: 'All items have been removed from your cart.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    // Reload table to show updated cart quantities
                                    table.ajax.reload(null, false);
                                    renderCartModal();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to clear cart'
                                    });
                                }
                            },
                            error: function() {
                                hideLoader();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to clear cart'
                                });
                            }
                        });
                    }
                });
            });

            // Submit order
            $('#submitCartBtn').on('click', function() {
                Swal.fire({
                    title: 'Submit Order?',
                    text: "Are you sure you want to submit this order?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ed1c24',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, submit order!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoader();

                        $.ajax({
                            url: '{{ route('order.store') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                hideLoader();
                                if (response.status == true) {
                                    $('#cartModal').modal('hide');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Order Submitted!',
                                        text: 'Your order has been submitted successfully.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Something went wrong'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                hideLoader();
                                var errorMsg = 'Something went wrong';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
