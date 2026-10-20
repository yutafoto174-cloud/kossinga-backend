<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory;

    public $primaryKey = 'id_pemilik';

    protected $fillable = ['nama_pemilik', 'no_wa'];
    public function kos()
{
    return $this->hasMany(Kos::class, 'id_pemilik', 'id_pemilik');
}
}