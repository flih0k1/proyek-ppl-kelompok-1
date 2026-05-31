

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-0">Rekap Data Buku</h5>
        <small class="text-muted">Laporan koleksi buku perpustakaan</small>
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
            <table id="tableBuku" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>ISBN</th>
                        <th>Kategori</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Tanggal Input</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $getdata = $this->db->table('tb_buku')
                        ->join('tb_kategori_buku', 'tb_kategori_buku.kategori_id = tb_buku.buku_kategori_id', 'left')
                        ->orderBy('buku_date_add','desc')
                        ->get()->getResult();

                    foreach ($getdata as $b):
                        $dipinjam = model('Usermodel')->buku_outstok($b->buku_id);
                        $tersedia = $b->buku_stok - $dipinjam;
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($b->buku_judul) ?></td>
                        <td><?= esc($b->buku_penulis) ?></td>
                        <td><?= esc($b->buku_isbn) ?></td>
                        <td><?= esc($b->kategori_nama) ?></td>
                        <td><?= esc($b->buku_penerbit) ?></td>
                        <td><?= $b->buku_tahun ?></td>
                        <td><?= $tersedia ?></td>
                        <td><?= date('Y-m-d', strtotime($b->buku_date_add)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    let table = $('#tableBuku').DataTable({
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

    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        let min = $('#minDate').val();
        let max = $('#maxDate').val();
        let tanggal = data[8];

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