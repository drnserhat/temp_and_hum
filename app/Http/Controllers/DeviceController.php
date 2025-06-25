<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Measurement;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function index()
    {
        $userDevices = Auth::user()->userDevices()->with('device')->get();
        $availableDevices = Device::whereNull('user_id')->get();
        return view('devices.index', compact('userDevices', 'availableDevices'));
    }

    public function create()
    {
        $availableDevices = Device::whereNull('user_id')->get();
        return view('devices.create', compact('availableDevices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->device_id);
        
        if ($device->user_id !== null) {
            return back()->with('error', 'Bu cihaz zaten bir kullanıcıya atanmış.');
        }

        // Cihazı kullanıcıya ata
        $device->update(['user_id' => Auth::id()]);

        // UserDevice tablosuna ekle
        UserDevice::create([
            'user_id' => Auth::id(),
            'device_id' => $device->id,
        ]);

        return redirect()->route('devices.index')->with('success', 'Cihaz başarıyla eklendi.');
    }

    public function show(Device $device)
    {
        // Kullanıcının kendi cihazına erişim kontrolü
        if ($device->user_id !== Auth::id()) {
            abort(403);
        }

        $userDevice = UserDevice::where('user_id', Auth::id())
                               ->where('device_id', $device->id)
                               ->first();
        
        $latestMeasurement = $device->getLatestMeasurement();
        $measurements = $device->measurements()->latest('measured_at')->take(50)->get();

        return view('devices.show', compact('device', 'userDevice', 'latestMeasurement', 'measurements'));
    }

    public function destroy(Device $device)
    {
        // Kullanıcının kendi cihazına erişim kontrolü
        if ($device->user_id !== Auth::id()) {
            abort(403);
        }

        // UserDevice kaydını sil
        UserDevice::where('user_id', Auth::id())
                  ->where('device_id', $device->id)
                  ->delete();

        // Cihazı kullanıcıdan ayır (cihazı silme, sadece ilişkiyi kesme)
        $device->update(['user_id' => null]);

        return redirect()->route('devices.index')->with('success', 'Cihaz başarıyla kaldırıldı.');
    }
}
