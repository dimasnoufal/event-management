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
            'user_id' => ['required', 'exists:users,id', 'integer'],
            'event_id' => ['required', 'exists:events,id', 'integer'],
            'registration_date' => ['sometimes', 'date'],
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
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'Selected user does not exist.',
            'event_id.required' => 'Event ID is required.',
            'event_id.exists' => 'Selected event does not exist.',
            'registration_date.date' => 'Registration date must be a valid date.',
            'payment_status.in' => 'Payment status must be pending, paid, or failed.',
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
            'user_id' => 'User',
            'event_id' => 'Event',
            'registration_date' => 'Tanggal Registrasi',
            'payment_status' => 'Status Pembayaran',
        ];
    }
}