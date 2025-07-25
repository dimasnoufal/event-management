<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class MidtransService
{
    /**
     * MidtransService constructor.
     */
    public function __construct()
    {
        // Set up Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);  // Set to true for production
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Membuat transaksi di Midtrans dan mendapatkan snap token.
     *
     * @param array $transactionDetails
     * @return string
     */
    public function createTransaction(array $transactionDetails)
    {
        try {
            // Mendapatkan snap token
            $snapToken = Snap::getSnapToken($transactionDetails);

            return $snapToken;
        } catch (\Exception $e) {
            // Tangani jika ada kesalahan dalam pembuatan transaksi
            throw new \Exception('Error creating transaction with Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Mengecek status pembayaran di Midtrans.
     *
     * @param string $orderId
     * @return array
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            throw new \Exception('Error checking payment status: ' . $e->getMessage());
        }
    }
}