<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $events = Event::query()
                ->whereIn('status', ['scheduled', 'ongoing']) 
                ->latest('date')
                ->get();

            if ($events->isEmpty()) {
                return ResponseFormatter::success(
                    [],
                    'Tidak ada events mendatang'
                );
            }

            return ResponseFormatter::success(
                $events,
                'Events mendatang berhasil diambil'
            );

        } catch (\Exception $e) {
            Log::error('Error fetching upcoming events: ' . $e->getMessage());
            
            return ResponseFormatter::error(
                null,
                'Gagal mengambil events mendatang',
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        try {
            if (!in_array($event->status, ['scheduled', 'ongoing', 'active'])) {
                return ResponseFormatter::error(
                    null,
                    'Event tidak tersedia atau sudah berakhir',
                    404
                );
            }

            return ResponseFormatter::success(
                $event,
                'Event berhasil diambil'
            );

        } catch (\Exception $e) {
            Log::error('Error fetching event ID: ' . $event->id . ' - ' . $e->getMessage());
            
            return ResponseFormatter::error(
                null,
                'Gagal mengambil event dengan ID: ' . $event->id,
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
