@extends('web.layout.content')

@section('css')

@endsection
@section('content')
<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root" id="kt_content" style="margin-top:70px">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">


            <!--begin::Content-->
            <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">



                    <div class="row">
				<div class="col-12">
					<h1 class="text-center">Database Resident</h1><br>
				</div>
				<div class="col-9 mx-auto">
					<div class="accordion" id="accordionResident">
                        @foreach($universities as $university)
						<div class="card">
							<div class="card-header" id="heading{{$university->id}}">
								<h5 class="mb-0">
                                    <button class="btn btn-link btn-lg btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$university->id}}" aria-expanded="true" aria-controls="collapse{{$university->id}}" style="text-decoration: none;">
                                        <i class="fa fa-angle-double-right mr-3"></i>FK {{ strtoupper($university->name) }}
                                    </button>
                                </h5>
							</div>

							<div id="collapse{{$university->id}}" class="collapse fade" aria-labelledby="heading{{$university->id}}" data-parent="#accordionResident">
								<div class="card-body">
                                    <table id="" class="table display table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Tahap Kompetensi</th>
                                                <th>NPA IDI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no=1; @endphp
                                            @foreach($query as $data)
                                            @if($data->university->id == $university->id)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $data->user->name }}</td>
                                                    <td>{{ $data->user->tahap_kompetensi }}</td>
                                                    <td>{{ $data->user->npa }}</td>
                                                </tr>
                                                @php $no++; @endphp
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
								</div>
							</div>
						</div>
                        @endforeach

					</div>
				</div>
			</div>

                    <!--end::Signin-->
                </div>

                <!--end::Body-->
            </div>

            <!--end::Content-->
    </div>


@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('table.display').DataTable({
        fixedHeader: {
            header: true,
            footer: false
        },
        filter: true,
        responsive: true,
    });
});
</script>
@endpush
