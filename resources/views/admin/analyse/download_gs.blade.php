@extends ('admin.layouts.dashboard')

@section('page_heading','Загрузка анализов из Google Sheets')
<div id="form_start" hidden></div>
@section('section')

    <div class="col-md-12 download-form">
        <div class="col-md-12 txt">
            <p>После загрузки анализов из GS, будут добавлены на сайт новые анализы и существующие анализы будут обновлены.</p>
            <h3 class="h3-instr">Инструкция</h3>
            <div class="instruction">
                <ol>
                    <li>Новый анализ не будет иметь категорию и добавится со статусом "Неактивный";</li>
                    <li>Гугл-таблица со списком анализов доступна по <a target="_blank" href="{{ config('app.google_sheets_table_url') }}">ссылке</a>;</li>
                    <li>Все поля обязательны для заполнения:</li>
                    <ol class="sub">
                        <li>Код - Уникальный код анализа. Должен иметь маску хх.хх.ххх (где х - кириллица/латиница/цифры любого регистра);</li>
                        <li>Статус - Отображение анализа на сайте (Активный/Неактивный);</li>
                        <li>Condition - Статус анализа в Базе Данных (Существующий/Удаленный);</li>
                        <li>Название анализа - Название самого анализа (Кириллица/латиница/цифры любого регистра. Максимальное количество символов - 1000);</li>
                        <li>Цена - Цена анализа (Числовое поле, диапазон значений от 00000.01 до 99999.99; Поле цена не может быть меньше поля цена со скидкой или равна 0);</li>
                        <li>Цена со скидкой - Цена анализа со скидкой (Числовое поле, диапазон значений от 0 до 99999.99; Поле цена со скидкой не может быть больше поля цена);</li>
                        <li>Срок выполнения анализа - Количество дней проведения анализа (Числовое поле, диапозон допустимых значений от 1 до 365).</li>
                    </ol>
                </ol>
            </div>
            <div class="col-md-4 dwn">
                <button id="upload" type="button" class="btn btn-primary">Загрузить</button>
            </div>
            <br><br>

        </div>
        <div class="col-md-12 hr-div">
            <hr class="my-hr">
        </div>

        @if($dataForView != null)
            <div>
                Последняя успешная загрузка была произведена <span class="response-date">
                    {{ $dataForView }}
                </span>
                <ul class="data-list">
                    <li id="updated" class="data-item">
                        <span class="updated-amount">
                            @if($updated != 0)
                                {{ $updated }}
                            @else
                                0
                            @endif

                        </span>
                        записей обновлено
                        @if($countDeletedInUpdated > 0)
                            (из них {{ $countDeletedInUpdated }} со статусом "Удаленный")
                        @endif
                    </li>
                    <li id="added" class="data-item">
                        <span class="added-amount">
                            @if($added != 0)
                                {{ $added }}
                            @else
                                0
                            @endif
                        </span>
                        записей добавлено
                        @if($countDeletedInAdded > 0)
                            (из них {{ $countDeletedInAdded }} со статусом "Удаленный")
                        @endif
                    </li>
                </ul>
                <p>Примечание. Для новых записей Вам необходимо заполнить обязательные поля для их отображения на сайте. Для этого
                    вы можете отсортировать анализы по полю статус и отредактировать все "неактивные" в списке ниже.</p>
            </div>
        @endif
        <a class="btn btn-default btn-outline go-list" href="{{ route('downloaded_analyses') }}">Перейти к списку</a>
    </div>


@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            var ajaxUrl = "{{route('download_gs')}}";

            $('#upload').on('click', function (e) {
                e.preventDefault();
                showPreloader();

                $.ajax({
                    type: 'POST',
                    datatype: 'json',
                    data: {myData: 'check_gs_table'},
                    url: ajaxUrl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        console.log(result);

                        if (result.success == false) {

                            if (result.data == 'empty_gs_table') {
                                alert("В таблице Google Sheets на данные момент нет анализов. Проверьте, пожалуйста, строку № 4");
                                location.reload();
                            }

                            if (result.data == 'number_cells') {
                                alert("Таблица в Google Sheets некорректно заполнена. Проверьте, пожалуйста, строку №" + result.row + " на наличие пустых ячеек");
                                location.reload();
                            }

                            if (result.data == 'error_spaces') {
                                var rows = result.row.join(", ");

                                alert("Таблица в Google Sheets некорректно заполнена. Перечень строк, которые должны быть заполнены - " + rows);
                                location.reload();
                            }

                            if (result.data == 'duplicate_keys') {
                                var arr = result.duplicates;
                                msg = "Таблица в Google Sheets некорректно заполнена. Проверьте, пожалуйста, колонку 'Код'. Значение в данной колонке не должно повторяться.";
                                $.each(arr, function(index, value){
                                    var msg_sub = ' Код ' + index + ' повторяется несколько раз (' + value + '). ';
                                    msg = msg + msg_sub;
                                });

                                alert(msg);
                                location.reload();
                            }

                            if (result.data == 'error_field') {
                                alert("Таблица в Google Sheets некорректно заполнена. Проверьте, пожалуйста, строку №" + result.row + ". " + result.msg);
                                location.reload();
                            }

                            hidePreloader();
                        } else if (result.success == true) {
                            // console.log("Включаем прелоадер, загружаем данные");
                            // Включаем прелоадер
                            // + отправляем новый ajax запрос с пометкой, чтобы данные начали загружаться в бд

                            $.ajax({
                                type: 'POST',
                                datatype: 'json',
                                data: {myData: 'upload_db'},
                                url: ajaxUrl,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (result) {

                                    if (result.success == true) {
                                        // 1) убираем прелоадер
                                        hidePreloader();
                                        alert('Анализы успешно загружены');
                                        location.reload();
                                    } else if (result.success == false) {
                                        hidePreloader();
                                        alert('Во время загрузки произошла ошибка');
                                        location.reload();
                                        // Добавить сообщение, что во время загрузки произошла ошибка
                                    }
                                }
                            });
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                        location.reload();
                    }
                });
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

    </script>
@endpush

<style>
    .download-form {
        padding-left: 15px;
        padding-right: 15px;
        /*border: 1px solid red;*/
    }
    .dwn, .txt, .hr-div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .download-form .btn-primary {
        margin-bottom: 8px;
    }
    .alert-info {
        margin-top: 15px;
    }
    .alert-info ul {
        color: #000;
        margin-top: 15px;
        margin-bottom: 15px !important;
    }

    .alert-info p {
        color: #000;
    }

    .my-hr {
        margin-top: 15px;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
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

    .instruction ol {
        margin: 0;
        padding: 0;
        counter-reset: item;
    }

    .instruction li {
        display: block; margin: 5px 7px;
    }

    .instruction li:before {
        content: counters(item, ".") " "; counter-increment: item
    }

    .sub {
        padding-left: 15px !important;
    }


</style>