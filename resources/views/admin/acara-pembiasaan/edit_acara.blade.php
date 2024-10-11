@extends('admin.layout.master')

@section('title', 'Edit Acara Sekolah')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="text-center my-4">Edit Acara Sekolah Hari : {{ $acaraSekolah->hari->nama_hari }}</h1>
            <form action="{{ route('acara_sekolah.update', $acaraSekolah->id_acara_sekolah) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Hidden Input untuk id_hari -->
                <input type="hidden" name="id_hari" value="{{ $acaraSekolah->id_hari }}">
                
                <div class="form-group mb-3">
                    <label for="nama_acara">Nama Acara</label>
                    <input type="text" class="form-control @error('nama_acara') is-invalid @enderror" id="nama_acara" name="nama_acara" required value="{{ old('nama_acara', $acaraSekolah->nama_acara) }}">
                    @error('nama_acara')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="deskripsi">Deskripsi Acara</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $acaraSekolah->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="jenis_acara">Jenis Acara</label>
                    <select class="form-select @error('jenis_acara') is-invalid @enderror" id="jenis_acara" name="jenis_acara" required>
                        <option value="">Pilih Jenis Acara</option>
                        <option value="Pembiasaan" {{ old('jenis_acara', $acaraSekolah->jenis_acara) == 'Pembiasaan' ? 'selected' : '' }}>Pembiasaan</option>
                        <option value="Apel" {{ old('jenis_acara', $acaraSekolah->jenis_acara) == 'Apel' ? 'selected' : '' }}>Apel</option>
                        <option value="Upacara" {{ old('jenis_acara', $acaraSekolah->jenis_acara) == 'Upacara' ? 'selected' : '' }}>Upacara</option>
                        <option value="Pelatihan" {{ old('jenis_acara', $acaraSekolah->jenis_acara) == 'Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="Lainnya" {{ old('jenis_acara', $acaraSekolah->jenis_acara) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_acara')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="tingkat">Tingkat</label>
                    <select class="form-select @error('tingkat') is-invalid @enderror" id="tingkat" name="tingkat" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="Tingkat 1" {{ old('tingkat', $acaraSekolah->tingkat) == 'Tingkat 1' ? 'selected' : '' }}>Tingkat 1</option>
                        <option value="Tingkat 2" {{ old('tingkat', $acaraSekolah->tingkat) == 'Tingkat 2' ? 'selected' : '' }}>Tingkat 2</option>
                        <option value="Tingkat 3" {{ old('tingkat', $acaraSekolah->tingkat) == 'Tingkat 3' ? 'selected' : '' }}>Tingkat 3</option>
                        <option value="Seluruh Tingkat" {{ old('tingkat', $acaraSekolah->tingkat) == 'Seluruh Tingkat' ? 'selected' : '' }}>Seluruh Tingkat</option>
                    </select>
                    @error('tingkat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" id="waktu_mulai" name="waktu_mulai" required value="{{ old('waktu_mulai', $acaraSekolah->waktu_mulai) }}">
                    @error('waktu_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="waktu_selesai">Waktu Selesai</label>
                    <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" id="waktu_selesai" name="waktu_selesai" required value="{{ old('waktu_selesai', $acaraSekolah->waktu_selesai) }}">
                    @error('waktu_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection

