@extends('admin.partials.layout')

@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <form class="mb-5" action="{{ route('admin.order.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col text-end">
                        <button type="button" class="btn btn-sm bg-base text-white" data-bs-toggle="modal" data-bs-target="#cartModal">Submit
                            Order</button>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="order_no" class="form-label align-self-end fw-bold">Order No : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="order_no" id="order_no"
                            value="{{ old('order_no') }}" autocomplete="off">
                        @error('order_no')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="order_date" class="form-label align-self-end fw-bold">Order Date : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="order_date" id="order_date"
                            value="{{ old('order_date') }}" readonly autocomplete="off">
                        @error('order_date')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col">
                        <label for="customer_name" class="form-label align-self-end fw-bold">Client : <span
                                class="text-danger">*</span></label>
                        <select class="form-select select2" name="user_id" data-placeholder="Select Client" id="client"
                            aria-label="Default select example">
                            @if ($users)
                                <option selected value="">Select brand</option>
                            @endif
                            @forelse ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @empty
                                <option value="">No client found</option>
                            @endforelse
                        </select>
                        @error('user_id')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="brand" class="form-label align-self-end fw-bold">Brand : <span
                                class="text-danger">*</span></label>
                        <select class="form-select select2" name="brand_id" data-placeholder="Select Brand" id="brand"
                            aria-label="Default select example">
                            @if ($brands)
                                <option selected value="">Select brand</option>
                            @endif
                            @forelse ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                            @empty
                                <option value="">No brand found</option>
                            @endforelse
                        </select>
                        @error('brand_id')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- <div class="row mb-5">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div> --}}
            </form>
            <table id="products-table" class="table">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center">No Records Found</td>
                    </tr>
                </tbody>
            </table>
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
                    <div class="row">
                        <div class="col">
                            <label>Order No : <span id="cart_order_no"></span></label>
                        </div>
                        <div class="col">
                            <label>Order Date : <span id="cart_order_date"></span></label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label>Client : <span id="cart_client_name"></span></label>
                        </div>
                        <div class="col">
                            <label>Brand : <span id="cart_brand_name"></span></label>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col">
                            <h5>Products</h5>
                            <div class="modal-table">
                                No products found
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitCartBtn" class="btn btn-sm bg-base text-white"
                        style="display:none;">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            localStorage.getItem('cartItems') ? localStorage.removeItem('cartItems') : '';

            var table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.brand.product.list') }}',
                    data: function(d) {
                        d.brand_id = $('#brand').val(); // send brand_id to backend
                    }
                },
                columns: [{
                        data: 'product_info',
                        name: 'product_info',
                        orderable: false,
                        searchable: false,
                        width: '70%'
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
                var modalBody = $(this).find('.modal-body .modal-table');

                modalBody.empty();

                if (cart.length > 0) {

                    modalBody.append(
                        '<table class="table" id="cartTable"><thead><tr><th>Code</th><th>Product</th><th>Quantity</th></tr></thead><tbody></tbody></table>'
                        );

                    $('#cartTable tbody').empty();

                    cart.forEach(function(item) {
                        $('#cartTable tbody').append('<tr><td>' + item.product_code + '</td><td>' +
                            item.product + '</td><td>' + item.quantity + '</td></tr>');
                    });

                    $('#submitCartBtn').show();
                } else {
                    modalBody.append('No products found');
                }

                var order_no = $('#order_no').val();
                var order_date = $('#order_date').val();
                var client_name = $('#client option:selected').text();
                var brand_name = $('#brand option:selected').text();


                if(order_no == '' || order_date == '' || client_name == '' || brand_name == ''){
                    $('#submitCartBtn').hide();
                }

                $('#cart_order_no').text(order_no);
                $('#cart_order_date').text(order_date);
                $('#cart_client_name').text(client_name);
                $('#cart_brand_name').text(brand_name);
            });

            $('#submitCartBtn').on('click', function() {

                showLoader();

                var cart = JSON.parse(localStorage.getItem('cartItems')) || [];
                var formData = new FormData();

                formData.append('_token', '{{ csrf_token() }}');
                formData.append('cart', JSON.stringify(cart));
                formData.append('order_no', $('#order_no').val());
                formData.append('order_date', $('#order_date').val());
                formData.append('user_id', $('#client').val());
                formData.append('brand_id', $('#brand').val());

                $.ajax({
                    url: '{{ route('admin.order.store') }}',
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
                            }, 3000);
                        } else {

                            var errors = response.errors;
                            
                            $.each(errors, function(key, value) {
                                if ($('[name="' + key + '"]').length) {
                                    $('[name="' +key+'"]').parent().find('.text-danger').remove();
                                    $('[name="' +key+'"]').parent().append('<span class="text-danger">' + value + '</span>');
                                }else{
                                    toastr.error(value);
                                }
                            });
                              
                            $('#cartModal').modal('hide');
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
