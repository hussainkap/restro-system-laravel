<?php

use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\MenuCategoryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TableController;
use Illuminate\Support\Facades\Route;

Route::apiResource('branches', BranchController::class);
Route::apiResource('tables', TableController::class);
Route::apiResource('menu-categories', MenuCategoryController::class);
Route::apiResource('menu-items', MenuItemController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('order-items', OrderItemController::class);
Route::apiResource('payments', PaymentController::class);
