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
                                <h2 class="kt-infobox__title">Pendaftaran PPDS</h2>
                            </div>
                            <div class="kt-infobox__body">
                                @if($registered)
                                    <div class="alert alert-solid-info alert-bold" role="alert">
                                        <div class="alert-text">Anda telah terdaftar di {{ $registered->university->name }}.</div>
                                    </div>
                                @elseif($submission < 2)
                                    <div class="alert alert-solid-warning alert-bold" role="alert">
                                        <div class="alert-text">Anda mempunyai kesempatan <b>{{ 2 - $submission }} kali</b> lagi untuk mendaftar.</div>
                                    </div>
                                @else
                                    <div class="alert alert-solid-danger alert-bold" role="alert">
                                        <div class="alert-text">Anda sudah tidak mempunyai kesempatan untuk mendaftar.</div>
                                    </div>
                                @endif

                                @if($progress)
                                    <div class="alert alert-solid-brand alert-bold" role="alert">
                                        @if($submission < 2)
                                        <div class="alert-text">Anda mempunyai 1 pendaftaran dalam proses, Anda belum bisa mendaftar ke pendaftaran lain sampai proses selesai.</div>
                                        @else
                                        <div class="alert-text">Anda mempunyai 1 pendaftaran dalam proses.</div>
                                        @endif
                                    </div>
                                @endif
                                <p>Pendaftaran yang sedang dibuka :</p>

                                <div class="kt-widget11">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th style="width:1%"></th>
                                                <th style="width:20%">Prodi Universitas</th>
                                                <th style="width:34%">Deskripsi</th>
                                                <th style="width:15%">Tanggal Pembukaan</th>
                                                <th>Pendaftaran</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @if( ! $schedules)
                                                <tr>
                                                    <td colspan="5" class="text-center">Belum ada Pendaftaran</td>
                                                </tr>
                                            @else

                                            @foreach($schedules as $schedule)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}.
                                                    </td>
                                                    <td>
                                                        {{ $schedule->university->name }}
                                                    </td>
                                                    <td>{{ $schedule->note }}</td>
                                                    <td>{{ Daster::tanggal($schedule->created_at) }}</td>
                                                    <td>
                                                        @if($registered && $registered->requirement_id === $schedule->id)
                                                            <span class="btn btn-sm btn-label-success btn-bold">Telah Terdaftar</span>
                                                        @elseif($rejected && $rejected->where('requirement_id', $schedule->id)->count() > 0)
                                                            <span class="btn btn-sm btn-label-danger btn-bold">Tidak Lolos</span>
                                                        @elseif($progress && $progress->requirement_id === $schedule->id)
                                                            <span class="btn btn-sm btn-label-warning btn-bold">Dalam Proses</span>
                                                        @elseif(!$registered && !$progress && $submission < 2)
                                                        <a href="{{ route('web.registration.register', $schedule->id) }}" class="kt-link kt-link--brand kt-font-bolder shoot">
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
                                        {{ $schedules ? $schedules->links() : '' }}
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
