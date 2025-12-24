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
                    <h5 class="modal-title" id="cartModalTitle">Cart Items</h5>
                </div>
                <div class="modal-body">
                    No products found
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" id="submitCartBtn" class="btn btn-sm bg-base text-white" style="display:none;">Submit</button>
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
</style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            localStorage.getItem('cartItems') ? localStorage.removeItem('cartItems') : '';

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

            $(document).on('click', '.btn-add', function() {

                var id = $(this).data('id');
                var product_code = $(this).data('product-code');
                var product = $(this).data('product');
                var quantity = $('input[name="quantity[' + product_code + ']"').val();

                if (quantity <= 0) {
                    toastr.error('Please enter quantity');
                    return;
                }
                // Get existing data from localStorage (or empty array if none)
                var cart = JSON.parse(localStorage.getItem('cartItems')) || [];

                // Check if product already exists in the array
                var existingIndex = cart.findIndex(item => item.product_code === product_code);

                if (existingIndex !== -1) {
                    // If exists, update quantity
                    cart[existingIndex].quantity = quantity;
                } else {
                    // If not exists, push new object
                    cart.push({
                        id: id,
                        product_code: product_code,
                        product: product,
                        quantity: quantity
                    });
                }

                // Save back to localStorage
                localStorage.setItem('cartItems', JSON.stringify(cart));

                toastr.success('Product added to cart');
            });

            $(document).on('click', '.btn-remove', function() {

                var product_code = $(this).data('product-code');

                // Get existing cart data from localStorage
                var cart = JSON.parse(localStorage.getItem('cartItems')) || [];

                // Filter out the item to remove
                cart = cart.filter(item => item.product_code !== product_code);

                // Save updated cart back to localStorage
                localStorage.setItem('cartItems', JSON.stringify(cart));

                toastr.success('Product removed from cart');

            });

            $('#cartModal').on('show.bs.modal', function(e) {
                
                var cart = JSON.parse(localStorage.getItem('cartItems')) || [];
                var modalBody = $(this).find('.modal-body');

                modalBody.empty();

                if (cart.length > 0) {
                    
                    modalBody.append('<table class="table" id="cartTable"><thead><tr><th>Code</th><th>Product</th><th>Quantity</th></tr></thead><tbody></tbody></table>');
                    
                    $('#cartTable tbody').empty();

                    cart.forEach(function(item) {
                        $('#cartTable tbody').append('<tr><td>' + item.product_code + '</td><td>' + item.product + '</td><td>' + item.quantity + '</td></tr>');
                    });

                    $('#submitCartBtn').show();
                }else{
                    modalBody.append('No products found');
                }
            });

            $('#submitCartBtn').on('click', function() {
                
                showLoader();

                var cart = JSON.parse(localStorage.getItem('cartItems')) || [];
                var formData = new FormData();

                formData.append('_token', '{{ csrf_token() }}');
                formData.append('cart', JSON.stringify(cart));

                $.ajax({
                    url: '{{ route('order.store') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == true) {
                            localStorage.removeItem('cartItems');
                            $('#cartModal').modal('hide');
                            toastr.success('Order submitted successfully');
                            setTimeout(function() {
                                location.reload();
                            },3000);
                        }else{
                            console.log(response.errors);
                            toastr.error('Something went wrong');
                        }
                    },
                    error: function(xhr, status, error) {
                         toastr.error('Something went wrong');
                        console.log(error);
                    },
                    complete: function() {
                        hideLoader();
                    }
                });
            });
        });
    </script>
@endpush
