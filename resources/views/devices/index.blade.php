<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cihazlarım') }}
            </h2>
            <a href="{{ route('devices.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Cihaz Ekle
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Mevcut Cihazlarım -->
            @if($userDevices->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cihazlarım</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($userDevices as $userDevice)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $userDevice->device->name }}</h4>
                                        <span class="text-sm text-gray-500">{{ $userDevice->device->measurements_count }} ölçüm</span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Seri No:</span> {{ $userDevice->device->serial_number }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Eklenme:</span> {{ $userDevice->created_at->format('d.m.Y') }}
                                        </p>
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('devices.show', $userDevice->device) }}" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-4 rounded text-sm">
                                            Görüntüle
                                        </a>
                                        <form action="{{ route('devices.destroy', $userDevice->device) }}" method="POST" class="inline" onsubmit="return confirm('Bu cihazı kaldırmak istediğinizden emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded text-sm">
                                                Kaldır
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Kullanılabilir Cihazlar -->
            @if($availableDevices->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kullanılabilir Cihazlar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($availableDevices as $device)
                                <div class="border rounded-lg p-4">
                                    <div class="mb-4">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $device->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Seri No:</span> {{ $device->serial_number }}
                                        </p>
                                    </div>

                                    <form action="{{ route('devices.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="device_id" value="{{ $device->id }}">
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Cihazı Ekle
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                @if($userDevices->count() == 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz cihaz yok</h3>
                            <p class="mt-1 text-sm text-gray-500">Admin tarafından eklenen cihazlar burada görünecektir.</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout> 