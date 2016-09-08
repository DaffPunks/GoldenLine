@extends('common.schedule')

@section('tbody')

    <tbody>
    @for($i = 9; $i < 22; $i+=0.25)
        <tr style="width:100%">
            @if(!fmod($i,1))
                <th scope="row" style="width:12.5%" rowspan="4">
                    <time>
                        {{$i}}:00
                    </time>
                </th>
            @endif
            @for($j = 0; $j < 7; $j++)
                @if(isset($workouts[$j][number_format($i,2)]))
                    @if($workouts[$j][number_format($i,2)] != '0')
                        <td rowspan="4" class="{{$workouts[$j][number_format($i,2)]['hasEntry'] ? 'active' : 'non-active'}}">
                            <cell style="{{ $workouts[$j][number_format($i,2)]['canEnter'] || $workouts[$j][number_format($i,2)]['hasEntry'] ? '' : 'padding-top: 35px' }}">
                                <input type="hidden" name="id" value="{{$workouts[$j][number_format($i,2)]['id']}}">
                                <input type="hidden" name="freePlace" value="{{$workouts[$j][number_format($i,2)]['placesCount']}}">
                                @if($workouts[$j][number_format($i,2)]['hasSubscriptionEntry'])
                                    <div class="cell-has-subscription-entry">
                                            Вы записаны
                                    </div>
                                @endif
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
                                @if($workouts[$j][number_format($i,2)]['canEnter'])
                                    @if($workouts[$j][number_format($i,2)]['hasEntry'] && !$workouts[$j][number_format($i,2)]['hasSubscriptionEntry'])
                                        <button class="btn btn-default quit-btn" onclick="openEntryModal($(this), true)">
                                            Вы записаны
                                        </button>
                                    @elseif(!$workouts[$j][number_format($i,2)]['hasEntry'])
                                        <button class="btn btn-default enter-btn" onclick="openEntryModal($(this), false)">
                                            Записаться
                                        </button>
                                    @endif
                                @endif
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

        @include('client.enterWorkoutModal')

@endsection


@section('bottomjs-extend')
    <script src="/js/schedule-client.js"></script>
@endsection