<?php
$today = date('Y-m-d 00:00:00');
$uid   = userid();

/* SPRINT 2
$totalAktif     = $this->db->table('tb_peminjaman')->where(['peminjaman_userid' => $uid, 'peminjaman_status' => 'pinjam'])->countAllResults();
$totalPinjam    = $this->db->table('tb_peminjaman')->where('peminjaman_userid', $uid)->countAllResults();
$totalTerlambat = $this->db->table('tb_peminjaman')->where('peminjaman_userid', $uid)->where('peminjaman_status', 'pinjam')->where('peminjaman_date_end <', $today)->countAllResults();
$totalDenda     = $this->db->table('tb_peminjaman')->where('peminjaman_userid', $uid)->selectSum('peminjaman_denda')->get()->getRow()->peminjaman_denda ?? 0;
$cekDenda     = $this->db->table('tb_peminjaman')->where('peminjaman_userid', $uid)->where('peminjaman_status', 'selesai')->where('peminjaman_status_bayar', 'belum')->get()->getNumRows() ?? 0;
$dendaBelumBayar = $this->db->table('tb_peminjaman')->where(['peminjaman_userid' => userid(), 'peminjaman_status_bayar' => 'belum'])->selectSum('peminjaman_denda')->get()->getRow()->peminjaman_denda ?? 0;

$widget = [
    ['label' => 'Sedang Dipinjam', 'value' => $totalAktif,     'icon' => 'bi-bookmark',        'color' => 'primary'],
    ['label' => 'Total Dipinjam',  'value' => $totalPinjam,    'icon' => 'bi-bookmark-check',  'color' => 'success'],
    ['label' => 'Terlambat',       'value' => $totalTerlambat, 'icon' => 'bi-clock-history',   'color' => 'danger'],
    ['label' => 'Total Denda',     'value' => 'Rp ' . number_format($totalDenda, 0, ',', '.'), 'icon' => 'bi-cash-coin', 'color' => 'warning'],
];

$buku_dipinjam = $this->db->table('tb_peminjaman')
    ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->where(['peminjaman_userid' => $uid, 'peminjaman_status' => 'pinjam'])
    ->limit(3)
    ->get()->getResult();

$riwayat = $this->db->table('tb_peminjaman')
    ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->where('peminjaman_userid', $uid)
    ->whereIn('peminjaman_status', ['selesai', 'kembalikan'])
    ->orderBy('peminjaman_date_kembali', 'desc')
    ->limit(4)
    ->get()->getResult();
*/

$rekomendasi = $this->db->table('tb_buku')
    ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
    ->orderBy('buku_id', 'desc')
    ->limit(5)
    ->get()->getResult();
?>

<style>
.buku-card {
    transition: transform .15s, box-shadow .15s;
    cursor: pointer;
}
.buku-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.1) !important;
}
.deadline-bar { height: 6px; border-radius: 3px; }
.greeting-card {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #be185d 100%);
    border-radius: 14px;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.greeting-card::after {
    content: '\F12F';
    font-family: 'bootstrap-icons';
    position: absolute;
    right: -10px;
    top: -20px;
    font-size: 140px;
    opacity: .06;
    line-height: 1;
}
.denda-alert {
    background: linear-gradient(135deg, #fef2f2, #fff5f5);
    border-left: 4px solid #dc2626;
}
</style>
<div class="greeting-card p-4 mb-4 shadow-sm">
    <div class="d-flex align-items-center gap-3">
        <img src="<?= avatar($userdata->id) ?>" class="rounded-circle border border-white border-3" width="64" height="64">
        <div>
            <div class="fs-5 fw-bold">Halo, <?= esc(explode(' ', $userdata->user_fullname)[0]) ?>! 👋</div>
            <div class="opacity-75 small">Selamat datang di Perpustakaan Al-Azhar</div>
            <div class="mt-1">
                <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 small">
                    <i class="bi bi-person-badge me-1"></i><?= esc($userdata->email) ?>
                </span>
                <span class="badge bg-success ms-1 small">
                    <i class="bi bi-circle-fill me-1" style="font-size:7px"></i><?= $userdata->active ? 'Aktif' : 'Non-Aktif' ?>
                </span>
            </div>
        </div>
        <div class="ms-auto text-end d-none d-md-block">
            <div class="opacity-75 small">Anggota sejak</div>
            <div class="fw-semibold small"><?= date('d M Y', $userdata->created_on) ?></div>
        </div>
    </div>
</div>

<?php /* SPRINT 2 
if ($dendaBelumBayar > 0): ?>
<div class="denda-alert rounded-3 p-3 mb-4 d-flex align-items-center gap-3">
    <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
    <div class="flex-grow-1">
        <div class="fw-semibold text-danger small">Kamu memiliki denda sebesar <?= rp($dendaBelumBayar, 0, ',', '.') ?> yang belum dibayar</div>
        <div class="text-muted small">Segera lunasi denda untuk dapat meminjam buku kembali.</div>
    </div>
    <a href="<?= site_url('data-tagihan') ?>" class="btn btn-sm btn-danger text-nowrap">
        Bayar Sekarang
    </a>
</div>
<?php endif; 
*/ ?>

<?php /* SPRINT 2 ?>
<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <?php foreach ($widget as $s): ?>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-<?= $s['color'] ?> bg-opacity-10 text-<?= $s['color'] ?>">
                    <i class="bi <?= $s['icon'] ?> fs-3"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold"><?= $s['value'] ?></div>
                    <div class="text-muted small"><?= $s['label'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php */ ?>

<?php /* SPRINT 2 ?>
<div class="row g-4 mb-4">

    <!-- Buku Sedang Dipinjam -->
    <div class="col-12 col-xl-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-bookmark me-2 text-primary"></i>Buku Sedang Dipinjam</span>
                <a href="<?= site_url('data-pinjaman-buku') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <?php if (empty($buku_dipinjam)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-bookmark-x fs-1 d-block mb-2 opacity-25"></i>
                    Anda tidak sedang meminjam buku.
                </div>
                <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($buku_dipinjam as $b):
                        $due       = strtotime(date('Y-m-d', strtotime($b->peminjaman_date_end)));
                        $now       = strtotime(date('Y-m-d'));
                        $sisaHari  = ($due - $now) / 86400;
                        $terlambat = $sisaHari < 0;
                        $hampirJatuh = !$terlambat && $sisaHari <= 2;
                        
                        $start     = strtotime(date('Y-m-d', strtotime($b->peminjaman_date_start)));
                        $totalDays = max(1, ($due - $start) / 86400);
                        $elapsed   = ($now - $start) / 86400;
                        $pct       = $terlambat ? 100 : max(0, min(100, ($elapsed / $totalDays) * 100));
                        $barColor = $terlambat ? 'danger' : ($hampirJatuh ? 'warning' : 'success');

                        $coverSrc = !empty($b->buku_cover)
                            ? base_url('assets/upload/cover/thumbnail/' . $b->buku_cover)
                            : 'https://placehold.co/56x78/e2e8f0/94a3b8?text=No+Cover';
                    ?>
                    <div class="d-flex gap-3 p-3 rounded-3 border">
                        <img src="<?= $coverSrc ?>" class="rounded shadow-sm" width="48" height="68" style="object-fit:cover;flex-shrink:0">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-truncate"><?= esc($b->buku_judul) ?></div>
                            <div class="text-muted small mb-2"><?= esc($b->buku_penulis) ?></div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Jatuh tempo: <strong><?= date('d M Y', strtotime($b->peminjaman_date_end)) ?></strong></span>
                                <?php if ($terlambat): ?>
                                    <span class="text-danger fw-semibold">
                                        <i class="bi bi-clock me-1"></i>Terlambat <?= abs($sisaHari) ?> hari
                                    </span>
                                <?php elseif ($hampirJatuh): ?>
                                    <span class="text-warning fw-semibold">
                                        <i class="bi bi-exclamation-circle me-1"></i>Sisa <?= $sisaHari ?> hari
                                    </span>
                                <?php else: ?>
                                    <span class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>Sisa <?= $sisaHari ?> hari
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="progress deadline-bar">
                                <div class="progress-bar bg-<?= $barColor ?>" style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white">
                <a href="<?= site_url('cari-buku') ?>" class="btn btn-primary w-100">
                    <i class="bi bi-search me-2"></i>Cari & Pinjam Buku Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Riwayat Peminjaman -->
    <div class="col-12 col-xl-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-success"></i>Riwayat Terakhir</span>
                <a href="<?= site_url('data-pinjaman-buku?status=selesai') ?>" class="btn btn-sm btn-outline-success">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($riwayat as $r):
                        $isSelesai = ($r->peminjaman_status === 'selesai');
                        $color = $isSelesai ? 'success' : 'info';
                        $coverSrc = !empty($r->buku_cover)
                            ? base_url('assets/upload/cover/thumbnail/' . $r->buku_cover)
                            : 'https://placehold.co/40x56/e2e8f0/94a3b8?text=No+Cover';
                    ?>
                    <li class="list-group-item d-flex align-items-center gap-3 py-3">
                        <img src="<?= $coverSrc ?>" class="rounded" width="32" height="44" style="object-fit:cover;flex-shrink:0">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="small fw-semibold text-truncate"><?= esc($r->buku_judul) ?></div>
                            <div class="text-muted" style="font-size:11px"><?= $r->peminjaman_date_kembali ? date('d M Y', strtotime($r->peminjaman_date_kembali)) : '-' ?></div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> border border-<?= $color ?> d-block mb-1" style="font-size: 10px;">
                                <?= ucfirst($r->peminjaman_status) ?>
                            </span>
                            <?php if ($r->peminjaman_denda > 0): ?>
                            <small class="text-danger fw-semibold">Rp <?= number_format($r->peminjaman_denda, 0, ',', '.') ?></small>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</div>
<?php */ ?>

<!-- Rekomendasi Buku -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-stars me-2 text-warning"></i>Rekomendasi Buku</span>
        <a href="<?= site_url('cari-buku') ?>" class="btn btn-sm btn-outline-warning">Lihat Katalog</a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($rekomendasi as $b): 
                $coverSrc = !empty($b->buku_cover)
                    ? base_url('assets/upload/cover/thumbnail/' . $b->buku_cover)
                    : 'https://placehold.co/80x112/e2e8f0/94a3b8?text=No+Cover';
            ?>
            <div class="col-6 col-md-4 col-xl-2-4">
                <div class="card border-0 shadow-sm buku-card h-100"
                    data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                    data-bs-href="<?= site_url('member/modal/detail-buku?code=' . $b->buku_code) ?>"
                    data-bs-title="<?= esc($b->buku_judul) ?>">
                    <div class="text-center pt-3 px-3">
                        <img src="<?= $coverSrc ?>" class="rounded" style="width:72px;height:100px;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,.15)">
                    </div>
                    <div class="card-body p-2 text-center">
                        <div class="small fw-semibold text-truncate"><?= esc($b->buku_judul) ?></div>
                        <div class="text-muted" style="font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= esc($b->buku_penulis) ?></div>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary mt-1" style="font-size:10px">
                            <?= esc($b->kategori_nama) ?>
                        </span>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-2 text-center">
                        <?php if ($b->buku_stok > 0): ?>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success w-100" style="font-size:10px">
                            <i class="bi bi-check-circle me-1"></i>Tersedia (<?= $b->buku_stok ?>)
                        </span>
                        <?php else: ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger w-100" style="font-size:10px">
                            <i class="bi bi-x-circle me-1"></i>Habis
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.col-xl-2-4 { flex: 0 0 auto; width: 20%; }
@media (max-width: 1199px) { .col-xl-2-4 { width: 33.333%; } }
@media (max-width: 767px)  { .col-xl-2-4 { width: 50%; } }
</style>
