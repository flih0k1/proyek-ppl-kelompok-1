<?php

namespace Modules\Admin\Controllers;

use CodeIgniter\Controller;
use \IonAuth\Libraries\IonAuth;
use App\Libraries\Template;

class Admin extends Controller
{
    protected $session;
    protected $ionAuth;
    protected $template;
    protected $db;
    protected $modul;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->ionAuth = new IonAuth();
        $this->template = new Template();
        $this->db  =  \Config\Database::connect(); // buat instance langsung
        $this->modul  =  'Admin';
    }

    // view
    public function index($page = 'dashboard')
    {
        if (!$this->ionAuth->loggedIn() || !$this->ionAuth->isAdmin()) {
            return redirect()->to('login');
        }
        $path = ROOTPATH . 'Modules/'.$this->modul.'/Views/Pages/' . $page . '.php';
        if (!is_file($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }
        $data = [
            'title' => ucfirst($page),
            'page' => $page,
            'userid' => userid(),
            'userdata' => userdata()
        ];
        $path = $this->modul.'/Views/Pages/' . $page;
        return $this->template->render('template', $path, $data);
    }
    // modal
    public function modal($filename = null)
    {
        if (!$this->ionAuth->isAdmin()) {
            return $this->response->setStatusCode(403)->setBody('<div class="alert alert-danger">Akses Ditolak.</div>');
        }

        // Sanitasi input
        $filename = trim($filename ?? '', '/');
        $filename = str_replace(['..', '\\'], '', $filename);

        if ($filename === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Path fisik (untuk validasi file)
        $filePath = ROOTPATH . 'Modules/'.$this->modul.'/Views/Modals/' . $filename . '.php';

        if (!is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($filename);
        }

        // Ambil data dari service (optional)
        // $service = $this->resolveService();
        // $data = $service ? $service->handle($filename) : [];
        $data = [
        ];
        $modalname = $this->modul.'\\Views\\Modals\\'.$filename;
        return  $this->template->view($modalname, $data);
    }
    public function getdata($model, $method)
    {

        if (!$this->ionAuth->isAdmin()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses Ditolak.'], 403);
        }
        // Bersihkan nama model dan method untuk keamanan
        $model = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $model));
        $method = preg_replace('/[^a-zA-Z0-9_]/', '', $method);

        // Tentukan namespace model
        $modelName = '\\Modules\\'.$this->modul.'\\Models\\Getdata\\' . $model;

        // Cek apakah model tersebut ada
        if (!class_exists($modelName)) {
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
        if (!$this->ionAuth->isAdmin()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses Ditolak.'], 403);
        }
        // Bersihkan nama model dan method untuk keamanan
        $model = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $model));
        $method = preg_replace('/[^a-zA-Z0-9_]/', '', $method);

        // Tentukan namespace model
        $modelName = '\\Modules\\'.$this->modul.'\\Models\\Postdata\\' . $model;

        // Cek apakah model tersebut ada
        if (!class_exists($modelName)) {
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

        $requestData = $this->request->getPost();
        $result = call_user_func_array([$modelInstance, $method], [$requestData]);

        return $this->response->setJSON($result);
    }
}
