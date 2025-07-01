@extends('admin.partials.layout')
@push('styles')
    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <table id="catalog-catgories-table" class="table table-hover table-responsive table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="select-all"></th>
                        <th scope="col">TITLE</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
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

            var table_top_left_html = `<div class="row mb-4">
                <div class="col">
                    <a href="{{ route('admin.catalog.category.add') }}"><button class="btn btn-sm bg-primary text-white">Create</button></a>
                    <button id="delete-selected" class="btn btn-danger btn-sm bg-base">Delete Selected</button>
                </div>
            </div>`;

            $('#select-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            var table = $('#catalog-catgories-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.get.catalog.categories') }}',
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
                        data: 'title',
                    }
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
                        text: 'Please select at least one category to delete.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the selected categories.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.catalog.categories.delete') }}',
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
                                    text: 'Selected categories have been deleted.',
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

            $('#catalog-catgories-table tbody').on('click', 'tr td:not(:first-child)', function() {
                const row = $(this).closest('tr');
                const id = row.find('.row-checkbox').val();
                window.location.href = '{{ route('admin.catalog.category.edit', ':id') }}'.replace(':id', id);
            });

            // table.column(1).visible(false);
        });
    </script>
@endpush
