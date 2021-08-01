<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('deskripsi');
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->unsignedBigInteger('rw_id')->nullable();
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->timestamps();

            $table->foreign('rt_id')->references('id')->on('rukun_tetanggas');
            $table->foreign('rw_id')->references('id')->on('rukun_wargas');
            $table->foreign('desa_id')->references('id')->on('desas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatans');
    }
}
