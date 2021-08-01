<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('name');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->enum('role', ['Warga', 'RT', 'RW', 'Admin']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string("tempat_lahir");
            $table->date("tanggal_lahir");
            $table->string('agama');
            $table->string('alamat');
            $table->string('no_telp');
            $table->string('password');
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->unsignedBigInteger('rw_id')->nullable();
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->boolean('is_kepala');
            $table->boolean('status');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
