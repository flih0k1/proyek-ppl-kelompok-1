<?php
$filterStatus = trim((string) (get('status') ?? ''));
$search       = trim((string) (get('search') ?? ''));
$today        = date('Y-m-d 00:00:00');

$limit  = 12;
$page   = max(1, (int) (get('page') ?? 1));
$offset = ($page - 1) * $limit;

    $datapeminjaman = $this->db->table('tb_peminjaman')
        ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
        ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
        ->where('peminjaman_userid', userid())
        ->orderBy('peminjaman_date_start', 'desc');

    if ($search !== '') {
        $datapeminjaman->groupStart()
            ->like('buku_judul', $search)
            ->orLike('buku_penulis', $search)
            ->orLike('buku_isbn', $search)
            ->orLike('peminjaman_code', $search)
            ->groupEnd();
    }

    if ($filterStatus === 'terlambat') {
        $datapeminjaman->where('peminjaman_status', 'pinjam')
            ->where('peminjaman_date_end <', $today);
    } elseif ($filterStatus !== '') {
        $datapeminjaman->where('peminjaman_status', $filterStatus);
    }

$getdata = $datapeminjaman->limit($limit, $offset)->get()->getResult();
$total   = $datapeminjaman->countAllResults();

$totalPinjam          = $this->db->table('tb_peminjaman')->where('peminjaman_userid', userid())->countAllResults();
$totalPending         = $this->db->table('tb_peminjaman')->where('peminjaman_userid', userid())->where('peminjaman_status', 'pending')->countAllResults();
$totalAktif           = $this->db->table('tb_peminjaman')->where('peminjaman_userid', userid())->where('peminjaman_status', 'pinjam')->where('peminjaman_date_end >=', $today)->countAllResults();
$totalMenungguKembali = $this->db->table('tb_peminjaman')->where('peminjaman_userid', userid())->where('peminjaman_status', 'kembalikan')->countAllResults();
$totalSelesai         = $this->db->table('tb_peminjaman')->where('peminjaman_userid', userid())->where('peminjaman_status', 'selesai')->countAllResults();
$totalTerlambat       = $this->db->table('tb_peminjaman')->where('peminjaman_userid', userid())->where('peminjaman_status', 'pinjam')->where('peminjaman_date_end <', $today)->countAllResults();

$widget = [
    ['label' => 'Total Pinjaman',    'value' => $totalPinjam,          'icon' => 'bi-journal-bookmark', 'color' => 'primary'],
    ['label' => 'Pending',           'value' => $totalPending,         'icon' => 'bi-hourglass-split',  'color' => 'warning'],
    ['label' => 'Sedang Dipinjam',   'value' => $totalAktif,           'icon' => 'bi-book',             'color' => 'info'],
    ['label' => 'Menunggu Kembali',  'value' => $totalMenungguKembali, 'icon' => 'bi-arrow-repeat',     'color' => 'secondary'],
    ['label' => 'Selesai',           'value' => $totalSelesai,         'icon' => 'bi-check-circle',     'color' => 'success'],
    ['label' => 'Terlambat',         'value' => $totalTerlambat,       'icon' => 'bi-clock-history',    'color' => 'danger'],
];

$statusss = [
    'pending'    => ['label' => 'Pending',          'color' => 'warning',  'icon' => 'bi-hourglass-split'],
    'pinjam'     => ['label' => 'Dipinjam',         'color' => 'primary',  'icon' => 'bi-book'],
    'kembalikan' => ['label' => 'Menunggu Kembali', 'color' => 'info',     'icon' => 'bi-arrow-repeat'],
    'selesai'    => ['label' => 'Selesai',          'color' => 'success',  'icon' => 'bi-check-circle'],
    'terlambat'  => ['label' => 'Terlambat',        'color' => 'danger',   'icon' => 'bi-exclamation-circle'],
];
?>

<style>
    .pinjam-summary-card {
        border: 1px solid #eef2f7;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .pinjam-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(17, 24, 39, .08) !important;
    }

    .pinjam-card {
        border: 1px solid #eef2f7;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .pinjam-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(17, 24, 39, .08) !important;
    }

    .pinjam-card--late {
        border-color: #fecaca;
        background: linear-gradient(180deg, rgba(254, 242, 242, .55) 0%, #ffffff 50%);
    }

    .deadline-bar {
        height: 6px;
        border-radius: 6px;
    }

    .pinjam-title {
        font-size: .9rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tab-status.active {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Data Pinjaman Buku</h5>
        <small class="text-muted">Riwayat pinjaman, status buku, dan tenggat pengembalian</small>
    </div>
    <a href="<?= site_url('cari-buku') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Pinjam Buku Baru
    </a>
</div>

<div class="row g-3 mb-4">
    <?php foreach ($widget as $card): ?>
        <div class="col-6 col-xl-4">
            <div class="card border-0 shadow-sm pinjam-summary-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-<?= $card['color'] ?> bg-opacity-10 text-<?= $card['color'] ?>">
                        <i class="bi <?= $card['icon'] ?> fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold"><?= $card['value'] ?></div>
                        <div class="text-muted small"><?= $card['label'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="get" action="" id="formFilter" class="row g-2 align-items-center">
            <div class="col-12 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="inputSearch" name="search" class="form-control border-start-0"
                        placeholder="Cari judul, penulis, ISBN, atau kode pinjaman..." value="<?= esc($search) ?>">
                </div>
            </div>
            <div class="col-8 col-md-5 col-lg-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $filterStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="pinjam" <?= $filterStatus === 'pinjam' ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="kembalikan" <?= $filterStatus === 'kembalikan' ? 'selected' : '' ?>>Menunggu Kembali</option>
                    <option value="selesai" <?= $filterStatus === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="terlambat" <?= $filterStatus === 'terlambat' ? 'selected' : '' ?>>Terlambat</option>
                </select>
            </div>
            <?php if ($search || $filterStatus): ?>
                <div class="col-4 col-md-3 col-lg-2 d-grid">
                    <a href="?" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
                </div>
            <?php endif; ?>
        </form>

    </div>
</div>

<div class="text-muted small mb-3">
    Menampilkan <strong><?= count($getdata) ?></strong> dari <strong><?= $total ?></strong> data pinjaman
</div>

<?php if (empty($getdata)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-journal-x fs-1 text-muted opacity-25 d-block mb-3"></i>
            <h6 class="fw-bold mb-1">Data pinjaman belum tersedia</h6>
            <p class="text-muted small mb-3">
                <?= ($filterStatus || $search) ? 'Tidak ada data yang cocok dengan filter yang dipilih.' : 'Kamu belum memiliki riwayat peminjaman.' ?>
            </p>
            <a href="<?= site_url('cari-buku') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i>Cari Buku
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3 mb-4">
        <?php foreach ($getdata as $p):
            $tglMulai   = $p->peminjaman_date_start ?? null;
            $tglJatuh   = $p->peminjaman_date_end ?? null;
            $tglKembali = $p->peminjaman_date_kembali ?? null;
            $coverSrc   = !empty($p->buku_cover)
                ? base_url('assets/upload/cover/thumbnail/' . $p->buku_cover)
                : 'https://placehold.co/60x84/e2e8f0/94a3b8?text=No+Cover';

            $sisaHari  = null;
            $isLate    = false;
            $barColor  = 'success';

            if ($p->peminjaman_status === 'pinjam' && $tglJatuh) {

                $now       = strtotime(date('Y-m-d')); // biar konsisten per hari
                $due       = strtotime(date('Y-m-d', strtotime($tglJatuh)));

                $diffDay = floor(abs($now - $due) / 86400);

                if ($now > $due) {
                    $sisaHari = -$diffDay;
                    $isLate   = true;
                } else {
                    $sisaHari = $diffDay;
                    $isLate   = false;
                }

                if ($tglMulai) {
                    $start = strtotime(date('Y-m-d', strtotime($tglMulai)));

                    $totalDay = max(1, floor(abs($start - $due) / 86400));
                    $elapsed  = max(0, floor(($now - $start) / 86400));
                }

                $barColor = $isLate
                    ? 'danger'
                    : (($sisaHari !== null && $sisaHari <= 2) ? 'warning' : 'success');
            }

            $cfg = $statusss[$p->peminjaman_status] ?? ['label' => ucfirst($p->peminjaman_status), 'color' => 'secondary', 'icon' => 'bi-circle'];
            if ($isLate) {
                $cfg = $statusss['terlambat'];
            }
        ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm pinjam-card <?= $isLate ? 'pinjam-card--late' : '' ?> h-100">
                    <div class="card-body">
                        <div class="d-flex gap-3 mb-3">
                            <img src="<?= $coverSrc ?>" alt="cover" class="rounded shadow-sm flex-shrink-0"
                                style="width:52px;height:72px;object-fit:cover">
                            <div class="overflow-hidden flex-grow-1">
                                <div class="fw-semibold pinjam-title mb-1"><?= esc($p->buku_judul ?? '-') ?></div>
                                <div class="text-muted small text-truncate"><?= esc($p->buku_penulis ?? '-') ?></div>
                                <?php if (!empty($p->kategori_nama)): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary mt-1" style="font-size:10px">
                                        <?= esc($p->kategori_nama) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="badge bg-<?= $cfg['color'] ?> bg-opacity-10 text-<?= $cfg['color'] ?> border border-<?= $cfg['color'] ?> align-self-start flex-shrink-0" style="font-size:10px">
                                <i class="bi <?= $cfg['icon'] ?> me-1"></i><?= esc($cfg['label']) ?>
                            </span>
                        </div>

                        <div class="row g-2 mb-3" style="font-size:12px">
                            <div class="col-6">
                                <div class="text-muted">Tgl Pinjam</div>
                                <div class="fw-semibold"><?= $tglMulai ? date('d M Y', strtotime($tglMulai)) : '-' ?></div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">Jatuh Tempo</div>
                                <div class="fw-semibold <?= $isLate ? 'text-danger' : '' ?>">
                                    <?= $tglJatuh ? date('d M Y', strtotime($tglJatuh)) : '-' ?>
                                </div>
                            </div>
                            <?php if ($p->peminjaman_status !== 'pinjam'): ?>
                                <div class="col-6">
                                    <div class="text-muted">Tgl Kembali</div>
                                    <div class="fw-semibold"><?= $tglKembali ? date('d M Y', strtotime($tglKembali)) : '-' ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($p->peminjaman_denda) && $p->peminjaman_denda > 0): ?>
                                <div class="col-6">
                                    <div class="text-muted">Denda</div>
                                    <div class="fw-semibold text-danger">Rp <?= number_format($p->peminjaman_denda, 0, ',', '.') ?></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($p->peminjaman_status === 'pinjam' && $sisaHari !== null): ?>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1" style="font-size:11px">
                                    <span class="text-muted">Batas waktu</span>
                                    <?php if ($isLate): ?>
                                        <span class="text-danger fw-semibold">
                                            <i class="bi bi-clock me-1"></i>Terlambat <?= abs($sisaHari) ?> hari
                                        </span>
                                    <?php elseif ($sisaHari <= 2): ?>
                                        <span class="text-warning fw-semibold">
                                            <i class="bi bi-exclamation-circle me-1"></i>Sisa <?= $sisaHari ?> hari
                                        </span>
                                    <?php else: ?>
                                        <span class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>Sisa <?= $sisaHari ?> hari
                                        </span>
                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endif; ?>

                        <div class="text-muted" style="font-size:10px">
                            <i class="bi bi-hash me-1"></i><?= esc($p->peminjaman_code ?? $p->peminjaman_id) ?>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top p-2 d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info flex-grow-1"
                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                            data-bs-href="<?= site_url('member/modal/detail-peminjaman?code=' . $p->peminjaman_code) ?>"
                            data-bs-title="Detail Pinjaman">
                            <i class="bi bi-eye me-1"></i>Detail
                        </button>
                        <?php if ($p->peminjaman_status === 'pending'): ?>
                            <button class="btn btn-sm btn-outline-danger flex-grow-1"
                                onclick="batalkanPinjaman('<?= esc($p->peminjaman_code, 'js') ?>', '<?= esc($p->buku_judul, 'js') ?>')">
                                <i class="bi bi-x-circle me-1"></i>Batalkan
                            </button>
                        <?php elseif ($p->peminjaman_status === 'kembalikan'): ?>
                            <span class="btn btn-sm btn-light border flex-grow-1 disabled">
                                <i class="bi bi-hourglass-split me-1"></i>Menunggu Konfirmasi
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">Halaman <?= $page ?> dari <?= max(1, (int) ceil($total / $limit)) ?></small>
        <?= pagination(page_url(), $total, $limit) ?>
    </div>
<?php endif; ?>

<script>
    let searchTimer;
    const inputSearch = document.getElementById('inputSearch');

    if (inputSearch) {
        inputSearch.addEventListener('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                document.getElementById('formFilter').submit();
            }, 450);
        });
    }

    function batalkanPinjaman(code, judul) {
        Swal.fire({
            title: 'Batalkan Permintaan?',
            html: `Permintaan pinjam buku <b>${judul}</b> akan dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-x-circle me-1"></i>Ya, Batalkan',
            cancelButtonText: 'Tutup',
        }).then(result => {
            if (!result.isConfirmed) return;
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            $.post('<?= site_url('member/postdata/Pinjam/delete_pinjam') ?>', {
                code: code,
                csrf_myapp: $('input[name=csrf_myapp]').val()
            }).done(res => {
                updateCSRF(res.csrf_data);
                Swal.fire(res.heading, res.message, res.type).then(() => {
                    if (res.status) location.reload();
                });
            }).fail(() => {
                Swal.fire('Gagal', 'Terjadi kesalahan, coba lagi.', 'error');
            });
        });
    }
</script>