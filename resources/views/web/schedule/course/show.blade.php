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

        <div class="col-md-12">

            <!--begin:: Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body kt-portlet__body--fit">

                    <!--begin::Widget -->
                    <div class="kt-widget kt-widget--project-1">
                        <div class="kt-widget__head">
                            <div class="kt-widget__label">
                                <div class="kt-widget__info p-0">
                                    <a href="#" class="kt-widget__title">
                                        {{ $schedule->course->name }}
                                    </a>
                                    <span class="kt-widget__desc">
                                        {{ $schedule->location }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__body">
                            <div class="kt-widget__stats">
                                <div class="kt-widget__item p-0">
                                    <span class="kt-widget__date">
                                        Tanggal Kursus
                                    </span>
                                    <div class="kt-widget__label">
                                        <span class="btn btn-label-brand btn-sm btn-bold btn-upper">{{ $schedule->event_date_format }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="kt-widget__text">
                                {{ $schedule->description }}
                            </span>
                            <div class="kt-widget__content">
                                <div class="kt-widget__details">
                                    <span class="kt-widget__subtitle">Convenor</span>
                                    <span class="kt-widget__value">{{ $schedule->convenor }}</span>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__subtitle">Kuota Peserta</span>
                                    <span class="kt-widget__value">{{ $schedule->quota }} peserta</span>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__subtitle">Jumlah Pendaftar</span>
                                    <span class="kt-widget__value">{{ $participant > 0 ? $participant.' orang' : 'Belum ada' }}</span>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__subtitle">Peserta Lolos Administrasi</span>
                                    <span class="kt-widget__value">{{ $schedule->countApprovedParticipant() }} peserta</span>
                                </div>
                                <div class="kt-widget__details" style="flex-grow: 1">
                                    <span class="kt-widget__subtitle">Kuota Terpenuhi</span>
                                    <span class="kt-widget__value">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar kt-bg-brand" role="progressbar" style="width: {{ $quotaPercent }}%" aria-valuenow="{{ $quotaPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span>{{ $quotaPercent }}%</span>
                                    </span>
                                </div>
                            </div>

                            @if( $registered && $approved )

                            <span class="kt-widget__text">
                                <a href="{{ route('web.schedule.course.subject', $schedule->id) }}" class="btn btn-brand btn-sm btn-upper btn-bold shoot">Lihat Materi Kursus</a>
                            </span>

                            @endif

                        </div>
                        <div class="kt-widget__footer">
                            <div class="kt-widget__wrapper">
                                <div class="kt-widget__section">
                                    <div class="kt-widget__blog">
                                        <i class="flaticon2-calendar-1"></i>
                                        <span class="kt-widget__value kt-label-font-color-1">
                                            dibuat {{ $schedule->getPublishedInDays() }}
                                        </span>
                                    </div>
                                    <div class="kt-widget__blog">
                                        <i class="flaticon2-chronometer"></i>
                                        <span class="kt-widget__value kt-font-brand">
                                            pendaftaran berakhir {{ $schedule->getEndInDays() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="kt-widget__section">
                                    @if(Auth::check() && ! $registered && $quotaPercent < 100)
                                    <a href="{{ route('web.schedule.course.register', $schedule->id) }}" class="btn btn-brand btn-sm btn-upper btn-bold shoot">Daftar Sebagai Peserta</a>
                                    @elseif($registered)
                                        <span class="btn btn-label-{{ config('constant.validation_status.badge.'.$registered->status) }}">Pendaftaran {{ $registered->status }}</span>
                                    @elseif($quotaPercent < 100 && $schedule->getEndRemaining() < 0)
                                        <a href="{{ route('web.login') }}" class="btn btn-brand btn-sm btn-upper btn-bold">Daftar Sebagai Peserta</a>
                                    @elseif($schedule->getEndRemaining() >= 0)
                                        <span class="btn btn-label-danger">Pendaftaran berakhir</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end::Widget -->
                </div>
            </div>

            <!--end:: Portlet-->
        </div>

        @if($registered)

        <div class="col-md-12">

            <!--begin:: Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Persyaratan Pendaftaran
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget">
                        <div class="kt-widget4">

                            @foreach($requirements->requirements as $requirement)

                            <div class="kt-widget4__item">
                                <div class="kt-widget4__info">

                                    <span class="kt-widget4__username">
                                        {{ $requirement->requirement->name }}
                                    </span>

                                    @if($requirement->requirement->type === 'Text')

                                    <p class="kt-widget4__text">
                                        {{ $requirement->value }}
                                    </p>

                                    @elseif($requirement->requirement->type === 'Checkbox')

                                    <p class="kt-widget4__text">
                                        {{ $requirement->value ? 'Ya' : 'Tidak' }}
                                    </p>

                                    @elseif($requirement->requirement->type === 'File')

                                    <p class="kt-widget4__text">
                                        <a href="{{ route('web.file', ['path' => $requirement->value]) }}" target="_blank" class="btn btn-brand btn-icon"><i class="la la-file"></i></a>
                                    </p>

                                    @endif
                                </div>
                                @if($requirement->validated_at)
                                    @if($requirement->note)
                                    <span class="mr-3">
                                        Catatan: {{ $requirement->note }}
                                    </span>
                                    @endif
                                    <span class="btn btn-sm btn-label-{{ $requirement->validation ? 'success' : 'danger' }} btn-bold">
                                        {{ $requirement->validation ? 'Cukup' : 'Kurang' }}
                                    </span>
                                @else
                                    <span class="mr-3">
                                        Belum Diperiksa
                                    </span>
                                @endif
                            </div>

                            @endforeach

                        </div>
                    </div>
                    <small class="d-block mt-5">
                        Telah mendaftar pada hari {{ Daster::tanggal($registered->created_at, 2, true) }}
                    </small>
                </div>
            </div>

        </div>

        @endif

    </div>

@endsection
