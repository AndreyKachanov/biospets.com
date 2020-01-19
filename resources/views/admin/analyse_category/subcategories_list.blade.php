@extends('admin.layouts.dashboard')

@section('page_heading', 'Подкатегории анализов')
@section('button')
    <a style="margin-top: 50px; margin-left: 50px" href="{{ route('admin.analyse.subcategories.create') }}" class="btn btn-primary" id="createPromotion" > Добавить подкатегорию</a>
@endsection

@section('section')
    @if(session()->has('successMessage'))
        <div class="alert alert-success" >
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
            <table class="table table-category table-striped table-bordered table-hover main-table table-without-icons" id="users-table" style="width: 100%; min-width: 1024;">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Статус</th>
                    <th>Название подкатегории</th>
                    <th>Категория</th>
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
        $(function() {
            $(document).on('click', 'a#logout', function(e) {
                localStorage.clear();
            });

            var t = $('#users-table').DataTable({
                processing: true,
                pagingType: 'numbers',
                pageLength: 25,
                ajax: '{!!route('analysesSubCategoriesGetData') !!}',
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false },
                    { data: 'is_active', orderable: true, searchable: true },
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'parent', name: 'parent', orderable: true, searchable: true },
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

            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
            $('.alert-success').delay(3000).hide('slow');
            $('.alert-danger').delay(3000).hide('slow');
        });

        $(document).on("submit", "form", function(e){
            var table = $('#users-table').DataTable();
            var answer = confirm('Вы действительно хотите удалить категорию?');
            localStorage.setItem('currentPage', table.page.info().page);
            if (answer == false) {
                return false;
            }
        });
    </script>
@endpush

<style>

    /*№*/
    .table-category tr td:first-child, .table-category th:first-child {
        width: 25px !important;
        padding: 8px 15px !important;
    }

    /*status*/
    .table-category  tr td:nth-child(2), .table-category  th:nth-child(2) {
        width: 92px !important;
        padding: 8px !important;
    }

    /*column action*/
    .table-category  tr td:last-child, .table-category  th:last-child {
        width: 100px !important;
        padding: 8px !important;
    }

    .fa-spinner {
        position: relative;
        right: 30px;
        bottom: 10px;
    }

    .table-category {
        table-layout: fixed;
    }

    .table-category tr td:nth-child(3) {
        width: 50%;
    }

    .table-category tr td:nth-child(4) {
        width: 25%;
    }

    .table-category .btn-sm {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }

    .table-category td, th{
        font-size: 14px !important;
    }

    .table-category tr td:nth-child(2) {
        width: 100px !important;
    }

    .table-category tr td:nth-child(3) {
        word-break: break-word !important;
    }

    .table-category tr td:nth-child(4) {
        word-break: break-word !important;
    }

    .table-category tr td:last-child {
        min-width: 100px !important;
    }
</style>