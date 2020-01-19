@extends ('admin.layouts.dashboard')

@section('page_heading','Изменение анализа')
<div id="form_start" hidden></div>
@section('section')

    <div class="col-md-12">
        <div class="row" >
            <div class="col-sm-6" id="form-formatting">

                <div class="alert alert-danger print-error-msg" id="alert" hidden>
                    <ul></ul>
                </div>

                {{ Form::open(['method' => 'post', 'id' => 'formCreatePromotion']) }}

                <div class="form-group">
                    <label class="control-label" for="status">Статус*</label>
                    <select class="form-control" id="status" name="is_active">
                        <option value="{{ \App\Models\Analyse::STATUS_NOT_ACTIVE }}" @if ($analyse->is_active == \App\Models\Analyse::STATUS_NOT_ACTIVE) selected="selected" @endif>@lang('statuses.analise.not_active')</option>
                        <option value="{{ \App\Models\Analyse::STATUS_ACTIVE }}" @if ($analyse->is_active == \App\Models\Analyse::STATUS_ACTIVE) selected="selected" @endif>@lang('statuses.analise.active')</option>
                    </select>
                </div>

                @if(isset($analyse->rAnalysesCategories))
                    <div class="form-group" @if($errors->has('category_id')) has-error @endif">
                        {{ Form::label('category_id', 'Категория*', ['class' => 'control-label']) }}
                        {{ Form::select(
                            'category_id',
                            $mainCategoriesArray,
                            ( $analyse->rAnalysesCategories->parent_id == null) ? $analyse->rAnalysesCategories->id : $analyse->rAnalysesCategories->parent_id,

                                [
                                    'class' => 'form-control',
                                    'id' => 'selectCategory',
                                ]
                            ) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('sub_category_id', 'Подкатегория', ['class' => 'control-label']) }}
                        {{ Form::select(
                            'sub_category_id',
                            Session::get('edit_sub_categories')['arr'] ?? $subCategoriesArray,
                            $analyse->rAnalysesCategories->id ?? '',
                                [
                                    'class' => 'form-control',
                                    'id' => 'subCategory',

                                    (isset(Session::get('edit_sub_categories')['arr']))
                                    ? (count(Session::get('edit_sub_categories')['arr']) == 0)
                                        ? 'disabled'
                                        : ''
                                    : ( count($subCategoriesArray) == 0 ) ? 'disabled' : ''
                                ]
                        ) }}
                    </div>
                @else

                    <div class="form-group" @if($errors->has('category_id')) has-error @endif">
                    {{ Form::label('category_id', 'Категория*', ['class' => 'control-label']) }}
                    {{ Form::select(
                        'category_id',
                        $mainCategoriesArray, 0,
                            [
                                'class' => 'form-control',
                                'id' => 'selectCategory',
                            ]
                        ) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('sub_category_id', 'Подкатегория', ['class' => 'control-label']) }}
                        {{ Form::select(
                            'sub_category_id', $subCategoriesArray, 0,
                                [
                                    'class' => 'form-control',
                                    'id' => 'subCategory',
                                    (count($subCategoriesArray) == 0) ? 'disabled' : ''
                                ]
                        ) }}
                    </div>

                @endif
            <div class="form-group @if($errors->has('title')) has-error @endif" id="title_name_error">
                {{ Form::label('title', 'Название анализа*', ['class' => 'control-label']) }}
                {{ Form::text('title', old('title', $analyse->title), ['class' => 'form-control', 'placeholder' => 'Введите название анализа', 'maxlength' => '1000' ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 1000</div>
            </div>

            <div class="form-group @if($errors->has('code')) has-error @endif" id="code_name_error">
                {{ Form::label('code', 'Код*', ['class' => 'control-label']) }}
                {{ Form::text('code', old('code', $analyse->code), ['class' => 'form-control', 'placeholder' => 'Введите код', 'id' => 'code']) }}
                <div style="color: #a4aaae; margin-top: 5px;">Формат записи - xx.xxx.xxx</div>
            </div>

            <div class="form-group @if($errors->has('discount')) has-error @endif" id="discount_name_error">
                {{ Form::label('discount', 'Цена со скидкой, руб.', ['class' => 'control-label', 'id' => 'label_discount']) }}
                {{ Form::text('discount', (is_null($analyse->discount)) ? '' : old('discount', number_format($analyse->discount, 2, ',', '')), ['class' => 'form-control', 'placeholder' => 'Введите цену со скидкой' ]) }}
            </div>

            <div class="form-group @if($errors->has('price')) has-error @endif" id="price_name_error">
                {{ Form::label('price', 'Цена, руб.*', ['class' => 'control-label']) }}
                {{ Form::text('price', old('price', number_format($analyse->price, 2, ',', '')), ['class' => 'form-control', 'placeholder' => 'Введите цену' ]) }}
            </div>

            <div class="form-group @if($errors->has('title_lat')) has-error @endif" id="title_lat_name_error">
                {{ Form::label('title_lat', 'Второе название', ['class' => 'control-label', 'id' => 'label_title_lat']) }}
                {{ Form::text('title_lat', old('title_lat', $analyse->title_lat), ['class' => 'form-control', 'id' => 'title_lat', 'placeholder' => 'Второе название (на латинском)', 'maxlength' => '255' ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
            </div>

            <div class="form-group @if($errors->has('material')) has-error @endif" id="material_name_error">
                {{ Form::label('material', 'Биоматериал', ['class' => 'control-label', 'id' => 'label_material']) }}
                {{ Form::text('material', old('material', $analyse->material), ['class' => 'form-control', 'placeholder' => 'Введите название биоматериала', 'maxlength' => '255']) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
            </div>

            <div class="form-group @if($errors->has('preparation')) has-error @endif" id="preparation_name_error">
                {{ Form::label('preparation', 'Подготовка', ['class' => 'control-label', 'id' => 'label_preparation']) }}
                {{ Form::text('preparation', old('preparation', $analyse->preparation), ['class' => 'form-control', 'placeholder' => 'Введите подготовку', 'maxlength' => '255']) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
            </div>

            <div class="form-group @if($errors->has('result')) has-error @endif" id="result_name_error">
                {{ Form::label('result', 'Результат', ['class' => 'control-label', 'id' => 'label_result']) }}
                {{ Form::text('result', old('result', $analyse->result), ['class' => 'form-control', 'placeholder' => 'Введите результат', 'maxlength' => '255' ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
            </div>

            <div class="form-group @if($errors->has('term')) has-error @endif" id="term_name_error">
                {{ Form::label('term', 'Срок (дней)*', ['class' => 'control-label']) }}
                {{ Form::text('term', old('term', $analyse->term),
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

            <div class="form-group @if($errors->has('method')) has-error @endif" id="method_name_error">
                {{ Form::label('method', 'Метод', ['class' => 'control-label', 'id' => 'label_method']) }}
                {!! Form::textarea('method', old('method', $analyse->method), [
                    'class' => 'form-control analyse-textarea',
                    'placeholder' => 'Введите метод',
                    'maxlength' => '255',
                    'rows' => 5,
                    'style' => 'resize:none'
                ]) !!}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
            </div>

            <div class="form-group @if($errors->has('description')) has-error has-error-editor @endif" id="description_name_error">
                {{ Form::label('description', 'Описание', ['class' => 'control-label', 'id' => 'label_description']) }}
                {{ Form::textarea('description', old('description', $analyse->description), ['id' => 'analyse_description', 'class' => 'form-control', 'placeholder' => 'Введите описание анализа', 'maxlength' => '5000' ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 5000</div>
            </div>

            <div class="form-group @if($errors->has('meta_title')) has-error @endif">
                {{ Form::label('meta_title', 'meta-title', ['class' => 'control-label']) }}
                {{ Form::text('meta_title', old('meta_title', $analyse->meta_title), ['class' => 'form-control', 'placeholder' => 'Введите meta-title', 'maxlength' => '80' ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 80</div>
            </div>

            <div class="form-group @if($errors->has('meta_description')) has-error @endif">
                {{ Form::label('meta_description', 'meta-description', ['class' => 'control-label']) }}
                {{ Form::text('meta_description', old('meta_description', $analyse->meta_description), ['class' => 'form-control', 'placeholder' => 'Введите meta-description', 'maxlength' => '180' ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 180</div>
            </div>

            <div class="form-group @if($errors->has('meta_keywords')) has-error @endif">
                {{ Form::label('meta_keywords', 'meta-keywords', ['class' => 'control-label']) }}
                {{ Form::text('meta_keywords', old('meta_keywords', $analyse->meta_keywords), ['class' => 'form-control', 'placeholder' => 'Введите meta-keywords', 'maxlength' => '255' ]) }}
                <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
            </div>
            {{--{{ Form::submit('Сохранить', ['class' => 'btn btn-primary', 'id' => 'btn_submit' ]) }}--}}
            <button type="submit" class="btn btn-primary" id="btn_submit"><i class="fa fa-spinner fa-spin" style="display: none"></i> Сохранить</button>
            <input type="hidden" name="id" value="{{$analyse->id}}">
            @csrf
            {{ Form::close() }}
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script>


        $(document).ready(function() {
            $('#price, #discount').inputmask({"mask": "9{1,5},99"}, {jitMasking: true});
            $('#code').inputmask({regex: "^[А-Яа-яёЁA-Za-z0-9]{2}(\\.[А-Яа-яёЁA-Za-z0-9]{2})(\\.[А-Яа-яёЁA-Za-z0-9]{3})$", jitMasking: true});
            $('#term').inputmask({"mask": "9{1,3}"}, {jitMasking: true});
            // $('#title_lat').inputmask({regex: "^[A-Za-z0-9-_ ]*$", jitMasking: true});

            $("#term").on("keypress keyup",function(){
                if($(this).val() == '0'){
                    $(this).val('');
                }
            });

            $('#selectCategory').on('change', function () {
                var idCategory =  $(this).find("option:selected").attr('value');

                if (idCategory != 0) {

                    var getSubCategories = '{!!route('get_subcategories', ['id' => 'J']) !!}';
                    var url = getSubCategories.replace("J", idCategory);

                    $.ajax({
                        type: "get",
                        url: url,
                        success: function (result) {

                            var items = result.sub_categories;

                            if (items.length > 0) {

                                $('#subCategory').children('option').remove();
                                $('#subCategory').removeAttr('disabled');

                                $('#subCategory').append("<option value='0'>Выберите подкатегорию</option>");
                                $.each(items, function (i, item) {
                                    $('#subCategory').append($('<option>', {
                                        value: item.id,
                                        text : item.title
                                    }));
                                });
                            } else {
                                $('#subCategory').attr('disabled','disabled');
                                $('#subCategory').children('option').remove();
                            }
                        }
                    });
                } else {
                    $('#subCategory').attr('disabled','disabled');
                    $('#subCategory').children('option').remove();
                }
            });

            tinymce.init({
                paste_auto_cleanup_on_paste : true,
                valid_elements: 'a[*],p,strong,ul,li,em',
                selector: '#analyse_description',
                menubar: false,
                toolbar: "bold italic | bullist link",
                language: 'ru',
                plugins: ['lists', 'link', 'placeholder', 'paste'],
                target_list: false,
                max_chars: 5000, // max. allowed chars
                setup: function (ed) {
                    var allowedKeys = [8, 37, 38, 39, 40, 46, 32]; // backspace, delete and cursor keys
                    ed.on('keydown', function (e) {
                        if (allowedKeys.indexOf(e.keyCode) != -1) return true;
                        if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                        return true;
                    });
                    ed.on('keyup', function (e) {
                        tinymce_updateCharCounter(this, tinymce_getContentLength());
                    });
                },
                init_instance_callback: function () { // initialize counter div
                    // $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
                    tinymce_updateCharCounter(this, tinymce_getContentLength());
                },
                paste_preprocess: function (plugin, args) {
                    var editor = tinymce.get(tinymce.activeEditor.id);
                    var len = editor.contentDocument.body.innerText.length;
                    var text = $(args.content).text();

                    if (len + text.length > editor.settings.max_chars) {
                        alert('Запрещено вставлять больше ' + editor.settings.max_chars + ' символов.');
                        args.content = '';
                    } else {
                        tinymce_updateCharCounter(editor, len + text.length);

                    }
                },

                paste_postprocess : function(pl, o) {
                }
            });
        });

        function tinymce_updateCharCounter(el, len) {
            $('#' + el.id).prev().find('.char_count').text(len + '/' + el.settings.max_chars);
        }

        function tinymce_getContentLength() {
            var content = tinymce.trim(tinymce.activeEditor.getContent({format: 'text'}));
            return content.length;
        }

        function txtOnBlur(txt){
            if (txt.value > 365 || txt.value < 0 || txt.value.substring(0,1) == '0') {
                $('#term').val(365);
                $('#term_name_error').addClass('has-error');
            } else {
                $('#term_name_error').removeClass('has-error');
            }
        }
        // validation
        $('#status').change(function() {
            if ($(this).find(":selected").text() == 'Неактивный') {

                $('#label_discount').text('Цена со скидкой, руб.');
                $('#label_description').text('Описание');
                $('#label_result').text('Результат');
                $('#label_material').text('Биоматериал');
                $('#label_method').text('Метод');
                $('#label_preparation').text('Подготовка');
                $('#label_title_lat').text('Второе название');
            } else {

                $('#label_discount').text('Цена со скидкой, руб.*');
                $('#label_result').text('Результат*');
                $('#label_material').text('Биоматериал*');
                // $('#label_method').text('Метод*');
                // $('#label_preparation').text('Подготовка*');
                // $('#label_title_lat').text('Второе название*');
            }
        });
        $('#formCreatePromotion').on('submit', function (e) {
            e.preventDefault();

            $('.fa-spin').show();
            $('#btn_submit').prop('disabled', true);
            $('#title_name_error').removeClass('has-error');
            $('#code_name_error').removeClass('has-error');
            $('#discount_name_error').removeClass('has-error');
            $('#price_name_error').removeClass('has-error');
            $('#title_lat_name_error').removeClass('has-error');
            $('#material_name_error').removeClass('has-error');
            $('#preparation_name_error').removeClass('has-error');
            $('#result_name_error').removeClass('has-error');
            $('#term_name_error').removeClass('has-error');
            $('#method_name_error').removeClass('has-error');

            $('.mce-tinymce.mce-container.mce-panel').css('border-color', '#ccd0d2');
            tinymce.triggerSave();

            var ajaxUrl = '{{ route('admin.analyses.update', ['analyse' => $analyse->id]) }}';
            var windowRedirect = '{{ route ('admin.analyses.index') }}';

            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                statusCode: {
                    503: function(data) {
                        var errors = $.parseJSON(data.responseText);
                        postAjax(errors);
                    }
                },
                error: function(data) {
                    var errors = $.parseJSON(data.responseText);
                    postAjax(errors['errors']);
                },
                success: function (data) {
                    localStorage.setItem('analyseId', data['analyseId']);
                    $(".print-error-msg").hide();
                    window.location = windowRedirect
                },
                complete: function() {
                    $('#btn_submit').prop('disabled', false);
                    $('.fa-spin').hide();
                }
            });
            function postAjax (msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display','block');

                $.each( msg, function( key, value ) {
                    $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

                    if (value == 'Поле Название анализа обязательно для заполнения.') {
                        $('#title_name_error').addClass('has-error');
                    }

                    if (value == 'Поле Код обязательно для заполнения.') {
                        $('#code_name_error').addClass('has-error');
                    }

                    if (value == 'Поле Цена со скидкой, руб. обязательно для заполнения.') {
                        $('#discount_name_error').addClass('has-error');
                    }

                    if (value == 'Поле Цена со скидкой, руб. не должно быть больше или равно полю Цена, руб.') {
                        $('#discount_name_error').addClass('has-error');
                    }

                    if (value == 'Поле Цена, руб. обязательно для заполнения.') {
                        $('#price_name_error').addClass('has-error');
                    }

                    if (value == 'Поле Цена, руб. не может быть равна или меньше 0.') {
                        $('#price_name_error').addClass('has-error');
                    }

                    // if (value == 'Поле Второе название обязательно для заполнения.') {
                    //     $('#title_lat_name_error').addClass('has-error');
                    // }

                    if (value == 'Поле Биоматериал обязательно для заполнения.') {
                        $('#material_name_error').addClass('has-error');
                    }

                    // if (value == 'Поле Подготовка обязательно для заполнения.') {
                    //     $('#preparation_name_error').addClass('has-error');
                    // }

                    if (value == 'Поле Результат обязательно для заполнения.') {
                        $('#result_name_error').addClass('has-error');
                    }

                    if (value == 'Поле Срок (дней) обязательно для заполнения.') {
                        $('#term_name_error').addClass('has-error');
                    }

                    if (value == 'Измените значение в поле Срок (дней), т.к. комплекс, в который входит данный анализ, в поле Срок (дней) имеет меньшее значение.') {
                        $('#term_name_error').addClass('has-error');
                    }

                    // if (value == 'Поле Метод обязательно для заполнения.') {
                    //     $('#method_name_error').addClass('has-error');
                    // }

                    if (value == 'Поле Код имеет ошибочный формат.' || value == 'Такое значение поля Код уже существует.') {
                        $('#code_name_error').addClass('has-error');
                    }


                    if (value == 'Количество символов в поле Описание не может превышать 5000.') {
                        $('#mceu_3').css('border', "1px solid #a94442");
                    } else {
                        $('#mceu_3').css('border', "none");
                    }

                });

                var targetOffset = $('#form_start').offset().top;
                $('html, body').animate({scrollTop: targetOffset}, 1000);
            }
        });

    </script>
@endpush