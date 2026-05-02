<?php
// namespace App\Helpers;
use Config\Services;



if (! function_exists('random_str')) {
    /**
     * Create a "Random" String
     *
     * @param	string	type of random string.  basic, alpha, alnum, numeric, nozero, unique, md5, encrypt and sha1
     * @param	int	number of characters
     * @return	string
     */
    function random_str($type = 'alnum', $len = 8)
    {
        switch ($type) {
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type) {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique': // todo: remove in 3.1+
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt': // todo: remove in 3.1+
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
    }
}

if (!function_exists('sekarang')) {

    function sekarang()
    {
        return date('Y-m-d H:i:s');
    }
}
if (!function_exists('get')) {

    function get($key = null)
    {
        $request = service('request');
        return $key !== null ? $request->getGet($key) : $request->getGet();
    }
}
if (!function_exists('post')) {

    function post($key = null)
    {
        $request = service('request');
        return $key !== null ? $request->getPost($key) : $request->getPost();
    }
}
if (!function_exists('str')) {

    function str($string = null)
    {
        $hasil  = preg_replace('/[^a-zA-Z0-9]/', '', $string);
        return $hasil;
    }
}

if (!function_exists('userid')) {
    function userid()
    {
        return session()->get('user_id');
    }
}

if (!function_exists('sekarang')) {
    /**
     * Fungsi untuk mendapatkan tanggal saat ini.
     *
     * @return string
     */
    function sekarang()
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('userdata')) {
    function userdata($where_data = null)
    {
        $db = db_connect();
        $builder = $db->table('tb_users')
            ->join('tb_users_groups', 'tb_users.id = tb_users_groups.user_id')
            ->join('tb_groups', 'tb_groups.id = tb_users_groups.group_id')
            ->select('tb_users.*, tb_users_groups.group_id, tb_groups.name as role');

        if ($where_data !== null) {
            $builder->where($where_data);
        } else {
            $builder->where('tb_users.id', userid());
        }

        $query = $builder->get();

        if ($query->getNumRows() == 1) {
            return $query->getRow();
        } elseif ($query->getNumRows() > 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }
}

if (!function_exists('howdy')) {
    function howdy($string = 'Guest')
    {
        $get_hour = date('H');
        if ($get_hour >= 0 && $get_hour < 12) {
            $output_string = 'Pagi';
        } elseif ($get_hour >= 12 && $get_hour < 15) {
            $output_string = 'Siang';
        } elseif ($get_hour >= 15 && $get_hour < 18) {
            $output_string = 'Sore';
        } elseif ($get_hour >= 18) {
            $output_string = 'Malam';
        }

        return ucwords('Selamat ' . $output_string . ', ' . $string);
    }
}

if (!function_exists('user_picture')) {
    function user_picture($image = 'no-images.jpg')
    {
        return base_url('uploads/users/' . $image);
    }
}

if (!function_exists('option')) {
    function option($param = null)
    {
        $db = db_connect();
        $builder = $db->table('tb_options');
        $builder->select('*')->where('option_name', $param);
        $query = $builder->get();
        return ($query->getNumRows() == 1) ? $query->getRowArray() : null;
    }
}

if (!function_exists('script_tag')) {
    function script_tag($src = '', $type = 'text/javascript', $index_page = false)
    {
        $src = (is_array($src)) ? $src : [$src];
        $link = '';
        foreach ($src as $item) {
            $link .= '<script src="' . esc($item, 'attr') . '" type="' . esc($type, 'attr') . '"></script>' . "\n";
        }
        return $link;
    }
}

if (!function_exists('time_span')) {
    function time_span($post_date = null, $distance = 2)
    {
        $post_date = (is_numeric($post_date)) ? date('Y-m-d H:i:s', $post_date) : $post_date;
        $date1 = new DateTime($post_date);
        $date2 = new DateTime(date('Y-m-d H:i:s'));
        $interval = $date1->diff($date2);

        if ($interval->days >= 5) {
            $show_date = date('d F Y H:i', strtotime($post_date));
        } else {
            $show_date = time_span(strtotime($post_date), time(), $distance) . ' ago';
        }

        return $show_date;
    }
}

if (!function_exists('avatar')) {
    function avatar($id)
    {
        $userdata = userdata(['tb_users.id' => $id]);
        if ($userdata) {
            if ($userdata->user_img && file_exists('assets/upload/users/thumbnail/' . $userdata->user_img)) {
                return base_url('assets/upload/users/thumbnail/' . $userdata->user_img);
            } else {
                $huruf = $userdata->user_fullname[0];
                $warna = sprintf('%06X', mt_rand(0, 0xFFFFFF));
                $link = 'https://placehold.co/40x40/' . $warna . '/ffffff?text=' . $huruf;
                return $link;
            }
        } else {
            $curl = file_get_contents('https://randomuser.me/api/');
            $result = json_decode($curl);
            return $result->results[0]->picture->large;
        }
    }
}




if (!function_exists('indo_phone')) {
    function indo_phone($string = '')
    {
        $output = preg_replace('/(0|\+?\d{2})(\d{7,8})/', '$2', $string);
        $split_detect = explode('620', $string);
        if (isset($split_detect[1])) {
            $output = str_replace('620', '', $string);
        }
        return '62' . $output;
    }
}
if (!function_exists('form_open')) {
    /**
     * Generate the opening form tag with optional CSRF token.
     *
     * @param string $action The URL the form will submit to
     * @param array $attributes Array of attributes for the form tag
     * @param array $hidden Array of hidden fields
     * @return string
     */
    function form_open(string $action = '', array $attributes = [], array $hidden = []): string
    {
        // Start building the form tag
        $form = '<form action="' . esc($action) . '"';

        // Add attributes to the form tag
        foreach ($attributes as $key => $value) {
            $form .= ' ' . esc($key) . '="' . esc($value) . '"';
        }

        // Set the method to POST by default
        if (!isset($attributes['method'])) {
            $form .= ' method="post"';
        }

        $form .= '>';

        // Add CSRF field if CSRF protection is enabled
        $csrfTokenName = 'csrf_app';
        $csrfTokenValue = csrf_hash();
        $form .= '<input type="hidden" name="' . esc($csrfTokenName) . '" value="' . esc($csrfTokenValue) . '">';

        // Add hidden fields
        foreach ($hidden as $name => $value) {
            $form .= '<input type="hidden" name="' . esc($name) . '" value="' . esc($value) . '">';
        }

        return $form;
    }
}
if (!function_exists('form_open_multipart')) {
    /**
     * Generate the opening form tag with multipart support.
     *
     * @param string $action The URL the form will submit to
     * @param array $attributes Array of attributes for the form tag
     * @param array $hidden Array of hidden fields
     * @return string
     */
    function form_open_multipart(string $action = '', array $attributes = [], array $hidden = []): string
    {
        // Start building the form tag
        $form = '<form action="' . esc($action) . '"';

        // Add attributes to the form tag
        foreach ($attributes as $key => $value) {
            $form .= ' ' . esc($key) . '="' . esc($value) . '"';
        }

        // Set the method to POST by default
        if (!isset($attributes['method'])) {
            $form .= ' method="post"';
        }

        // Add multipart/form-data for file uploads
        $form .= ' enctype="multipart/form-data">';

        // Add CSRF field
        $csrfTokenName = 'csrf_app';
        $csrfTokenValue = csrf_hash();
        $form .= '<input type="hidden" name="' . esc($csrfTokenName) . '" value="' . esc($csrfTokenValue) . '">';

        // Add hidden fields
        foreach ($hidden as $name => $value) {
            $form .= '<input type="hidden" name="' . esc($name) . '" value="' . esc($value) . '">';
        }

        return $form;
    }
}

if (!function_exists('form_hidden')) {
    /**
     * Generate a hidden form field.
     *
     * @param string $name The name of the hidden field
     * @param string $value The value of the hidden field
     * @return string
     */
    function form_hidden(string $name, string $value): string
    {
        return '<input type="hidden" name="' . esc($name) . '" value="' . esc($value) . '">';
    }
}

if (!function_exists('form_close')) {
    /**
     * Generate the closing form tag.
     *
     * @return string
     */
    function form_close(): string
    {
        return '</form>';
    }
}
if (!function_exists('db')) {
    /**
     * Generate the closing form tag.
     *
     * @return object
     */
    function db(): object
    {
        return \Config\Database::connect();
    }
}
if (!function_exists('format_str')) {
    /**
     * Generate the closing form tag.
     *
     * @return string
     */
    function format_str($string): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower(str_replace(' ', '',  $string)));
    }
}

if (!function_exists('model')) {
    function model($modelName)
    {
        $modelName = strtolower($modelName);
        $modelName = ucfirst($modelName);
        $namespace = 'App/Models/' . $modelName;
        if (class_exists($namespace)) {
            return new $namespace();
        } else {
            throw new \Exception("Model tidak ditemukan: {$namespace}");
        }
    }
}
if (!function_exists('rp')) {

    function rp($nominal = null)
    {

        return 'Rp. ' . number_format($nominal, 0, ',', '.');
    }
}

if (!function_exists('pagination')) {

    function pagination(
        string $baseUrl,
        int $total,
        int $limit
    ): string {

        if ($total <= $limit) {
            return '';
        }

        $request = service('request');

        // Current page (?page=)
        $currentPage = (int) ($request->getGet('page') ?? 1);
        $currentPage = $currentPage > 0 ? $currentPage : 1;

        $totalPages = (int) ceil($total / $limit);
        $url = base_url($baseUrl);
        // Ambil semua query kecuali page
        $query = $request->getGet();
        unset($query['page']);

        $queryString = http_build_query($query);
        $queryPrefix = $queryString ? '&' . $queryString : '';

        $html = '<div class="mt-3">';

        $html .= '
        <nav aria-label="Pagination">
            <ul class="pagination pagination-sm justify-content-center">';

        // Prev
        if ($currentPage > 1) {
            $html .= '
                <li class="page-item">
                    <a class="page-link" href="' . $url . '?page=' . ($currentPage - 1) . $queryPrefix . '">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>';
        }

        // Page numbers (±2)
        $start = max(1, $currentPage - 2);
        $end   = min($totalPages, $currentPage + 2);

        for ($i = $start; $i <= $end; $i++) {
            $active = $i === $currentPage ? 'active' : '';

            $html .= '
                <li class="page-item ' . $active . '">
                    <a class="page-link" href="' . $url . '?page=' . $i . $queryPrefix . '">' . $i . '</a>
                </li>';
        }

        // Next
        if ($currentPage < $totalPages) {
            $html .= '
                <li class="page-item">
                    <a class="page-link" href="' . $url . '?page=' . ($currentPage + 1) . $queryPrefix . '">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>';
        }

        $html .= '
            </ul>
        </nav>';

        // Info
        $html .= '
        <div class="text-center mt-2">
            <small class="text-muted">
                Halaman ' . $currentPage . ' dari ' . $totalPages . ' 
                (' . number_format($total) . ' total data)
            </small>
        </div>';

        $html .= '</div>';

        return $html;
    }
}


if (!function_exists('badge')) {
    function badge($status)
    {
        $text = ucfirst(str_replace('_', ' ', $status));

        $baseStyle = "
            display:inline-flex;
            align-items:center;
            padding:4px 10px;
            font-size:12px;
            font-weight:600;
            border-radius:999px;
            line-height:1;
            white-space:nowrap;
            letter-spacing:0.3px;
            border:1px solid;
        ";

        $status_green = ['1', 'verified', 'active', 'member', 'selesai', 'success', 'approved'];
        $status_red   = ['0', 'unverified', 'inactive', 'ditolak', 'rejected', 'cancel', 'dibatalkan', 'belum lunas', 'closed', 'pimpinan'];
        $status_blue  = ['proses', 'process', 'pinjam', 'progress', 'in progress','admin'];
        $status_yellow = ['pending', 'menunggu'];

        if (in_array($status, $status_green)) {
            $style = $baseStyle . "
                color:#0f5132;
                background:#d1e7dd;
                border-color:#badbcc;
            ";
        } elseif (in_array($status, $status_red)) {
            $style = $baseStyle . "
                color:#842029;
                background:#f8d7da;
                border-color:#f5c2c7;
            ";
        } elseif (in_array($status, $status_blue)) {
            $style = $baseStyle . "
                color:#084298;
                background:#cfe2ff;
                border-color:#b6d4fe;
            ";
        } elseif (in_array($status, $status_yellow)) {
            $style = $baseStyle . "
                color:#664d03;
                background:#fff3cd;
                border-color:#ffecb5;
            ";
        } else {
            $style = $baseStyle . "
                color:#41464b;
                background:#e2e3e5;
                border-color:#d3d6d8;
            ";
        }

        return "<span style=\"{$style}\">{$text}</span>";
    }
}


if (! function_exists('resizeImage')) {

    /**
     * Upload + resize image CI4
     *
     * @param string       $folder
     * @param UploadedFile $file
     * @param int|null     $width
     * @param int|null     $height
     * @param int          $maxSizeMB
     * @param array        $allowedMime
     *
     * @return string|false
     */
    function resizeImage(
        string $folder,
        object $file,
        int $maxSizeMB = 5,
        ?int $width = null,
        ?int $height = null,
    ) {
        $allowedMime = ['image/jpg', 'image/jpeg', 'image/png'];
        $data  = [
            'status' => true,
            'message' => 'berhasil',
        ];
        if (! $file->isValid() || $file->hasMoved()) {
            $data['message'] = 'Silahkan Upload File Yang Dibutuhkan';
            $data['status'] = false;
            return $data;
        }

        if (! in_array($file->getMimeType(), $allowedMime)) {
            $data['message'] = 'resizeImage: Invalid mime type - ' . $file->getMimeType();
            $data['status'] = false;
            return $data;
        }

        if ($file->getSizeByUnit('mb') > $maxSizeMB) {
            $data['message'] = 'Ukuran File Terlalu Besar, Max : ' . $maxSizeMB . ' MB';
            $data['status'] = false;
            return $data;
        }

        // =========================
        // PATH
        // =========================
        $basePath  = FCPATH . 'assets/upload/' . $folder . '/';
        $thumbPath = $basePath . 'thumbnail/';

        if (! is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        if (! is_dir($thumbPath)) {
            mkdir($thumbPath, 0777, true);
        }

        // =========================
        // MOVE FILE
        // =========================
        $ext  = $file->getClientExtension();
        $newName = bin2hex(random_bytes(16)) . '.' . $ext;
        $file->move($basePath, $newName);

        $sourcePath = $basePath . $newName;

        // =========================
        // RESIZE
        // =========================
        try {

            $image = Services::image();

            if ($width || $height) {
                $image
                    ->withFile($sourcePath)
                    ->resize($width ?? 0, $height ?? 0, true)
                    ->save($sourcePath, 70);
            }

            // =========================
            // THUMBNAIL
            // =========================
            $image
                ->withFile($sourcePath)
                ->resize(200, 200, true)
                ->save($thumbPath . $newName, 40);
            $data['file_name'] =  $newName;
        } catch (\Throwable $e) {
            $data['message'] = 'Rezise Gagal : ' . $e->getMessage();
            $data['status'] = false;
        }


        return $data;
    }
}



if (!function_exists('format_date')) {
    /**
     * Convert datetime dari format YmdHis ke Y-m-d H:i:s
     *
     * @param string|null $datetime
     * @return string|null
     */
    function format_date(?string $datetime): ?string
    {
        if (empty($datetime) || strlen($datetime) !== 14) {
            return null;
        }

        $dt = DateTime::createFromFormat('YmdHis', $datetime);

        return $dt ? $dt->format('Y-m-d H:i:s') : null;
    }
}

if (!function_exists('get_ip')) {

    function get_ip()
    {
        $services = [
            'https://api.ipify.org',
            'https://ifconfig.me/ip',
            'https://icanhazip.com'
        ];

        foreach ($services as $url) {
            $ip = @file_get_contents($url);
            if ($ip !== false) {
                $ip =  trim($ip);
            } else {
                $ip = $_SERVER['SERVER_ADDR'] ?? gethostbyname($_SERVER['HTTP_HOST']);
            }
        }
        // Fallback jika semua layanan di atas gagal
        return $ip;
    }
}
if (! function_exists('page_url')) {
    function page_url()
    {
        $uri = service('request')->getUri();

        // Ambil semua path setelah domain
        $path = $uri->getPath();

        // // Ambil query string (jika ada)
        // $query = $uri->getQuery();

        // Gabungkan jika query ada
        return $path;
    }
}
