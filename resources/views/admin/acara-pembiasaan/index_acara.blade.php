@extends('admin.layout.master')

@section('title', 'Data Acara Sekolah')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1 class="my-4 text-center">Daftar Acara Sekolah</h1>
            <p class="card-text text-center">Berikut adalah daftar acara sekolah yang pernah diadakan. Anda dapat menambahkan acara baru, mengedit, atau menghapusnya.</p>
        </div>
        <div class="card-body">
            <!-- Tombol Tambah Hari -->
            <div class="mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahHariModal"><i class="fas fa-plus me-2"></i> Tambah Hari</button>
            </div>

            <!-- Loop untuk menampilkan card berdasarkan hari yang ada -->
            @foreach($hari as $h)
            <div class="card my-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Acara Hari: {{ $h->nama_hari }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('acara_sekolah.create', ['id_hari' => $h->id_hari]) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-2"></i> Tambah Acara</a>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusHariModal{{ $h->id_hari }}"><i class="fas fa-trash me-2"></i> Hapus Hari</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Daftar acara yang sudah ditambahkan pada hari ini -->
                    <h5 class="mt-4">Acara Terdaftar di Hari {{ $h->nama_hari }}</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Acara</th>
                                    <th>Deskripsi</th>
                                    <th>Jenis Acara</th>
                                    <th>Status Acara</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($h->acaraSekolah as $acara)
                                <tr>
                                    <td>{{ $acara->nama_acara }}</td>
                                    <td>{{ Str::limit($acara->deskripsi, 20) }}</td>
                                    <td>{{ $acara->jenis_acara }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-capitalize
                                            @if($acara->status_acara == 'aktif')
                                                bg-primary
                                            @elseif($acara->status_acara == 'berlangsung')
                                                bg-success
                                            @elseif($acara->status_acara == 'batal')
                                                bg-danger
                                            @elseif($acara->status_acara == 'selesai')
                                                bg-dark
                                            @endif
                                            ">{{ $acara->status_acara }}</span>
                                    </td>
                                    <td>{{ $acara->waktu_mulai }}</td>
                                    <td>{{ $acara->waktu_selesai }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Event Actions">
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailAcaraModal{{ $acara->id_acara_sekolah }}" title="Lihat Detail Acara"><i class="fas fa-eye text-white"></i></button>
                                            <a href="{{ route('acara_sekolah.edit', $acara->id_acara_sekolah) }}" class="btn btn-warning btn-sm" title="Edit Acara"><i class="fas fa-edit text-white"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusAcaraModal{{ $acara->id_acara_sekolah }}" title="Hapus Acara"><i class="fas fa-trash"></i></button>
                                            @if($acara->status_acara == 'batal')
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#activateAcaraModal{{ $acara->id_acara_sekolah }}" title="Aktifkan Acara"><i class="fas fa-check"></i></button>
                                            @elseif($acara->status_acara == 'aktif')
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#cancelAcaraModal{{ $acara->id_acara_sekolah }}" title="Batalkan Acara"><i class="fas fa-ban"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Tambah Hari -->
<div class="modal fade" id="tambahHariModal" tabindex="-1" aria-labelledby="tambahHariModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahHariModalLabel">Tambah Hari</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('hari.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_hari" class="form-label">Nama Hari</label>
                        <select class="form-select" id="nama_hari" name="nama_hari" required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>    
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach($hari as $h)
<!-- Modal Konfirmasi Hapus Hari -->
<div class="modal fade" id="hapusHariModal{{ $h->id_hari }}" tabindex="-1" aria-labelledby="hapusHariModalLabel{{ $h->id_hari }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusHariModalLabel{{ $h->id_hari }}">Konfirmasi Hapus Hari</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus hari {{ $h->nama_hari }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('hari.destroy', $h->id_hari) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>  
@endforeach

<!-- Modal Hapus Acara -->
@foreach ($acaraSekolah as $acara)
<div class="modal fade" id="hapusAcaraModal{{ $acara->id_acara_sekolah }}" tabindex="-1" aria-labelledby="hapusAcaraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusAcaraLabel">Hapus Acara: {{ $acara->nama_acara }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus acara {{ $acara->nama_acara }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('acara_sekolah.destroy', $acara->id_acara_sekolah) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Cancel Acara -->
@foreach ($acaraSekolah as $acara)
<div class="modal fade" id="cancelAcaraModal{{ $acara->id_acara_sekolah }}" tabindex="-1" aria-labelledby="cancelAcaraLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelAcaraLabel">Batalkan Acara: {{ $acara->nama_acara }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin membatalkan acara ini?</p>
        </div>
        <div class="modal-footer">
          <form action="{{ route('acara_sekolah.cancel', $acara->id_acara_sekolah) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-danger">Batalkan Acara</button>
          </form>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  <!-- Modal Detail Acara -->
  @foreach ($acaraSekolah as $acara)
    <div class="modal fade" id="detailAcaraModal{{ $acara->id_acara_sekolah }}" tabindex="-1" aria-labelledby="detailAcaraLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailAcaraLabel">Detail Acara: {{ $acara->nama_acara }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Deskripsi:</strong> {{ $acara->deskripsi }}</p>
          <p><strong>Tingkat:</strong> {{ $acara->tingkat }}</p>
          <p><strong>Waktu Mulai:</strong> {{ $acara->waktu_mulai }}</p>
          <p><strong>Waktu Selesai:</strong> {{ $acara->waktu_selesai }}</p>
          <p class="text-capitalize"><strong>Status:</strong>
            <span class="badge rounded-pill
              @if($acara->status_acara == 'aktif')
                bg-primary
              @elseif($acara->status_acara == 'berlangsung')
                bg-success
              @elseif($acara->status_acara == 'selesai')
                bg-dark
              @elseif($acara->status_acara == 'batal')
                bg-danger
              @endif
              ">{{ $acara->status_acara }}</span>
          </p>
          <p><strong>Hari:</strong> {{ $acara->hari->nama_hari }}</p>
          <p><strong>Tanggal Selanjutnya:</strong> {{ \Carbon\Carbon::parse('next monday')->format('d F Y') }}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  @endforeach


  <!-- Modal Aktifkan Acara -->
  @foreach ($acaraSekolah as $acara)
<div class="modal fade" id="activateAcaraModal{{ $acara->id_acara_sekolah }}" tabindex="-1" aria-labelledby="activateAcaraLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="activateAcaraLabel">Aktifkan Acara: {{ $acara->nama_acara }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin mengaktifkan kembali acara ini?</p>
        </div>
        <div class="modal-footer">
          <form action="{{ route('acara_sekolah.activate', $acara->id_acara_sekolah) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-success">Aktifkan Acara</button>
          </form>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  

{{-- SWEETALERT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                background: '#ECFFE6',
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                customClass: {
                    container: 'custom-toast-container' // Tambahkan kelas kustom jika diperlukan
                }
            });
        @endif

        @if ($errors->any())
            const Toast = Swal.mixin({
                toast: true,
                background: '#F8D7DA',
                position: 'top-end',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545',
                timer: 4000,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'error',
                title: "Error!",
                html: `
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                customClass: {
                    container: 'custom-toast-container' // Tambahkan kelas kustom jika diperlukan
                }
            });
        @endif
    });
</script>
<style>
    .custom-toast-container {
        margin-top: 70px;
    }
</style>
@endsection
