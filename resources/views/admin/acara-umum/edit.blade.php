@extends('admin.layout.master')

@section('title', 'Edit Acara')

@section('content')
<div class="container-fluid">
     <div class="row">
         <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Edit Acara : {{ $acara->nama_acara }}</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                      <div class="alert alert-danger">
                           <ul>
                               @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                               @endforeach
                           </ul>
                      </div>
                    @endif

                    <form action="{{ route('acara.update', $acara->id_acara) }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      @method('PUT')
                      <div class="mb-3">
                           <label for="nama_acara" class="form-label">Nama Acara</label>
                           <input type="text" class="form-control @error('nama_acara') is-invalid @enderror" id="nama_acara" name="nama_acara" value="{{ old('nama_acara', $acara->nama_acara) }}" placeholder="Masukkan Nama Acara"  required>
                           @error('nama_acara')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                      </div>

                      <div class="mb-3">
                           <label for="penyelenggara" class="form-label">Penyelenggara</label>
                           <input type="text" class="form-control @error('penyelenggara') is-invalid @enderror" id="penyelenggara" name="penyelenggara" value="{{ old('penyelenggara', $acara->penyelenggara) }}" placeholder="Masukkan Penyelenggara" required>
                           @error('penyelenggara')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                      </div>

                      <div class="mb-3">
                           <label for="deskripsi" class="form-label">Deskripsi Acara</label>
                           <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $acara->deskripsi) }}</textarea>
                           @error('deskripsi')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                      </div>

                      <div class="mb-3 row">
                           <div class="col">
                              <label for="tanggal_acara" class="form-label">Tanggal Acara</label>
                              <input type="date" class="form-control @error('tanggal_acara') is-invalid @enderror" id="tanggal_acara" name="tanggal_acara" value="{{ old('tanggal_acara', $acara->tanggal_acara) }}" required min="{{ date('Y-m-d') }}">
                              @error('tanggal_acara')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="col">
                              <label for="tempat" class="form-label">Tempat</label>
                              <input type="text" class="form-control @error('tempat') is-invalid @enderror" id="tempat" name="tempat" value="{{ old('tempat', $acara->tempat) }}" placeholder="Masukkan Tempat" required>
                              @error('tempat')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                      </div>

                      <div class="mb-3 row">
                           <div class="col">
                              <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                              <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', date('H:i', strtotime($acara->waktu_mulai))) }}" required>
                              @error('waktu_mulai')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="col">
                              <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                              <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai', date('H:i', strtotime($acara->waktu_selesai))) }}" required >
                              @error('waktu_selesai')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                      </div>

                      <div class="mb-3 row">
                           <div class="col">
                              <label for="tingkat" class="form-label">Tingkat</label>
                              <select class="form-select @error('tingkat') is-invalid @enderror" id="tingkat" name="tingkat" required>
                                  <option value="Tingkat 1" {{ old('tingkat', $acara->tingkat) == 'Tingkat 1' ? 'selected' : '' }}>Tingkat 1</option>
                                  <option value="Tingkat 2" {{ old('tingkat', $acara->tingkat) == 'Tingkat 2' ? 'selected' : '' }}>Tingkat 2</option>
                                  <option value="Tingkat 3" {{ old('tingkat', $acara->tingkat) == 'Tingkat 3' ? 'selected' : '' }}>Tingkat 3</option>
                                  <option value="Seluruh Tingkat" {{ old('tingkat', $acara->tingkat) == 'Seluruh Tingkat' ? 'selected' : '' }}>Seluruh Tingkat</option>
                              </select>
                              @error('tingkat')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="col">
                              <label for="jenis_acara" class="form-label">Jenis Acara</label>
                              <select class="form-select @error('jenis_acara') is-invalid @enderror" id="jenis_acara" name="jenis_acara">
                                   <option value="seminar" {{ old('jenis_acara', $acara->jenis_acara) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                   <option value="workshop" {{ old('jenis_acara', $acara->jenis_acara) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                   <option value="lomba" {{ old('jenis_acara', $acara->jenis_acara) == 'lomba' ? 'selected' : '' }}>Lomba</option>
                                   <option value="pelatihan" {{ old('jenis_acara', $acara->jenis_acara) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                   <option value="festival" {{ old('jenis_acara', $acara->jenis_acara) == 'festival' ? 'selected' : '' }}>Festival</option>
                                   <option value="pentas_seni" {{ old('jenis_acara', $acara->jenis_acara) == 'pentas_seni' ? 'selected' : '' }}>Pentas Seni</option>
                                   <option value="orientasi" {{ old('jenis_acara', $acara->jenis_acara) == 'orientasi' ? 'selected' : '' }}>Orientasi</option>
                                   <option value="reuni" {{ old('jenis_acara', $acara->jenis_acara) == 'reuni' ? 'selected' : '' }}>Reuni</option>
                                   <option value="lainnya" {{ old('jenis_acara', $acara->jenis_acara) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                              </select>
                              @error('jenis_acara')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                      </div>

                      <div class="mb-3">
                         <label class="form-label">Kuota Peserta</label>
                         
                         <div class="form-check form-check-inline">
                             <input class="form-check-input @error('kuota_peserta') is-invalid @enderror" 
                                    type="radio" 
                                    name="kuota_peserta" 
                                    id="kuota_peserta_tidak_terbatas" 
                                    value="tidak terbatas" 
                                    required 
                                    {{ old('kuota_peserta', $acara->kuota_peserta === null ? 'tidak terbatas' : '') == 'tidak terbatas' ? 'checked' : '' }}
                                    onclick="toggleKuotaInput()">
                             <label class="form-check-label" for="kuota_peserta_tidak_terbatas">Tidak terbatas</label>
                         </div>
                         
                         <div class="form-check form-check-inline">
                             <input class="form-check-input @error('kuota_peserta') is-invalid @enderror" 
                                    type="radio" 
                                    name="kuota_peserta" 
                                    id="kuota_peserta_terbatas" 
                                    value="terbatas" 
                                    required 
                                    {{ old('kuota_peserta', $acara->kuota_peserta !== null ? 'terbatas' : '') == 'terbatas' ? 'checked' : '' }}
                                    onclick="toggleKuotaInput()">
                             <label class="form-check-label" for="kuota_peserta_terbatas">Terbatas</label>
                         </div>
                         
                         @error('kuota_peserta')
                         <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     
                         <div id="kuota_peserta_terbatas_container" class="mt-2">
                             <input type="number" 
                                    class="form-control w-25 @error('kuota_peserta_terbatas') is-invalid @enderror" 
                                    id="kuota_peserta_terbatas" 
                                    name="kuota_peserta_terbatas" 
                                    value="{{ old('kuota_peserta_terbatas', $acara->kuota_peserta) }}" 
                                    placeholder="Masukkan Kuota" 
                                    {{ old('kuota_peserta', $acara->kuota_peserta === null ? 'tidak terbatas' : 'terbatas') != 'terbatas' ? 'disabled' : '' }}>
                             @error('kuota_peserta_terbatas')
                             <div class="invalid-feedback">{{ $message }}</div>
                             @enderror
                         </div>
                     </div>

                      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                      <a href="{{ route('acara') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
         </div>
     </div>
</div>

<script>
     function toggleKuotaInput() {
         const terbatasRadio = document.getElementById('kuota_peserta_terbatas');
         const inputKuota = document.getElementById('kuota_peserta_terbatas_container').querySelector('input');
 
         if (terbatasRadio.checked) {
             inputKuota.removeAttribute('disabled');
         } else {
             inputKuota.setAttribute('disabled', 'disabled');
         }
     }
 
     document.addEventListener('DOMContentLoaded', function () {
         toggleKuotaInput();
     });
 </script>
@endsection