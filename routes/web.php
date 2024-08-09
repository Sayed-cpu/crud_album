<?php

use App\Http\Controllers\AlbumController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource('albums', AlbumController::class);

Route::delete('/albums/{id}/delete-all', [AlbumController::class, 'deleteAllPictures'])->name('albums.deleteAllPictures');
Route::get('/albums/{id}/move-pictures', [AlbumController::class, 'movePictures'])->name('albums.movePictures');
Route::post('/albums/move-pictures-to-another', [AlbumController::class, 'movePicturesToAnother'])->name('albums.movePicturesToAnother');
Route::delete('/photos/{id}', [AlbumController::class, 'deletePicture']);
