@extends('web.layout.content')

@section('content')

    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ $title }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc"></span>

                {!! Daster::breadcrumb($breadcrumbs) !!}

            </div>

            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                    <span class="btn kt-subheader__btn-secondary">{{ Daster::tanggal(now(), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content Head -->

    <div class="kt-container  kt-grid__item kt-grid__item--fluid">

        <div class="row justify-content-center">
            <div class="col-md-8">

            <div class="kt-portlet kt-bg-brand kt-portlet--skin-solid kt-portlet--height-fluid">
                <div class="kt-portlet__head kt-portlet__head--noborder">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Pendaftaran Berhasil
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                    </div>
                </div>
                <div class="kt-portlet__body">

                    <!--begin::Widget 7-->
                    <div class="kt-widget7 kt-widget7--skin-light">
                        <div class="kt-widget7__desc">
                            Pendaftaran telah berhasil disimpan.
                        </div>
                        <div class="kt-widget7__content">
                            <div class="kt-widget7__info">
{{--                                <h3 class="kt-widget7__username">--}}
{{--                                    Nick Mana--}}
{{--                                </h3>--}}
                                <span class="kt-widget7__time">
                                    Tunggu pemberitahuan berikutnya.
                                </span>
                            </div>
                        </div>
                        <div class="kt-widget7__button">
                            <a class="btn btn-success" href="{{ route('web.dashboard.home') }}" role="button">Kembali ke Dashboard</a>
                        </div>
                    </div>

                    <!--end::Widget 7-->
                </div>
            </div>

        </div>
        </div>

    </div>

@endsection
