<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandCategory;
use Yajra\DataTables\DataTables;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

class BrandCategoryController extends Controller
{
    public function list(){
        return view('admin.brand.category.list');
    }

    public function getBrandCategories(){

        $brand_categories = BrandCategory::orderBy('id', 'desc')->get();

        return DataTables::of($brand_categories)
            ->make(true);
    }

    public function add(){
        return view('admin.brand.category.add');
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

        BrandCategory::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'short_description' => $request->description,
        ]);

        Toastr::success('Category created successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.brand.category.list');
        } else {
            return redirect()->route('admin.brand.category.add');
        }
    }

     public function edit($brandCategoryId)
    {
        $brand_category = BrandCategory::find($brandCategoryId);
        return view('admin.brand.category.edit', compact('brand_category'));
    }

    public function update(Request $request)
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

        BrandCategory::find($request->brand_category_id)->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'short_description' => $request->description,
        ]);

        Toastr::success('Category updated successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.brand.category.list');
        } else {
            return redirect()->route('admin.brand.category.edit',$request->brand_category_id);
        }
    }

    public function delete($brandCategoryId)
    {
        BrandCategory::find($brandCategoryId)->delete();

        Toastr::success('Deleted successfully');
        return redirect()->route('admin.brand.category.list');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        BrandCategory::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }
        
}
