<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-0">Rekap Data Peminjaman</h5>
        <small class="text-muted">Laporan aktivitas peminjaman buku</small>
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
                <button id="filterTanggal" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <button id="resetFilter" class="btn btn-outline-secondary w-100">
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableRekap" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Buku</th>
                        <th>Anggota</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Keterlambatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $getdata = $this->db->table('tb_peminjaman')
                        ->select('
        tb_peminjaman.*,
        tb_buku.buku_judul,
        tb_buku.buku_penulis,
        tb_users.user_fullname,
        tb_users.username
    ')
                        ->join('tb_buku', 'tb_buku.buku_id = tb_peminjaman.peminjaman_buku_id', 'left')
                        ->join('tb_users', 'tb_users.id = tb_peminjaman.peminjaman_userid', 'left')
                        ->orderBy('tb_peminjaman.peminjaman_date_add', 'DESC')
                        ->get()
                        ->getResult();
                    foreach ($getdata as $p):

                        $today = date('Y-m-d 00:00:00');
                        $isLate = ($p->peminjaman_status === 'pinjam' && $p->peminjaman_date_end < $today);

                        $lateDays = 0;
                        if ($isLate && $p->peminjaman_date_end) {
                            $lateDays = max(0, floor((strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', strtotime($p->peminjaman_date_end)))) / 86400));
                        }

                        if ($isLate) {
                            $statusLabel = 'Terlambat';
                        } elseif ($p->peminjaman_status === 'pending') {
                            $statusLabel = 'Pending';
                        } elseif ($p->peminjaman_status === 'pinjam') {
                            $statusLabel = 'Dipinjam';
                        } elseif ($p->peminjaman_status === 'kembalikan') {
                            $statusLabel = 'Menunggu Kembali';
                        } elseif ($p->peminjaman_status === 'selesai') {
                            $statusLabel = 'Selesai';
                        } else {
                            $statusLabel = ucfirst($p->peminjaman_status);
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($p->peminjaman_code) ?></td>
                            <td><?= esc($p->buku_judul) ?></td>
                            <td><?= esc($p->user_fullname) ?></td>
                            <td><?= $p->peminjaman_date_start ? date('Y-m-d', strtotime($p->peminjaman_date_start)) : '-' ?></td>
                            <td><?= $p->peminjaman_date_end ? date('Y-m-d', strtotime($p->peminjaman_date_end)) : '-' ?></td>
                            <td><?= $p->peminjaman_date_kembali ? date('Y-m-d', strtotime($p->peminjaman_date_kembali)) : '-' ?></td>
                            <td><?= $statusLabel ?></td>
                            <td><?= $lateDays > 0 ? $lateDays . ' hari' : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        let table = $('#tableRekap').DataTable({
            dom: 'Bfrtip',
            buttons: [{
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
            let tanggal = data[4];

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

        $('#filterTanggal').on('click', function() {
            table.draw();
        });

        $('#resetFilter').on('click', function() {
            $('#minDate').val('');
            $('#maxDate').val('');
            table.draw();
        });

    });
</script>