<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(OrderItem::query()->with(['order', 'menuItem'])->latest()->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $orderItem = OrderItem::create($request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]));

        return response()->json($orderItem->load(['order', 'menuItem']), 201);
    }

    public function show(OrderItem $orderItem): JsonResponse
    {
        return response()->json($orderItem->load(['order', 'menuItem']));
    }

    public function update(Request $request, OrderItem $orderItem): JsonResponse
    {
        $orderItem->update($request->validate([
            'order_id' => ['sometimes', 'required', 'integer', 'exists:orders,id'],
            'menu_item_id' => ['sometimes', 'required', 'integer', 'exists:menu_items,id'],
            'quantity' => ['sometimes', 'required', 'integer', 'min:1'],
            'unit_price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'total_price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]));

        return response()->json($orderItem->refresh()->load(['order', 'menuItem']));
    }

    public function destroy(OrderItem $orderItem): JsonResponse
    {
        $orderItem->delete();

        return response()->json([], 204);
    }
}
