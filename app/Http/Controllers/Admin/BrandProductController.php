<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandProduct;
use App\Models\BrandCategory;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\SystemFile;
use Illuminate\Support\Facades\Storage;


class BrandProductController extends Controller
{

    public function list()
    {
        return view('admin.brand.product.list');
    }


    public function getBrandProducts(Request $request)
    {
        $query = BrandProduct::select(['id', 'title', 'product_code'])->orderBy('id', 'desc');

        return datatables()->of($query)
            // ->filter(function ($query) use ($request) {
            //     if ($search = $request->get('search')['value']) {
            //         $query->where(function ($q) use ($search) {
            //             $q->where('product_code', 'like', "%{$search}%")
            //                 ->orWhere('title', 'like', "%{$search}%");
            //         });
            //     }
            // })
            // ->addColumn('product_info', function ($row) {
            //     return '<a href="img1.jpg" data-fancybox="gallery" data-caption="Caption 1">
            //     ' . $row->product_code . ' ' . $row->title . '
            //     </a>

            //     <a href="img2.jpg" data-fancybox="gallery" data-caption="Caption 2" style="display:none;"></a>
            //     <a href="img3.jpg" data-fancybox="gallery" data-caption="Caption 3" style="display:none;"></a>';
            // })
            // ->addColumn('quantity', function ($row) {
            //     return '<input type="text" onkeypress="return /[0-9]/i.test(event.key)" class="form-control" name="quantity[' . $row->product_code . ']" autocomplete="off"/>';
            // })
            // ->addColumn('action', function ($row) {
            //     return '
            //     <div class="d-flex gap-2">
            //         <button class="btn-add btn btn-sm btn-primary text-white" data-id="' . $row->id . '" data-product="' . $row->title . '" data-product-code="' . $row->product_code . '">Add</button>
            //         <button class="btn-remove btn btn-sm bg-base text-white" data-id="' . $row->id . '" data-product="' . $row->title . '" data-product-code="' . $row->product_code . '">Remove</button>
            //     </div>
            //     ';
            // })
            // ->rawColumns(['product_info', 'quantity', 'action']) // allow HTML rendering for these columns
            ->make(true);

    }

    public function add()
    {

        $brand_categories = BrandCategory::orderBy('title', 'asc')->get();

        return view('admin.brand.product.add', compact('brand_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'product_code' => 'required',
            'brand_id' => 'required',
        ], [
            'title.required' => 'Title is required',
            'slug.required' => 'Slug is required',
            'product_code.required' => 'Product code is required',
            'brand_id.required' => 'Brand is required',
        ]);

        $brand_product = BrandProduct::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'product_code' => $request->product_code,
            'category_id' => $request->brand_id,
            'technical_specification' => $request->technical_specification
        ]);

        if ($request->has('product_image')) {
            $this->uploadImage($request->product_image, $brand_product->id);
        }

        Toastr::success('Product created successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.brand.product.list');
        } else {
            return redirect()->route('admin.brand.product.add');
        }
    }

    public function deleteImage($brand_product_id)
    {

        $file = SystemFile::where('attachment_id', $brand_product_id)
            ->where('attachment_type', 'Chivalry\Brand\Models\Product')
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
    public function uploadImage($file, $brand_product_id)
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
                'attachment_type' => 'Chivalry\Brand\Models\Product',
                'attachment_id' => $brand_product_id,
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
        BrandProduct::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $brand_product = BrandProduct::find($id);

        $brands = BrandCategory::orderBy('title', 'asc')->get();

        return view('admin.brand.product.edit', compact('brand_product', 'brands'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'product_code' => 'required',
            'brand_id' => 'required',
        ], [
            'title.required' => 'Title is required',
            'slug.required' => 'Slug is required',
            'product_code.required' => 'Product code is required',
            'brand_id.required' => 'Brand is required',
        ]);

        $brand_product = BrandProduct::find($request->brand_product_id);

        $brand_product->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'product_code' => $request->product_code,
            'category_id' => $request->brand_id,
            'technical_specification' => $request->technical_specification
        ]);

        if ($request->file_status == "uploaded") {
            $this->deleteImage($brand_product->id);
            $this->uploadImage($request->product_image, $brand_product->id);
        } else if ($request->file_status == "removed") {
            $this->deleteImage($brand_product->id);
        }

        Toastr::success('Product updated successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.brand.product.list');
        } else {
            return redirect()->route('admin.brand.product.edit', $brand_product->id);
        }
    }

    public function delete($brandProductId)
    {
        BrandProduct::find($brandProductId)->delete();

        Toastr::success('Deleted successfully');
        return redirect()->route('admin.brand.product.list');
    }
}
