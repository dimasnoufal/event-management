<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use App\Helper\ResponseFormatter;

class UserDeviceController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'device_token' => 'required|string',
            'platform'     => 'nullable|string|in:android,ios,web',
            'device_name'  => 'nullable|string|max:150',
        ]);

        $device = UserDevice::updateOrCreate(
            ['device_token' => $data['device_token']],
            [
                'user_id'     => $request->user()->id,
                'platform'    => $data['platform'] ?? null,
                'device_name' => $data['device_name'] ?? null,
            ]
        );

        return ResponseFormatter::success($device, 'Device registered');
    }

    public function unregister(Request $request)
    {
        $data = $request->validate([
            'device_token' => 'required|string',
        ]);

        UserDevice::where('device_token', $data['device_token'])
            ->where('user_id', $request->user()->id)
            ->delete();

        return ResponseFormatter::success(null, 'Device unregistered');
    }
}
