@extends($userRole == 'client' || $userRole == 'doctorAsClient' ? 'client.app' : 'admin.app')

@section('headTitle')
    Спорт Расписание
@endsection

@section('headExtra')
    <link rel="stylesheet" href="/css/individuals/sportschedule.css">

    <link rel="stylesheet" href="/css/datetimepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/css/datetimepicker/bootstrapValidator.min.css">
    <link rel="stylesheet" href="/css/datetimepicker/custom-datetimepicker.css">

    @yield('headExtra2')
@endsection

@section('content')

    <div class="container-fluid">

        <div class="row">

            <div class="col-xs-12 text-center">

                <div class="nav-pills-zl">

                    @if($userRole == 'client' || $userRole == 'doctorAsClient')

                        <a href="/sport/subscriptions" class="visible-xs col-xs-6">
                            Абонементы
                        </a>

                        <a href="/sport/subscriptions" class="hidden-xs">
                            Мои абонементы
                        </a>

                        <a href="#" class="active visible-xs col-xs-5">
                            Расписание
                        </a>

                        <a href="#" class="active hidden-xs">
                            Расписание тренировок
                        </a>
                    @endif

                    <div class="btn-group dropdown schedule-gym-dropdown" style="margin-top: -3px;">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{$gymnames[$gymtype]}} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @if($gymtype != 'small')
                                <li><a href="/sport/small">{{$gymnames['small']}}</a></li>
                            @endif
                            @if($gymtype != 'big')
                                <li><a href="/sport/big">{{$gymnames['big']}}</a></li>
                            @endif
                            {{--@if($gymtype != 'fitness')--}}
                                @if(false)
                                <li><a href="/sport/fitness">{{$gymnames['fitness']}}</a></li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>

            {{--<div class="col-sm-4">--}}
            {{--<input id="headerDatePickeratePicker" name="headerDatePickeratePicker" class="form-control" type="text">--}}
            {{--</div>--}}

            <div class="col-xs-12">

                <div class="panel-heading col-sm-6 hidden-xs">
                    Расписание на {{$period['dayNow'] . ' ' . $period['monthNow'] . ' - ' . $period['dayFuture'] . ' ' . $period['monthFuture']}}
                </div>

                @if($userRole == 'admin')
                <div class="col-sm-6" style="text-align: right;">
                    <button class="btn btn-default" style="font-size: 15px;" data-toggle="modal" data-target="#createEntryModal"><span class="glyphicon glyphicon-plus"></span> Добавить Тренировку</button>
                </div>
                @endif
                <div class="table-container">
                <table class="table col-xs-12">
                    @for($i = 0; $i < 2; $i++)
                        <thead {{$i == 1? 'id=schedule-dynamic-thead class=thead-dynamic' : 'id=schedule-static-thead'}} style="{{$i == 1? 'z-index: 100; display : none' : ''}}">
                        <tr style="width:100%">
                            <th style="width:12.5%; padding-top: 0;">
                                @if($i == 0)
                                <div class="form-group" style="display: block; margin: 0; height: 25px; width: 30px; margin-left: auto; margin-right: auto;">
                                    <div id="headerDatePicker" class="input-group date">
                                        <input type='hidden' class="form-control" name="chosenDate"/>
                                    <span class="input-group-addon" style="
                                    margin-top: -5px;
                                    background: none;
                                    text-align: center;
                                    width: 25px;
                                     height: 25px;
                                     padding-left: 13.5px;
                                     color: #66757f;
                                       cursor: pointer;
                                       border: 1.5px solid #CCC !important;
                                       border-radius: 5px;
                                       ">
                                        <span class="glyphicon-calendar glyphicon" style="padding: 0; margin: 0;"></span>
                                    </span>
                                    </div>
                                </div>
                                @endif
                            </th>
                            @foreach($tableHead as $th)
                                <th style="width:12.5%">
                                    <day>{{$th['day']}}</day>
                                    <date>{{$th['date']}}</date>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                    @endfor
                    @yield('tbody')
                </table>
                </div>
            </div>

            @yield('modal')

        </div>
    </div>

@endsection
@section('bottomjs')

    <script src="/js/datepicker/moment-with-locales.js"></script>
    <script src="/js/datepicker/bootstrap-datetimepicker.min.js"></script>

    <script src="/js/schedule.js"></script>
    @yield('bottomjs-extend')
@endsection