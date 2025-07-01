@extends('admin.partials.layout')
@push('styles')
<style>
    #cke_notifications_area_description {
        display: none;
    }
</style>
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <form class="mb-5" action="{{ route('admin.catalog.category.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="catalog_category_id" value="{{ $catalog_category->id }}">
                <div class="row mb-3">
                    <div class="col-lg-6 mt-3">
                        <label for="title" class="form-label align-self-end fw-bold">Title : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="title" value="{{ $catalog_category->title }}"
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
                        <input type="text" class="form-control" name="slug" id="slug" value="{{ $catalog_category->slug }}"
                            autocomplete="off">
                        @error('slug')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label class="form-label fw-bold">Image upload:</label>
                        <div id="drop-area-image-upload" class="rounded p-4 text-center" style="cursor:pointer; border: 2px dashed #0d6efd;">
                            <p class="mb-2">Drag & drop an image or click to select</p>
                            @php
                                $imageUrl = $catalog_category->ImageUrl
                                    ? $catalog_category->ImageUrl
                                    : null;

                                $bannerUrl = $catalog_category->BannerUrl
                                    ? $catalog_category->BannerUrl
                                    : null;
                            @endphp
                            <input type="file" id="image_upload_input" name="image_upload_input" accept="image/*" hidden>
                            <input type="text" name="image_upload_input_status" id="image_upload_input_status" hidden value="">
                            <img id="image-upload-preview" src="{{ $imageUrl ?? '#' }}" alt="Preview" class="img-fluid mt-3 {{ $imageUrl ? '' : 'd-none' }}"
                                style="max-height: 200px;">
                            <p id="image-upload-info" class="text-muted mt-2 {{ $imageUrl ? '' : 'd-none' }}"></p>
                            <button type="button" id="remove-image-btn"
                                class="btn btn-sm btn-danger mt-2 {{ $imageUrl ? '' : 'd-none' }}">Remove</button>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label class="form-label fw-bold">Banner:</label>
                        <div id="drop-area-banner" class="rounded p-4 text-center" style="cursor:pointer; border: 2px dashed #0d6efd;">
                            <p class="mb-2">Drag & drop an image or click to select</p>
                            <input type="file" id="banner_upload_input" name="banner_upload_input" accept="image/*" hidden>
                            <input type="text" name="banner_upload_input_status" id="banner_upload_input_status" hidden value="">
                            <img id="banner-preview" src="{{ $bannerUrl ?? '#' }}" alt="Preview" class="img-fluid mt-3 {{ $bannerUrl ? '' : 'd-none' }}"
                                style="max-height: 200px;">
                            <p id="banner-info" class="text-muted mt-2 {{ $bannerUrl ? '' : 'd-none' }}"></p>
                            <button type="button" id="remove-banner-btn"
                                class="btn btn-sm btn-danger mt-2 {{ $bannerUrl ? '' : 'd-none' }}">Remove</button>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="description" class="form-label align-self-end fw-bold">Description : </label>
                        <textarea class="form-control" name="description" id="description">{{ $catalog_category->short_description }}</textarea>

                        @error('description')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    
                </div>
                <div class="row mt-5" style="bottom: 0;">
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-primary text-white">Save</button>
                        <input type="hidden" name="close" value="1" disabled>
                        <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                            class="btn btn-sm btn-primary text-white">Save & Close</button>
                        <a href="{{ route('admin.catalog.category.list') }}"><button type="button"
                                class="btn btn-sm bg-danger text-white">Cancel</button></a>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('admin.catalog.category.delete', $catalog_category->id) }}"><button type="button" id="delete-catalog-category" class="btn btn-sm btn-danger">Delete Category</button></a>
                    </div>
                </div>
            </form>
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

                $('#image_upload_input_status').val('uploaded');
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

            $('#image_upload_input_status').val('removed');
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

                $('#banner_upload_input_status').val('uploaded');
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

            $('#banner_upload_input_status').val('removed');
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
