<?php
$filterKat = get('kat');
$stok = get('stok');
$search = get('search');
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Data Kategori Buku</h5>
        <small class="text-muted">Kelola seluruh kategori buku perpustakaan</small>
    </div>
    <a data-bs-href="<?php echo site_url('admin/modal/add-kategori') ?>"
        data-bs-title="Tambah Kategori Buku"
        data-bs-remote="false"
        data-bs-toggle="modal"
        data-bs-target="#dinamicModal2"
        data-bs-bg="bg-success"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        class="btn btn-primary"
        title="Tambah Kategori Buku">
        <i class="bi bi-plus-circle me-2"></i>
        Tambah Kategori
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

<!-- Tabel Buku -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableBuku">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Kategori</th>
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
                    $getkategori = $this->db->table('tb_kategori_buku')->orderBy('kategori_nama', 'asc');
                    if ($search) {
                        $getkategori->groupStart()
                            ->like('kategori_nama', $search)
                            ->groupEnd();
                    }
                    $getdata = $getkategori->limit($limit, $offset)->get()->getResult();

                    $gettotal = $this->db->table('tb_kategori_buku');
                      if ($search) {
                        $gettotal->groupStart()
                            ->like('kategori_nama', $search)
                            ->groupEnd();
                    }
                    $total = $gettotal->countAllResults();
                    foreach ($getdata as $i => $kat):
                        $tot_buku = $this->db->table('tb_buku')->where('buku_kategori_id', $kat->kategori_id)->countAllResults();
?>
                        <tr>
                            <td class="ps-3 text-muted"><?= $no++ ?></td>
                            <td><?php echo $kat->kategori_nama ?></td>
                            <td><?php echo $tot_buku ?></td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                       <button class="btn btn-sm btn-outline-info" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/detail-kategori?id=' . $kat->kategori_id) ?>"
                                        data-bs-title="Detail Buku">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/update-kategori?id=' . $kat->kategori_id) ?>"
                                        data-bs-title="Edit Buku">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="hapusKategori('<?php echo $kat->kategori_id ?>')">
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
        <small class="text-muted">Menampilkan <?= count($getdata) ?> buku</small>
        <?php echo pagination(page_url(), $total, $limit) ?>
    </div>
</div>


<script>
    function hapusKategori(id) {
        Swal.fire({
            title: 'Hapus Buku?',
            text: 'Data kategori akan dihapus permanen!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/delete_kategori') ?>',
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
                        $('#btn020').prop('disabled', false).text('Tambahkan');
                    })
            }
        });
    }
</script>