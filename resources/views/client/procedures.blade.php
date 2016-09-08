@extends('client.app')

@section('headTitle')
    Мои Процедуры
@endsection

@section('headExtra')
    <link rel="stylesheet" href="/css/colcell.css">
    <link rel="stylesheet" href="/css/individuals/procedures.css">
@endsection

@section('content')

<div class="col-sm-12 text-center">

    <div class="nav-pills-zl">
        <a href="/procedures/past" class="{{$isFuture?'':'active'}}">
            Прошедшие
        </a>
        <a href="/procedures" class="{{$isFuture?'active':''}}">
            Будущие
        </a>
    </div>

</div>

<div id="procedures-container" class="col-xs-12 procedures-container">
</div>
<div class="col-xs-12 col-sm-offset-1 col-sm-10">
    <div id="procedures-loading" class="col-xs-12 text-center" style="">
        <img src="/images/loading_spinner.gif" style="max-height: 60px;">
    </div>
</div>


@endsection

@section('bottomjs')
    <script src="/js/procedures.js"></script>
@endsection