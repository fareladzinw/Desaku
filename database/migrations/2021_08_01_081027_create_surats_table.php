<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengirim_id')->nullable();
            $table->unsignedBigInteger('penerima_id')->nullable();
            $table->string('keperluan');
            $table->string('file');
            $table->enum('status', ['Ditolak', 'Pending RT', 'Pending RW', 'Pending Admin', 'Disetujui']);
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->unsignedBigInteger('rw_id')->nullable();
            $table->unsignedBigInteger('desa_id')->nullable();
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
        Schema::dropIfExists('surats');
    }
}
