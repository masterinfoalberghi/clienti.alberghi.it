<?php

/**
 * Kernel
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     * @var array
     */
    protected $middleware = [
        \BeyondCode\ServerTiming\Middleware\ServerTimingMiddleware::class,
        'App\Http\Middleware\GetQueryLog',
        'App\Http\Middleware\CheckOldBrowser',
        'App\Http\Middleware\Headers',
        'App\Http\Middleware\NewViewBladeFile',
        'App\Http\Middleware\CheckAttack',
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'App\Http\Middleware\CheckHeaderScanner',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'App\Http\Middleware\MyVerifyCsrfToken',
        'App\Http\Middleware\HrefLang',
        'App\Http\Middleware\CheckSendCookie',
        'App\Http\Middleware\CheckWhatsAppShare',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */

    protected $routeMiddleware = [
        'auth' => 'App\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
        'roles' => 'App\Http\Middleware\CheckRole',
        'lang' => 'App\Http\Middleware\Lang',
        'checkDisattivato' => 'App\Http\Middleware\CheckHotelDisattivato',
        'checkDispositivo' => 'App\Http\Middleware\CheckDispositivo',
        'couponAttivo' => 'App\Http\Middleware\CouponAttivoMiddleware',
        'limitIP' => 'App\Http\Middleware\LimitForIP',
    ];

}
