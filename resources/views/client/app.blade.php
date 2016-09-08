<!DOCTYPE html>
<html lang="ru">
	@include('common.head')
<body>
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="container-fluid">

	<div class="row">

		<!--    Navbar begin-->
		<div class="col-sm-12 navbar-zl with-shadow color-font-darkgray">

			<div class="row">

				<div class="hidden-xs col-sm-4 col-md-offset-1 col-md-3">
					<a href="/">
						<img id="logo" style="max-width: 100%" src="/images/logo_new_wo_slogan.png" alt="Косметология Кемерово">
					</a>
				</div>

				<div class="visible-xs col-xs-1">
					<a href="/">
						<img id="logo" style="max-width: 50px; padding: 5px;" src="/images/logo_small.png" alt="Косметология Кемерово">
					</a>
				</div>

				<div class="hidden-xs col-sm-4 address-container">
					<p>ул. Д.Бедного, 11 <span class="phone">+7&nbsp;(3842)&nbsp;452-452 </span></p>
					<!-- <p>СПОРТ <span>+7&nbsp;(3842)&nbsp;452-402 </span></p> -->
					<p>пр. Ленинградский, 30/1 <span class="phone">+7&nbsp;(3842)&nbsp;452-035 </span></p>
				</div>
				<div class="col-xs-10 col-sm-4">

					@if($userRole == 'doctorAsClient')
						@include('client.dropdownmenu')
					@else
						@include('navbar/dropdownMenu')
					@endif
					<div class="hidden-xs pull-right" style="position:relative;top:50px;">
						<button class="btn btn-default pull-right call-btn" style="font-size: 16px;" data-toggle="modal" data-target="#call-me-modal">
							<span class="glyphicon glyphicon-earphone"></span> Перезвоните Мне
						</button>
					</div>
				</div>
			</div>
		</div>
		<!--    navbar end-->
		<!--      shadow-->

		<div class="col-xs-12 status-panel color-font-darkgray text-center" >
			<div class="status-container">
				<div id="account-info-container" class="hidden-xs" style="display: none">
					<div class="status-label">
						<span class='glyphicon glyphicon-credit-card card'></span>
						<div class="name">
							Остаток:
						</div>
						<div id="deposit" class="num">
						</div>
						<span class="glyphicon glyphicon-rub rub"></span>
					</div>
					<div id="discount-label" class="status-label">
						<span class='glyphicon glyphicon-credit-card card'></span>
						<div class="name">
							Скидка:
						</div>
						<div id="discount" class="num">
						</div>
					</div>
					<div class="status-label">
						<div class="name">
							Бонусных баллов:
						</div>
						<div id="bonus" class="num">
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
@include('client.callMeModal')

@include('common.js')
<script src="/js/client.js"></script>
<script src="/js/callMe.js"></script>
@yield('bottomjs')

</body>

</html>