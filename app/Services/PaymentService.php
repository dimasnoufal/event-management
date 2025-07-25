<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Registration;

class PaymentService
{
    /**
     * Proses pembayaran dan simpan ke database
     *
     * @param array $paymentData
     * @return Payment
     */
    public function processPayment(array $paymentData)
    {
        // Simpan informasi pembayaran ke database
        $payment = Payment::create([
            'registration_id' => $paymentData['registration_id'],
            'amount' => $paymentData['amount'],
            'payment_method' => $paymentData['payment_method'],
            'payment_status' => $paymentData['payment_status'],
        ]);

        // Update status pendaftaran sesuai pembayaran
        $payment->registration->update(['payment_status' => $paymentData['payment_status']]);

        return $payment;
    }

    /**
     * Mengupdate status pembayaran berdasarkan hasil dari Midtrans
     *
     * @param string $paymentId
     * @param string $status
     * @return void
     */
    public function updatePaymentStatus($paymentId, $status)
    {
        $payment = Payment::find($paymentId);
        if ($payment) {
            $payment->update(['payment_status' => $status]);
            $payment->registration->update(['payment_status' => $status]);
        }
    }
}