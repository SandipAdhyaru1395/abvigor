<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\BrandProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalysisController extends Controller
{

    public function getAnalysisData(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = $request->input('user_id');
        $state = $request->input('state');
        $brandId = $request->input('brand_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $analysisType = $request->input('analysis_type', 'overview');

        $query = OrderProduct::query()
            ->join('rudra_order_order', 'rudra_order_products.order_id', '=', 'rudra_order_order.id')
            ->join('users', 'rudra_order_order.user_id', '=', 'users.id')
            ->leftJoin('chivalry_brand_product', 'rudra_order_products.product_id', '=', 'chivalry_brand_product.id')
            ->select(
                'rudra_order_products.*',
                'rudra_order_order.order_date',
                'rudra_order_order.order_no',
                'rudra_order_order.user_id',
                'users.name as user_name',
                'users.state as user_state',
                'users.email as user_email',
                'chivalry_brand_product.category_id as brand_id'
            )
            ->whereNotNull('rudra_order_order.order_date');

        // Apply filters
        if ($productId) {
            $query->where('rudra_order_products.product_id', $productId);
        }

        if ($userId) {
            $query->where('rudra_order_order.user_id', $userId);
        }

        if ($state) {
            $query->where('users.state', $state);
        }

        if ($brandId) {
            $query->where('chivalry_brand_product.category_id', $brandId);
        }

        if ($startDate) {
            $query->whereDate('rudra_order_order.order_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('rudra_order_order.order_date', '<=', $endDate);
        }

        switch ($analysisType) {
            case 'product_wise':
                return $this->getProductWiseAnalysis($query);
            case 'date_wise':
                return $this->getDateWiseAnalysis($query);
            case 'state_wise':
                return $this->getStateWiseAnalysis($query);
            case 'user_wise':
                return $this->getUserWiseAnalysis($query);
            case 'brand_wise':
                return $this->getBrandWiseAnalysis($query);
            default:
                return $this->getOverviewAnalysis($query);
        }
    }

    private function getOverviewAnalysis($query)
    {
        $data = $query->get();
        
        $orderIds = $data->pluck('order_id')->unique();
        $totalOrders = $orderIds->count();
        $totalQuantity = $data->sum('qty');
        $totalProducts = $data->pluck('product_id')->unique()->count();
        $totalUsers = $data->pluck('user_id')->unique()->count();

        // Top products
        $topProducts = $data->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product_name' => $items->first()->product_name,
                    'total_qty' => $items->sum('qty'),
                    'order_count' => $items->pluck('order_id')->unique()->count()
                ];
            })
            ->sortByDesc('total_qty')
            ->take(10)
            ->values();

        // Top states
        $topStates = $data->groupBy('user_state')
            ->map(function ($items) {
                return [
                    'state' => $items->first()->user_state,
                    'total_qty' => $items->sum('qty'),
                    'order_count' => $items->pluck('order_id')->unique()->count()
                ];
            })
            ->sortByDesc('total_qty')
            ->take(10)
            ->values();

        // Date wise trend (last 30 days)
        $dateWise = $data->groupBy(function ($item) {
            return Carbon::parse($item->order_date)->format('Y-m-d');
        })
            ->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'total_qty' => $items->sum('qty'),
                    'order_count' => $items->pluck('order_id')->unique()->count()
                ];
            })
            ->sortBy('date')
            ->values();

        return response()->json([
            'status' => true,
            'data' => [
                'summary' => [
                    'total_orders' => $totalOrders,
                    'total_quantity' => $totalQuantity,
                    'total_products' => $totalProducts,
                    'total_users' => $totalUsers,
                ],
                'top_products' => $topProducts,
                'top_states' => $topStates,
                'date_wise' => $dateWise,
            ]
        ]);
    }

    private function getProductWiseAnalysis($query)
    {
        $data = $query->get();

        $productWise = $data->groupBy('product_id')
            ->map(function ($items) {
                $orderCount = $items->pluck('order_id')->unique()->count();
                return [
                    'product_id' => $items->first()->product_id,
                    'product_name' => $items->first()->product_name,
                    'total_quantity' => $items->sum('qty'),
                    'order_count' => $orderCount,
                    'avg_quantity_per_order' => $orderCount > 0 ? round($items->sum('qty') / $orderCount, 2) : 0,
                ];
            })
            ->sortByDesc('total_quantity')
            ->values();

        return response()->json([
            'status' => true,
            'data' => $productWise
        ]);
    }

    private function getDateWiseAnalysis($query)
    {
        $data = $query->get();

        $dateWise = $data->groupBy(function ($item) {
            return Carbon::parse($item->order_date)->format('Y-m-d');
        })
            ->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'total_quantity' => $items->sum('qty'),
                    'order_count' => $items->pluck('order_id')->unique()->count(),
                    'product_count' => $items->pluck('product_id')->unique()->count(),
                    'user_count' => $items->pluck('user_id')->unique()->count(),
                ];
            })
            ->sortBy('date')
            ->values();

        return response()->json([
            'status' => true,
            'data' => $dateWise
        ]);
    }

    private function getStateWiseAnalysis($query)
    {
        $data = $query->get();

        $stateWise = $data->groupBy('user_state')
            ->map(function ($items) {
                return [
                    'state' => $items->first()->user_state ?: 'N/A',
                    'total_quantity' => $items->sum('qty'),
                    'order_count' => $items->pluck('order_id')->unique()->count(),
                    'product_count' => $items->pluck('product_id')->unique()->count(),
                    'user_count' => $items->pluck('user_id')->unique()->count(),
                ];
            })
            ->sortByDesc('total_quantity')
            ->values();

        return response()->json([
            'status' => true,
            'data' => $stateWise
        ]);
    }

    private function getUserWiseAnalysis($query)
    {
        $data = $query->get();

        $userWise = $data->groupBy('user_id')
            ->map(function ($items) {
                $orderCount = $items->pluck('order_id')->unique()->count();
                return [
                    'user_id' => $items->first()->user_id,
                    'user_name' => $items->first()->user_name,
                    'user_email' => $items->first()->user_email,
                    'user_state' => $items->first()->user_state,
                    'total_quantity' => $items->sum('qty'),
                    'order_count' => $orderCount,
                    'product_count' => $items->pluck('product_id')->unique()->count(),
                    'avg_quantity_per_order' => $orderCount > 0 ? round($items->sum('qty') / $orderCount, 2) : 0,
                ];
            })
            ->sortByDesc('total_quantity')
            ->values();

        return response()->json([
            'status' => true,
            'data' => $userWise
        ]);
    }

    private function getBrandWiseAnalysis($query)
    {
        $data = $query->get();

        $brandWise = $data->groupBy('brand_id')
            ->map(function ($items) {
                $brandId = $items->first()->brand_id;
                $brand = \App\Models\BrandCategory::find($brandId);
                $orderCount = $items->pluck('order_id')->unique()->count();
                return [
                    'brand_id' => $brandId,
                    'brand_name' => $brand ? $brand->title : 'N/A',
                    'total_quantity' => $items->sum('qty'),
                    'order_count' => $orderCount,
                    'product_count' => $items->pluck('product_id')->unique()->count(),
                    'user_count' => $items->pluck('user_id')->unique()->count(),
                    'avg_quantity_per_order' => $orderCount > 0 ? round($items->sum('qty') / $orderCount, 2) : 0,
                ];
            })
            ->sortByDesc('total_quantity')
            ->values();

        return response()->json([
            'status' => true,
            'data' => $brandWise
        ]);
    }
}
