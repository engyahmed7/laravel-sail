<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\sendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// user profile route
Route::middleware('auth:api')->group(function () {
    Route::get('/user-profile', function (Request $request) {
        return response()->json($request->user());
    });
});

// auth routes
// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::post('refresh', 'refresh');
// });

// Sanctum auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');                      //check if there is token in db for that user

Route::middleware(['auth:sanctum', 'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value])->group(function () {
    Route::get('/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::post('password/email', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');


// posts routes
Route::middleware(['firebase.auth', 'locale'])->group(function () {
    // Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);

});
Route::get('/getMessages', [ChatController::class, 'getMessages']);
Route::post('/sendMessage', [ChatController::class, 'sendMessage']);


// Route::post('/posts', [PostController::class, 'store']);
// Route::get('/posts', [PostController::class, 'index']);
// Route::get('/posts/{id}', [PostController::class, 'show']);
// Route::get('/posts/create', [PostController::class, 'create']);
// Route::put('/posts/{id}', [PostController::class, 'update']);
// Route::delete('/posts/{id}', [PostController::class, 'destroy']);
// Route::get('/posts/{id}/edit', [PostController::class, 'edit']);


//firebase routes

Route::post('/firebase/user', [FirebaseController::class, 'createUser']);
Route::post('/firebase/login', [FirebaseController::class, 'loginUser']);

Route::get('/send-web-notification', [sendNotification::class, 'sendWebNotification'])->name('send.web-notification');

// lara Notification
Route::get('/send-notification', [NotificationController::class, 'sendNotification']);