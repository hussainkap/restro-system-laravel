<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Order::query()->with(['branch', 'table', 'orderItems', 'payments'])->latest()->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $order = Order::create($request->validate([
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'table_id' => ['nullable', 'integer', 'exists:tables,id'],
            'order_number' => ['required', 'string', 'max:255', 'unique:orders,order_number'],
            'status' => ['nullable', 'string', 'max:30'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'ordered_at' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date'],
        ]));

        return response()->json($order->load(['branch', 'table', 'orderItems', 'payments']), 201);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json($order->load(['branch', 'table', 'orderItems.menuItem', 'payments']));
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $order->update($request->validate([
            'branch_id' => ['sometimes', 'required', 'integer', 'exists:branches,id'],
            'table_id' => ['nullable', 'integer', 'exists:tables,id'],
            'order_number' => ['sometimes', 'required', 'string', 'max:255', 'unique:orders,order_number,' . $order->id],
            'status' => ['nullable', 'string', 'max:30'],
            'subtotal' => ['sometimes', 'required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'total' => ['sometimes', 'required', 'numeric', 'min:0'],
            'ordered_at' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date'],
        ]));

        return response()->json($order->refresh()->load(['branch', 'table', 'orderItems', 'payments']));
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json([], 204);
    }
}
