<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Payment::query()->with('order')->latest()->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $payment = Payment::create($request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'method' => ['required', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:30'],
        ]));

        return response()->json($payment->load('order'), 201);
    }

    public function show(Payment $payment): JsonResponse
    {
        return response()->json($payment->load('order'));
    }

    public function update(Request $request, Payment $payment): JsonResponse
    {
        $payment->update($request->validate([
            'order_id' => ['sometimes', 'required', 'integer', 'exists:orders,id'],
            'method' => ['sometimes', 'required', 'string', 'max:30'],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:30'],
        ]));

        return response()->json($payment->refresh()->load('order'));
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();

        return response()->json([], 204);
    }
}
