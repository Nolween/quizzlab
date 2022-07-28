<?php

use App\Http\Controllers\Api\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//! Si besoin d'ajouter d'autres actions dans les Controller avec resources, les déclarer avant
Route::middleware(['auth'])->get('/questions','QuestionController@index')->name('questions.index');
Route::middleware(['auth'])->controller(QuestionController::class)->group(function() {
    Route::patch('/question/{question}/vote','vote')->name('question.vote');
});
// Route::get('/questions/vote', [QuestionController::class, 'vote']);
Route::apiResource('questions', QuestionController::class);