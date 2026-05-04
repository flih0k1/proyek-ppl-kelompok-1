<?php
$code = get('code');
$buku = $this->db->table('tb_buku')
    ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
    ->join('tb_rak_buku', 'tb_rak_buku.rak_id = tb_buku.buku_rak_id', 'left')
    ->where('buku_code', $code)
    ->get()->getRow();

if (!$buku): ?>
    <div class="text-center py-4 text-muted">
        <i class="bi bi-exclamation-circle fs-1 d-block mb-2"></i>
        <p class="mb-0">Buku tidak ditemukan.</p>
    </div>
<?php return;
endif;

$dipinjam = (int)$this->db->table('tb_peminjaman')
    ->where('peminjaman_buku_id', $buku->buku_id)
    ->where('peminjaman_status', 'pinjam')
    ->countAllResults();

$tersedia  = $buku->buku_stok - $dipinjam;
$stokColor = $tersedia <= 0 ? 'danger' : ($tersedia <= 2 ? 'warning' : 'success');

// Durasi pinjam default (hari)
$durasiDefault = 3;
$tglPinjam     = date('Y-m-d');
$tglKembali    = date('Y-m-d', strtotime("+{$durasiDefault} days"));
?>

<?php if ($tersedia <= 0): ?>
    <!-- Stok Habis -->
    <div class="text-center py-4">
        <i class="bi bi-bookmark-x text-danger d-block mb-2" style="font-size:48px"></i>
        <h6 class="fw-bold text-danger">Stok Habis</h6>
        <p class="text-muted small mb-3">Buku <strong><?= esc($buku->buku_judul) ?></strong> sedang tidak tersedia.</p>
        <div class="p-3 bg-light rounded small text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Kamu bisa mengajukan <strong>request buku</strong> agar admin menambah stok.
        </div>
    </div>
    <div class="border-top pt-3 mt-2 d-flex gap-2">
        <button class="btn btn-secondary flex-grow-1" data-bs-dismiss="modal">Tutup</button>
        <a href="<?= site_url('member/request-buku') ?>" class="btn btn-primary flex-grow-1">
            <i class="bi bi-send me-1"></i>Request Buku
        </a>
    </div>

<?php else: ?>
    <!-- Info Buku -->
    <div class="d-flex gap-3 p-3 bg-light rounded mb-4">
        <img src="<?= $buku->buku_cover ? base_url('assets/upload/cover/thumbnail/' . $buku->buku_cover) : 'https://placehold.co/60x84/4f46e5/ffffff?text=Buku' ?>"
            class="rounded shadow-sm flex-shrink-0" style="width:60px;height:84px;object-fit:cover">
        <div class="overflow-hidden">
            <div class="fw-bold lh-sm mb-1"><?= esc($buku->buku_judul) ?></div>
            <div class="text-muted small mb-2"><?= esc($buku->buku_penulis) ?></div>
            <div class="d-flex flex-wrap gap-1">
                <?php if ($buku->kategori_nama): ?>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary" style="font-size:10px">
                        <i class="bi bi-tag me-1"></i><?= esc($buku->kategori_nama) ?>
                    </span>
                <?php endif; ?>
                <?php if ($buku->rak_nama): ?>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary" style="font-size:10px">
                        <i class="bi bi-archive me-1"></i><?= esc($buku->rak_nama) ?>
                    </span>
                <?php endif; ?>
                <span class="badge bg-<?= $stokColor ?>" style="font-size:10px">
                    <i class="bi bi-check-circle me-1"></i><?= $tersedia ?> tersedia
                </span>
            </div>
        </div>
    </div>

    <!-- Form Peminjaman -->
    <?php echo form_open_multipart('', array('id' => 'pinjam-buku')); ?>
    <input type="hidden" name="peminjaman_buku_id" value="<?= $buku->buku_id ?>">

    <div class="row g-3">
        <!-- Tanggal Pinjam -->
        <div class="col-6">
            <label class="form-label fw-semibold small">Tanggal Pinjam</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-calendar text-muted"></i></span>
                <input type="date" onchange="hitungKembali()" class="form-control border-start-0"
                    id="inputTglPinjam" name="peminjaman_date_start"
                    value="<?= $tglPinjam ?>">
            </div>
        </div>

        <!-- Durasi -->
        <div class="col-6">
            <label class="form-label fw-semibold small">Durasi Pinjam</label>
            <div class="input-group input-group-sm">
                <select class="form-select" id="selectDurasi" name="peminjaman_durasi" onchange="hitungKembali()">
                    <option value="3" selected>3 hari</option>
                    <option value="5">5 hari</option>
                    <option value="7">7 hari</option>
                    <option value="10">10 hari</option>
                    <option value="14">14 hari</option>
                    <option value="30">30 hari</option>
                </select>
            </div>
        </div>

        <!-- Tanggal Kembali -->
        <div class="col-12">
            <label class="form-label fw-semibold small">Estimasi Tanggal Kembali</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-calendar-check text-success"></i></span>
                <input type="date" class="form-control border-start-0 fw-semibold text-success"
                    id="inputTglKembali" name="peminjaman_date_end"
                    value="<?= $tglKembali ?>" readonly>
            </div>
        </div>

        <!-- Catatan -->
        <div class="col-12">
            <label class="form-label fw-semibold small">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
            <textarea class="form-control form-control-sm" name="peminjaman_desc" rows="2" maxlength="255"
                placeholder="Tambahkan catatan untuk petugas..."></textarea>
        </div>
    </div>

    <!-- Info Denda -->
    <div class="d-flex align-items-start gap-2 mt-3 p-3 bg-warning bg-opacity-10 rounded border border-warning border-opacity-25">
        <i class="bi bi-info-circle-fill text-warning mt-1 flex-shrink-0"></i>
        <div class="small text-muted">
            Keterlambatan pengembalian dikenakan denda
            <strong class="text-dark"><?php echo rp(option('denda')['option_desc1']) ?> / hari</strong>.
            Pastikan mengembalikan buku tepat waktu.
        </div>
    </div>

    <!-- Footer -->
    <div class="border-top pt-3 mt-3 d-flex gap-2">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary flex-grow-1">
            <i class="bi bi-bookmark-plus me-1"></i>Pinjam Sekarang
        </button>
    </div>
    <?php echo form_close(); ?>

    <script>
        function hitungKembali() {
            const durasi = parseInt(document.getElementById('selectDurasi').value);
            const tglPinjam = new Date(document.getElementById('inputTglPinjam').value);
            tglPinjam.setDate(tglPinjam.getDate() + durasi);
            document.getElementById('inputTglKembali').value = tglPinjam.toISOString().split('T')[0];
        }

        $('#pinjam-buku').submit(function(event) {
            event.preventDefault();
            let tgl_kembali = document.getElementById('inputTglKembali').value;
            $('#btn010').prop('disabled', true).text('Loading...');
            Swal.fire({
                title: 'Konfirmasi Peminjaman',
                html: `Pinjam buku <b><?= esc($buku->buku_judul) ?></b>?<br>
               <small class="text-muted">Kembali paling lambat <b>${tgl_kembali}</b></small>`,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-bookmark-plus me-1"></i>Ya, Pinjam',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.value) {
                    $.ajax({
                            url: '<?php echo site_url('member/postdata/pinjam/pinjam_buku') ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: $('#pinjam-buku').serialize(),
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
            })
        });
    </script>
<?php endif; ?>