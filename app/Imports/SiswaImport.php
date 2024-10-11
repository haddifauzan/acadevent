<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\ValidationException;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi input sebelum menyimpan data
        $validator = Validator::make($row, [
            'nis' => 'required|unique:tbl_siswa,nis', // NIS harus unik
            'nama_siswa' => 'required|string|max:255',  // Nama siswa
            'kelas' => 'required|string|max:10',   // Kelas
            'jurusan' => 'required|string|max:255',  // Jurusan
            'email' => 'required|email',           // Email valid
            'no_hp' => 'required',         // Nomor HP harus angka
        ]);

        // Jika validasi gagal, kembalikan error
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return new Siswa([
            'nis' => $row['nis'],
            'nama_siswa' => $row['nama_siswa'],
            'kelas' => $row['kelas'],
            'jurusan' => $row['jurusan'],
            'email' => $row['email'],
            'no_hp' => $row['no_hp'],
        ]);
    }
}

