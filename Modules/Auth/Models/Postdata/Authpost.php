<?php

namespace Modules\Auth\Models\Postdata;

use CodeIgniter\Model;
use IonAuth\Libraries\IonAuth;
use IonAuth\Models\IonAuthModel;

class Authpost extends Model
{
    private static $data = [
        'status'  => true,
        'message' => null,
    ];

    protected $ionAuth;
    protected $ionAuthModel;
    protected $request;
    protected $validation;
    protected $email;

    public function __construct()
    {
        parent::__construct();
        // Inisialisasi IonAuth dan IonAuthModel
        $this->ionAuth = new IonAuth();
        $this->ionAuthModel = new IonAuthModel();
        $this->request = service('request');
        $this->validation = \Config\Services::validation();
        self::$data['csrf_data'] = csrf_hash();
        $this->email = \Config\Services::email();
    }

    public function do_login()
    {
        $request = $this->request;
        $session = session();

        // Validasi form inputs
        $this->validation->setRules([
            'authentication_id' => 'required',
            'authentication_password' => 'required',
        ]);

        if (userdata(['username' => post('authentication_id')]) && userdata(['username' => post('authentication_id')])->active == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Akun Anda Tidak Aktif';
            return self::$data;
        }

        if (!$this->validation->withRequest($request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        } else {
            // Cek login
            $do_login = $this->ionAuth->login(post('authentication_id'), post('authentication_password'), true);
            if (!$do_login) {
                self::$data['status']  = false;
                self::$data['message'] = $this->ionAuth->errors();
            }

            if (self::$data['status']) {
                // Ambil grup user
                $user_group = $this->ionAuth->getUsersGroups()->getRow();

                if ($user_group && $user_group->id == 1) {
                    // Set session admin
                    $session->set('admin_userid', $this->ionAuth->user()->row()->id);
                }

                self::$data['message'] = 'Anda telah berhasil login. Klik OK untuk melanjutkan';
                self::$data['heading'] = 'Sukses';
                self::$data['type']    = 'success';
            } else {
                self::$data['heading'] = 'Gagal';
                self::$data['type']    = 'error';
            }
        }

        return self::$data;
    }
    function login_back_admin()
    {

        Self::$data['heading']         = 'Login Admin Berhasil';
        Self::$data['type']             = 'success';

        if (!session('admin_userid')) {
            Self::$data['status']         = false;
            Self::$data['message']         = 'Not allowed';
        }

        if (Self::$data['status']) {

            //update status
            $array = array(
                'user_id' => session('admin_userid')
            );
            session()->set($array);
            Self::$data['message']    = 'Berhasil login kembali menjadi menjadi Admin';
        } else {

            Self::$data['heading']         = 'Failed';
            Self::$data['type']             = 'error';
        }

        return Self::$data;
    }
    public function do_register()
    {
        $request = $this->request;

        // Validasi input
        $this->validation->setRules([
            'user_fullname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama wajib diisi'
                ]
            ],
            'user_username' => [
                'rules' => 'required|is_unique[tb_users.username]',
                'errors' => [
                    'required'  => 'Username wajib diisi',
                    'is_unique' => 'Username sudah terdaftar'
                ]
            ],
            'user_email' => [
                'rules' => 'required|valid_email|is_unique[tb_users.email]',
                'errors' => [
                    'required'     => 'Email wajib diisi',
                    'valid_email'  => 'Format email tidak valid',
                    'is_unique'  => 'Email Sudah Terdaftar',
                ]
            ],
            'user_phone' => [
                'rules' => 'required',
                'errors' => [
                    'required'  => 'Nomor Whatsapp wajib diisi',
                ]
            ],
            'user_password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password wajib diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
        ]);


        if (!$this->validation->withRequest($request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br>', $this->validation->getErrors());
        }


        // Jika validasi berhasil, masukkan data ke database
        $userpass = post('user_password');
        $useremail = post('user_email');
        $username = post('user_username');
        $usertipe = post('user_tipe');
        // Data tambahan untuk disimpan
        $code = bin2hex(random_bytes(16));
        $additional_data = [
            'user_fullname' => post('user_fullname'),
            'user_passtext' => post('user_password'),
            'user_phone' => post('user_phone'),
            'user_code' => $code,
        ];
        if (self::$data['status']) {

            $this->ionAuth->register($username, $userpass, $useremail, $additional_data, [$usertipe]);

            self::$data['message'] = 'Anda telah berhasil Daftar. Klik OK untuk melanjutkan';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }

    function update_profile()
    {
        $cekdatauser = $this->db->table('tb_users')->where('user_code', post('code'))->get();
        if ($cekdatauser->getNumRows() == 0) {
            Self::$data['status']       = false;
            Self::$data['message']      = 'Data User Tidak Valid!';
        }
        $cekdatauser = $this->db->table('tb_users')
            ->where('username', post('user_username'))
            ->where('user_code !=', post('code'))->get();
        if ($cekdatauser->getNumRows() != 0) {
            Self::$data['status']       = false;
            Self::$data['message']      = 'Data User Sudah Terdaftar!';
        }

        $this->validation->setRules([
            'user_fullname'     => 'required',
            'user_email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required'     => 'Email wajib diisi',
                    'valid_email'  => 'Format email tidak valid',
                ]
            ],
            'code'              => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $dataimages = $this->request->getFile('user_img');
        $imagesdata = null;
        if ($dataimages && $dataimages->isValid() && !$dataimages->hasMoved()) {
            $imagesdata = resizeImage('users', $dataimages, 10);
        }

        $images = ($imagesdata && isset($imagesdata['file_name']))
            ? $imagesdata['file_name']
            : null;

        $data_update = [
            'user_fullname'  => post('user_fullname'),
            'user_phone'     => post('user_phone'),
            'email'     => post('user_email'),
            'username'       => post('user_username'),
        ];
        if ($images) {
            $data_update['user_img'] = $images;
        }
        if (Self::$data['status']) {
            $this->db->table('tb_users')->where('user_code', post('code'))->update($data_update);

            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "Data Profile Berhasil Diperbarui";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }

        return Self::$data;
    }
    function update_password()
    {

        $cekdatauser = $this->db->table('tb_users')->where('user_code', post('code'))->get();
        if ($cekdatauser->getNumRows() == 0) {
            Self::$data['status']       = false;
            Self::$data['message']      = 'Data User Tidak Valid!';
        }
        $this->validation->setRules([
            'password' => 'required|min_length[6]',
            'code' => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status'] = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }

        if (self::$data['status']) {
           $tes = $this->ionAuthModel->changePassword($cekdatauser->getRow()->username, $cekdatauser->getRow()->user_passtext, post('password'));

            $this->db->table('tb_users')->where('user_code', post('code'))->update(['user_passtext' => post('password')]);

            self::$data['heading'] = 'Berhasil';
            self::$data['message'] = 'Password berhasil diperbarui';
            self::$data['type'] = 'success';
            self::$data['res'] = $tes;
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type'] = 'error';
        }

        return self::$data;
    }
    function update_status()
    {

        $cekdatauser = $this->db->table('tb_users')->where('user_code', post('code'))->get();
        if ($cekdatauser->getNumRows() == 0) {
            Self::$data['status']       = false;
            Self::$data['message']      = 'Data User Tidak Valid!';
        }
        $this->validation->setRules([
            'code' => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status'] = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $userdata = $cekdatauser->getRow();
        $status = ($userdata->active == 1) ? 0 : 1;
        $msg = ($status == 1) ? 'Diaktifkan' : 'Dinonaktifkan';
        if (self::$data['status']) {
            $this->db->table('tb_users')->where('user_code', post('code'))->update(['active' => $status]);

            self::$data['heading'] = 'Berhasil';
            self::$data['message'] = 'Member berhasil ' . $msg;
            self::$data['type'] = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type'] = 'error';
        }

        return self::$data;
    }
    function update_password_user()
    {
        if (!$this->ionAuth->verifyPassword(post('current_password'), userdata()->password)) {
            self::$data['status'] = false;
            self::$data['message'] = 'Password lama tidak benar';
        }

        $this->validation->setRules([
            'current_password' => 'required|min_length[6]',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status'] = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }

        if (self::$data['status']) {
            $this->ionAuthModel->changePassword(userdata()->username, post('current_password'), post('new_password'));
            $this->db->table('tb_users')->where('id', userid())->update(['user_passtext' => post('new_password')]);

            self::$data['heading'] = 'Berhasil';
            self::$data['message'] = 'Password berhasil diperbarui';
            self::$data['type'] = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type'] = 'error';
        }

        return self::$data;
    }

}
