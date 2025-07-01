@extends('admin.partials.layout')
@push('styles')
    <style>
        input.form-control {
            border-radius: 0.3rem;
            padding: 0.4rem 0.6rem;
            font-size: 0.875rem;
            /* small but readable */
            font-family: 'Segoe UI', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .table-hover tbody tr:hover {
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content">
            <form class="mt-5" action="{{ route('admin.order.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $order->id }}">
                <div class="row mb-3">
                    <div class="col">
                        <label for="order_no" class="form-label align-self-end fw-bold">Order No : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="order_no" id="order_no"
                            value="{{ $order->order_no }}" autocomplete="off">
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
                            value="{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}" readonly
                            autocomplete="off">
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
                                <option value="{{ $user->id }}" @selected($user->id == $order->user_id)>{{ $user->name }}
                                </option>
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
                                <option value="{{ $brand->id }}" @selected($brand->id == $order->category_id)>{{ $brand->title }}
                                </option>
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


                <table id="order-products-table" class="table mt-3 table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><input type="checkbox" id="select-all"></th>
                            <th scope="col">Part No</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Main Product Name</th>
                            <th scope="col">Product Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
                <div class="row mt-5">
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-primary text-white">Save</button>
                        <input type="hidden" name="close" value="1" disabled>
                        <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                        class="btn btn-sm btn-primary text-white">Save & Close</button>
                        <a href="{{ route('admin.order.list') }}">
                            <button type="button"
                            class="btn btn-sm bg-danger text-white">Cancel</button>
                        </a>
                    </div>
                   
                    <div class="col text-end">
                            <button type="button" id="delete-order" class="btn btn-sm btn-danger">Delete Order</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                            <label>Product : <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="edit_product_id" data-placeholder="Select Product"
                                id="edit_product_id" aria-label="Default select example">
                                @if ($products)
                                    <option selected value="">Select product</option>
                                @endif
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->product_code }} - {{ $product->title }}
                                    </option>
                                @empty
                                    <option value="">No product found</option>
                                @endforelse
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
                            <label>Product : <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="add_product_id" data-placeholder="Select Product"
                                id="add_product_id" aria-label="Default select example">
                                @if ($products)
                                    <option selected value="">Select product</option>
                                @endif
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->product_code }} - {{ $product->title }}
                                    </option>
                                @empty
                                    <option value="">No product found</option>
                                @endforelse
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            var table_top_left_html = `<div class="row mt-5">
                <div class="col">
                    <button type="button" class="btn btn-sm btn-secondary text-white" data-bs-toggle="modal"
                        data-bs-target="#addProductModal">Create Order Product List</button>
                    <button type="button" class="btn btn-sm bg-base text-white" id="delete-selected">Delete</button>
                </div>
            </div>`;


            $('#select-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            $('#product').select2({
                dropdownParent: $('#editProductModal') // 👈 Important!
            });

            var table = $('#order-products-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.order.product.list') }}',
                    data: function(d) {
                        d.order_id = "{{ $order->id }}"; // send brand_id to backend
                    }
                },
                dom: `
                    <"row mb-3"
                        <"col-md-6 align-content-end table-top-left">
                        <"col-md-6 text-end"
                        <"d-inline-flex gap-2 mb-2 mt-5">
                        f
                        >
                    >
                    rt
                    <"row mt-2"
                        <"col-md-6"i>
                        <"col-md-6 d-flex justify-content-end"p>
                    >
                    `,
                // buttons: [{
                //         extend: 'copyHtml5',
                //         className: 'btn btn-sm',
                //         exportOptions: {
                //             columns: ':visible:not(:first-child)'
                //         }
                //     },
                //     {
                //         extend: 'excelHtml5',
                //         className: 'btn btn-sm',
                //         exportOptions: {
                //             columns: ':visible:not(:first-child)'
                //         }
                //     },
                //     {
                //         extend: 'csvHtml5',
                //         className: 'btn btn-sm',
                //         exportOptions: {
                //             columns: ':visible:not(:first-child)'
                //         }
                //     },
                //     {
                //         extend: 'pdfHtml5',
                //         className: 'btn btn-sm',
                //         exportOptions: {
                //             columns: ':visible:not(:first-child)'
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         className: 'btn btn-sm',
                //         exportOptions: {
                //             columns: ':visible:not(:first-child)'
                //         }
                //     }
                // ],
                columns: [{
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        },
                    },
                    {
                        data: 'part_no',
                        width: '15%',

                    },
                    {
                        data: 'qty',
                    },
                    {
                        data: 'main_product_name',
                    },
                    {
                        data: 'product_name',
                    }

                ],
                initComplete: function() {
                    $('.table-top-left').prepend(table_top_left_html);
                }
            });

            $('#order-products-table tbody').on('click', 'tr td:not(:first-child)', function() {

                const row = $(this).closest('tr');
                const id = row.find('.row-checkbox').val();

                $.ajax({
                    url: "{{ route('admin.order.product.edit', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        if (response.status == true) {
                            var orderProduct = response.orderProduct;
                            var product = response.product;

                            $('#editProductModal').find('[name="id"]').val(id);

                            $('#editProductModal').find('select[name="edit_product_id"]').val(
                                orderProduct.product_id).trigger('change');
                            $('#editProductModal').find('[name="edit_quantity"]').val(
                                orderProduct
                                .qty);

                            if (orderProduct.product_name != product.title) {
                                $('#edit_old_product_name').val(orderProduct.product_name);
                            }
                        }
                    }
                });

                $('#editProductModal').modal('show');
            });

            $('#editProductModal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $('#editProductModal')
                });
            });

            $('#addProductModal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $('#addProductModal')
                });
            });

            $('#addProductModal').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('.select2').val(null).trigger('change');
            });

            $('#editProductModal').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('.select2').val(null).trigger('change');
            });

            $('#create').click(function() {
                var product_id = $('#add_product_id').val();
                var quantity = $('#add_quantity').val();
                var old_product_name = $('#add_old_product_name').val();

                if (product_id == "" || product_id == null) {
                    toastr.error('Please select product');
                    return false;
                }
                if (quantity == "" || quantity == null) {
                    toastr.error('Please enter quantity');
                    return false;
                }

                $.ajax({
                    url: "{{ route('admin.order.product.create') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_id: "{{ $order->id }}",
                        product_id: product_id,
                        quantity: quantity,
                        old_product_name: old_product_name,
                    },
                    success: function(response) {
                        if (response.status == true) {
                            $('#addProductModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Something went wrong');
                        console.log(error);
                    }
                });
            });

            $('#update').click(function() {

                var id = $('#editProductModal').find('[name="id"]').val();
                var order_product_id = $('#order_product_id').val();
                var product_id = $('#edit_product_id').val();
                var quantity = $('#edit_quantity').val();
                var old_product_name = $('#edit_old_product_name').val();

                if (product_id == "" || product_id == null) {
                    toastr.error('Please select product');
                    return false;
                }
                if (quantity == "" || quantity == null) {
                    toastr.error('Please enter quantity');
                    return false;
                }

                $.ajax({
                    url: "{{ route('admin.order.product.update') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_product_id: id,
                        product_id: product_id,
                        quantity: quantity,
                        old_product_name: old_product_name,
                    },
                    success: function(response) {
                        if (response.status == true) {
                            $('#editProductModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Something went wrong');
                        console.log(error);
                    }
                });
            });

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
                    text: "This will delete the selected products.",
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

            $(document).on('click','#delete-order', function() {
                
                const ids = ["{{ $order->id }}"];

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
                                });
                                setTimeout(function() {
                                    window.location.href = "{{ route('admin.order.list') }}";
                                },2000);
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

            
        });

        function getSelectedIds() {
            let ids = [];
            $('.row-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }
    </script>
@endpush
