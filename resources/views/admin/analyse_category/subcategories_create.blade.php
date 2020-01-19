@extends ('admin.layouts.dashboard')

@section('page_heading','Добавление подкатегории')
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
                    {{ Form::label('title', 'Название подкатегории*', ['class' => 'control-label']) }}
                    {{ Form::text('title','', ['class' => 'form-control', 'placeholder' => 'Введите название категории', 'maxlength' => '255' ]) }}
                    <div style="color: #a4aaae; margin-top: 5px;">Максимальное количество символов 255</div>
                </div>

                <div class="form-group">
                    {{ Form::label('parent_id', 'Родительская категория*', ['class' => 'control-label']) }}
                    {{ Form::select('parent_id', $categoryToView, '0', ['class' => 'form-control' ]) }}
                </div>

                {{ Form::submit('Сохранить', ['class' => 'btn btn-primary' ]) }}
                @csrf
                {{ Form::close() }}

            </div>
        </div>
@endsection