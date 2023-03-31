<?php

use App\Http\Controllers\GameController;
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

Route::get('/', [GameController::class, 'index'])->name('game.index');
Route::get('/play/{level}', [GameController::class, 'playGame'])->name('game.play');
Route::get('/level-editor', [GameController::class, 'levelEditor'])->name('game.editor');
Route::post('/level-editor/save', [GameController::class, 'saveLevel'])->name('game.editor.save');
Route::post('/submit-score', [GameController::class, 'setHiScore'])->name('game.score');
