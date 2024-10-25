<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Admin Dashboard</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('back-end/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back-end/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('back-end/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back-end/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('back-end/assets/vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('back-end/assets/css/shared/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom background */
        .auth-bg-1 {
            background-image: url('{{ asset('back-end/assets/images/bg.jpg') }}');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Overlay */
        .auth-bg-1::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }

        /* Form container */
        .auto-form-wrapper {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        /* Button styles */
        .btn-primary {
            background: linear-gradient(90deg, #ff7e5f, #feb47b);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #feb47b, #ff7e5f);
        }

        /* Input field icons */
        .form-group i {
            position: absolute;
            right: 15px;
            top: 12px;
            font-size: 20px;
            color: #888;
        }

        .form-group {
            position: relative;
        }

        /* Google login button */
        .g-login {
            border: 1px solid #ddd;
        }

        .g-login img {
            width: 24px;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
                <div class="row w-100">
                    <div class="col-lg-4 mx-auto">
                        <div class="auto-form-wrapper">
                            <h3 class="text-center mb-4">Welcome Back</h3>
                            @if (Session::has('error') )
                            <div class="alert alert-warning alert-dismissible fade show d-flex justify-content-between align-items-center px-2" role="alert">
                                <p class="mb-0">{{ Session::get('error') }}</p>
                                <i class="bi bi-x-circle btn" data-bs-dismiss="alert" aria-label="Close"></i>
                            </div>
                        @endif
                            <form action="{{ route('auth.authenticate') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Email">
                                    <i class="mdi mdi-email-outline"></i>
                                    @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="label">Password</label>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="*********">
                                    <i class="mdi mdi-lock-outline"></i>
                                    @error('password')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary submit-btn btn-block">Login</button>
                                </div>
                                <div class="form-group d-flex justify-content-between">
                                    <div class="form-check form-check-flat mt-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" checked> Keep me signed in
                                        </label>
                                    </div>
                                    <a href="#" class="text-small forgot-password text-black">Forgot Password?</a>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-block g-login">
                                        <img src="{{ asset('back-end/assets/images/file-icons/icon-google.svg') }}"
                                            alt="">Log in with Google
                                    </button>
                                </div>
                                <div class="text-block text-center my-3">
                                    <span class="text-small font-weight-semibold">Not a member?</span>
                                    <a href="register.html" class="text-black text-small">Create new account</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- plugins:js -->
    <script src="{{ asset('back-end/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('back-end/assets/vendors/js/vendor.bundle.addons.js') }}"></script>
    <script src="{{ asset('back-end/assets/js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('back-end/assets/js/shared/misc.js') }}"></script>
    <script src="{{ asset('back-end/assets/js/shared/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
