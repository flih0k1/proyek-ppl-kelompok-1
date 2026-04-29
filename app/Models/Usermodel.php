<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model

{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Ambil semua user
     */
    public function getUsers()
    {
        return $this->db->table('tb_users')->get()->getResultArray();
    }
    public function total_pinjam($userid)
    {
        return $this->db->table('tb_peminjaman')->where('peminjaman_userid', $userid)->get()->getNumRows() ?? 0;
    }
    public function total_denda($userid)
    {
        return $this->db->table('tb_peminjaman')->selectSum('peminjaman_denda')->where('peminjaman_userid', $userid)->get()->getRow()->peminjaman_denda ?? 0;
    }
    public function buku_outstok($bukuid)
    {
        $mt = $this->db->table('tb_maintenance')->where('mt_status', 'process')->where('mt_buku_id', $bukuid)->get()->getNumRows() ?? 0;
        $pinjam =  $this->db->table('tb_peminjaman')->where('peminjaman_status', 'pinjam')->where('peminjaman_buku_id', $bukuid)->get()->getNumRows() ?? 0;
        return $mt + $pinjam;
    }
    // public function data_terlambat($userid)
    // {
    //     $pinjam =  $this->db->table('tb_peminjaman')->where('peminjaman_userid', $userid)->where('peminjaman_status', 'pinjam')->get()->getResult();
    //     $data = [];
    //     foreach ($pinjam as $key => $value) {
    //         $data['id'][] = $value->peminjaman_id;

    //         $tgl_berakhir = $value->peminjaman_date_end;
    //         $tgl_sekarang = date('Y-m-d');

    //         $datetime1 = new \DateTime($tgl_berakhir);
    //         $datetime2 = new \DateTime($tgl_sekarang);
    //         $interval = $datetime1->diff($datetime2);

    //         if ($tgl_sekarang < $tgl_kembali) {
    //             $data[] = $interval->days;
    //         } else {
    //             $data[] = 0;
    //         }
    //     }


    // }

}
