<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KosResource extends JsonResource
{
    /**
     * Mengubah satu data Kos menjadi array yang akan dijadikan JSON.
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id_kos,
            'name'       => $this->nama,
            'slug'       => $this->slug,
            'price'      => (int) $this->harga,
            'area'       => $this->daerah,
            'type'       => $this->tipe,
            // "WiFi, AC" (teks di database) -> ["WiFi", "AC"] (array JSON)
            'facilities' => $this->fasilitas
                                ? array_map('trim', explode(',', $this->fasilitas))
                                : [],
            // 'Y' / 'N' di database -> true / false
            'available'  => $this->status === 'Y',
            'img'        => $this->foto ? asset($this->foto) : null,
            // tampil hanya jika relasi pemilik sudah dimuat dengan with('pemilik')
            'owner'      => $this->whenLoaded('pemilik', function () {
                return [
                    'id'   => $this->pemilik->id_pemilik,
                    'name' => $this->pemilik->nama_pemilik,
                    'wa'   => $this->pemilik->no_wa,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}