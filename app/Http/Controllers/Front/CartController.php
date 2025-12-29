<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\BrandProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:chivalry_brand_product,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $user = Auth::user();
            $cart = Cart::getOrCreateCart($user->id);

            // Check if product already exists in cart
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                // Update quantity if product already exists
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Product added to cart successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to add product to cart'
            ], 500);
        }
    }

    /**
     * Remove product from cart
     */
    public function remove(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:chivalry_brand_product,id',
            ]);

            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart not found'
                ], 404);
            }

            CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product removed from cart successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to remove product from cart'
            ], 500);
        }
    }

    /**
     * Get cart items
     */
    public function get()
    {
        try {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'status' => true,
                    'cart' => [],
                    'cart_count' => 0,
                    'cart_total_quantity' => 0
                ]);
            }

            $cartItems = CartItem::with(['product.Brand'])
                ->where('cart_id', $cart->id)
                ->get();

            $cartData = $cartItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'product_id' => $item->product_id,
                    'product_code' => $item->product->product_code ?? 'N/A',
                    'product_name' => $item->product->title ?? 'N/A',
                    'product' => $item->product->title ?? 'N/A',
                    'brand_title' => $item->product->Brand->title ?? 'N/A',
                    'quantity' => $item->quantity,
                ];
            });

            $totalQuantity = $cartItems->sum('quantity');

            return response()->json([
                'status' => true,
                'cart' => $cartData,
                'cart_count' => $cartItems->count(),
                'cart_total_quantity' => $totalQuantity
            ]);

        } catch (\Exception $e) {
            Log::error('Cart get error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to get cart items'
            ], 500);
        }
    }

    /**
     * Get cart count
     */
    public function count()
    {
        try {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'status' => true,
                    'cart_count' => 0
                ]);
            }

            $cartCount = CartItem::where('cart_id', $cart->id)->count();

            return response()->json([
                'status' => true,
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            Log::error('Cart count error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'cart_count' => 0
            ]);
        }
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        try {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
            }

            return response()->json([
                'status' => true,
                'message' => 'Cart cleared successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Cart clear error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }
}
