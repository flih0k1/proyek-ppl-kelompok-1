<?php
$limit = 15;
$page  = (int)(get('page') ?? 1);
$page  = $page > 0 ? $page : 1;
$offset = ($page - 1) * $limit;
$no     = $offset + 1;
$search = get('search');
$status = get('status');
$today  = date('Y-m-d 00:00:00');

$getpeminjaman = $this->db->table('tb_peminjaman')->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')->join('tb_users', 'tb_users.id = tb_peminjaman.peminjaman_userid', 'left')->orderBy('peminjaman_date_add', 'desc');
if ($search) {
    $getpeminjaman->groupStart()
        ->like('buku_judul', $search)
        ->orLike('buku_penerbit', $search)
        ->orLike('buku_isbn', $search)
        ->orLike('buku_desc', $search)
        ->orLike('user_fullname', $search)
        ->orLike('username', $search)
        ->groupEnd();
}
if ($status && $status == 'terlambat') {
    $getpeminjaman->where('peminjaman_status', 'pinjam');
    $getpeminjaman->where('peminjaman_date_end <', $today);
} elseif ($status && $status != 'terlambat') {
    $getpeminjaman->where('peminjaman_status', $status);
    if ($status == 'pinjam') {
        $getpeminjaman->where('peminjaman_date_end >', $today);
    }
}
$getdata = $getpeminjaman->limit($limit, $offset)->get()->getResult();

$gettotal = $this->db->table('tb_peminjaman')->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')->join('tb_users', 'tb_users.id = tb_peminjaman.peminjaman_userid', 'left')->orderBy('peminjaman_date_add', 'desc');
if ($search) {
    $gettotal->groupStart()
        ->like('buku_judul', $search)
        ->orLike('buku_penerbit', $search)
        ->orLike('buku_isbn', $search)
        ->orLike('buku_desc', $search)
        ->orLike('user_fullname', $search)
        ->orLike('username', $search)
        ->groupEnd();
}
if ($status && $status == 'terlambat') {
    $gettotal->where('peminjaman_status', 'pinjam');
    $gettotal->where('peminjaman_date_end <', $today);
} elseif ($status && $status != 'terlambat') {
    $gettotal->where('peminjaman_status', $status);
    if ($status == 'pinjam') {
        $gettotal->where('peminjaman_date_end >', $today);
    }
}
$total = $gettotal->countAllResults();


$total_pinjam = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'pinjam')->where('peminjaman_date_end >', $today)->get()->getNumRows();
$total_pending = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'pending')->get()->getNumRows();
$total_kembali = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'selesai')->get()->getNumRows();
$total_sedang_kembali = $this->db->table('tb_peminjaman')->where('peminjaman_status', 'kembalikan')->get()->getNumRows();
$total_terlambat = $this->db->table('tb_peminjaman')
    ->where('peminjaman_date_end <', $today)
    ->where('peminjaman_status', 'pinjam')->get()->getNumRows();

$summaryCards = [
    ['label' => 'Pending',         'value' => $total_pending,        'icon' => 'bi-hourglass-split', 'color' => 'warning'],
    ['label' => 'Sedang Dipinjam', 'value' => $total_pinjam,         'icon' => 'bi-book',            'color' => 'primary'],
    ['label' => 'Menunggu Kembali', 'value' => $total_sedang_kembali, 'icon' => 'bi-arrow-repeat',    'color' => 'info'],
    ['label' => 'Selesai',         'value' => $total_kembali,        'icon' => 'bi-check-circle',    'color' => 'success'],
    ['label' => 'Terlambat',       'value' => $total_terlambat,      'icon' => 'bi-exclamation-octagon', 'color' => 'danger'],
];
?>

<style>
    .loan-summary-card {
        border: 1px solid #eef2f7;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .loan-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(17, 24, 39, .08) !important;
    }

    .loan-table thead th {
        font-size: .76rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #64748b;
        font-weight: 700;
        white-space: nowrap;
    }

    .loan-book-title {
        font-size: .86rem;
        line-height: 1.25;
    }

    .loan-row-late {
        background-color: #fff8f8;
    }

    .loan-status-badge {
        border-width: 1px;
        border-style: solid;
        font-weight: 600;
        font-size: .72rem;
        white-space: nowrap;
    }

    @media (max-width: 575.98px) {
        .loan-table td {
            min-width: 120px;
        }
    }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Peminjaman Buku</h5>
        <small class="text-muted">Kelola proses peminjaman dan konfirmasi buku anggota</small>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <?php foreach ($summaryCards as $card): ?>
        <div class="col-6 col-lg">
            <div class="card border-0 shadow-sm loan-summary-card h-100">
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

<!-- Filter & Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="get" id="formFilter" class="row g-2 align-items-center">
            <div class="col-12 col-md-7 col-lg-8">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" id="inputSearch" class="form-control border-start-0"
                        placeholder="Cari judul buku, nama anggota, atau username..." value="<?= esc($search) ?>">
                </div>
            </div>
            <div class="col-8 col-md-3 col-lg-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="pinjam" <?= $status === 'pinjam' ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="kembalikan" <?= $status === 'kembalikan' ? 'selected' : '' ?>>Menunggu Kembali</option>
                    <option value="terlambat" <?= $status === 'terlambat' ? 'selected' : '' ?>>Terlambat</option>
                    <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>
            <?php if ($search || $status): ?>
                <div class="col-4 col-md-2 col-lg-1 d-grid">
                    <a href="?" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 loan-table">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Buku</th>
                        <th>Anggota</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($getdata)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                <?= ($search || $status) ? 'Tidak ada data yang cocok dengan filter.' : 'Belum ada data peminjaman.' ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($getdata as $p):
                            $isLate = ($p->peminjaman_status == 'pinjam' && $p->peminjaman_date_end < $today);
                            $selisih = $p->peminjaman_date_end
                                ? floor(abs(strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', strtotime($p->peminjaman_date_end)))) / 86400)
                                : 0;
                            $coverSrc = $p->buku_cover
                                ? base_url('assets/upload/cover/thumbnail/' . $p->buku_cover)
                                : 'https://placehold.co/120x170/e2e8f0/94a3b8?text=Cover';

                            if ($isLate) {
                                $badge = ['label' => 'Terlambat', 'color' => 'danger', 'icon' => 'bi-exclamation-circle'];
                            } elseif ($p->peminjaman_status == 'pending') {
                                $badge = ['label' => 'Pending', 'color' => 'warning', 'icon' => 'bi-hourglass-split'];
                            } elseif ($p->peminjaman_status == 'pinjam') {
                                $badge = ['label' => 'Dipinjam', 'color' => 'primary', 'icon' => 'bi-bookmark-check'];
                            } elseif ($p->peminjaman_status == 'kembalikan') {
                                $badge = ['label' => 'Menunggu Kembali', 'color' => 'info', 'icon' => 'bi-arrow-repeat'];
                            } elseif ($p->peminjaman_status == 'selesai') {
                                $badge = ['label' => 'Selesai', 'color' => 'success', 'icon' => 'bi-check-circle'];
                            } else {
                                $badge = ['label' => ucfirst($p->peminjaman_status), 'color' => 'secondary', 'icon' => 'bi-circle'];
                            }
                        ?>
                            <tr class="<?= $isLate ? 'loan-row-late' : '' ?>">
                                <td class="ps-3 text-muted fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= $coverSrc ?>" alt="cover" class="rounded shadow-sm" style="width:44px;height:62px;object-fit:cover;">
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold loan-book-title text-truncate"><?= esc($p->buku_judul) ?></div>
                                            <small class="text-muted d-block text-truncate"><?= esc($p->buku_penulis) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= avatar($p->id) ?>" class="rounded-circle" width="40" height="40" alt="avatar">
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold text-truncate" style="font-size:13px;max-width:180px"><?= esc($p->user_fullname) ?></div>
                                            <div class="text-muted text-truncate" style="font-size:11px;max-width:180px">@<?= esc($p->username) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="fw-semibold"><?= date('d M Y', strtotime($p->peminjaman_date_start)) ?></small>
                                </td>
                                <td>
                                    <small class="<?= $isLate ? 'text-danger fw-semibold' : '' ?>">
                                        <?= date('d M Y', strtotime($p->peminjaman_date_end)) ?>
                                        <?php if ($isLate): ?>
                                            <span class="d-block" style="font-size:10px">Terlambat <?= $selisih ?> hari</span>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $badge['color'] ?> bg-opacity-10 text-<?= $badge['color'] ?> border border-<?= $badge['color'] ?> loan-status-badge">
                                        <i class="bi <?= $badge['icon'] ?> me-1"></i><?= $badge['label'] ?>
                                    </span>
                                </td>
                                <td class="text-center pe-3">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-outline-info" title="Detail"
                                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                            data-bs-href="<?= site_url('admin/modal/detail-peminjaman?code=' . $p->peminjaman_code) ?>"
                                            data-bs-title="Detail Peminjaman">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php if ($p->peminjaman_status == 'pending') { ?>
                                            <button class="btn btn-sm btn-success" title="Konfirmasi"
                                                onclick="approve('<?= esc($p->peminjaman_code) ?>','<?= esc($p->buku_judul) ?>','<?= esc($p->user_fullname) ?>')">
                                                <i class="bi bi-check"></i> Konfirmasi
                                            </button>
                                        <?php } ?>
                                        <?php if ($p->peminjaman_status == 'kembalikan') { ?>
                                            <button class="btn btn-sm btn-secondary" title="Konfirmasi"
                                                onclick="terima('<?= esc($p->peminjaman_code) ?>','<?= esc($p->buku_judul) ?>','<?= esc($p->user_fullname) ?>')">
                                                <i class="bi bi-check"></i> Terima
                                            </button>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan <?= count($getdata) ?> dari <?= $total ?> data</small>
        <?php echo pagination(page_url(), $total, $limit) ?>
    </div>
</div>

<script>
    let searchTimer;
    const inputSearch = document.getElementById('inputSearch');
    if (inputSearch) {
        inputSearch.addEventListener('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => document.getElementById('formFilter').submit(), 450);
        });
    }

    function approve(kode, judul, nama) {
        Swal.fire({
            title: 'Konfirmasi',
            text: `"${judul}" akan dipinjamkan kepada ${nama}`,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Konfirmasi',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/approve_peminjaman') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            code: kode,
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
                        $('#btn020').prop('disabled', false).text('Tambahkan');
                    })
            }
        });
    }

    function terima(kode, judul, nama) {
        Swal.fire({
            title: 'Konfirmasi',
            text: `Menerima Buku "${judul}" yang telah dipinjam ${nama}`,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Terima',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/terima_buku') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            code: kode,
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
                        $('#btn020').prop('disabled', false).text('Tambahkan');
                    })
            }
        });
    }
</script>