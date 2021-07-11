<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_detail', function (Blueprint $table) {
            $table->bigIncrements("ID");
            $table->unsignedInteger("ID_HEADER")->index();
            $table->string("INV_BILLING")->nullable();
            $table->date("TGL_INV_BILLING")->nullable();
            $table->decimal("NOMINAL",13,2)->default(0);
            $table->decimal("TAX", 13,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_detail');
    }
}
