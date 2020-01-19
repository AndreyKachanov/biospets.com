@php
    /** @var \App\Models\AnalyseCategory $categories */
    $collectionNotCategory = \App\Models\Analyse::getAnalysesForGoogleSheetsWithoutCategory();
@endphp

@extends ('admin.layouts.dashboard')

@section('page_heading','Выгрузка анализов в Google Sheets')
<div id="form_start" hidden></div>
@section('section')

    <div class="col-md-12" style="margin-bottom: 20px;">
     <p style="margin-bottom: 5px !important;">После нажатия "Выгрузить" произойдёт выгрузка анализов из базы данных в Google Sheets. Будут выгружены только
         выбранные категории и обязательные поля (код, название анализа, цена, цена со скидкой, срок).
         Гугл-таблица со списком анализов доступна по <a target="_blank" href="{{ config('app.google_sheets_table_url') }}">ссылке</a>.
         Выберите категорию:</p>

        <div class="check-buttons-wrap">
            <a style="margin-right: 5px;" id="check-all" class="btn btn-default btn-primary btn-outline btn-xs">Выделить всё</a>
            <a id="uncheck-all" class="btn btn-default btn-primary btn-outline btn-xs">Отменить всё</a>
        </div>

        @if($categories->count() > 0)
            <ul class="researches-list">
                <label><input type="checkbox" class="research-checkbox category-checkbox form-check-label" data-id="not_categories">
                    Анализы без категорий
                    ({{ $collectionNotCategory->where('category_id', null)->where('deleted_at', null)->count() }})
                </label>
            @foreach($categories as $category)
                @if($category->children->count() == 0)
                    <li class="researches-item">
                        <label><input type="checkbox" class="research-checkbox category-checkbox form-check-label" data-id="{{ $category->id }}">
                            {{ $category->title }}
                            @php
                                $analysesCollection = \App\Models\Analyse::getAnalysesForGoogleSheetsWithCategory(['array' => (array)$category->id]);
                            @endphp
                            ({{ $analysesCollection->where('deleted_at', null)->count() }})
                        </label>
                    </li>
                @else
                    <li class="researches-item">
                        <label><input type="checkbox" class="research-checkbox category-checkbox form-check-label" data-id="{{ $category->id }}">
                            {{ $category->title }}
                            @php
                                $analysesCollection = \App\Models\Analyse::getAnalysesForGoogleSheetsWithCategory(['array' => (array)$category->id]);
                            @endphp
                            ({{ $analysesCollection->where('deleted_at', null)->count() }})
                        </label>
                        <ul class="researches-sublist">
                            @php $sortedChildren = $category->children->sortBy('title'); @endphp
                            @foreach($sortedChildren as $child)
                                <li class="researches-subitem">
                                    <label><input type="checkbox" class="research-checkbox form-check-label" data-id="{{ $child->id }}">
                                        {{ $child->title }}
                                        @php
                                            $analysesCollection = \App\Models\Analyse::getAnalysesForGoogleSheetsWithCategory(['array' => (array)$child->id]);
                                        @endphp
                                        ({{ $analysesCollection->where('deleted_at', null)->count() }})
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
            </ul>
        @else
            <p>Категории анализов отсутствуют в базе данных.</p>
        @endif

        <button class="btn btn-primary" id="upload" class="underline" disabled>Выгрузить</button>

    <!-- HTML-код модального окна -->
        <div id="myModalBox" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Основное содержимое модального окна -->
                    <div class="modal-body">
                        На данный момент Google Sheets таблица заполнена.<br>
                        После выгрузки, все данные в таблице будут очищены.
                    </div>
                    <!-- Футер модального окна -->
                    <div class="modal-footer">
                        <button id="upload2" type="button" class="btn btn-primary">Продолжить</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#check-all').on('click', function() {
                $('.research-checkbox').prop('checked', true);
                $('#upload').removeAttr('disabled');
            });

            $('#uncheck-all').on('click', function() {
                $('.research-checkbox').prop('checked', false);
                $('#upload').attr('disabled', true);
            });

            $('.category-checkbox').on('change', function() {
                var $this = $(this);
                var $categoryItem = $this.closest('.researches-item');

                if (this.checked) {
                    $categoryItem.find('.research-checkbox').prop('checked', true);
                } else {
                    $categoryItem.find('.research-checkbox').prop('checked', false);
                }
            });

            $('.research-checkbox').on('change', function() {
                var $uploadBtn = $('#upload');

                if($('.research-checkbox:checked').length === 0) {
                    $uploadBtn.attr('disabled', true);
                } else {
                    $uploadBtn.removeAttr('disabled');
                }
            });

            var ajaxUrl = "{{route('upload_gs')}}";

            $('#upload').on('click', function (e) {
                e.preventDefault();
                showPreloader();
                // $("#myModalBox").modal('show');

                var postData = {
                    "array": [],
                };

                $('.research-checkbox:checked').each(function(index){
                    postData.array.push($(this).data('id'));
                });

                postData.check = true;


                $.ajax({
                    type: 'POST',
                    datatype: 'json',
                    data: {myData: postData},
                    url: ajaxUrl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        // console.log(result);
                        hidePreloader();

                        if (result.success == false) {

                            $("#myModalBox").modal('show');

                        } else if (result.success == true) {
                            // console.log("Добавляем прелоадер и выгружаем анализы. См. комменты");
                            //
                            // // Иначе GS пустая и мы заливаем данные.
                            // // 1) добавить прелоадер
                            showPreloader();
                            //
                            // // 2) подставить отмеченные галочками чекбоксы в массив postData
                            // // массив выбранных значений чекбоксов
                            var postData = {
                                "array": []
                            };
                            //
                            $('.research-checkbox:checked').each(function(index){
                                postData.array.push($(this).data('id'));
                            });

                            delete postData.check;
                            postData.send = true;
                            //
                            // // вызываем функцию для отправки ajax запроса,
                            // // которая убирает прелоадер и выводит ссылку на GS, см. ниже
                            //
                            sendAjaxToUploadAnalyses(postData, ajaxUrl);
                        } else if (result.success == 'error') {
                            alert('Ошибка подключения к GS');
                            location.reload();
                            $('#uncheck-all').trigger('click');
                        } else if (result.success == 'empty_db') {
                            alert("Анализы отсутствуют в базе данных");
                            location.reload();
                            $('#uncheck-all').trigger('click');
                        } else if (result.success == 'empty_category') {
                            alert("Анализов, входящих в данные категории нет. Выберите, пожалуйста, другие категории.");
                            location.reload();
                            $('#uncheck-all').trigger('click');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        hidePreloader();
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });

            });

            // обработчик после нажатия на кнопку Продолжить
            // #upload2 заменить на айдишник кнопки Продолжить

            $('#upload2').on('click', function (e) {
                // после нажатия кнопки добавить прелоадер
                // и отправить с помощью ajax отмеченные значения чекбоксов

                e.preventDefault();
                $("#myModalBox").modal('hide');

                showPreloader();
                // подставить отмеченные галочками чекбоксы
                // массив выбранных значений чекбоксов
                var postData = {
                    "array": []
                };


                $('.research-checkbox:checked').each(function(index){
                    postData.array.push($(this).data('id'));
                });

                delete postData.check;
                postData.send = true;

                // вызываем функцию для отправки ajax запроса,
                // которая убирает прелоадер и выводит ссылку на GS, см. ниже
                sendAjaxToUploadAnalyses(postData, ajaxUrl);
            });


        });

        function showPreloader() {
            $('body').addClass('overflow-hidden');
            $('.preloader').removeClass('hidden');
        }

        function hidePreloader() {
            $('body').removeClass('overflow-hidden');
            $('.preloader').addClass('hidden');
        }

        function sendAjaxToUploadAnalyses(postData, ajaxUrl) {
            // Чтобы протестить прелоадер, сделал так, что
            // Ajax ответ приходит через 5 сек.

            $.ajax({
                type: 'POST',
                datatype: 'json',
                data: {myData: postData},
                url: ajaxUrl,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (result) {
                    // console.log(result);

                    if (result.success == true) {
                        // console.log("Убрать прелоадер, добавить ссылку на GS.");
                        // 1) убрать прелоадер
                        hidePreloader();
                        alert("Анализы будут выгружены в Google Sheets таблицу в течении 1 минуты, статус загрузки отображается в ячейке A1. Посмотреть список выгруженных анализов можно здесь - {{ config('app.google_sheets_table_url') }}");
                        location.reload();
                        $('#uncheck-all').trigger('click');
                    } else if (result.success == false) {
                        hidePreloader();
                        alert('Во время загрузки произошла ошибка');
                        location.reload();
                        $('#uncheck-all').trigger('click');
                        // оповестить пользователя, что произошла ошибка во время выгрузки в GS
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    hidePreloader();
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }

    </script>
@endpush


<style>

    .check-buttons-wrap {
        margin-bottom: 15px;
    }

    .check-buttons-wrap a {
        padding: 4px 7px;
    }

    .btn-block {
        margin-top: 10px;
    }

    .upload-row {
        margin-top: 15px;
    }

    .upload-row .btn-primary {
        margin-left: 15px;
    }

    /*new style*/

    .researches-list {
        padding-left: 0;
        text-align: left !important;
    }

    .researches-item {
        text-align: left !important;
        list-style-type: none;
        padding: 5px;
        /*border: solid #DEDEDE 1px;*/
        margin-bottom: 4px;

    }

    .researches-sublist {
        padding-left: 17px !important;
        list-style-type: none;
    }

    .researches-list label {
        color: #000;
        font-weight: normal;
    }

    .researches-subitem {
        text-align: left !important;
        border: none !important;
    }

    .btn-check {
        width: auto;
    }

    .check-buttons-wrap {
        margin-bottom: 20px !important;
    }

    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10000000;
        width: 100%;
        height: 100vh;
        background: url(/backalert.png) repeat;
    }

    .overflow-hidden {
        overflow: hidden;
    }

    .preloader__img {
        position: absolute;
        left: 50%;
        top: 40%;
        width: 80px;
        height: 80px;
        /*transform: translate(-50%,-50%);*/
    }

    .research-checkbox {
        margin: 5px !important;
    }

    #myModalBox .modal-body {
        font-family: Verdana,Arial,sans-serif;
        font-size: 16px;
        text-align: center;
    }

    #myModalBox .modal-footer {
        text-align: center;
        border-top: none !important;
    }

    #myModalBox .modal-footer button {
        width: auto;
    }


    #myModalBox {
        text-align: center;
        padding: 0!important;
    }

    #myModalBox:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }

    #myModalBox .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }

</style>