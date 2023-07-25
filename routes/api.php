<?php

use App\Http\Controllers\Api\CommentApprovalController;
use App\Http\Controllers\Api\GameChatController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GamePlayerController;
use App\Http\Controllers\Api\GameQuestionController;
use App\Http\Controllers\Api\GameResultController;
use App\Http\Controllers\Api\QuestionCommentController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Models\CommentApproval;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//! Si besoin d'ajouter d'autres actions dans les Controller avec resources, les déclarer avant
Route::controller(QuestionController::class)->group(function() {
    Route::patch('/question/{question}/vote','vote')->name('question.vote');
    Route::get('/questions/search','search')->name('questions.search');
});
Route::controller(TagController::class)->group(function() {
    Route::get('/tags/search','search')->name('tags.search');
    Route::get('/tags/questions/count','questionsCount')->name('tags.questionsCount');
});
Route::controller(GameController::class)->group(function() {
    Route::get('/games/join/{game}','join')->name('game.join');
    Route::get('/games/code','code')->name('game.code');
    Route::get('/games/results/{game}','results')->name('game.results');
    Route::patch('/games/begin','begin')->name('game.begin');
});
Route::controller(GamePlayerController::class)->group(function() {
    Route::patch('/gameplayers/ready','ready')->name('game.ready');
});

Route::controller(GameQuestionController::class)->group(function() {
    Route::get('/gamequestions/question/{gameId}','question')->name('game.question');
});

Route::controller(UserController::class)->group(function() {
    Route::patch('/profile/update','updateUserProfile')->name('user.profile.update');
});


Route::get('/questions/vote', [QuestionController::class, 'vote']);
// API Resources (middleware définis au __construct du Controller)
Route::apiResource('questions', QuestionController::class);
Route::apiResource('approvals', CommentApprovalController::class);
Route::apiResource('comments', QuestionCommentController::class);
Route::apiResource('tags', TagController::class);
Route::apiResource('games', GameController::class);
Route::apiResource('gamechats', GameChatController::class);
Route::apiResource('gameplayers', GamePlayerController::class);
Route::apiResource('gamequestions', GamePlayerController::class);
Route::apiResource('gameresults', GameResultController::class);

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});
