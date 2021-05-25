<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('tbl_header_pengeluaran', function (Blueprint $table) {
              $table->bigIncrements("ID");
              $table->unsignedInteger("ID_HEADER")->index();
              $table->date("TGL_KIRIM")->nullable();
              $table->text("CATATAN")->nullable();
          });

          Schema::create('tbl_detail_pengeluaran', function (Blueprint $table) {
              $table->bigIncrements("ID");
              $table->unsignedInteger("ID_HEADER")->index();
              $table->string("NOPOL")->nullable();
              $table->string("NOSJ")->nullable();
              $table->unsignedInteger("EKSPEDISI")->nullable();
              $table->unsignedInteger("JENISTRUK")->nullable();
              $table->string("SOPIR")->nullable();
              $table->decimal("JMLROLL")->nullable();
              $table->date("TGL_KELUAR")->nullable();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_detail_pengeluaran');
        Schema::dropIfExists('tbl_header_pengeluaran');
    }
}
