<?php
$code = get('code');
$buku = $this->db->table('tb_buku')
    ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
    ->join('tb_rak_buku', 'tb_rak_buku.rak_id = tb_buku.buku_rak_id', 'left')
    ->where('buku_code', $code)
    ->get()->getRow();

if (!$buku): ?>
    <div class="text-center py-4 text-muted">
        <i class="bi bi-exclamation-circle fs-1"></i>
        <p class="mt-2">Buku tidak ditemukan.</p>
    </div>
<?php return; endif;

$dipinjam = (int)$this->db->table('tb_peminjaman')
    ->where('peminjaman_buku_id', $buku->buku_id)
    ->where('peminjaman_status', 'pinjam')
    ->countAllResults();

$tersedia = $buku->buku_stok - $dipinjam;
$stokColor = $tersedia <= 0 ? 'danger' : ($tersedia <= 2 ? 'warning' : 'success');
?>

<div class="row g-3">
    <!-- Cover -->
    <div class="col-md-4 text-center">
        <img src="<?= $buku->buku_cover ? base_url('assets/upload/cover/' . $buku->buku_cover) : 'https://placehold.co/200x280/e2e8f0/94a3b8?text=No+Cover' ?>"
            class="rounded shadow-sm" style="width:100%;max-width:200px;height:280px;object-fit:cover;">
        <?php if($buku->buku_status == 1){ ?>

        <div class="mt-3">
            <span class="badge bg-<?= $stokColor ?> fs-6 px-3 py-2">
                <?= $tersedia <= 0 ? 'Stok Habis' : $tersedia . ' Tersedia' ?>
            </span>
            <div class="text-muted mt-1" style="font-size:12px">
                <?= $dipinjam ?> dipinjam &bull; <?= $buku->buku_stok ?> total stok
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Info -->
    <div class="col-md-8">
        <h5 class="fw-bold mb-1"><?= esc($buku->buku_judul) ?></h5>
        <div class="text-muted mb-3"><?= esc($buku->buku_penulis) ?></div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <?php if ($buku->kategori_nama): ?>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                    <i class="bi bi-tag me-1"></i><?= esc($buku->kategori_nama ?? 'tidak ada') ?>
                </span>
            <?php endif; ?>
            <?php if ($buku->rak_nama): ?>
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
                    <i class="bi bi-bookshelf me-1"></i><?= esc($buku->rak_nama) ?>
                </span>
            <?php endif; ?>
        </div>

        <table class="table table-sm table-borderless mb-0" style="font-size:14px">
            <tr>
                <td class="text-muted ps-0" style="width:110px">ISBN</td>
                <td class="fw-semibold"><?= esc($buku->buku_isbn) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="text-muted ps-0">Penerbit</td>
                <td class="fw-semibold"><?= esc($buku->buku_penerbit) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="text-muted ps-0">Tahun Terbit</td>
                <td class="fw-semibold"><?= esc($buku->buku_tahun) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="text-muted ps-0">Kode Buku</td>
                <td><code><?= esc($buku->buku_code) ?></code></td>
            </tr>
            <tr>
                <td class="text-muted ps-0">Ditambahkan</td>
                <td class="fw-semibold"><?= date('d M Y', strtotime($buku->buku_date_add)) ?></td>
            </tr>
        </table>
    </div>

    <!-- Deskripsi -->
    <?php if ($buku->buku_desc): ?>
    <div class="col-12">
        <hr class="my-1">
        <p class="text-muted mb-1" style="font-size:12px">SINOPSIS</p>
        <p class="mb-0" style="font-size:14px;line-height:1.7"><?= nl2br(esc($buku->buku_desc)) ?></p>
    </div>
    <?php endif; ?>
</div>


<script>
function hapusBuku(code) {
    Swal.fire({
        title: 'Hapus Buku?',
        text: 'Data buku akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.isConfirmed) {
            $.post('<?= site_url('admin/postdata/buku/delete_buku') ?>', { buku_code: code, <?= csrf_token() ?>: '<?= csrf_hash() ?>' })
                .done(data => {
                    Swal.fire('Dihapus!', 'Buku berhasil dihapus.', 'success').then(() => location.reload());
                });
        }
    });
}
</script>
