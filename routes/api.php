<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NodeController;
use Database\Seeders\NodeSeeder;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/seed', function () {
    $seeder = new NodeSeeder;
    $seeder->run();
});

Route::post('node/', [NodeController::class, 'create']);
Route::get('node/parents', [NodeController::class, 'indexParents']);
Route::get('node/child/{parent}', [NodeController::class, 'indexChildByParent']);
Route::delete('node/{id}', [NodeController::class, 'delete']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});