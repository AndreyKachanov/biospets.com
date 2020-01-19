<div class="alphabeticalWrap">
    <div class="alphabeticalBlock alphabeticalBlock--active">Все</div>
    @foreach($firstLetters as $firstLetter)
        <div class="alphabeticalBlock">{{ $firstLetter }}</div>
    @endforeach
</div>

<div class="table-w-alphabet">
    <div class="d-none" id="pginatorNav" data-pgination-curent="{{ $complexes->currentPage() }}" data-pagination-pages="{{ $complexes->lastPage() }}"></div>
    <div class="table-w-alphabet-td">
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
                @foreach($complexes as $complexesIndex => $complex)
                    <tr class="project-table-body-tr complecs-tr"
                        data-explain-id="1{{ $complex->id }}"
                        data-complecsses="{{ $complex->rAnalyses()->whereIsActive(true)->count() }}"> {{-- Добавить количество анализов в тесте --}}
                        <td class="project-table-body-td project-table-body-td--code"
                            data-complex-id="{{ $complex->id }}">
                            {{ $complex->code }}
                                <div class="letter-block">{{ $complex->first_letter }}</div>
                        </td>
                        <td class="project-table-body-td project-table-body-td--name">
                            <a href="{{ route('analyseComplexes.show', ['slug' => $complex->slug]) }}">
                                {{ $complex->title }}
                            </a>
                            <div class="project-table-body-td--name-qtt-tests">
                                ({{ $complex->rAnalyses()->whereIsActive(true)->count() }} {{ plural($complex->rAnalyses()->whereIsActive(true)->count(), 'тест', 'теста', 'тестов') }})
                            </div>
                        </td>
                        <td class="project-table-body-td project-table-body-td--show-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-down" width="20"
                                 height="22" viewBox="0 0 20 22">
                                <path id="open" fill="#3ec4f0" fill-rule="evenodd"
                                      d="M1097,1314a8,8,0,0,1-15.41,3H1078a1,1,0,0,1,0-2h3.07a8.258,8.258,0,0,1-.07-1h-3a1,1,0,0,1,0-2h3.26a8.361,8.361,0,0,1,.33-1H1078a1,1,0,0,1,0-2h4.76a8.236,8.236,0,0,1,.96-1H1078a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1c0,0.03-.01.05-0.01,0.07A7.994,7.994,0,0,1,1097,1314Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,1089,1308Zm0.75,10.67a0.89,0.89,0,0,1-.35.23,1.028,1.028,0,0,1-1.17-.2l-2.93-2.93a1.032,1.032,0,0,1,0-1.47,1.02,1.02,0,0,1,1.46,0l1.24,1.23V1310a1,1,0,0,1,2,0v5.51l1.21-1.22a1.038,1.038,0,0,1,1.46,0,1.02,1.02,0,0,1,0,1.46ZM1092,1305h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1305Zm0-3h-14a1,1,0,0,1,0-2h14A1,1,0,0,1,1092,1302Z"
                                      transform="translate(-1077 -1300)"/>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="show-btn show-btn-up" width="20" height="22"
                                 viewBox="0 0 20 22">
                                <path id="roll-up" fill="#3ec4f0" full-rule="evenodd"
                                      d="M575,1501a8,8,0,0,1-15.413,3H556a1,1,0,0,1,0-2h3.069a8.06,8.06,0,0,1-.069-1h-3a1,1,0,0,1,0-2h3.262a7.837,7.837,0,0,1,.325-1H556a1,1,0,0,1,0-2h4.76a8.4,8.4,0,0,1,.961-1H556a1,1,0,0,1,0-2h14a1,1,0,0,1,1,1,0.43,0.43,0,0,1-.014.07A7.983,7.983,0,0,1,575,1501Zm-8-6a6,6,0,1,0,6,6A6,6,0,0,0,567,1495Zm2.238,5.71-1.219-1.22V1505a1,1,0,0,1-2.007,0v-5.53l-1.24,1.23a1.041,1.041,0,1,1-1.475-1.47l2.951-2.93a1.03,1.03,0,0,1,1.173-.2,0.953,0.953,0,0,1,.353.23l2.93,2.92a1.029,1.029,0,0,1,0,1.46A1.045,1.045,0,0,1,569.238,1500.71ZM570,1492H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1492Zm0-3H556a1,1,0,0,1,0-2h14A1,1,0,0,1,570,1489Z"
                                      transform="translate(-555 -1487)"/>
                            </svg>
                        </td>
                        <td class="project-table-body-td project-table-body-td--term">
                            {{ $complex->term }} дн.
                        </td>
                        <td class="project-table-body-td project-table-body-td--category">
                            Комплексы
                        </td>
                        <td class="project-table-body-td project-table-body-td-cost">

                            @if($complex->discount == 0 || $complex->discount == null)
                                @if(isset($complex->price))
                                    <div class="project-table-body-td-cost__new">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</div>
                                @endif
                            @else
                                @if(isset($complex->discount))
                                    <div class="project-table-body-td-cost__new">{{ number_format($complex->discount, 2, ',', '') }}&nbsp;руб.</div>
                                @endif

                                @if(isset($complex->price))
                                    <div class="project-table-body-td-cost__old">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</div>
                                @endif
                            @endif

                            {{--@if($complex->price == 0)--}}
                                {{--<div class="project-table-body-td-cost__new">Бесплатно</div>--}}
                            {{--@else--}}
                                {{--@if(!is_null($complex->discount))--}}
                                    {{--@if($complex->discount == 0)--}}
                                        {{--<div class="project-table-body-td-cost__new">Бесплатно</div>--}}
                                    {{--@else--}}
                                        {{--<div class="project-table-body-td-cost__new">{{ number_format($complex->discount, 2, ',', '') }} руб.</div>--}}
                                    {{--@endif--}}

                                    {{--<div class="project-table-body-td-cost__old">{{ number_format($complex->price, 2, ',', '') }} руб.</div>--}}
                                {{--@else--}}
                                    {{--<div style="text-decoration: none;" class="project-table-body-td-cost__new">{{ number_format($complex->price, 2, ',', '') }} руб.</div>--}}
                                {{--@endif--}}

                            {{--@endif--}}

                        </td>
                        <td class="project-table-body-td project-table-body-td--to-basket"
                        data-analyse-id="{{ $complex->id }}"
                        data-is-complex="1">
                        <div class="to-basket-btn">
                            <div class="added-text">Добавлено в корзину</div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="22" viewBox="0 0 27 22"><path id="cart" fill="#3ec4f0" fill-rule="evenodd" d="M640,6638H619a1,1,0,0,1,0-2h21A1,1,0,0,1,640,6638Zm-4,10a1,1,0,0,1,0,2H620c-0.019,0-.034-0.01-0.053-0.01a0.714,0.714,0,0,1-.777-0.38l-0.061-.18a0.818,0.818,0,0,1-.05-0.14l-5.031-14.22a0.874,0.874,0,0,1,.665-0.98,0.846,0.846,0,0,1,1.131.3l4.814,13.61H636Zm-12.5,3a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,623.5,6651Zm9,0a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,632.5,6651Zm-10.5-4a1,1,0,0,1,0-2h15a1,1,0,0,1,0,2H622Zm-1-3a1,1,0,0,1,0-2h17a1,1,0,0,1,0,2H621Zm-1-3a1,1,0,0,1,0-2h19a1,1,0,0,1,0,2H620Z" transform="translate(-614 -6634)"/></svg>
                            <div class="added-text added-text--on-hover">Добавить в корзину</div>
                        </div>
                        </td>
                    </tr>
                    {{-- Здесь список анализов --}}
                    @foreach($complex->rAnalyses()->whereIsActive(true)->get() as $analyse)
                        <tr class="explain-tr">
                            <td class="project-table-body-td project-table-body-td--code"
                                data-analyse-id="{{ $analyse->id }}">
                                {{ $analyse->code }}
                            </td>
                            <td class="project-table-body-td project-table-body-td--name">
                                <a href="{{ route('analyses.show', ['slug' => $analyse->slug]) }}">
                                    {{ $analyse->title }}
                                </a>
                            </td>
                            <td class="project-table-body-td project-table-body-td--show-btn"></td>
                            <td class="project-table-body-td project-table-body-td--term">
                                {{ $analyse->term }} дн.
                            </td>
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
                @endforeach
                </tbody>
            </table>
            <div class="project-table-mobile">
                @foreach($complexes as $complexesIndex => $complex)
                <div class="project-table-mobile-letter">{{ $complex->first_letter }}</div>
                <div class="project-table-mobile-tr project-table-mobile-tr-complecs"
                    data-explain-id="1{{ $complex->id }}"
                    data-complecsses="{{ $complex->rAnalyses()->whereIsActive(true)->count() }}">
                    <div class="project-table-mobile-code">Код: <span>{{ $complex->code }}</span></div>
                    <div class="project-table-mobile-content">
                        <div class="project-table-mobile-content-text">
                            <div class="project-table-mobile-content-td project-table-mobile-content-td--name">
                                <a href="{{ route('analyseComplexes.show', ['slug' => $complex->slug]) }}">
                                    {{ $complex->title }}
                                </a>
                                <div class="project-table-body-td--name-qtt-tests">
                                    ({{ $complex->rAnalyses()->whereIsActive(true)->count() }} {{ plural($complex->rAnalyses()->whereIsActive(true)->count(), 'тест', 'теста', 'тестов') }})
                                </div>
                            </div>
                            <div class="project-table-mobile-content-td project-table-mobile-content-td--category">Категория: <span>Комплексы</span></div>
                            <div class="project-table-mobile-content-td project-table-mobile-content-td--term">Срок: {{ $complex->term }} дн.</div>

                            @if($complex->discount == 0 || $complex->discount == null)
                                @if(isset($complex->price))
                                    <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</div>
                                @endif
                            @else
                                @if(isset($complex->discount))
                                    <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($complex->discount, 2, ',', '') }}&nbsp;руб.</div>
                                @endif

                                @if(isset($complex->price))
                                    <div class="project-table-mobile-content-td project-table-mobile-content-td-cost__old">{{ number_format($complex->price, 2, ',', '') }}&nbsp;руб.</div>
                                @endif
                            @endif

                            {{--@if($complex->price == 0)--}}
                                {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">Бесплатно</div>--}}
                            {{--@else--}}
                                {{--@if(!is_null($complex->discount))--}}
                                    {{--@if($complex->discount == 0)--}}
                                        {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">Бесплатно</div>--}}
                                    {{--@else--}}
                                        {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($complex->discount, 2, ',', '') }} руб.</div>--}}
                                    {{--@endif--}}

                                    {{--<div class="project-table-mobile-content-td project-table-mobile-content-td-cost__old">{{ number_format($complex->price, 2, ',', '') }} руб.</div>--}}
                                {{--@else--}}
                                    {{--<div style="text-decoration: none;" class="project-table-mobile-content-td project-table-mobile-content-td-cost__new">{{ number_format($complex->price, 2, ',', '') }} руб.</div>--}}
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
                            data-analyse-id="{{ $complex->id }}"
                            data-is-complex="1">
                                <div class="project-icon-btn add-basket">
                                    <div class="added-text">Добавлено в корзину</div>
                                    <svg class="to-basket-btn" xmlns="http://www.w3.org/2000/svg" width="27"
                                         height="22" viewBox="0 0 27 22">
                                        <path id="cart" fill="#3ec4f0" fill-rule="evenodd"
                                              d="M640,6638H619a1,1,0,0,1,0-2h21A1,1,0,0,1,640,6638Zm-4,10a1,1,0,0,1,0,2H620c-0.019,0-.034-0.01-0.053-0.01a0.714,0.714,0,0,1-.777-0.38l-0.061-.18a0.818,0.818,0,0,1-.05-0.14l-5.031-14.22a0.874,0.874,0,0,1,.665-0.98,0.846,0.846,0,0,1,1.131.3l4.814,13.61H636Zm-12.5,3a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,623.5,6651Zm9,0a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,632.5,6651Zm-10.5-4a1,1,0,0,1,0-2h15a1,1,0,0,1,0,2H622Zm-1-3a1,1,0,0,1,0-2h17a1,1,0,0,1,0,2H621Zm-1-3a1,1,0,0,1,0-2h19a1,1,0,0,1,0,2H620Z"
                                              transform="translate(-614 -6634)"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Здесь список анализов --}}
                    @foreach($complex->rAnalyses()->whereIsActive(true)->get() as $analyse)
                    <div class="project-table-mobile-tr project-table-mobile-tr-explain" data-complecsses="{{ $complex->rAnalyses()->whereIsActive(true)->count() }}">
                        <div class="project-table-mobile-code">Код: <span>{{ $analyse->code }}</span></div>
                        <div class="project-table-mobile-content">
                            <div class="project-table-mobile-content-text">
                                <div class="project-table-mobile-content-td project-table-mobile-content-td--name">
                                    <a href="{{ route('analyses.show', ['slug' => $analyse->slug]) }}">
                                        {{ $analyse->title }}
                                    </a>
                                </div>
                                <div class="project-table-mobile-content-td project-table-mobile-content-td--category"><span>Категория:
{{--                                        {{ $analyse->rAnalysesCategories()->first()->title }}--}}
                                        {{ isset($analyse->rAnalysesCategories->parent)
                                             ? $analyse->rAnalysesCategories->parent->title
                                             : $analyse->rAnalysesCategories->title
                                         }}
                                    </span></div>
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
                            <div class="project-table-mobile-content-btn-td project-table-mobile-content-btn-td-to-basket"
                                data-analyse-id="{{ $analyse->id }}">
                                <div class="project-icon-btn add-basket">
                                    <div class="added-text">Добавлено в корзину</div>
                                    <svg class="to-basket-btn" xmlns="http://www.w3.org/2000/svg" width="27"
                                         height="22" viewBox="0 0 27 22">
                                        <path id="cart" fill="#3ec4f0" fill-rule="evenodd"
                                              d="M640,6638H619a1,1,0,0,1,0-2h21A1,1,0,0,1,640,6638Zm-4,10a1,1,0,0,1,0,2H620c-0.019,0-.034-0.01-0.053-0.01a0.714,0.714,0,0,1-.777-0.38l-0.061-.18a0.818,0.818,0,0,1-.05-0.14l-5.031-14.22a0.874,0.874,0,0,1,.665-0.98,0.846,0.846,0,0,1,1.131.3l4.814,13.61H636Zm-12.5,3a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,623.5,6651Zm9,0a2.5,2.5,0,1,1-2.5,2.5A2.5,2.5,0,0,1,632.5,6651Zm-10.5-4a1,1,0,0,1,0-2h15a1,1,0,0,1,0,2H622Zm-1-3a1,1,0,0,1,0-2h17a1,1,0,0,1,0,2H621Zm-1-3a1,1,0,0,1,0-2h19a1,1,0,0,1,0,2H620Z"
                                              transform="translate(-614 -6634)"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
