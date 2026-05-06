<?php

namespace Modules\Member\Models\Postdata;

use CodeIgniter\Model;
use IonAuth\Libraries\IonAuth;
use IonAuth\Models\IonAuthModel;

class Pinjam extends Model
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

    public function pinjam_buku()
    {
        // Validasi      input
        $this->validation->setRules([
            'peminjaman_date_start'    => 'required',
            'peminjaman_date_end'    => 'required',
            'peminjaman_buku_id'    => 'required',
            'peminjaman_durasi'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $data_insert = [
            'peminjaman_userid' => userid(),
            'peminjaman_buku_id' => post('peminjaman_buku_id'),
            'peminjaman_date_start' => post('peminjaman_date_start'),
            'peminjaman_date_start' => post('peminjaman_date_start'),
            'peminjaman_date_end' => post('peminjaman_date_end'),
            'peminjaman_durasi' => post('peminjaman_durasi'),
            'peminjaman_status' => 'pending',
            'peminjaman_desc' => post('peminjaman_desc'),
            'peminjaman_code' => bin2hex(random_bytes(16)),
            'peminjaman_date_add' => sekarang(),
        ];
        if (self::$data['status']) {

            $this->db->table('tb_peminjaman')->insert($data_insert);

            self::$data['message'] = 'Pinjam Buku Berhasil';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
    public function delete_pinjam()
    {
        // Validasi      input
        $this->validation->setRules([
            'code'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cekpinjaman = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'pending')->where('peminjaman_code', post('code'))->get()->getRow();
        if (!$cekpinjaman) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Pinjaman Buku Tidak Ditemukan.';
        }

        if (self::$data['status']) {

            $this->db->table('tb_peminjaman')->where('peminjaman_code', post('code'))->delete();

            self::$data['message'] = 'Peminjaman Buku Berhasil Dibatalkan';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
    public function kembalikan()
    {
        // Validasi      input
        $this->validation->setRules([
            'code'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cekpinjaman = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'pinjam')->where('peminjaman_code', post('code'))->get()->getRow();
        if (!$cekpinjaman) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Pinjaman Buku Tidak Ditemukan.';
        }

        if (self::$data['status']) {
            $this->db->table('tb_peminjaman')->where('peminjaman_code', post('code'))->update([
                'peminjaman_status' => 'kembalikan',
            ]);

            self::$data['message'] = 'Peminjaman Buku Berhasil Dikembalikan';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
    public function bayar_denda()
    {
        // Validasi      input
        $this->validation->setRules([
            'code'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cekpinjaman = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'selesai')->where('peminjaman_code', post('code'))->get()->getRow();
        if (!$cekpinjaman) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Pinjaman Buku Tidak Ditemukan.';
        }

        if (self::$data['status']) {
            $this->db->table('tb_peminjaman')->where('peminjaman_code', post('code'))->update([
                'peminjaman_status_bayar' => 'lunas',
            ]);

            self::$data['message'] = 'Denda berhasil dibayar';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
    public function request_buku()
    {
        // Validasi      input
        $this->validation->setRules([
            'request_buku_judul'    => 'required',
            'request_buku_penulis'    => 'required',
            'request_desc'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $data_insert = [
            'request_userid' => userid(),
            'request_buku_judul' => post('request_buku_judul'),
            'request_buku_penulis' => post('request_buku_penulis'),
            'request_buku_tahun' => post('request_buku_tahun'),
            'request_desc' => post('request_desc'),
            'request_status' => 'pending',
            'request_code' => bin2hex(random_bytes(16)),
            'request_date_add' => sekarang(),
        ];
        if (self::$data['status']) {

            $this->db->table('tb_request')->insert($data_insert);

            self::$data['message'] = 'Request Buku Berhasil';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
}
