<?php

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $user = $request->user();
    // dump($user->hasRole('user'));
    // dump($user->can('edit'));
    //return view('welcome');
    $user->givePermissionTo(['delete post', 'edit']);

    return response('hello', 200);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'role:admin'], function() {
    Route::get('admin', function () {
        return 'Admin Panel';
    });
});
