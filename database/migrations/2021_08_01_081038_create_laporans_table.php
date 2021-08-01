<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengirim_id')->nullable();
            $table->unsignedBigInteger('penerima_id')->nullable();
            $table->string('judul');
            $table->string('detail_laporan');
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->unsignedBigInteger('rw_id')->nullable();
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->boolean('status_selesai');
            $table->timestamps();

            $table->foreign('pengirim_id')->references('id')->on('users');
            $table->foreign('penerima_id')->references('id')->on('users');
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
        Schema::dropIfExists('laporans');
    }
}
