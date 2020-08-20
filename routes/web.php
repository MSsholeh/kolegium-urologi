
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
    Route::get('university', 'SelectController@university')->name('university');
});

Route::prefix('schedule')->name('schedule.')->middleware('auth:web')->group(static function () {
    Route::get('/', 'ScheduleController@index')->name('index');
    Route::prefix('{requirement}')->group(static function(){
        Route::get('show', 'ScheduleController@show')->name('show');
    });
    Route::post('register', 'ScheduleController@store')->name('store');
});

Route::prefix('graduation')->name('graduation.')->middleware('auth:web')->group(static function () {
    Route::get('/', 'GraduationController@index')->name('index');
    Route::post('register', 'GraduationController@store')->name('store');
});

Route::prefix('certificate')->name('certificate.')->middleware('auth:web')->group(static function () {
    Route::get('/', 'CertificateController@index')->name('index');
});

Route::prefix('certificate/{requirement}')->name('certificate.')->group(static function () {
    Route::get('register', 'CertificateController@register')->name('register');
    Route::post('register', 'CertificateController@store')->name('store');
});

Route::prefix('dashboard')->name('dashboard.')->namespace('Dashboard')
    ->middleware('auth:web')
    ->group(static function(){

        Route::get('profile', 'DashboardController@profile')->name('profile');

        Route::get('/', 'DashboardController@index')->name('home');
        Route::get('{registrant}/detail', 'DashboardController@detail')->name('detail');
    });

Route::prefix('registration/{requirement}')->name('registration.')->group(static function () {
    Route::get('register', 'RegistrationController@register')->name('register');
    Route::post('register', 'RegistrationController@store')->name('store');
});

Route::prefix('registration-graduation/{requirement}')->name('registration-graduation.')->group(static function () {
    Route::get('register', 'RegistrationGraduationController@register')->name('register');
    Route::post('register', 'RegistrationGraduationController@store')->name('store');
});
Route::get('registration-graduation', 'RegistrationGraduationController@index')->name('registration-graduation.index');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
