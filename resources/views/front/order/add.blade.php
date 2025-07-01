@extends('front.partials.layout')

@section('content')
    <div class="front container py-2">
        @include('front.partials.sidebar')
        <div class="front main-content p-4 table-responsive">
            <div class="row">
                <div class="col text-end">
                    <a href="{{ route('order.list') }}"><button class="btn btn-sm btn-secondary text-white">Back</button></a>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col d-flex gap-5">
                    <label for="brand" class="form-label align-self-end fw-bold">Tractor Brand</label>
                    <select class="form-select w-50" id="brand" aria-label="Default select example">
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
            </div>
            <div class="row">
                <div class="col text-end">
                    <button class="btn btn-sm bg-base text-white" data-toggle="modal" data-target="#cartModal">Submit Order</button>
                </div>
            </div>
            <h5 id="brand_label" class="mb-3 text-base"></h5>
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
                    $('#brand_label').text($(this).find('option:selected').text());
                } else {
                    $('#brand_label').text('');
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
