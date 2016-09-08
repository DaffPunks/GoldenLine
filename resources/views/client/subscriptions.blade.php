@extends('client.app')

@section('headTitle')
    Спорт Расписание
@endsection

@section('headExtra')
    <link rel="stylesheet" href="/css/individuals/subscriptions.css">
    <link rel="stylesheet" href="/css/table.css">
@endsection

@section('content')

    <div class="container-fluid">

        <div class="row">

            <div class="col-xs-12 text-center">

                <div class="nav-pills-zl">

                    <a href="#" class="active visible-xs col-xs-6">
                        Абонементы
                    </a>

                    <a href="#" class="active hidden-xs">
                        Мои абонементы
                    </a>

                    <a href="/sport/big" class="visible-xs col-xs-5">
                        Расписание
                    </a>

                    <a href="/sport/big" class="hidden-xs">
                        Расписание тренировок
                    </a>
                </div>

            </div>

            <div class="col-xs-12">

                @foreach($subscriptions as $subscription)

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 cell-container text-center" style="margin-bottom: 30px;">
                    <div class="col-cell subscription" style="display: block; max-width: 350px; margin: auto; padding-top: 0.9px;">
                        <div class="header">
                            <div class="name" style="padding-top: 10px;">
                                {{$subscription['name']}}
                            </div>
                        </div>
                        <div class="content row" style="margin-top: 25px;">
                            <div class="status">{{$subscription['leftVisits'] > 0 ? 'Осталось посещений: ' . $subscription['leftVisits'] : 'Окончен'}}</div>
                            <div class="interval col-sm-8">{{$subscription['startDay'] .' ' . $subscription['startMonth'] . ' - ' . $subscription['endDay'] . ' ' . $subscription['endMonth'] .' ' . $subscription['year']}}</div>
                            <div class="num col-sm-4">{{$subscription['number'] != ''? "№ " . $subscription['number'] : ''}}</div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if(count($subscriptions) == 0)
                    <div class="col-xs-12 text-center" style="margin-top: 20vh">
                        <h4>
                            Нет Абонементов
                        </h4>
                    </div>
                @endif

                {{--<div class="col-sm-4 col-md-3 col-lg-3 cell-container">--}}
                    {{--<button class="col-sm-12 col-cell subscription-add">--}}

                        {{--<b>--}}
                            {{--Купить абонемент--}}
                        {{--</b>--}}

                    {{--</button>--}}
                {{--</div>--}}

            </div>


        </div>
    </div>

@endsection
@section('bottomjs')
@endsection