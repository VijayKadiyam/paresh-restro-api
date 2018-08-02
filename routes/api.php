<?php

use Illuminate\Http\Request; 

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Helpers
// Roles
Route::resource('roles', 'RoleController');
// Contact Types
Route::resource('contact-types', 'ContactTypeController');
// Menu Types
Route::resource('menu-types', 'MenuTypeController');
// Currencies
Route::resource('currencies', 'CurrencyController');
// Statuses
Route::resource('statuses', 'StatusController');

// Auth
Route::post('/login', 'Auth\LoginController@login');
Route::post('/register', 'Auth\RegisterController@register');

// Hotels
Route::resource('hotels', 'HotelController');
// Contacts
Route::resource('contacts', 'ContactController');
// Recepies
Route::resource('recepies', 'RecepieController');
// Addons
Route::resource('addons', 'AddonController');
// Recepie menus
Route::resource('recepie-menus', 'RecepieMenuController');
// Addon menus
Route::resource('addon-menus', 'AddonMenuController');
// Tables
Route::resource('tables', 'TableController');
// Orders
Route::resource('orders', 'OrderController');
// Tickets
Route::resource('orders/{order}/tickets', 'TicketController');
// Ticket addons
Route::resource('orders/{order}/tickets/{ticket}/addons', 'TicketAddonController');


