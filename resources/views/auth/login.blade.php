<!DOCTYPE html>
<html lang="en">
<head>
    <title>Золотая Линия Личный Кабинет</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/common.css">

    <link rel="stylesheet" href="/css/individuals/login.css">
</head>
<body style="background: #FAF7E8 url(/images/back.png);">

<div class="container-fluid">

    <div class="row">

        <!--    Navbar begin-->
        <div class="col-sm-12 navbar-zl with-shadow color-font-darkgray">

            <div class="row">
                <div class="hidden-xs col-sm-offset-1 col-sm-3">
                    <a href="/">
                        <img id="logo" style="max-width: 100%" src="/images/logo_new_wo_slogan.png" alt="Косметология Кемерово">
                    </a>
                </div>

                <div class="visible-xs col-xs-1">
                    <a href="/">
                        <img id="logo" style="max-width: 50px; padding: 5px;" src="/images/logo_small.png" alt="Косметология Кемерово">
                    </a>
                </div>

                <div class="visible-xs col-xs-10 text-center" style="font-size: 20px; padding-top: 10px;">
                    Личный Кабинет
                </div>

                <div class="hidden-xs col-sm-4 address-container">
                    <p>ул. Д.Бедного, 11 <span class="phone">+7&nbsp;(3842)&nbsp;452-452 </span></p>
                    <!-- <p>СПОРТ <span>+7&nbsp;(3842)&nbsp;452-402 </span></p> -->
                    <p>пр. Ленинградский, 30/1 <span class="phone">+7&nbsp;(3842)&nbsp;452-035 </span></p>
                </div>

                <div class="hidden-xs col-sm-4">
                    <a class="link-to-main-site" target="_blank" href="http://zl42.ru">Основной сайт zl42.ru</a>
                </div>
            </div>
        </div>
        <!--    navbar end-->
        <!--      shadow-->
        <div class="col-xs-12" style="margin-top: 100px;">

            <div class="col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6">
                <div class="login-panel color-font-darkgray row">

                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h2>
                                Вход
                            </h2>
                        </div>
                    </div>

                    <form id="loginform" class="loginform" role="form" method="POST" action="/auth/login">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12 col-sm-6">

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <input id="logintel" type="tel" name="cellphone" class="form-control" placeholder="Номер телефона">
                                </div>
                                <div class="form-group">
                                    <input id="loginpass" type="password" name="password" class="form-control" placeholder="Пароль">
                                </div>
                                <input type="checkbox" name="remember" checked style="display: none">
                                <!--<div class="checkbox">-->
                                <!--<label>-->
                                <!--<input name="autolog" type="checkbox"> Log in automatically-->
                                <!--</label>-->
                                <!--</div>-->
                                <div id="loginerror" style="display:none;">
                                    <br>
                                    <div class="form-group bg-danger" style="border-radius: 5px; color: #DCALLB;">
                                        <p id="loginerrorfield" class="text-center"></p>
                                    </div>
                                    <br>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                @if (count($errors))
                                        @foreach($errors->all() as $error)
                                            @if($error == 'These credentials do not match our records.')
                                                <div class="error" role="alert">{{'Неправильный номер или пароль'}}</div>
                                            @endif
                                        @endforeach
                                @endif
                            </div>

                            <div class="col-xs-12">
                                <button class="btn btn-default">
                                    Войти
                                </button>
                            </div>

                        </div>
                        <div class="hidden-xs col-sm-6">
                            <div class="description">
                                <h4>
                                    Как войти в личный кабинет?
                                </h4>
                                <p>
                                    Введите номер телефона и пароль,
                                    который выдал вам наш администратор
                                    или который вам дали на ресепшене
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

</div>

<script src="/js/jquery/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

</body>

</html>
