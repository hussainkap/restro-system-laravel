<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Branch::query()->latest()->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $branch = Branch::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
        ]));

        return response()->json($branch, 201);
    }

    public function show(Branch $branch): JsonResponse
    {
        return response()->json($branch);
    }

    public function update(Request $request, Branch $branch): JsonResponse
    {
        $branch->update($request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
        ]));

        return response()->json($branch->refresh());
    }

    public function destroy(Branch $branch): JsonResponse
    {
        $branch->delete();

        return response()->json([], 204);
    }
}
