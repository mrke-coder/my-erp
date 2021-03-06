<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'user'], function (){
    Route::post('/login', 'AuthController@login')->name('auth.login');
    Route::group(['middleware'=>'auth:api'], function (){
        Route::get('/logout', 'AuthController@logout')->name('auth.logout');
        Route::get('/profil', 'AuthController@profil');
        Route::put('/edit-avatar/{id}', 'AuthController@editAvatar')->name('auth.edit_avatar');
        Route::put('/edit-password/{id}', 'AuthController@editPassword')->name('auth.edit_password');
        Route::put('/edit-email/{id}', 'AuthController@editUsername')->name('auth.edit_email');
        Route::put('/edit-my-infos/{id}','AuthController@editAdmin')->name('auth.edit_my-profile');

        Route::post('/register', 'UserController@register')->name('auth.register');
        Route::get('users','UserController@index')->name('auth.list');
        Route::get('roles','RoleController@index');
        Route::get('/role_by_user','RoleController@usersRoles');
        Route::get('/users/{id}','UserController@show');
        Route::put('/update','UserController@update');
        Route::delete('/delete/{id}','UserController@destroy');
        Route::post('/addRole','UserController@addRole' );
        Route::group(['prefix'=>'rh'], function (){
            Route::resource('jobs','Rh\JobApplicationController')->middleware('scope:do_anyThings,Rh');
            Route::resource('employees','Rh\EmployeeController')->middleware('scope:do_anyThings,Rh');
            Route::get('employeeDetails/{id}','Rh\RestoreDeleteController@employeeDetails')->middleware('scope:do_anyThings,Rh');
            Route::get('employees/{id}/delete','Rh\RestoreDeleteController@employee')->middleware('scope:do_anyThings');
            Route::get('wages/{id}/restore','Rh\RestoreDeleteController@wage')->middleware('scope:do_anyThings');
            Route::resource('bonuses','Rh\BonusController')->middleware('scope:do_anyThings,Rh');
            Route::resource('absences','Rh\AbsenceController')->middleware('scope:do_anyThings,Rh');
            Route::get('bonus/{id}', 'Rh\RestoreDeleteController@bonus')->middleware('scope:do_anyThings');
            Route::resource('add_hours','Rh\AdditionalHourController')->middleware('scope:do_anyThings,Rh');
            Route::resource('departures','Rh\DepartureController')->middleware('scope:do_anyThings,Rh');
            Route::resource('displacements','Rh\DisplacementController')->middleware('scope:do_anyThings,Rh');
            Route::resource('leaves','Rh\LeaveController')->middleware('scope:do_anyThings,Rh');
            Route::resource('trainings','Rh\TrainingController')->middleware('scope:do_anyThings,Rh');
            Route::resource('wags','Rh\WagController')->middleware('scope:do_anyThings,Rh');
            Route::resource('departments','Rh\DepartmentController')->middleware('scope:do_anyThings,Rh');
            Route::get('restore/department/{id}','Rh\RestoreDeleteController@departement')->middleware('scope:do_anyThings');
            Route::get('restore/leave/{id}','Rh\RestoreDeleteController@leave')->middleware('scope:do_anyThings');
            Route::get('restore/add_hour/{id}','Rh\RestoreDeleteController@add_hour')->middleware('scope:do_anyThings');
            Route::get('restore/speciality/{id}','Rh\RestoreDeleteController@speciality')->middleware('scope:do_anyThings');
            Route::get('restore/training/{id}','Rh\RestoreDeleteController@training')->middleware('scope:do_anyThings');
            Route::get('restore/displacement/{id}','Rh\RestoreDeleteController@displacement')->middleware('scope:do_anyThings');
            Route::get('restore/departure/{id}','Rh\RestoreDeleteController@departure')->middleware('scope:do_anyThings');
            Route::get('all_departement','Rh\RestoreDeleteController@allDepartement')->middleware('scope:do_anyThings,Rh');
            Route::get('all_specialities','Rh\RestoreDeleteController@allSpecialities')->middleware('scope:do_anyThings,Rh');
            Route::get('all_employees','Rh\RestoreDeleteController@allEmployees')->middleware('scope:do_anyThings,Rh');
            Route::resource('specialities','Rh\SpecialityController')->middleware('scope:do_anyThings,Rh');
            Route::get('all_emplyees_by_department/{id}','Rh\RhController@employeesByDepartment')->middleware('scope:do_anyThings,Rh');
            Route::get('nearBirthday','Rh\RhController@nearBirthday')->middleware('scope:do_anyThings,Rh');
        });
    });
});
