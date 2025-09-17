<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Auth;

// Главная страница (категории)
Route::get('/', [MainController::class, 'index'])->name('home');

// Карточки по категориям и регионам
Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');

// Карта регионов
Route::get('/map', [MapController::class, 'index'])->name('map');


// Админ-панель (с middleware auth)
Route::prefix('admin')->middleware(['auth'])->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{card}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/{card}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{card}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

// Аутентификация
Auth::routes(['register' => false]); // Отключаем регистрацию, оставляем только логин
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
