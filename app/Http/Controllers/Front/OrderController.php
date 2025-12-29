<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\BrandCategory;
use App\Models\BrandProduct;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function list()
    {

        $orders = Order::where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')->paginate(10);

        return view('front.order.list', compact('orders'));
    }

    public function get($id)
    {
        $order = Order::with(['products.product.Brand'])->whereId($id)->where('user_id', Auth::user()->id)->first();
       
        if (empty($order)) {

            Toastr::error('Order not found');
            
            return redirect()->route('order.list');
        }
        return view('front.order.view', compact('order'));

    }

    public function add()
    {

        $brands = BrandCategory::orderBy('title', 'asc')->get();

        return view('front.order.add', compact('brands'));
    }

    // public function getProducts(Request $request)
    // {
    //     $query = BrandProduct::with('brand')->select(['id', 'name', 'price', 'brand_id', 'created_at']);

    //     if ($request->has('brand_id') && $request->brand_id != '') {
    //         $query->where('brand_id', $request->brand_id);
    //     }

    //     return datatables()->of($query)
    //         ->addColumn('brand', fn($row) => $row->brand->name ?? 'N/A')
    //         ->make(true);
    // }

    public function store(Request $request, MailService $mailService)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json(['status' => false, 'message' => 'Cart is empty']);
            }

            $cartItems = CartItem::with(['product.Brand'])
                ->where('cart_id', $cart->id)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Cart is empty']);
            }

            $lastOrder = Order::latest('id')->first();
            $generatedOrderNo = $lastOrder ? $lastOrder->order_no + 1 : 1;

            // Create a single order for all items
            $order = Order::create([
                'order_no' => $generatedOrderNo,
                'email' => $user->email,
                'user_id' => $user->id,
                'order_date' => now(),
            ]);

            // Add all products to the single order
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                $order->products()->create([
                    'product_id' => $product->id,
                    'order_number' => $order->order_no,
                    'user_email' => $user->email,
                    'qty' => $cartItem->quantity,
                    'category_id' => $product->category_id ?? null,
                    'category_title' => $product->Brand ? $product->Brand->title : '',
                    'product_name' => $product->title,
                ]);
            }

            // Clear cart after order is placed
            CartItem::where('cart_id', $cart->id)->delete();

            // Load relationships for email
            $order->load('products.product.Brand');

            // Send email for the single order
            $mailService->sendOrderEmails($user, $order, $order->products);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Order placed successfully.']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
