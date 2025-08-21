<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\PaymentService;
use App\Models\Registration;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Log;

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
    public function store(StorePaymentRequest $request, MidtransService $midtrans, PaymentService $paymentService)
    {
        $user = $request->user();
        $data = $request->validated();

        $registration = Registration::with('event')
            ->where('id', $data['registration_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (optional($registration->payment)->payment_status === 'success') {
            return ResponseFormatter::error('Registrasi sudah dibayar', 422);
        }

        $amount  = (int) round($registration->event->price); 
        $orderId = $this->generate_order_id($registration->id);

        $payment = \App\Models\Payment::updateOrCreate(
            ['registration_id' => $registration->id, 'payment_status' => 'pending'],
            [
                'external_order_id' => $orderId,
                'amount'            => $amount,
                'payment_method'    => 'bank_transfer',
            ]
        );

        $params = [
            'transaction_details' => [
                'order_id'     => $payment->external_order_id,
                'gross_amount' => $amount, 
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? '',
            ],
        ];

        $snap = $midtrans->createSnap($params);

        $payment->update(['redirect_url' => $snap->redirect_url]);

        return ResponseFormatter::success([
            'registration_id' => $registration->id,
            'payment_id'      => $payment->id,
            'order_id'        => $payment->external_order_id,
            'snap_token'      => $snap->token,
            'redirect_url'    => $snap->redirect_url,
            'client_key'      => config('services.midtrans.client_key'),
        ], 'Checkout berhasil dibuat', 201);
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

    public function webhook(Request $request)
    {
        Log::info('Midtrans webhook hit', ['json' => $request->all()]);

        $orderId           = $request->input('order_id');
        $statusCode        = $request->input('status_code');
        $grossAmount       = $request->input('gross_amount');          
        $signatureKeyRecv  = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');    
        $fraudStatus       = $request->input('fraud_status');          

        if (empty($orderId) || empty($statusCode) || empty($grossAmount)) {
            return response()->json(['message' => 'pong'], 200);
        }

        $serverKey    = env('MIDTRANS_SERVER_KEY');
        $signatureCalc = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if (!hash_equals($signatureCalc, (string) $signatureKeyRecv)) {
            Log::warning('Midtrans invalid signature', ['order_id' => $orderId]);
            return ResponseFormatter::error('Invalid signature', 401);
        }

        $payment = Payment::with('registration')
            ->where('external_order_id', $orderId)
            ->first();

        if (!$payment) {
            Log::warning('Payment not found for order_id', ['order_id' => $orderId]);
            return ResponseFormatter::success(null, 'Payment not found (ignored)', 200);
        }

        $newStatus = match ($transactionStatus) {
            'capture'    => ($fraudStatus === 'challenge') ? 'pending' : 'success',
            'settlement' => 'success',
            'pending'    => 'pending',
            'cancel', 'deny', 'expire' => 'failed',
            default => 'pending',
        };

        if (in_array($payment->payment_status, ['success', 'failed'], true)) {
            return ResponseFormatter::success(null, 'Already finalized', 200);
        }

        DB::transaction(function () use ($payment, $newStatus, $request) {
            $payload = $request->all();

            $payment->update([
                'payment_status'   => $newStatus,
            ]);

            if ($payment->registration) {
                $payment->registration->update([
                    'payment_status' => $newStatus === 'success'
                        ? 'paid'
                        : ($newStatus === 'failed' ? 'failed' : 'pending'),
                ]);
            }
        });

        return ResponseFormatter::success(null, 'Webhook processed', 200);
    }

    // Resposne notification url midtrans
    // {
    //   "transaction_time": "2023-11-15 18:45:13",
    //   "transaction_status": "settlement",
    //   "transaction_id": "513f1f01-c9da-474c-9fc9-d5c64364b709",
    //   "status_message": "midtrans payment notification",
    //   "status_code": "200",
    //   "signature_key": "3b40795c25d9c5c6245951de7382d0ece6857921001bf937dc2fdd6907cdf3dde82d2ced0bc82be7fbcf21aa07db28f6b104469a8961aa8cb1d2fcfe59a9a657",
    //   "settlement_time": "2023-11-15 22:45:13",
    //   "payment_type": "gopay",
    //   "order_id": "payment_notif_test_G684004555_4d95f89d-7948-4bb0-948c-9c9cba48a9b8",
    //   "merchant_id": "G684004555",
    //   "gross_amount": "105000.00",
    //   "fraud_status": "accept",
    //   "currency": "IDR"
    // }

    function generate_order_id(int $registrationId): string
    {
        return 'EVT-' . $registrationId . '-' . now()->format('YmdHis');
    }
}
