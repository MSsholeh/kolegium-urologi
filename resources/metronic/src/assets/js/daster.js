var Daster = (function() {

    const mainContent = $('#kt_content');
    let previousUrl = window.location.href;

    const ajaxSetup = function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    };

    const isSidebarLink = function (self) {
        const ele = $(self).hasClass('kt-menu__link');
        if (ele) {
            $('.kt-menu__item.kt-menu__item--active').removeClass('kt-menu__item--active');
            $(self).closest('.kt-menu__item').addClass('kt-menu__item--active');
        }
    };

    const loadAjax = function (url, options) {
        const currentUrl = window.location.href;
        options = $.extend(true, {
            content: mainContent,
            block: $('#kt_content'),
            history: true,
            message: 'Loading...'
        }, options);

        const pageContent = $(options.content);

        if (options.history) {
            previousUrl = url;
        }

        KTApp.block(options.block, {
            overlayColor: '#000000',
            type: 'v2',
            state: 'success',
            message: options.message
        });

        return $.ajax({
            type: "GET",
            cache: false,
            url: url,
            dataType: "html",
            success: function (response) {
                KTApp.unblock(options.block);
                pageContent.html(response);
                $('.tooltip').remove();
            },
            error: function (response) {
                KTApp.unblock(options.block);
                if (response.status === 401) {
                    window.location = baseUrl + 'login';
                } else if (response.status === 403) {
                    toastr.error('Anda tidak mempunyai hak akses ke menu yang dituju.')
                } else {
                    toastr.error('Halaman gagal dimuat.');
                }
                previousUrl = currentUrl;
            }
        });
    };

    const handleAjaxLink = function () {

        $('a.shoot').unbind('click').click(function (e) {

            if ( ! $(mainContent)) {
                return;
            }

            e.preventDefault();
            const url = $(this).attr('href');

            if (previousUrl === url) {
                return false;
            }

            isSidebarLink(this);

            loadAjax(url).then(function () {
                history.pushState(null, null, url);
            });

        });
    };

    const handleBack = function () {
        $(window).bind('popstate', function () {
            var url = location.href;

            loadAjax(url);

        });
    };

    const handleAjaxModal = function () {
        $('a.shoot-modal').unbind('click').click(function (e) {
            e.preventDefault();

            const modalContent = $('#shoot-modal .modal-content');
            if (!$(modalContent)) return;

            const options = $(this).data();
            const size = options.size || 'lg';
            const url = this.href;

            $('#shoot-modal .modal-dialog')
                .removeClass('modal-sm modal-md modal-lg modal-xl')
                .addClass('modal-' + size);

            loadAjax(url, {content: modalContent, block: mainContent, history: false}).then(function () {
                $('#shoot-modal').modal('show');

                Daster.initAjax();
            });

        });

    };

    const reloadDatatable = function(element) {

        if ( ! $.fn.DataTable.isDataTable( element )) {
            return false;
        }

        $.fn.DataTable.Api(element).ajax.reload();
    };

    const dataTable = function (target, options) {

        const $el = $(target);

        if ($.fn.DataTable.isDataTable( target )) {
            return false;
        }

        options = $.extend(true, {
            url: '',
            columns: [],
            order: [],
            dom: "<'row'<'col-sm-12 col-md-6'l>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [],
        }, options);

        $el.DataTable({
            serverSide: true,
            processing: true,
            scrollX: true,
            searching: true,
            ajax: options.url,
            columns: options.columns,
            order: options.order,
            buttons: options.buttons,
            dom: options.dom,
            drawCallback: function () {

                KTApp.initTooltips();

                $('.action-edit').click(function (event) {
                    event.preventDefault();
                    loadAjax(this.getAttribute('href'), {
                        content: $('#shoot-modal .modal-content'),
                        block: $('#kt_content'),
                        history: false
                    }).then(function () {
                        $('#shoot-modal').modal('show');
                        Daster.initAjax();
                    });
                });

                $('.action-delete').click(function (event) {
                    event.preventDefault();
                    var self = this;

                    swal.fire({
                        title: "Anda yakin?",
                        text: "Data akan dihapus",
                        type: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-danger",
                        cancelButtonClass: "btn btn-outline-secondary",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return $.post(self.getAttribute('href'), {_method: 'DELETE'});
                        }
                    }).then(function (result) {

                        if ( ! result.value) {
                            return false;
                        }

                        if (result.value && result.value.success) {

                            swal.fire(
                                'Dihapus!',
                                'Data telah dihapus.',
                                'success'
                            );

                            reloadDatatable(target);

                        } else {

                            swal.fire(
                                'Gagal!',
                                'Data gagal dihapus.',
                                'error'
                            );

                        }
                    });

                });

                $('.action-confirm').click(function (event) {
                    event.preventDefault();
                    var self = this;
                    var title = $(this).data('confirm');

                    swal.fire({
                        title: `Apakah anda yakin untuk ${title}?`,
                        type: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-danger",
                        cancelButtonClass: "btn btn-outline-secondary",
                        confirmButtonText: "Ya, Yakin!",
                        cancelButtonText: "Batal",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return $.post(self.getAttribute('href'), {_method: 'GET'});
                        }
                    }).then(function (result) {
                        if (!result.value) return false
                        if (result.value && result.value.success) {
                            swal.fire(
                                `Di ${title}!`,
                                'Data telah dikonfirmasi.',
                                'success'
                            );
                            reloadDatatable(target);
                        } else {
                            swal.fire(
                                'Gagal!',
                                'Aksi gagal dilakukan.',
                                'error'
                            );
                        }
                    });

                });

                $('.action-other').click(function (event) {
                    event.preventDefault();

                    KTApp.block(mainContent, {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'Loading'
                    });

                    $.get($(this).attr('href'), function (response) {
                        KTApp.unblock(mainContent);
                        if (response.success) {
                            toastr.success(response.message);
                            reloadDatatable(target);
                        } else {
                            toastr.error(response.message);
                        }
                    });
                });

                handleAjaxLink();

            }
        });

        const table = $.fn.DataTable.Api(target);

        $('#kt_search').on('click', function (e) {
            e.preventDefault();
            var params = {};
            $('.kt-input').each(function () {
                var i = $(this).data('col-index');
                if (params[i]) {
                    params[i] += '|' + $(this).val();
                } else {
                    params[i] = $(this).val();
                }
            });
            $.each(params, function (i, val) {
                table.column(i).search(val ? val : '', false, false);
            });
            table.table().draw();
        });

        $('#kt_reset').on('click', function (e) {
            e.preventDefault();
            $('.kt-input').each(function (i, obj) {
                $(this).val('').trigger('change');
                table.column($(this).data('col-index')).search('', false, false);
            });
            table.table().draw();
        });

        $('#kt_aside_toggler').click(function () {
            setTimeout(function(){
                table.columns.adjust().draw();
            }, 500);
        });

        $('#dt-trash').click(function () {
            const $btn = $(this);

            $btn.toggleClass('btn-danger');
            $btn.toggleClass('btn-success');

            if ($btn.hasClass('trashed')) {
                $btn.find('span').text('Sampah');
                let url = table.ajax.url();
                table.ajax.url(url.replace('?trashed=1', '')).load();
            } else {
                $btn.find('span').text('Kembali');
                table.ajax.url(table.ajax.url() + '?trashed=1').load();
            }

            $btn.toggleClass('trashed');

        });

        Daster.toggleFilter();
    };

    const initDefaultDatepicker = function () {

        moment.locale('id');

        !function(a){a.fn.datepicker.dates.id={days:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],daysShort:["Mgu","Sen","Sel","Rab","Kam","Jum","Sab"],daysMin:["Mg","Sn","Sl","Ra","Ka","Ju","Sa"],months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],monthsShort:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"],today:"Hari Ini",clear:"Kosongkan"}}(jQuery);

        $('.input-datepicker').datepicker({
            format: 'dd MM yyyy',
            language: "id",
            locale: "id",
        });

        $('.input-datepicker-year').datepicker({
            format: 'yyyy',
            viewMode: 'years',
            minViewMode: 'years',
            autoclose: true,
            language: "id",
            locale: "id",
        });

        $('.input-datepicker-month').datepicker({
            format: 'mm',
            viewMode: 'months',
            minViewMode: 'months',
            autoclose: true,
            language: "id",
            locale: "id",
        });

        $('.input-datetimepicker').datetimepicker({
            format: 'dd MM yyyy hh:ii',
            autoclose: true,
            language: "id",
            locale: "id",
        });

        $('.input-daterangepicker').daterangepicker({
            buttonClasses: 'btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            language: "id",
            locale: {
                format: 'DD MMMM YYYY',
            }
        }, function(start, end) {
            $(this.element).find('input').val( start.format('DD MMMM YYYY') + ' s/d ' + end.format('DD MMMM YYYY'));
        });

    };

    const initSelect2 = function () {
        $('.modal select').css('width', '100%');
        $('.select-select2').select2({
            placeholder: 'Pilih',
            minimumResultsForSearch: -1,
            width: '100%',
            allowClear: true
        });
    };

    const select2Ajax = function (target, options) {
        const templateResult = function (result) {
            return result.text;
        };

        const $el = $(target);
        const $p = $(target).parent();

        options = $.extend(true, {
            placeholder: 'Search',
            url: '',
            minimumInputLength: 1,
            templateResult: templateResult,
            templateSelection: templateResult,
            allowClear: true,
            dropdownParent: $p
        }, options);

        $el.select2({
            placeholder: options.placeholder,
            allowClear: options.allowClear,
            dropdownParent: options.dropdownParent,
            ajax: {
                url: options.url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: options.minimumInputLength,
            templateResult: options.templateResult, // omitted for brevity, see the source of this page
            templateSelection: options.templateSelection // omitted for brevity, see the source of this page
        });
    };

    const validation = function(target, options, btnSubmit) {
        const $el = $(target);
        const $buttonSubmit = $(btnSubmit && "#form-submit");

        options = $.extend(true, {
            rules: {},
            invalidHandler: function () {
                KTUtil.scrollTop();
            },
            submitHandler: function (form) {
                const optionsAjax = {
                    dataType: 'json',
                    success: function (result) {

                        if (!result.success) {
                            toastr.error(result.message);
                        } else {
                            toastr.success(result.message);
                        }

                        if (options.datatable){
                            reloadDatatable(options.datatable);
                        }

                        if (options.modal){
                            $(options.modal).modal('hide');
                        }

                        if (options.contentReload) {
                            loadAjax(options.contentReload);
                        }

                    },
                    error: function (error) {

                        toastr.error(error.responseJSON.message || 'Something Error!');

                        $buttonSubmit.attr('disabled', false);
                        $buttonSubmit.removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                        $buttonSubmit.text('Simpan');

                        if (error.status === 422) {

                            let errors = error.responseJSON.errors;
                            let errorList = Object.keys(errors).map(function(value){
                                $("name["+value+"]").addClass('is-invalid');
                                return `<li>${errors[value]}</li>`;
                            });

                            $el.find('.alert').remove();

                            $el.prepend(`<div class="alert alert-solid-danger alert-bold fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">
                                      ${error.responseJSON.message}!<br/>
                                      <ul>${errorList.join('')}</ul>
                                    </div>
                                    <div class="alert-close">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                      </button>
                                    </div>
                                  </div>`);

                        }
                    }
                };

                $buttonSubmit.attr('disabled', true);
                $buttonSubmit.addClass('kt-spinner kt-spinner--md kt-spinner--light');
                $buttonSubmit.text('Menyimpan');

                $el.ajaxSubmit(optionsAjax);

            },
            modal: false
        }, options);

        $el.validate({
            rules: options.rules,
            invalidHandler: options.invalidHandler,
            submitHandler: options.submitHandler
        });
    };

    return {
        init: function() {
            Daster.initComponent();
            handleBack();
        },

        initAjax: function () {
            Daster.initComponent();
        },

        initComponent: function () {
            ajaxSetup();
            handleAjaxLink();
            handleAjaxModal();
            initDefaultDatepicker();
            initSelect2();
        },

        initTable: function (target, options) {
            dataTable(target, options);
        },

        toggleFilter: function () {

            $('#l-toggle-filter').click(function () {

                const $filter = $('#kt_filter');

                if ($filter.hasClass('hidden')) {

                    $filter.removeClass('hidden');
                    $filter.slideDown();

                } else {

                    $filter.addClass('hidden');
                    $filter.slideUp();

                }

            });

        },

        reloadDatatable: function(element) {
            reloadDatatable(element);
        },

        initValidate: function (target, options, btnSubmit) {
            validation(target, options, btnSubmit);
        },

        loadAjax: function(url) {
            loadAjax(url);
        },

        select2: function (target, options) {
            select2Ajax(target, options);
        },
    };
}());

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = Daster;
}

KTUtil.ready(function () {
    Daster.init();
});
