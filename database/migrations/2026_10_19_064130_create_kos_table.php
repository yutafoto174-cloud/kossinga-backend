<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id('id_kos');                       // primary key
            $table->foreignId('id_pemilik');            // foreign key ke pemiliks
            $table->string('nama');
            $table->string('slug')->unique();           // harus unik (dipakai di URL)
            $table->integer('harga');
            $table->string('daerah', 100);
            $table->string('tipe', 10);                 // Putra / Putri / Campur
            $table->string('fasilitas')->nullable();
            $table->string('foto', 100)->nullable();
            $table->char('status', 1)->default('Y');    // Y = Tersedia, N = Penuh
            $table->timestamps();                       // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('kos');
    }
};