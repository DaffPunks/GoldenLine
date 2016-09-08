<div class="col-xs-12 dropdown">
	<a href="#" class="color-font-darkgray pull-right dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		<div class="dropdown-username">
			<div id="dropdown-username" style="display: inline-block">{{$username}} <span class="caret"></span></div>
		</div>
	</a>
	<ul class="dropdown-menu" aria-labelledby="drop1" style="float: right; top:50px; right: 0; left: auto;">
		<li>
			<a href="" data-toggle="modal" data-target="#changePassword">Сменить пароль</a>
		</li>
		<li role="separator" class="divider"></li>
		<li>
			<a target="_blank" href="http://zl42.ru">ZL42.RU</a>
		</li>
		@yield('dropdownMenuExtra')
		<li role="separator" class="divider"></li>
		<li>
			<a href="/logout">Выйти</a>
		</li>
	</ul>
</div>