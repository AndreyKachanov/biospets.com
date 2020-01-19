@php
    /** @var \App\Models\Analyse $analise */
@endphp

@extends('layouts.master')
@section ('head')
    <meta charset="UTF-8">

    @if(isset($analise))
        <title>{{ $analise->title or '' }}</title>
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <meta name="theme-color" content="#05a5f8">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    @if(isset($analise))
        <meta name="title" content="{{ $analise->meta_title or mb_substr($analise->title, 0, 80) }}">
        <meta name="description" content="{{ $analise->meta_description or mb_substr(strip_tags($analise->description), 0, 180)}}">

        @if($analise->meta_keywords)
            <meta name="keywords" content="{{ $analise->meta_keywords }}">
        @endif
    @endif
    <!--Critical-->
    <style>{!! view()->file(public_path('css/critical/analysesDetail.css'))  !!}</style>
@endsection

@section ('body')

@if(isset($analise))

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
                    @if ($analise->title)
                        <div class="title-3 analyses-head-block-content__title">
                            {{ $analise->title }}
                        </div>
                    @endif
                    @if ($analise->code)
                         <div class="analyses-head-block__code d-block d-md-none">Код:&nbsp;{{ $analise->code }}</div>
                    @endif
                    <div class="analyses-head-block-text">
                        <div class="analyses-head-block-text-price-code">
                            <div class="paragraph-2 analyses-head-block-text__text">Стоимость</div>
                            @if ($analise->code)
                                <div class="analyses-head-block__code d-none d-md-block">Код:&nbsp;{{ $analise->code }}</div>
                            @endif
                        </div>
                        <div class="analyses-head-block-text-price">

                            @if($analise->discount == 0 || $analise->discount == null)
                                @if(isset($analise->price))
                                    <span style="text-decoration: none;" class="analyses-head-block-text-price__new">{{ number_format($analise->price, 2, ',', '') }}&nbsp;руб.</span>
                                @endif
                            @else
                                @if(isset($analise->discount))
                                    <span style="text-decoration: none;" class="analyses-head-block-text-price__new">{{ number_format($analise->discount, 2, ',', '') }}&nbsp;руб.</span>
                                @endif

                                @if(isset($analise->price))
                                    <span class="analyses-head-block-text-price__old">{{ number_format($analise->price, 2, ',', '') }}&nbsp;руб.</span>
                                @endif

                            @endif
                            {{--<p>price - {{ $analise->price }}</p>--}}
                            {{--<p>discount - {{ dump($analise->discount) }}</p>--}}

                            {{--@if($analise->price == 0)--}}
                                {{--<span class="analyses-head-block-text-price__new">Бесплатно</span>--}}
                            {{--@else--}}
                                {{--@if(!is_null($analise->discount))--}}
                                    {{--@if($analise->discount == 0)--}}
                                        {{--<span class="analyses-head-block-text-price__new">Бесплатно</span>--}}
                                    {{--@else--}}
                                        {{--<span class="analyses-head-block-text-price__new">{{ number_format($analise->discount, 2, ',', '') }}&nbsp;руб.</span>--}}
                                    {{--@endif--}}

                                    {{--&nbsp;&nbsp;<span class="analyses-head-block-text-price__old">{{ number_format($analise->price, 2, ',', '') }}&nbsp;руб.</span>--}}
                                {{--@else--}}
                                    {{--&nbsp;&nbsp;<span style="text-decoration: none;" class="analyses-head-block-text-price__new">{{ number_format($analise->price, 2, ',', '') }}&nbsp;руб.</span>--}}
                                {{--@endif--}}

                            {{--@endif--}}

                        </div>
                        <div class="analyses-head-block-text-btn">
                            <div class="add-to-cart projectBtn projectBtn--1 add-to-cart" data-analyse-id="{{ $analise->id }}">Добавить в заказ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container-fluid container--with-padding posRel">
            <div class="analyses-content-block analyses-content-block--1">
                @if ($analise->title_lat)
                    <div class="title-4 analyses-content-block__title text-owerflow">{{ $analise->title_lat }}</div>
                @endif
                <div class="analyses-table">
                    @if ($analise->material)
                    <div class="analyses-table-tr">
                        <div class="analyses-table-td analyses-table-td--1 d-none d-md-block">Биоматериал:</div>
                        <div class="analyses-table-td analyses-table-td--2 text-owerflow"><span>Биоматериал:&nbsp;</span>{{ $analise->material }}</div>
                    </div>
                    @endif
                    @if ($analise->preparation)
                        <div class="analyses-table-tr">
                                <div class="analyses-table-td analyses-table-td--1 d-none d-md-block">Подготовка:</div>
                                <div class="analyses-table-td analyses-table-td--2 text-owerflow"><span class="">Подготовка:</span> {{ $analise->preparation }}</div>
                        </div>
                    @endif
                    @if ($analise->result)
                    <div class="analyses-table-tr">
                        <div class="analyses-table-td analyses-table-td--1 d-none d-md-block">Результат:</div>
                        <div class="analyses-table-td analyses-table-td--2"><span>Результат:</span> {{ $analise->result }}</div>
                    </div>
                    @endif
                    @if ($analise->term)
                    <div class="analyses-table-tr">
                        <div class="analyses-table-td analyses-table-td--1 d-none d-md-block">Срок:</div>
                        <div class="analyses-table-td analyses-table-td--2">
                            <span>Срок:</span>
                            {{ $analise->term }}
                            {{ plural($analise->term, 'день', 'дня', 'дней') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @if ($analise->method)
    <section>
        <div class="container-fluid container--with-padding posRel">
            <div class="analyses-content-block analyses-content-block--text">
                <div class="title-4 analyses-content-block__title">Метод анализа</div>
                <p class="paragraph-2 analyses-content-block__text">{{ $analise->method }}</p>
            </div>
        </div>
    </section>
    @endif
    @if ($analise->description != '<p></p>')
    <section>
        <div class="container-fluid container--with-padding posRel">
            <div class="analyses-content-block analyses-content-block--text article-block">
                <div class="title-4 analyses-content-block__title">Описание</div>
                {!! $analise->description !!}
            </div>
        </div>
    </section>
    @endif
    @if ($analise->price or $analise->price == 0 or !is_null($analise->discount))
    <section>
        <div class="container-fluid container--with-padding posRel">
            <div class="analyses-price-text paragraph-2">**Цена указана без учета стоимости взятия биоматериала. Услуги по взятию биоматериала добавляются в предварительный заказ автоматически. При единовременном заказе нескольких услуг, услуга по сбору биоматериала оплачивается только один раз. Взятие крови из вены - 100&nbsp;руб., Взятие мазка из урогенитального тракта - 150&nbsp;руб., Взятие мазка (прочее: из зева, носа, уха, глаза, раневой поверхности и т. д.) - 70&nbsp;руб. </div>
        </div>
    </section>
    @endif
    <div class="pageBuffer"></div>
</div>
@endif

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
    <script src="{{ config('app.sub_domains.src') }}{{ mix('js/app.js') }}"  defer></script>
    <script src="{{ config('app.sub_domains.src') }}{{ mix('js/analysesDetail.js') }}"  defer></script>
@endpush