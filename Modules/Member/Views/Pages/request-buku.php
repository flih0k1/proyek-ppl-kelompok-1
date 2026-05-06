<?php
$limit  = 12;
$page   = max(1, (int) (get('page') ?? 1));
$offset = ($page - 1) * $limit;
$search      = get('search');
$status = get('status');
$no = 1;


$datarequest = $this->db->table('tb_request')
    ->where('request_userid', userid())
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
$menunggu = $this->db->table('tb_request')->where('request_userid', userid())->where('request_status', 'pending')->countAllResults();
$aktif   = $this->db->table('tb_request')->where('request_userid', userid())->whereIn('request_status', ['approved', 'process'])->countAllResults();
$tolak = $this->db->table('tb_request')->where('request_userid', userid())->where('request_status', 'rejected')->countAllResults();
$selesai = $this->db->table('tb_request')->where('request_userid', userid())->where('request_status', 'done')->countAllResults();
$cancel = $this->db->table('tb_request')->where('request_userid', userid())->where('request_status', 'cancel')->countAllResults();

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Request Buku Baru</h5>
        <small class="text-muted">Ajukan buku yang belum tersedia di koleksi kami</small>
    </div>
    <button class="btn btn-primary"
        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
        data-bs-href="<?= site_url('member/modal/add-request') ?>"
        data-bs-title="Request Buku Baru">
        <i class="bi bi-journal-plus"></i> Request Buku Baru
    </button>
</div>

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

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Request Saya</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="border-0">NO</th>
                        <th class="border-0">BUKU</th>
                        <th class="border-0">PENULIS</th>
                        <th class="border-0">TAHUN TERBIT</th>
                        <th class="border-0">TGL REQUEST</th>
                        <th class="border-0">STATUS</th>
                        <th class="text-center border-0">AKSI</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php foreach ($getdata as $r):
                    ?>
                        <tr>
                            <td>
                              <?= $no++ ?>
                            </td>
                            <td>
                              <?= esc($r->request_buku_judul) ?>
                            </td>
                            <td>
                            <?= esc($r->request_buku_penulis) ?>
                            </td>
                            <td>
                            <?= esc($r->request_buku_tahun) ?>
                            </td>
                            <td><small class="text-muted"><?= $r->request_date_add ?></small></td>
                            <td>
                                <?php echo badge($r->request_status) ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info" title="Detail"
                                    data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                    data-bs-href="<?= site_url('member/modal/detail-request?code=' . $r->request_code) ?>"
                                    data-bs-title="Detail Request Buku">
                                    <i class="bi bi-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">

            <div class="d-flex justify-content-between align-items-center">
                <?= pagination(page_url(), $total, $limit) ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#formRequestBuku').submit(function(e) {
            e.preventDefault();

            // Simulasi pengiriman data
            Swal.fire({
                title: 'Sedang Mengirim...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Permintaan buku Anda telah dikirim dan akan segera ditinjau oleh tim perpustakaan.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    bootstrap.Modal.getInstance(document.getElementById('modalRequestBuku')).hide();
                    location.reload(); // Hanya simulasi refresh untuk menampilkan data baru (jika sudah ada backend)
                });
            }, 1500);
        });
    });
</script>