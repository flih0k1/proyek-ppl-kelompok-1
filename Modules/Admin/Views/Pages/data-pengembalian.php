<?php
$pengembalian_list = [
    [
        'id_kembali'  => 'KBL-001',
        'id_pinjam'   => 'PJM-021',
        'anggota'     => ['nama' => 'Andi Saputra',    'id' => 'AGT-001', 'avatar' => 'https://placehold.co/40x40/4f46e5/ffffff?text=AS'],
        'buku'        => ['judul' => 'Laskar Pelangi',  'cover' => 'https://placehold.co/40x56/4f46e5/ffffff?text=LP', 'kode' => 'BK-001'],
        'tgl_pinjam'  => '01 Apr 2026',
        'tgl_jatuh'   => '08 Apr 2026',
        'tgl_kembali' => '15 Apr 2026',
        'terlambat'   => 7,
        'denda'       => 7000,
        'kondisi'     => 'Baik',
        'status'      => 'Menunggu',
    ],
    [
        'id_kembali'  => 'KBL-002',
        'id_pinjam'   => 'PJM-018',
        'anggota'     => ['nama' => 'Siti Rahayu',     'id' => 'AGT-002', 'avatar' => 'https://placehold.co/40x40/be185d/ffffff?text=SR'],
        'buku'        => ['judul' => 'Bumi Manusia',    'cover' => 'https://placehold.co/40x56/0891b2/ffffff?text=BM', 'kode' => 'BK-002'],
        'tgl_pinjam'  => '03 Apr 2026',
        'tgl_jatuh'   => '10 Apr 2026',
        'tgl_kembali' => '15 Apr 2026',
        'terlambat'   => 5,
        'denda'       => 5000,
        'kondisi'     => 'Rusak Ringan',
        'status'      => 'Menunggu',
    ],
    [
        'id_kembali'  => 'KBL-003',
        'id_pinjam'   => 'PJM-015',
        'anggota'     => ['nama' => 'Budi Santoso',    'id' => 'AGT-003', 'avatar' => 'https://placehold.co/40x40/059669/ffffff?text=BS'],
        'buku'        => ['judul' => 'Negeri 5 Menara', 'cover' => 'https://placehold.co/40x56/059669/ffffff?text=N5M', 'kode' => 'BK-003'],
        'tgl_pinjam'  => '05 Apr 2026',
        'tgl_jatuh'   => '12 Apr 2026',
        'tgl_kembali' => '12 Apr 2026',
        'terlambat'   => 0,
        'denda'       => 0,
        'kondisi'     => 'Baik',
        'status'      => 'Menunggu',
    ],
    [
        'id_kembali'  => 'KBL-004',
        'id_pinjam'   => 'PJM-012',
        'anggota'     => ['nama' => 'Rizky Pratama',   'id' => 'AGT-005', 'avatar' => 'https://placehold.co/40x40/0891b2/ffffff?text=RP'],
        'buku'        => ['judul' => 'Atomic Habits',   'cover' => 'https://placehold.co/40x56/d97706/ffffff?text=AH', 'kode' => 'BK-004'],
        'tgl_pinjam'  => '06 Apr 2026',
        'tgl_jatuh'   => '13 Apr 2026',
        'tgl_kembali' => '15 Apr 2026',
        'terlambat'   => 2,
        'denda'       => 2000,
        'kondisi'     => 'Baik',
        'status'      => 'Menunggu',
    ],
    [
        'id_kembali'  => 'KBL-005',
        'id_pinjam'   => 'PJM-010',
        'anggota'     => ['nama' => 'Fajar Nugroho',   'id' => 'AGT-006', 'avatar' => 'https://placehold.co/40x40/7c3aed/ffffff?text=FN'],
        'buku'        => ['judul' => 'Clean Code',      'cover' => 'https://placehold.co/40x56/dc2626/ffffff?text=CC', 'kode' => 'BK-005'],
        'tgl_pinjam'  => '07 Apr 2026',
        'tgl_jatuh'   => '14 Apr 2026',
        'tgl_kembali' => '14 Apr 2026',
        'terlambat'   => 0,
        'denda'       => 0,
        'kondisi'     => 'Baik',
        'status'      => 'Dikonfirmasi',
    ],
    [
        'id_kembali'  => 'KBL-006',
        'id_pinjam'   => 'PJM-009',
        'anggota'     => ['nama' => 'Hendra Wijaya',   'id' => 'AGT-008', 'avatar' => 'https://placehold.co/40x40/0f766e/ffffff?text=HW'],
        'buku'        => ['judul' => 'Perahu Kertas',   'cover' => 'https://placehold.co/40x56/7c3aed/ffffff?text=PK', 'kode' => 'BK-006'],
        'tgl_pinjam'  => '02 Apr 2026',
        'tgl_jatuh'   => '09 Apr 2026',
        'tgl_kembali' => '13 Apr 2026',
        'terlambat'   => 4,
        'denda'       => 4000,
        'kondisi'     => 'Rusak Ringan',
        'status'      => 'Dikonfirmasi',
    ],
];

$total_menunggu    = count(array_filter($pengembalian_list, fn($p) => $p['status'] === 'Menunggu'));
$total_dikonfirmasi = count(array_filter($pengembalian_list, fn($p) => $p['status'] === 'Dikonfirmasi'));
$total_denda       = array_sum(array_column($pengembalian_list, 'denda'));
$total_terlambat   = count(array_filter($pengembalian_list, fn($p) => $p['terlambat'] > 0));
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Pengembalian Buku</h5>
        <small class="text-muted">Konfirmasi penerimaan buku yang dikembalikan anggota</small>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <?php
    $summary = [
        ['label' => 'Menunggu Konfirmasi', 'value' => $total_menunggu,    'icon' => 'bi-hourglass-split',  'color' => 'warning'],
        ['label' => 'Sudah Dikonfirmasi',  'value' => $total_dikonfirmasi,'icon' => 'bi-patch-check',      'color' => 'success'],
        ['label' => 'Pengembalian Telat',  'value' => $total_terlambat,   'icon' => 'bi-clock-history',    'color' => 'danger'],
        ['label' => 'Total Denda',         'value' => 'Rp ' . number_format($total_denda, 0, ',', '.'), 'icon' => 'bi-cash-coin', 'color' => 'info'],
    ];
    foreach ($summary as $s):
    ?>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-<?= $s['color'] ?> bg-opacity-10 text-<?= $s['color'] ?>">
                    <i class="bi <?= $s['icon'] ?> fs-3"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold"><?= $s['value'] ?></div>
                    <div class="text-muted small"><?= $s['label'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="searchData" placeholder="Cari nama anggota, judul buku, ID...">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select class="form-select" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option>Menunggu</option>
                    <option>Dikonfirmasi</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select class="form-select" id="filterKondisi">
                    <option value="">Semua Kondisi</option>
                    <option>Baik</option>
                    <option>Rusak Ringan</option>
                    <option>Rusak Berat</option>
                </select>
            </div>
            <div class="col-12 col-md-1 d-grid">
                <button class="btn btn-outline-secondary" onclick="resetFilter()">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Pengembalian -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablePengembalian">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Keterlambatan</th>
                        <th>Denda</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengembalian_list as $p):
                        $isLate     = $p['terlambat'] > 0;
                        $isDone     = $p['status'] === 'Dikonfirmasi';
                        $kondisiColor = match($p['kondisi']) {
                            'Baik'         => 'success',
                            'Rusak Ringan' => 'warning',
                            'Rusak Berat'  => 'danger',
                            default        => 'secondary'
                        };
                    ?>
                    <tr>
                        <td class="ps-3">
                            <div class="small fw-semibold text-muted"><?= $p['id_kembali'] ?></div>
                            <div class="text-muted" style="font-size:11px"><?= $p['id_pinjam'] ?></div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= $p['anggota']['avatar'] ?>" class="rounded-circle" width="36" height="36">
                                <div>
                                    <div class="fw-semibold small"><?= esc($p['anggota']['nama']) ?></div>
                                    <div class="text-muted" style="font-size:11px"><?= $p['anggota']['id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= $p['buku']['cover'] ?>" class="rounded" width="28" height="40" style="object-fit:cover">
                                <div>
                                    <div class="fw-semibold small"><?= esc($p['buku']['judul']) ?></div>
                                    <div class="text-muted" style="font-size:11px"><?= $p['buku']['kode'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td><small><?= $p['tgl_pinjam'] ?></small></td>
                        <td>
                            <small class="<?= $isLate ? 'text-danger fw-semibold' : '' ?>">
                                <?= $p['tgl_jatuh'] ?>
                            </small>
                        </td>
                        <td><small><?= $p['tgl_kembali'] ?></small></td>
                        <td>
                            <?php if ($isLate): ?>
                                <span class="badge bg-danger">
                                    <i class="bi bi-clock me-1"></i><?= $p['terlambat'] ?> hari
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check me-1"></i>Tepat Waktu
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['denda'] > 0): ?>
                                <span class="text-danger fw-semibold small">
                                    Rp <?= number_format($p['denda'], 0, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $kondisiColor ?> bg-opacity-10 text-<?= $kondisiColor ?> border border-<?= $kondisiColor ?>">
                                <?= $p['kondisi'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($isDone): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    <i class="bi bi-patch-check me-1"></i>Dikonfirmasi
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                    <i class="bi bi-hourglass-split me-1"></i>Menunggu
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <!-- Tombol Detail -->
                                <button class="btn btn-sm btn-outline-info" title="Detail"
                                    onclick="showDetail(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)"
                                    data-bs-toggle="modal" data-bs-target="#modalDetail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <!-- Tombol Konfirmasi -->
                                <?php if (!$isDone): ?>
                                <button class="btn btn-sm btn-success" title="Konfirmasi Penerimaan"
                                    onclick="konfirmasiPenerimaan(this, '<?= $p['id_kembali'] ?>', '<?= esc($p['anggota']['nama']) ?>', '<?= esc($p['buku']['judul']) ?>', <?= $p['denda'] ?>)">
                                    <i class="bi bi-patch-check me-1"></i>Konfirmasi
                                </button>
                                <?php else: ?>
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="bi bi-check-lg me-1"></i>Selesai
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan <?= count($pengembalian_list) ?> data pengembalian</small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
            </ul>
        </nav>
    </div>
</div>

<!-- Modal Detail Pengembalian -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2 text-info"></i>Detail Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success d-none" id="mdBtnKonfirmasi">
                    <i class="bi bi-patch-check me-1"></i>Konfirmasi Penerimaan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const kondisiColor = { 'Baik': 'success', 'Rusak Ringan': 'warning', 'Rusak Berat': 'danger' };
const statusColor  = { 'Menunggu': 'warning', 'Dikonfirmasi': 'success' };

function showDetail(p) {
    document.getElementById('mdAvatar').src         = p.anggota.avatar;
    document.getElementById('mdAnggota').textContent = p.anggota.nama;
    document.getElementById('mdAnggotaId').textContent = p.anggota.id;
    document.getElementById('mdCover').src           = p.buku.cover;
    document.getElementById('mdJudul').textContent   = p.buku.judul;
    document.getElementById('mdKodeBuku').textContent = p.buku.kode;
    document.getElementById('mdIdKembali').textContent = p.id_kembali;
    document.getElementById('mdIdPinjam').textContent  = p.id_pinjam;
    document.getElementById('mdTglPinjam').textContent  = p.tgl_pinjam;
    document.getElementById('mdJatuhTempo').textContent = p.tgl_jatuh;
    document.getElementById('mdTglKembali').textContent = p.tgl_kembali;

    document.getElementById('mdTerlambat').innerHTML = p.terlambat > 0
        ? `<span class="badge bg-danger">${p.terlambat} hari</span>`
        : `<span class="badge bg-success">Tepat Waktu</span>`;

    document.getElementById('mdDenda').innerHTML = p.denda > 0
        ? `<span class="text-danger fw-semibold">Rp ${p.denda.toLocaleString('id-ID')}</span>`
        : '—';

    const kc = kondisiColor[p.kondisi] || 'secondary';
    document.getElementById('mdKondisi').innerHTML =
        `<span class="badge bg-${kc} bg-opacity-10 text-${kc} border border-${kc}">${p.kondisi}</span>`;

    const sc = statusColor[p.status] || 'secondary';
    document.getElementById('mdStatus').innerHTML =
        `<span class="badge bg-${sc} bg-opacity-10 text-${sc} border border-${sc}">${p.status}</span>`;

    const btnKonfirmasi = document.getElementById('mdBtnKonfirmasi');
    if (p.status === 'Menunggu') {
        btnKonfirmasi.classList.remove('d-none');
        btnKonfirmasi.onclick = () => {
            bootstrap.Modal.getInstance(document.getElementById('modalDetail')).hide();
            konfirmasiPenerimaan(null, p.id_kembali, p.anggota.nama, p.buku.judul, p.denda);
        };
    } else {
        btnKonfirmasi.classList.add('d-none');
    }
}

function konfirmasiPenerimaan(btn, idKembali, namaAnggota, judulBuku, denda) {
    const dendaInfo = denda > 0
        ? `<br><small class="text-danger">Denda: <b>Rp ${denda.toLocaleString('id-ID')}</b></small>`
        : `<br><small class="text-success">Tidak ada denda</small>`;

    Swal.fire({
        title: 'Konfirmasi Penerimaan?',
        html: `Buku <b>${judulBuku}</b> dari <b>${namaAnggota}</b> telah diterima?${dendaInfo}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-patch-check me-1"></i>Ya, Konfirmasi',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.isConfirmed) {
            // Update baris di tabel jika dipanggil dari tombol tabel
            if (btn) {
                const row = btn.closest('tr');
                // Update badge status
                row.cells[9].innerHTML = `
                    <span class="badge bg-success bg-opacity-10 text-success border border-success">
                        <i class="bi bi-patch-check me-1"></i>Dikonfirmasi
                    </span>`;
                // Ganti tombol konfirmasi jadi selesai
                btn.outerHTML = `<button class="btn btn-sm btn-outline-secondary" disabled>
                    <i class="bi bi-check-lg me-1"></i>Selesai
                </button>`;
            }
            Swal.fire({
                title: 'Berhasil!',
                html: `Pengembalian <b>${idKembali}</b> telah dikonfirmasi.`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
            });
        }
    });
}

function resetFilter() {
    document.getElementById('searchData').value    = '';
    document.getElementById('filterStatus').value  = '';
    document.getElementById('filterKondisi').value = '';
    filterTable();
}

document.getElementById('searchData').addEventListener('keyup', filterTable);
document.getElementById('filterStatus').addEventListener('change', filterTable);
document.getElementById('filterKondisi').addEventListener('change', filterTable);

function filterTable() {
    const search  = document.getElementById('searchData').value.toLowerCase();
    const status  = document.getElementById('filterStatus').value.toLowerCase();
    const kondisi = document.getElementById('filterKondisi').value.toLowerCase();

    document.querySelectorAll('#tablePengembalian tbody tr').forEach(row => {
        const text        = row.innerText.toLowerCase();
        const statusCell  = row.cells[9].innerText.toLowerCase();
        const kondisiCell = row.cells[8].innerText.toLowerCase();

        const ok = text.includes(search)
            && (!status  || statusCell.includes(status))
            && (!kondisi || kondisiCell.includes(kondisi));

        row.style.display = ok ? '' : 'none';
    });
}
</script>
