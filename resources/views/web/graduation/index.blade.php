@extends('web.layout.content')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/pages/support-center/faq.css') }}">

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

        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-infobox">
                            <div class="kt-infobox__header">
                                <h2 class="kt-infobox__title">Pendaftaran Ujian Nasional</h2>
                            </div>
                            <div class="kt-infobox__body">
                                @if($registered)
                                    <div class="alert alert-solid-info alert-bold" role="alert">
                                        <div class="alert-text">Anda telah mendaftar ujian nasional kolegium urologi.</div>
                                    </div>
                                @endif
                                <p>Pendaftaran yang sedang dibuka :</p>

                                <div class="kt-widget11">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th style="width:1%"></th>
                                                <th style="width:44%">Deskripsi</th>
                                                <th style="width:25%">Tanggal Pembukaan</th>
                                                <th>Pendaftaran</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @if( ! $graduations)
                                                <tr>
                                                    <td colspan="5" class="text-center">Belum ada Pendaftaran</td>
                                                </tr>
                                            @else

                                            @foreach($graduations as $graduation)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}.
                                                    </td>
                                                    <td>{{ $graduation->note }}</td>
                                                    <td>{{ Daster::tanggal($graduation->created_at) }}</td>
                                                    <td>
                                                        @if($registered && $registered->requirement_graduation_id === $graduation->id)
                                                            <span class="btn btn-sm btn-label-success btn-bold">Telah Terdaftar</span>
                                                        @elseif($rejected && $rejected->where('requirement_graduation_id', $graduation->id)->count() > 0)
                                                            <span class="btn btn-sm btn-label-danger btn-bold">Tidak Lolos</span>
                                                        @elseif($progress && $progress->requirement_graduation_id === $graduation->id)
                                                            <span class="btn btn-sm btn-label-warning btn-bold">Dalam Proses</span>
                                                        @elseif(!$registered && !$progress)
                                                        <a href="{{ route('web.registration-graduation.register', $graduation->id) }}" class="kt-link kt-link--brand kt-font-bolder shoot">
                                                            Daftar
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="kt-widget11__action">
                                        {{ $graduations ? $graduations->links() : '' }}
                                    </div>
                                </div>

                                <!--end::Widget 11-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
