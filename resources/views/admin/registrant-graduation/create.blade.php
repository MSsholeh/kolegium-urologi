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
                @if(auth()->user()->university_id == null)
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama <span class="kt-font-danger">*</span> :</label>
                            <select name="registrant_id" required class="select2-user-admin"></select>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                </div>
                @else
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama <span class="kt-font-danger">*</span> :</label>
                            <select name="registrant_id" required class="select2-user-universitas"></select>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="kt-section">
                <h3 class="kt-section__title">
                    Persyaratan Pendaftar Ujian Nasional
                </h3>
                <div class="kt-section__content">
                    <div class="form-group form-group-last">
                        <div class="alert alert-secondary" role="alert">
                            <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
                            <div class="alert-text">
                                {{ $requirement->note }}
                            </div>
                        </div>
                    </div>

                    @foreach($requirement->items as $item)

                        <div class="form-group">
                            <label>{{ $item->name }}</label>

                            @if($item->type === 'Text')

                                <input type="text" name="requirement_{{ $item->id }}" class="form-control" @if($item->required) required @endif placeholder="{{ $item->name }}">

                            @elseif($item->type === 'Checkbox')

                                <div>
                                    <input data-switch="true" name="requirement_{{ $item->id }}" type="checkbox" data-on-text="Ya" data-handle-width="50" data-off-text="Tidak" data-on-color="success">
                                </div>

                            @elseif($item->type === 'File')

                                <input type="file" name="requirement_{{ $item->id }}" class="form-control" @if($item->required) required @endif placeholder="{{ $item->name }}">

                            @endif

                            <span class="form-text text-muted"></span>
                        </div>

                    @endforeach
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

        Daster.select2('.select2-user-admin', {
            url: '{{ route('admin.select.useradmin') }}',
            minimumInputLength: 0
        });

        Daster.select2('.select2-user-universitas', {
            url: '{{ route('admin.select.useruniversitas', auth()->user()->university_id) }}',
            minimumInputLength: 0
        });

        $('#requirements').repeater({
            initEmpty: false,

            defaultValues: {
            },

            show: function () {
                $(this).slideDown();

                $(this).find('.select2-container').remove();

                $('.select-select2').select2();
                $(this).find('.select-select2').val('Text').trigger('change');

                $('[data-switch=true]').bootstrapSwitch();

            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        $('[data-switch=true]').bootstrapSwitch();

        Daster.select2('.select2-periode', {
            url: '{{ route('admin.select.period') }}',
            placeholder: 'Pilih Periode',
            minimumInputLength: 0
        });

    });
</script>
