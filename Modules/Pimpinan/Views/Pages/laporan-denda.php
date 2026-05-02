<?php
$getdata = $this->db->table('tb_peminjaman')
    ->select('
        tb_peminjaman.*,
        tb_buku.buku_judul,
        tb_buku.buku_cover,
        tb_users.user_fullname,
        tb_users.username,
        tb_users.id
    ')
    ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
    ->join('tb_users', 'tb_users.id = tb_peminjaman.peminjaman_userid', 'left')
    ->where('tb_peminjaman.peminjaman_denda >', 0)
    ->whereIn('tb_peminjaman.peminjaman_status', ['selesai', 'kembalikan'])
    ->orderBy('tb_peminjaman.peminjaman_date_kembali', 'DESC')
    ->get()
    ->getResult();

    $totalDenda = $this->db->table('tb_peminjaman')
    ->selectSum('peminjaman_denda')
    ->where('peminjaman_denda >', 0)
    ->whereIn('peminjaman_status', ['selesai', 'kembalikan'])
    ->get()
    ->getRow()
    ->peminjaman_denda ?? 0;

    $dendaBelum = $this->db->table('tb_peminjaman')
    ->selectSum('peminjaman_denda')
    ->where('peminjaman_denda >', 0)
    ->where('peminjaman_status_bayar', 'belum')
    ->whereIn('peminjaman_status', ['selesai', 'kembalikan'])
    ->get()
    ->getRow()
    ->peminjaman_denda ?? 0;

    $dendaLunas = $this->db->table('tb_peminjaman')
    ->selectSum('peminjaman_denda')
    ->where('peminjaman_denda >', 0)
    ->where('peminjaman_status_bayar', 'lunas')
    ->whereIn('peminjaman_status', ['selesai', 'kembalikan'])
    ->get()
    ->getRow()
    ->peminjaman_denda ?? 0;

    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-0">Rekap Denda</h5>
        <small class="text-muted">Laporan denda keterlambatan pengembalian buku</small>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="p-3 border rounded-3 bg-light text-center">
            <div class="fw-bold fs-5 text-primary">Rp <?= number_format($totalDenda,0,',','.') ?></div>
            <small class="text-muted">Total Denda</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 border rounded-3 bg-light text-center">
            <div class="fw-bold fs-5 text-danger">Rp <?= number_format($dendaBelum,0,',','.') ?></div>
            <small class="text-muted">Belum Dibayar</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 border rounded-3 bg-light text-center">
            <div class="fw-bold fs-5 text-success">Rp <?= number_format($dendaLunas,0,',','.') ?></div>
            <small class="text-muted">Sudah Lunas</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" id="minDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" id="maxDate" class="form-control">
            </div>
            <div class="col-md-2">
                <button id="filterTanggal" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <button id="resetFilter" class="btn btn-outline-secondary w-100">Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableDenda" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Member</th>
                        <th>Buku</th>
                        <th>Tgl Kembali</th>
                        <th>Terlambat</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($getdata as $t):

                        $end   = strtotime($t->peminjaman_date_end);
                        $balik = strtotime($t->peminjaman_date_kembali);
                        $telat = max(0, floor(($balik - $end) / 86400));
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($t->user_fullname) ?></td>
                        <td><?= esc($t->buku_judul) ?></td>
                        <td><?= date('Y-m-d', strtotime($t->peminjaman_date_kembali)) ?></td>
                        <td><?= $telat ?> hari</td>
                        <td>Rp <?= number_format($t->peminjaman_denda,0,',','.') ?></td>
                        <td><?= $t->peminjaman_status_bayar === 'lunas' ? 'Lunas' : 'Belum' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    let table = $('#tableDenda').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm',
                text: '<i class="bi bi-file-earmark-excel"></i> Excel'
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF'
            },
            {
                extend: 'print',
                className: 'btn btn-secondary btn-sm',
                text: '<i class="bi bi-printer"></i> Print'
            }
        ]
    });

    $.fn.dataTable.ext.search.push(function(settings, data) {
        let min = $('#minDate').val();
        let max = $('#maxDate').val();
        let tanggal = data[3];

        if (!min && !max) return true;

        if (tanggal) {
            if (
                (!min || tanggal >= min) &&
                (!max || tanggal <= max)
            ) {
                return true;
            }
        }
        return false;
    });

    $('#filterTanggal').on('click', function () {
        table.draw();
    });

    $('#resetFilter').on('click', function () {
        $('#minDate').val('');
        $('#maxDate').val('');
        table.draw();
    });

});
</script>