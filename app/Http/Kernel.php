<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
//		Prevent TokenMismatch
//		'App\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
		'admin' => 'App\Http\Middleware\Admin',
		'admin_cosmetics' => 'App\Http\Middleware\AdminCosmetics',
		'admin_sport' => 'App\Http\Middleware\AdminSport',
		'admin_coach' => 'App\Http\Middleware\AdminCoach',
		'client' => 'App\Http\Middleware\Client',
		'doctor' => 'App\Http\Middleware\Doctor'
	];

}
