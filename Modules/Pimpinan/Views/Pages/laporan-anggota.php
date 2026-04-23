<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-0">Rekap Data Anggota</h5>
        <small class="text-muted">Laporan seluruh anggota perpustakaan</small>
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
            <table id="tableAnggota" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Kontak</th>
                        <th>Tgl Daftar</th>
                        <th>Pinjam Aktif</th>
                        <th>Total Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $getdata = $this->db->table('tb_users')
                        ->join('tb_users_groups', 'tb_users.id = tb_users_groups.user_id', 'left')
                        ->orderBy('created_on','desc')
                        ->get()->getResult();

                    foreach ($getdata as $a):
                        $pinjam = model('Usermodel')->total_pinjam($a->id);
                        $denda  = model('Usermodel')->total_denda($a->id);
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($a->user_fullname) ?></td>
                        <td><?= esc($a->username) ?></td>
                        <td>
                            <?= esc($a->user_phone) ?><br>
                            <small class="text-muted"><?= esc($a->email) ?></small>
                        </td>
                        <td><?= date('Y-m-d', $a->created_on) ?></td>
                        <td><?= $pinjam ?></td>
                        <td><?= $denda > 0 ? rp($denda) : 0 ?></td>
                        <td><?= $a->active == 1 ? 'Aktif' : 'Nonaktif' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    let table = $('#tableAnggota').DataTable({
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