<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function storeMeasurement(Request $request): JsonResponse
    {
        $request->validate([
            'serial_number' => 'required|string|exists:devices,serial_number',
            'temperature' => 'required|numeric|between:-50,100',
            'humidity' => 'required|numeric|between:0,100',
            'measured_at' => 'nullable|date',
        ]);

        $device = Device::where('serial_number', $request->serial_number)->first();

        if (!$device) {
            return response()->json(['error' => 'Cihaz bulunamadı.'], 404);
        }

        $measurement = $device->measurements()->create([
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'measured_at' => $request->measured_at ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Veri başarıyla kaydedildi.',
            'data' => [
                'id' => $measurement->id,
                'device_id' => $device->id,
                'temperature' => $measurement->temperature,
                'humidity' => $measurement->humidity,
                'measured_at' => $measurement->measured_at,
            ]
        ], 201);
    }

    public function getLatestMeasurement(Request $request): JsonResponse
    {
        $request->validate([
            'serial_number' => 'required|string|exists:devices,serial_number',
        ]);

        $device = Device::where('serial_number', $request->serial_number)->first();
        $latestMeasurement = $device->getLatestMeasurement();

        if (!$latestMeasurement) {
            return response()->json(['error' => 'Henüz veri bulunmuyor.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'device_id' => $device->id,
                'device_name' => $device->name,
                'temperature' => $latestMeasurement->temperature,
                'humidity' => $latestMeasurement->humidity,
                'measured_at' => $latestMeasurement->measured_at,
            ]
        ]);
    }
}
