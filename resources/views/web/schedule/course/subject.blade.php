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

        @if ($subjects->hasPages())
        <div class="row">
            <div class="col-md-12">

                <!--begin:: Components/Pagination/Default-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">

                        <!--begin: Pagination-->
                        <div class="kt-pagination kt-pagination--brand">
                            {{ $subjects->links() }}
                        </div>

                        <!--end: Pagination-->
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">

            @foreach($subjects as $subject)
            <div class="col-md-3">

                <!--Begin::Portlet-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">

                        <!--begin::Widget -->
                        <div class="kt-widget kt-widget--user-profile-2">
                            <div class="kt-widget__head mt-0">
                                <div class="kt-widget__info pl-0">
                                    <span class="kt-widget__titel kt-hidden-">
                                        {{ $subject->title }}
                                    </span>
                                </div>
                            </div>
                            <div class="kt-widget__body">
                                <div class="kt-widget__section">
                                    {{ $subject->description }}
                                </div>
                            </div>
                            <div class="kt-widget__footer">
                                <a href="{{ route('web.file', ['path' => $subject->attachment]) }}" class="btn btn-label-warning btn-lg btn-upper">Unduh Materi</a>
                            </div>
                        </div>

                        <!--end::Widget -->
                    </div>
                </div>

                <!--End::Portlet-->
            </div>
            @endforeach
        </div>
    </div>

@endsection
