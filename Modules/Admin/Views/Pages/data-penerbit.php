<?php
$search = get('search');
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Data Penerbit Buku</h5>
        <small class="text-muted">Kelola seluruh data master penerbit buku perpustakaan</small>
    </div>
    <a data-bs-href="<?php echo site_url('admin/modal/add-penerbit') ?>"
        data-bs-title="Tambah Penerbit Buku"
        data-bs-remote="false"
        data-bs-toggle="modal"
        data-bs-target="#dinamicModal2"
        data-bs-bg="bg-success"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        class="btn btn-primary"
        title="Tambah Penerbit Buku">
        <i class="bi bi-plus-circle me-2"></i>
        Tambah Penerbit
    </a>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="get" action="" id="formFilter">
            <div class="row g-2">
                <div class="col-12 col-md-11">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" id="searchBuku" placeholder="Cari penerbit..." value="<?= esc($search) ?>">
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

<!-- Tabel Penerbit -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableBuku">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nama Penerbit</th>
                        <th>Jumlah Buku</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $limit = 15;
                    $page  = (int)(get('page') ?? 1);
                    $page  = $page > 0 ? $page : 1;
                    $offset = ($page - 1) * $limit;
                    $no     = $offset + 1;                   
                    $getpenerbit = $this->db->table('tb_penerbit')->orderBy('penerbit_nama', 'asc');
                    if ($search) {
                        $getpenerbit->groupStart()
                            ->like('penerbit_nama', $search)
                            ->groupEnd();
                    }
                    $getdata = $getpenerbit->limit($limit, $offset)->get()->getResult();

                    $gettotal = $this->db->table('tb_penerbit');
                    if ($search) {
                        $gettotal->groupStart()
                            ->like('penerbit_nama', $search)
                            ->groupEnd();
                    }
                    $total = $gettotal->countAllResults();
                    foreach ($getdata as $i => $p):
                        $tot_buku = $this->db->table('tb_buku')->where('buku_penerbit', $p->penerbit_nama)->countAllResults();
?>
                        <tr>
                            <td class="ps-3 text-muted"><?= $no++ ?></td>
                            <td><?php echo esc($p->penerbit_nama) ?></td>
                            <td><?php echo $tot_buku ?></td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-outline-info" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/detail-penerbit?id=' . $p->penerbit_id) ?>"
                                        data-bs-title="Detail Penerbit">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/update-penerbit?id=' . $p->penerbit_id) ?>"
                                        data-bs-title="Edit Penerbit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="hapusPenerbit('<?php echo $p->penerbit_id ?>')">
                                        <i class="bi bi-trash"></i>
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
        <small class="text-muted">Menampilkan <?= count($getdata) ?> penerbit</small>
        <?php echo pagination(page_url(), $total, $limit) ?>
    </div>
</div>

<script>
    function hapusPenerbit(id) {
        Swal.fire({
            title: 'Hapus Penerbit?',
            text: 'Data penerbit akan dihapus! Buku dengan penerbit ini akan diset tanpa penerbit.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/delete_penerbit') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
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
