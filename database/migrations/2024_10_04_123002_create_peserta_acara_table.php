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
        Schema::create('tbl_peserta_acara', function (Blueprint $table) {
            $table->bigIncrements('id_peserta_acara');
            $table->unsignedBigInteger('id_acara');
            $table->unsignedBigInteger('id_user');
            $table->enum('status_kehadiran', ['hadir', 'tidak hadir']); // Ganti dengan status kehadiran yang sesuai

            // Foreign key constraints
            $table->foreign('id_acara')->references('id_acara')->on('tbl_acara')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('tbl_users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_peserta_acara');
    }
};