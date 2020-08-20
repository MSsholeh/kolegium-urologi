<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.store') }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama <span class="kt-font-danger">*</span> :</label>
                            <input type="text" name="name" required class="form-control" placeholder="Nama Admin">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Email <span class="kt-font-danger">*</span> :</label>
                            <input type="email" name="email" required class="form-control" placeholder="Alamat Email">
                            <span class="form-text text-muted">Email untuk login backoffice</span>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Password <span class="kt-font-danger">*</span> :</label>
                            <input type="password" name="password" required class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Universitas <span class="kt-font-danger">*</span> :</label>
                            <select name="university_id" class="form-control select2-university"></select>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
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
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"> {{ $role->name }}
                                    <span></span>
                                </label>
                                @endforeach
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
            minimumInputLength: 0
        });
    });
</script>
