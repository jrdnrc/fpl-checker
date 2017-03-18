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
$this->post('login', Controllers\Auth\\LoginController::class . '@login');
$this->post('logout', Controllers\Auth\\LoginController::class . '@logout')->name('logout');

// Registration Routes...
$this->get('register', Controllers\Auth\\RegisterController::class . '@showRegistrationForm')->name('register');
$this->post('register', Controllers\Auth\\RegisterController::class . '@register');

// Password Reset Routes...
$this->get('password/reset', Controllers\Auth\\ForgotPasswordController::class . '@showLinkRequestForm')->name('password.request');
$this->post('password/email', Controllers\Auth\\ForgotPasswordController::class . '@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', Controllers\Auth\\ResetPasswordController::class . '@showResetForm')->name('password.reset');
$this->post('password/reset', Controllers\Auth\\ResetPasswordController::class . '@reset');

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