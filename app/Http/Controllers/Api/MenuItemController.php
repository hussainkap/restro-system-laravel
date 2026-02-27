<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(MenuItem::query()->with('category')->latest()->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $item = MenuItem::create($request->validate([
            'menu_category_id' => ['required', 'integer', 'exists:menu_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_available' => ['boolean'],
        ]));

        return response()->json($item->load('category'), 201);
    }

    public function show(MenuItem $menuItem): JsonResponse
    {
        return response()->json($menuItem->load('category'));
    }

    public function update(Request $request, MenuItem $menuItem): JsonResponse
    {
        $menuItem->update($request->validate([
            'menu_category_id' => ['sometimes', 'required', 'integer', 'exists:menu_categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'is_available' => ['boolean'],
        ]));

        return response()->json($menuItem->refresh()->load('category'));
    }

    public function destroy(MenuItem $menuItem): JsonResponse
    {
        $menuItem->delete();

        return response()->json([], 204);
    }
}
