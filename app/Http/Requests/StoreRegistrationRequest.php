<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
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
            'event_id' => ['required', 'exists:events,id', 'integer'],
            'payment_status' => ['sometimes', 'in:pending,paid,failed'],
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
            'event_id.required' => 'Event harus dipilih.',
            'event_id.exists' => 'Event yang dipilih tidak valid.',
            'event_id.integer' => 'ID Event harus berupa angka.',
            'payment_status.in' => 'Status pembayaran hanya boleh "pending", "paid", atau "failed".',
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
            'event_id' => 'Event',
            'payment_status' => 'Status Pembayaran',
        ];
    }
}