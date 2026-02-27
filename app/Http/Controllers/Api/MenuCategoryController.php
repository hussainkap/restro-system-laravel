<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(MenuCategory::query()->latest()->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $category = MenuCategory::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]));

        return response()->json($category, 201);
    }

    public function show(MenuCategory $menuCategory): JsonResponse
    {
        return response()->json($menuCategory);
    }

    public function update(Request $request, MenuCategory $menuCategory): JsonResponse
    {
        $menuCategory->update($request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]));

        return response()->json($menuCategory->refresh());
    }

    public function destroy(MenuCategory $menuCategory): JsonResponse
    {
        $menuCategory->delete();

        return response()->json([], 204);
    }
}
