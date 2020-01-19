@extends ('admin.layouts.dashboard')

@section('page_heading','Изменение комплекса')
<div id="form_start" hidden></div>
@section('section')

    <div class="col-md-12">
        <div class="row" >
            <div class="col-sm-6" id="form-formatting">
                <div class="alert alert-danger print-error-msg" id="alert" hidden>
                    <ul>
                    </ul>
                </div>
            </div>
        </div>
        {{ Form::open(['method' => 'post', 'id' => 'formCreateComplex']) }}

        <div class="form-group row">
            <div class="form-group col-sm-6 custom-mb" id="status">
                {{ Form::label('is_active', 'Статус*', ['class' => 'control-label']) }}

                <select class="form-control" id="status" name="is_active">
                    <option value="{{ \App\Models\Complex::STATUS_NOT_ACTIVE }}" @if ($complex->is_active == \App\Models\Complex::STATUS_NOT_ACTIVE) selected="selected" @endif>@lang('statuses.analise.not_active')</option>
                    <option value="{{ \App\Models\Complex::STATUS_ACTIVE }}" @if ($complex->is_active == \App\Models\Complex::STATUS_ACTIVE) selected="selected" @endif>@lang('statuses.analise.active')</option>
                </select>

            </div>
        </div>

        <div class="form-group row">
            <div class="form-group col-sm-6 custom-mb" id="title_name_error">
                {{ Form::label('title', 'Название комплекса*', ['class' => 'control-label']) }}
                {{ Form::text('title', old('title', $complex->title), ['class' => 'form-control', 'placeholder' => 'Введите название комплекса', 'maxlength' => '255']) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group col-sm-6 custom-mb" id="code_name_error">
                {{ Form::label('code', 'Код*', ['class' => 'control-label']) }}
                {{ Form::text('code', old('code', $complex->code), ['class' => 'form-control', 'placeholder' => 'Введите код', 'id' => 'code']) }}
                <div style="color: #a4aaae; margin-top: 5px;">Формат записи - xx.xxx.xxx</div>
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group col-sm-6 custom-mb" id="term_name_error">
                {{ Form::label('term', 'Срок (дней)*', ['class' => 'control-label']) }}
                {{ Form::text('term', old('term', $complex->term),
                    [
                        'class' => 'form-control',
                        'id' => 'term',
                        'placeholder' => 'Введите количество (дней)',
                        'maxlength' => '255',
                        'min' => 1, 'max' => '365',
                        'onBlur' => 'javascript:txtOnBlur(this);'
                    ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимально 365 дней</div>
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group col-sm-6 custom-mb" id="discount_name_error">
                {{ Form::label('discount', 'Цена со скидкой, руб.', ['class' => 'control-label', 'id' => 'label_discount']) }}
                {{ Form::text('discount', (is_null($complex->discount)) ? '' : old('discount', number_format($complex->discount, 2, ',', '')), ['class' => 'form-control', 'placeholder' => 'Введите цену со скидкой', 'id' => 'discount']) }}
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group col-sm-6 custom-mb" id="price_name_error">
                {{ Form::label('price', 'Цена, руб.*', ['class' => 'control-label']) }}
                {{ Form::text('price', old('term', number_format($complex->price, 2, ',', '')), ['class' => 'form-control', 'placeholder' => 'Введите цену', 'id' => 'price']) }}
            </div>
        </div>

        <div style="margin-bottom: 40px; margin-top: 40px;"><h2>Анализы</h2></div>
        <div class="form-group row custom-mb" id="doctor_select_block" @if(empty($selectAnalyse)) hidden @endif>
            <div class="form-group col-sm-6" id="doctor_profession">
                {{ Form::label('doctor', 'Выберите анализ*', ['class' => 'control-label']) }}
                {{ Form::select('analyse[]', $selectAnalyse, 0, ['class' => 'form-control', 'id' => 'doctor_select']) }}
            </div>
            <div class="form-group col-sm-6">
                {{ Form::button('Добавить анализ', ['class' => 'btn btn-primary add_doctor_button', 'style' => 'margin-top: 26px' ]) }}
            </div>
        </div>
        <div class="doctors_wrapper">
            <div id="doctors_list">
                <b>Список выбранных анализов:</b>
            </div>
            @foreach($complex->rAnalyses()->get() as $analyse)
                <div class="form-group row custom-mb">
                    <div class="form-group col-sm-6">
                        <input name="analyse_id[]" type="hidden" value="{{ $analyse->id }}">
                        <input type="text" value="{{ $analyse->title }}" readonly class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                        {{ Form::button('Удалить анализ', ['class' => 'btn btn-primary remove_doctor_button']) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-inline">
            <button type="submit" class="btn btn-primary" id="btn_submit"></i> Сохранить</button>
        </div>
        <input type="hidden" name="id" value="{{$complex->id}}">
        @csrf
        {{ Form::close() }}
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $('#price, #discount').inputmask({"mask": "9{1,5},99"}, {jitMasking: true});
            $('#code').inputmask({regex: "^[А-Яа-яёЁA-Za-z0-9]{2}(\\.[А-Яа-яёЁA-Za-z0-9]{2})(\\.[А-Яа-яёЁA-Za-z0-9]{3})$", jitMasking: true});
            $('#term').inputmask({"mask": "9{1,3}"}, {jitMasking: true});

            var addDoctorButton = $('.add_doctor_button');
            var doctorsWrapper = $('.doctors_wrapper');
            var doctorFieldsCounter = {{ $complex->rAnalyses()->count() }};

            ///////////////////////////////////Adding doctor/////////////////////////
            $(addDoctorButton).click(function () {
                $('#doctor_profession').removeClass('has-error');
                if ($('#doctor_select option').length === 1) {
                    $('#doctor_select_block').hide();
                }
                $('#doctors_list').show();

                var doctor_id = $('#doctor_select').val();
                //var is_unique_doctor;
                // $('input[name^="doctor_id"]').each(function() {
                //     if($(this).val() == doctor_id ) {
                //         alert('Такой врач уже есть в списке');
                //         is_unique_doctor = false;
                //     }
                // });
                // if(is_unique_doctor == false)
                // {
                //     return;
                var doctor_profession = $('#doctor_select option:selected').text();
                var doctorHTML = '<div class="form-group row custom-mb">\n' +
                    '                    <div class="form-group col-sm-6">\n' +
                    '                        <input name="analyse_id[]" type="hidden" value="' + doctor_id + '">\n' +
                    '                        <input type="text" value="' + doctor_profession + '" readonly class="form-control">\n' +
                    '                    </div>\n' +
                    '                    <div class="form-group col-sm-6">\n' +
                    '                        {{ Form::button('Удалить анализ', ['class' => 'btn btn-primary remove_doctor_button']) }}\n' +
                    '                    </div>\n' +
                    '                </div>';
                $(doctorsWrapper).append(doctorHTML);
                doctorFieldsCounter++;
                $('#doctor_select option:selected').remove();
            });

            $(doctorsWrapper).on('click', '.remove_doctor_button', function (e) {
                e.preventDefault();
                var doctor_id_for_select = $(this).parent().prev().find('input').val();
                var doctor_profession_for_select = $(this).parent().prev().find('input.form-control').val();
                $('#doctor_select_block').show();
                $('#doctor_select')
                    .append($("<option></option>")
                        .attr("value", doctor_id_for_select)
                        .text(doctor_profession_for_select));
                $(this).parent().parent().remove();
                var selectOptions = $("#doctor_select option");
                selectOptions.sort(function(a, b) {
                    if (a.text > b.text) {
                        return 1;
                    }
                    else if (a.text < b.text) {
                        return -1;
                    }
                    else {
                        return 0
                    }
                });

                $("#doctor_select").empty().append(selectOptions);

                doctorFieldsCounter--;
                if (doctorFieldsCounter == 0) {
                    $('#doctors_list').hide();
                }
            });

            // validation
            $('#status').change(function() {
                if ($(this).find(":selected").text() == 'Неактивный') {

                    $('#label_discount').text('Цена со скидкой, руб.');
                } else {

                    $('#label_discount').text('Цена со скидкой, руб.*');
                }
            });

            $('#formCreateComplex').on('submit', function (e) {
                e.preventDefault();

                $('.fa-spin').show();

                $('#title_name_error').removeClass('has-error');
                $('#code_name_error').removeClass('has-error');
                $('#discount_name_error').removeClass('has-error');
                $('#price_name_error').removeClass('has-error');
                $('#term_name_error').removeClass('has-error');

                var ajaxUrl = '{{ route('admin.complexes.update', ['id' => $complex->id]) }}';
                var windowRedirect = '{{ route ('admin.complexes.index') }}';
                $.ajax({
                    url: ajaxUrl,
                    type:"POST",
                    data: $('#formCreateComplex').serialize(),
                    error: function(data) {
                        var errors = $.parseJSON(data.responseText);
                        postAjax(errors['errors']);
                    },
                    success: function (data) {
                        localStorage.setItem('complexId', data['complexId']);
                        $(".print-error-msg").hide();
                        window.location = windowRedirect
                    },
                });

                function postAjax (msg) {
                    $(".print-error-msg").find("ul").html('');
                    $(".print-error-msg").css('display','block');
                    $.each( msg, function( key, value ) {
                        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

                        if (value == 'Поле Название комплекса обязательно для заполнения.') {
                            $('#title_name_error').addClass('has-error');
                        }

                        if (value == 'Поле Код обязательно для заполнения.' || value == 'Поле Код имеет ошибочный формат.' || value == 'Такое значение поля Код уже существует.') {
                            $('#code_name_error').addClass('has-error');
                        }

                        if (value == 'Поле Срок (дней) обязательно для заполнения.') {
                            $('#term_name_error').addClass('has-error');
                        }

                        if (value == 'Поле Цена, руб. обязательно для заполнения.') {
                            $('#price_name_error').addClass('has-error');
                        }

                        if (value == 'Поле Цена со скидкой, руб. не должно быть больше или равно полю Цена, руб.'
                            || value == 'Поле Цена со скидкой, руб. обязательно для заполнения.') {
                            $('#discount_name_error').addClass('has-error');
                        }

                        if (value == 'Добавьте хотя бы один анализ.') {
                            $('#doctor_profession').addClass('has-error');
                        }

                    });
                    var targetOffset = $('#form_start').offset().top;
                    $('html, body').animate({scrollTop: targetOffset}, 1000);
                }
            })

        });

        function txtOnBlur(txt){
            if (txt.value > 365 || txt.value < 0 || txt.value.substring(0,1) == '0') {
                $('#term').val(365);
                $('#term_name_error').addClass('has-error');
            } else {
                $('#term_name_error').removeClass('has-error');
            }
        }

    </script>
@endpush