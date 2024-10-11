@extends('admin.layout.master')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>HALO ADMIN</h1>


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
		

        

            