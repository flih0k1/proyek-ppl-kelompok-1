<?php
$filterStatus = trim((string) (get('status') ?? ''));
$search       = trim((string) (get('search') ?? ''));

$limit  = 10;
$page   = max(1, (int) (get('page') ?? 1));
$offset = ($page - 1) * $limit;
$no     = $offset + 1;

// Query dasar untuk tagihan (hanya pinjaman selesai dengan denda > 0)
$baseQuery = $this->db->table('tb_peminjaman')
    ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->join('tb_users', 'tb_users.id = tb_peminjaman.peminjaman_userid', 'left')
    ->where('peminjaman_denda >', 0)
    ->where('peminjaman_status', 'selesai');

if ($search !== '') {
    $baseQuery->groupStart()
        ->like('buku_judul', $search)
        ->orLike('peminjaman_code', $search)
        ->groupEnd();
}

if ($filterStatus !== '') {
    $baseQuery->where('peminjaman_status_bayar', $filterStatus);
}

$getdata = (clone $baseQuery)
    ->orderBy('peminjaman_date_kembali', 'desc')
    ->limit($limit, $offset)
    ->get()
    ->getResult();

$total = (clone $baseQuery)->countAllResults();

$totalDenda = $this->db->table('tb_peminjaman')
    ->where('peminjaman_status', 'selesai')
    ->where('peminjaman_denda >', 0)
    ->selectSum('peminjaman_denda')
    ->get()
    ->getRow()
    ->peminjaman_denda ?? 0;

$dendaBelumBayar = $this->db->table('tb_peminjaman')
    ->where('peminjaman_status', 'selesai')
    ->where('peminjaman_denda >', 0)
    ->where('peminjaman_status_bayar', 'belum')
    ->selectSum('peminjaman_denda')
    ->get()
    ->getRow()
    ->peminjaman_denda ?? 0;

$dendaLunas = $this->db->table('tb_peminjaman')
    ->where('peminjaman_status', 'selesai')
    ->where('peminjaman_denda >', 0)
    ->where('peminjaman_status_bayar', 'lunas')
    ->selectSum('peminjaman_denda')
    ->get()
    ->getRow()
    ->peminjaman_denda ?? 0;

$widgett = [
    ['label' => 'Total Denda',      'value' => 'Rp ' . number_format($totalDenda, 0, ',', '.'),      'icon' => 'bi-cash-stack',     'color' => 'primary'],
    ['label' => 'Belum Dibayar',    'value' => 'Rp ' . number_format($dendaBelumBayar, 0, ',', '.'), 'icon' => 'bi-exclamation-octagon', 'color' => 'danger'],
    ['label' => 'Sudah Dilunasi',   'value' => 'Rp ' . number_format($dendaLunas, 0, ',', '.'),      'icon' => 'bi-check2-all',     'color' => 'success'],
];
?>

<style>
    .tagihan-table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .02em;
        color: #6c757d;
        white-space: nowrap;
    }

    .tagihan-table tbody td {
        vertical-align: middle;
        font-size: 13px;
    }

    .status-badge {
        font-size: 11px;
        padding: 5px 10px;
    }

    .tagihan-cover {
        width: 44px;
        height: 62px;
        object-fit: cover;
    }

    .tagihan-book-title {
        max-width: 280px;
    }

    .tagihan-amount {
        font-size: 15px;
        font-weight: 700;
        color: #dc3545;
    }

    @media (max-width: 576px) {
        .tagihan-table tbody td {
            font-size: 12px;
        }

        .tagihan-cover {
            width: 38px;
            height: 54px;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Tagihan Denda</h5>
        <small class="text-muted">Pantau dan lunasi denda keterlambatan pengembalian buku</small>
    </div>
    <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
    </button>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <?php foreach ($widgett as $card): ?>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-<?= $card['color'] ?> bg-opacity-10 text-<?= $card['color'] ?>">
                        <i class="bi <?= $card['icon'] ?> fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold"><?= $card['value'] ?></div>
                        <div class="text-muted small"><?= $card['label'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="get" id="formFilter" class="row g-2 align-items-center">
            <div class="col-12 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" id="inputSearch" class="form-control border-start-0"
                        placeholder="Cari judul buku atau kode pinjaman..." value="<?= esc($search) ?>">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-8 col-md-5 col-lg-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="belum" <?= $filterStatus === 'belum' ? 'selected' : '' ?>>Belum Lunas</option>
                    <option value="lunas" <?= $filterStatus === 'lunas' ? 'selected' : '' ?>>Sudah Lunas</option>
                </select>
            </div>
            <?php if ($search || $filterStatus): ?>
                <div class="col-4 col-md-3 col-lg-2 d-grid">
                    <a href="?" class="btn btn-outline-secondary">Reset</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if (empty($getdata)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-receipt fs-1 text-muted opacity-25 d-block mb-3"></i>
            <h6 class="fw-bold">Tidak ada tagihan ditemukan</h6>
            <p class="text-muted small">Anda tidak memiliki denda pada kriteria ini.</p>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tagihan-table">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 56px;">No</th>
                            <th>Member</th>
                            <th>Buku</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Kembali</th>
                            <th>Terlambat</th>
                            <th>Total Denda</th>
                            <th>Status</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($getdata as $t):
                            $isLunas = ($t->peminjaman_status_bayar === 'lunas');
                            $coverSrc = !empty($t->buku_cover)
                                ? base_url('assets/upload/cover/thumbnail/' . $t->buku_cover)
                                : 'https://placehold.co/60x84/e2e8f0/94a3b8?text=No+Cover';

                            $end   = strtotime($t->peminjaman_date_end);
                            $balik = strtotime($t->peminjaman_date_kembali);
                            $telat = max(0, (int) floor(($balik - $end) / 86400));
                        ?>
                            <tr>
                                <td class="ps-3 text-muted fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= avatar($t->id) ?>" class="rounded-circle" width="40" height="40" alt="avatar">
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold text-truncate" style="font-size:13px;max-width:180px"><?= esc($t->user_fullname) ?></div>
                                            <div class="text-muted text-truncate" style="font-size:11px;max-width:180px">@<?= esc($t->username) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= $coverSrc ?>" alt="cover" class="rounded shadow-sm flex-shrink-0 tagihan-cover">
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold text-truncate tagihan-book-title"><?= esc($t->buku_judul) ?></div>
                                            <small class="text-muted">Denda dihitung per hari keterlambatan</small>
                                        </div>
                                    </div>
                                </td>
                               
                                <td><?= date('d M Y', strtotime($t->peminjaman_date_end)) ?></td>
                                <td><?= date('d M Y', strtotime($t->peminjaman_date_kembali)) ?></td>
                                <td>
                                    <span class="fw-semibold <?= $telat > 0 ? 'text-danger' : 'text-success' ?>">
                                        <?= $telat ?> Hari
                                    </span>
                                </td>
                                <td>
                                    <span class="tagihan-amount">Rp <?= number_format($t->peminjaman_denda, 0, ',', '.') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $isLunas ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $isLunas ? 'success' : 'danger' ?> border border-<?= $isLunas ? 'success' : 'danger' ?> status-badge">
                                        <i class="bi <?= $isLunas ? 'bi-check-circle' : 'bi-clock-history' ?> me-1"></i>
                                        <?= $isLunas ? 'Lunas' : 'Belum Bayar' ?>
                                    </span>
                                </td>
                                <td class="text-center pe-3">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                            data-bs-href="<?= site_url('member/modal/detail-peminjaman?code=' . $t->peminjaman_code) ?>"
                                            data-bs-title="Detail Transaksi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Halaman <?= $page ?> dari <?= max(1, (int) ceil($total / $limit)) ?></small>
            <?= pagination(page_url(), $total, $limit) ?>
        </div>
    </div>

    <div class="text-muted small">
        <i class="bi bi-info-circle me-1"></i>Denda dihitung berdasarkan selisih tanggal kembali dengan jatuh tempo.
    </div>
<?php endif; ?>