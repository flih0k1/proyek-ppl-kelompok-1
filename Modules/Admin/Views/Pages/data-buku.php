<?php
$filterKat = get('kat');
$stok = get('stok');
$search = get('search');
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Data Buku</h5>
        <small class="text-muted">Kelola seluruh koleksi buku perpustakaan</small>
    </div>
    <a data-bs-href="<?php echo site_url('admin/modal/add-buku') ?>"
        data-bs-title="Tambah Buku"
        data-bs-remote="false"
        data-bs-toggle="modal"
        data-bs-target="#dinamicModal2"
        data-bs-bg="bg-success"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        class="btn btn-primary"
        title="Tambah Buku">
        <i class="bi bi-plus-circle me-2"></i>
        Tambah Buku
    </a>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="get" action="" id="formFilter">
            <div class="row g-2">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" id="searchBuku" placeholder="Cari judul, penulis, ISBN..." value="<?= esc($search) ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select" name="kat" id="filterKategori" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <?php
                        $kategoriList = $this->db->table('tb_kategori_buku')->get()->getResult();
                        foreach ($kategoriList as $value) { ?>
                            <option value="<?= $value->kategori_id ?>" <?= $filterKat == $value->kategori_id ? 'selected' : '' ?>><?= esc($value->kategori_nama) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select" name="stok" id="filterStok" onchange="this.form.submit()">
                        <option value="">Semua Stok</option>
                        <option value="tersedia" <?= $stok === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="habis" <?= $stok === 'habis' ? 'selected' : '' ?>>Stok Habis</option>
                    </select>
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
                        <th>Cover</th>
                        <th>Judul & Penulis</th>
                        <th>ISBN</th>
                        <th>Kategori</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Stok</th>
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


                    $getbuku = $this->db->table('tb_buku')->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')->orderBy('buku_date_add', 'desc');
                    if ($search) {
                        $getbuku->groupStart()
                            ->like('buku_judul', $search)
                            ->orLike('buku_penerbit', $search)
                            ->orLike('buku_isbn', $search)
                            ->orLike('buku_desc', $search)
                            ->groupEnd();
                    }
                    if ($filterKat) $getbuku->where('buku_kategori_id', $filterKat);

                    $getdata = $getbuku->limit($limit, $offset)->get()->getResult();

                    $gettotal = $this->db->table('tb_buku')->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left');
                    if ($search) {
                        $gettotal->groupStart()
                            ->like('buku_judul', $search)
                            ->orLike('buku_penerbit', $search)
                            ->orLike('buku_isbn', $search)
                            ->orLike('buku_desc', $search)
                            ->groupEnd();
                    }
                    if ($filterKat) $gettotal->where('buku_kategori_id', $filterKat);

                    $total = $gettotal->countAllResults();
                    foreach ($getdata as $i => $b):
                        $dipinjam = model('Usermodel')->buku_outstok($b->buku_id);
                        $tersedia = $b->buku_stok - $dipinjam;
                        $stokColor = $tersedia <= 0 ? 'danger' : ($tersedia <= 2 ? 'warning' : 'success');
                    ?>
                        <tr>
                            <td class="ps-3 text-muted"><?= $no++ ?></td>
                            <td>
                                <img src="<?php echo ($b->buku_cover) ? base_url('assets/upload/cover/thumbnail/'.$b->buku_cover) : 'https://placehold.co/120x170/e2e8f0/94a3b8?text=Cover' ?>" alt="cover" class="rounded" style="width:48px;height:66px;object-fit:cover;">
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($b->buku_judul) ?></div>
                                <small class="text-muted"><?= esc($b->buku_penulis) ?></small>
                            </td>
                            <td><small class="text-muted"><?= esc($b->buku_isbn) ?></small></td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                    <?= esc($b->kategori_nama ?? 'tidak ada') ?>
                                </span>
                            </td>
                            <td><small><?= esc($b->buku_penerbit) ?></small></td>
                            <td><?= $b->buku_tahun ?></td>
                            <td>
                                <span class="badge bg-<?= $stokColor ?>">
                                    <?= $tersedia <= 0 ? 'Habis' : $tersedia . ' tersedia' ?>
                                </span>
                                <div class="text-muted" style="font-size:11px"><?= $dipinjam ?> dipinjam / <?= $b->buku_stok ?> total</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-outline-info" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/detail-buku?code=' . $b->buku_code) ?>"
                                        data-bs-title="Detail Buku">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/update-buku?code=' . $b->buku_code) ?>"
                                        data-bs-title="Edit Buku">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="hapusBuku('<?php echo $b->buku_code ?>')">
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
    function hapusBuku(code) {
        Swal.fire({
            title: 'Hapus Buku?',
            text: 'Data buku akan dihapus permanen!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.value) {
                $.ajax({
                        url: '<?php echo site_url('admin/postdata/buku/delete_buku') ?>',
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