<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request, MidtransService $midtransService, PaymentService $paymentService)
    {
        // Mendapatkan data pembayaran dari request
        $validatedData = $request->validated();

        // Membuat transaksi di Midtrans
        $transactionDetails = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $validatedData['amount'],
            ]
        ];

        $snapToken = $midtransService->createTransaction($transactionDetails);

        // Menyimpan pembayaran ke database
        $payment = $paymentService->processPayment([
            'registration_id' => $validatedData['registration_id'],
            'amount' => $validatedData['amount'],
            'payment_method' => $validatedData['payment_method'],
            'payment_status' => 'pending',
        ]);

        // Mengembalikan snap token kepada client untuk memproses pembayaran
        return response()->json(['snap_token' => $snapToken, 'payment' => $payment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // public function webhook(Request $request)
    // {
    //     // Handle payment gateway webhook callback
    //     $data = PaymentService::parseWebhook($request);
    //     $payment = Payment::findOrFail($data['payment_id']);
    //     $payment->update(['payment_status' => $data['status']]);
    //     $payment->registration->update([
    //         'payment_status' => $data['status'] === 'success' ? 'paid' : 'failed'
    //     ]);

    //     return response()->json(['ok' => true]);
    // }
}
