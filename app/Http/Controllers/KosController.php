<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Support\FotoKos;

class KosController extends Controller
{
    /**
     * Pesan kesalahan validasi (bahasa Indonesia).
     */
    private function pesan()
    {
        return [
            'required'      => 'Kolom :attribute harus diisi',
            'numeric'       => 'Kolom :attribute harus berupa angka',
            'in'            => 'Pilihan :attribute tidak valid',
            'exists'        => 'Pemilik tidak ditemukan',
            'foto.required' => 'Foto kos harus diunggah',
            'foto.mimes'    => 'Foto harus berformat png, jpg, atau jpeg',
            'foto.max'      => 'Ukuran foto maksimal 1 MB',
        ];
    }

    /**
     * Menyimpan file foto ke public/uploads/kos, mengembalikan path-nya.
     */
    private function simpanFoto($file)
    {
        // nama file = waktu + nama asli yang dirapikan (tanpa spasi)
        $nama = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = time() . '_' . Str::slug($nama) . '.' . $file->getClientOriginalExtension();

        // storeAs(folder, nama file, disk) -> mengembalikan 'uploads/kos/namafile.jpg'
        return $file->storeAs('uploads/kos', $fileName, 'public');
    }

    /**
     * READ: halaman daftar kos.
     */
    public function index(Request $request)
    {
        $title = 'Daftar Kos';

        // query dasar; with('pemilik') memuat relasi sekaligus (lebih hemat query)
        $query = Kos::with('pemilik');

        // jika ada kata kunci (?s=...), cari pada nama ATAU daerah
        if ($request->filled('s')) {
            $s = $request->s;
            // where(function) mengelompokkan kondisi OR supaya tidak merusak filter lain
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%$s%")
                ->orWhere('daerah', 'like', "%$s%");
            });
        }

        // jika ada filter pemilik (?id_pemilik=...)
        if ($request->filled('id_pemilik')) {
            $query->where('id_pemilik', $request->id_pemilik);
        }

        // eksekusi query dengan pagination 3 data per halaman
        $kosList = $query->paginate(3);

        // daftar pemilik untuk isi dropdown filter
        $pemiliks = Pemilik::all();

        return view('admin.daftarkos', compact('title', 'kosList', 'pemiliks'));
    }

    /**
     * CREATE: menampilkan halaman input data.
     */
    public function create()
    {
        $title = 'Tambah Kos';
        // mengambil daftar pemilik untuk pilihan (dropdown)
        $pemiliks = Pemilik::all();

        return view('admin.inputkos', compact('title', 'pemiliks'));
    }

    /**
     * CREATE: menyimpan data baru dari form.
     */
    public function store(Request $request)
    {
        // validasi: jika gagal, Laravel otomatis kembali ke form membawa pesan error
        $validasi = $request->validate([
            'nama'       => 'required',
            'harga'      => 'required|numeric|min:0',
            'daerah'     => 'required',
            'tipe'       => 'required|in:Putra,Putri,Campur',
            'fasilitas'  => 'nullable',
            'foto'       => 'required|mimes:png,jpg,jpeg|max:1024',
            'id_pemilik' => 'required|exists:pemiliks,id_pemilik',
            'status'     => 'required|in:Y,N',
        ], $this->pesan());

        try {
            // upload foto, lalu simpan path-nya ke kolom foto
            // sebelumnya: $validasi['foto'] = $this->simpanFoto($request->file('foto'));
            $validasi['foto'] = FotoKos::simpan($request->file('foto'));

            // slug dibuat otomatis dari nama; jika sudah dipakai, tambahkan waktu
            $slug = Str::slug($validasi['nama']);
            if (Kos::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }
            $validasi['slug'] = $slug;

            Kos::create($validasi);

            return redirect()->route('admin.kos.index')->with('sukses', 'Data kos berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('gagal', $e->getMessage());
        }
    }

    /**
     * Detail data (tidak dipakai di back page, dialihkan ke halaman edit).
     */
    public function show($id)
    {
        return redirect()->route('admin.kos.edit', $id);
    }

    /**
     * UPDATE: menampilkan form edit.
     */
    public function edit($id)
    {
        // findOrFail: jika id tidak ada, tampil halaman 404
        $kos = Kos::findOrFail($id);
        $title = 'Edit Data ' . $kos->nama;
        $pemiliks = Pemilik::all();

        return view('admin.inputkos', compact('title', 'pemiliks', 'kos'));
    }

    /**
     * UPDATE: menyimpan perubahan (method PUT).
     */
    public function update(Request $request, $id)
    {
        $validasi = $request->validate([
            'nama'       => 'required',
            'harga'      => 'required|numeric|min:0',
            'daerah'     => 'required',
            'tipe'       => 'required|in:Putra,Putri,Campur',
            'fasilitas'  => 'nullable',
            'foto'       => 'nullable|mimes:png,jpg,jpeg|max:1024',
            'id_pemilik' => 'required|exists:pemiliks,id_pemilik',
            'status'     => 'required|in:Y,N',
        ], $this->pesan());

        $kos = Kos::findOrFail($id);

        try {
            // foto hanya diganti jika ada file baru
            unset($validasi['foto']);
            // sebelumnya: if ($kos->foto) { File::delete(public_path($kos->foto)); } ...
        if ($request->hasFile('foto')) {
            FotoKos::hapus($kos->foto);                                  // hapus foto lama
            $validasi['foto'] = FotoKos::simpan($request->file('foto')); // simpan foto baru
        }
                $kos->update($validasi);

                return redirect()->route('admin.kos.index')->with('sukses', 'Data kos berhasil diubah');
            } catch (\Exception $e) {
                return back()->withInput()->with('gagal', $e->getMessage());
            }
        }

    /**
     * DELETE: menghapus data berdasarkan primary key.
     */
    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);

        try {
            // hapus file foto dari folder public
            // sebelumnya: if ($kos->foto) { File::delete(public_path($kos->foto)); }
            FotoKos::hapus($kos->foto);
            $kos->delete();
            

            return redirect()->route('admin.kos.index')->with('sukses', 'Data kos berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('gagal', $e->getMessage());
        }
    }
    /**
 * Menampilkan daftar kos berdasarkan pemilik (memakai relasi hasMany).
 */
    public function pemilik($id)
    {
        // memanggil pemilik berdasarkan id_pemilik
        $pemilik = Pemilik::findOrFail($id);

        // nama pemilik ditampilkan pada judul halaman
        $title = 'Kos milik ' . $pemilik->nama_pemilik;

        // memanggil daftar kos milik pemilik (relasi hasMany: fungsi kos() di model Pemilik)
        $kosList = $pemilik->kos;

        $pemiliks = Pemilik::all();

        return view('admin.daftarkos', compact('title', 'kosList', 'pemiliks'));
    }
}