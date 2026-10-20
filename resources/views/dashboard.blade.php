<x-admin-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>

    <!-- Kartu ringkasan -->
    <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Total Kos</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalKos }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Tersedia</p>
            <p class="text-3xl font-bold text-green-600">{{ $tersedia }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Penuh</p>
            <p class="text-3xl font-bold text-red-600">{{ $penuh }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Pemilik</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalPemilik }}</p>
        </div>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Kelola data kos KosSinga dari sini.</p>
        <div class="mt-4 flex gap-3">
            <a href="{{ route('admin.kos.index') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Kelola Data Kos</a>
            <a href="{{ route('admin.kos.create') }}"
               class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">+ Tambah Kos</a>
        </div>
    </div>
</x-admin-layout>