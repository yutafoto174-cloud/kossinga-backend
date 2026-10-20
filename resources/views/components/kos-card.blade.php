@props(['k'])

<div class="border rounded-lg overflow-hidden shadow hover:shadow-lg bg-white">
    <img src="{{ $k->img }}" alt="{{ $k->name }}" class="w-full h-40 object-cover">
    <div class="p-4">
        <div class="flex justify-between items-start">
            <h2 class="font-semibold">{{ $k->name }}</h2>

            {{-- Conditional rendering: status ketersediaan --}}
            @if ($k->available)
                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Tersedia</span>
            @else
                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Penuh</span>
            @endif
        </div>

        <p class="text-gray-600 text-sm">{{ $k->type }} • {{ $k->area }} • ★ {{ $k->rating }}</p>
        <p class="font-bold mt-1">
            Rp {{ number_format($k->price, 0, ',', '.') }}<span class="text-sm font-normal"> /bulan</span>
        </p>

        <a href="{{ route('kos.detail', $k->slug) }}" class="text-blue-600 hover:underline mt-2 block">
            Lihat Detail
        </a>
    </div>
</div>