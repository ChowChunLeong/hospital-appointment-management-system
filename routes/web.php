<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\DoctorController;
//use App\Http\Middleware\NurseAuthCheck;
//use App\Http\Middleware\DoctorAuthCheck;

//PATIENT
Route::get('/', [PatientController::class, 'load_patient_view'])->name('landing_page');
Route::post('/',[PatientController::class,'submit_and_save_register_info']); 
Route::get('/search',[PatientController::class,'retrieve_patient_info'])->name('search');

//NURSE
Route::get('/nurse',[NurseController::class, 'load_login_page']);
Route::get('/nurse/logout',[NurseController::class, 'logout'])->name('nurse.logout');

Route::group(['middleware'=>['NurseAuthCheck']], function(){
    Route::get('/nurse/dashbroad', [NurseController::class, 'load_dashbroad_page']);
    Route::post('/nurse/login',[NurseController::class, 'login'])->name('nurse.login');
    Route::post('/nurse/dashbroad/retrieve_doctor_list', [NurseController::class, 'retrieve_doctor_list'])->name('nurse.dashbroad.retrieve_doctor_list');
    Route::post('/nurse/dashbroad/complete_appointment', [NurseController::class, 'complete_appointment'])->name('nurse.dashbroad.complete_appointment');
    Route::post('/nurse/dashbroad/update_status', [NurseController::class, 'update_status'])->name('nurse.dashbroad.update_status');
});

//DOCTOR
Route::get('/doctor',[DoctorController::class, 'load_login_page']);
Route::get('/doctor/logout',[DoctorController::class, 'logout'])->name('doctor.logout');

Route::group(['middleware'=>['DoctorAuthCheck']], function(){
    Route::post('/doctor/login',[DoctorController::class, 'login'])->name('doctor.login');
    Route::get('/doctor/dashbroad',[DoctorController::class,'load_dashbroad_page'])->name('doctor.dashbroad');
    Route::post('/doctor/dashbroad/update_status', [DoctorController::class, 'update_status'])->name('doctor.dashbroad.update_status');
});

