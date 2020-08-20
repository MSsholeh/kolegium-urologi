<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.update', $value->id) }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        <input type="hidden" name="_method" value="PATCH" />
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama :</label>
                            <input type="text" name="name" readonly class="form-control" placeholder="Nama Admin" value="{{ $value->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Email :</label>
                            <input type="email" name="email" readonly class="form-control" placeholder="Alamat Email" value="{{ $value->email }}">
                            <span class="form-text text-muted">Email untuk login backoffice</span>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Universitas :</label>
                            <select name="university_id" class="form-control select2-university">
                                <option value="{{ $value->university_id }}">{{ $value->university->name ?? '' }}</option>
                            </select>
                        </div>
{{--                        <div class="col-lg-6">--}}
{{--                            <label class="form-control-label">Password :</label>--}}
{{--                            <input type="password" name="password" class="form-control" placeholder="Password">--}}
{{--                            <span class="form-text text-muted">Isi untuk mengganti password</span>--}}
{{--                        </div>--}}
                    </div>
                    <h3 class="kt-section__title">
                        Roles
                    </h3>
                    <div class="kt-section__content">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-control-label">Hak Akses :</label>
                                <div class="kt-checkbox-inline">
                                    @foreach($roles as $role)
                                    <label class="kt-checkbox">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" @if($value->hasRole($role->name)) checked @endif> {{ $role->name }}
                                        <span></span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-label-brand btn-bold btn-brand--icon btn-label-brand" form="form-modal" id="form-submit">
        <span><i class="la la-save"></i>Simpan</span>
    </button> &nbsp;&nbsp;
    <a href="javascript:;" data-dismiss="modal" class="kt-link kt-font-bold kt-font-danger">Batal</a>
</div>

<script type="text/javascript">
    $(function(){
        Daster.initValidate("#form-modal", {
            rules: {},
            modal: '#shoot-modal',
            datatable: '#main-table'
        });

        Daster.select2('.select2-university', {
            url: '{{ route('admin.select.university') }}',
            placeholder: 'Pilih Universitas',
            minimumInputLength: 0
        });
    });
</script>
