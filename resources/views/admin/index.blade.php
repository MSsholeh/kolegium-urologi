@extends('admin.layout.content')

@section('content')

    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Dashboard</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc">
                    Statistik Pendaftaran
                    @if($periodFilter)
                       - {{ $periodFilter->name }}
                    @endif
                </span>
            </div>
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                    <a href="#" class="btn kt-subheader__btn-secondary dropdown-toggle" data-toggle="dropdown">
                        Periode Pendaftaran
                    </a>
                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                        <ul class="kt-nav">
                            @foreach($periods as $period)
                            <li class="kt-nav__item">
                                <a href="{{ route('admin.home', $period->id) }}" class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-calendar-4"></i>
                                    <span class="kt-nav__link-text">{{ $period->name }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__body">
                <div class="row row-no-padding row-col-separator-xl">
                    <div class="col-md-12 col-lg-12 col-xl-4">



                        <!--begin:: Widgets/Stats2-1 -->
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Jumlah Total Akun</h3>
                                    <span class="kt-widget1__desc">Akun Terdaftar</span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand">{{ $users }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Jumlah Lolos</h3>
                                    <span class="kt-widget1__desc">Pendaftar Lolos Validasi</span>
                                </div>
                                <span class="kt-widget1__number kt-font-danger">{{ $approved }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-4">

                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Jumlah Gagal</h3>
                                    <span class="kt-widget1__desc">Pendaftar Gagal Validasi</span>
                                </div>
                                <span class="kt-widget1__number kt-font-success">{{ $rejected }}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Jumlah Dalam Proses</h3>
                                    <span class="kt-widget1__desc">Pendaftar Dalam Proses Validasi</span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand">{{ $process }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-4">

                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Jumlah Pendaftaran</h3>
                                    <span class="kt-widget1__desc">Pendaftaran Dibuka</span>
                                </div>
                                <span class="kt-widget1__number kt-font-danger">{{ $university }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content -->
@endsection

@push('scripts')
    <script>
        $(function(){

        });
    </script>
@endpush
