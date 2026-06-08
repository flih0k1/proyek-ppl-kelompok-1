<?php
$today = date('Y-m-d H:i:s');

// Fetch Real Stats
$total_buku = $this->db->table('tb_buku')->countAllResults();
$anggota_aktif = $this->db->table('tb_users')->join('tb_users_groups', 'tb_users.id = tb_users_groups.user_id')->where('group_id', 2)->where('active', 1)->countAllResults();
$sedang_dipinjam = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'pinjam')->countAllResults();
$terlambat = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'pinjam')->where('peminjaman_date_end <', $today)->countAllResults();

$stats = [
    ['label' => 'Total Buku', 'value' => $total_buku, 'icon' => 'bi-book', 'color' => 'primary', 'sub' => 'Koleksi terdaftar'],
    ['label' => 'Anggota Aktif', 'value' => $anggota_aktif, 'icon' => 'bi-people', 'color' => 'success', 'sub' => 'Member aktif'],
    ['label' => 'Dipinjam', 'value' => $sedang_dipinjam, 'icon' => 'bi-bookmark-check', 'color' => 'warning', 'sub' => 'Sedang berjalan'],
    ['label' => 'Terlambat', 'value' => $terlambat, 'icon' => 'bi-exclamation-circle', 'color' => 'danger', 'sub' => 'Perlu tindakan'],
];

// Peminjaman Terbaru
$peminjaman_terbaru = $this->db->table('tb_peminjaman')
    ->join('tb_users', 'tb_users.id = tb_peminjaman.peminjaman_userid', 'left')
    ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->orderBy('peminjaman_date_add', 'desc')
    ->limit(5)
    ->get()->getResult();

// Buku Populer
$buku_populer = $this->db->table('tb_peminjaman')
    ->select('tb_buku.buku_judul, tb_buku.buku_penulis, tb_kategori_buku.kategori_nama, count(tb_peminjaman.peminjaman_id) as total_dipinjam')
    ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
    ->groupBy('peminjaman_buku_id')
    ->orderBy('total_dipinjam', 'desc')
    ->limit(5)
    ->get()->getResult();

// Anggota Baru
$anggota_baru = $this->db->table('tb_users')
    ->join('tb_users_groups', 'tb_users.id = tb_users_groups.user_id')
    ->where('group_id', 2)
    ->orderBy('created_on', 'desc')
    ->limit(4)
    ->get()->getResult();

$status_badge = [
    'pending'    => 'bg-info',
    'pinjam'     => 'bg-warning text-dark',
    'kembalikan' => 'bg-primary',
    'selesai'    => 'bg-success',
];
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <?php foreach ($stats as $s): ?>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-<?= $s['color'] ?> bg-opacity-10 text-<?= $s['color'] ?>">
                    <i class="bi <?= $s['icon'] ?> fs-2"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold counter"><?= $s['value'] ?></div>
                    <div class="text-muted small"><?= $s['label'] ?></div>
                    <div class="text-<?= $s['color'] ?> small"><?= $s['sub'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3 mb-4">
    <!-- Peminjaman Terbaru -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Peminjaman Terbaru</span>
                <a href="<?= site_url('admin/data-peminjaman') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($peminjaman_terbaru as $p): ?>
                            <?php 
                                $status_text = $p->peminjaman_status;
                                $badge_class = $status_badge[$status_text] ?? 'bg-secondary';
                                
                                // Cek jika terlambat
                                if ($status_text == 'pinjam' && $p->peminjaman_date_end < $today) {
                                    $status_text = 'terlambat';
                                    $badge_class = 'bg-danger';
                                }
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:34px;height:34px">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <div class="small fw-medium"><?= esc($p->user_fullname) ?></div>
                                    </div>
                                </td>
                                <td><div class="text-truncate" style="max-width: 150px;"><?= esc($p->buku_judul) ?></div></td>
                                <td><small><?= date('d M Y', strtotime($p->peminjaman_date_start)) ?></small></td>
                                <td><small><?= date('d M Y', strtotime($p->peminjaman_date_end)) ?></small></td>
                                <td>
                                    <span class="badge <?= $badge_class ?> text-capitalize" style="font-size: 10px;">
                                        <?= $status_text ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Anggota Baru -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-person-plus me-2 text-success"></i>Anggota Baru</span>
                <a href="<?= site_url('admin/data-anggota') ?>" class="btn btn-sm btn-outline-success">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($anggota_baru as $a): ?>
                    <li class="list-group-item d-flex align-items-center gap-3 py-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px">
                            <i class="bi bi-person text-success fs-5"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-semibold text-truncate small"><?= esc($a->user_fullname) ?></div>
                            <div class="text-muted small text-truncate" style="font-size: 11px;"><?= esc($a->email) ?></div>
                        </div>
                        <div class="ms-auto text-muted small text-nowrap" style="font-size: 10px;"><?= date('d M Y', $a->created_on) ?></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Buku Populer -->
<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-fire me-2 text-danger"></i>Buku Paling Banyak Dipinjam</span>
                <a href="<?= site_url('admin/data-buku') ?>" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Judul Buku</th>
                                <th>Penulis</th>
                                <th>Kategori</th>
                                <th>Total Dipinjam</th>
                                <th>Popularitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($buku_populer as $i => $b): ?>
                            <?php $pct = ($buku_populer[0]->total_dipinjam > 0) ? round($b->total_dipinjam / $buku_populer[0]->total_dipinjam * 100) : 0; ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $i + 1 ?></span></td>
                                <td class="fw-semibold small"><?= esc($b->buku_judul) ?></td>
                                <td class="small"><?= esc($b->buku_penulis) ?></td>
                                <td><span class="badge bg-info bg-opacity-10 text-info border border-info" style="font-size: 10px;"><?= esc($b->kategori_nama ?? 'Umum') ?></span></td>
                                <td class="small"><?= $b->total_dipinjam ?> kali</td>
                                <td style="min-width:100px">
                                    <div class="progress" style="height:8px">
                                        <div class="progress-bar bg-danger" style="width:<?= $pct ?>%"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
