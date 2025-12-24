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

    .orders-card label.form-label {
        font-weight: 600;
        color: #2c3e50;
    }

    .orders-card .form-control {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.9rem;
        font-family: 'Segoe UI', sans-serif;
        border: 1px solid #dfe6e9;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
    }

    .orders-card .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
        background-color: #ffffff;
    }

    .orders-card .error-text {
        font-size: 0.8rem;
    }

    .upload-area {
        cursor: pointer;
        border: 2px dashed #667eea;
        border-radius: 10px;
        background: #f8f9ff;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .upload-area:hover {
        border-color: #764ba2;
        background: #eef0ff;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-start;
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

    #cke_notifications_area_description {
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
                            <i class="fas fa-tag page-title-icon"></i>
                            Create Catalog Category
                        </h1>
                    </div>

                    <form class="mb-3" action="{{ route('admin.catalog.category.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label class="form-label">Image upload:</label>
                                <div id="drop-area-image-upload" class="upload-area rounded p-4 text-center">
                                    <p class="mb-2">Drag &amp; drop an image or click to select</p>
                                    <input type="file" id="image_upload_input" name="image_upload_input" accept="image/*" hidden>
                                    <img id="image-upload-preview" src="#" alt="Preview" class="img-fluid mt-3 d-none"
                                        style="max-height: 200px;">
                                    <p id="image-upload-info" class="text-muted mt-2 d-none"></p>
                                    <button type="button" id="remove-image-btn"
                                        class="btn btn-sm btn-danger mt-2 d-none">Remove</button>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-3">
                                <label class="form-label">Banner:</label>
                                <div id="drop-area-banner" class="upload-area rounded p-4 text-center">
                                    <p class="mb-2">Drag &amp; drop an image or click to select</p>
                                    <input type="file" id="banner_upload_input" name="banner_upload_input" accept="image/*" hidden>
                                    <img id="banner-preview" src="#" alt="Preview" class="img-fluid mt-3 d-none"
                                        style="max-height: 200px;">
                                    <p id="banner-info" class="text-muted mt-2 d-none"></p>
                                    <button type="button" id="remove-banner-btn"
                                        class="btn btn-sm btn-danger mt-2 d-none">Remove</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="description" class="form-label align-self-end">Description : </label>
                                <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>

                                @error('description')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div class="action-buttons">
                                    <button type="submit" class="btn btn-sm btn-primary text-white">Create</button>
                                    <input type="hidden" name="close" value="1" disabled>
                                    <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                                        class="btn btn-sm btn-primary text-white">Create &amp; Close</button>
                                    <a href="{{ route('admin.catalog.category.list') }}"
                                        class="btn btn-sm btn-secondary text-white">Cancel</a>
                                </div>
                            </div>
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
        const imageUploadArea = document.getElementById('drop-area-image-upload');
        const imageUploadInput = document.getElementById('image_upload_input');
        const imageUploadPreview = document.getElementById('image-upload-preview');
        const imageUploadInfo = document.getElementById('image-upload-info');
        const removeImageBtn = document.getElementById('remove-image-btn');

        // Allow clicking on the drop area
        imageUploadArea.addEventListener('click', () => imageUploadInput.click());

        // Drag-and-drop support
        imageUploadArea.addEventListener('dragover', e => {
            e.preventDefault();
            imageUploadArea.classList.add('bg-hover');
        });

        imageUploadArea.addEventListener('dragleave', () => {
            imageUploadArea.classList.remove('bg-hover');
        });

        imageUploadArea.addEventListener('drop', e => {
            e.preventDefault();
            imageUploadArea.classList.remove('bg-hover');
            const file = e.dataTransfer.files[0];
            imageUploadInput.files = e.dataTransfer.files;
            showImagePreview(file);
        });

        // File input change
        imageUploadInput.addEventListener('change', () => {
            const file = imageUploadInput.files[0];
            showImagePreview(file);
        });

        // Preview function
        function showImagePreview(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imageUploadPreview.src = e.target.result;
                    imageUploadPreview.classList.remove('d-none');

                    const sizeKB = (file.size / 1024).toFixed(1);
                    imageUploadInfo.textContent = `${file.name} (${sizeKB} KB)`;
                    imageUploadInfo.classList.remove('d-none');
                    removeImageBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove/reset everything
        removeImageBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            imageUploadInput.value = '';
            imageUploadPreview.src = '#';
            imageUploadPreview.classList.add('d-none');
            imageUploadInfo.classList.add('d-none');
            removeImageBtn.classList.add('d-none');
        });

        const pond = FilePond.create(document.querySelector('#productImage'), {
            allowMultiple: false,
            instantUpload: false, // ✅ This prevents automatic upload
        });

    </script>

    <script>
        const bannerUploadArea = document.getElementById('drop-area-banner');
        const bannerUploadInput = document.getElementById('banner_upload_input');
        const bannerUploadPreview = document.getElementById('banner-preview');
        const bannerInfo = document.getElementById('banner-info');
        const removeBannerBtn = document.getElementById('remove-banner-btn');

        // Allow clicking on the drop area
        bannerUploadArea.addEventListener('click', () => bannerUploadInput.click());

        // Drag-and-drop support
        bannerUploadArea.addEventListener('dragover', e => {
            e.preventDefault();
            bannerUploadArea.classList.add('bg-hover');
        });

        bannerUploadArea.addEventListener('dragleave', () => {
            bannerUploadArea.classList.remove('bg-hover');
        });

        bannerUploadArea.addEventListener('drop', e => {
            e.preventDefault();
            bannerUploadArea.classList.remove('bg-hover');
            const file = e.dataTransfer.files[0];
            bannerUploadInput.files = e.dataTransfer.files;
            showPreview(file);
        });

        // File input change
        bannerUploadInput.addEventListener('change', () => {
            const file = bannerUploadInput.files[0];
            showPreview(file);
        });

        // Preview function
        function showPreview(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    bannerUploadPreview.src = e.target.result;
                    bannerUploadPreview.classList.remove('d-none');

                    const sizeKB = (file.size / 1024).toFixed(1);
                    bannerInfo.textContent = `${file.name} (${sizeKB} KB)`;
                    bannerInfo.classList.remove('d-none');

                    removeBannerBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove/reset everything
        removeBannerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            bannerUploadInput.value = '';
            bannerUploadPreview.src = '#';
            bannerUploadPreview.classList.add('d-none');
            bannerInfo.classList.add('d-none');
            removeBannerBtn.classList.add('d-none');
        });

        const pond1 = FilePond.create(document.querySelector('#productImage1'), {
            allowMultiple: false,
            instantUpload: false, // ✅ This prevents automatic upload
        });

    </script>

    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.env.isCompatible = true; // Optional, prevents extra checks
        CKEDITOR.replace('description');

        $(document).ready(function() {
            $('#title').on('input', function() {
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
