<x-home-layout>
    <div class="max-w-6xl mx-auto p-6 grid md:grid-cols-3 gap-6">

        <section class="md:col-span-2 space-y-4">
            <div class="border rounded-lg bg-white overflow-hidden shadow">
                <img src="{{ $kos->img }}" alt="{{ $kos->name }}" class="w-full h-60 object-cover">
                <div class="p-4">
                    <h1 class="text-2xl font-bold">{{ $kos->name }}</h1>
                    <p class="text-gray-600">{{ $kos->type }} • {{ $kos->area }} • ★ {{ $kos->rating }}</p>
                    <p class="font-bold text-lg mt-2">Rp {{ number_format($kos->price, 0, ',', '.') }} /bulan</p>
                    <p class="mt-2 text-sm">Fasilitas: {{ implode(', ', $kos->facilities) }}</p>
                    <p class="mt-1 text-sm">
                        Pemilik: {{ $kos->owner }} • Deposit: Rp {{ number_format($kos->deposit, 0, ',', '.') }}
                    </p>

                    <h3 class="mt-3 font-semibold text-sm">Aturan kos:</h3>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($kos->rules as $rule)
                            <li>{{ $rule }}</li>
                        @endforeach
                    </ul>

                    {{-- Conditional rendering: tombol sesuai ketersediaan --}}
                    @if ($kos->available)
                        <a href="https://wa.me/{{ $kos->wa }}?text={{ urlencode('Halo, saya tertarik dengan ' . $kos->name) }}"
                           target="_blank" rel="noreferrer"
                           class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded-lg">
                            Hubungi via WhatsApp
                        </a>
                    @else
                        <button disabled class="mt-4 px-4 py-2 bg-gray-300 text-gray-500 rounded-lg">
                            Kamar Penuh
                        </button>
                    @endif
                </div>
            </div>

            <h2 class="text-xl font-semibold">Ulasan Penghuni</h2>
            <p class="text-gray-500">Belum ada ulasan.</p>
        </section>

        <aside class="border rounded-lg p-4 bg-white shadow h-fit">
            <h2 class="text-xl font-semibold mb-2">Kembali</h2>
            <a href="{{ route('kos.index') }}" class="text-blue-600">← Lihat kos lainnya</a>
        </aside>

    </div>
</x-home-layout>