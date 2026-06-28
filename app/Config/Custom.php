<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class Custom extends BaseConfig
{
    public string $nama = 'Perpustakaan Al-Azhar';

    public array $menu_admin = [

        [
            'heading' => 'MAIN',
            'data' => [
                [
                    'title'     => 'Dashboard',
                    'icon'      => 'bi bi-speedometer2',
                    'url'       => 'admin/dashboard',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'KOLEKSI BUKU',
            'data' => [
                [
                    'title'     => 'Data Buku',
                    'icon'      => 'bi bi-book',
                    'url'       => 'admin/data-buku',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Kategori Buku',
                    'icon'      => 'bi bi-tags',
                    'url'       => 'admin/data-kategori-buku',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Penulis Buku',
                    'icon'      => 'bi bi-person',
                    'url'       => 'admin/data-penulis',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Penerbit Buku',
                    'icon'      => 'bi bi-building',
                    'url'       => 'admin/data-penerbit',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Rak Buku',
                    'icon'      => 'bi bi-archive',
                    'url'       => 'admin/data-rak-buku',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Maintenance',
                    'icon'      => 'bi bi-wrench',
                    'url'       => 'admin/data-maintenance',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'SIRKULASI',
            'data' => [
                [
                    'title'     => 'Peminjaman',
                    'icon'      => 'bi bi-bookmark-plus',
                    'url'       => 'admin/data-peminjaman',
                    'notif'     => 1,
                    'typenotif' => 'peminjaman',
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Tagihan Denda',
                    'icon'      => 'bi bi-cash-coin',
                    'url'       => 'admin/data-tagihan-member',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            
                 [
                    'title'     => 'Request Buku',
                    'icon'      => 'bi bi-envelope-paper',
                    'url'       => 'admin/data-request-buku',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'ANGGOTA',
            'data' => [
                [
                    'title'     => 'Data Anggota',
                    'icon'      => 'bi bi-people',
                    'url'       => 'admin/data-anggota',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
        
            ]
        ],
        [
            'heading' => 'PENGATURAN',
            'data' => [
                [
                    'title'     => 'Pengaturan',
                    'icon'      => 'bi bi-gear',
                    'url'       => 'admin/settings',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Logout',
                    'icon'      => 'bi bi-box-arrow-right',
                    'url'       => 'logout',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

    ];

    public array $menu_member = [

        [
            'heading' => 'MAIN',
            'data' => [
                [
                    'title'     => 'Dashboard',
                    'icon'      => 'bi bi-speedometer2',
                    'url'       => 'dashboard',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'PEMINJAMAN',
            'data' => [
                 [
                    'title'     => 'Cari Buku',
                    'icon'      => 'bi bi-search',
                    'url'       => 'cari-buku',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Buku Dipinjam',
                    'icon'      => 'bi bi-bookmark-check',
                    'url'       => 'data-pinjaman-buku',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Permintaan Buku',
                    'icon'      => 'bi bi-envelope-paper',
                    'url'       => 'request-buku',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'DENDA',
            'data' => [
                [
                    'title'     => 'Tagihan Denda',
                    'icon'      => 'bi bi-receipt-cutoff',
                    'url'       => 'data-tagihan',
                    'notif'     => 1,
                    'typenotif' => 'denda',
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'AKUN',
            'data' => [
                [
                    'title'     => 'Profil Saya',
                    'icon'      => 'bi bi-person-circle',
                    'url'       => 'settings',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Logout',
                    'icon'      => 'bi bi-box-arrow-right',
                    'url'       => 'logout',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

    ];

    public array $menu_pimpinan = [

        [
            'heading' => 'MAIN',
            'data' => [
                [
                    'title'     => 'Dashboard',
                    'icon'      => 'bi bi-speedometer2',
                    'url'       => 'pimpinan/dashboard',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'LAPORAN',
            'data' => [
                [
                    'title'     => 'Lap. Peminjaman',
                    'icon'      => 'bi bi-bookmark-check',
                    'url'       => 'pimpinan/laporan-peminjaman',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Lap. Denda',
                    'icon'      => 'bi bi-cash-coin',
                    'url'       => 'pimpinan/laporan-denda',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Lap. Koleksi Buku',
                    'icon'      => 'bi bi-book',
                    'url'       => 'pimpinan/laporan-koleksi',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Lap. Anggota',
                    'icon'      => 'bi bi-people',
                    'url'       => 'pimpinan/laporan-anggota',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
                [
                    'title'     => 'Lap. Maintenance',
                    'icon'      => 'bi bi-wrench',
                    'url'       => 'pimpinan/laporan-maintenance',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

        [
            'heading' => 'AKUN',
            'data' => [
                [
                    'title'     => 'Logout',
                    'icon'      => 'bi bi-box-arrow-right',
                    'url'       => 'logout',
                    'notif'     => 0,
                    'typenotif' => null,
                    'submenu'   => FALSE,
                ],
            ]
        ],

    ];
}
