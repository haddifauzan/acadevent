<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->string('nama_user');
            $table->string('nis');
            $table->string('email');
            $table->string('no_hp');
            $table->integer('role'); // Asumsikan role berupa angka
            $table->string('password');
            $table->unsignedBigInteger('id_siswa')->nullable(); // Relasi ke tbl_siswa

            // Foreign key constraint
            $table->foreign('id_siswa')->references('id_siswa')->on('tbl_siswa')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};