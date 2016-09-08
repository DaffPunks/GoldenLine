@extends('navbar.dropdownMenu')

@section('dropdownMenuExtra')
    @if($userRole == 'doctorAsClient')
    <li role="separator" class="divider"></li>
    <li>
        <a href="/procedures/future">ЛК Клиента</a>
    </li>
    @endif
@endsection