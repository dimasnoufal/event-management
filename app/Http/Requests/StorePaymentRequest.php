<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'registration_id' => ['required','integer','exists:registrations,id'],
        ];
    }

    /**
     * Custom messages untuk kesalahan validasi.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'registration_id.required' => 'ID Registrasi harus diisi.',
            'registration_id.exists' => 'Registrasi yang dipilih tidak valid.',
            'registration_id.integer' => 'ID Registrasi harus berupa angka.',
            'amount.required' => 'Jumlah pembayaran harus diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran minimal 0.01.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran hanya boleh "credit_card", "paypal", atau "bank_transfer".',
            'payment_status.in' => 'Status pembayaran hanya boleh "pending", "success", atau "failed".',
        ];
    }

    /**
     * Menyesuaikan nama atribut di pesan kesalahan validasi.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'registration_id' => 'ID Registrasi',
            'amount' => 'Jumlah Pembayaran',
            'payment_method' => 'Metode Pembayaran',
            'payment_status' => 'Status Pembayaran',
        ];
    }
}
