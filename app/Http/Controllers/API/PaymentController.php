<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function processPayment(ProcessPaymentRequest $request, Order $order)
    {

        try {
            $validatedData = $request->validated();
            $payment = $this->paymentService->processPayment($order, $validatedData['payment_method']);

            return sendResponse($payment, 'Payment Created', 201);
        } catch (\Exception $e) {
            return sendError($e->getMessage(), 400);
        }
    }

    public function index(Order $order)
    {
        $payments = $order->payments()->paginate(10);

        return sendResponse(PaymentResource::collection($payments), 'Payments Data Reverited successfully', 200);
    }
}
