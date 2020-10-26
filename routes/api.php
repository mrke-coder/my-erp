<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'user'], function (){
    Route::post('/login', 'AuthController@login')->name('auth.login');
    Route::group(['middleware'=>'auth:api'], function (){
        Route::get('/logout', 'AuthController@logout')->name('auth.logout');
        Route::get('/profil', 'AuthController@profil');
        Route::post('/register', 'UserController@register')->name('auth.register');
        Route::get('users','UserController@index')->name('auth.list');
        Route::get('roles','RoleController@index');
        Route::get('/role_by_user','RoleController@usersRoles');
        Route::get('/users/{id}','UserController@show');
        Route::put('/update','UserController@update');
        Route::put('/delete/{id}','UserController@destroy');
        Route::group(['prefix'=>'rh'], function (){
            Route::resource('jobs','Rh\JobApplicationController')->middleware('scope:do_anyThings,can_create');
            Route::resource('employees','Rh\EmployeeController')->middleware('scope:do_anyThings,can_create');
            Route::get('employees/{id}/delete','Rh\RestoreDeleteController@employee')->middleware('scope:do_anyThings');
            Route::get('wages/{id}/restore','Rh\RestoreDeleteController@wage')->middleware('scope:do_anyThings');
            Route::resource('bonuses','Rh\BonusController')->middleware('scope:do_anyThings,can_create');
            Route::resource('absences','Rh\AbsenceController')->middleware('scope:do_anyThings,can_create');
            Route::get('bonus/{id}', 'Rh\RestoreDeleteController@bonus')->middleware('scope:do_anyThings');
            Route::resource('add_hours','Rh\AdditionalHourController')->middleware('scope:do_anyThings,can_create');
            Route::resource('departures','Rh\DepartureController')->middleware('scope:do_anyThings,can_create');
            Route::resource('displacements','Rh\DisplacementController')->middleware('scope:do_anyThings,can_create');
            Route::resource('leaves','Rh\LeaveController')->middleware('scope:do_anyThings,can_create');
            Route::resource('trainings','Rh\TrainingController')->middleware('scope:do_anyThings,can_create');
            Route::resource('wags','Rh\WagController')->middleware('scope:do_anyThings,can_create');
            Route::resource('departments','Rh\DepartmentController')->middleware('scope:do_anyThings,can_create');
            Route::get('restore/department/{id}','Rh\RestoreDeleteController@departement')->middleware('scope:do_anyThings');
            Route::get('restore/leave/{id}','Rh\RestoreDeleteController@leave')->middleware('scope:do_anyThings');
            Route::get('restore/add_hour/{id}','Rh\RestoreDeleteController@add_hour')->middleware('scope:do_anyThings');
            Route::get('restore/speciality/{id}','Rh\RestoreDeleteController@speciality')->middleware('scope:do_anyThings');
            Route::get('restore/training/{id}','Rh\RestoreDeleteController@training')->middleware('scope:do_anyThings');
            Route::get('restore/displacement/{id}','Rh\RestoreDeleteController@displacement')->middleware('scope:do_anyThings');
            Route::get('all_departement','Rh\RestoreDeleteController@allDepartement')->middleware('scope:do_anyThings,can_create');
            Route::get('all_specialities','Rh\RestoreDeleteController@allSpecialities')->middleware('scope:do_anyThings,can_create');
            Route::get('all_employees','Rh\RestoreDeleteController@allEmployees')->middleware('scope:do_anyThings,can_create');
            Route::resource('specialities','Rh\SpecialityController')->middleware('scope:do_anyThings,can_create');
        });
    });
});
