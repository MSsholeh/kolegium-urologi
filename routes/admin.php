<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->group(static function(){

    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

});

Auth::routes(['register' => false, 'verify' => false]);

Route::middleware('auth:admin')->group(static function () {

    Route::get('/', 'HomeController@index');

    Route::get('home/{period?}', 'HomeController@index')->name('home');

    Route::prefix('select')->name('select.')->group(static function() {
        Route::get('period/{type?}', 'SelectController@period')->name('period');
        Route::get('university', 'SelectController@university')->name('university');
        Route::get('useradmin', 'SelectController@userAdmin')->name('useradmin');
        Route::get('useruniversitas/{university_id}', 'SelectController@userUniversitas')->name('useruniversitas');
        Route::get('university', 'SelectController@university')->name('university');
        Route::get('registrant/{university_id}', 'SelectController@registrant')->name('registrant');
        Route::get('registrant-graduation/{university_id}', 'SelectController@registrant_graduation')->name('graduation');
    });

    Route::prefix('period')->name('period.')->group(static function() {
        Route::get('table', 'PeriodController@table')->name('table');
        Route::get('change/{period}', 'PeriodController@change')->name('change');
    });
    Route::resource('period', 'PeriodController');

    Route::prefix('user')->name('user.')->group(static function() {
        Route::get('table', 'UserController@table')->name('table');
        Route::get('change/{user}', 'UserController@change')->name('change');
    });
    Route::resource('user', 'UserController');

    Route::prefix('requirement')->name('requirement.')->group(static function() {
        Route::get('table', 'RequirementController@table')->name('table');
        Route::get('{requirement}/change', 'RequirementController@change')->name('change');
        Route::get('{requirement}/change', 'RequirementController@change')->name('change');
    });
    Route::resource('requirement', 'RequirementController');

    Route::prefix('requirement-graduation')->name('requirement-graduation.')->group(static function() {
        Route::get('table', 'RequirementGraduationController@table')->name('table');
        Route::get('{requirement_graduation}/change', 'RequirementGraduationController@change')->name('change');
        Route::get('{requirement_graduation}/change', 'RequirementGraduationController@change')->name('change');
    });
    Route::resource('requirement-graduation', 'RequirementGraduationController');

        Route::resource('requirement', 'RequirementController');

    Route::prefix('requirement-certificate')->name('requirement-certificate.')->group(static function() {
        Route::get('table', 'RequirementCertificateController@table')->name('table');
        Route::get('{requirement_certificate}/change', 'RequirementCertificateController@change')->name('change');
        Route::get('{requirement_certificate}/change', 'RequirementCertificateController@change')->name('change');
    });
    Route::resource('requirement-certificate', 'RequirementCertificateController');

    Route::prefix('registrant')->name('registrant.')->group(static function() {
        Route::get('{registrant}/validation', 'RegistrantController@validation')->name('validation');
        Route::post('{registrant}/validation', 'RegistrantController@store')->name('store');
        Route::get('table', 'RegistrantController@table')->name('table');
    });
    Route::resource('registrant', 'RegistrantController')->except(['store']);

    Route::prefix('registrant-validation')->name('registrant-validation.')->group(static function() {
        Route::get('{registrant_validation}/validation', 'RegistrantValidationController@validation')->name('validation');
        Route::post('{registrant_validation}/validation', 'RegistrantValidationController@store')->name('store');
        Route::get('table', 'RegistrantValidationController@table')->name('table');
    });
    Route::resource('registrant-validation', 'RegistrantValidationController')->except(['store']);

    Route::prefix('registrant-graduation')->name('registrant-graduation.')->group(static function() {
        Route::get('{registrant_graduation}/validation', 'RegistrantGraduationController@validation')->name('validation');
        Route::post('{registrant_graduation}/validation', 'RegistrantGraduationController@store')->name('store');
        Route::get('table', 'RegistrantGraduationController@table')->name('table');
    });
    Route::resource('registrant-graduation', 'RegistrantGraduationController');

    Route::prefix('registrant-certificate')->name('registrant-certificate.')->group(static function() {
        Route::get('{registrant_certificate}/validation', 'RegistrantCertificateController@validation')->name('validation');
        Route::post('{registrant_certificate}/validation', 'RegistrantCertificateController@store')->name('store');
        Route::get('table', 'RegistrantCertificateController@table')->name('table');
    });
    Route::resource('registrant-certificate', 'RegistrantCertificateController')->except(['store']);

    Route::prefix('sertifikat')->name('sertifikat.')->group(static function() {
        Route::get('table', 'SertifikatController@table')->name('table');
        Route::get('{id}/download', 'SertifikatController@download')->name('download');
    });
    Route::resource('sertifikat', 'SertifikatController')->except(['store']);

    Route::prefix('exam')->name('exam.')->group(static function() {
        Route::get('table', 'ExamScheduleController@table')->name('table');

        Route::prefix('{exam}')->group(static function() {

            Route::prefix('participants')->name('participants.')->group(static function() {
                Route::get('table', 'ExamParticipantController@table')->name('table');
            });
            Route::resource('participants', 'ExamParticipantController');

        });

    });
    Route::resource('exam', 'ExamScheduleController');

    Route::prefix('setting')->name('setting.')->namespace('Setting')->group(static function() {

        Route::prefix('university')->name('university.')->group(static function() {
            Route::get('table', 'UniversityController@table')->name('table');
        });
        Route::resource('university', 'UniversityController');

        Route::prefix('admin')->name('admin.')->group(static function() {
            Route::get('table', 'AdminController@table')->name('table');
        });
        Route::resource('admin', 'AdminController');

        Route::prefix('role')->name('role.')->group(static function() {
            Route::get('table', 'RoleController@table')->name('table');
        });
        Route::resource('role', 'RoleController');

    });

});
