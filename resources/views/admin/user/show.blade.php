<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <form class="kt-form kt-form--label-right">
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                <div class="kt-section__body">
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Nomor KTP</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->nik }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">NPA IDI</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->npa }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Asal FK</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->university }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Nama Lengkap</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Tempat Lahir</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->pob }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Tanggal Lahir</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ Daster::tanggal($user->dob) }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Alamat</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->address }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Nomor Handphone</label>
                        <div class="col-lg-9 col-xl-6">
                            <p class="form-control-plaintext">: {{ $user->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <a href="javascript:;" data-dismiss="modal" class="kt-link kt-font-bold kt-font-danger">Tutup</a>
</div>
