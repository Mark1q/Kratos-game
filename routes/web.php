<?php

use App\Http\Controllers\BattleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BattleController::class, 'index'])->name('battle.index');
Route::get('/battle/start', [BattleController::class, 'start'])->name('battle.start');
Route::get('/history', [BattleController::class, 'history'])->name('battle.history');
