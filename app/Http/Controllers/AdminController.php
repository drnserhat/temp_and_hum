<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Models\Measurement;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalDevices = Device::count();
        $totalMeasurements = Measurement::count();
        $recentMeasurements = Measurement::with('device.user')->latest('measured_at')->take(10)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalDevices', 'totalMeasurements', 'recentMeasurements'));
    }

    public function users()
    {
        $users = User::where('role', 'user')->withCount('devices')->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function devices()
    {
        $devices = Device::with('user')->withCount('measurements')->paginate(15);
        return view('admin.devices', compact('devices'));
    }

    public function measurements()
    {
        $measurements = Measurement::with('device.user')->latest('measured_at')->paginate(20);
        return view('admin.measurements', compact('measurements'));
    }

    public function createDevice()
    {
        return view('admin.devices.create');
    }

    public function storeDevice(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $device = Device::create([
            'name' => $request->name,
            'serial_number' => Device::generateSerialNumber(),
            'user_id' => null, // Admin tarafından oluşturulan cihazlar henüz kullanıcıya atanmamış
        ]);

        return redirect()->route('admin.devices')->with('success', 'Cihaz başarıyla oluşturuldu. Seri No: ' . $device->serial_number);
    }

    public function editDevice(Device $device)
    {
        return view('admin.devices.edit', compact('device'));
    }

    public function updateDevice(Request $request, Device $device)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $device->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.devices')->with('success', 'Cihaz başarıyla güncellendi.');
    }

    public function destroyDevice(Device $device)
    {
        $device->delete();
        return redirect()->route('admin.devices')->with('success', 'Cihaz başarıyla silindi.');
    }
}
