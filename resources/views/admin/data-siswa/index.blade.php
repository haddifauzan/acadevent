@extends('admin.layout.master')

@section('title', 'Data Siswa')

@section('content')
    <!-- Card Pertama: Judul dan Deskripsi -->
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="fw-bold">Data Siswa</h2>
            <p class="card-text">
                Halaman ini menampilkan data siswa sekolah yang terdaftar. Anda dapat menambahkan siswa baru, mengimpor data dari file Excel, atau mengunduh format Excel untuk diisi.
            </p>
        </div>
    </div>

    <!-- Card Kedua: Button Tambah Siswa, Import Excel, dan Download Format Excel -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="row ms-auto">
                    <div class="col-auto">
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa"><i class="fas fa-plus me-2"></i> Tambah Siswa</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                            <i class="fas fa-upload me-2"></i> Import Data Excel
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('siswa.downloadFormat') }}" class="btn btn-secondary">
                            <i class="fas fa-download me-2"></i> Download Format Excel
                        </a>
                    </div>
                </div>

                <!-- Modal Import Excel -->
                <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalImportExcelLabel">Import Data Excel</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('siswa.importExcel') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" class="form-control mb-2" accept=".xlsx, .xls, .csv" required>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-upload me-2"></i> Import
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Ketiga: Input Pencarian dan Tabel Siswa -->
    <div class="card mb-4">
        <div class="card-body">
            <!-- Table Siswa -->
            <div class="table-responsive">
                <table class="table table-hover" id="table-siswa">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Looping data siswa -->
                        @foreach($siswa as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nis }}</td>
                            <td>{{ $item->nama_siswa }}</td>
                            <td>{{ $item->kelas }}</td>
                            <td>{{ $item->jurusan }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td>
                                <div class="btn-group d-flex justify-content-center" role="group" aria-label="Aksi Siswa">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $item->id_siswa }}"><i class="fas fa-pencil text-white"></i></button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapusSiswa{{ $item->id_siswa }}"><i class="fas fa-trash text-white"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formTambahSiswa" method="POST" action="{{ route('data.siswa.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahSiswaLabel">Tambah Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nis" class="form-label">NIS</label>
                            <input type="text" class="form-control" id="nis" name="nis" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_siswa" class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" required>
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Siswa -->
    @foreach($siswa as $item)
    <div class="modal fade" id="modalEditSiswa{{ $item->id_siswa }}" tabindex="-1" aria-labelledby="modalEditSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditSiswa" method="POST" action="{{ route('data.siswa.update', $item->id_siswa) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSiswaLabel">Edit Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Isi form dengan data siswa yang ingin diedit -->
                        <div class="mb-3">
                            <label for="edit_nis" class="form-label">NIS</label>
                            <input type="text" class="form-control" id="edit_nis" name="nis" value="{{ $item->nis }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_siswa" class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="edit_nama_siswa" name="nama_siswa" value="{{ $item->nama_siswa }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="edit_kelas" name="kelas" value="{{ $item->kelas }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="edit_jurusan" name="jurusan" value="{{ $item->jurusan }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" value="{{ $item->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="edit_no_hp" name="no_hp" value="{{ $item->no_hp }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Konfirmasi Hapus -->
    @foreach($siswa as $item)
    <div class="modal fade" id="modalHapusSiswa{{ $item->id_siswa }}" tabindex="-1" aria-labelledby="modalHapusSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formHapusSiswa" method="POST" action="{{ route('data.siswa.destroy', $item->id_siswa) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusSiswaLabel">Hapus Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus siswa ini?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- DATATABLE -->
    <script>
        $(document).ready(function() {
            new DataTable('#table-siswa', {
                searching: true,  // Nonaktifkan search box
                paging: true,      // Mengaktifkan pagination
                info: true,        // Menampilkan informasi jumlah data
                ordering: true     // Mengaktifkan fitur pengurutan
            });
        });
    </script>

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