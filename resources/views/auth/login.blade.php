<!DOCTYPE html>
<html lang="fa" dir="rtl" data-topbar-color="brand">
    <head>
        <meta charset="utf-8" />
        <title>بارمان سیستم | ورود</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="/assets/images/favicon.ico">
		<!-- App css -->
		<link href="/assets/css/bootstrap-rtl.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/app-rtl.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
		<!-- icons -->
		<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

		<!-- Theme Config Js -->
		<script src="/assets/js/config.js"></script>

        <style>
            /*#captcha_sec img {*/
            /*    cursor: pointer;*/
            /*}*/

            /*#captcha_sec input {*/
            /*    text-align: center !important;*/
            /*    letter-spacing: 1rem;*/
            /*}*/
        </style>
    </head>
    <body class="loading">
        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4">
                        <div class="card">

                            <div class="card-body p-4">

                                <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        <a href="javascript:void(0)" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <img src="/assets/images/img/logo-barman-red.png" alt="" height="60">
                                            </span>
                                        </a>

                                        <a href="javascript:void(0)" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="/assets/images/img/logo-barman-red.png" alt="" height="60">
                                            </span>
                                        </a>
                                    </div>
                                </div>

                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ request()->role }}">
                                    <div class="mb-2">
                                        <label for="phone" class="form-label">شماره موبایل</label>
                                        <input class="form-control" type="text" name="phone" id="phone" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="password" class="form-label">رمز عبور</label>
                                        <input type="password" id="password" name="password" class="form-control">
                                    </div>
                                    <div class="mb-2">
                                        <input type="checkbox" id="remember" name="remember">
                                        <label for="remember" class="form-label">منو به خاطر داشته باش</label>
                                    </div>
                                    <div class="form-group text-center mt-3 mb-4" id="captcha_sec">
                                        <div class="container-fluid d-flex justify-content-center align-items-center">
                                            <div class="g-recaptcha" data-sitekey="{{config('services.recaptcha.sitekey')}}"></div>
                                        </div>
                                        @error ('g-recaptcha-response')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="d-grid mb-0 text-center">
                                        <button class="btn btn-primary" type="submit"> ورود </button>
                                    </div>
                                    @error('phone')
                                        <span class="invalid-feedback text-danger text-center d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @error('notAllow')
                                        <span class="invalid-feedback text-danger text-center d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->
        <!-- Vendor js -->
        <script src="/assets/js/vendor.min.js"></script>
        <!-- App js -->
        <script src="/assets/js/app.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js?hl=fa" async defer></script>
    </body>
</html>
