@extends('admin.layout.master')

@section('title', 'Data Acara')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center">
            <h2>Data Acara atau Kegiatan Umum</h2>
            <p class="lead">Kelola semua acara atau kegiatan umum di sekolah dengan mudah.</p>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <form action="{{ route('acara') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari acara..." value="{{ request('search') }}">
                            <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('acara.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Tambah Acara</a>
                </div>
            </div>

            {{-- Menampilkan daftar acara dengan card --}}
            <div class="row">
                @foreach($acara as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $item->nama_acara }}</h5>
                                <p class="card-text">{{ Str::limit($item->deskripsi, 100) }}</p>
                                <p class="card-text"><small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_acara)->format('l, d F Y') }} || {{ $item->waktu_mulai }} s/d {{ $item->waktu_selesai }}</small></p>
                                <span class="d-inline-block align-middle text-capitalize">
                                @if($item->kuota_peserta !== null)
                                    <p class="text-muted align-middle me-2 fw-bold text-dark ">
                                        <small class="text-muted">
                                            Jumlah Peserta : {{ $item->users()->count() }}/{{ $item->kuota_peserta }}
                                        </small>
                                    </p>
                                @else
                                    <p class="text-muted align-middle me-2 fw-bold text-dark ">
                                        <small class="text-muted">
                                            Jumlah Peserta : {{ $item->users()->count() }} (Tanpa batas kuota)
                                        </small>
                                    </p>
                                @endif
                                    <span class="text-muted align-middle me-2 fw-bold text-dark ">Status Acara : </span>
                                    <span class="badge
                                        @if($item->status_acara == 'aktif')
                                            bg-primary
                                        @elseif($item->status_acara == 'berlangsung')
                                            bg-success
                                        @elseif($item->status_acara == 'selesai')
                                            bg-dark
                                        @elseif($item->status_acara == 'batal')
                                            bg-danger
                                        @endif
                                        " style="font-size: 0.8rem;">
                                        {{ $item->status_acara }}
                                    </span>
                                </span>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100" role="group" aria-label="Event Actions">
                                    <button type="button" class="btn btn-info btn-sm text-white w-100" data-bs-toggle="modal" data-bs-target="#modalShow{{ $item->id_acara }}"
                                        title="Lihat detail acara"
                                        data-bs-placement="top"
                                        data-bs-toggle="tooltip">
                                        <i class="fas fa-eye me-2"></i> 
                                    </button>
                                    @if($item->kode_kehadiran != null)
                                        <button type="button" class="btn btn-dark btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalPeserta{{ $item->id_acara }}"
                                            title="Lihat peserta acara"
                                            data-bs-placement="top"
                                            data-bs-toggle="tooltip">
                                            <i class="fas fa-users me-2"></i> 
                                        </button>
                                    @endif
                                    @if($item->status_acara != 'selesai')
                                        <a href="{{ route('acara.edit', $item->id_acara) }}" class="btn btn-warning btn-sm text-white w-100"
                                            title="Ubah acara"
                                            data-bs-placement="top"
                                            data-bs-toggle="tooltip">
                                            <i class="fas fa-edit me-2"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $item->id_acara }}"
                                            title="Hapus acara"
                                            data-bs-placement="top"
                                            data-bs-toggle="tooltip">
                                            <i class="fas fa-trash me-2"></i>
                                        </button>
                                    @endif
                                    @if(!in_array($item->status_acara, ['batal', 'selesai']))
                                        <button type="button" class="btn btn-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalCancel{{ $item->id_acara }}"
                                            title="Batalkan acara"
                                            data-bs-placement="top"
                                            data-bs-toggle="tooltip">
                                            <i class="fas fa-ban me-2"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $acara->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal Delete -->
@foreach($acara as $item)
<div class="modal fade" id="modalDelete{{ $item->id_acara }}" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus acara {{ $item->nama_acara }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('acara.destroy', $item->id_acara) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($acara as $item)
    {{-- Modal for showing event details --}}
    <div class="modal fade" id="modalShow{{ $item->id_acara }}" tabindex="-1" aria-labelledby="modalShowLabel{{ $item->id_acara }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalShowLabel{{ $item->id_acara }}">Detail Acara: {{ $item->nama_acara }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Penyelenggara:</strong> {{ $item->penyelenggara }}</p>
                            <p><strong>Hari, Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal_acara)->format('l, d F Y') }}</p>
                            <p><strong>Waktu:</strong> {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}</p>
                            <p><strong>Tempat:</strong> {{ $item->tempat }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tingkat:</strong> {{ $item->tingkat }}</p>
                            <p><strong>Jenis Acara:</strong> {{ ucfirst($item->jenis_acara) }}</p>
                            <p><strong>Kuota Peserta:</strong> 
                                @if($item->kuota_peserta === null)
                                    Tidak terbatas
                                @else
                                    {{ $item->kuota_peserta }} orang
                                @endif
                            </p>
                            <p class="text-capitalize"><strong>Status:</strong> {{ $item->status_acara }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6><strong>Deskripsi Acara:</strong></h6>
                            <p>{{ $item->deskripsi }}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <h4><strong>Kode Kehadiran:</strong>
                            @if($item->kode_kehadiran === null)
                                -
                            @else
                                {{$item->kode_kehadiran}}
                            @endif
                        </h4>
                        @if($item->kode_kehadiran !== null)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($item->kode_kehadiran) }}" alt="QR Code Kehadiran" class="mt-3" />
                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($item->kode_kehadiran) }}" download="QR-Code-Kehadiran-{{$item->nama_acara}}.png" class="btn btn-sm btn-primary mt-3" id="download-btn-{{$item->id_acara}}">
                                <i class="fas fa-download me-2" id="download-icon-{{$item->id_acara}}"></i> <span id="download-text-{{$item->id_acara}}">Unduh QR Code</span>
                            </a>
                            <script>
                                document.getElementById('download-btn-{{$item->id_acara}}').addEventListener('click', (e) => {
                                    e.target.disabled = true;
                                    document.getElementById('download-icon-{{$item->id_acara}}').classList.add('fa-spinner', 'fa-spin');
                                    document.getElementById('download-text-{{$item->id_acara}}').textContent = 'Memproses...';
                                    setTimeout(() => {
                                        e.target.disabled = false;
                                        document.getElementById('download-icon-{{$item->id_acara}}').classList.remove('fa-spinner', 'fa-spin');
                                        document.getElementById('download-text-{{$item->id_acara}}').textContent = 'Unduh QR Code';
                                    }, 2000);
                                });
                            </script>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach



{{-- Add Cancel Modal for each event --}}
@foreach($acara as $item)
    <div class="modal fade" id="modalCancel{{ $item->id_acara }}" tabindex="-1" aria-labelledby="modalCancelLabel{{ $item->id_acara }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCancelLabel{{ $item->id_acara }}">Batalkan Acara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin membatalkan acara "{{ $item->nama_acara }}"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('acara.cancel', $item->id_acara) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Ya, Batalkan Acara</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal untuk menampilkan peserta acara --}}
@foreach($acara as $item)
<div class="modal fade" id="modalPeserta{{ $item->id_acara }}" tabindex="-1" aria-labelledby="modalPesertaLabel{{ $item->id_acara }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPesertaLabel{{ $item->id_acara }}">Peserta Acara: {{ $item->nama_acara }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Tabel Peserta --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Peserta</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody id="pesertaList{{ $item->id_acara }}">
                            @forelse($item->users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->nama_user }}</td>
                                    <td>{{ $user->siswa->kelas }}</td>
                                    <td>{{ $user->siswa->jurusan }}</td>
                                    <td>
                                        <span class="badge rounded-pill
                                            @if($user->pivot->status_kehadiran == 'belum hadir')
                                                bg-secondary
                                            @elseif($user->pivot->status_kehadiran == 'hadir')
                                                bg-success
                                            @elseif($user->pivot->status_kehadiran == 'tidak hadir')
                                                bg-danger
                                            @endif
                                            ">
                                            {{ ucfirst($user->pivot->status_kehadiran) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada peserta yang terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
