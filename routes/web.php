<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSeenController;
use App\Http\Controllers\PaytmController;
use App\Http\Controllers\AttendenceController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::resource('report', ReportController::class)->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
]);

Route::post('update_gallery/{gallery}', [GalleryController::class, 'update_gallery'])->name('update_gallery');

Route::post('update_employee/{report}', [ReportController::class, 'update_employee'])->name('update_employee');

Route::get('report/{report}/employee', [ReportController::class, 'viewEmployee'])->name('report.employee.show')->middleware('auth');
Route::get('report/{report}/employer', [ReportController::class, 'viewEmployer'])->name('report.employer.show')->middleware('auth');
Route::get('report/{report}/Manager', [ReportController::class, 'viewManager'])->name('report.manager.show')->middleware('auth');

// Route::get('', [NotificationController::class, 'index'])->name('asd');

Route::resource('notification', NotificationController::class)->middleware('auth')->only(['index']);
Route::put('notification/{notification}/seen', NotificationSeenController::class)->middleware('auth')->name('notification.seen');

Route::resource('gallery', GalleryController::class)->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
]);


Route::resource('users', UserController::class)->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
]);


Route::get('/setup', function () {
    $credentials = [
        'email' => 'admin@admin.com',
        'password' => 'password'
    ];
    if (!Auth::attempt($credentials)) {
        $user = new \App\Models\User();

        $user->name = 'Adminn';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);

        $user->save();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
            $updateToken = $user->createToken('update-token', ['create', 'update',]);
            $basicToken = $user->createToken('basic-token');

            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken,
            ];
        }
    }
});

Route::get('initiate', [PaytmController::class, 'initiate'])->name('initiate.payment');
Route::post('payment', [PaytmController::class, 'pay'])->name('make.payment');


Route::get('showqr', [AttendenceController::class, 'showQr'])->name('showQr');
Route::get('scanqr', [AttendenceController::class, 'scanQr'])->name('scanQr');
Route::post('attendence', [AttendenceController::class, 'markAttendence'])->name('attendence');

Route::post('/verify-password', [AttendenceController::class, 'verifyPassword'])->name('verifyPassword');
