<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.html">
        <span class="align-middle">AcadEvent</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Home
            </li>

            <li class="sidebar-item {{ (request()->routeIs('admin.dashboard*') ? 'active' : '') }}">
                <a class="sidebar-link" href="{{route('admin.dashboard')}}">
                    <i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Kelola User
            </li>
            <li class="sidebar-item {{ (request()->routeIs('data.siswa*') ? 'active' : '') }}">
                <a class="sidebar-link" href="{{route('data.siswa')}}">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">Data Siswa</span>
                </a>
            </li>

            <li class="sidebar-item {{ (request()->routeIs('user.index') ? 'active' : '') }}">
                <a class="sidebar-link" href="{{route('user.index')}}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Data User</span>
                </a>
            </li>

            <li class="sidebar-header">
                Kelola Acara
            </li>

            <li class="sidebar-item {{ (request()->routeIs(['acara', 'acara.create', 'acara.edit']) ? 'active' : '') }}">
                <a class="sidebar-link" href="{{route('acara')}}">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Acara Umum</span>
                </a>
            </li>
            <li class="sidebar-item {{ (request()->routeIs('acara_sekolah*') ? 'active' : '') }}">
                <a class="sidebar-link" href="{{route('acara_sekolah.index')}}">
                    <i class="align-middle" data-feather="check-square"></i> <span class="align-middle">Acara Pembiasaan</span>
                </a>
            </li>
            <li class="sidebar-item {{ (request()->routeIs('calendar') ? 'active' : '') }}">
                <a class="sidebar-link" href="{{route('calendar')}}">
                    <i class="align-middle" data-feather="calendar"></i> <span class="align-middle">Jadwal Acara</span>
                </a>
            </li>
        </ul>
    </div>
</nav>