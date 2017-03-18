<?php declare(strict_types=1);

use JrdnRc\FplChecker\Laravel\Http\Controllers;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** @var \Illuminate\Routing\Router $router */

$router->get(
    '/',
    function () {
        return view('welcome');
    }
);

// Authentication Routes...
$this->get('login', Controllers\Auth\LoginController::class . '@showLoginForm')->name('login');
$this->post('login', Controllers\Auth\LoginController::class . '@login');
$this->post('logout', Controllers\Auth\LoginController::class . '@logout')->name('logout');

$router->get('/home', Controllers\HomeController::class);

$router->group(
    [
        'prefix' => 'oauth',
    ],
    function (Router $router) {
        $router->get('register', Controllers\RegisterViaGoogle::class)->name('google_register');
        $router->post('register', Controllers\CompleteGoogleRegistration::class)->name('post_google_register');
    }
);