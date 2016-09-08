<!DOCTYPE html>
<html lang="ru">
	@include('head')
<body>

<div class="container-fluid">

	<div class="row">

		<!--    Navbar begin-->
		<div class="col-sm-12 navbar-zl with-shadow color-font-darkgray">

			<div class="row">
				<div class="col-xs-4 col-sm-offset-1 col-sm-3">
					<a href="/">
						<img id="logo" style="max-width: 100%" src="/images/logo_new_wo_slogan.png" alt="Косметология Кемерово">
					</a>
				</div>
				<div class="col-xs-4 col-sm-4 address-container">
					<p>ул. Д.Бедного, 11 <span>+7&nbsp;(3842)&nbsp;452-452 </span></p>
					<!-- <p>СПОРТ <span>+7&nbsp;(3842)&nbsp;452-402 </span></p> -->
					<p>пр. Ленинградский, 30/1 <span>+7&nbsp;(3842)&nbsp;452-035 </span></p>
				</div>
				<div class="col-xs-4 col-sm-4">

					<!-- <div class="col-xs-12 dropdown">
						<a href="#" class="color-font-darkgray pull-right dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<div class="col-sm-2 col-lg-3">
								<img src="/images/profile_icon.png" style="max-width: 60px;" alt="profile">
							</div>
							<div class="col-sm-9 col-lg-9">
								<div style="position:relative; left:10px; height: 22px; top:19px; font-size: 22px;">
									{{$username}} <span class="caret"></span>
								</div>
							</div>
						</a>
						<ul class="dropdown-menu" aria-labelledby="drop1" style="float: right; top:50px; right: 0; left: auto;">
							<li>
								<a href="" data-toggle="modal" data-target="#changePassword">Сменить пароль</a>
							</li>
							<li role="separator" class="divider"></li>
							<li>
								<a href="/logout">Выйти</a>
							</li>
						</ul>
					</div> -->
					@include('navbar/dropdownMenu')
					<div class="col-xs-11 pull-right" style="position:relative;top:50px;">
						<button class="btn btn-default pull-right call-btn">
							<span class="glyphicon glyphicon-earphone"></span> Перезвоните Мне
						</button>
					</div>
				</div>
			</div>
		</div>
		<!--    navbar end-->
		<!--      shadow-->

		<div class="col-sm-12 status-panel color-font-darkgray">
			<div class="col-sm-offset-1 col-sm-10 col-lg-offset-2 col-lg-8 status-container">

				<div class="col-xs-offset-1 col-xs-2  col-sm-offset-0 col-sm-4">
					<div class="status-label text-left">
                    <span class='glyphicon glyphicon-credit-card card'></span>
						<div class="name">
							Остаток :
						</div>
						<div class="num">
							{{$deposit}}
						</div>
						<span class="glyphicon glyphicon-rub rub"></span>
					</div>
				</div>
				<div class="col-xs-offset-1 col-xs-2  col-sm-offset-0 col-sm-4">
					<div class="status-label text-center">
                  <span class='glyphicon glyphicon-credit-card card'></span>
						<div class="name">
							Скидка:
						</div>
						<div class="num">
							NUM%
						</div>
					</div>
				</div>
				<div class="col-xs-offset-1 col-xs-2  col-sm-offset-0 col-sm-4">
					<div class="status-label text-right">
						<div class="name">
							Бонусных баллов:
						</div>
						<div class="num">
							{{$bonus}}
						</div>
					</div>
				</div>

				@include('client.globalnavigation')

			</div>
		</div>

		@yield('content')

	</div>
</div>

@include('default.changePassword')

@include('common.js')
@yield('bottomjs')

</body>

</html>