<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KontainerMasuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('kontainer_masuk', function (Blueprint $table) {
          $table->bigIncrements("ID");
          $table->string('NO_KONTAINER', 20);
          $table->string('NOPOL',15)->default("");
          $table->unsignedInteger('GUDANG_ID')->nullable();
          $table->date('TGL_MASUK')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kontainer_masuk');
    }
}
