<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEkspedisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ekspedisi', function (Blueprint $table) {
            $table->bigIncrements("EKSPEDISI_ID");
            $table->string("NAMA");
            $table->string("ALAMAT");
            $table->string("TELEPON", 50);
            $table->string("KODE", 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ekspedisi');
    }
}
