<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventManagerController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('en');
});

Route::get('/pusher', function () {
    return view('pusher');
});

Route::get('/localization/{locale}', LocalizationController::class)->name('localization');

Route::middleware([Localization::class])
    ->group(function () {

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
    });


Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Route Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/user/search', [UserController::class, 'search'])->name('admin.user.search');
    Route::get('/user-list', [UserController::class, 'formUserList'])->name('admin.user');
    Route::get('/get-all-user', [UserController::class, 'getAllUser'])->name('admin.user.get');
    // Route::get('/user/{id}/show', [UserController::class, 'getUserById'])->name('admin.user.show');
    Route::get('/user/{id}/detail', [UserController::class, 'formUserDetail'])->name('admin.user.detail');
    Route::get('user/showFormCreate', [UserController::class, 'showFormCreateUser'])->name('admin.user.create');
    Route::post('user/create', [UserController::class, 'createUser'])->name('admin.user.submit');
    Route::get('/user/{id}/edit', [UserController::class, 'showFormEditUser'])->name('admin.user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'updateUser'])->name('admin.user.update');
    Route::post('/user/{id}/delete', [UserController::class, 'deleteUser'])->name('admin.user.delete');

    Route::get('/event/search', [EventController::class, 'search'])->name('admin.event.search');
    Route::get('/event-list', [EventController::class, 'formEventList'])->name('admin.event');
    Route::get('/get-all-event', [EventController::class, 'getAllEvent'])->name('admin.event.get');
    Route::get('/event/{id}/detail', [EventController::class, 'eventDetail'])->name('admin.event.show');
    Route::get('event/showFormCreate', [EventController::class, 'formCreateEvent'])->name('admin.event.create');
    Route::post('event/create', [EventController::class, 'creatEvent'])->name('admin.event.submit');
    Route::get('/event/{id}/edit', [EventController::class, 'formEditEvent'])->name('admin.event.edit');
    Route::post('/event/{id}/update', [EventController::class, 'updateEvent'])->name('admin.event.update');
    Route::post('/event/{id}/delete', [EventController::class, 'deleteEvent'])->name('admin.event.delete');

    Route::get('/ticket/search', [TicketController::class, 'search'])->name('admin.ticket.search');
    Route::get('/ticket-list', [TicketController::class, 'formTicketList'])->name('admin.ticket');
    Route::get('/get-all-ticket', [TicketController::class, 'getAllTicket'])->name('admin.ticket.get');
    Route::get('/ticket/create', [TicketController::class, 'formCreateTicket'])->name('admin.ticket.create');
    Route::post('/ticket', [TicketController::class, 'createTicket'])->name('admin.ticket.submit');
    Route::get('/ticket/{id}/edit', [TicketController::class, 'formEditTicket'])->name('admin.ticket.edit');
    Route::post('/ticket/{id}/update', [TicketController::class, 'updateTicket'])->name('admin.ticket.update');
    Route::post('/ticket/{id}/delete', [TicketController::class, 'deleteTicket'])->name('admin.ticket.delete');
});

// Role  Event_Manager
Route::middleware(['auth', 'role:event_manager'])->prefix('event_manager')->group(function () {
    Route::get('/dashboard', [EventManagerController::class, 'index'])->name('event_manager.dashboard');

    Route::get('/event/search', [EventManagerController::class, 'searchEventOfManager'])->name('event_manager.event.search');
    Route::get('/event-list', [EventManagerController::class, 'formEventListOfManager'])->name('event_manager.event');
    Route::get('/get-all-event', [EventManagerController::class, 'getAllEventOfManager'])->name('event_manager.get');
    Route::get('/event/{id}/detail', [EventManagerController::class, 'eventDetail'])->name('event_manager.event.show');
    Route::get('event/showFormCreate', [EventManagerController::class, 'formCreateEventOfManager'])->name('event_manager.event.create');
    Route::post('event/create', [EventManagerController::class, 'creatEventOfManager'])->name('event_manager.event.submit');
    Route::get('/event/{id}/edit', [EventManagerController::class, 'formEditEventOfManager'])->name('event_manager.event.edit');
    Route::post('/event/{id}/update', [EventManagerController::class, 'updateEventOfManager'])->name('event_manager.event.update');
    Route::post('/event/{id}/delete', [EventManagerController::class, 'deleteEvent'])->name('event_manager.event.delete');
    Route::get('/notification', [NotificationController::class, 'getAllNotification'])->name('event_manager.notification');
    Route::get('/formNotification', [NotificationController::class, 'formNotification'])->name('event_manager.formNotification');
    Route::post('/notification/update', [NotificationController::class, 'updateStatus'])->name('event_manager.updateNotification');
});



//User
Route::prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');


    Route::get('/event/search', [EventController::class, 'searchEvent'])->name('user.event.search');
    Route::get('/event-list', [EventController::class, 'formEventListOfUser'])->name('user.event');
    Route::get('/get-all-event', [EventController::class, 'getAllEventOfUser'])->name('user.event.get');
    Route::get('/event/{id}/detail', [EventController::class, 'eventDetailOfUser'])->name('user.event.show');
    Route::get('/event/{id}/register', [EventRegistrationController::class, 'registerEvent'])->name('user.event.register');
    Route::post('/event/{eventId}/register', [EventRegistrationController::class, 'processRegistration'])->name('user.event.processRegistration');
    Route::post('/event/{eventId}/cancel', [EventRegistrationController::class, 'cancel'])->name('user.event.cancel');
    Route::get('/my-event', [EventRegistrationController::class, 'formEventRegisterd'])->name('user.event.showEvent');
    Route::get('/get-all-eventRegistration', [EventRegistrationController::class, 'getAllEventRegisterd'])->name('user.event.getEventRegistered');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});
