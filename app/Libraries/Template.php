<?php

namespace App\Libraries;

use CodeIgniter\Config\Services;
use \IonAuth\Libraries\IonAuth; 

class Template
{
        protected $ionAuth;
        protected $uri;
        protected $session;
        protected $db;
        protected string $title = '';

  public function __construct()
    {
        $this->ionAuth = new IonAuth(); // buat instance langsung
        $this->uri =    Services::uri(); // buat instance langsung
        $this->session = Services::session(); // buat instance langsung
        $this->db  =  \Config\Database::connect(); // buat instance langsung
    }
    /**
     * Render halaman modular dengan layout utama.
     *
     * @param string $view  Path ke file view (tanpa ekstensi .php)
     * @param array  $data  Data yang dikirim ke view
     * @return string
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function render(string $layout, string $view, array $data = [])
    {
        $layoutPath = APPPATH . 'Views/' . $layout . '.php';
        $viewPath = ROOTPATH . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, 'Modules/'.$view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("❌ View tidak ditemukan: {$viewPath}");
        }

        extract($data);

        // Tangkap output dari view modul
        ob_start();
        include($viewPath);
        $content = ob_get_clean();

        // Ambil $title jika di-set di dalam view
        $data['content'] = $content;
        $data['title'] ??= $title ?? $this->title;

        // Render layout utama
        if (!file_exists($layoutPath)) {
            throw new \Exception("❌ Layout tidak ditemukan: {$layoutPath}");
        }

        ob_start();
        extract($data);
        include($layoutPath);
        return ob_get_clean();
    }

    public function view(string $view, array $data = [])
    {
        $viewPath = ROOTPATH . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, 'Modules/' . $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("❌ View tidak ditemukan: {$viewPath}");
        }

        $data['title'] ??= $this->title;

        ob_start();
        extract($data);
        include($viewPath);
        return ob_get_clean();
    }
}
