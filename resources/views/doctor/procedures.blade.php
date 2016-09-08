@extends('doctor.app')

@section('headTitle')
    Мои Процедуры
@endsection

@section('headExtra')
    <link rel="stylesheet" href="/css/datetimepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/css/datetimepicker/bootstrapValidator.min.css">
    <link rel="stylesheet" href="/css/datetimepicker/custom-datetimepicker.css">

    <link rel="stylesheet" href="/css/colcell.css">
    <link rel="stylesheet" href="/css/individuals/procedures.css">
@endsection

@section('content')

<div class="col-sm-12 text-center">

    <div class="nav-pills-zl">
        {{--<a href="/procedures/past" class="{{$time == 'past' ? 'active' : ''}}">--}}
            {{--Прощедшие--}}
        {{--</a>--}}
        {{--<a href="/procedures" class="{{$time == 'today' ? 'active' : ''}}">--}}
            {{--Сегодня--}}
        {{--</a>--}}
        {{--<a href="/procedures/future" class="{{$time == 'future' ? 'active' : ''}}">--}}
            {{--Будущие--}}
        {{--</a>--}}

        <button id="datePicker" type="button" class="btn dropdown-toggle">
            <input type='hidden' class="form-control" name="chosenDate"/>
            <div id="btnDateValue" style="float: left; display: block"></div>
            <span class="glyphicon-calendar glyphicon" style="padding-left: 10px;"></span>
        </button>
    </div>

</div>

@if(count($procedures) > 0)
    <div class="col-xs-12 procedures-container">

        @foreach ($procedures as $procedure)
            <div class="col-sm-4 col-md-3 cell-container">
                <div class="col-sm-12 col-cell">
                    <div class="header">
                        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-4" style="padding-left: 2px; text-align: center;">
                            <div class="time">
                                {{ $procedure['time'] }}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-7 col-lg-8" style="text-align: center">
                            @if($procedure['day'] != 'Сегодня' || !$isFuture)
                                <h4>
                                    {{ $procedure['day'] }}
                                </h4>
                                <h5>
                                    {{ $procedure['month']}}
                                </h5>
                            @else
                                <h3>
                                    {{ $procedure['day'] }}
                                </h3>
                            @endif
                        </div>
                    </div>
                    <div class="content {{ ($procedure['day'] == 'Завтра' || $procedure['day'] == 'Сегодня') && $isFuture ?'active':'' }}" style="height: 180px;">
                        @if($procedure['manipulationStatusId'] == 1)
                            <span class="col-xs-12 text-center" style="color:#556270; border-radius: 3px; border: 1px solid #ddd;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @elseif($procedure['manipulationStatusId'] == 2)
                            <span class="col-xs-12 text-center" style="background-color: #FE4365; color:#556270; border-radius: 3px;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @elseif($procedure['manipulationStatusId'] == 3)
                            <span class="col-xs-12 text-center" style="background-color: #C7F464; color:#556270; border-radius: 3px;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @elseif($procedure['manipulationStatusId'] == 4)
                            <span class="col-xs-12 text-center" style="background-color: #f9f9f9; color:#556270; border-radius: 3px;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @elseif($procedure['manipulationStatusId'] == 5)
                            <span class="col-xs-12 text-center" style="background-color: #F9F93B; color:#556270; border-radius: 3px;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @elseif($procedure['manipulationStatusId'] == 6)
                            <span class="col-xs-12 text-center" style="background-color: #4ECDC4; color:#556270; border-radius: 3px;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @elseif($procedure['manipulationStatusId'] == 7)
                            <span class="col-xs-12 text-center" style="background-color: #262626; color:#fff; border-radius: 3px;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @elseif($procedure['manipulationStatusId'] == 8)
                            <span class="col-xs-12 text-center" style="background-color: #4ECDC4; color:#556270; border-radius: 3px;">
                                {{ $procedure['manipulationStatus'] }}
                            </span>
                        @endif
                        <p1>
                            {{ $procedure['comment'] }}
                        </p1>
                        <h5>
                            Клиент:<br>
                            {{ $procedure['clientName']}}
                        </h5>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@else
    <div class="col-xs-12 text-center" style="margin-top: 8%;">
        <div style="font-size: 26px;">
            В этот день нет процедур
        </div>
    </div>
@endif

@endsection

@section('bottomjs')
    <script src="/js/datepicker/moment-with-locales.js"></script>
    <script src="/js/datepicker/bootstrap-datetimepicker.min.js"></script>

    <script src="/js/doctor/procedures.js"></script>
@endsection