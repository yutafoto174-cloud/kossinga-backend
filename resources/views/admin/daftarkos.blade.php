<!-- memanggil layout admin -->
<x-admin-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $title }}</h2>

    @if (session('sukses'))
        <div class="my-4 p-3 rounded bg-green-100 text-green-700 text-sm">{{ session('sukses') }}</div>
    @endif
    @if (session('gagal'))
        <div class="my-4 p-3 rounded bg-red-100 text-red-700 text-sm">{{ session('gagal') }}</div>
    @endif

    <div class="shadow px-6 py-4 bg-white rounded sm:px-1 sm:py-1 mt-4">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4">
            <div>
                <a href="{{ route('admin.kos.create') }}">
                    <button class="px-4 py-1 text-sm rounded text-purple-600 font-semibold border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none">
                        Tambah
                    </button>
                </a>
            </div>

            <div>
                <!-- PENCARIAN -->
                <!-- method GET lebih baik untuk pencarian: kata kunci terekam di address bar -->
                <form action="{{ route('admin.kos.index') }}" method="GET">
                    <div class="flex">
                        <!-- filter pemilik -->
                        <select name="id_pemilik"
                                class="block w-full py-2 px-4 border border-gray-300 bg-white rounded-l-2xl shadow-sm focus:outline-none sm:text-sm">
                            <option value="">Semua Pemilik</option>
                            @foreach ($pemiliks as $p)
                                <option value="{{ $p->id_pemilik }}"
                                    {{ request('id_pemilik') == $p->id_pemilik ? 'selected' : '' }}>
                                    {{ $p->nama_pemilik }}
                                </option>
                            @endforeach
                        </select>
                        <!-- kata kunci dengan nama "s" -->
                        <input type="text" name="s" value="{{ request('s') }}" placeholder="Cari nama / daerah..."
                               class="px-4 py-2 sm:text-sm border border-gray-300 w-full">
                        <button type="submit"
                                class="rounded-r-md border border-l-0 px-4 py-1 border-gray-300 bg-gray-50 text-blue-600 hover:text-white hover:bg-blue-600">
                            Cari
                        </button>
                    </div>
                </form>
                <!-- END PENCARIAN -->
            </div>
        </div>

        <div class="py-2 align-middle inline-block min-w-full sm:px-4 lg:px-4">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($kosList as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if (!empty($item->foto))
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset($item->foto) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($item->nama, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->daerah }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500">{{ $item->tipe }}</div>
                            </td>

                            <!-- PEMILIK: nama berupa link ke daftar kos milik pemilik tsb -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('admin.kos.pemilik', $item->id_pemilik) }}" class="text-blue-600 hover:underline">
                                    {{ $item->pemilik->nama_pemilik ?? '-' }}
                                </a>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($item->status == 'Y')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Tersedia</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Penuh</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.kos.destroy', $item->id_kos) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('admin.kos.edit', $item->id_kos) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <button class="text-red-600 hover:text-red-900 ml-2"
                                            onclick="return confirm('Anda Yakin ?')" type="submit">Del</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500">Data kos tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        <!-- hanya tampil jika datanya hasil paginate() -->
        @if (method_exists($kosList, 'links'))
            <!-- appends(request()->query()) membawa kata kunci dan filter ke halaman berikutnya -->
            <div class="m-4">{{ $kosList->appends(request()->query())->links() }}</div>
        @endif
        <!-- END PAGINATION -->

    </div>
</x-admin-layout>