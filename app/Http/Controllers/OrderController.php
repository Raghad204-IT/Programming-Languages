<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with(['details.product'])->get();

        // إظهار الطلبات مع إجمالي السعر لكل قطعة
        $orders = $orders->map(function ($order) {
            $order->details = $order->details->map(function ($detail) {
                $detail->total_price = $detail->quantity * $detail->price; // حساب السعر الإجمالي
                return $detail;
            });
            return $order;
        });

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        // Create a new order logic
    }
}
