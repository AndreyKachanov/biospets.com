@php
    /** @var \App\Models\AnalyseCategory $analysecategory */
@endphp

@extends('layouts.master')
@section ('head')
    <title>Лабораторные анализы</title>
    <style>{!! view()->file(public_path('css/critical/analyses.css'))  !!}</style>
@endsection

@section ('body')
    <div class="pageWrap" id="pageWrap">
        <section class="promotion-detail">
            <div class="promotion-detail-baner__wrapper">
                <div class="block-baner-photo promotion-detail--litle-height">
                    <div class="block-baner-photo__content-position">
                        <div class="block-baner-photo__bg">
                            <div class="block-baner-photo__img-wrap">
                                <picture>
                                    <source media="(min-width: 1200px)" srcset="{{config('app.sub_domains.img1')}}/assets/images/pages/analyses/analyses-page-bg_lg.jpg">
                                    <source media="(min-width: 992px)" srcset="{{config('app.sub_domains.img1')}}/assets/images/pages/analyses/analyses-page-bg_md.jpg">
                                    <source media="(min-width: 768px)" srcset="{{config('app.sub_domains.img1')}}/assets/images/pages/analyses/analyses-page-bg_sm.jpg">
                                    <img src="{{config('app.sub_domains.img1')}}/assets/images/pages/analyses/analyses-page-bg_xs.jpg" alt="–">
                                </picture>
                            </div>
                            <div class="block-baner-photo__overlay block-baner-photo">
                                <div class="block-baner-photo-layer block-baner-photo-layer--1"></div>
                                <div class="block-baner-photo-layer block-baner-photo-layer--2"></div>
                                <div class="block-baner-photo-layer block-baner-photo-layer--3"></div>
                                <div class="block-baner-photo-layer block-baner-photo-layer--4"></div>
                                <div class="block-baner-photo-layer block-baner-photo-layer--5"></div>
                            </div>
                        </div>
                        <div class="block-baner-photo__content-wrap">
                            <div class="block-baner-photo__content">
                                <h1 class="promotions-item__title title-1">
                                    Лабораторные <br> анализы
                                </h1>
                                <div class="pararagraph-position">
                                    <p class="content-text contacts__desc">
                                        Медицинский центр «Биоспец» обладает множеством преимуществ. Наш диагностический центр находится в зоне удобной транспортной развязки: помимо станции метро Китай–город, рядом расположены остановки маршрутных автобусов, такси и троллейбусов.
                                    </p>
                                </div>
                                <div class="consult-btn projectBtn projectBtn--1">Получить консультацию</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <main>
            <section class="analyses-price-block">
                <div class="container-fluid container--with-padding">
                    <div class="block-background block-background--2 block-background-img--{{$imgArray[0]}}"></div>
                    <div class="service-content">
                        <div class="title-2 service-content__title">Стоимость услуг</div>
                        <div class="service-content-container">
                            <div class="service-content-block">
                                <div class="service-content-block-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                        <path fill="#0878cf" fill-rule="evenodd" id="blood" d="M79,954a32,32,0,1,1,32-32A32,32,0,0,1,79,954Zm0-62a30,30,0,1,0,30,30A30,30,0,0,0,79,892Zm15.983,35.662c0,0.113.017,0.224,0.017,0.338a16,16,0,0,1-32,0c0-.333.03-0.658,0.05-0.986L63,927s0.717-9.056,5-16a164.447,164.447,0,0,1,11-15s5.648,6.808,9.792,13.08A9.991,9.991,0,0,1,94.983,927.662ZM79,898.991V899a101.485,101.485,0,0,0-10,14c-0.52.92-.965,1.8-1.353,2.635C64.76,920.945,65,928,65,928a13.991,13.991,0,0,0,27.972.551A10,10,0,0,1,86.889,909.5,108.907,108.907,0,0,0,79,898.991ZM90,911a8,8,0,1,0,8,8A8,8,0,0,0,90,911Zm2.346,8.329-0.559.443a1.732,1.732,0,0,0-.606.846,3.653,3.653,0,0,0-.1.951H88.941a6.472,6.472,0,0,1,.252-1.882,3.3,3.3,0,0,1,1.054-1.2l0.575-.46a2.032,2.032,0,0,0,.456-0.475,1.65,1.65,0,0,0,.315-0.975,1.912,1.912,0,0,0-.35-1.116,1.453,1.453,0,0,0-1.279-.5,1.4,1.4,0,0,0-1.3.62,2.43,2.43,0,0,0-.382,1.29H86.005a3.787,3.787,0,0,1,1.57-3.256A4.077,4.077,0,0,1,89.864,913a4.858,4.858,0,0,1,2.963.87,3,3,0,0,1,1.18,2.579,2.961,2.961,0,0,1-.512,1.765A5.967,5.967,0,0,1,92.346,919.329ZM91.245,925H88.885v-2.328h2.359V925ZM79,938a11,11,0,0,1-11-11,1,1,0,0,1,2,0,9,9,0,0,0,9,9A1,1,0,0,1,79,938Z" transform="translate(-47 -890)"/>
                                    </svg>
                                </div>
                                <div class="service-content-block-text">
                                    <div class="service-content-block-text__text">Анализ на группу <br>и резус-фактор крови</div>
                                    <div class="service-content-block-text__cost">400 руб.</div>
                                </div>
                            </div>
                            <div class="service-content-block">
                                <div class="service-content-block-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                        <path fill="#0878cf" fill-rule="evenodd" id="hiv" d="M399,954a32,32,0,1,1,32-32A32,32,0,0,1,399,954Zm0-62a30,30,0,1,0,30,30A30,30,0,0,0,399,892Zm8.122,52L399.5,930.167,391.878,944,386,941s4.813-9.075,9.553-18l-6.063-11a6.21,6.21,0,0,1,0-5,31.988,31.988,0,0,1,1.874-4.138c0.773-2,2.953-5.942,8.136-5.862a8.692,8.692,0,0,1,8.077,5.753A32.115,32.115,0,0,1,409.51,907a6.21,6.21,0,0,1,0,5l-6.063,11c4.739,8.922,9.553,18,9.553,18Zm-18.532-3.787,2.427,1.408,7.389-13.439-1.8-3.26ZM399.425,898.9c-4.8-.079-6.029,2.692-6.346,4.1,0.057,0.159.112,0.314,0.175,0.481,0.582-.528,2.2-1.534,6.125-1.53,3.715,0,5.558,1.008,6.334,1.615,0.082-.216.156-0.42,0.228-0.622C405.443,901.468,403.938,898.976,399.425,898.9Zm-5.379,6.43c0.111,0.228.223,0.454,0.342,0.668,0.372,0.672,2.462,4.593,5.112,9.575,2.648-4.978,4.74-8.9,5.112-9.575,0.121-.218.235-0.448,0.348-0.68A11.892,11.892,0,0,0,394.046,905.332Zm13.56,6.118a4.312,4.312,0,0,0-.228-4.855c-0.238-.147-0.532-0.322-0.884-0.515l-5.981,11.4c0.6,1.135,1.225,2.3,1.857,3.493Zm-15.095-5.361c-0.358.195-.657,0.371-0.9,0.518a4.308,4.308,0,0,0-.22,4.843l16.589,30.171,2.427-1.408Z" transform="translate(-367 -890)"/>
                                    </svg>
                                </div>
                                <div class="service-content-block-text">
                                    <div class="service-content-block-text__text">Анализ на ВИЧ</div>
                                    <div class="service-content-block-text__cost">400 руб.</div>
                                </div>
                            </div>
                        </div>
                        <div class="service-content-container-btn">
                            <div class="consult-btn projectBtn projectBtn--1" data-modal-title="Запись на прием">Сдать анализ</div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="container-fluid container--with-padding">
                    <div class="control-block" id="tableServices">
                        <div id="analysesUrl" data-uzi-service="{{ route('ajaxGetAnalyses') }}"></div>
                        <div class="control-block-category">
                            <div class="btn-group projectBtn-group" id="selectCategory">
                                <button type="button" id="btnCategory" class="dropDownBtn" data-toggle="dropdown" data-flip="false" aria-haspopup="true" aria-expanded="false">Все категории</button>
                                <button type="button" class="dropDownArrow">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" id="dropdown-menu-analyses-category">
                                    <a class="dropdown-item" id="resultSeaech" href="#" data-category-id='-2'>Результаты поиска</a>
                                    <a class="dropdown-item active" href="#" data-category-id='0'>Все категории</a>
                                    <a class="dropdown-item" href="#" data-category-id='-1'>Комплексы</a>
                                    @if(isset($analysesCategories))
                                        @foreach($analysesCategories as $analysecategory)
                                            <a class="dropdown-item" href="#" data-category-id="{{ $analysecategory->id }}">{{ $analysecategory->title }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="btn-group projectBtn-group dropdown-menu--disabled">
                                <button type="button" id="btnSubCategory" class="dropDownBtn" data-toggle="dropdown" data-flip="false" aria-haspopup="true" aria-expanded="false">Все подкатегории</button>
                                <button type="button" class="dropDownArrow">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" id="dropdown-menu-analyses-subcategory">
                                    <a class="dropdown-item active" href="#" data-subcategory-id='0'>Все подкатегории</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('ajaxSearchAnalysesButtonClick') }}" method="post" novalidate id="analisesSearchForm" class="control-block-search">
                            <div class="projectFormGroup projectFormSearchGroup" id="serchBlock" data-url-serch="{{route('ajaxSearchAnalyses')}}">
                                <div class="control-block-search-result">
                                    <input id="searchInput" type="text" class="projectFormInput projectFormControl" autocomplete="off" maxlength="200" placeholder="Название исследования или код" data-mobile-placeholder="Название или код" data-desc-placeholder="Название исследования или код">
                                    <div class="control-block-search-result__items" id="shortSearchContainer"></div>
                                </div>
                                <div class="input-group-append">
                                    <div class="d-xs-block d-md-none">
                                        <button type="submit" class="project-icon-btn" id="searchBtnMobile">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21">
                                                <path id="search" fill="#3ec4f0" fill-rule="evenodd" d="M276.573,886.573a1.446,1.446,0,0,1-2.044,0l-3.878-3.878a8.988,8.988,0,1,1,2.21-1.878l3.712,3.712A1.446,1.446,0,0,1,276.573,886.573ZM273,875a7,7,0,1,0-7,7A7,7,0,0,0,273,875Zm-3,0a4,4,0,0,0-4-4,1,1,0,0,1,0-2h0a6,6,0,0,1,6,6A1,1,0,0,1,270,875Z" transform="translate(-257 -866)"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <button type="submit" class="projectBtn projectBtn--2 d-none d-md-block" id="searchBtnDesctop" type="button">Искать</button>
                                </div>
                            </div>
                        </form>
                        <div class="d-none d-md-block control-block-name title-4 preloader-element">Все категории</div>
                        <div class="alphabeticalWrap preloader-element">
                            <div class="alphabeticalBlock alphabeticalBlock--active">Все</div>
                            @foreach($firstLetters as $firstLetter)
                                <div class="alphabeticalBlock">{{ $firstLetter }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="container-fluid container--with-padding">
                    <div class="preloader-container">
                        <div class="preloader">
                                <img src="/assets/images/pages/promotion/preloader-actions-act.svg"/>
                                <div class="preloader__text">
                                    Пожалуйста, подождите...
                                </div>
                        </div>
                    </div>

                    <div class="search__no-res">
                        <div class="title-5">
                            По вашему запросу ничего не найдено.
                        </div>
                        <div class=" consult-link projectLink">
                            <span>Получить консультацию&nbsp;&nbsp;</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="14" viewBox="0 0 18 14">
                                <path id="mail" class="cls-1"
                                        d="M935,796H921a2,2,0,0,1-2-2v-8l9,6,9-6v8A2,2,0,0,1,935,796Zm-16-12a2,2,0,0,1,2-2h14a2,2,0,0,1,2,2l-9,6Z"
                                        transform="translate(-919 -782)"/>
                            </svg>
                        </div>
                    </div>

                    <div class="table-w-alphabet preloader-element">
                        <div class="table-w-alphabet-td">
                            <div class="project-table-block">
                                {{-- ANALYSES TABLE --}}
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
                                        @foreach($analyses as $analysesIndex => $analyse)
                                        @if($analysesIndex == 0)
                                        <tr class="project-table-body-tr tr-first" data-explain-id="{{ $analyse->id }}">
                                            <td class="project-table-body-td project-table-body-td--code">
                                                {{ $analyse->code }}
                                                <div class="letter-block">{{ $analyse->first_letter }}</div>
                                        @elseif(($analysesIndex >= 0) && $analyse->first_letter != $analyses[$analysesIndex - 1]->first_letter)
                                            <tr class="project-table-body-tr tr-first" data-explain-id="{{ $analyse->id }}">
                                                <td class="project-table-body-td project-table-body-td--code">
                                                    {{ $analyse->code }}
                                                        <div class="letter-block">{{ $analyse->first_letter }}</div>
                                        @else
                                            <tr class="project-table-body-tr" data-explain-id="{{ $analyse->id }}">
                                                <td class="project-table-body-td project-table-body-td--code">
                                                    {{ $analyse->code }}
                                        @endif
                                            </td>
                                            <td class="project-table-body-td project-table-body-td--name">

                                                <a href="{{ route('analyses.show', ['slug' => $analyse->slug]) }}">
                                                    {{ $analyse->title }}
                                                </a>
                                                 @if($analyse->material != '' || $analyse->preparation)
                                                <div class="explain-text">
                                                <div class="project-table-mobile-content-td project-table-mobile-content-td--category d-none d-md-block d-lg-none">
                                                    Категория: <span>
                                                    {{ isset($analyse->rAnalysesCategories->parent)
                                                        ? $analyse->rAnalysesCategories->parent->title
                                                        : $analyse->rAnalysesCategories->title
                                                    }}
                                                    </span>
                                                </div>
                                                    @if($analyse->material != '')
                                                        Биоматериал: {{ $analyse->material }} <br>
                                                    @endif
                                                    @if($analyse->preparation != '')
                                                        Подготовка: {{ $analyse->preparation }}
                                                    @endif
                                                </div>
                                                @endif
                                            </td>
                                            <td class="project-table-body-td project-table-body-td--show-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-down" width="20" height="22" viewBox="0 0 20 22"><path id="open" fill="#3ec4f0" fill-rule="evenodd" d="M1097,1314a8,8,0,0,1-15.41,3H1078a1,1,0,0,1,0-2h3.07a8.258,8.258,0,0,1-.07-1h-3a1,1,0,0,1,0-2h3.26a8.361,8.361,0,0,1,.33-1H1078a1,1,0,0,1,0-2h4.76a8.236,8.236,0,0,1,.96-1H1078a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1c0,0.03-.01.05-0.01,0.07A7.994,7.994,0,0,1,1097,1314Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,1089,1308Zm0.75,10.67a0.89,0.89,0,0,1-.35.23,1.028,1.028,0,0,1-1.17-.2l-2.93-2.93a1.032,1.032,0,0,1,0-1.47,1.02,1.02,0,0,1,1.46,0l1.24,1.23V1310a1,1,0,0,1,2,0v5.51l1.21-1.22a1.038,1.038,0,0,1,1.46,0,1.02,1.02,0,0,1,0,1.46ZM1092,1305h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1305Zm0-3h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1302Z" transform="translate(-1077 -1300)"/></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-up" width="20" height="22" viewBox="0 0 20 22"><path id="roll-up" fill="#3ec4f0" full-rule="evenodd"  d="M575,1501a8,8,0,0,1-15.413,3H556a1,1,0,0,1,0-2h3.069a8.06,8.06,0,0,1-.069-1h-3a1,1,0,0,1,0-2h3.262a7.837,7.837,0,0,1,.325-1H556a1,1,0,0,1,0-2h4.76a8.4,8.4,0,0,1,.961-1H556a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1,0.43,0.43,0,0,1-.014.07A7.983,7.983,0,0,1,575,1501Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,567,1495Zm2.238,5.71-1.219-1.22V1505a1,1,0,0,1-2.007,0v-5.53l-1.24,1.23a1.041,1.041,0,1,1-1.475-1.47l2.951-2.93a1.03,1.03,0,0,1,1.173-.2,0.953,0.953,0,0,1,.353.23l2.93,2.92a1.029,1.029,0,0,1,0,1.46A1.045,1.045,0,0,1,569.238,1500.71ZM570,1492H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1492Zm0-3H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1489Z" transform="translate(-555 -1487)"/></svg>
                                            </td>
                                            <td class="project-table-body-td project-table-body-td--term">{{ $analyse->term }} дн.</td>
                                            <td class="project-table-body-td project-table-body-td--category">

                                                {{ isset($analyse->rAnalysesCategories->parent)
                                                    ? $analyse->rAnalysesCategories->parent->title
                                                    : $analyse->rAnalysesCategories->title
                                                }}
                                            </td>
                                            <td class="project-table-body-td project-table-body-td-cost">

                                                @if($analyse->discount == 0 || $analyse->discount == null)
                                                    @if(isset($analyse->price))
                                                        <div class="project-table-body-td-cost__new">{{ number_format($analyse->price, 2, ',', '') }}&nbsp;руб.</div>
                                                    @endif
                                                @else
                                                    @if(isset($analyse->discount))
                                                        <div class="project-table-body-td-cost__new">{{ number_format($analyse->discount, 2, ',', '') }}&nbsp;руб.</div>
                                                    @endif

                                                    @if(isset($analyse->price))
                                                        <div class="project-table-body-td-cost__old">{{ number_format($analyse->price, 2, ',', '') }}&nbsp;руб.</div>
                                                    @endif
                                                @endif

                                                {{--@if($analyse->price == 0)--}}
                                                    {{--<div class="project-table-body-td-cost__new">Бесплатно</div>--}}
                                                {{--@else--}}
                                                    {{--@if(!is_null($analyse->discount))--}}
                                                        {{--@if($analyse->discount == 0)--}}
                                                            {{--<div class="project-table-body-td-cost__new">Бесплатно</div>--}}
                                                        {{--@else--}}
                                                            {{--<div class="project-table-body-td-cost__new">{{ number_format($analyse->discount, 2, ',', '') }} руб.</div>--}}
                                                        {{--@endif--}}

                                                            {{--<div class="project-table-body-td-cost__old">{{ number_format($analyse->price, 2, ',', '') }} руб.</div>--}}
                                                    {{--@else--}}
                                                        {{--<div style="text-decoration: none;" class="project-table-body-td-cost__new">{{ number_format($analyse->price, 2, ',', '') }} руб.</div>--}}
                                                    {{--@endif--}}

                                                {{--@endif--}}

                                            </td>
                                            <td class="project-table-body-td project-table-body-td--to-basket"
                                            data-analyse-id="{{ $analyse->id }}">
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
                                <div class="project-table-mobile">
                                    @foreach($analyses as $analysesIndex => $analyse)
                                        @if($analysesIndex == 0)
                                            <div class="project-table-mobile-letter">{{ $analyse->first_letter }}</div>
                                        @elseif(($analysesIndex >= 0) && $analyse->first_letter != $analyses[$analysesIndex - 1]->first_letter)
                                            <div class="project-table-mobile-letter">{{ $analyse->first_letter }}</div>
                                        @endif
                                        <div class="project-table-mobile-tr"  data-explain-id="{{ $analyse->id }}">
                                            <div class="project-table-mobile-code">Код: <span>{{ $analyse->code }}</span></div>
                                            <div class="project-table-mobile-content">
                                                <div class="project-table-mobile-content-text">
                                                    <div class="project-table-mobile-content-td project-table-mobile-content-td--name">
                                                        <a href="{{ route('analyses.show', ['slug' => $analyse->slug]) }}">
                                                            {{ $analyse->title }}
                                                        </a>
                                                        @if($analyse->material != '' || $analyse->preparation)
                                                       <div class="explain-text">
                                                           @if($analyse->material != '')
                                                               Биоматериал: {{ $analyse->material }} <br>
                                                           @endif
                                                           @if($analyse->preparation != '')
                                                               Подготовка: {{ $analyse->preparation }}
                                                           @endif
                                                       </div>
                                                       @endif
                                                    </div>
                                                    <div class="project-table-mobile-content-td project-table-mobile-content-td--category">
                                                        Категория:
                                                        <span>
                                                    {{ isset($analyse->rAnalysesCategories->parent)
                                                        ? $analyse->rAnalysesCategories->parent->title
                                                        : $analyse->rAnalysesCategories->title
                                                    }}
                                                        </span>
                                                    </div>
                                                    <div class="project-table-mobile-content-td project-table-mobile-content-td--term">Срок: {{ $analyse->term }} дн.</div>


                                                    @if($analyse->discount == 0 || $analyse->discount == null)
                                                        @if(isset($analyse->price))
                                                            <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($analyse->price, 2, ',', '') }}&nbsp;руб.</div>
                                                        @endif
                                                    @else
                                                        @if(isset($analyse->discount))
                                                            <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($analyse->discount, 2, ',', '') }}&nbsp;руб.</div>
                                                        @endif

                                                        @if(isset($analyse->price))
                                                            <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__old">{{ number_format($analyse->price, 2, ',', '') }}&nbsp;руб.</div>
                                                        @endif

                                                    @endif

                                                    {{--@if($analyse->price == 0)--}}
                                                        {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">Бесплатно</div>--}}
                                                    {{--@else--}}
                                                        {{--@if(!is_null($analyse->discount))--}}
                                                            {{--@if($analyse->discount == 0)--}}
                                                                {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">Бесплатно</div>--}}
                                                            {{--@else--}}
                                                                {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($analyse->discount, 2, ',', '') }} руб.</div>--}}
                                                            {{--@endif--}}

                                                                {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__old">{{ number_format($analyse->price, 2, ',', '') }} руб.</div>--}}
                                                        {{--@else--}}
                                                            {{--<div style="text-decoration: none;" class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($analyse->price, 2, ',', '') }} руб.</div>--}}
                                                        {{--@endif--}}
                                                    {{--@endif--}}

                                                </div>
                                                <div class="project-table-mobile-content-btn">
                                                    <div class="project-table-mobile-content-btn-td">
                                                        <div class="project-icon-btn show-btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-down" width="20" height="22" viewBox="0 0 20 22"><path id="open" fill="#3ec4f0" fill-rule="evenodd" d="M1097,1314a8,8,0,0,1-15.41,3H1078a1,1,0,0,1,0-2h3.07a8.258,8.258,0,0,1-.07-1h-3a1,1,0,0,1,0-2h3.26a8.361,8.361,0,0,1,.33-1H1078a1,1,0,0,1,0-2h4.76a8.236,8.236,0,0,1,.96-1H1078a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1c0,0.03-.01.05-0.01,0.07A7.994,7.994,0,0,1,1097,1314Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,1089,1308Zm0.75,10.67a0.89,0.89,0,0,1-.35.23,1.028,1.028,0,0,1-1.17-.2l-2.93-2.93a1.032,1.032,0,0,1,0-1.47,1.02,1.02,0,0,1,1.46,0l1.24,1.23V1310a1,1,0,0,1,2,0v5.51l1.21-1.22a1.038,1.038,0,0,1,1.46,0,1.02,1.02,0,0,1,0,1.46ZM1092,1305h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1305Zm0-3h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1302Z" transform="translate(-1077 -1300)"/></svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-up" width="20" height="22" viewBox="0 0 20 22"><path id="roll-up" fill="#3ec4f0" full-rule="evenodd"  d="M575,1501a8,8,0,0,1-15.413,3H556a1,1,0,0,1,0-2h3.069a8.06,8.06,0,0,1-.069-1h-3a1,1,0,0,1,0-2h3.262a7.837,7.837,0,0,1,.325-1H556a1,1,0,0,1,0-2h4.76a8.4,8.4,0,0,1,.961-1H556a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1,0.43,0.43,0,0,1-.014.07A7.983,7.983,0,0,1,575,1501Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,567,1495Zm2.238,5.71-1.219-1.22V1505a1,1,0,0,1-2.007,0v-5.53l-1.24,1.23a1.041,1.041,0,1,1-1.475-1.47l2.951-2.93a1.03,1.03,0,0,1,1.173-.2,0.953,0.953,0,0,1,.353.23l2.93,2.92a1.029,1.029,0,0,1,0,1.46A1.045,1.045,0,0,1,569.238,1500.71ZM570,1492H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1492Zm0-3H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1489Z" transform="translate(-555 -1487)"/></svg>
                                                        </div>
                                                    </div>
                                                    <div class="project-table-mobile-content-btn-td project-table-mobile-content-btn-td-to-basket"
                                                    data-analyse-id="{{ $analyse->id }}">
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
                            </div>
                        </div>
                    </div>
                    <div class="analyses-table-btn-contant">
                        <div class="consult-btn projectBtn projectBtn--1" data-modal-title="Запись на прием">Сдать анализ</div>
                        <div class="projectBtn projectBtn--2" id="btnShoveMoreTable">Показать еще</div>
                    </div>
                </div>
            </section>
            <div id="defaultArticle">
                <section class="content-section">
                    <div class="container-fluid container--with-padding">
                        <h2 class="title-2">Сдача анализов</h2>
                        <div class="article-block color-grey">
                            <p>
                                <strong>
                                    Без лабораторных исследований невозможна своевременная и точная диагностика различных заболеваний. Болезнь не всегда сразу внешне проявляется, поэтому необходимо сдавать кровь на анализы, чтобы вовремя определить или исключить патологию. По одному анализу диагноз не поставят, но по общей клинической картине, основанной по другим исследованиям и наблюдениям, уже можно определить направление поиска.
                                </strong>
                            </p>
                            <p>
                                Регулярные профилактические обследования - это залог своевременного начала лечения, а ответственная подготовка к сдаче анализов в современной лаборатории - это точный диагноз.
                            </p>
                        </div>
                    </div>
                </section>
                <section class="content-section">
                    <div class="container-fluid container--with-padding">
                        <div class="block-background block-background--1  block-background-img--{{$imgArray[2]}} d-none d-md-block"></div>
                        <div class="title-4">Какие анализы можно сдать <br class="d-none d-md-block">в нашем медцентре?</div>
                        <div class="article-block color-grey">
                            <p>Лаборатория «Биоспец» проводит исследования в следующих направлениях:</p>
                            <ul>
                                <li>гематология (СОЭ, лейкоцитарная формула);</li>
                                <li>изосерология (группа крови и резус-фактор);</li>
                                <li>гемостаз (протромбин, фибриноген, антитромбин и др.);</li>
                                <li>биохимия крови (холестерин, билирубин, кальций и проч.);</li>
                                <li>белковый обмен (альбумин, креатинин, мочевина, миоглобин, ревматоидный фактор и т.д.);</li>
                                <li>углеводный обмен (глюкоза в сыворотке крови, гликированный гемоглобин);</li>
                                <li>гормональная система (оценка функций щитовидной железы, диагностика репродуктивной системы);</li>
                                <li>исследования на онкомаркеры;</li>
                                <li>серологические маркеры инфекционных заболеваний;</li>
                                <li>урогенитальный мазок и т.д.</li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="content-section">
                    <div class="container-fluid container--with-padding">
                        <div class="title-4">Подготовка к анализам</div>
                        <div class="article-block color-grey">
                            <p>Для точности результатов, необходимо придерживаться некоторых правил. Для каждого исследования свои требования, но мы назовем самые распространенные:</p>
                            <ul>
                                <li>Рекомендуется сдавать кровь утром, в период с 8 до 11 часов, натощак (не менее 8 часов и не более 14 часов голода, питье – вода, в обычном режиме), накануне избегать пищевых перегрузок.</li>
                                <li>Если вы принимаете какие-то лекарственные препараты – следует проконсультироваться с врачом по поводу возможности отмены приема препарата перед исследованием, длительность отмены определяется периодом выведения препарата из крови.</li>
                                <li>Алкоголь – исключить прием алкоголя накануне исследования.</li>
                                <li>Курение – не курить минимально за 1 час до исследования.</li>
                                <li>Исключить физические и эмоциональные нагрузки накануне исследования</li>
                                <li>После прихода в лабораторию посидеть 10-20 минут перед взятием проб крови.</li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="content-section">
                    <div class="container-fluid container--with-padding">
                        <div class="title-4">Преимущества медцентра «Биоспец»</div>
                        <div class="article-block color-grey">
                            <ul>
                                <li>Экономия вашего времени не только на ожидание в очереди, а и на поездку к нам, так как мы находимся в центре города, недалеко от метро «Китай-город».</li>
                                <li>Комфорт и уют при сдаче анализов – наши специалисты приветливые, внимательные и заботятся об эмоциональном фоне наших пациентов. </li>
                                <li>Точность исследований и уверенность в своей безопасности – для забора анализов, мы используем одноразовые, стерильные материалы, применяем качественные реагенты и высокоточное, современное оборудование.</li>
                            </ul>
                            <p>Стоимость анализов можете уточнить в нашем прайсе на сайте. Полный список подготовительных мероприятий – у своего лечащего врача, который выдает направления на обследование, или у наших специалистов.</p>
                        </div>
                    </div>
                </section>
            </div>
            <div class="analyses-table-btn-contant container-fluid container--with-padding">
                <div class="consult-btn projectBtn projectBtn--1" data-modal-title="Запись на прием">Сдать анализ</div>
            </div>
        </main>
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
        <link rel="stylesheet" href="{{ config('app.sub_domains.src') }}{{ mix('css/analyses.css') }}">
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
    <script src="{{ config('app.sub_domains.src') }}{{ mix('js/analyses.js') }}" defer></script>
@endpush
