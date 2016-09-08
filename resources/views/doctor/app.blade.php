<!DOCTYPE html>
<html lang="ru">
@include('common.head')
<body>
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="container-fluid">

	<div class="row">

		<!--    Navbar begin-->
		<div class="col-sm-12 navbar-zl with-shadow color-font-darkgray navbar-zl-admin">

			<div class="row">

				<div class="hidden-xs col-sm-offset-1 col-sm-3">
					<a href="/">
						<img id="logo" style="max-height: 60px" src="/images/logo_new_wo_slogan.png" alt="Косметология Кемерово">
					</a>
				</div>

				<div class="visible-xs col-xs-4">
					<a href="/">
						<img id="logo" style="max-width: 50px; padding: 5px;" src="/images/logo_small.png" alt="Косметология Кемерово">
					</a>
				</div>
				<div class="col-xs-8 col-sm-8 col-lg-8">
					@include('doctor.dropdownmenu')

				</div>
			</div>
		</div>
		<!--    navbar end-->
		<!--      shadow-->

		@yield('content')

	</div>
</div>

@include('default.changePassword')

@include('common.js')
@yield('bottomjs')
</body>

</html>