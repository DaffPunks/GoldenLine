@extends('admin.app')

@section('content')


    <div class="col-sm-3"></div>

    <div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
        <form id="form-coaches-search" autocomplete="off">
            <div class="form-group col-xs-10">
                <input class="form-control" placeholder="Поиск" name="search" type="text">
            </div>
            <div class="col-xs-2">
                <button id="btn-coaches-search" type="button" class="btn btn-default modal-btn" style="width: auto;">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </div>
        </form>
    </div>

    <div class="col-sm-4 pull-right">
        <button class="btn btn-default" style="font-size: 15px;" data-toggle="modal" data-target="#add-coach-modal"><span class="glyphicon glyphicon-plus"></span> Добавить Тренера</button>
    </div>

    <div class="col-xs-12 col-sm-offset-1 col-sm-10">

        <table id="coachesTable" class="table sortable">
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
                        Процент
                    </button>
                </th>
                <th>
                    <button class="btn-no-bg">
                        Базовая ЗП
                    </button>
                </th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        @include('admin.coaches.addCoach')
        @include('admin.coaches.editCoach')

        @include('admin.deletePerson')
    </div>
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div id="coaches-loading" class="col-xs-12 text-center" style="">
            <img src="/images/loading_spinner.gif" style="max-height: 60px;">
        </div>
    </div>
@endsection

@section('bottomjs')
    <script src="/js/admin/coachsearch.js"></script>
    <script src="/js/admin/coachedit.js"></script>
@endsection