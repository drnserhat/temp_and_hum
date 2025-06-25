<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $device->name }}
            </h2>
            <a href="{{ route('devices.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Geri Dön
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cihaz Bilgileri -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cihaz Bilgileri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Seri No:</span> {{ $device->serial_number }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Eklenme Tarihi:</span> {{ $device->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Toplam Ölçüm:</span> {{ $device->measurements->count() }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Son Güncelleme:</span> 
                                <span id="last-update">
                                    {{ $latestMeasurement ? $latestMeasurement->measured_at->format('d.m.Y H:i') : 'Henüz veri yok' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Canlı Veriler -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Sıcaklık</h3>
                            <div class="p-2 rounded-full bg-red-100">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-red-600" id="temperature-display">
                                {{ $latestMeasurement ? $latestMeasurement->temperature : '--' }}°C
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Nem</h3>
                            <div class="p-2 rounded-full bg-blue-100">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-blue-600" id="humidity-display">
                                {{ $latestMeasurement ? $latestMeasurement->humidity : '--' }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Son Ölçümler Tablosu -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Son Ölçümler</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sıcaklık</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nem</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="measurements-table">
                                @foreach($measurements as $measurement)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $measurement->temperature }}°C
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $measurement->humidity }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $measurement->measured_at->format('d.m.Y H:i:s') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Canlı veri güncelleme (5 saniyede bir)
        function updateLiveData() {
            fetch(`/api/measurements/latest?serial_number={{ $device->serial_number }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('temperature-display').textContent = data.data.temperature + '°C';
                        document.getElementById('humidity-display').textContent = data.data.humidity + '%';
                        document.getElementById('last-update').textContent = new Date(data.data.measured_at).toLocaleString('tr-TR');
                    }
                })
                .catch(error => {
                    console.log('Veri güncellenirken hata oluştu:', error);
                });
        }

        // Her 5 saniyede bir veri güncelle
        setInterval(updateLiveData, 5000);
    </script>
</x-app-layout> 