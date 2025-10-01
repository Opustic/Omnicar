<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post("/equipe/create", function(Request $request){

    dd($request);

});