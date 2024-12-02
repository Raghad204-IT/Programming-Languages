<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // عرض المتاجر لجميع المستخدمين
    public function index() {
        $stores = Store::with('products')->get();
        return response()->json($stores, 200);
    }

    // إضافة متجر جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $store = Store::create($validated);

        return response()->json($store, 201);
    }

    // عرض متجر معين مع المنتجات المرتبطة به
    public function show($id)
    {
        $store = Store::with('products')->find($id);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        return response()->json($store, 200);
    }
}
