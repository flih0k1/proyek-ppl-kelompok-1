<?php

$limit  = 12;
$page   = max(1, (int) (get('page') ?? 1));
$offset = ($page - 1) * $limit;
$search      = get('search');
$status = get('status');
$no = 1;


$datarequest = $this->db->table('tb_request')
    ->join('tb_users', 'tb_users.id = tb_request.request_userid', 'left')
    ->orderBy('request_date_add', 'desc');
if ($search) {
    $datarequest->groupStart()
        ->like('request_buku_judul', $search)
        ->orLike('request_buku_penulis', $search)
        ->orLike('request_buku_tahun', $search)
        ->groupEnd();
}

if ($status) {
    $datarequest->where('request_status', $status);
}

$getdata = $datarequest->limit($limit, $offset)->get()->getResult();
$total   = $datarequest->countAllResults();
$menunggu = $this->db->table('tb_request')->where('request_status', 'pending')->countAllResults();
$aktif   = $this->db->table('tb_request')->whereIn('request_status', ['approved', 'process'])->countAllResults();
$tolak = $this->db->table('tb_request')->where('request_status', 'rejected')->countAllResults();
$selesai = $this->db->table('tb_request')->where('request_status', 'done')->countAllResults();
$cancel = $this->db->table('tb_request')->where('request_status', 'cancel')->countAllResults();

?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Request Buku</h5>
        <small class="text-muted">Kelola permintaan pengadaan buku baru dari anggota perpustakaan</small>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <?php
    $summary = [
        ['label' => 'Menunggu',    'value' => $menunggu,       'icon' => 'bi-hourglass-split', 'color' => 'warning'],
        ['label' => 'Diproses',    'value' => $aktif,          'icon' => 'bi-gear-wide-connected', 'color' => 'info'],
        ['label' => 'Selesai',     'value' => $selesai,        'icon' => 'bi-check-circle',    'color' => 'success'],
        ['label' => 'Ditolak',     'value' => $tolak + $cancel,'icon' => 'bi-x-circle',        'color' => 'danger'],
    ];
    foreach ($summary as $s): 
    ?>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-<?= $s['color'] ?> bg-opacity-10 text-<?= $s['color'] ?>">
                        <i class="bi <?= $s['icon'] ?> fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-0"><?= $s['label'] ?></div>
                        <div class="fs-4 fw-bold lh-1"><?= $s['value'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-12 col-md-7">
                <form method="get" class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul, penulis, atau nama anggota..." value="<?= esc($search) ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>
            <div class="col-12 col-md-4">
                <select class="form-select" id="filterStatus" onchange="window.location.href='?status=' + this.value">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>Disetujui</option>
                    <option value="process" <?= $status == 'process' ? 'selected' : '' ?>>Proses Pengadaan</option>
                    <option value="done" <?= $status == 'done' ? 'selected' : '' ?>>Selesai</option>
                    <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                    <option value="cancel" <?= $status == 'cancel' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>
            <div class="col-12 col-md-1 d-grid">
                <a href="?" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Request -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableRequest">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Anggota</th>
                        <th>Informasi Buku</th>
                        <th>Tgl Request</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($getdata)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                Tidak ada data request ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($getdata as $r): ?>
                        <tr>
                            <td class="ps-3 text-center">
                                <small class="fw-semibold text-muted"><?= $no++ ?></small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= avatar($r->id) ?>" class="rounded-circle shadow-sm" width="38" height="38">
                                    <div class="overflow-hidden">
                                        <div class="small fw-semibold"><?= esc($r->user_fullname) ?></div>
                                        <div class="text-muted text-truncate" style="font-size:11px; max-width: 150px;"><?= $r->email ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold small"><?= esc($r->request_buku_judul) ?></div>
                                <div class="text-muted small" style="font-size:11px">
                                    <span class="text-primary fw-medium"><?= esc($r->request_buku_penulis) ?></span> 
                                    &bull; <?= $r->request_buku_tahun ?: 'Tahun -' ?>
                                </div>
                            </td>
                            <td><small class="text-muted"><?= date('d M Y', strtotime($r->request_date_add)) ?></small></td>
                            <td>
                                <?php echo badge($r->request_status) ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <?php if ($r->request_status === 'pending'): ?>
                                        <button class="btn btn-sm btn-success px-2" title="Konfirmasi"
                                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                            data-bs-href="<?= site_url('admin/modal/tanggapi-request?code=' . $r->request_code) ?>"
                                            data-bs-title="Konfirmasi Request Buku">
                                            <i class="bi bi-check-lg me-1"></i> Tanggapi
                                        </button>
                                    <?php elseif ($r->request_status === 'approved'): ?>
                                        <button class="btn btn-sm btn-warning px-2" title="Update Status"
                                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                            data-bs-href="<?= site_url('admin/modal/update-request?code=' . $r->request_code) ?>"
                                            data-bs-title="Update Status Request Buku">
                                            <i class="bi bi-pencil-square me-1"></i> Update
                                        </button>
                                    <?php elseif ($r->request_status === 'process'): ?>
                                        <button class="btn btn-sm btn-primary px-2" title="Tambahkan Buku"
                                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                            data-bs-href="<?= site_url('admin/modal/add-buku-from-request?code=' . $r->request_code) ?>"
                                            data-bs-title="Input Buku Baru">
                                            <i class="bi bi-plus-circle me-1"></i> Input Koleksi
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-info" title="Detail"
                                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                            data-bs-href="<?= site_url('admin/modal/detail-request?code=' . $r->request_code) ?>"
                                            data-bs-title="Detail Request Buku">
                                            <i class="bi bi-info-circle"></i> Detail
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan <?= count($getdata) ?> request</small>
        <?php echo pagination(page_url(), $total, $limit) ?>

    </div>
</div>