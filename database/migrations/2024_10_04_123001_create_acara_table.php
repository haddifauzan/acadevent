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
        Schema::create('tbl_acara', function (Blueprint $table) {
            $table->bigIncrements('id_acara');
            $table->string('nama_acara');
            $table->string('jenis_acara');
            $table->string('penyelenggara');
            $table->string('tingkat');
            $table->text('deskripsi');
            $table->date('tanggal_acara');
            $table->timestamp('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->string('tempat');
            $table->integer('kuota_peserta');
            $table->enum('status_acara', ['aktif', 'berlangsung', 'selesai', 'batal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_acara');
    }
};