<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/**
 * Call with default JWT policy
 * $routes->get("test", "ApiController::test",['filter'=>'jwt']);
 *
 * Call with custom JWT policy defined in APIJwt config file
 * $routes->get("test", "ApiController::test",['filter'=>'jwt:CONFIG_POLICY']);
 * $routes->get("test", "ApiController::test",['filter'=>'jwt:test']);
 *
 */


// $routes->group('api', ['filter' => 'jwt'], static function ($routes) 
$routes->group("api", function ($routes) {

    $routes->post("diegopacman/v1/login", "UsersController::login");

    $routes->get("diegopacman/v1/logged", "UsersController::logged", ['filter' => 'jwt']);

    $routes->post("diegopacman/v1/logout", "UsersController::logout", ['filter' => 'jwt']);
    
    $routes->post("diegopacman/v1/create_user", "UsersController::create");

    $routes->post("diegopacman/v1/update_user", "UsersController::update_user", ['filter' => 'jwt']);


    $routes->post("diegopacman/v1/config_game", "ApiController::addGameConfig", ['filter' => 'jwt']);
    $routes->post("diegopacman/v1/update_config_game", "ApiController::updateGameConfig", ['filter' => 'jwt']);

    $routes->post("diegopacman/v1/add_game", "ApiController::addGame", ['filter' => 'jwt']);


    $routes->get("diegopacman/v1/get_user_last_games", "ApiController::getUserLastGames", ['filter' => 'jwt']);

    $routes->get("diegopacman/v1/get_user_stats", "ApiController::getUserStats", ['filter' => 'jwt']);

    $routes->get("diegopacman/v1/get_top_users", "ApiController::getTopUsers");


    /**
   * POST 
   * POST nom_projecte/V1 /login
   * GET nom_projecte/V1/logged
   * POST nom_projecte/V1/update_user
   * POST nom_projecte/V1/logout
   * POST nom_projecte/V1/config_game
   * POST nom_projecte/V1/update_config_game
   * POST nom_projecte/V1/add_game
   * GET nom_projecte/V1/get_user_last_games
   * GET nom_projecte/V1/get_user_stats
   * GET nom_projecte/V1/get_top_users 
    **/

    
    /**
     * Call with default JWT policy
     * $routes->get("test", "ApiController::test",['filter'=>'jwt']);
     *
     * Call with custom JWT policy defined in APIJwt config file
     * $routes->get("test", "ApiController::test",['filter'=>'jwt:CONFIG_POLICY']);
     * $routes->get("test", "ApiController::test",['filter'=>'jwt:test']);
     *
     */
    $routes->get("test", "ApiController::test", ['filter' => 'jwt']);
});
