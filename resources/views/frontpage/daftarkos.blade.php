<x-home-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $title }}</h1>

        {{-- Form pencarian memakai method GET agar kata kunci tampil di alamat URL --}}
        <form action="{{ route('kos.index') }}" method="GET" class="flex flex-col md:flex-row gap-2 mb-6">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari nama kos atau daerah..."
                   class="flex-1 px-4 py-2 border rounded-lg bg-white">

            <select name="tipe" class="px-4 py-2 border rounded-lg bg-white">
                <option value="">Semua</option>
                @foreach (['Putra', 'Putri', 'Campur'] as $t)
                    <option value="{{ $t }}" {{ request('tipe') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">Cari</button>
        </form>

        {{-- @forelse = foreach + pesan jika data kosong (conditional rendering) --}}
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($kosList as $item)
                <x-kos-card :k="$item" />
            @empty
                <p class="text-gray-500">Kos tidak ditemukan.</p>
            @endforelse
        </div>
    </div>
</x-home-layout>