<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/ 
 
Route::namespace('Api') 
            ->name('api.') 
            ->middleware('auth:api')
            ->group(function () {

                 Route::resource('/tools', 'ToolsController'); 
                 
        });

