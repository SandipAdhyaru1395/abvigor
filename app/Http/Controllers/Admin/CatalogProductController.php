<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatalogProduct;
use App\Models\CatalogCategory;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\SystemFile;
use Illuminate\Support\Facades\Storage;


class CatalogProductController extends Controller
{

    public function list()
    {
        return view('admin.catalog.product.list');
    }


    public function getCatalogProducts(Request $request)
    {
        $query = CatalogProduct::select(['id', 'title'])->orderBy('id', 'desc');

        return datatables()->of($query)
            ->make(true);

    }

    public function add()
    {

        $catalog_categories = CatalogCategory::with('children')->orderBy('title', 'asc')->get();
       
        return view('admin.catalog.product.add', compact('catalog_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'brand_id' => 'required',
        ], [
            'title.required' => 'Title is required',
            'slug.required' => 'Slug is required',
            'brand_id.required' => 'Brand is required',
        ]);

        $catalog_product = CatalogProduct::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'category_id' => $request->brand_id,
            'technical_specification' => $request->technical_specification
        ]);

        if($request->has('product_image')) {
            $this->uploadImage($request->product_image, $catalog_product->id);
        }

        Toastr::success('Product created successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.catalog.product.list');
        } else {
            return redirect()->route('admin.catalog.product.add');
        }
    }

    public function deleteImage($catalog_product_id)
    {

        $file = SystemFile::where('attachment_id', $catalog_product_id)
            ->where('attachment_type', 'Chivalry\Catalog\Models\Product')
            ->where('field', 'product_image')->first();
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
    public function uploadImage($file, $catalog_product_id)
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
                'field' => 'product_image',
                'attachment_type' => 'Chivalry\Catalog\Models\Product',
                'attachment_id' => $catalog_product_id,
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

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        foreach ($ids as $id) {
            $this->deleteImage($id);
        }
        CatalogProduct::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $catalog_product = CatalogProduct::find($id);

        $catalog_categories = CatalogCategory::orderBy('title', 'asc')->get();

        return view('admin.catalog.product.edit', compact('catalog_product', 'catalog_categories'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'brand_id' => 'required',
        ], [
            'title.required' => 'Title is required',
            'slug.required' => 'Slug is required',
            'brand_id.required' => 'Brand is required',
        ]);

        $catalog_product = CatalogProduct::find($request->catalog_product_id);

        $catalog_product->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'category_id' => $request->brand_id,
            'technical_specification' => $request->technical_specification
        ]);

        if ($request->file_status == "uploaded") {
            $this->deleteImage($catalog_product->id);
            $this->uploadImage($request->product_image, $catalog_product->id);
        } else if ($request->file_status == "removed") {
            $this->deleteImage($catalog_product->id);
        }

        Toastr::success('Product updated successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.catalog.product.list');
        } else {
            return redirect()->route('admin.catalog.product.edit', $catalog_product->id);
        }
    }

    public function delete($catalogProductId)
    {
        CatalogProduct::find($catalogProductId)->delete();

        Toastr::success('Deleted successfully');
        return redirect()->route('admin.catalog.product.list');
    }
}
