<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use Yajra\DataTables\DataTables;
use App\Models\BrandCategory;
use App\Models\BrandProduct;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function list(Request $request)
    {

        return view('admin.order.list');
    }

    public function getOrders(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'order_no',
            2 => 'order_date',
            3 => 'users.name',   // client/user name
            4 => 'chivalry_brand_category.title', // brand title
            5 => 'created_at',
        ];
        // Start query with joins for sorting on related columns
        $query = Order::with(['user', 'brand'])
            ->leftJoin('users', 'rudra_order_order.user_id', '=', 'users.id')
            ->leftJoin('chivalry_brand_category', 'rudra_order_order.category_id', '=', 'chivalry_brand_category.id')
            ->select('rudra_order_order.*'); // select only order columns to avoid ambiguity

        $search = $request->input('search.value');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('chivalry_brand_category.title', 'like', "%{$search}%");
            });
        }
        $orderColumnIndex = $request->input('order.0.column'); // e.g. "1"
        $orderDirection = $request->input('order.0.dir');      // "asc" or "desc"

        
        // Apply order
        $query->orderBy($columns[$orderColumnIndex], $orderDirection);

        // Use DataTables to process the result
        return DataTables::of($query)
            ->addColumn('order_no', fn($row) => $row->order_no)
            ->addColumn('order_date', fn($row) => \Carbon\Carbon::parse($row->order_date)->format('d/m/Y'))
            ->addColumn('client', fn($row) => $row->user->name ?? '-')
            ->addColumn('brand', fn($row) => $row->brand->title ?? '-')
            ->addColumn('created_at', fn($row) => Carbon::parse($row->created_at)->format('D, M d, Y g:i A'))
            ->make(true);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        OrderBrandProduct::whereIn('order_id', $ids)->delete();
        Order::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $order = Order::with(['products.product'])->find($id);

        $orderedProductIds = $order->products->pluck('product_id')->toArray();

        $products = $order->brand->products()
            ->whereNotIn('id', $orderedProductIds)
            ->get();

        $brands = BrandCategory::orderBy('title', 'asc')->get();

        $users = User::orderBy('name', 'asc')->get();

        return view('admin.order.edit', compact('users', 'order', 'brands', 'products'));
    }

    public function removeOrderProduct($orderProductId)
    {
        OrderBrandProduct::find($orderProductId)->delete();

        Toastr::success('Product removed successfully');
        return redirect()->back();
    }

    public function addOrderProduct(Request $request)
    {

        $input = $request->all();

        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ], [
            'product_id.required' => 'Please select product',
            'quantity.required' => 'Please enter quantity',
        ]);


        $order = OrderBrandProduct::create([
            'order_id' => $input['order_id'],
            'product_id' => $input['product_id'],
            'order_no' => $input['order_no'],
            'user_email' => $input['user_email'],
            'qty' => $input['quantity'],
            'category_title' => $input['category_title'],
            'product_name' => BrandProduct::find($input['product_id'])->title
        ]);

        Toastr::success('Product added successfully');
        return redirect()->back();
    }

    public function updteOrderProductQty($orderProductId, Request $request)
    {

        $input = $request->all();
        OrderBrandProduct::find($orderProductId)->update(['qty' => $input['qty']]);

        Toastr::success('Product quantity updated successfully');
        return redirect()->back();
    }

    public function update(Request $request)
    {

        $request->validate([
            'order_no' => 'required|unique:rudra_order_order,order_no,' . $request->id,
            'order_date' => 'required',
            'user_id' => 'required',
            'brand_id' => 'required',
        ], [
            'order_no.required' => 'Please enter order number',
            'order_no.unique' => 'Order number already exists',
            'order_date.required' => 'Please enter order date',
            'user_id.required' => 'Please select client',
            'brand_id.required' => 'Please select brand',
        ]);

        $input = $request->all();

        Order::find($request->id)->update([
            'order_no' => $input['order_no'],
            'order_date' => Carbon::createFromFormat('d/m/Y', $input['order_date'])->format('Y-m-d'),
            'user_id' => $input['user_id'],
            'brand_name' => BrandCategory::find($input['brand_id'])->title,
            'email' => User::find($input['user_id'])->email,
            'category_id' => $input['brand_id'],
        ]);

        Toastr::success('Order updated successfully');

        return redirect()->route('admin.order.list');
    }

    public function add()
    {

        $brands = BrandCategory::orderBy('title', 'asc')->get();

        $users = User::orderBy('name', 'asc')->get();

        return view('admin.order.add',compact('users', 'brands'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->except('_token');
            $cart = json_decode($input['cart'], true); // decode as associative array
            $input['cart'] = $cart;
            $validator = Validator::make($input, [
                'order_no' => 'required|unique:rudra_order_order',
                'order_date' => 'required',
                'user_id' => 'required',
                'cart.*.id' => 'required',
                'cart.*.quantity' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()]);
            }

            $user = User::find($input['user_id']);

            foreach ($cart as $item) {

                $product = BrandProduct::find($items[0]['id']); // sample product for category info

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
