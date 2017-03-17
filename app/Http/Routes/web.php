<?php declare(strict_types=1);

use JrdnRc\FplChecker\Laravel\Http\Controllers;

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

$router->auth();

$router->get('/home', Controllers\HomeController::class);
