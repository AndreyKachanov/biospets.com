@extends ('admin.layouts.dashboard')

@section('page_heading','Редактирование подкатегории')
<div id="form_start" hidden></div>
@section('section')

    <div class="col-md-12">
        <div class="row" >
            <div class="col-sm-6" id="form-formatting">
                @if($errors->count() > 0)
                    <div class="alert alert-danger print-error-msg" id="alert">
                        <ul id="alert-ul">
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {{ Form::open(['url' => route('admin.analyse.subcategories.update', ['id' => $analyseCategory->id]),'method'=>'POST']) }}

                    <div class="form-group">
                    {{ Form::label('is_active', 'Статус*', ['class' => 'control-label']) }}
                    {{ Form::select('is_active', ['Неактивный', 'Активный'], $analyseCategory->is_active, ['class' => 'form-control', 'id' => 'status' ]) }}
                </div>

                <div class="form-group @if($errors->has('title')) has-error @endif">
                    {{ Form::label('title', 'Название подкатегории*', ['class' => 'control-label']) }}
                    {{ Form::text('title', old('title', $analyseCategory->title), ['class' => 'form-control', 'placeholder' => 'Введите название категории', 'maxlength' => '255' ]) }}
                    <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
                </div>

                <div class="form-group">
                    {{ Form::label('parent_id', 'Родительская категория*', ['class' => 'control-label']) }}
                    {{ Form::select('parent_id', $categoryToView, $analyseCategory->parent_id, ['class' => 'form-control' ]) }}
                </div>

                {{ Form::submit('Сохранить', ['class' => 'btn btn-primary', 'id' => 'save_category' ]) }}
                @csrf

                {{ Form::close() }}
            </div>
        </div>

@endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                // check active analyses
                $('#status').change(function() {
                    if ($(this).find(":selected").val() == 0) {
                        var url = '{{ route('check_active_analyses', ['id' => $analyseCategory->id]) }}';

                        $.ajax({
                            type: "get",
                            url: url,
                            success: function (result) {
                                var alert_block = $("div").is("#alert");

                                if (result.analyses > 0 ) {
                                    $(".print-error-msg").css('display','block');
                                    if (alert_block) {
                                        $('#save_category').prop('disabled', true);
                                        $("#alert-ul").prepend('<li>Невозможно изменить статус на "Неактивный". В подкатегории есть активные анализы.</li>');
                                    } else {
                                        $('#save_category').prop('disabled', true);
                                        $("#form-formatting").prepend('<div class="alert alert-danger print-error-msg" id="alert"><ul id="alert-ul"><li>Невозможно изменить статус на "Неактивный". В подкатегории есть активные анализы.</li></ul></div>');
                                    }
                                }
                            }
                        });
                    } else {
                        $('#alert').remove();
                        $('#save_category').prop('disabled', false);

                    }
                });

            });

        </script>
@endpush