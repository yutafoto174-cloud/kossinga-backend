<x-home-layout>
    {{-- Bagian ajakan (Call to Action) --}}
    <section class="bg-blue-50 py-16 px-6 text-center">
        <h1 class="text-4xl font-bold text-gray-900">Cari Kos di Singaraja</h1>
        <p class="mt-3 text-gray-600 max-w-xl mx-auto">
            Temukan kos putra, putri, atau campur dekat kampus Undiksha. Hubungi pemilik langsung lewat WhatsApp.
        </p>
        <a href="{{ route('kos.index') }}"
           class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700">
            Cari Kos Sekarang
        </a>
    </section>

    {{-- Kos terbaru --}}
    <section class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-baseline mb-4">
            <h2 class="text-2xl font-bold">Kos Terbaru</h2>
            <a href="{{ route('kos.index') }}" class="text-blue-600">Lihat semua →</a>
        </div>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($kosList as $item)
                <x-kos-card :k="$item" />
            @endforeach
        </div>
    </section>
</x-home-layout>