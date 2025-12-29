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
            4 => 'created_at',
        ];
        // Start query with joins for sorting on related columns
        $query = Order::with(['user'])
            ->leftJoin('users', 'rudra_order_order.user_id', '=', 'users.id')
            ->select('rudra_order_order.*'); // select only order columns to avoid ambiguity

        $search = $request->input('search.value');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        $orderColumnIndex = ($request->input('order.0.column')) ? $request->input('order.0.column') : 0;

        
        $orderDirection = ($request->input('order.0.dir') && $request->input('order.0.column') != 0) ? $request->input('order.0.dir') : 'desc';

        
        $query->orderBy($columns[$orderColumnIndex], $orderDirection);


        // Use DataTables to process the result
        return DataTables::of($query)
            ->addColumn('order_no', fn($row) => $row->order_no)
            ->addColumn('order_date', fn($row) => \Carbon\Carbon::parse($row->order_date)->format('d/m/Y'))
            ->addColumn('client', fn($row) => $row->user->name ?? '-')
            ->addColumn('created_at', fn($row) => Carbon::parse($row->created_at)->format('D, M d, Y g:i A'))
            ->make(true);
    }

    public function deleteMultipleOrders(Request $request)
    {
        $ids = $request->input('ids', []);

        OrderProduct::whereIn('order_id', $ids)->delete();
        Order::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

     public function deleteMultipleProducts(Request $request)
    {
        $ids = $request->input('ids', []);
        OrderProduct::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $order = Order::with(['products.product.Brand'])->find($id);

        $users = User::orderBy('name', 'asc')->get();

        $brands = BrandCategory::orderBy('title', 'asc')->get();

        return view('admin.order.edit', compact('users', 'order', 'brands'));
    }

     public function editProduct($id)
    {
        $orderProduct = OrderProduct::with([
            'product' => function($query) {
                $query->select('id', 'category_id', 'title', 'product_code');
            },
            'product.Brand' => function($query) {
                $query->select('id', 'title');
            }
        ])->select('id', 'order_id', 'product_id', 'qty', 'category_id', 'product_name')
          ->find($id);

        if (!$orderProduct) {
            return response()->json(['status' => false, 'message' => 'Order product not found']);
        }

        // Return only necessary data - optimized for performance
        return response()->json([
            'status' => true, 
            'orderProduct' => [
                'id' => $orderProduct->id,
                'product_id' => $orderProduct->product_id,
                'qty' => $orderProduct->qty,
                'category_id' => $orderProduct->category_id,
                'product_name' => $orderProduct->product_name,
            ],
            'product' => $orderProduct->product ? [
                'id' => $orderProduct->product->id,
                'title' => $orderProduct->product->title,
                'product_code' => $orderProduct->product->product_code,
                'category_id' => $orderProduct->product->category_id,
                'Brand' => $orderProduct->product->Brand ? [
                    'id' => $orderProduct->product->Brand->id,
                    'title' => $orderProduct->product->Brand->title,
                ] : null
            ] : null
        ]);
    }

    public function removeOrderProduct($orderProductId)
    {
        OrderProduct::find($orderProductId)->delete();

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


        $product = BrandProduct::with('Brand')->find($input['product_id']);
        
        $order = OrderProduct::create([
            'order_id' => $input['order_id'],
            'product_id' => $input['product_id'],
            'order_no' => $input['order_no'],
            'user_email' => $input['user_email'],
            'qty' => $input['quantity'],
            'category_id' => $product->category_id ?? null,
            'category_title' => $input['category_title'],
            'product_name' => $product->title
        ]);

        Toastr::success('Product added successfully');
        return redirect()->back();
    }

    public function updteOrderProductQty($orderProductId, Request $request)
    {
        $input = $request->all();
        
        $orderProduct = OrderProduct::find($orderProductId);
        if (!$orderProduct) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => 'Product not found']);
            }
            Toastr::error('Product not found');
            return redirect()->back();
        }

        $orderProduct->update(['qty' => $input['qty']]);

        if ($request->ajax()) {
            return response()->json(['status' => true, 'message' => 'Product quantity updated successfully']);
        }

        Toastr::success('Product quantity updated successfully');
        return redirect()->back();
    }

    public function update(Request $request)
    {

        $request->validate([
            'order_date' => 'required',
            'user_id' => 'required',
        ], [
            'order_date.required' => 'Please enter order date',
            'user_id.required' => 'Please select client',
        ]);

        $input = $request->all();

        Order::find($request->id)->update([
            'order_date' => Carbon::createFromFormat('d/m/Y', $input['order_date'])->format('Y-m-d'),
            'user_id' => $input['user_id'],
            'email' => User::find($input['user_id'])->email,
        ]);

        Toastr::success('Order updated successfully');

        if(isset($input['close']) && $input['close'] == 1){
            return redirect()->route('admin.order.list');
        }else{
            return redirect()->back();
        }
    }

    public function add()
    {
        $brands = BrandCategory::orderBy('title', 'asc')->get();
        // Users are now loaded via AJAX, so we don't need to pass them here
        // But keep for backward compatibility if needed
        $users = collect([]);
        
        // Generate smart order number
        $generatedOrderNo = $this->generateOrderNumber();

        return view('admin.order.add',compact('users', 'brands', 'generatedOrderNo'));
    }

    /**
     * Search users for Select2 AJAX
     */
    public function searchUsers(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $id = $request->input('id'); // For loading specific user
        $perPage = 20;

        $query = User::orderBy('name', 'asc');

        // If ID is provided, return that specific user (for initial selection)
        if ($id) {
            $user = User::find($id);
            if ($user) {
                return response()->json([
                    'results' => [[
                        'id' => $user->id,
                        'text' => $user->name . ' (' . $user->email . ')'
                    ]],
                    'pagination' => ['more' => false]
                ]);
            }
        }

        // Search by name or email
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->skip(($page - 1) * $perPage)->take($perPage)->get(['id', 'name', 'email']);

        $results = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->name . ' (' . $user->email . ')'
            ];
        });

        $total = $query->count();
        $hasMore = ($page * $perPage) < $total;

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $hasMore
            ]
        ]);
    }

    /**
     * Generate smart order number (sequential number)
     */
    private function generateOrderNumber()
    {
        // Get the last order number
        $lastOrder = Order::orderBy('id', 'desc')->first();
        
        if ($lastOrder) {
            // Try to extract numeric part from last order number
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastOrder->order_no);
            // If no number found, start from 1
            $newNumber = $lastNumber > 0 ? $lastNumber + 1 : 1;
        } else {
            $newNumber = 1;
        }
        
        // Format with leading zeros (4 digits)
        return str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get next order number via AJAX
     */
    public function getNextOrderNumber()
    {
        return response()->json([
            'status' => true,
            'order_no' => $this->generateOrderNumber()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate( [
            'order_date' => 'required',
            'user_id' => 'required',
        ],[
            'order_date.required' => 'Please select order date',
            'user_id.required' => 'Please select client',
        ]);
    
        $input = $request->except('_token');
        $user = User::find($input['user_id']);

        // Auto-generate order number if not provided
        $orderNo = $request->order_no ?? $this->generateOrderNumber();
        
        // Ensure order number is unique
        while (Order::where('order_no', $orderNo)->exists()) {
            $orderNo = $this->generateOrderNumber();
        }

        $order = Order::create([
            'order_no' => $orderNo,
            'email' => $user->email,
            'user_id' => $user->id,
            'order_date' => Carbon::createFromFormat('d/m/Y', $input['order_date'])->format('Y-m-d'),
        ]);
        
        // If create_only flag is set, return JSON response for AJAX
        if ($request->has('create_only') && $request->create_only == true) {
            return response()->json([
                'status' => true,
                'order_id' => $order->id,
                'message' => 'Order created successfully'
            ]);
        }
        
        Toastr::success('Order added successfully');

        if($request->has('close') && $request->close == 1){
            return redirect()->route('admin.order.list');
        }else{
            return redirect()->route('admin.order.edit', $order->id);
        }
    }

    public function createOrderProduct(Request $request){
       
        $validator=Validator::make($request->all(), [
          'order_id' => 'required',
          'product_id' => 'required',
          'quantity' => 'required',
          
       ], [
          'order_id.required' => 'Order is required',
          'product_id.required' => 'Product is required',
          'quantity.required' => 'Quantity is required',
       ]);

       if($validator->fails()){
          return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
       }

        $order = Order::find($request->order_id);
        $product = BrandProduct::with('Brand')->find($request->product_id);

        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found']);
        }

        OrderProduct::updateOrCreate([
                'order_id' => $request->order_id, 'product_id' => $request->product_id,
                'order_number' => $order->order_no, 'user_email' => $order->email
        ], [
                'category_id' => $product->category_id ?? null,
                'product_name'=> $request->old_product_name ?? $product->title,
                'qty' => $request->quantity
        ]);       
       return response()->json(['status' => true, 'message' => 'Product added successfully']);
    }

    public function updateOrderProduct(Request $request){
       
        $validator=Validator::make($request->all(), [
          'product_id' => 'required',
          'quantity' => 'required',
          
       ], [
          'product_id.required' => 'Product is required',
          'quantity.required' => 'Quantity is required',
       ]);

       if($validator->fails()){
          return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
       }

        $order = Order::find($request->order_id);
        $product = BrandProduct::with('Brand')->find($request->product_id);

        OrderProduct::find($request->order_product_id)->update([
                'product_id' => $request->product_id,
                'category_id' => $product->category_id ?? null,
                'product_name'=> $request->old_product_name,
                'qty' => $request->quantity
        ]);       
       return response()->json(['status' => true, 'message' => 'Product updated successfully']);
    }

    public function getOrderProducts(Request $request){

        $orderProducts = OrderProduct::where('order_id', $request->order_id)
            ->with([
                'product' => function($query) {
                    $query->select('id', 'category_id', 'title', 'product_code');
                },
                'product.Brand' => function($query) {
                    $query->select('id', 'title');
                }
            ])
            ->select('id', 'order_id', 'product_id', 'qty', 'product_name')
            ->orderBy('id', 'desc')
            ->get();
        
        return DataTables::of($orderProducts)
            ->addColumn('part_no', fn($row) => $row->product->product_code ?? '-')
            ->addColumn('brand', function($row) {
                return $row->product && $row->product->Brand 
                    ? $row->product->Brand->title 
                    : '-';
            })
            ->addColumn('main_product_name', fn($row) => $row->product->title ?? '-')
            ->make(true);
    }

    public function getProductsByBrand(Request $request)
    {
        $brandId = $request->input('brand_id');
        
        if (!$brandId) {
            return response()->json(['data' => []]);
        }

        $products = BrandProduct::where('category_id', $brandId)
            ->select('id', 'product_code', 'title')
            ->orderBy('title', 'asc')
            ->get();

        return response()->json(['data' => $products]);
    }
}
