<?php
$code       = get('code');
$peminjaman = $this->db->table('tb_peminjaman')
    ->join('tb_buku',     'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->join('tb_users',    'tb_users.id = tb_peminjaman.peminjaman_userid', 'left')
    ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
    ->where('peminjaman_code', $code)
    ->get()->getRow();

if (!$peminjaman): ?>
    <div class="text-center py-4 text-muted">
        <i class="bi bi-exclamation-circle fs-1 d-block mb-2"></i>
        <p class="mb-0">Data peminjaman tidak ditemukan.</p>
    </div>
<?php return; endif;

$tglJatuh   = $peminjaman->peminjaman_date_end;
$tglKembali = $peminjaman->peminjaman_date_kembali ?? date('Y-m-d 00:00:00');

$terlambat = 0;

if ($tglJatuh) {
    $jatuh   = strtotime($tglJatuh);
    $kembali = strtotime($tglKembali);

    if ($kembali > $jatuh) {
        $selisih = $kembali - $jatuh;
        $terlambat = floor($selisih / (60 * 60 * 24)); // konversi ke hari
    }
}

$denda = $peminjaman->peminjaman_denda ?? ($terlambat * option('denda')['option_desc1']);

$statusCfg = [
    'pinjam'    => ['label' => 'Dipinjam',  'color' => 'primary'],
    'kembali'   => ['label' => 'Kembali',   'color' => 'success'],
    'terlambat' => ['label' => 'Terlambat', 'color' => 'danger'],
];
$cfg = $statusCfg[$peminjaman->peminjaman_status] ?? ['label' => ucfirst($peminjaman->peminjaman_status), 'color' => 'secondary'];

$coverSrc  = !empty($peminjaman->buku_cover)
    ? base_url('assets/upload/cover/thumbnail/' . $peminjaman->buku_cover)
    : 'https://placehold.co/52x72/e2e8f0/94a3b8?text=No+Cover';

$avatarSrc = avatar($peminjaman->id);
?>

<!-- Header: Anggota & Buku -->
<div class="d-flex gap-3 align-items-center p-3 bg-light rounded mb-3">
    <img src="<?= $avatarSrc ?>" class="rounded-circle flex-shrink-0" width="52" height="52">
    <div class="flex-grow-1 overflow-hidden">
        <div class="fw-bold text-truncate"><?= esc(($peminjaman->user_fullname ?? '')) ?></div>
        <div class="text-muted small"><?= esc($peminjaman->email ?? '—') ?></div>
    </div>
    <img src="<?= $coverSrc ?>" class="rounded flex-shrink-0" width="40" height="56" style="object-fit:cover">
    <div class="overflow-hidden">
        <div class="fw-semibold small text-truncate" style="max-width:120px"><?= esc($peminjaman->buku_judul ?? '—') ?></div>
        <div class="text-muted" style="font-size:11px"><?= esc($peminjaman->buku_penerbit ?? '—') ?></div>
        <?php if (!empty($peminjaman->kategori_nama)): ?>
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary" style="font-size:10px">
            <?= esc($peminjaman->kategori_nama) ?>
        </span>
        <?php endif; ?>
    </div>
</div>

<!-- Detail List -->
<ul class="list-group list-group-flush mb-3">
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Kode Peminjaman</span>
        <span class="fw-semibold small"><code><?= esc($peminjaman->peminjaman_code) ?></code></span>
    </li>
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Tgl Pinjam</span>
        <span class="fw-semibold small">
            <?= $peminjaman->peminjaman_date_start ? date('d M Y', strtotime($peminjaman->peminjaman_date_start)) : '—' ?>
        </span>
    </li>
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Jatuh Tempo</span>
        <span class="fw-semibold small <?= $terlambat > 0 ? 'text-danger' : '' ?>">
            <?= $tglJatuh ? date('d M Y', strtotime($tglJatuh)) : '—' ?>
        </span>
    </li>
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Tgl Kembali</span>
        <span class="fw-semibold small">
            <?= $tglKembali ? date('d M Y', strtotime($tglKembali)) : '<span class="text-muted">Belum dikembalikan</span>' ?>
        </span>
    </li>
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Durasi Pinjam</span>
        <span class="fw-semibold small">
            <?= $peminjaman->peminjaman_durasi ?? 3 ?> hari
        </span>
    </li>
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Keterlambatan</span>
        <span>
            <?php if ($terlambat > 0): ?>
                <span class="badge bg-danger"><i class="bi bi-clock me-1"></i><?= $terlambat ?> hari</span>
            <?php elseif ($peminjaman->peminjaman_status === 'kembali'): ?>
                <span class="badge bg-success"><i class="bi bi-check me-1"></i>Tepat Waktu</span>
            <?php else: ?>
                <span class="text-muted small">—</span>
            <?php endif; ?>
        </span>
    </li>
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Denda</span>
        <span>
            <?php if ($denda > 0): ?>
                <span class="fw-semibold text-danger small"><?= rp($denda) ?></span>
            <?php else: ?>
                <span class="text-muted small">—</span>
            <?php endif; ?>
        </span>
    </li>
    <li class="list-group-item d-flex justify-content-between py-2">
        <span class="text-muted small">Status</span>
        <span class="badge bg-<?= $cfg['color'] ?> bg-opacity-10 text-<?= $cfg['color'] ?> border border-<?= $cfg['color'] ?>">
            <i class="bi bi-circle-fill me-1" style="font-size:7px"></i><?= $cfg['label'] ?>
        </span>
    </li>
    <?php if (!empty($peminjaman->peminjaman_desc)): ?>
    <li class="list-group-item py-2">
        <div class="text-muted small mb-1">Catatan</div>
        <div class="small p-2 bg-light rounded"><?= esc($peminjaman->peminjaman_desc) ?></div>
    </li>
    <?php endif; ?>
</ul>

<!-- Footer Aksi -->
<div class="d-flex gap-2">
    <?php if ($peminjaman->peminjaman_status === 'pinjam'): ?>
    <button class="btn btn-success btn-sm flex-grow-1"
        onclick="konfirmasiKembali('<?= esc($peminjaman->peminjaman_code) ?>', '<?= esc($peminjaman->buku_judul) ?>', <?= $denda ?>)">
        <i class="bi bi-patch-check me-1"></i>Kembalikan Buku
    </button>
    <?php endif; ?>
</div>

<script>
function konfirmasiKembali(code, judul, denda) {
    const dendaInfo = denda > 0
        ? `<br><small class="text-danger">Denda: <b>Rp ${denda.toLocaleString('id-ID')}</b></small>`
        : `<br><small class="text-success">Tidak ada denda</small>`;

    Swal.fire({
        title: 'Konfirmasi Pengembalian?',
        html: `Buku <b>${judul}</b> telah diterima kembali?${dendaInfo}`,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-patch-check me-1"></i>Ya, Konfirmasi',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.value){

            $.post('<?= site_url('member/postdata/Pinjam/kembalikan') ?>', {
                code: code,
                csrf_myapp: $('input[name=csrf_myapp]').val()
        }).done(res => {
            updateCSRF(res.csrf_data);
            Swal.fire(res.heading, res.message, res.type).then(() => {
                if (res.status) {
                    $('#dinamicModal2').modal('hide');
                    location.reload();
                }
            });
        })
    }
    });
}
</script>
