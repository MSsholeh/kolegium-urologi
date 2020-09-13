<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="Login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Barlow:300,400,500,700|Roboto:300,400,500,600,700">

    <!--end::Fonts -->

    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('assets') }}/media/logo/logo-kui.ico" />
    <style>
        .stepwizard-step p {
            margin-top: 10px;
        }
        .stepwizard-row {
            display: table-row;
        }
        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }
        .stepwizard-step button[disabled] {
            opacity: 1 !important;
            filter: alpha(opacity=100) !important;
        }
        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-order: 0;
        }
        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }

        .hide{
            display:none;
        }
    </style>
    @stack('css')
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<div class="container">
    <nav class="navbar navbar-expand-sm fixed-top bg-dark navbar-dark">
      <a class="navbar-brand" style="padding-left: 10px;">
        <img src="{{ asset('assets') }}/media/logo/logo-light.png" width="200" height="70" class="d-inline-block align-top" alt="">
      </a>

      @if(Auth::check())
      <ul class="navbar-nav ml-auto" style="margin-right:20px">
<!--
        <li class="nav-item">
          <a class="nav-link p-3" href="https://kolegium-urologi.id">Website</a>
        </li>
-->
        <!-- Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
            Hi, {{ auth()->user()->name }} <span class="kt-badge kt-badge--username kt-badge--unified-light kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
          </a>
          <div class="dropdown-menu">
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" target="_blank" class="dropdown-item">Keluar</button>
               </form>
          </div>
        </li>
      </ul>
      @endif
    </nav>
</div>


@yield('content')

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
</script>

<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
@stack('scripts')

<!--end::Page Scripts -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-176287339-4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-176287339-4');
</script>

</body>

<!-- end::Body -->
</html>
