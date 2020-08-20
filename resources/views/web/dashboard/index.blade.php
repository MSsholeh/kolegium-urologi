@extends('web.layout.content')

@section('content')

    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ $title }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    {!! Daster::breadcrumb($breadcrumbs) !!}
                </div>
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
                <!--begin:: Widgets/Applications/User/Profile3-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__media kt-hidden-">
                                    <img src="{{ asset('assets/media/users/default.jpg') }}" alt="image">
                                </div>
                                <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                    JM
                                </div>
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <a href="#" class="kt-widget__username">
                                            {{ $user->name }}
{{--                                            <i class="flaticon2-correct"></i>--}}
                                        </a>
                                    </div>
                                    <div class="kt-widget__subhead">
                                        <a href="#"><i class="flaticon2-new-email"></i>{{ $user->email }}</a>
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__desc">
{{--                                            <p class="text-muted">Belum pernah mendaftarkan ke prodi manapun.</p>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Applications/User/Profile3-->
            </div>
            <div class="col-md-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Status Pendaftaran
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th>Prodi Universitas</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Pendaftaran Ke</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if($registrants->count() === 0)
                                    <tr>
                                        <td colspan="5" class="text-center">Belum pernah mendaftar</td>
                                    </tr>
                                @endif

                                @foreach($registrants as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $item->university->name }}</td>
                                        <td>{{ Daster::tanggal($item->created_at, 1, true) }}</td>
                                        <td>{{ $item->submission }}</td>
                                        <td>
                                            <a href="{{ route('web.dashboard.detail', $item->id) }}" class="btn btn-sm btn-label-{{ config('constant.registrant_status.badge.'.$item->status) }} btn-bold">{{ config('constant.registrant_status.'.$item->status) }}</a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
