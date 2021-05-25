<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKonversistokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konversistok', function (Blueprint $table) {
            $table->bigIncrements("ID");
            $table->unsignedInteger("KODEBARANG")->index();
            $table->unsignedInteger("PRODUK_ID")->index();
            $table->decimal("JMLSATKONVERSI")->nullable();
            $table->unsignedInteger("SATUAN_ID")->nullable();
            $table->date("TGL_KONVERSI")->nullable();
            $table->decimal("TAX")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konversistok');
    }
}
