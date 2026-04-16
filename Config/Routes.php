//auth publik
$routes->get('/register',       'AuthController::register');
$routes->post('/register',      'AuthController::registerProcess');
$routes->get('/login',          'AuthController::login');
$routes->post('/login',         'AuthController::loginProcess');
$routes->get('/logout',         'AuthController::logout');

// dashboard 
$routes->group('mahasiswa', ['filter' => 'auth:mahasiswa'], function ($routes) {
    $routes->get('dashboard', 'MahasiswaController::dashboard');
});

$routes->group('staff', ['filter' => 'auth:staff'], function ($routes) {
    $routes->get('dashboard', 'StaffController::dashboard');
});

$routes->group('pimpinan', ['filter' => 'auth:pimpinan'], function ($routes) {
    $routes->get('dashboard', 'PimpinanController::dashboard');
});