@extends('admin.layout.content')

@section('content')

    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __($title) }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    {!! Daster::breadcrumb($breadcrumbs) !!}
                </div>
            </div>
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">

                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--Begin::Dashboard 1-->

        <!--Begin::Row-->
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-light alert-elevate fade show" role="alert">
                    <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
                    <div class="alert-text">
                        {{ $exam->description }}
                    </div>
                </div>
            </div>
            <div class="col-lg-12">

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Jadwal Ujian</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <button type="button" data-size="lg" class="btn btn-secondary btn-sm" aria-expanded="false" id="l-toggle-filter">
                                    <i class="la la-search"></i>
                                    Filter
                                </button>
                                <a href="{{ route($route.'.schedule.create', $exam->id) }}" class="btn btn-bold btn-twitter btn-sm btn-icon-h kt-margin-l-10 shoot-modal">
                                    <i class="flaticon2-plus"></i>
                                    Tambah
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <form class="kt-form kt-form--fit kt-margin-b-20 hidden" id="kt_filter" style="display: none;">
                            <div class="row kt-margin-b-20">
                                <div class="col-lg-6 kt-margin-b-10-tablet-and-mobile">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <input type="text" class="form-control kt-input" placeholder="Nama ujian" data-col-index="1">
                                    </div>
                                </div>
                                <div class="col-lg-6 kt-margin-b-10-tablet-and-mobile">
                                    <div class="form-group">
                                        <label>Convenor</label>
                                        <input type="text" class="form-control kt-input" placeholder="Convenor" data-col-index="3">
                                    </div>
                                </div>
                                <div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <input type="text" class="form-control kt-input" placeholder="Lokasi" data-col-index="2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-primary btn-brand--icon btn-sm" id="kt_search">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>Cari</span>
                                </span>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-secondary btn-secondary--icon btn-sm" id="kt_reset">
                                <span>
                                    <i class="la la-close"></i>
                                    <span>Kembalikan</span>
                                </span>
                                    </button>
                                </div>
                            </div>
                            <div class="kt-separator kt-separator--md kt-separator--dashed"></div>
                        </form>

                        <!--begin: Datatable -->
                        <table class="table table-hover table-checkable" id="table-schedule">
                            <thead>
                            <tr>
                                <th width="35px">No</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Convenor</th>
                                <th>Jumlah Peserta</th>
                                <th width="150px">Pembuat & Tanggal</th>
                                <th width="160px">Aksi</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- end: Datatable -->
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            Daster.initTable('#table-schedule', {
                url: '{{ route($route.'.schedule.table', $exam->id) }}',
                columns: [
                    { data: 'DT_RowIndex', searchable: false, orderable: false, className: 'dt-center' },
                    { data: 'event_date'},
                    { data: 'location' },
                    { data: 'convenor' },
                    { data: 'participant' },
                    { data: 'log' },
                    { data: 'action', searchable: false, orderable: false, className: 'dt-center' },
                ],
                order: [5, 'desc']
            });

        });
    </script>
@endpush
