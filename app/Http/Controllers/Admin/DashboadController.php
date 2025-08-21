<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboadController extends Controller
{
    public function index()
    {
        // (opsional) data metrik
        $eventCount        = \App\Models\Event::count();
        $userCount         = \App\Models\User::count();
        $registrationCount = \App\Models\Registration::count();
        $paymentSum        = \App\Models\Payment::where('payment_status','success')->sum('amount');

        return view('admin.dashboard', compact(
            'eventCount','userCount','registrationCount','paymentSum'
        ));
    }
}
