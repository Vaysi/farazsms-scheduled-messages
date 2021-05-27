<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title','داشبورد') | فراز اس ام اس
    </title>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="{{ asset('css/admin.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/override.css') }}">
    @yield('css')
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <a href="{{ route('contacts') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">دفترچه تلفن</span>
                </a>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav mr-auto">

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="img-profile rounded-circle" src="{{ asset('images/default-100.jpg') }}">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ user()->name }}</span>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in text-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('settings') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                تنظیمات حساب
                            </a>
                            <a class="dropdown-item" href="{{ route('webservice') }}">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                تنظیمات وب سرویس
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                خروج
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
                    @yield('button')
                </div>

                <!-- Content Row -->
                <div class="row">
                    {{-- If User Did Not Set Settings Show Message --}}
                    @if(empty(user()->api_username) || empty(user()->api_password))
                        <div class="col-12">
                            <div class="alert alert-warning w-100 text-right d-flex justify-content-between align-items-center">
                                <span>شما تنظیمات وب سرویس فراز اس ام اس خود را وارد نکرده اید , برای استفاده از امکانات لطفا ابتدا اطلاعات را تکمیل کنید</span>
                                <a href="{{ route('webservice') }}" class="btn btn-primary btn-sm btn-icon-split">
                                    <span class="icon text-white-50">
                                      <i class="fas fa-cogs"></i>
                                    </span>
                                    <span class="text">تنظیمات وب سرویس</span>
                                </a>
                            </div>
                        </div>
                    @endif
                    {{-- Errors --}}
                    @if($errors->count())
                        <div class="col-12">
                            <ul class="alert alert-danger text-right">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>تمام حقوق این وبسایت محفوظ میباشد !</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/admin.min.js') }}"></script>

<script>
    let baseUrl = '{{ url('/') }}';
</script>
@include('sweetalert::alert')
@yield('js')
<script src="{{ asset('js/bootstrap.js') }}"></script>
</body>
</html>
