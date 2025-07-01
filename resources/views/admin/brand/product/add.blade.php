@extends('admin.partials.layout')
@push('styles')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet">
    <style>
        #drop-area.bg-hover {
            background-color: #f8f9fa;
            border-color: #007bff;
        }
        #cke_notifications_area_technical_specification{
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <form class="mb-5" id="product-form" action="{{ route('admin.brand.product.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-6 mt-3">
                        <label for="title" class="form-label align-self-end fw-bold">Title : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}"
                            autocomplete="off">
                        @error('title')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="slug" class="form-label align-self-end fw-bold">Slug : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug') }}"
                            autocomplete="off">
                        @error('slug')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="product_code" class="form-label align-self-end fw-bold">Product Code : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="product_code" id="product_code"
                            value="{{ old('product_code') }}" autocomplete="off">
                        @error('product_code')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="brand" class="form-label align-self-end fw-bold">Brand : <span
                                class="text-danger">*</span></label>
                        <select class="form-select select2" name="brand_id" data-placeholder="Select Brand" id="brand"
                            aria-label="Default select example">
                            @if ($brand_categories)
                                <option selected value="">Select brand</option>
                            @endif
                            @forelse ($brand_categories as $brand)
                                <option value="{{ $brand->id }}" @selected($brand->id == old('brand_id'))>{{ $brand->title }}
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
                    <div class="col-12 mt-3">
                        <label class="form-label fw-bold">Product Image:</label>
                        <div id="drop-area" class="rounded p-4 text-center" style="cursor:pointer; border: 2px dashed #0d6efd;">
                            <p class="mb-2">Drag & drop an image or click to select</p>
                            <input type="file" id="product_image_input" name="product_image" accept="image/*" hidden>
                            <img id="preview" src="#" alt="Preview" class="img-fluid mt-3 d-none"
                                style="max-height: 200px;">
                            <p id="file-info" class="text-muted mt-2 d-none"></p>
                            <button type="button" id="remove-preview"
                                class="btn btn-sm btn-danger mt-2 d-none">Remove</button>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="technical_specification" class="form-label align-self-end fw-bold">Technical
                            Specification : </label>
                        <textarea class="form-control" name="technical_specification"
                            id="technical_specification">{{ old('technical_specification') }}</textarea>

                        @error('technical_specification')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
                <div class="row mt-5" style="bottom: 0;">
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-primary text-white">Create</button>
                        <input type="hidden" name="close" value="1" disabled>
                        <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                            class="btn btn-sm btn-primary text-white">Create & Close</button>
                        <a href="{{ route('admin.brand.product.list') }}"><button type="button"
                                class="btn btn-sm bg-danger text-white">Cancel</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('product_image_input');
        const preview = document.getElementById('preview');
        const fileInfo = document.getElementById('file-info');
        const removeBtn = document.getElementById('remove-preview');

        // Allow clicking on the drop area
        dropArea.addEventListener('click', () => fileInput.click());

        // Drag-and-drop support
        dropArea.addEventListener('dragover', e => {
            e.preventDefault();
            dropArea.classList.add('bg-hover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('bg-hover');
        });

        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            dropArea.classList.remove('bg-hover');
            const file = e.dataTransfer.files[0];
            fileInput.files = e.dataTransfer.files;
            showPreview(file);
        });

        // File input change
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            showPreview(file);
        });

        // Preview function
        function showPreview(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');

                    const sizeKB = (file.size / 1024).toFixed(1);
                    fileInfo.textContent = `${file.name} (${sizeKB} KB)`;
                    fileInfo.classList.remove('d-none');

                    removeBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove/reset everything
        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.value = '';
            preview.src = '#';
            preview.classList.add('d-none');
            fileInfo.classList.add('d-none');
            removeBtn.classList.add('d-none');
        });
    </script>


    <script>
        const pond = FilePond.create(document.querySelector('#productImage'), {
            allowMultiple: false,
            instantUpload: false, // ✅ This prevents automatic upload
        });

    </script>

    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.env.isCompatible = true; // Optional, prevents extra checks
        CKEDITOR.replace('technical_specification');

        $(document).ready(function () {
            $('#title').on('input', function () {
                var slug_value = slugify($(this).val());
                $('#slug').val(slug_value);
            });
        });
        function slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/[^\w\-]+/g, '') // Remove all non-word chars
                .replace(/\-\-+/g, '-'); // Replace multiple - with single -
        }

    </script>
    @endpush