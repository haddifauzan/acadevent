<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_acara_sekolah', function (Blueprint $table) {
            $table->dropColumn('tanggal_acara'); 
            $table->unsignedBigInteger('id_hari')->after('deskripsi'); 

            $table->foreign('id_hari')->references('id_hari')->on('tbl_hari')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_acara_sekolah', function (Blueprint $table) {
            $table->dropForeign(['id_hari']);
            $table->dropColumn('id_hari');
            $table->date('tanggal_acara')->after('deskripsi'); 
        });
    }
};