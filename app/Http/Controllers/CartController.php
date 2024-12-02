<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // عرض محتويات السلة
    public function index(Request $request)
    {
        $cartItems = $request->user()->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        return response()->json($cartItems);
    }

    // إضافة منتج إلى السلة
    public function addToCart(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($validated['product_id']);

        if (!$product || $product->quantity < $validated['quantity']) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        $cart = Cart::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $product->id],
            ['quantity' => $validated['quantity']]
        );

        return response()->json($cart, 201);
    }

    // حذف منتج من السلة
    public function removeFromCart(Request $request, $id)
    {
        $cartItem = $request->user()->cart()->findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart']);
    }

    // تأكيد الطلب
    public function confirmOrder(Request $request)
    {
        $cartItems = $request->user()->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => $request->user()->id,
            'status' => 'confirmed',
            'total_price' => $totalPrice,
        ]);

        foreach ($cartItems as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            // تقليل الكمية المتاحة من المنتج
            $item->product->decrement('quantity', $item->quantity);
        }

        // مسح السلة بعد تأكيد الطلب
        $request->user()->cart()->delete();

        return response()->json(['message' => 'Order confirmed', 'order' => $order]);
    }
}
