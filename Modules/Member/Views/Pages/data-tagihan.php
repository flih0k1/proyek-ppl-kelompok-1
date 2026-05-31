<?php
$filterStatus = trim((string) (get('status') ?? ''));
$search       = trim((string) (get('search') ?? ''));

$limit  = 10;
$page   = max(1, (int) (get('page') ?? 1));
$offset = ($page - 1) * $limit;

// Query dasar untuk tagihan (hanya menampilkan yang memiliki denda > 0)
$datapeminjaman = $this->db->table('tb_peminjaman')
    ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->where('peminjaman_userid', userid())
    ->where('peminjaman_denda >', 0)
    ->where('peminjaman_status', 'selesai')
    ->orderBy('peminjaman_date_kembali', 'desc');

if ($search !== '') {
    $datapeminjaman->groupStart()
        ->like('buku_judul', $search)
        ->orLike('peminjaman_code', $search)
        ->groupEnd();
}

if ($filterStatus !== '') {
    $datapeminjaman->where('peminjaman_status_bayar', $filterStatus);
}
$getdata = $datapeminjaman->limit($limit, $offset)->get()->getResult();
$total   = $datapeminjaman->countAllResults();
$totalDenda      = $this->db->table('tb_peminjaman')->where('peminjaman_userid', userid())->selectSum('peminjaman_denda')->get()->getRow()->peminjaman_denda ?? 0;
$dendaBelumBayar = $this->db->table('tb_peminjaman')->where(['peminjaman_userid' => userid(), 'peminjaman_status_bayar' => 'belum'])->selectSum('peminjaman_denda')->get()->getRow()->peminjaman_denda ?? 0;
$dendaLunas      = $this->db->table('tb_peminjaman')->where(['peminjaman_userid' => userid(), 'peminjaman_status_bayar' => 'lunas'])->selectSum('peminjaman_denda')->get()->getRow()->peminjaman_denda ?? 0;

$widgett = [
    ['label' => 'Total Denda',      'value' => 'Rp ' . number_format($totalDenda, 0, ',', '.'),      'icon' => 'bi-cash-stack',     'color' => 'primary'],
    ['label' => 'Belum Dibayar',    'value' => 'Rp ' . number_format($dendaBelumBayar, 0, ',', '.'), 'icon' => 'bi-exclamation-octagon', 'color' => 'danger'],
    ['label' => 'Sudah Dilunasi',   'value' => 'Rp ' . number_format($dendaLunas, 0, ',', '.'),      'icon' => 'bi-check2-all',     'color' => 'success'],
];
?>

<style>
    .tagihan-card {
        border: 1px solid #eef2f7;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .tagihan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
    }

    .status-badge {
        font-size: 11px;
        padding: 5px 10px;
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
    <div class="row g-3 mb-4">
        <?php foreach ($getdata as $t):
            $isLunas = ($t->peminjaman_status_bayar === 'lunas');
            $coverSrc = !empty($t->buku_cover)
                ? base_url('assets/upload/cover/thumbnail/' . $t->buku_cover)
                : 'https://placehold.co/60x84/e2e8f0/94a3b8?text=No+Cover';

            $end       = strtotime($t->peminjaman_date_end); // biar konsisten per hari
            $balik       = strtotime($t->peminjaman_date_kembali);

            $telat = floor(abs($balik - $end) / 86400);
        ?>
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm tagihan-card h-100">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <img src="<?= $coverSrc ?>" alt="cover" class="rounded shadow-sm flex-shrink-0"
                                style="width:60px; height:84px; object-fit:cover">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="badge bg-<?= $isLunas ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $isLunas ? 'success' : 'danger' ?> border border-<?= $isLunas ? 'success' : 'danger' ?> status-badge">
                                        <i class="bi <?= $isLunas ? 'bi-check-circle' : 'bi-clock-history' ?> me-1"></i>
                                        <?= $isLunas ? 'Lunas' : 'Belum Bayar' ?>
                                    </span>
                                    <small class="text-muted">#<?= esc($t->peminjaman_code) ?></small>
                                </div>
                                <h6 class="fw-bold text-truncate mb-1"><?= esc($t->buku_judul) ?></h6>
                                <div class="row g-0 small text-muted">
                                    <div class="col-6">Tgl Kembali: <b><?= date('d M Y', strtotime($t->peminjaman_date_kembali)) ?></b></div>
                                    <div class="col-6 text-end">Terlambat: <b class="text-danger"><?= $telat ?? '?' ?> Hari</b></div>
                                </div>
                                <hr class="my-2 opacity-50">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted small">Total Denda:</span>
                                        <span class="fs-5 fw-bold text-danger d-block">Rp <?= number_format($t->peminjaman_denda, 0, ',', '.') ?></span>
                                    </div>
                                    <?php if (!$isLunas): ?>
                                        <button class="btn btn-sm btn-primary px-3" onclick="bayarDenda('<?= $t->peminjaman_code ?>', <?= $t->peminjaman_denda ?>)">
                                            <i class="bi bi-wallet2 me-1"></i>Bayar
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-success disabled px-3">
                                            <i class="bi bi-patch-check me-1"></i>Selesai
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light bg-opacity-50 border-top-0 py-2">
                        <div class="d-flex justify-content-between align-items-center px-1">
                            <small class="text-muted" style="font-size: 10px;">
                                <i class="bi bi-info-circle me-1"></i>Denda dihitung per hari keterlambatan.
                            </small>
                            <button class="btn btn-link btn-sm text-decoration-none p-0" style="font-size: 11px;"
                                data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                data-bs-href="<?= site_url('member/modal/detail-peminjaman?code=' . $t->peminjaman_code) ?>"
                                data-bs-title="Detail Transaksi">
                                Lihat Detail <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
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
    function bayarDenda(code, nominal) {
        const rupiah = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(nominal);

        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `Anda akan melakukan pembayaran denda keterlambatan <br>Sebesar Rp <b class="fs-4 text-danger">${rupiah}</b>`,
            type: 'info',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan ke Pembayaran',
            cancelButtonText: 'Nanti Saja',
            confirmButtonColor: '#0d6efd',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                        url: '<?php echo site_url('member/postdata/pinjam/bayar_denda') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            code: code,
                            csrf_myapp: $('input[name=csrf_myapp]').val()

                        },
                    })
                    .done(function(data) {
                        updateCSRF(data.csrf_data);
                        Swal.fire(
                            data.heading,
                            data.message,
                            data.type
                        ).then(function() {
                            if (data.status) {
                                location.reload();
                            }
                        });
                    })
            }
        });
    }
</script>

<style>
    @media (max-width: 576px) {
        .tagihan-card .fs-5 {
            font-size: 1.1rem !important;
        }

        .tagihan-card h6 {
            font-size: 0.9rem;
        }

        .tagihan-card img {
            width: 50px !important;
            height: 70px !important;
        }
    }
</style>