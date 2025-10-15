<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Registration;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{

    public function getByUserId(Request $request, $userId)
    {
        try {
            // Optional: Add authorization check if needed
            // if ($request->user()->role !== 'admin') {
            //     return ResponseFormatter::error(null, 'Unauthorized', 403);
            // }

            $registrations = Registration::with(['event', 'payment', 'user'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($registrations->isEmpty()) {
                return ResponseFormatter::success(
                    [],
                    "Tidak ada registrasi untuk user ID: $userId"
                );
            }

            // Transform data dengan info user
            $transformedData = $registrations->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'user' => [
                        'id' => $registration->user->id,
                        'name' => $registration->user->name,
                        'email' => $registration->user->email,
                    ],
                    'event_id' => $registration->event_id,
                    'event_title' => $registration->event->title ?? 'Event Deleted',
                    'event_date' => $registration->event->event_date ?? null,
                    'event_location' => $registration->event->location ?? null,
                    'event_price' => $registration->event->price ?? 0,
                    'payment_status' => $registration->payment_status,
                    'registration_date' => $registration->created_at,
                    'payment' => $registration->payment ? [
                        'id' => $registration->payment->id,
                        'amount' => $registration->payment->amount,
                        'payment_method' => $registration->payment->payment_method,
                        'payment_status' => $registration->payment->payment_status,
                        'external_order_id' => $registration->payment->external_order_id,
                        'payment_date' => $registration->payment->payment_date,
                    ] : null,
                ];
            });

            return ResponseFormatter::success(
                $transformedData,
                "Daftar registrasi user ID: $userId berhasil diambil"
            );

        } catch (\Exception $e) {
            Log::error("Error fetching registrations for user ID $userId: " . $e->getMessage());
            
            return ResponseFormatter::error(
                null,
                'Gagal mengambil daftar registrasi user',
                500
            );
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $userId = $request->user()->id;
            
            $registrations = Registration::with(['event', 'payment'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($registrations->isEmpty()) {
                return ResponseFormatter::success(
                    [],
                    'Belum ada registrasi event'
                );
            }

            $transformedData = $registrations->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'event_id' => $registration->event_id,
                    'event_title' => $registration->event->title ?? 'Event Deleted',
                    'event_date' => $registration->event->event_date ?? null,
                    'event_location' => $registration->event->location ?? null,
                    'event_price' => $registration->event->price ?? 0,
                    'payment_status' => $registration->payment_status,
                    'registration_date' => $registration->created_at,
                    'payment' => $registration->payment ? [
                        'id' => $registration->payment->id,
                        'amount' => $registration->payment->amount,
                        'payment_method' => $registration->payment->payment_method,
                        'payment_status' => $registration->payment->payment_status,
                        'external_order_id' => $registration->payment->external_order_id,
                        'payment_date' => $registration->payment->payment_date,
                    ] : null,
                ];
            });

            return ResponseFormatter::success(
                $transformedData,
                'Daftar registrasi berhasil diambil'
            );

        } catch (\Exception $e) {
            Log::error('Error fetching user registrations: ' . $e->getMessage());
            
            return ResponseFormatter::error(
                null,
                'Gagal mengambil daftar registrasi',
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegistrationRequest $request)
    {
        $validatedData = $request->validated();

        $registration = Registration::create([
            'user_id' => $request->user()->id,
            'event_id' => $validatedData['event_id'],
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Registration created successfully!',
            'registration_id' => $registration->id
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $userId = $request->user()->id;
            
            $registration = Registration::with(['event', 'payment'])
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$registration) {
                return ResponseFormatter::error(
                    null,
                    'Registrasi tidak ditemukan',
                    404
                );
            }

            $data = [
                'id' => $registration->id,
                'event_id' => $registration->event_id,
                'event' => $registration->event ? [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'description' => $registration->event->description,
                    'event_date' => $registration->event->event_date,
                    'location' => $registration->event->location,
                    'price' => $registration->event->price,
                    'status' => $registration->event->status,
                ] : null,
                'payment_status' => $registration->payment_status,
                'registration_date' => $registration->created_at,
                'payment' => $registration->payment ? [
                    'id' => $registration->payment->id,
                    'amount' => $registration->payment->amount,
                    'payment_method' => $registration->payment->payment_method,
                    'payment_status' => $registration->payment->payment_status,
                    'external_order_id' => $registration->payment->external_order_id,
                    'redirect_url' => $registration->payment->redirect_url,
                    'payment_date' => $registration->payment->payment_date,
                ] : null,
            ];

            return ResponseFormatter::success(
                $data,
                'Detail registrasi berhasil diambil'
            );

        } catch (\Exception $e) {
            Log::error('Error fetching registration: ' . $e->getMessage());
            
            return ResponseFormatter::error(
                null,
                'Gagal mengambil detail registrasi',
                500
            );
        }
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
}
