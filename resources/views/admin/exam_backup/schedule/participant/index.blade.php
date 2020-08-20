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
                            <h3 class="kt-portlet__head-title">Peserta Ujian</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <button type="button" data-size="lg" class="btn btn-secondary btn-sm" aria-expanded="false" id="l-toggle-filter">
                                    <i class="la la-search"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <form class="kt-form kt-form--fit kt-margin-b-20 hidden" id="kt_filter" style="display: none;">
                            <div class="row kt-margin-b-20">
                                <div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control kt-input" placeholder="Nama" data-col-index="1">
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
                        <table class="table table-hover table-checkable" id="table-main">
                            <thead>
                            <tr>
                                <th width="35px">No</th>
                                <th>Nama</th>
                                <th>Kelulusan</th>
                                <th>Status Pendaftaran</th>
                                <th style="width: 170px;">Tanggal Daftar</th>
                                <th width="120px">Aksi</th>
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
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {

            Daster.initTable('#table-main', {
                url: '{{ route($route.'.table', [$exam->id, $schedule->id]) }}',
                columns: [
                    { data: 'DT_RowIndex', searchable: false, orderable: false, className: 'dt-center' },
                    { data: 'name'},
                    { data: 'graduate'},
                    { data: 'status'},
                    { data: 'created_at' },
                    { data: 'action', searchable: false, orderable: false, className: 'dt-center' },
                ],
                order: [4, 'desc'],
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel', {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4 ]
                        }
                    },
                ]
            });

        });
    </script>
@endpush
