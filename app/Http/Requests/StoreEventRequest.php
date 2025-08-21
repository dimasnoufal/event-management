<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'date'        => ['required','date'], 
            'location'    => ['nullable','string','max:255'],
            'organizer'   => ['required','string','max:255'],
            'price'       => ['required','numeric','min:0'],  
            'status'      => ['required','in:scheduled,ongoing,completed,cancelled'], 
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
            'title.required' => 'Judul event harus diisi.',
            'title.string' => 'Judul event harus berupa teks.',
            'title.max' => 'Judul event tidak boleh lebih dari 255 karakter.',
            'description.string' => 'Deskripsi event harus berupa teks.',
            'date.required' => 'Tanggal event harus diisi.',
            'date.date' => 'Tanggal event harus dalam format yang valid.',
            'date.after' => 'Tanggal event harus setelah hari ini.',
            'location.string' => 'Lokasi event harus berupa teks.',
            'location.max' => 'Lokasi event tidak boleh lebih dari 255 karakter.',
            'organizer.required' => 'Nama penyelenggara harus diisi.',
            'organizer.string' => 'Nama penyelenggara harus berupa teks.',
            'organizer.max' => 'Nama penyelenggara tidak boleh lebih dari 255 karakter.',
            'price.required' => 'Harga event harus diisi.',
            'price.numeric' => 'Harga event harus berupa angka.',
            'price.min' => 'Harga event tidak boleh kurang dari 0.',
            'status.required' => 'Status event harus dipilih.',
            'status.in' => 'Status event hanya boleh "scheduled", "ongoing", "completed", atau "cancelled".',
        ];
    }

    /**
     * Menyesuaikan nama atribut di pesan kesalahan validasi.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title'     => 'Judul Event',
            'date'      => 'Tanggal/Waktu Event',
            'location'  => 'Lokasi Event',
            'organizer' => 'Penyelenggara',
            'price'     => 'Harga',
            'status'    => 'Status',
        ];
    }
}
