<?php

use App\Http\Controllers\AdminPanel\AdminController;
use App\Http\Controllers\AdminPanel\EventController;
use App\Http\Controllers\AdminPanel\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventManagerPanel\EventManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPanel\UserController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Route Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/user-list', [UserController::class, 'getAllUser'])->name('admin.user');
    Route::get('user/showFormCreate', [UserController::class, 'showFormCreateUser'])->name('admin.user.create');
    Route::post('user/create', [UserController::class, 'createUser'])->name('admin.user.submit');
    Route::get('/user/{id}/edit', [UserController::class, 'showFormEditUser'])->name('admin.user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'updateUser'])->name('admin.user.update');
    Route::post('/user/{id}/delete', [UserController::class, 'deleteUser'])->name('admin.user.delete');

    Route::get('/eventManager-list', [EventManagerController::class, 'getAllEventManager'])->name('admin.eventManager');
    Route::get('eventManager/showFormCreate', [EventManagerController::class, 'formCreateEventManager'])->name('admin.eventManager.create');
    Route::post('eventManager/create', [EventManagerController::class, 'createEventManager'])->name('admin.eventManager.submit');
    Route::get('/eventManager/{id}/edit', [EventManagerController::class, 'formEditEventManager'])->name('admin.eventManager.edit');
    Route::post('/eventManager/{id}/update', [EventManagerController::class, 'updateEventManager'])->name('admin.eventManager.update');
    Route::post('/eventManager/{id}/delete', [EventManagerController::class, 'deleteEventManager'])->name('admin.eventManager.delete');

    Route::get('/event-list', [EventController::class, 'getAllEvent'])->name('admin.event');
    Route::get('/event/{id}/detail', [EventController::class, 'eventDetail'])->name('admin.event.show');
    Route::get('event/showFormCreate', [EventController::class, 'formCreateEvent'])->name('admin.event.create');
    Route::post('event/create', [EventController::class, 'creatEvent'])->name('admin.event.submit');
    Route::get('/event/{id}/edit', [EventController::class, 'formEditEvent'])->name('admin.event.edit');
    Route::post('/event/{id}/update', [EventController::class, 'updateEvent'])->name('admin.event.update');
    Route::post('/event/{id}/delete', [EventController::class, 'deleteEvent'])->name('admin.event.delete');


    Route::get('/ticket-list', [TicketController::class, 'getAllTicket'])->name('admin.ticket');
    Route::get('event/{id}/showFormCreate', [TicketController::class, 'formCreateTicket'])->name('admin.ticket.create');
    Route::post('event/{id}/create', [TicketController::class, 'createTicket'])->name('admin.ticket.submit');
});

// Role  Event_Manager
Route::middleware(['auth', 'role:event_manager'])->prefix('event_manager')->group(function () {
    Route::get('/dashboard', [EventManagerController::class, 'index'])->name('event_manager.dashboard');

    Route::get('/event-list', [EventController::class, 'getManagerEvents'])->name('event_manager.event');
    Route::get('/event/{id}/detail', [EventManagerController::class, 'eventDetail'])->name('event_manager.event.show');
    Route::get('event/showFormCreate', [EventManagerController::class, 'formCreateEvent'])->name('event_manager.event.create');
    Route::post('event/create', [EventManagerController::class, 'creatEvent'])->name('event_manager.event.submit');
    Route::get('/event/{id}/edit', [EventManagerController::class, 'formEditEvent'])->name('event_manager.event.edit');
    Route::post('/event/{id}/update', [EventManagerController::class, 'updateEvent'])->name('event_manager.event.update');
    Route::post('/event/{id}/delete', [EventManagerController::class, 'deleteEvent'])->name('event_manager.event.delete');


    Route::get('/ticket-list', [TicketController::class, 'getAllTicket'])->name('admin.ticket');
    Route::get('event/{id}/showFormCreate', [TicketController::class, 'formCreateTicket'])->name('admin.ticket.create');
    Route::post('event/{id}/create', [TicketController::class, 'createTicket'])->name('admin.ticket.submit');
});



Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});
