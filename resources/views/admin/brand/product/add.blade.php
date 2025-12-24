@extends('admin.partials.layout')
@push('styles')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet">
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

        .upload-area,
        #drop-area {
            cursor: pointer;
            border: 2px dashed #667eea;
            border-radius: 10px;
            background: #f8f9ff;
            transition: border-color 0.2s, background-color 0.2s;
        }

        #drop-area.bg-hover,
        .upload-area.bg-hover {
            background-color: #eef0ff;
            border-color: #764ba2;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
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

        #cke_notifications_area_technical_specification {
            display: none;
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
                            <i class="fas fa-box-open page-title-icon"></i>
                            Create Brand Product
                        </h1>
                    </div>

                    <form class="mb-3" id="product-form" action="{{ route('admin.brand.product.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-6 mt-3">
                                <label for="title" class="form-label align-self-end">Title : <span
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
                                <label for="slug" class="form-label align-self-end">Slug : <span
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
                                <label for="product_code" class="form-label align-self-end">Product Code : <span
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
                                <label for="brand" class="form-label align-self-end">Brand : <span
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
                                <label class="form-label">Product Image:</label>
                                <div id="drop-area" class="upload-area rounded p-4 text-center">
                                    <p class="mb-2">Drag &amp; drop an image or click to select</p>
                                    <input type="file" id="product_image_input" name="product_image" accept="image/*" hidden>
                                    <img id="preview" src="#" alt="Preview" class="img-fluid mt-3 d-none"
                                        style="max-height: 200px;">
                                    <p id="file-info" class="text-muted mt-2 d-none"></p>
                                    <button type="button" id="remove-preview"
                                        class="btn btn-sm btn-danger mt-2 d-none">Remove</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="technical_specification" class="form-label align-self-end">Technical
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
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <input type="hidden" name="close" value="1" disabled>
                            <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                                class="btn btn-primary">Create &amp; Close</button>
                            <a href="{{ route('admin.brand.product.list') }}">
                                <button type="button" class="btn btn-secondary">Cancel</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
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