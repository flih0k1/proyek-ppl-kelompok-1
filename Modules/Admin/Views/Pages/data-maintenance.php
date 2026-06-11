<?php
$limit = 10;
$page  = (int)(get('page') ?? 1);
$page  = $page > 0 ? $page : 1;
$offset = ($page - 1) * $limit;
$no     = $offset + 1;
$search = get('search');
$status = get('status');

$builder = $this->db->table('tb_maintenance')
    ->join('tb_buku', 'tb_buku.buku_id = tb_maintenance.mt_buku_id', 'left')
    ->orderBy('mt_date_add', 'desc');

if ($search) {
    $builder->groupStart()
        ->like('buku_judul', $search)
        ->orLike('mt_desc', $search)
        ->groupEnd();
}
if ($status) {
    $builder->where('mt_status', $status);
}

$getdata = $builder->limit($limit, $offset)->get()->getResult();

// Hitung Total untuk Pagination (tanpa limit/offset)
$countBuilder = $this->db->table('tb_maintenance');
if ($search) {
    $countBuilder->join('tb_buku', 'tb_buku.buku_id = tb_maintenance.mt_buku_id', 'left')
        ->groupStart()
        ->like('buku_judul', $search)
        ->orLike('mt_desc', $search)
        ->groupEnd();
}
if ($status) $countBuilder->where('mt_status', $status);
$total = $countBuilder->countAllResults();

// Statistik Widget
$total_proses = $this->db->table('tb_maintenance')->where('mt_status', 'process')->countAllResults();
$total_selesai = $this->db->table('tb_maintenance')->where('mt_status', 'selesai')->countAllResults();
$total_rusak = $this->db->table('tb_buku')->where('buku_stok <', 1)->countAllResults();

$summaryCards = [
    ['label' => 'Dalam Perbaikan', 'value' => $total_proses,  'icon' => 'bi-gear-wide-connected', 'color' => 'warning'],
    ['label' => 'Selesai Rawat',  'value' => $total_selesai, 'icon' => 'bi-check-all',          'color' => 'success'],
];
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Maintenance & Perawatan Buku</h5>
        <small class="text-muted">Pantau kondisi fisik dan perbaikan koleksi perpustakaan</small>
    </div>
</div>

<!-- Summary Widgets -->
<div class="row g-3 mb-4">
    <?php foreach ($summaryCards as $card): ?>
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-<?= $card['color'] ?> bg-opacity-10 text-<?= $card['color'] ?>">
                        <i class="bi <?= $card['icon'] ?> fs-3"></i>
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

<div class="row g-4">
    <!-- Form Tambah Maintenance -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Input Perawatan Baru</h6>
            </div>
            <div class="card-body">
                <form id="formMaintenance">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Buku</label>
                        <select name="mt_buku_id" class="form-select" required>
                            <option value="">-- Pilih Koleksi --</option>
                            <?php
                            $buku = $this->db->table('tb_buku')->orderBy('buku_judul', 'asc')->get()->getResult();
                            foreach ($buku as $b): ?>
                                <option value="<?= $b->buku_id ?>"><?= esc($b->buku_judul) ?> (<?= $b->buku_isbn ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jenis Perawatan</label>
                        <select name="mt_tipe" class="form-select" required>
                            <option value="Perbaikan Fisik">Perbaikan Fisik (Jilid)</option>
                            <option value="Ganti Sampul">Ganti Sampul</option>
                            <option value="Pembersihan">Pembersihan Berkala</option>
                            <option value="Restorasi">Restorasi Halaman</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Kerusakan</label>
                        <textarea name="mt_desc" class="form-control" rows="3" placeholder="Contoh: Halaman 10-15 lepas, sampul robek..."></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data Maintenance -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <form method="get" class="row g-2">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul buku..." value="<?= esc($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="proses" <?= $status == 'proses' ? 'selected' : '' ?>>Proses</option>
                            <option value="selesai" <?= $status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Buku</th>
                                <th>Jenis</th>
                                <th>Deskripsi</th>
                                <th>Tgl Mulai</th>
                                <th class="text-center pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($getdata)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada data perawatan.</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($getdata as $row): ?>
                                <tr>
                                    <td class="ps-3 text-muted small"><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-semibold small"><?= esc($row->buku_judul) ?></div>
                                        <div class="text-muted" style="font-size: 10px;">ID: <?= $row->mt_buku_id ?></div>
                                    </td>
                                    <td><span class="small"><?= $row->mt_tipe ?></span></td>
                                    <td><?= $row->mt_desc ?></td>
                                    <td><small><?= date('d M Y', strtotime($row->mt_date_start)) ?></small> <br>
                                <?php echo badge($row->mt_status) ?></td>
                                   
                                    <td class="text-center pe-3">
                                            <?php if ($row->mt_status == 'process'): ?>
                                                <button class="btn btn-sm btn-success mb-1" title="Selesaikan" onclick="updateStatus('<?= $row->mt_code ?>', 'selesai')">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                                <?php elseif ($row->mt_status == 'pending'): ?>
                                                <button class="btn btn-sm btn-danger mb-1" title="Hapus" onclick="deletemt('<?= $row->mt_code ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <?= pagination(page_url(), $total, $limit) ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('#formMaintenance').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Data?',
            text: "Pastikan data perawatan sudah benar.",
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/add_maintenance') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: $('#formMaintenance').serialize(),
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
    });

    function updateStatus(code) {
        Swal.fire({
            title: 'Selesaikan Perawatan?',
            text: "Buku akan ditandai kembali tersedia.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#198754'
        }).then((res) => {
            if (res.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/update_maintenance') ?>',
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
                        $('#btn020').prop('disabled', false).text('Tambahkan');
                    })
            }
        });
    }
    function deletemt(code) {
        Swal.fire({
            title: 'Kofirmasi?',
            text: "Data Perawatan Buku Akan Dihapus.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754'
        }).then((res) => {
            if (res.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/delete_maintenance') ?>',
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
                        $('#btn020').prop('disabled', false).text('Tambahkan');
                    })
            }
        });
    }
</script>