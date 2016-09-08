<!DOCTYPE html>
<html lang="ru">
@include('common.head')
<body>

<div class="container-fluid">

	<div class="row">
		<!--    Navbar begin-->
		<div class="col-sm-12 navbar-zl with-shadow color-font-darkgray navbar-zl-admin">

			<div class="row">
				<div class="hidden-xs col-sm-offset-1 col-sm-3">
					<a href="/">
						<img id="logo" style="max-width: 100%; max-height: 60px" src="/images/logo_new_wo_slogan.png" alt="Косметология Кемерово">
					</a>
				</div>

				<div class="visible-xs col-xs-1">
					<a href="/">
						<img id="logo" style="max-width: 50px; padding: 5px;" src="/images/logo_small.png" alt="Косметология Кемерово">
					</a>
				</div>
				<div class="hidden-xs col-sm-5 col-lg-4 text-center">
					<div class="btn-group nav-btn-group" role="group">
						@include('admin.globalnavigation')
					</div>
				</div>

				<div class="col-xs-10 col-sm-3 col-lg-4">
					@include('navbar/dropdownMenu')
				</div>
			</div>
		</div>
		<!--    navbar end-->
		<!--      shadow-->

		<div class="col-xs-12 visible-xs status-panel color-font-darkgray">
			<div class="col-xs-12 status-container nopadding">

				<div class="text-center">
					<div class="btn-group nav-btn-group" role="group">
						@include('admin.globalnavigation')
					</div>
				</div>

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