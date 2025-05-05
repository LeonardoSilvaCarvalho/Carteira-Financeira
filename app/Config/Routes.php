<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');
$routes->post('loginAuth', 'AuthController::loginAuth');
$routes->get('register', 'AuthController::register');
$routes->post('save', 'AuthController::save');
$routes->get('logout', 'AuthController::logout');


// Rotas protegidas com filtro de autenticação
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'WalletController::dashboard');
    $routes->get('deposit', 'WalletController::depositView');
    $routes->get('transfer', 'WalletController::transferView');
    $routes->get('revers', 'WalletController::reverseRequestView');
    $routes->post('revers/(:num)', 'WalletController::reverseTransaction/$1');
    $routes->post('deposit', 'WalletController::deposit');
    $routes->post('transfer', 'WalletController::transfer');
});
