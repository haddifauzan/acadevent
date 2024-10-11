<div class="main">
    <nav class="navbar navbar-expand navbar-light navbar-bg px-3">
        <a class="sidebar-toggle js-sidebar-toggle">
            <i class="hamburger align-self-center"></i>
        </a>

        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                        <i class="align-middle" data-feather="settings"></i>
                    </a>

                    <a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <img src="{{asset('assets/img/avatars/admin.png')}}" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark">{{ Auth::user()->nama_user }}</span>
                        <i class="align-middle" data-feather="chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasProfile" aria-controls="offcanvasProfile">
                            <i class="align-middle me-1" data-feather="user"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal" href="#"><i class="align-middle me-1" data-feather="log-out"></i> Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{route('logout')}}" method="GET">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas for Profile -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasProfile" aria-labelledby="offcanvasProfileLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasProfileLabel">Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Profile Form -->
        <form id="profileForm" action="{{route('profile.update')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->nama_user }}" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->no_hp }}" readonly>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <input type="text" class="form-control" id="role" name="role" value="{{ auth()->user()->role }}" readonly>
            </div>

            <!-- Change Password Section (only visible when editing) -->
            <div id="changePasswordSection" class="d-none">
                <hr>
                <h5>Change Password</h5>
                <div class="mb-3">
                    <label for="currentPassword" class="form-label">Current Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="Show/Hide Password">
                            <a href="javascript:void(0)" onclick="togglePassword('currentPassword')">
                                <i class="fas fa-eye" id="toggleCurrentPassword"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="Show/Hide Password">
                            <a href="javascript:void(0)" onclick="togglePassword('newPassword')">
                                <i class="fas fa-eye" id="toggleNewPassword"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirmNewPassword" name="newPassword_confirmation">
                        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="top" title="Show/Hide Password">
                            <a href="javascript:void(0)" onclick="togglePassword('confirmNewPassword')">
                                <i class="fas fa-eye" id="toggleConfirmNewPassword"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function togglePassword(id) {
                    var x = document.getElementById(id);
                    if (x.type === "password") {
                        x.type = "text";
                        document.getElementById("toggle" + id.charAt(0).toUpperCase() + id.slice(1)).className = "fa fa-eye-slash";
                    } else {
                        x.type = "password";
                        document.getElementById("toggle" + id.charAt(0).toUpperCase() + id.slice(1)).className = "fa fa-eye";
                    }
                }
            </script>
            
            <!-- Edit and Save Buttons -->
            <button type="button" id="editButton" class="btn btn-primary"><i class="fas fa-pencil me-2"></i> Edit Profile</button>
            <div id="editActions" class="d-none">
                <div class="btn-group w-100" role="group" aria-label="Edit Profile Actions">
                    <button type="submit" id="saveButton" class="btn btn-success"><i class="fas fa-save me-2"></i> Save</button>
                    <button type="button" id="cancelButton" class="btn btn-danger"><i class="fas fa-times me-2"></i> Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Handle edit profile
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    const editActions = document.getElementById('editActions');
    const changePasswordSection = document.getElementById('changePasswordSection');

    const inputs = document.querySelectorAll('#profileForm input');

    editButton.addEventListener('click', () => {
        inputs.forEach(input => {
            if (input.name !== 'role') {
                input.removeAttribute('readonly');
            }
        });
        editButton.classList.add('d-none');
        editActions.classList.remove('d-none');
        changePasswordSection.classList.remove('d-none');
    });

    cancelButton.addEventListener('click', () => {
        inputs.forEach(input => input.setAttribute('readonly', true));
        document.getElementById('profileForm').reset();
        editButton.classList.remove('d-none');
        editActions.classList.add('d-none');
        changePasswordSection.classList.add('d-none');
    });

</script>