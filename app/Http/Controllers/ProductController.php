<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض جميع المنتجات
    public function index() {
        $products = Product::with('store')->get();
        return response()->json($products, 200);
    }

    // إضافة منتج إلى متجر
    public function store(Request $request, $storeId)
    {
        $store = Store::find($storeId);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:0',
            'product_image' => 'nullable|string',
        ]);

        $product = $store->products()->create($validated);

        return response()->json($product, 201);
    }

    // عرض منتج معين
    public function show($id)
    {
        $product = Product::with('store')->find($id);

        if (!$product) {

            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }
}

