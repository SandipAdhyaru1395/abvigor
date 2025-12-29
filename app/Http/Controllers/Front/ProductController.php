<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Models\BrandProduct;
use App\Models\BrandCategory;
use App\Models\Cart;
use App\Models\CartItem;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $query = BrandProduct::where('category_id', $request->brand_id)
            ->select(['id','title', 'product_code'])
            ->orderBy('display_order', 'asc')
            ->orderBy('id', 'asc');
        
        // Get user's cart items
        $cart = null;
        $cartItems = [];
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::user()->id)->first();
            if ($cart) {
                $cartItems = CartItem::where('cart_id', $cart->id)
                    ->pluck('quantity', 'product_id')
                    ->toArray();
            }
        }

        return datatables()->of($query)
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('product_code', 'like', "%{$search}%")
                            ->orWhere('title', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('product_info', function ($row) {
                return '<span>'.$row->product_code.' '.$row->title.'</span>';
            })
            ->addColumn('quantity', function ($row) use ($cartItems) {
                $cartQuantity = isset($cartItems[$row->id]) ? $cartItems[$row->id] : '';
                return '<input type="text" onkeypress="return /[0-9]/i.test(event.key)" class="form-control" name="quantity[' . $row->product_code . ']" value="' . $cartQuantity . '" autocomplete="off"/>';
            })
            ->addColumn('action', function ($row) {
                $imageUrl = $row->ImageUrl ?? '#';
                $hasImage = $imageUrl && $imageUrl != '#';
                
                $viewImageBtn = $hasImage 
                    ? '<a href="' . $imageUrl . '" class="btn btn-xs btn-view-image" data-fancybox="gallery" data-caption="' . $row->product_code . ' - ' . $row->title . '" title="View Image">
                        <i class="fa fa-image"></i>
                    </a>'
                    : '<button class="btn btn-xs btn-no-image" disabled title="No Image Available">
                        <i class="fa fa-image"></i>
                    </button>';
                
                return '
                <div class="action-buttons-group">
                    ' . $viewImageBtn . '
                    <button class="btn-add btn btn-xs btn-add-cart" data-id="' . $row->id . '" data-product="' . $row->title . '" data-product-code="' . $row->product_code . '" title="Add to Cart">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button class="btn-remove btn btn-xs btn-remove-cart" data-id="' . $row->id . '" data-product="' . $row->title . '" data-product-code="' . $row->product_code . '" title="Remove from Cart">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns(['product_info','quantity', 'action']) // allow HTML rendering for these columns
            ->make(true);

    }


}
