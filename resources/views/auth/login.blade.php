<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="AdminKit">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-in.html" />

	<title>Login | Admin AcadEvent</title>

	<link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>

<body>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">
						<div class="text-center mt-4">
							<h1 class="h2">Welcome Back, Admin</h1>
							<p class="lead">
								Sign in to your account to continue
							</p>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="m-sm-4">
									<div class="d-flex align-items-center justify-content-center my-3">
										<img src="{{asset('assets/img/icons/icon-48x48.png')}}" class="img-fluid" alt="AdminKit" width="32" height="32" />
										<div class="ms-2">
											<h4 class="m-0">Admin AcadEvent</h4>
										</div>
									</div>
									<form action="{{ route('login-proses') }}" method="POST">
                                        @csrf
										@if ($errors->any())
											<script>
												Swal.fire({
													icon: 'error',
													title: 'Oops...',
													text: '{{ $errors->first() }}'
												})
											</script>
										@endif
										<div class="mb-3">
											<label class="form-label">Email</label>
											<input class="form-control form-control-lg @error('email') is-invalid @enderror" type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" />
											@error('email')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
										<div class="mb-3">
											<label class="form-label">Password</label>
											<div class="input-group input-group-merge">
												<input class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" name="password" placeholder="Enter your password" id="passwordInput" />
												<div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="Show/Hide Password">
													<a href="javascript:void(0)" onclick="togglePassword()">
														<i class="fas fa-eye" id="togglePassword"></i>
													</a>
												</div>
											</div>
											@error('password')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
										<div>
											<label class="form-check">
                                            <input class="form-check-input" type="checkbox" value="remember-me" name="remember-me" checked>
                                            <span class="form-check-label">
                                            Remember me
                                            </span>
                                        </label>
										</div>
										<div class="text-center mt-3">
											<button type="submit" class="btn btn-lg btn-primary w-50">Sign in</button>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</main>

    @if (session('success'))
        <script>
            setTimeout(function() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            setTimeout(function() {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        </script>
    @endif


	<script src="{{asset('assets/js/app.js')}}"></script>

    <script>
        function togglePassword() {
            var x = document.getElementById("passwordInput");
            if (x.type === "password") {
                x.type = "text";
                document.getElementById("togglePassword").className = "fa fa-eye-slash";
            } else {
                x.type = "password";
                document.getElementById("togglePassword").className = "fa fa-eye";
            }
        }
    </script>
</body>

</html>