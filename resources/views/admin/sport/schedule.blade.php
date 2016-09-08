@extends('common.schedule')

@section('tbody')
    <tbody>
    @for($i = 9; $i < 22; $i+=0.25)
        <tr>
            @if(!fmod($i,1))
                <th scope="row" rowspan="4">
                    <time>
                        {{$i}}:00
                    </time>
                </th>
            @endif
            @for($j = 0; $j < 7; $j++)
                @if(isset($workouts[$j][number_format($i,2)]))
                    @if($workouts[$j][number_format($i,2)] != '0')
                        <td rowspan="4" class="non-active">
                            <cell>
                                <div class="cell-time-interval">
                                    {{$workouts[$j][number_format($i,2)]['starttime'] . ' - ' . $workouts[$j][number_format($i,2)]['endtime']}}<br>
                                </div>
                                <div class="cell-workout-name">
                                    {{$workouts[$j][number_format($i,2)]['name']}}
                                </div>
                                <div class="cell-coach">
                                    {{$workouts[$j][number_format($i,2)]['coachName']}}
                                </div>
                                <div class="cell-coach-replaced-container">
                                    @if($workouts[$j][number_format($i,2)]['isCoachReplaced'] )
                                        <span>&#8226;</span>Тренер заменен
                                    @endif
                                </div>
                                <button class="btn btn-default enter-btn" data-toggle="modal" data-target="#modal-edit-entry"
                                        data-name = "{{$workouts[$j][number_format($i,2)]['name']}}"
                                        data-date="{{$workouts[$j][number_format($i,2)]['date']}}"
                                        data-startevent = "{{$workouts[$j][number_format($i,2)]['startevent']}}"
                                        data-start-time="{{$workouts[$j][number_format($i,2)]['starttime']}}"
                                        data-end-time="{{$workouts[$j][number_format($i,2)]['endtime']}}"
                                        data-id="{{$workouts[$j][number_format($i,2)]['id']}}"
                                        data-coachname="{{$workouts[$j][number_format($i,2)]['coachName']}}"
                                        data-coachid="{{$workouts[$j][number_format($i,2)]['coachid']}}"
                                        data-clientscapacity = "{{$workouts[$j][number_format($i,2)]['clientscapacity']}}"
                                        data-freeplaces = "{{$workouts[$j][number_format($i,2)]['freeplaces']}}"
                                        data-canEnroll="false"
                                        data-entry-type="edit">
                                    Редактировать
                                </button>
                            </cell>
                        </td>
                    @endif
                @else
                    <td @if(fmod($i,1))style="border-top: 0"@endif>
                        <input type="hidden" value="1997-12-12 {{$i}}">
                    </td>
                @endif
            @endfor
        </tr>
    @endfor

    </tbody>
@endsection

@section('modal')

@include('admin.sport.createEntry')
@include('admin.sport.editEntryModal')

@endsection

@section('bottomjs-extend')
    <script src="/js/input-dropdown.js"></script>
    <script src="/js/admin/schedule-admin.js"></script>
@endsection