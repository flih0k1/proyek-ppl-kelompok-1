<?php
$filterKat = get('kat');
$stok      = get('stok');
$search    = get('search');
?>

<style>
.buku-card {
    transition: transform .15s, box-shadow .15s;
}
.buku-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.12) !important;
}
.buku-cover {
    width: 100%;
    aspect-ratio: 2/3;
    object-fit: cover;
    border-radius: 8px 8px 0 0;
}
.buku-cover-list {
    width: 56px;
    height: 78px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
}
.view-btn.active { background: #4f46e5; color: #fff; border-color: #4f46e5; }
.stok-badge-habis  { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
.stok-badge-ok     { background: #dcfce7; color: #16a34a; border: 1px solid #86efac; }
.stok-badge-tipis  { background: #fef9c3; color: #ca8a04; border: 1px solid #fde047; }
.search-hero {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 14px;
    padding: 28px 32px;
    color: #fff;
    margin-bottom: 24px;
}
</style>

<!-- Hero Search -->
<div class="search-hero">
    <h5 class="fw-bold mb-1"><i class="bi bi-search me-2"></i>Cari Buku</h5>
    <p class="opacity-75 small mb-3">Temukan buku yang kamu inginkan dari koleksi perpustakaan</p>
    <form method="get" action="" id="formFilter">
        <div class="d-flex gap-2">
            <div class="input-group flex-grow-1">
                <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-0 border-start-0 shadow-none"
                    placeholder="Cari judul, penulis, ISBN, penerbit..."
                    value="<?= esc($search) ?>" id="inputSearch">
            </div>
            <button type="submit" class="btn btn-warning fw-semibold px-4">
                Cari
            </button>
        </div>
        <!-- hidden fields agar filter tetap terbawa saat search -->
        <input type="hidden" name="kat"  value="<?= esc($filterKat) ?>">
        <input type="hidden" name="stok" value="<?= esc($stok) ?>">
    </form>
</div>

<!-- Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="get" action="" id="formFilterBar">
            <input type="hidden" name="search" value="<?= esc($search) ?>">
            <div class="d-flex flex-wrap gap-2 align-items-center">

                <!-- Kategori -->
                <select class="form-select form-select-sm w-auto" name="kat" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <?php
                    $kategoriList = $this->db->table('tb_kategori_buku')->get()->getResult();
                    foreach ($kategoriList as $value): ?>
                        <option value="<?= $value->kategori_id ?>"
                            <?= $filterKat == $value->kategori_id ? 'selected' : '' ?>>
                            <?= esc($value->kategori_nama) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Stok -->
                <select class="form-select form-select-sm w-auto" name="stok" onchange="this.form.submit()">
                    <option value="">Semua Stok</option>
                    <option value="tersedia" <?= $stok === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="habis"    <?= $stok === 'habis'    ? 'selected' : '' ?>>Stok Habis</option>
                </select>

                <!-- Reset -->
                <?php if ($search || $filterKat || $stok): ?>
                <a href="?" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Reset Filter
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php
// ── Query ─────────────────────────────────────────────────────────────────────
$limit  = 12;
$page   = max(1, (int)(get('page') ?? 1));
$offset = ($page - 1) * $limit;
$no     = $offset + 1;

$base = function() use ($search, $filterKat, $stok) {
    $q = $this->db->table('tb_buku')
        ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
        ->orderBy('buku_date_add', 'desc');
    if ($search) {
        $q->groupStart()
            ->like('buku_judul', $search)
            ->orLike('buku_penulis', $search)
            ->orLike('buku_penerbit', $search)
            ->orLike('buku_isbn', $search)
            ->orLike('buku_desc', $search)
            ->groupEnd();
    }
    if ($filterKat) $q->where('buku_kategori_id', $filterKat);
    if ($stok === 'tersedia') $q->where('buku_stok >', 0);
    if ($stok === 'habis')    $q->where('buku_stok', 0);
    return $q;
};

$getdata = (clone $base())->limit($limit, $offset)->get()->getResult();
$total   = (clone $base())->countAllResults();
?>

<!-- Result Info -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted small">
        <?php if ($search): ?>
            Hasil pencarian untuk <strong>"<?= esc($search) ?>"</strong> —
        <?php endif; ?>
        Menampilkan <strong><?= count($getdata) ?></strong> dari <strong><?= $total ?></strong> buku
    </div>
</div>

<?php if (empty($getdata)): ?>
<!-- Empty State -->
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-search fs-1 text-muted opacity-25 d-block mb-3"></i>
        <h6 class="fw-bold">Buku tidak ditemukan</h6>
        <p class="text-muted small mb-3">Coba kata kunci lain atau ubah filter pencarian.</p>
        <a href="?" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Pencarian
        </a>
    </div>
</div>

<?php else: ?>
<div class="row g-3 mb-4">
    <?php foreach ($getdata as $b):
        $dipinjam  = model('Usermodel')->buku_outstok($b->buku_id); 
        $tersedia  = $b->buku_stok - $dipinjam;
        $tersedia  = max(0, $tersedia);
        $stokClass = $tersedia <= 0 ? 'stok-badge-habis' : ($tersedia <= 2 ? 'stok-badge-tipis' : 'stok-badge-ok');
        $stokLabel = $tersedia <= 0 ? 'Stok Habis' : ($tersedia <= 2 ? "Sisa $tersedia" : "Tersedia $tersedia");
        $stokIcon  = $tersedia <= 0 ? 'bi-x-circle' : 'bi-check-circle';
        $coverSrc  = $b->buku_cover
            ? base_url('assets/upload/cover/thumbnail/' . $b->buku_cover)
            : 'https://placehold.co/200x280/e2e8f0/94a3b8?text=No+Cover';
    ?>
    <div class="col-6 col-md-4 col-xl-3 col-xxl-2">
        <div class="card border-0 shadow-sm buku-card h-100">
            <!-- Cover -->
            <div class="position-relative">
                <img src="<?= $coverSrc ?>" class="buku-cover" alt="<?= esc($b->buku_judul) ?>">
                <span class="badge position-absolute top-0 end-0 m-2 <?= $stokClass ?>" style="font-size:10px">
                    <i class="bi <?= $stokIcon ?> me-1"></i><?= $stokLabel ?>
                </span>
            </div>
            <!-- Info -->
            <div class="card-body p-2 pb-1">
                <div class="small fw-semibold lh-sm mb-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                    <?= esc($b->buku_judul) ?>
                </div>
                <div class="text-muted" style="font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    <?= esc($b->buku_penulis) ?>
                </div>
                <?php if (!empty($b->kategori_nama)): ?>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary mt-1" style="font-size:10px">
                    <?= esc($b->kategori_nama) ?>
                </span>
                <?php endif; ?>
            </div>
            <!-- Aksi -->
            <div class="card-footer bg-white border-top p-2 d-flex gap-1">
                <button class="btn btn-sm btn-outline-info flex-shrink-0"
                    data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                    data-bs-href="<?= site_url('member/modal/detail-buku?code=' . $b->buku_code) ?>"
                    data-bs-title="<?= esc($b->buku_judul) ?>">
                    <i class="bi bi-eye"></i>
                </button>

                <?php if ($b->buku_status == 1): ?>
                <button class="btn btn-sm btn-primary flex-grow-1 <?= $tersedia <= 0 ? 'disabled' : '' ?>"
                    <?= $tersedia > 0 ? "data-bs-toggle=\"modal\" data-bs-target=\"#dinamicModal2\"
                    data-bs-href=\"" . site_url('member/modal/pinjam-buku?code=' . $b->buku_code) . "\"
                    data-bs-title=\"Pinjam Buku\"" : 'disabled' ?>>
                    <i class="bi bi-bookmark-plus me-1"></i>
                    <?= $tersedia <= 0 ? 'Habis' : 'Pinjam' ?>
                </button>
                <?php else: ?>
                    <button class="btn btn-sm btn-danger flex-grow-1 disabled">
                        <i class="bi bi-x me-1"></i>
                        Tidak Tersedia
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>


<!-- Pagination -->
<div class="d-flex justify-content-between align-items-center">
    <small class="text-muted">
        Halaman <?= $page ?> dari <?= max(1, ceil($total / $limit)) ?>
    </small>
    <?php echo pagination(page_url(), $total, $limit) ?>
</div>