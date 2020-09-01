<!DOCTYPE html>

<!--
    DASTER
    Ali Alghozali - alghoza.li
-->
<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} - Reset Password</title>
    <meta name="description" content="Login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Barlow:300,400,500,700|Roboto:300,400,500,600,700">

    <!--end::Fonts -->

    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('assets') }}/media/logo/logo-kui.ico" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<div class="container">
    <nav class="navbar navbar-expand-sm fixed-top bg-dark navbar-dark">
      <a class="navbar-brand" style="padding-left: 10px;">
        <img src="{{ asset('assets') }}/media/logo/logo-light.png" width="190" height="50" class="d-inline-block align-top" alt="">
      </a>

      <ul class="navbar-nav ml-auto" style="margin-right:20px">
        <li class="nav-item">
          <a class="nav-link" href="https://kolegium-urologi.id">Website</a>
        </li>
        @if(Auth::check())
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
            <span class="kt-badge kt-badge--username kt-badge--unified-light kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
            Hi, {{ auth()->user()->name }}
          </a>
          <div class="dropdown-menu">
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" target="_blank" class="dropdown-item">Keluar</button>
               </form>
          </div>
        </li>
        @endif
      </ul>
    </nav>
</div>

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root" style="margin-top:70px">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">

            <!--begin::Content-->
            <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">

                <!--begin::Head-->
                <div class="kt-login__head">
                </div>

                <!--end::Head-->

                <!--begin::Body-->
                <div class="kt-login__body">

                    <!--begin::Signin-->
                    <div class="kt-login__form">
                        <div class="kt-login__title">
                            <h3>Buat Ulang Password</h3>
                        </div>

                        <!--begin::Form-->
                        <form class="kt-form" action="{{ route('web.password.update') }}" method="POST" novalidate="novalidate" id="kt_login_form">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group">
                                <input class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Email" name="email" value="{{ $email ?? old('email') }}" autocomplete="off">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Password baru" name="password" autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Ketik ulang password" name="password_confirmation" autocomplete="new-password">

                            </div>

                            <!--begin::Action-->
                            <div class="kt-login__actions">
                                <button id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Simpan</button>
                            </div>

                            <!--end::Action-->
                        </form>

                        <!--end::Form-->
                    </div>

                    <!--end::Signin-->
                </div>

                <!--end::Body-->
            </div>

            <!--end::Content-->
        </div>
    </div>
</div>

<!-- end:: Page -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": [
                    "#c5cbe3",
                    "#a1a8c3",
                    "#3d4465",
                    "#3e4466"
                ],
                "shape": [
                    "#f0f3ff",
                    "#d9dffa",
                    "#afb4d4",
                    "#646c9a"
                ]
            }
        }
    };

    let baseUrl = '{{ url('/') }}';
    let homeUrl = '{{ route('web.registration.index') }}';
</script>

<!-- end::Global Config -->

<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>

</body>

<!-- end::Body -->
</html>
