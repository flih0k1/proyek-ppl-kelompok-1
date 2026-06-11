<?php
$id = get('id');

$penerbit = $this->db->table('tb_penerbit')->where('penerbit_id', $id)->get()->getRow();

if (!$penerbit): ?>
    <div class="text-center py-4 text-muted">
        <i class="bi bi-exclamation-circle fs-1"></i>
        <p class="mt-2">Penerbit tidak ditemukan.</p>
    </div>
<?php return; endif;

$buku_list = $this->db->table('tb_buku')
    ->where('buku_penerbit', $penerbit->penerbit_nama)
    ->orderBy('buku_judul', 'asc')
    ->get()->getResult();

$total = count($buku_list);
?>

<!-- Header Penerbit -->
<div class="d-flex align-items-center gap-3 mb-3">
    <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
        <i class="bi bi-building fs-4"></i>
    </div>
    <div>
        <h6 class="fw-bold mb-0"><?= esc($penerbit->penerbit_nama) ?></h6>
        <small class="text-muted"><?= $total ?> buku diterbitkan oleh penerbit ini</small>
    </div>
</div>

<hr class="my-2">

<!-- List Buku -->
<?php if (empty($buku_list)): ?>
    <div class="text-center text-muted py-4">
        <i class="bi bi-inbox fs-2 d-block mb-1"></i>
        Belum ada buku oleh penerbit ini.
    </div>
<?php else: ?>
    <div class="list-group list-group-flush" style="max-height:420px;overflow-y:auto">
        <?php foreach ($buku_list as $i => $b): ?>
        <div class="list-group-item px-0 py-2">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= $b->buku_cover ? base_url('assets/upload/cover/thumbnail/' . $b->buku_cover) : 'https://placehold.co/40x56/e2e8f0/94a3b8?text=?' ?>"
                    class="rounded" style="width:40px;height:56px;object-fit:cover;flex-shrink:0">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-semibold text-truncate"><?= esc($b->buku_judul) ?></div>
                    <small class="text-muted"><?= esc($b->buku_penulis) ?></small>
                    <div class="d-flex gap-2 mt-1">
                        <small class="text-muted"><i class="bi bi-calendar me-1"></i><?= esc($b->buku_tahun) ?: '-' ?></small>
                        <small class="text-muted"><i class="bi bi-upc me-1"></i><?= esc($b->buku_isbn) ?: '-' ?></small>
                    </div>
                </div>
                <span class="badge bg-<?= $b->buku_stok > 0 ? 'success' : 'danger' ?> flex-shrink-0">
                    <?= $b->buku_stok > 0 ? $b->buku_stok . ' stok' : 'Habis' ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
