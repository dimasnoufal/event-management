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
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $caPath = storage_path('app/certs/cacert.pem'); 
        $opts = [];

        if (is_file($caPath)) {
            $opts[CURLOPT_CAINFO]         = $caPath;
            $opts[CURLOPT_SSL_VERIFYPEER] = true;
            $opts[CURLOPT_SSL_VERIFYHOST] = 2;
        }

        if (!isset($opts[CURLOPT_HTTPHEADER])) {
            $opts[CURLOPT_HTTPHEADER] = [];
        }

        Config::$curlOptions = array_replace(Config::$curlOptions ?? [], $opts);
    }

    /**
     * Membuat transaksi Snap Midtrans dan mendapatkan response object.
     *
     * @param array $params Parameter transaksi yang diperlukan untuk Snap
     * @return object Response object dari Midtrans Snap transaction
     * @throws \Exception Jika terjadi kesalahan dalam pembuatan transaksi Snap
     */
    public function createSnap(array $params): object
    {
        try {
            return Snap::createTransaction($params); 
        } catch (\Exception $e) {
            throw new \Exception('Error creating Snap transaction with Midtrans: '.$e->getMessage());
        }
    }

    /**
     * Membuat transaksi di Midtrans dan mendapatkan snap token.
     *
     * @param array $transactionDetails
     * @return string
     */
    public function createTransaction(array $params): string
    {
        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            throw new \Exception('Error creating transaction with Midtrans: '.$e->getMessage());
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
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Error checking payment status: '.$e->getMessage());
        }
    }
}