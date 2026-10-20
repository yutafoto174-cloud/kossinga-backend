<x-admin-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $title }}</h2>

    <div class="shadow px-6 py-4 bg-white rounded sm:px-1 sm:py-1 mt-4">
        <div class="container mx-auto">

            @if (session('gagal'))
                <div class="m-4 p-3 rounded bg-red-100 text-red-700 text-sm">{{ session('gagal') }}</div>
            @endif

            <!-- action: route update jika ada $kos, route store jika tambah baru -->
            <!-- enctype multipart/form-data wajib karena ada upload file -->
            <form enctype="multipart/form-data"
                  action="{{ isset($kos) ? route('admin.kos.update', $kos->id_kos) : route('admin.kos.store') }}"
                  method="POST">
                <!-- token keamanan -->
                @csrf
                <!-- method PUT untuk update (form HTML hanya mengenal GET dan POST) -->
                @if (isset($kos)) @method('PUT') @endif

                <div class="px-2 py-8 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">

                        <!-- NAMA -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kos</label>
                            <!-- old() menyimpan isian sebelumnya; jika kosong pakai data $kos (mode edit) -->
                            <input type="text" id="nama" name="nama" placeholder="Contoh: Kos Putri Asri"
                                   value="{{ old('nama', $kos->nama ?? '') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <!-- pesan error untuk field nama -->
                            @error('nama') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                        <!-- HARGA -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga per Bulan (Rp)</label>
                            <input type="number" id="harga" name="harga" placeholder="650000"
                                   value="{{ old('harga', $kos->harga ?? '') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('harga') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                        <!-- DAERAH -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="daerah" class="block text-sm font-medium text-gray-700">Daerah</label>
                            <input type="text" id="daerah" name="daerah" placeholder="Contoh: Jineng Dalem"
                                   value="{{ old('daerah', $kos->daerah ?? '') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('daerah') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                        <!-- TIPE -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe</label>
                            <select id="tipe" name="tipe"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih Tipe</option>
                                @foreach (['Putra', 'Putri', 'Campur'] as $t)
                                    <option value="{{ $t }}" {{ old('tipe', $kos->tipe ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                            @error('tipe') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                        <!-- PEMILIK (dari tabel pemiliks) -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="id_pemilik" class="block text-sm font-medium text-gray-700">Pemilik</label>
                            <select id="id_pemilik" name="id_pemilik"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih Pemilik</option>
                                <!-- menampilkan list pemilik -->
                                @foreach ($pemiliks as $p)
                                    <option value="{{ $p->id_pemilik }}"
                                        {{ old('id_pemilik', $kos->id_pemilik ?? '') == $p->id_pemilik ? 'selected' : '' }}>
                                        {{ $p->nama_pemilik }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pemilik') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                        <!-- FASILITAS -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas (pisahkan dengan koma)</label>
                            <input type="text" id="fasilitas" name="fasilitas" placeholder="WiFi, AC, Parkir motor"
                                   value="{{ old('fasilitas', $kos->fasilitas ?? '') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('fasilitas') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                        <!-- FOTO -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                            <!-- mode edit: tampilkan foto saat ini -->
                            @if (isset($kos) && $kos->foto)
                                <img src="{{ asset($kos->foto) }}" alt="" class="h-16 w-16 rounded object-cover my-2">
                            @endif
                            <input type="file" id="foto" name="foto"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm sm:text-sm">
                            @if (isset($kos))
                                <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengganti foto.</p>
                            @endif
                            @error('foto') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                        <!-- STATUS -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            @php $statusNow = old('status', $kos->status ?? 'Y'); @endphp
                            <input type="radio" name="status" value="Y" {{ $statusNow == 'Y' ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 border-gray-300"> Tersedia &nbsp;&nbsp;
                            <input type="radio" name="status" value="N" {{ $statusNow == 'N' ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 border-gray-300"> Penuh
                            @error('status') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                <hr>
                <div class="px-4 py-3 bg-white text-right sm:px-6">
                    <a href="{{ route('admin.kos.index') }}" class="px-4 py-2 text-sm text-gray-600">Batal</a>
                    <button type="submit"
                            class="inline-flex justify-center w-24 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md bg-indigo-600 hover:bg-indigo-700 text-white">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>