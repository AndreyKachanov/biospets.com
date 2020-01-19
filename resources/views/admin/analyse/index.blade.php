@extends('admin.layouts.dashboard')

@section('page_heading', 'Анализы')
@section('button')
    <a style="margin-top: 50px; margin-left: 50px" href="{{ route('admin.analyses.create') }}" class="btn btn-primary"
       id="createPromotion"> Добавить анализ</a>
@endsection

@section('section')
    @if(session()->has('successMessage'))
        <div class="alert alert-success">
            {{ session()->get('successMessage') }}
        </div>
    @endif
    @if(session()->has('errors'))
        <div class="alert alert-danger">
            {{ session()->get('errors') }}
        </div>
    @endif
    <div class="row" style="margin-top: 10px">
        <div class="col-sm-12" id="page-bottom">
            <table class="analyses-table table-striped table-bordered table-hover table-without-icons"
                   id="users-table" style="width: 100%; min-width: 1024px;">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Код</th>
                    <th>Статус</th>
                    <th>Название анализа</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Цена со скидкой</th>
                    <th>Действия</th>
                </tr>
                </thead>
            </table>

        </div>
    </div>


    <!-- /.row -->
@stop

@push('scripts')
    <script>
        $.extend($.fn.dataTableExt.oSort, {
            "numeric-comma-pre": function (a) {
                var x = (a == "-") ? 0 : a.replace(/,/, ".");
                return parseFloat(x);
            },

            "numeric-comma-asc": function (a, b) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },

            "numeric-comma-desc": function (a, b) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        });

        $(function () {
            $(document).on('click', 'a#logout', function (e) {
                localStorage.clear();
            });

            var t = $('#users-table').DataTable({
                processing: true,
                pagingType: 'numbers',
                pageLength: 25,
                ajax: '{!!route('analysesGetData') !!}',
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false },
                    { data: 'code', name: 'code', orderable: true, searchable: true },
                    { data: 'is_active', orderable: true, searchable: true },
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'category', name: 'category', orderable: true, searchable: true },
                    { data: 'price', name: 'price', orderable: true, searchable: true },
                    { data: 'discount', name: 'discount', orderable: true, searchable: true },
                    { data: 'action', name: 'action', orderable: false, searchable: false },

                ],
                order: [],
                language: {
                    "sProcessing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    "sLengthMenu": "Показать _MENU_ записей",
                    "sZeroRecords": "Записи отсутствуют.",
                    "sInfo": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "sInfoEmpty": "Записи с 0 до 0 из 0 записей",
                    "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
                    "sInfoPostFix": "",
                    "sSearch": "Поиск:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Первая",
                        "sPrevious": "Предыдущая",
                        "sNext": "Следующая",
                        "sLast": "Последняя"
                    },
                    "oAria": {
                        "sSortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sSortDescending": ": активировать для сортировки столбцов по убыванию"
                    }
                },
                fnInitComplete: function (oSettings){
                    var table = $('#users-table').DataTable();
                    var currentPage = localStorage.getItem('currentPage');
                    if (currentPage != null) {
                        table.page(parseInt(currentPage)).draw('page');
                        localStorage.clear();
                    }

                },
            });

            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
            $('.alert-success').delay(3000).hide('slow');
            $('.alert-danger').delay(3000).hide('slow');

        });

        $(document).on("submit", "form", function (e) {
            var table = $('#users-table').DataTable();
            var answer = confirm('Вы действительно хотите удалить анализ?');
            localStorage.setItem('currentPage', table.page.info().page);
            if (answer == false) {
                return false;
            }
        });

    </script>
@endpush

<style>
    td, th {
        font-size: 14px;
    }
    th {
        font-weight: bold;
    }

    .btn-sm {
        padding: 8px 10px !important;
    }
    .fa-spinner {
        position: relative;
        right: 80px;
        bottom: 3px;
    }
</style>
