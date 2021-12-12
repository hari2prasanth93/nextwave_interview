<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->post('/deviceRegister','UsersController@deviceRegister');

$router->post('/userRegister','UsersController@userRegister');

$router->post('/login','UsersController@login');

$router->group(['middleware' => 'auth'], function() use ($router) {
    $router->get('/tournamentLists','TournamentsController@tournamentList');
    $router->post('/addTournamentScore/{id}','TournamentsController@addScore');
    $router->get('/leaderBoard/{id}','TournamentsController@leaderBoard');
});
