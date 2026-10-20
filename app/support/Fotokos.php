<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FotoKos
{
    const DISK   = 'public';
    const FOLDER = 'uploads/kos';

    /**
     * Menyimpan file, mengembalikan PATH relatif untuk database.
     * Nama file selalu baru, jadi URL berubah setiap foto diganti
     * (browser tidak menampilkan gambar lama dari cache).
     */
    public static function simpan(UploadedFile $file): string
    {
        $namaFile = time() . '_' . Str::random(6) . '.' . $file->extension();

        return $file->storeAs(self::FOLDER, $namaFile, self::DISK);
    }

    /**
     * Menghapus file lama (aman jika path kosong atau file sudah tidak ada).
     */
    public static function hapus(?string $path): void
    {
        if ($path) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * Mengubah PATH menjadi URL publik. SATU-SATUNYA tempat URL dibuat.
     */
    public static function url(?string $path): ?string
    {
        return $path ? asset($path) : null;
    }
}