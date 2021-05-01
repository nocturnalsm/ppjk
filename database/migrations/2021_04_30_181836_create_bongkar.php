<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBongkar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_header_bongkar', function (Blueprint $table) {
            $table->bigIncrements("ID");
            $table->unsignedInteger("ID_HEADER")->index();
            $table->date("TGL_BONGKAR")->nullable();
            $table->unsignedInteger("GUDANG_ID")->index();
            $table->char("HASIL_BONGKAR",1)->nullable();
            $table->text("CATATAN")->nullable();
        });

        Schema::create('tbl_detail_bongkar', function (Blueprint $table) {
            $table->bigIncrements("ID");
            $table->unsignedInteger("ID_HEADER")->index();
            $table->unsignedInteger("KODEBARANG")->index();
            $table->decimal("JMLKEMASANBONGKAR")->nullable();
            $table->decimal("JMLSATHARGABONGKAR")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_detail_bongkar');
        Schema::dropIfExists('tbl_header_bongkar');
    }
}
