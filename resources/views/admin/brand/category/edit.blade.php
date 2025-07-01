@extends('admin.partials.layout')
@push('styles')
<style>
     #cke_notifications_area_description{
            display: none;
        }
</style>
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <form class="mb-5" action="{{ route('admin.brand.category.update') }}" method="POST">
                @csrf
                <input type="hidden" name="brand_category_id" value="{{ $brand_category->id }}">
                <div class="row mb-3">
                    <div class="col-12 mt-3">
                        <label for="title" class="form-label align-self-end fw-bold">Title : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="title" value="{{ $brand_category->title }}"
                            autocomplete="off">
                        @error('title')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-12 mt-3">
                        <label for="description" class="form-label align-self-end fw-bold">Description : </label>
                        <textarea class="form-control" name="description" id="description">{{ $brand_category->short_description }}</textarea>

                        @error('description')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-12 mt-3">
                        <label for="slug" class="form-label align-self-end fw-bold">Slug : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="slug" id="slug" value="{{ $brand_category->slug }}"
                            autocomplete="off">
                        @error('slug')
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
                        <a href="{{ route('admin.brand.category.list') }}"><button type="button"
                                class="btn btn-sm bg-danger text-white">Cancel</button></a>
                    </div>
                    @if(!$brand_category->deleted_at)
                        <div class="col text-end">
                            <a href="{{ route('admin.brand.category.delete', $brand_category->id) }}"><button type="button" id="delete-brand-category" class="btn btn-sm btn-danger">Delete Category</button></a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
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
