@extends('admin.layout.master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="display-5">Selamat Datang, Admin!</h1>
                    <p class="lead">Kelola aplikasi AcadEvent tentang pengelolaan acara di SMK Negeri 1 Cimahi</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Acara</p>
                                <h5 class="font-weight-bolder">{{ $stats['total_acara'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="fas fa-calendar fa-2x text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Siswa</p>
                                <h5 class="font-weight-bolder">{{ $stats['total_siswa'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="fas fa-users fa-2x text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Acara Aktif</p>
                                <h5 class="font-weight-bolder">{{ $stats['acara_aktif'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="fas fa-clock fa-2x text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Peserta</p>
                                <h5 class="font-weight-bolder">{{ $stats['total_peserta'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                <i class="fas fa-user-check fa-2x text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Acara & Peserta (2 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="statistikPeserta" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Jenis Acara</h5>
                </div>
                <div class="card-body">
                    <canvas id="jenisAcaraPie" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Acara Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Acara</th>
                                    <th>Penyelenggara</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Peserta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acaraTerbaru as $acara)
                                <tr>
                                    <td>{{ $acara->nama_acara }}</td>
                                    <td>{{ $acara->penyelenggara }}</td>
                                    <td>{{ Carbon\Carbon::parse($acara->tanggal_acara)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($acara->waktu_mulai)->format('H:i') }} - 
                                        {{ Carbon\Carbon::parse($acara->waktu_selesai)->format('H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $acara->status_acara == 'aktif' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($acara->status_acara) }}
                                        </span>
                                    </td>
                                    <td>{{ $acara->users->count() ?? 0 }}/{{ $acara->kuota_peserta ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Acara Sekolah Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if($acaraSekolahHariIni->count() > 0)
                        <div class="list-group">
                            @foreach($acaraSekolahHariIni as $acaraSekolah)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $acaraSekolah->nama_acara }}</h6>
                                    <small>{{ Carbon\Carbon::parse($acaraSekolah->waktu_mulai)->format('H:i') }}</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ $acaraSekolah->jenis_acara }} - {{ $acaraSekolah->tingkat }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center my-3">Tidak ada acara sekolah hari ini</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        background-position: center;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-shape i {
        color: #fff;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(45deg, #1cc88a 0%, #13855c 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(45deg, #f6c23e 0%, #dda20a 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(45deg, #36b9cc 0%, #258391 100%);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Line Chart - Statistik Peserta
    const monthlyData = @json($monthlyStats);
    const months = monthlyData.map(item => {
        const date = new Date(item.month + '-01');
        return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
    });
    
    new Chart(document.getElementById('statistikPeserta'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Acara',
                data: monthlyData.map(item => item.total_acara),
                borderColor: '#4e73df',
                tension: 0.4,
                fill: true
            }, {
                label: 'Total Peserta',
                data: monthlyData.map(item => item.total_peserta),
                borderColor: '#1cc88a',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Pie Chart - Distribusi Jenis Acara
    const jenisAcaraData = @json($jenisAcaraStats);
    new Chart(document.getElementById('jenisAcaraPie'), {
        type: 'pie',
        data: {
            labels: jenisAcaraData.map(item => item.jenis_acara),
            datasets: [{
                data: jenisAcaraData.map(item => item.total),
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection