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
        Schema::create('tbl_acara_sekolah', function (Blueprint $table) {
            $table->bigIncrements('id_acara_sekolah');
            $table->string('jenis_acara');
            $table->string('nama_acara');
            $table->string('tingkat');
            $table->text('deskripsi');
            $table->date('tanggal_acara');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->enum('status_acara', ['aktif', 'berlangsung', 'selesai', 'batal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_acara_sekolah');
    }
};