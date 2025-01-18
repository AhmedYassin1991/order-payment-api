<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::Status($request->status)->paginate(10);

        return sendResponse(OrderResource::collection($orders), 'Order Data Reverited successfully', 200);
    }

    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        // Calculate total
        $total = collect($request->items)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
        $user = Auth::user();
        $order = Order::create([
            'user_id' => $user->id,
            'items' => $validatedData['items'],
            'total' => $total,
            'status' => 'pending', // Default status
        ]);

        return sendResponse(new OrderResource($order), 'order Created ', 201);
    }

    public function show(Order $order)
    {
        return $order;
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validatedData = $request->validated();

        // Recalculate total if items are updated
        if ($request->has('items')) {
            $total = collect($validatedData['items'])->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });
            $order->total = $total;
            $order->items = $validatedData['items'];
        }

        // Update status if provided
        if ($request->has('status')) {
            $order->status = $validatedData['status'];
        }

        $order->save();

        return sendResponse(new OrderResource($order), 'order Updated', 200);
    }

    public function destroy(Order $order)
    {
        // Check if the order exists
        if (! $order) {
            return sendError('Order not found.', 404);
        }

        if ($order->payments()->exists()) {
            return sendError('Cannot delete order with associated payments.', 400);
        }

        $order->delete();

        return sendResponse(null, 'order deleted', 204);
    }
}
