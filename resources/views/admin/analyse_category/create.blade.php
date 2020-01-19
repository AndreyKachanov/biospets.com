@extends ('admin.layouts.dashboard')

@section('page_heading','Добавление категории')
<div id="form_start" hidden></div>
@section('section')

    <div class="col-md-12">
        <div class="row" >
            <div class="col-sm-6" id="form-formatting">
                @if($errors->count() > 0)
                    <div class="alert alert-danger print-error-msg" id="alert">
                        <ul>
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ Form::open(['route' => 'admin.analysescategory.store', 'method' => 'post']) }}

                <div class="form-group">
                    {{ Form::label('is_active', 'Статус*', ['class' => 'control-label']) }}
                    {{ Form::select('is_active', ['Неактивный', 'Активный'], '0', ['class' => 'form-control' ]) }}
                </div>

                <div class="form-group @if($errors->has('title')) has-error @endif">
                    {{ Form::label('title', 'Название категории*', ['class' => 'control-label']) }}
                    {{ Form::text('title','', ['class' => 'form-control', 'placeholder' => 'Введите название категории', 'maxlength' => '255' ]) }}
                    <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
                </div>

                <div class="form-group @if($errors->has('description')) has-error has-error-editor @endif">
                    {{ Form::label('description', 'Описание', ['class' => 'control-label']) }}
                    {{ Form::textarea('description','', ['id' => 'category_description', 'class' => 'form-control', 'placeholder' => 'Введите описание категории' ]) }}
                </div>

                {{ Form::submit('Сохранить', ['class' => 'btn btn-primary' ]) }}
                    @csrf
                {{ Form::close() }}

        </div>
    </div>
@endsection

@push('scripts')
    <script>

        $(document).ready(function() {
            tinyMCE.PluginManager.add('stylebuttons', function(editor, url) {
                ['pre', 'p', 'code', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'].forEach(function(name){
                    editor.addButton("style-" + name, {
                        tooltip: "Заголовок " + name,
                        text: name.toUpperCase(),
                        onClick: function() { editor.execCommand('mceToggleFormat', false, name); },
                        onPostRender: function() {
                            var self = this, setup = function() {
                                editor.formatter.formatChanged(name, function(state) {
                                    self.active(state);
                                });
                            };
                            editor.formatter ? setup() : editor.on('init', setup);
                        }
                    })
                });
            });

            tinymce.init({
                valid_elements: 'a[*],p,h1,h2,strong,b,ul,li,em',
                selector: '#category_description',
                menubar: false,
                toolbar: "bold italic | style-h1 style-h2 style-h3 | bullist link",
                language: 'ru',
                plugins: ['lists', 'link', 'placeholder', 'paste', 'stylebuttons'],
                target_list: false,
                max_chars: 10000, // max. allowed chars
                paste_remove_styles_if_webkit: false,
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


    </script>
@endpush