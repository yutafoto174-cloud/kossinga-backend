<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToKosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->foreign('id_pemilik')
                ->references('id_pemilik')->on('pemiliks')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->dropForeign(['id_pemilik']);
        });
    }
}
