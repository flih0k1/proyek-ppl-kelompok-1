<?php

namespace Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Template;
use \IonAuth\Libraries\IonAuth;

class Auth extends BaseController
{
    protected $session;
    protected $ionAuth;
    protected $template;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->ionAuth = new IonAuth();
        $this->template = new Template();
        $this->db  =  \Config\Database::connect(); // buat instance langsung

    }

    public function login($page = 'login')
    {

        if ($this->ionAuth->loggedIn() && $this->ionAuth->isAdmin()) {
            return redirect()->to('admin/dashboard');
        } elseif ($this->ionAuth->loggedIn()) {
            if (userdata()->group_id == 2) {
                return redirect()->to('dashboard');
            } elseif (userdata()->group_id == 3) {
                return redirect()->to('pimpinan/dashboard');
            }
        }
        $data = [];
        $path = 'Auth/Views/Pages/' . $page;
        return $this->template->render('template_auth', $path, $data);
    }

    public function register($page = 'signup')
    {
        $path = ROOTPATH . 'Modules/Auth/Views/Pages/' . $page . '.php';

        if (!is_file($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }

        $data = [];
        $path = 'Auth/Views/pages/' . $page;
        return $this->template->render('template_auth', $path, $data);
    }
    public function logout()
    {
        $this->ionAuth->logout();
        $this->session->destroy();
        return $this->response->redirect(site_url('login'));
    }

    public function modal($filename = null)
    {
        // if (!$this->ionAuth->loggedIn()) {
        //     throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
        // }

        // Sanitasi input
        $filename = trim($filename ?? '', '/');
        $filename = str_replace(['..', '\\'], '', $filename);

        if ($filename === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Path fisik (untuk validasi file)
        $filePath = ROOTPATH . 'Modules/Auth/Views/Modals/' . $filename . '.php';

        if (!is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($filename);
        }

        // Ambil data dari service (optional)
        // $service = $this->resolveService();
        // $data = $service ? $service->handle($filename) : [];
        $data = [];
        $modalname = 'Auth\\Views\\Modals\\' . $filename;
        // Render view (INI YANG BENAR)
        return  $this->template->view($modalname, $data);
    }
    public function getdata($model, $method)
    {

        // Bersihkan nama model dan method untuk keamanan
        $model = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $model));
        $method = preg_replace('/[^a-zA-Z0-9_]/', '', $method);

        // Tentukan namespace model
        $modelName = '\\Modules\\Auth\\Models\\Getdata\\' . $model;

        // Cek apakah model tersebut ada
        if (!file_exists(ROOTPATH . 'Modules/Auth/Models/Getdata/' . $model . '.php')) {
            return $this->response->setJSON([
                'error' => 'Model tidak ditemukan',
                'nama' => $modelName,
                'tes' => class_exists($modelName)
            ], 404);
        }

        // Inisialisasi model
        $modelInstance = new $modelName();

        // Cek apakah method tersebut ada di model
        if (!method_exists($modelInstance, $method)) {
            return $this->response->setJSON([
                'error' => 'Method tidak ditemukan',
                'nama' => $modelInstance,
            ], 404);
        }

        // Panggil method di model dengan argumen dari POST
        $requestData = $this->request->getGet();
        $result = call_user_func_array([$modelInstance, $method], [$requestData]);

        // Kembalikan hasil sebagai JSON
        return $this->response->setJSON($result);
    }
    public function postdata($model, $method)
    {
        // Bersihkan nama model dan method untuk keamanan
        $model = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $model));
        $method = preg_replace('/[^a-zA-Z0-9_]/', '', $method);

        // Tentukan namespace model
        $modelName = '\\Modules\\Auth\\Models\\Postdata\\' . $model;

        // Cek apakah model tersebut ada
        if (!file_exists(ROOTPATH . 'Modules/Auth/Models/Postdata/' . $model . '.php')) {
            return $this->response->setJSON([
                'error' => 'Model tidak ditemukan',
                'nama' => $modelName,
                'tes' => class_exists($modelName),
            ], 404);
        }

        // Inisialisasi model
        $modelInstance = new $modelName();

        // Cek apakah method tersebut ada di model
        if (!method_exists($modelInstance, $method)) {
            return $this->response->setJSON([
                'error' => 'Method tidak ditemukan',
                'nama' => $modelInstance,
            ], 404);
        }

        $requestData = $this->request->getPost();
        $result = call_user_func_array([$modelInstance, $method], [$requestData]);

        return $this->response->setJSON($result);
    }
}
