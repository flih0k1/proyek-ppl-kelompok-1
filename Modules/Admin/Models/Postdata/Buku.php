<?php

namespace Modules\Admin\Models\Postdata;

use CodeIgniter\Model;
use IonAuth\Libraries\IonAuth;
use IonAuth\Models\IonAuthModel;

class Buku extends Model
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

    public function add_buku()
    {
        // Validasi      input
        $this->validation->setRules([
            'buku_penulis'    => 'required',
            'buku_judul'    => 'required',
            'buku_stok'    => 'required',
            'buku_kategori_id'    => 'required',
            'buku_rak_id'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $datacover = $this->request->getFile('buku_cover');
        $coverdata = null;
        if ($datacover && $datacover->isValid() && !$datacover->hasMoved()) {
            $coverdata = resizeImage('cover', $datacover, 10);
        }

        $cover = ($coverdata && isset($coverdata['file_name']))
            ? $coverdata['file_name']
            : null;
        $data_insert = [
            'buku_penulis' => post('buku_penulis'),
            'buku_judul' => post('buku_judul'),
            'buku_stok' => post('buku_stok'),
            'buku_kategori_id' => post('buku_kategori_id'),
            'buku_rak_id' => post('buku_kategori_id'),
            'buku_penerbit' => post('buku_penerbit'),
            'buku_tahun' => post('buku_tahun'),
            'buku_desc' => post('buku_desc'),
            'buku_isbn' =>  post('buku_isbn'),
            'buku_status' =>  post('buku_status'),
            'buku_cover' =>  $cover,
            'buku_code' => bin2hex(random_bytes(16)),
            'buku_date_add' => sekarang(),
        ];

        if (self::$data['status']) {

            $this->db->table('tb_buku')->insert($data_insert);
            if (post('code')) {
                $this->db->table('tb_request')->where('request_code', post('code'))->update([
                    'request_status' => 'done',
                    'request_balasan' => 'Permintaan selesai, buku sudah tersedia di perpustakaan',
                ]);
            }

            self::$data['message'] = 'Buku Baru Berhasil Ditambahkan';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
    public function update_buku()
    {
        // Validasi      input
        $this->validation->setRules([
            'buku_penulis'    => 'required',
            'buku_judul'    => 'required',
            'buku_stok'    => 'required',
            'buku_kategori_id'    => 'required',
            'buku_rak_id'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cekbuku = $this->db->table('tb_buku')->where('buku_code', post('code'))->get()->getRow();
        if (!$cekbuku) {
            self::$data['status']  = false;
            self::$data['message'] = 'Buku Tidak Ditemukan.';
        }

        $datacover = $this->request->getFile('buku_cover');
        $coverdata = null;
        if ($datacover && $datacover->isValid() && !$datacover->hasMoved()) {
            $coverdata = resizeImage('cover', $datacover, 10);
        }

        $cover = ($coverdata && isset($coverdata['file_name']))
            ? $coverdata['file_name']
            : null;
        $data_update = [
            'buku_penulis' => post('buku_penulis'),
            'buku_judul' => post('buku_judul'),
            'buku_stok' => post('buku_stok'),
            'buku_kategori_id' => post('buku_kategori_id'),
            'buku_rak_id' => post('buku_kategori_id'),
            'buku_penerbit' => post('buku_penerbit'),
            'buku_tahun' => post('buku_tahun'),
            'buku_desc' => post('buku_desc'),
            'buku_isbn' =>  post('buku_isbn'),
            'buku_status' =>  post('buku_status'),

        ];
        if ($cover) {
            $data_update['buku_cover'] = $cover;
        }
        if (self::$data['status']) {

            $this->db->table('tb_buku')->where('buku_code', post('code'))->update($data_update);

            self::$data['message'] = 'Data Buku Berhasil Diperbaharui';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
    public function delete_buku()
    {
        // Validasi      input
        $this->validation->setRules([
            'code'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cekbuku = $this->db->table('tb_buku')->where('buku_code', post('code'))->get()->getRow();
        if (!$cekbuku) {
            self::$data['status']  = false;
            self::$data['message'] = 'Buku Tidak Ditemukan.';
        }

        if (self::$data['status']) {
            $cover = $cekbuku->buku_cover;
            $original = FCPATH . 'assets/upload/cover/' . $cover;
            $thumb    = FCPATH . 'assets/upload/cover/thumbnail/' . $cover;

            if (is_file($original)) unlink($original);
            if (is_file($thumb)) unlink($thumb);

            $this->db->table('tb_buku')->where('buku_code', post('code'))->delete();

            self::$data['message'] = 'Buku Berhasil Dihapus';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }

    function add_kategori()
    {

        if (Self::$data['status']) {
            $nama = post('kategori_nama');
            foreach ($nama as $n) {
                $this->db->table('tb_kategori_buku')->insert([
                    'kategori_nama'         => $n,
                ]);
            }
            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "Kategori Baru Berhasil Ditambahkan";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }
    function update_kategori()
    {
        $this->validation->setRules([
            'kategori_nama'    => 'required',
        ]);
        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_kategori_buku')->where('kategori_id', post('id'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Kategori Tidak Ditemukan';
        }
        $data_update = [
            'kategori_nama' => post('kategori_nama'),
        ];
        if (Self::$data['status']) {

            $this->db->table('tb_kategori_buku')->where('kategori_id', post('id'))->update($data_update);

            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "Kategori Berhasil Diperbarui";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }

    function delete_kategori()
    {
        $this->validation->setRules([
            'id'              => 'required',
        ]);
        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_kategori_buku')->where('kategori_id', post('id'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data kategori Tidak Ditemukan';
        }

        if (Self::$data['status']) {
            $this->db->table('tb_buku')->where('buku_kategori_id', post('id'))->update(['buku_kategori_id' => null]);


            $this->db->table('tb_kategori_buku')->where('kategori_id', post('id'))->delete();

            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "Kategori Berhasil Dihapus";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }

    function add_rak()
    {

        if (Self::$data['status']) {
            $nama = post('rak_nama');
            foreach ($nama as $n) {
                $this->db->table('tb_rak_buku')->insert([
                    'rak_nama'         => $n,
                ]);
            }
            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "rak Baru Berhasil Ditambahkan";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }
    function update_rak()
    {
        $this->validation->setRules([
            'rak_nama'    => 'required',
        ]);
        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_rak_buku')->where('rak_id', post('id'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Rak Tidak Ditemukan';
        }
        $data_update = [
            'rak_nama' => post('rak_nama'),
        ];
        if (Self::$data['status']) {

            $this->db->table('tb_rak_buku')->where('rak_id', post('id'))->update($data_update);

            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "rak Berhasil Diperbarui";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }

    function delete_rak()
    {
        $this->validation->setRules([
            'id'              => 'required',
        ]);
        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_rak_buku')->where('rak_id', post('id'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Rak Tidak Ditemukan';
        }

        if (Self::$data['status']) {
            $this->db->table('tb_buku')->where('buku_rak_id', post('id'))->update(['buku_rak_id' => null]);

            $this->db->table('tb_rak_buku')->where('rak_id', post('id'))->delete();

            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "Rak Berhasil Dihapus";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }

    function approve_peminjaman()
    {
        $this->validation->setRules([
            'code'              => 'required',
        ]);
        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_peminjaman')->where('peminjaman_code', post('code'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Tidak Ditemukan';
        }

        if (Self::$data['status']) {
            $this->db->table('tb_peminjaman')->where('peminjaman_code', post('code'))->update(['peminjaman_status' => 'pinjam']);

            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "Peminjaman berhasil dikonfirmasi";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }
    function terima_buku()
    {
        $this->validation->setRules([
            'code'              => 'required',
        ]);
        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_peminjaman')->where('peminjaman_code', post('code'))->where('peminjaman_status', 'kembalikan')->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Tidak Ditemukan';
        }

        $tglJatuh   = $cek->getRow()->peminjaman_date_end;
        $tglKembali = date('Y-m-d 00:00:00');

        $terlambat = 0;

        if ($tglJatuh) {
            $jatuh   = strtotime($tglJatuh);
            $kembali = strtotime($tglKembali);

            if ($kembali > $jatuh) {
                $selisih = $kembali - $jatuh;
                $terlambat = floor($selisih / (60 * 60 * 24)); // konversi ke hari
            }
        }

        $denda = $peminjaman->peminjaman_denda ?? ($terlambat * option('denda')['option_desc1']);

        if (Self::$data['status']) {
            $this->db->table('tb_peminjaman')->where('peminjaman_code', post('code'))->update([
                'peminjaman_status' => 'selesai',
                'peminjaman_date_kembali' => sekarang(),
                'peminjaman_denda' => $denda,
                'peminjaman_status_bayar' => 'belum'
            ]);

            Self::$data['heading']      = "Berhasil";
            Self::$data['message']      = "Peminjaman berhasil dikonfirmasi";
            Self::$data['type']         = "success";
        } else {
            Self::$data['heading']      = "Gagal";
            Self::$data['type']         = "error";
        }
        return Self::$data;
    }
    public function tanggapi_request()
    {
        // Validasi      input
        $this->validation->setRules([
            'request_status'    => 'required',
            'request_balasan'    => 'required',
            'code'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cek = $this->db->table('tb_request')->where('request_code', post('code'))->get();
        if ($cek->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Tidak Ditemukan';
        }
        $data_insert = [
            'request_status' => post('request_status'),
            'request_balasan' => post('request_balasan'),
        ];
        if (self::$data['status']) {

            $this->db->table('tb_request')->where('request_code', post('code'))->update($data_insert);

            self::$data['message'] = 'Berhasil Menanggapi Request Buku';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
    }
    public function add_maintenance()
    {
        // Validasi      input
        $this->validation->setRules([
            'mt_buku_id'    => 'required',
            'mt_tipe'    => 'required',
            'mt_desc'    => 'required',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            self::$data['status']  = false;
            self::$data['message'] = implode('<br/>', $this->validation->getErrors());
        }
        $cekbuku = $this->db->table('tb_buku')->where('buku_id', post('mt_buku_id'))->get();
        if ($cekbuku->getNumRows() == 0) {
            self::$data['status']  = false;
            self::$data['message'] = 'Data Buku Tidak Ditemukan';
        }
        $data_insert = [
            'mt_buku_id' => post('mt_buku_id'),
            'mt_tipe' => post('mt_tipe'),
            'mt_desc' => post('mt_desc'),
            'mt_date_add' => sekarang(),
            'mt_date_start' => sekarang(),
            'mt_code' => bin2hex(random_bytes(16)),

        ];
        if (self::$data['status']) {

            $this->db->table('tb_maintenance')->insert($data_insert);

            self::$data['message'] = 'Data Perbaikan Baru Berhasil Ditambahkan';
            self::$data['heading'] = 'Sukses';
            self::$data['type']    = 'success';
        } else {
            self::$data['heading'] = 'Gagal';
            self::$data['type']    = 'error';
        }

        return self::$data;
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
            'mt_status' => 'selesai',
            'mt_date_selesai' => sekarang(),
        ];
        if (self::$data['status']) {

            $this->db->table('tb_maintenance')->where('mt_code', post('code'))->update($data_update);

            self::$data['message'] = 'Perbaikan Buku Berhasil Diselesaikan';
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
