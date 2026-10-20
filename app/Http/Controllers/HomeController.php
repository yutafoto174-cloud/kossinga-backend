<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;
class HomeController extends Controller
{
    /**
     * Data dummy kos (dipindahkan dari data.js pada prototipe React).
     * Dibungkus collect() agar bisa memakai fungsi collection (filter, take, dll).
     */
    private function dataKos()
{
    return Kos::with('pemilik')->get()->map(function ($k) {
        return (object) [
            'id'         => $k->id_kos,
            'name'       => $k->nama,
            'slug'       => $k->slug,
            'price'      => $k->harga,
            'area'       => $k->daerah,
            'type'       => $k->tipe,
            'available'  => $k->status === 'Y',
            'facilities' => $k->fasilitas ? array_map('trim', explode(',', $k->fasilitas)) : [],
            // foto dari database; jika belum ada, pakai gambar pengganti
            'img'        => $k->foto_url ?? 'https://picsum.photos/seed/kos' . $k->id_kos . '/400/250',
            'owner'      => $k->pemilik->nama_pemilik ?? '-',
            'wa'         => $k->pemilik->no_wa ?? '',
            'deposit'    => $k->harga,
            'rating'     => '-',   // belum ada kolom rating di database
            'rules'      => [],    // belum ada kolom aturan di database
        ];
    });
}
    // Halaman Beranda / Landing Page
    public function index()
    {
        // take(3) mengambil 3 data pertama sebagai "Kos Terbaru"
        return view('frontpage.landingpage', [
            'title'   => 'Landing Page',
            'kosList' => $this->dataKos()->take(3),
        ]);
    }

    // Halaman Cari Kos (daftar semua kos + pencarian sederhana)
    public function daftar(Request $request)
    {
        $title   = 'Cari Kos di Singaraja';
        $kosList = $this->dataKos();

        // Jika ada kata kunci ?q=... filter berdasarkan nama atau daerah
        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $kosList = $kosList->filter(fn ($k) => str_contains(strtolower($k->name . ' ' . $k->area), $q));
        }

        // Jika ada pilihan tipe ?tipe=Putri filter berdasarkan tipe
        if ($request->filled('tipe')) {
            $kosList = $kosList->where('type', $request->tipe);
        }

        return view('frontpage.daftarkos', compact('title', 'kosList'));
    }

    // Halaman Detail Kos, dipanggil lewat /kos/{slug}
    public function detail($slug)
    {
        $kos = $this->dataKos()->firstWhere('slug', $slug);

        // Jika slug tidak ditemukan, tampilkan halaman 404
        abort_if(! $kos, 404);

        return view('frontpage.detailkos', ['title' => $kos->name, 'kos' => $kos]);
    }

    // Halaman Favorit (versi statis: 2 kos contoh)
    public function favorit()
    {
        $title   = 'Kos Favorit Saya';
        $kosList = $this->dataKos()->whereIn('id', [1, 4]);

        return view('frontpage.favorit', compact('title', 'kosList'));
    }
}