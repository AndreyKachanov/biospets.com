@extends('layouts.master')
@section ('head')
    <meta charset="UTF-8">
    @if($complex->title)
        <title>{{ $complex->title }}</title>
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <meta name="theme-color" content="#05a5f8">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <!--Critical-->
    <style>{!! view()->file(public_path('css/critical/analysesDetail.css'))  !!}</style>
@endsection
@section ('body')
<div class="pageWrap" id="pageWrap">
    <div class="clear-header"></div>
    <section>
        <div class="container-fluid container--with-padding">
            <div class="row">
                <div class="col-12">

                    <a href="{{ route('analyses.index') }}?page=1&data_category_id=0&letter=all" id="comebackJs" class="flatButton flatButton--reverse posRel">Вернуться назад</a>

                </div>
            </div>
            <div class="analyses-head-block">
                <div class="block-background block-background--4 block-background-img--{{$imgArray[0]}}"></div>

                <div class="analyses-head-block-content">
                    @if(isset($complex))

                        <div class="title-3 analyses-head-block-content__title">

                            @if($complex->title) {{ $complex->title }} <br>@endif

                            @if($complex->rAnalyses()->whereIsActive(true)->count() > 0)
                                <span class="analyses-head-block-content__title-blue">
                                    ({{ $complex->rAnalyses()->whereIsActive(true)->count() }} {{ plural($complex->rAnalyses()->whereIsActive(true)->count(), 'тест', 'теста', 'тестов') }})
                                </span>
                            @endif
                        </div>
                    @endif
                    @if($complex->code)
                        <div class="analyses-head-block__code d-block d-md-none">Код:&nbsp;{{ $complex->code }}</div>
                    @endif

                    @if($complex->price)
                    <div class="analyses-head-block-text">
                        <div class="analyses-head-block-text-price-code">
                            <div class="paragraph-2 analyses-head-block-text__text">Стоимость</div>
                            @if($complex->code)
                                <div class="analyses-head-block__code d-none d-md-block">Код:&nbsp;{{ $complex->code }}</div>
                            @endif
                        </div>
                            <div class="analyses-head-block-text-price">

                                @if($complex->discount == 0 || $complex->discount == null)
                                    @if(isset($complex->price))
                                        <div class="analyses-head-block-text-price__new">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</div>
                                    @endif
                                @else
                                    @if(isset($complex->discount))
                                        <div class="analyses-head-block-text-price__new">{{ number_format($complex->discount, 2, ',', '') }}&nbsp;руб.</div>
                                    @endif

                                    @if(isset($complex->price))
                                        <div class="analyses-head-block-text-price__old">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</div>
                                    @endif
                                @endif

                                {{--@if($complex->price == 0)--}}
                                    {{--<span class="analyses-head-block-text-price__new">Бесплатно</span>--}}
                                {{--@else--}}
                                    {{--@if(!is_null($complex->discount))--}}
                                        {{--@if($complex->discount == 0)--}}
                                            {{--<span class="analyses-head-block-text-price__new">Бесплатно</span>--}}
                                        {{--@else--}}
                                            {{--<span class="analyses-head-block-text-price__new">{{ number_format($complex->discount, 2, ',', '') }}&nbsp;руб.</span>--}}
                                        {{--@endif--}}

                                        {{--&nbsp;&nbsp;<span class="analyses-head-block-text-price__old">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</span>--}}
                                    {{--@else--}}
                                        {{--&nbsp;&nbsp;<span style="text-decoration: none;" class="analyses-head-block-text-price__new">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</span>--}}
                                    {{--@endif--}}

                                {{--@endif--}}

                            </div>
                            <div class="analyses-head-block-text-btn">
                                <div class="projectBtn projectBtn--1 add-to-cart"
                                data-analyse-id="{{ $complex->id }}"
                                data-is-complex="1">Добавить в заказ</div>
                            </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container-fluid container--with-padding posRel">
            <div class="analyses-content-block analyses-content-block--1">
                @if($complex->rAnalyses()->whereIsActive(true)->count() > 0)
                    <div class="title-4 analyses-content-block__title">Тесты комплекса</div>
                    <div class="project-table-block">
                        <table class="project-table" cellpadding="0" cellspacing="0">
                            <thead class="project-table-head">
                                <tr>
                                    <td class="project-table-head-td project-table-head-td--code">Код</td>
                                    <td class="project-table-head-td project-table-head-td--name">Наименование</td>
                                    <td class="project-table-head-td project-table-head-td--show-btn">Раскрыть</td>
                                    <td class="project-table-head-td project-table-head-td--term">Срок</td>
                                    <td class="project-table-head-td project-table-head-td--category">Категория</td>
                                    <td class="project-table-head-td project-table-head-td--cost">Стоимость</td>
                                    <td class="project-table-head-td project-table-head-td--to-basket">В&nbsp;корзину</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complex->rAnalyses()->whereIsActive(true)->get() as $a)
                                <tr class="project-table-body-tr">
                                    <td class="project-table-body-td project-table-body-td--code">
                                        {{ $a->code }}
                                    </td>
                                    <td class="project-table-body-td project-table-body-td--name">
                                        <a href="{{ route('analyses.show', ['slug' => $a->slug]) }}">
                                            {{ $a->title }}
                                        </a>
                                        <div class="explain-text">
                                            <div class="project-table-mobile-content-td project-table-mobile-content-td--category d-none d-md-block d-lg-none">
                                                Категория: <span>
                                                    {{ isset($a->rAnalysesCategories->parent)
                                                        ? $a->rAnalysesCategories->parent->title
                                                        : $a->rAnalysesCategories->title
                                                    }}
                                                </span>
                                            </div>
                                            @if($a->material != '')
                                                Биоматериал: {{ $a->material }} <br>
                                            @endif
                                            @if($a->preparation != '')
                                                Подготовка: {{ $a->preparation }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="project-table-body-td project-table-body-td--show-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-down" width="20" height="22" viewBox="0 0 20 22"><path id="open" fill="#3ec4f0" fill-rule="evenodd" d="M1097,1314a8,8,0,0,1-15.41,3H1078a1,1,0,0,1,0-2h3.07a8.258,8.258,0,0,1-.07-1h-3a1,1,0,0,1,0-2h3.26a8.361,8.361,0,0,1,.33-1H1078a1,1,0,0,1,0-2h4.76a8.236,8.236,0,0,1,.96-1H1078a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1c0,0.03-.01.05-0.01,0.07A7.994,7.994,0,0,1,1097,1314Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,1089,1308Zm0.75,10.67a0.89,0.89,0,0,1-.35.23,1.028,1.028,0,0,1-1.17-.2l-2.93-2.93a1.032,1.032,0,0,1,0-1.47,1.02,1.02,0,0,1,1.46,0l1.24,1.23V1310a1,1,0,0,1,2,0v5.51l1.21-1.22a1.038,1.038,0,0,1,1.46,0,1.02,1.02,0,0,1,0,1.46ZM1092,1305h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1305Zm0-3h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1302Z" transform="translate(-1077 -1300)"/></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-up" width="20" height="22" viewBox="0 0 20 22"><path id="roll-up" fill="#3ec4f0" full-rule="evenodd"  d="M575,1501a8,8,0,0,1-15.413,3H556a1,1,0,0,1,0-2h3.069a8.06,8.06,0,0,1-.069-1h-3a1,1,0,0,1,0-2h3.262a7.837,7.837,0,0,1,.325-1H556a1,1,0,0,1,0-2h4.76a8.4,8.4,0,0,1,.961-1H556a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1,0.43,0.43,0,0,1-.014.07A7.983,7.983,0,0,1,575,1501Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,567,1495Zm2.238,5.71-1.219-1.22V1505a1,1,0,0,1-2.007,0v-5.53l-1.24,1.23a1.041,1.041,0,1,1-1.475-1.47l2.951-2.93a1.03,1.03,0,0,1,1.173-.2,0.953,0.953,0,0,1,.353.23l2.93,2.92a1.029,1.029,0,0,1,0,1.46A1.045,1.045,0,0,1,569.238,1500.71ZM570,1492H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1492Zm0-3H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1489Z" transform="translate(-555 -1487)"/></svg>
                                    </td>
                                    <td class="project-table-body-td project-table-body-td--term">{{ $a->term }} дн.</td>
                                    <td class="project-table-body-td project-table-body-td--category">
                                        {{ isset($a->rAnalysesCategories->parent)
                                            ? $a->rAnalysesCategories->parent->title
                                            : $a->rAnalysesCategories->title
                                        }}
                                    </td>
                                    <td class="project-table-body-td project-table-body-td-cost">

                                        @if($a->discount == 0 || $a->discount == null)
                                            @if(isset($a->price))
                                                <div class="project-table-body-td-cost__new">{{ number_format($a->price, 2, ',', '') }}&nbsp;руб.</div>
                                            @endif
                                        @else
                                            @if(isset($a->discount))
                                                <div class="project-table-body-td-cost__new">{{ number_format($a->discount, 2, ',', '') }}&nbsp;руб.</div>
                                            @endif

                                            @if(isset($a->price))
                                                <div class="project-table-body-td-cost__old">{{ number_format($a->price, 2, ',', '') }}&nbsp;руб.</div>
                                            @endif
                                        @endif


                                        {{--@if($a->discount != 0)--}}
                                            {{--<div class="project-table-body-td-cost__new">{{ number_format($a->discount, 2, ',', '') }} руб.</div>--}}
                                        {{--@else--}}
                                            {{--<div class="project-table-body-td-cost__new">Бесплатно</div>--}}
                                        {{--@endif--}}
                                        {{--<div class="project-table-body-td-cost__old">{{ number_format($a->price, 2, ',', '') }} руб.</div>--}}


                                    </td>
                                    <td class="project-table-body-td project-table-body-td--to-basket"
                                    data-analyse-id="{{ $a->id }}">
                                    <div class="to-basket-btn">
                                        <div class="added-text">Добавлено в корзину</div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="22" viewBox="0 0 27 22"><path id="cart" fill="#3ec4f0" fill-rule="evenodd" d="M640,6638H619a1,1,0,0,1,0-2h21A1,1,0,0,1,640,6638Zm-4,10a1,1,0,0,1,0,2H620c-0.019,0-.034-0.01-0.053-0.01a0.714,0.714,0,0,1-.777-0.38l-0.061-.18a0.818,0.818,0,0,1-.05-0.14l-5.031-14.22a0.874,0.874,0,0,1,.665-0.98,0.846,0.846,0,0,1,1.131.3l4.814,13.61H636Zm-12.5,3a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,623.5,6651Zm9,0a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,632.5,6651Zm-10.5-4a1,1,0,0,1,0-2h15a1,1,0,0,1,0,2H622Zm-1-3a1,1,0,0,1,0-2h17a1,1,0,0,1,0,2H621Zm-1-3a1,1,0,0,1,0-2h19a1,1,0,0,1,0,2H620Z" transform="translate(-614 -6634)"/></svg>
                                        <div class="added-text added-text--on-hover">Добавить в корзину</div>
                                    </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($complex->rAnalyses()->whereIsActive(true)->count() > 0)
                        <div class="project-table-mobile">
                            @foreach($complex->rAnalyses()->whereIsActive(true)->get() as $a)
                            <div class="project-table-mobile-tr" data-alphabet="а">
                                    <div class="project-table-mobile-code">Код: <span>{{ $a->code }}</span></div>
                                    <div class="project-table-mobile-content">
                                        <div class="project-table-mobile-content-text">
                                            <div class="project-table-mobile-content-td project-table-mobile-content-td--name">
                                                <a href="{{ route('analyses.show', ['slug' => $a->slug]) }}">
                                                    {{ $a->title }}
                                                </a>
                                                @if($a->material != '' || $a->preparation)
                                                <div class="explain-text">
                                                    @if($a->material != '')
                                                        Биоматериал: {{ $a->material }} <br>
                                                    @endif
                                                    @if($a->preparation != '')
                                                        Подготовка: {{ $a->preparation }}
                                                    @endif
                                                </div>
                                               @endif
                                            </div>
                                            <div class="project-table-mobile-content-td project-table-mobile-content-td--category">Категория: <span>

                                                    {{ isset($a->rAnalysesCategories->parent)
                                                        ? $a->rAnalysesCategories->parent->title
                                                        : $a->rAnalysesCategories->title
                                                    }}

                                                </span></div>
                                            <div class="project-table-mobile-content-td project-table-mobile-content-td--term">Срок: {{ $a->term }} дн.</div>


                                            @if($a->discount == 0 || $a->discount == null)
                                                @if(isset($a->price))
                                                    <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($a->price, 2, ',', '') }}&nbsp;руб.</div>
                                                @endif
                                            @else
                                                @if(isset($a->discount))
                                                    <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($a->discount, 2, ',', '') }}&nbsp;руб.</div>
                                                @endif

                                                @if(isset($a->price))
                                                    <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__old">{{ number_format($a->price, 2, ',', '') }}&nbsp;руб.</div>
                                                @endif
                                            @endif

                                            {{--@if($a->discount != 0)--}}
                                                {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($a->discount, 2, ',', '') }} руб.</div>--}}
                                            {{--@else--}}
                                                {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">Бесплатно</div>--}}
                                            {{--@endif--}}
                                            {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__old">{{ number_format($a->price, 2, ',', '') }} руб.</div>--}}


                                        </div>
                                        <div class="project-table-mobile-content-btn">
                                            <div class="project-table-mobile-content-btn-td">
                                                <div class="project-icon-btn show-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-down" width="20" height="22" viewBox="0 0 20 22"><path id="open" fill="#3ec4f0" fill-rule="evenodd" d="M1097,1314a8,8,0,0,1-15.41,3H1078a1,1,0,0,1,0-2h3.07a8.258,8.258,0,0,1-.07-1h-3a1,1,0,0,1,0-2h3.26a8.361,8.361,0,0,1,.33-1H1078a1,1,0,0,1,0-2h4.76a8.236,8.236,0,0,1,.96-1H1078a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1c0,0.03-.01.05-0.01,0.07A7.994,7.994,0,0,1,1097,1314Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,1089,1308Zm0.75,10.67a0.89,0.89,0,0,1-.35.23,1.028,1.028,0,0,1-1.17-.2l-2.93-2.93a1.032,1.032,0,0,1,0-1.47,1.02,1.02,0,0,1,1.46,0l1.24,1.23V1310a1,1,0,0,1,2,0v5.51l1.21-1.22a1.038,1.038,0,0,1,1.46,0,1.02,1.02,0,0,1,0,1.46ZM1092,1305h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1305Zm0-3h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1302Z" transform="translate(-1077 -1300)"/></svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-up" width="20" height="22" viewBox="0 0 20 22"><path id="roll-up" fill="#3ec4f0" full-rule="evenodd"  d="M575,1501a8,8,0,0,1-15.413,3H556a1,1,0,0,1,0-2h3.069a8.06,8.06,0,0,1-.069-1h-3a1,1,0,0,1,0-2h3.262a7.837,7.837,0,0,1,.325-1H556a1,1,0,0,1,0-2h4.76a8.4,8.4,0,0,1,.961-1H556a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1,0.43,0.43,0,0,1-.014.07A7.983,7.983,0,0,1,575,1501Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,567,1495Zm2.238,5.71-1.219-1.22V1505a1,1,0,0,1-2.007,0v-5.53l-1.24,1.23a1.041,1.041,0,1,1-1.475-1.47l2.951-2.93a1.03,1.03,0,0,1,1.173-.2,0.953,0.953,0,0,1,.353.23l2.93,2.92a1.029,1.029,0,0,1,0,1.46A1.045,1.045,0,0,1,569.238,1500.71ZM570,1492H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1492Zm0-3H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1489Z" transform="translate(-555 -1487)"/></svg>
                                                </div>
                                            </div>
                                            <div class="project-table-mobile-content-btn-td project-table-mobile-content-btn-td-to-basket"
                                            data-analyse-id="{{ $a->id }}">
                                                <div class="project-icon-btn add-basket">
                                                    <div class="added-text">Добавлено в корзину</div>
                                                    <svg class="to-basket-btn" xmlns="http://www.w3.org/2000/svg" width="27" height="22" viewBox="0 0 27 22"><path id="cart" fill="#3ec4f0" fill-rule="evenodd" d="M640,6638H619a1,1,0,0,1,0-2h21A1,1,0,0,1,640,6638Zm-4,10a1,1,0,0,1,0,2H620c-0.019,0-.034-0.01-0.053-0.01a0.714,0.714,0,0,1-.777-0.38l-0.061-.18a0.818,0.818,0,0,1-.05-0.14l-5.031-14.22a0.874,0.874,0,0,1,.665-0.98,0.846,0.846,0,0,1,1.131.3l4.814,13.61H636Zm-12.5,3a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,623.5,6651Zm9,0a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,632.5,6651Zm-10.5-4a1,1,0,0,1,0-2h15a1,1,0,0,1,0,2H622Zm-1-3a1,1,0,0,1,0-2h17a1,1,0,0,1,0,2H621Zm-1-3a1,1,0,0,1,0-2h19a1,1,0,0,1,0,2H620Z" transform="translate(-614 -6634)"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                 @endif
            </div>
        </div>
    </section>
    <section>
        <div class="container-fluid container--with-padding posRel">
            <div class="analyses-price-text paragraph-2">**Цена указана без учета стоимости взятия биоматериала. Услуги по взятию биоматериала добавляются в предварительный заказ автоматически. При единовременном заказе нескольких услуг, услуга по сбору биоматериала оплачивается только один раз. Взятие крови из вены - 100&nbsp;руб., Взятие мазка из урогенитального тракта - 150&nbsp;руб., Взятие мазка (прочее: из зева, носа, уха, глаза, раневой поверхности и т. д.) - 70&nbsp;руб. </div>
        </div>
    </section>
    <div class="pageBuffer"></div>
</div>
    @include('includes/pop-up15s')
    @include('includes/pop-upConsult')
    <div id="alert-container" class="d-none" data-alert-url="{{ route('alertPromotionsShow') }}"></div>
@endsection
@push('scripts')
    <noscript id="deferred-styles">
            <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
            <link rel="stylesheet" href="{{ config('app.sub_domains.src') }}{{ mix('css/analysesDetail.css') }}">
    </noscript>
    <div id="loadNoFrame" data-srcScript="{{ config('app.sub_domains.src') }}{{ mix('js/appNoFrame.js') }}"></div>
    <script>
        var loadDeferredStyles = function() {
            var addStylesNode = document.getElementById("deferred-styles");
            var replacement = document.createElement("div");
            replacement.innerHTML = addStylesNode.textContent;
            replacement.id = "deferred-styles";
            document.body.appendChild(replacement);
            addStylesNode.parentElement.removeChild(addStylesNode);
        };
        var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
            window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        if (raf) {
            raf(function() {
                window.setTimeout(loadDeferredStyles, 0);
            });
        }
        else window.addEventListener('load', loadDeferredStyles);
        var scriptNoFrame = document.createElement('script');
        var noFrameContainer = document.querySelector('#loadNoFrame');
        scriptNoFrame.src = noFrameContainer.getAttribute('data-srcScript');
        if(window.parent == window.top){
          document.body.appendChild(scriptNoFrame);
        }
    </script>
    <script src="{{ config('app.sub_domains.src') }}{{ mix('js/app.js') }}" defer></script>
    <script src="{{ config('app.sub_domains.src') }}{{ mix('js/analysesDetail.js') }}" defer></script>
@endpush