<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(RestaurantTable::query()->with('branch')->latest()->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $table = RestaurantTable::create($request->validate([
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'max:30'],
        ]));

        return response()->json($table->load('branch'), 201);
    }

    public function show(RestaurantTable $table): JsonResponse
    {
        return response()->json($table->load('branch'));
    }

    public function update(Request $request, RestaurantTable $table): JsonResponse
    {
        $table->update($request->validate([
            'branch_id' => ['sometimes', 'required', 'integer', 'exists:branches,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'max:30'],
        ]));

        return response()->json($table->refresh()->load('branch'));
    }

    public function destroy(RestaurantTable $table): JsonResponse
    {
        $table->delete();

        return response()->json([], 204);
    }
}
