<?php

use Illuminate\Http\Request;

// Events

Route::post('/events')
    ->name('events')
    ->uses('EventsController@store');
