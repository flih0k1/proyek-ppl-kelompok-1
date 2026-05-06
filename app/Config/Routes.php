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

// BLOKIR ROUTE UNTUK FITUR YANG BELUM MASUK SPRINT 1
$blocked_sprint2 = function() {
    echo "<div style='text-align:center; margin-top:50px; font-family: sans-serif;'>
            <h1>Maaf, Fitur Belum Tersedia!</h1>
            <p>Fitur ini akan dirilis pada Sprint berikutnya.</p>
            <a href='javascript:history.back()'>Kembali</a>
          </div>";
    exit;
};

$routes->get('admin/data-rak-buku', $blocked_sprint2);
$routes->get('admin/data-maintenance', $blocked_sprint2);
$routes->get('admin/data-peminjaman', $blocked_sprint2);
$routes->get('admin/data-tagihan-member', $blocked_sprint2);
$routes->get('admin/data-request-buku', $blocked_sprint2);
$routes->get('pimpinan/laporan-(.*)', $blocked_sprint2);
$routes->get('data-pinjaman-buku', $blocked_sprint2);
$routes->get('request-buku', $blocked_sprint2);
$routes->get('data-tagihan', $blocked_sprint2);

// Blokir modal & action peminjaman/request untuk Member
$routes->get('member/modal/pinjam-buku', $blocked_sprint2);
$routes->post('member/postdata/pinjam/pinjam_buku', $blocked_sprint2);
$routes->get('member/modal/add-request', $blocked_sprint2);
$routes->post('member/postdata/pinjam/request_buku', $blocked_sprint2);

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
