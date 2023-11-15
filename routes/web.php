<?php

use Illuminate\Http\Request;

//--Fer Jose
use App\Http\Controllers\Generales\ProtocoloController;
//--

Route::group(['middleware' => ['auth','role_or_permission:ADMIN']], function () {
    Route::get('generales/protocolo', [ProtocoloController::class, 'protocolo'])->name('generales.protocolo');
});

