<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\FotoKos;

class Kos extends Model
{
    use HasFactory;

    // nama tabel ditulis eksplisit
    protected $table = 'kos';

    // primary key bukan "id", jadi harus disebutkan
    public $primaryKey = 'id_kos';

    // kolom yang boleh diisi lewat create() dan update()
    protected $fillable = [
        'id_pemilik', 'nama', 'slug', 'harga', 'daerah',
        'tipe', 'fasilitas', 'foto', 'status',
    ];

    public function pemilik()
{
    // belongsTo(ModelTujuan, foreign key di tabel kos, primary key di tabel pemiliks)
    return $this->belongsTo(Pemilik::class, 'id_pemilik', 'id_pemilik');
}
// pemanggilan: $kos->foto_url  (mengambil dari kolom foto)
public function getFotoUrlAttribute()
{
    return FotoKos::url($this->foto);
}
}
