<?php

use CodeIgniter\Router\RouteCollection;

$routes->setDefaultNamespace('Modules\Auth\Controllers');
$routes->setDefaultController('login');
$routes->setDefaultMethod('login');
$routes->set404Override();
$routes->setAutoRoute(false);


$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->get('signup', 'Auth::register');
$routes->get('logout', 'Auth::logout');

$routes->group('admin', ['namespace' => 'Modules\Admin\Controllers'], function ($routes) {
    $routes->get('modal/(:any)', 'Admin::modal/$1');
    $routes->get('getdata/(:any)/(:any)', 'Admin::getdata/$1/$2');
    $routes->post('postdata/(:any)/(:any)', 'Admin::postdata/$1/$2');
    $routes->get('(:any)', 'Admin::index/$1');
});
$routes->group('pimpinan', ['namespace' => 'Modules\Pimpinan\Controllers'], function ($routes) {
    $routes->get('modal/(:any)', 'Pimpinan::modal/$1');
    $routes->get('getdata/(:any)/(:any)', 'Pimpinan::getdata/$1/$2');
    $routes->post('postdata/(:any)/(:any)', 'Pimpinan::postdata/$1/$2');
    $routes->get('(:any)', 'Pimpinan::index/$1');
});

// grouping internal
$routes->group('auth', ['namespace' => 'Modules\Auth\Controllers'], function ($routes) {
    $routes->get('modal/(:any)', 'Auth::modal/$1');
    $routes->get('getdata/(:any)/(:any)', 'Auth::getdata/$1/$2');
    $routes->post('postdata/(:any)/(:any)', 'Auth::postdata/$1/$2');
});
$routes->group('', ['namespace' => 'Modules\Member\Controllers'], function ($routes) {
    $routes->group('member', function ($routes) {
        $routes->get('modal/(:any)', 'Member::modal/$1');
        $routes->get('getdata/(:any)/(:any)', 'Member::getdata/$1/$2');
        $routes->post('postdata/(:any)/(:any)', 'Member::postdata/$1/$2');
    });
    $routes->get('(:any)', 'Member::index/$1');
});
