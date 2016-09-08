
@if($adminRole == 1)
    <div class="btn-group" role="group">
        <a href="/admin/clients" type="button" class="btn btn-default {{$tab == 'clients' ? 'active' : ''}}">Клиенты</a>
    </div>
@endif
@if($adminRole == 3)
    <div class="btn-group" role="group">
        <a href="/admin/coaches" type="button" class="btn btn-default {{$tab == 'coaches' ? 'active' : ''}}">Тренера</a>
    </div>
@endif
@if($adminRole == 2 || $adminRole == 3)
    <div class="btn-group" role="group">
        <a href="/sport/big" type="button" class="btn btn-default {{$tab == 'sport' ? 'active' : ''}}">Расписание</a>
    </div>
@endif
@if($adminRole == 1)
    <div class="btn-group" role="group">
        <a href="/admin/specialists" type="button" class="btn btn-default {{$tab == 'specialists' ? 'active' : ''}}">Специалисты</a>
    </div>
@endif
