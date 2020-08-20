<!DOCTYPE html>

<!--
    DASTER
    Metronic 6.1.8 & Larvel 7.x

    Ali ALghozali - alghoza.li
-->
<html lang="en">

<!-- begin::Head -->
<head>
    <base href="">
    <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="Updates and statistics">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@csrf

<!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600,700|Solway:300,400,500,600,700">

    <!--end::Fonts -->
    <link href="{{ asset('assets') }}/css/app.css" rel="stylesheet" type="text/css" />
{{--    <link href="{{ asset('admin') }}/plugins/datatables.css" rel="stylesheet" type="text/css" />--}}

<!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/media/logos/favicon.ico" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-page--loading">

<!-- begin:: Page -->

<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header-mobile__logo">
        <a href="index.html">
            <img alt="Logo" src="assets/media/logos/logo-light.png" />
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
        <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
        <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
    </div>
</div>

<!-- end:: Header Mobile -->
<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

        <!-- begin:: Aside -->

        <!-- Uncomment this to display the close button of the panel
<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
-->

        <!-- end:: Aside -->
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

            <!-- begin:: Header -->
            <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

                <!-- begin:: Header Menu -->

                <!-- Uncomment this to display the close button of the panel
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
-->
                <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                    <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
                        <div class="p-3">
                            <a href="{{ route('web.home') }}">
                                <img src="{{ asset('assets') }}/media/logo/logo-kui-hitam.png" alt="Logo" style="height: 34px;">
                                <span></span>
                            </a>
                        </div>
                        <ul class="kt-menu__nav ">

                            @if(Auth::check())

                                <li class="kt-menu__item kt-menu__item--rel">
                                    <a href="{{ route('web.dashboard.home') }}" class="kt-menu__link shoot">
                                        <span class="kt-menu__link-text">Dashboard</span><i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                </li>
                                <li class="kt-menu__item kt-menu__item--rel">
                                    <a href="{{ route('web.schedule.index') }}" class="kt-menu__link shoot">
                                        <span class="kt-menu__link-text">Pendaftaran PPDS</span><i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                </li>

                                @php
                                    $registrant = App\Models\Registrant::where('user_id',auth()->user()->id)->where('status','Approve')->first();
                                    if(!empty($registrant)) { $exam = App\Models\ExamParticipant::where('registrant_id', $registrant->id)->where('graduate','Lulus')->first(); }
                                @endphp
                                @if(!empty($exam))
                                    <li class="kt-menu__item kt-menu__item--rel">
                                        <a href="{{ route('web.graduation.index') }}" class="kt-menu__link shoot">
                                            <span class="kt-menu__link-text">Pendaftaran Kelulusan</span><i class="kt-menu__ver-arrow la la-angle-right"></i>
                                        </a>
                                    </li>
                                @endif

                            @endif

{{--                            <li class="kt-menu__item kt-menu__item--rel">--}}
{{--                                <a href="{{ route('web.schedule.index') }}" class="kt-menu__link shoot">--}}
{{--                                    <span class="kt-menu__link-text">Jadwal Ujian & Kursus</span><i class="kt-menu__ver-arrow la la-angle-right"></i>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                </div>

                <!-- end:: Header Menu -->

                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">

                    @if(Auth::check())

                    <!--begin: User Bar -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                            <div class="kt-header__topbar-user">
                                <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi, {{ auth()->user()->name }}</span>
{{--                                <span class="kt-header__topbar-username kt-hidden-mobile">{{ auth()->user()->name }}</span>--}}
                                <img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" />

                                <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

                            <!--begin: Head -->
                            <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{ asset('admin') }}/assets/media/misc/bg-1.jpg)">
                                <div class="kt-user-card__avatar">
                                    <img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" />

                                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                    <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div class="kt-user-card__name">
                                    {{ auth()->user()->name }}
                                </div>
                                <div class="kt-user-card__badge">
                                    {{--                                    <span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>--}}
                                </div>
                            </div>

                            <!--end: Head -->

                            <!--begin: Navigation -->
                            <div class="kt-notification">
                                <a href="{{ route('web.dashboard.profile') }}" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-calendar-3 kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            Profile
                                        </div>
                                        <div class="kt-notification__item-time">
                                            Informasi Akun
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ route('web.dashboard.home') }}" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-browser-2 kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            Dashboard
                                        </div>
                                        <div class="kt-notification__item-time">
                                            Status Pendaftaran
                                        </div>
                                    </div>
                                </a>
                                <div class="kt-notification__custom kt-space-between">
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" target="_blank"
                                                class="btn btn-label btn-label-brand btn-sm btn-bold">Keluar</button>
                                    </form>
                                </div>
                            </div>

                            <!--end: Navigation -->
                        </div>
                    </div>

                    <!--end: User Bar -->

                    @else

                    <div class="kt-header__topbar-item mr-5">
                        <div class="kt-header__topbar-wrapper">
                            <span class="kt-header__topbar-icon">
                                <a href="{{ route('web.login') }}" class="btn btn-label-success btn-sm  btn-bold">Login</a>
                            </span>
                        </div>
                    </div>
                    <div class="kt-header__topbar-item mr-5">
                        <div class="kt-header__topbar-wrapper">
                            <span class="kt-header__topbar-icon">
                                <a href="{{ route('web.register') }}" class="btn btn-label-success btn-sm  btn-bold">Daftar</a>
                            </span>
                        </div>
                    </div>

                    @endif

                </div>

                <!-- end:: Header Topbar -->
            </div>

            <!-- end:: Header -->
            <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

                @yield('content')

            </div>

            <!-- begin:: Footer -->
            <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
                <div class="kt-container  kt-container--fluid ">
                    <div class="kt-footer__copyright">
                        2020&nbsp;&copy;&nbsp;
                    </div>
                    <div class="kt-footer__menu">
                    </div>
                </div>
            </div>

            <!-- end:: Footer -->
        </div>
    </div>
</div>

<!-- end:: Page -->

<!-- begin::Modal -->
<div class="modal fade" id="shoot-modal" role="dialog" aria-labelledby="shoot-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<!-- end::Modal -->

<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="fa fa-arrow-up"></i>
</div>

<!-- end::Scrolltop -->

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

    var baseUrl = '{{ url('/') }}';
</script>

<!-- end::Global Config -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>

{{--<script src="{{ asset('admin/plugins/datatables.js') }}" type="text/javascript"></script>--}}

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts(used by this page) -->
@stack('scripts')

<!--end::Page Scripts -->

</body>

<!-- end::Body -->
</html>
