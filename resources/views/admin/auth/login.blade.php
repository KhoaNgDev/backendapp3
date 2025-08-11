                        <!DOCTYPE html>
                        <html lang="en">

                        <head>
                            <meta charset="UTF-8">
                            <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
                            <title>Login &mdash; Admin</title>
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <!-- General CSS Files -->
                            <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
                            <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

                            <!-- CSS Libraries -->
                            <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-social/bootstrap-social.css') }}">

                            <!-- Template CSS -->
                            <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
                            <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
                            <!-- Start GA -->
                            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
                            <script>
                                window.dataLayer = window.dataLayer || [];

                                function gtag() {
                                    dataLayer.push(arguments);
                                }
                                gtag('js', new Date());

                                gtag('config', 'UA-94034622-3');
                            </script>
                            <!-- /END GA -->
                        </head>

                        <body>

                            <div id="app">
                                <section class="section">
                                    <div class="container mt-5">
                                        <div class="row">
                                            <div
                                                class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                                                <div class="login-brand">
                                                    <img src="{{ asset('assets/img/stisla-fill.svg') }}" alt="logo" width="100"
                                                        class="shadow-light rounded-circle">
                                                    <h4 class="mt-2">Admin Panel</h4>
                                                </div>

                                                <div class="card card-primary">
                                                    <div class="card-header">
                                                        <h4>Đăng nhập</h4>
                                                    </div>

                                                    <div class="card-body">
                                                        <form method="POST" action="{{ route('login') }}" class="needs-validation"
                                                            novalidate="">
                                                            @csrf
                                                            @if ($errors->any())
                                                                <div class="alert alert-danger">
                                                                    <ul class="mb-0">
                                                                        @foreach ($errors->all() as $error)
                                                                            <li style="list-style:none">{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="login">Email hoặc Tên đăng nhập</label>
                                                                <input id="login" type="text"
                                                                    class="form-control
                                                                    @error('login') is-invalid @enderror"
                                                                    placeholder="Vui lòng nhập tài khoản tại đây.." name="login"
                                                                    value="{{ old('login') }}" required autofocus>

                                                                @error('login')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="d-block">
                                                                    <label for="password" class="control-label">Mật khẩu</label>

                                                                </div>
                                                                <input id="password" type="password"
                                                                    placeholder="Vui lòng nhập mật khẩu tại đây.."
                                                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                                                    required>

                                                                @error('password')
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" name="remember" class="custom-control-input"
                                                                        id="remember-me">
                                                                    <label class="custom-control-label" for="remember-me">Ghi nhớ đăng
                                                                        nhập</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                                                    Đăng nhập
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="simple-footer">
                                                    &copy; KaneNguyen {{ now()->year }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>


                            <!-- General JS Scripts -->
                            <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
                            <script src="{{ asset('assets/modules/popper.js') }}"></script>
                            <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
                            <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
                            <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
                            <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
                            <script src="{{ asset('assets/js/stisla.js') }}"></script>

                            <!-- JS Libraies -->

                            <!-- Page Specific JS File -->

                            <!-- Template JS File -->
                            <script src="{{ asset('assets/js/scripts.js') }}"></script>
                            <script src="{{ asset('assets/js/custom.js') }}"></script>
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                            <script type="text/javascript">
                                document.querySelector('form').addEventListener('submit', function(e) {
                                    const login = document.getElementById('login').value.trim();
                                    const password = document.getElementById('password').value.trim();

                                    if (!login || !password) {
                                        e.preventDefault();

                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Thiếu thông tin',
                                            text: 'Vui lòng nhập đầy đủ tài khoản và mật khẩu.',
                                            confirmButtonText: 'OK'
                                        });

                                        return false;
                                    }
                                });

                                document.addEventListener("DOMContentLoaded", function() {
                                    @if (session('message'))
                                        Swal.fire({
                                            title: 'Thành công!',
                                            text: "{{ session('message') }}",
                                            icon: 'success',
                                            confirmButtonText: 'OK',
                                            timer: 3000,
                                            timerProgressBar: true
                                        });
                                       @endif

                                    @if (session('error'))
                                        Swal.fire({
                                            title: 'Lỗi!',
                                            text: "{{ session('error') }}",
                                            icon: 'error',
                                            confirmButtonText: 'Đóng'
                                        });
                                    @endif
                                });
                            </script>
                        </body>

                        </html>
