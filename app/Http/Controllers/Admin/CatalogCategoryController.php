<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatalogCategory;
use Yajra\DataTables\DataTables;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\SystemFile;

class CatalogCategoryController extends Controller
{
    public function list(){
        return view('admin.catalog.category.list');
    }

    public function getCatalogCategories(){
        
        $catalog_categories = CatalogCategory::orderBy('id', 'desc')->get();

        return DataTables::of($catalog_categories)
            ->make(true);
    }

    public function add(){
        return view('admin.catalog.category.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'slug' => 'required',
        ], [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'slug.required' => 'Slug is required',
        ]);

        $catalog_category=CatalogCategory::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'short_description' => $request->description,
        ]);

        if($request->has('image_upload_input') && $request->hasFile('image_upload_input')) {
            $this->uploadImage($request->file('image_upload_input'), $catalog_category->id);
        }
        if($request->has('banner_upload_input') && $request->hasFile('banner_upload_input')) {
            $this->uploadBanner($request->file('banner_upload_input'), $catalog_category->id);
        }
        Toastr::success('Category created successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.catalog.category.list');
        } else {
            return redirect()->route('admin.catalog.category.add');
        }
    }

     public function edit($catalogCategoryId)
    {
        $catalog_category = CatalogCategory::find($catalogCategoryId);
        return view('admin.catalog.category.edit', compact('catalog_category'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
         $request->validate([
            'title' => 'required',
            'description' => 'required',
            'slug' => 'required',
        ], [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'slug.required' => 'Slug is required',
        ]);

        $catalog_category = CatalogCategory::find($request->catalog_category_id);
        
        $catalog_category->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'short_description' => $request->description,
        ]);

        if ($request->image_upload_input_status == "uploaded") {
            $this->deleteImage($catalog_category->id);
            $this->uploadImage($request->image_upload_input, $catalog_category->id);
        } else if ($request->image_upload_input_status == "removed") {
            $this->deleteImage($catalog_category->id);
        }
        if ($request->banner_upload_input_status == "uploaded") {
            $this->deleteBanner($catalog_category->id);
            $this->uploadBanner($request->banner_upload_input, $catalog_category->id);
        } else if ($request->banner_upload_input_status == "removed") {
            $this->deleteBanner($catalog_category->id);
        }

        Toastr::success('Category updated successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.catalog.category.list');
        } else {
            return redirect()->route('admin.catalog.category.edit',$request->catalog_category_id);
        }
    }

    public function deleteImage($catalog_category_id)
    {

        $file = SystemFile::where('attachment_id', $catalog_category_id)
            ->where('attachment_type', 'Chivalry\Catalog\Models\Category')
            ->where('field', 'image')->first();
        if (!$file) {
            return null;
        }

        $path = substr($file->disk_name, 0, 3) . '/' .
            substr($file->disk_name, 3, 3) . '/' .
            substr($file->disk_name, 6, 3) . '/' .
            $file->disk_name;

        Storage::disk('uploads')->delete($path);
        $file->delete();
    }

    public function deleteBanner($catalog_category_id)
    {

        $file = SystemFile::where('attachment_id', $catalog_category_id)
            ->where('attachment_type', 'Chivalry\Catalog\Models\Category')
            ->where('field', 'banner_image')->first();
        if (!$file) {
            return null;
        }

        $path = substr($file->disk_name, 0, 3) . '/' .
            substr($file->disk_name, 3, 3) . '/' .
            substr($file->disk_name, 6, 3) . '/' .
            $file->disk_name;

        Storage::disk('uploads')->delete($path);
        $file->delete();
    }

    public function delete($catalogCategoryId)
    {
        CatalogCategory::find($catalogCategoryId)->delete();

        Toastr::success('Deleted successfully');
        return redirect()->route('admin.catalog.category.list');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        CatalogCategory::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }
        
    public function uploadImage($file, $catalog_category_id)
    {
        try {
            $hash = $this->generateDiskName($file->getClientOriginalExtension());
            $folder1 = substr($hash, 0, 3);
            $folder2 = substr($hash, 3, 3);
            $folder3 = substr($hash, 6, 3);

            $folderPath = "$folder1/$folder2/$folder3";
            $filename = $hash . '.' . $file->getClientOriginalExtension();

            // Use Storage facade to store the file in 'uploads' disk
            $disk = Storage::disk('uploads');
            $path = $disk->putFileAs($folderPath, $file, $filename);

            // Collect file metadata
            $now = Carbon::now();
            $title = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();

            // Insert into system_files
            $systemFile = new SystemFile([
                'disk_name' => $filename,
                'file_name' => $title,
                'file_size' => $size,
                'content_type' => $mime,
                'field' => 'image',
                'attachment_type' => 'Chivalry\Catalog\Models\Category',
                'attachment_id' => $catalog_category_id,
                'is_public' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $systemFile->save();

            // Now use its own ID as sort_order
            $systemFile->sort_order = $systemFile->id;
            $systemFile->save();


            // Generate public URL (assumes local storage driver with 'public' visibility)
            return asset('storage/app/uploads/public/' . $path);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function uploadBanner($file, $catalog_category_id)
    {
        try {
            $hash = $this->generateDiskName($file->getClientOriginalExtension());
            $folder1 = substr($hash, 0, 3);
            $folder2 = substr($hash, 3, 3);
            $folder3 = substr($hash, 6, 3);

            $folderPath = "$folder1/$folder2/$folder3";
            $filename = $hash . '.' . $file->getClientOriginalExtension();

            // Use Storage facade to store the file in 'uploads' disk
            $disk = Storage::disk('uploads');
            $path = $disk->putFileAs($folderPath, $file, $filename);

            // Collect file metadata
            $now = Carbon::now();
            $title = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();

            // Insert into system_files
            $systemFile = new SystemFile([
                'disk_name' => $filename,
                'file_name' => $title,
                'file_size' => $size,
                'content_type' => $mime,
                'field' => 'banner_image',
                'attachment_type' => 'Chivalry\Catalog\Models\Category',
                'attachment_id' => $catalog_category_id,
                'is_public' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $systemFile->save();

            // Now use its own ID as sort_order
            $systemFile->sort_order = $systemFile->id;
            $systemFile->save();


            // Generate public URL (assumes local storage driver with 'public' visibility)
            return asset('storage/app/uploads/public/' . $path);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
     function generateDiskName($extension)
    {
        $timePart = dechex(time()); // timestamp in hex, like '65489034'
        $randomPart = bin2hex(random_bytes(7)); // 14 characters, like 'df16d853341197'

        return $timePart . $randomPart;
    }
}
