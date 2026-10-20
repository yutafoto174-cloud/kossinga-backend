<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KosResource;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KosController extends Controller
{
    /**
     * Mencari kos berdasarkan id; jika tidak ada, kirim JSON 404.
     */
    private function cari($id)
    {
        $kos = Kos::with('pemilik')->find($id);

        if (! $kos) {
            abort(response()->json(['message' => 'Kos tidak ditemukan'], 404));
        }

        return $kos;
    }

    /**
     * Membuat slug unik dari nama kos.
     */
    private function buatSlug($nama, $abaikanId = null)
    {
        $slug = Str::slug($nama);

        $sudahAda = Kos::where('slug', $slug)
            ->when($abaikanId, fn ($q) => $q->where('id_kos', '!=', $abaikanId))
            ->exists();

        return $sudahAda ? $slug . '-' . time() : $slug;
    }

    /**
     * Aturan validasi (dipakai store dan update).
     */
    private function aturan()
    {
        return [
            'name'       => 'required|string|max:255',
            'price'      => 'required|integer|min:0',
            'area'       => 'required|string|max:100',
            'type'       => 'required|in:Putra,Putri,Campur',
            'facilities' => 'nullable|string|max:255',
            'available'  => 'required|boolean',
            'owner_id'   => 'required|exists:pemiliks,id_pemilik',
            // file gambar jpg/jpeg/png, maksimal 2048 KB (2 MB)
            'img'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Memetakan field API ke kolom database (foto ditangani terpisah).
     */
    private function petakan(Request $request)
    {
        return [
            'id_pemilik' => $request->owner_id,
            'nama'       => $request->name,
            'harga'      => $request->price,
            'daerah'     => $request->area,
            'tipe'       => $request->type,
            'fasilitas'  => $request->facilities,
            'status'     => $request->boolean('available') ? 'Y' : 'N',
        ];
    }

    /**
     * GET /api/kos : daftar kos (pencarian, filter, pagination).
     */
    public function index(Request $request)
    {
        $query = Kos::with('pemilik');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%$q%")
                  ->orWhere('daerah', 'like', "%$q%");
            });
        }
        if ($request->filled('type')) {
            $query->where('tipe', $request->type);
        }
        if ($request->filled('owner_id')) {
            $query->where('id_pemilik', $request->owner_id);
        }

        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        return KosResource::collection($query->paginate($perPage)->withQueryString());
    }

    /**
     * POST /api/kos : menambah kos (boleh dengan foto).
     */
    public function store(Request $request)
    {
        $request->validate($this->aturan());

        $data = $this->petakan($request);
        $data['slug'] = $this->buatSlug($request->name);

        // jika ada file img: simpan ke folder uploads/kos pada disk public,
        // lalu hanya PATH-nya yang disimpan ke database
        if ($request->hasFile('img')) {
            $data['foto'] = $request->file('img')->store('uploads/kos', 'public');
        }

        $kos = Kos::create($data);

        return (new KosResource($kos->load('pemilik')))
            ->additional(['message' => 'Kos berhasil ditambahkan']);
    }

    /**
     * GET /api/kos/{id} : detail satu kos.
     */
    public function show(string $id)
    {
        return new KosResource($this->cari($id));
    }

    /**
     * PUT /api/kos/{id} : mengubah kos.
     */
    public function update(Request $request, string $id)
    {
        $kos = $this->cari($id);

        $request->validate($this->aturan());

        $data = $this->petakan($request);
        if ($request->name !== $kos->nama) {
            $data['slug'] = $this->buatSlug($request->name, $kos->id_kos);
        }

        // foto diganti hanya jika ada file baru
        if ($request->hasFile('img')) {
            // hapus foto lama agar tidak menumpuk
            if ($kos->foto) {
                Storage::disk('public')->delete($kos->foto);
            }
            $data['foto'] = $request->file('img')->store('uploads/kos', 'public');
        }

        $kos->update($data);

        return (new KosResource($kos->load('pemilik')))
            ->additional(['message' => 'Kos berhasil diubah']);
    }

    /**
     * DELETE /api/kos/{id} : menghapus kos beserta file fotonya.
     */
    public function destroy(string $id)
    {
        $kos = $this->cari($id);

        if ($kos->foto) {
            Storage::disk('public')->delete($kos->foto);
        }
        $kos->delete();

        return response()->json(['message' => 'Kos berhasil dihapus']);
    }
}