@extends('admin.app')

@section('content')

    @include('admin.clients.genPassword')

    <div class="col-xs-offset-1 col-xs-10 col-sm-offset-3 col-sm-6">
        <form id="form-clients-search" autocomplete="off">
            <div class="form-group col-xs-10">
                <input class="form-control" placeholder="Поиск" name="search" type="text">
            </div>
            <div class="col-xs-2">
                <button id="btn-clients-search" type="button" class="btn btn-default modal-btn" style="width: auto;">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </div>
        </form>
    </div>

    <div class="col-xs-12 col-sm-offset-1 col-sm-10">

        <table id="clientsTable" class="table sortable">
            <thead>
            <tr>
                <th>
                    <button class="btn-no-bg">
                        ID
                    </button>
                </th>
                <th>
                    <button class="btn-no-bg">
                        ФИО
                    </button>
                </th>
                <th>
                    <button class="btn-no-bg">
                        Телефон
                    </button>
                </th>
                <th>
                    <button class="btn-no-bg">
                        Последняя Операция
                    </button>
                </th>
                <th>
                    <button class="btn-no-bg">
                        Статус
                    </button>
                </th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div id="clients-loading" class="col-xs-12 text-center" style="">
            <img src="/images/loading_spinner.gif" style="max-height: 60px;">
        </div>
    </div>

    @include('admin.deletePerson')
@endsection

@section('bottomjs')
    <script src="/js/admin/clientssearch.js"></script>
    <script src="/js/admin/clientsedit.js"></script>
@endsection