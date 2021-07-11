<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order', function (Blueprint $table) {
            $table->bigIncrements("ID");
            $table->string("JOB_ORDER")->index();
            $table->date("TGL_JOB")->nullable();
            $table->unsignedInteger("JENIS_DOK")->index();
            $table->date("TGL_DOK")->nullable();
            $table->string("NO_DOK", 6)->nullable();
            $table->unsignedInteger("CUSTOMER")->nullable();
            $table->date("TGL_TIBA")->nullable();
            $table->string("JML_KONTAINER")->nullable();
            $table->string("NOPEN", 6)->nullable();
            $table->string("NOAJU", 6)->nullable();
            $table->date("TGL_NOPEN")->nullable();
            $table->date("TGl_SPPB")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order');
    }
}
