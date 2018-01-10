<?php

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

Route::get('/')
    ->name('home')
    ->uses('HomeController@index');

// Login

Route::get('/login')
    ->name('login.redirect')
    ->uses('Auth\LoginController@redirectToProvider');

Route::get('/login/callback')
    ->name('login.callback')
    ->uses('Auth\LoginController@handleProviderCallback');

// Slack

Route::get('/slack/callback')
    ->name('slack.callback')
    ->uses('Auth\SlackController@handleProviderCallback');
