<?php

namespace Modules\Pimpinan\Models\Postdata;

use CodeIgniter\Model;
use IonAuth\Libraries\IonAuth;
use IonAuth\Models\IonAuthModel;

class Maintenance extends Model
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

    public function update_maintenance()
    {
        // Validasi      input
        $this->validation->setRules([
            'code'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_maintenance')->where('mt_code', post('code'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Pemeliharaan Tidak Ditemukan';
        }
        $data_update = [
            'mt_status' => post('status'),
            'mt_date_selesai' => sekarang(),
        ];
        if (self::$data['status']) {

            $this->db->table('tb_maintenance')->where('mt_code', post('code'))->update($data_update);
            $status = (post('status') == 'selesai') ? 'selesaikan' : 'konfirmasi';
            self::$data['message'] = 'Perbaikan Buku Berhasil Di'.$status;
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
        public function delete_maintenance()
    {
        // Validasi      input
        $this->validation->setRules([
            'code'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_maintenance')->where('mt_code', post('code'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Pemeliharaan Tidak Ditemukan';
        }

        if (self::$data['status']) {

            $this->db->table('tb_maintenance')->where('mt_code', post('code'))->delete();

            self::$data['message'] = 'Perbaikan Buku Berhasil Dihapus';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
}
