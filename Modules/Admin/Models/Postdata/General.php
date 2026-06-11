<?php

namespace Modules\Admin\Models\Postdata;

use CodeIgniter\Model;
use IonAuth\Libraries\IonAuth;
use IonAuth\Models\IonAuthModel;

class General extends Model
{
    private static $data = [
        'status'  => true,
        'message' => null,
    ];

    protected $ionAuth;
    protected $ionAuthModel;
    protected $request;
    protected $validation;

    public function __construct()
    {
        parent::__construct();
        $this->ionAuth = new IonAuth();
        $this->ionAuthModel = new IonAuthModel();
        $this->request = service('request');
        $this->validation = \Config\Services::validation();
        self::$data['csrf_data'] = csrf_hash();
    }

    public function update_option()
    {
        // Validasi      input
        $name = post('name');
        $value = post('value');

        if (self::$data['status']) {


            $this->db->table('tb_options')
                ->where('option_name', post('name'))
                ->update([
                    'option_desc1' => post('value')
                ]);

            self::$data['message'] = 'nilai '.$name.' berhasil diupdate' ;
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }

        function login_as_user()
    {
        Self::$data['status']         = true;
        Self::$data['heading']         = 'Successfully Login as a Member';
        Self::$data['type']             = 'success';


        if (!session('admin_userid')) {
            Self::$data['status']         = false;
            Self::$data['heading']         = 'Cannot Login.<br>Please ReLogin Admin';
        }


        if (Self::$data['status']) {

            //update status
            $array = array(
                'user_id' => post('user_id')
            );

            session()->set($array);

            Self::$data['message']    = 'Login Successfully, Click OK to Continue';
        }

        return Self::$data;
    }
}
