<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_user' => 'Admin Master',
            'nis' => '1234567890', // Sesuaikan dengan format NIS yang kamu gunakan
            'email' => 'admin@example.com',
            'no_hp' => '081234567890',
            'role' => 'admin', // Asumsikan role 1 adalah admin
            'password' => Hash::make('admin123'), // Ganti 'password' dengan password yang diinginkan
            // 'id_siswa' =>  // Jika admin terhubung dengan data siswa, isikan id_siswa di sini
        ]);
    }
}
