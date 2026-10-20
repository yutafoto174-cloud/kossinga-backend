<x-home-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $title }}</h1>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($kosList as $item)
                <x-kos-card :k="$item" />
            @empty
                <p class="text-gray-600">
                    Belum ada kos favorit.
                    <a href="{{ route('kos.index') }}" class="text-blue-600">Cari kos</a>
                </p>
            @endforelse
        </div>
    </div>
</x-home-layout>