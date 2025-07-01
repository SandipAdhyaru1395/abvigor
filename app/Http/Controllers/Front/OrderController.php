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
        $order = Order::whereId($id)->where('user_id', Auth::user()->id)->first();
       
        if (empty($order)) {

            Toastr::error('Order not found');
            
            return redirect()->route('order.list');
        }
        // dd($order->products[0]->product);
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

            $input = $request->only('cart');
            $cart = json_decode($input['cart'], true); // decode as associative array

            $validator = Validator::make(['cart' => $cart], [
                'cart.*.id' => 'required',
                'cart.*.quantity' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()]);
            }

            $lastOrder = Order::latest('id')->first();
            $generatedOrderNo = $lastOrder ? $lastOrder->order_no + 1 : 1;

            $user = Auth::user();

            // Step 1: Organize cart items by category
            $grouped = collect($cart)->groupBy(function ($item) {
                $product = BrandProduct::find($item['id']);
                return $product->category_id;
            });

            // Store all created orders for mailing
            $createdOrders = [];

            // Step 2: Loop through each category group
            foreach ($grouped as $categoryId => $items) {

                $firstProduct = BrandProduct::find($items[0]['id']); // sample product for category info

                // Create one order per category
                $order = Order::create([
                    'order_no' => $generatedOrderNo++,
                    'email' => $user->email,
                    'user_id' => $user->id,
                    'category_id' => $categoryId,
                    'brand_name' => $firstProduct->brand->title,
                    'order_date' => now(),
                ]);

                $orderItems = [];

                // Add products to that order
                foreach ($items as $item) {
                    $product = BrandProduct::find($item['id']);

                    $orderItem = $order->products()->create([
                        'product_id' => $product->id,
                        'order_number' => $order->order_no,
                        'user_email' => $user->email,
                        'qty' => $item['quantity'],
                        'category_title' => $product->brand->title,
                        'product_name' => $product->title,
                    ]);

                    $orderItems[] = $orderItem;
                }

                // Store for email
                $createdOrders[] = [
                    'order' => $order,
                    'items' => $orderItems
                ];
            }

            // Send mail for each created order
            foreach ($createdOrders as $data) {
                $mailService->sendOrderEmails($user, $data['order'], $data['items']);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Order placed successfully.']);

        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }

    }
}
