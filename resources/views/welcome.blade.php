<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TrackTempHum - Sıcaklık & Nem Takip Sistemi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/build/assets/app.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full mx-auto p-8 bg-gray-800 rounded-2xl shadow-lg text-center">
        <div class="mb-6">
            <!-- Özel logo veya ikon -->
            <svg class="mx-auto h-16 w-16 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <circle cx="24" cy="24" r="22" stroke-width="4" class="text-green-600" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 32c2-4 8-4 10 0m-5-8v-8m0 0a4 4 0 118 0v8" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold mb-2">TrackTempHum</h1>
        <p class="text-lg text-gray-300 mb-6">Sıcaklık & Nem Takip Sistemi</p>
        <a href="{{ route('login') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">Giriş Yap</a>
    </div>
</body>
</html>
