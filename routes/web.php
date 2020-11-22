<?php

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

Route::get('/', function () {
    return view('index');
});

Route::post('/image-upload', [ \App\Http\Controllers\NotesController::class, 'imageUploadNote' ])->name('images.upload.post');
Route::post('/add-note', [ \App\Http\Controllers\NotesController::class, 'addNote' ])->name('add.note');
Route::post('/remove-note', [ \App\Http\Controllers\NotesController::class, 'removeNote' ])->name('remove.note');
Route::post('/get-notes', [ \App\Http\Controllers\NotesController::class, 'getNotes' ])->name('get.notes');
