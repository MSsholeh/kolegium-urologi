
<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');

Route::get('file/{path}', 'FileController@get')->name('file')->where(['path' => '.*']);

Route::prefix('select')->name('select.')->group(static function() {
    Route::get('period', 'SelectController@period')->name('period');
    Route::get('university', 'SelectController@university')->name('university');
    Route::get('certificate', 'SelectController@certificate')->name('certificate');
});

Route::prefix('registration')->name('registration.')->middleware('auth:web')->group(static function () {
    Route::get('/', 'RegistrationController@index')->name('index');
    Route::post('register', 'RegistrationController@register')->name('register');
    Route::get('{requirement}/register', 'RegistrationController@registration')->name('registration');
    Route::post('{requirement}/register', 'RegistrationController@store')->name('store');
});

Route::prefix('profile')->name('profile.')->middleware('auth:web')->group(static function () {
    Route::get('/', 'UserController@index')->name('index');
    Route::post('update', 'UserController@update')->name('update');
});

Route::prefix('certificate')->name('certificate.')->middleware('auth:web')->group(static function () {
    Route::get('/', 'CertificateController@index')->name('index');
    Route::post('register', 'CertificateController@register')->name('register');
});

Route::prefix('certificate/{requirement}')->name('certificate.')->group(static function () {
    Route::post('register', 'CertificateController@store')->name('store');
});

Route::get('change-password', 'ChangePasswordController@index');
Route::post('change-password', 'ChangePasswordController@store')->name('change.password');

Route::prefix('dashboard')->name('dashboard.')->namespace('Dashboard')->middleware('auth:web');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
