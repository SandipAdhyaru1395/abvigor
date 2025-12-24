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
            border: 1px solid #dfe6e9;
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

        #order-products-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        #order-products-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
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
            background: #95a5a6;
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

            #order-products-table {
                font-size: 12px;
            }

            #order-products-table thead th,
            #order-products-table tbody td {
                padding: 8px 6px;
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
                            <i class="fas fa-edit page-title-icon"></i>
                            Edit Order
                        </h1>
                    </div>

                    <form class="mt-2" action="{{ route('admin.order.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $order->id }}">

                        <div class="mb-3">
                            <div class="form-section-title">Order Information</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="order_no" class="form-label align-self-end">Order No : <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="order_no" id="order_no"
                                    value="{{ $order->order_no }}" autocomplete="off">
                                @error('order_no')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="order_date" class="form-label align-self-end">Order Date : <span
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
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="customer_name" class="form-label align-self-end">Client : <span
                                        class="text-danger">*</span></label>
                                <select class="form-select select2" name="user_id" data-placeholder="Select Client"
                                    id="client" aria-label="Default select example">
                                    @if ($users)
                                        <option selected value="">Select client</option>
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
                            <div class="col-md-6">
                                <label for="brand" class="form-label align-self-end">Brand : <span
                                        class="text-danger">*</span></label>
                                <select class="form-select select2" name="brand_id" data-placeholder="Select Brand"
                                    id="brand" aria-label="Default select example">
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

                        <div class="table-responsive">
                            <table id="order-products-table" class="table mt-2 table-hover table-striped table-bordered">
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
                        </div>

                        <div class="row mt-4 align-items-center">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <div class="action-buttons">
                                    <button type="submit" class="btn btn-sm btn-primary text-white">Save</button>
                                    <input type="hidden" name="close" value="1" disabled>
                                    <button type="submit"
                                        onclick="$('input[name=close]').prop('disabled', false);"
                                        class="btn btn-sm btn-primary text-white">Save &amp; Close</button>
                                    <a href="{{ route('admin.order.list') }}" class="btn btn-sm btn-secondary text-white">
                                        Cancel
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-4 text-md-end">
                                <button type="button" id="delete-order" class="btn btn-sm btn-danger">Delete Order</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

            var table_top_left_html = `<div class="row">
                <div class="col d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm btn-secondary text-white" data-bs-toggle="modal"
                        data-bs-target="#addProductModal">Create Order Product List</button>
                    <button type="button" class="btn btn-sm btn-danger" id="delete-selected">Delete</button>
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
                        <"d-inline-flex gap-2 mb-2">
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
