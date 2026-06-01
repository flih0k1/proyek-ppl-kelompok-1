<?php
$search = get('search');
$limit = 15;
$page  = (int)(get('page') ?? 1);
$page  = $page > 0 ? $page : 1;
$offset = ($page - 1) * $limit;
$no     = $offset + 1;

$query = $this->db->table('tb_rak_buku');
if ($search) $query->like('rak_nama', $search);
$getdata = $query->orderBy('rak_nama', 'asc')->get()->getResult();

$total_rak  = $this->db->table('tb_rak_buku')->countAllResults();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Rak Buku</h5>
        <small class="text-muted">Kelola rak penyimpanan buku perpustakaan</small>
    </div>
    <a data-bs-href="<?= site_url('admin/modal/add-rak') ?>"
        data-bs-title="Tambah Rak"
        data-bs-toggle="modal"
        data-bs-target="#dinamicModal2"
        data-bs-backdrop="static"
        class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Rak
    </a>
</div>


<!-- Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
             <form method="get" action="" id="formFilter">
            <div class="row g-2">
                <div class="col-12 col-md-11">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" id="searchBuku" placeholder="Cari judul, penulis, ISBN..." value="<?= esc($search) ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="col-12 col-md-1">
                    <div class="button-group">
                        <a href="?" class="btn btn-danger" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width:50px">#</th>
                        <th>Nama Rak</th>
                        <th>Jumlah Buku</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($getdata)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                Tidak ada data rak
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($getdata as $i => $r):
                            $jml_buku = $this->db->table('tb_buku')->where('buku_rak_id', $r->rak_id)->countAllResults();
                        ?>
                            <tr>
                                <td class="ps-3 text-muted"><?= $i + 1 ?></td>
                                <td class="fw-semibold"><?= esc($r->rak_nama) ?></td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                        <?= $jml_buku ?> buku
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-outline-warning" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                            data-bs-href="<?= site_url('admin/modal/update-rak?id=' . $r->rak_id) ?>"
                                            data-bs-title="Edit Rak">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus"
                                            onclick="hapusRak('<?= $r->rak_id ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
        <small class="text-muted">Menampilkan <?= count($getdata) ?> rak buku</small>
        <?php echo pagination(page_url(), $total_rak, $limit) ?>
    </div>
</div>

<script>
    function hapusRak(id) {
        Swal.fire({
            title: 'Hapus Rak?',
            text: `Rak akan dihapus permanen!`,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.value) {
                $.post('<?= site_url('admin/postdata/buku/delete_rak') ?>', {
                        id: id,
                        csrf_myapp: $('input[name=csrf_myapp]').val()
                    })
                    .done(data => {
                        updateCSRF(data.csrf_data);
                        Swal.fire(data.heading, data.message, data.type).then(() => {
                            if (data.status) {
                                location.reload();
                            }

                        });
                    });
            }
        });
    }
</script>