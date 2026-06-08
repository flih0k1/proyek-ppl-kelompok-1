<?php

$limit = 15;
$page  = (int)(get('page') ?? 1);
$page  = $page > 0 ? $page : 1;
$offset = ($page - 1) * $limit;
$no     = $offset + 1;
$search = get('search');
$status = get('status');


$data_anggota = $this->db->table('tb_users')->join('tb_users_groups', 'tb_users.id = tb_users_groups.user_id', 'left');
if ($search) {
    $data_anggota->groupStart()
        ->like('username', $search)
        ->orLike('user_fullname', $search)
        ->orLike('user_phone', $search)
        ->orLike('email', $search)
        ->groupEnd();
}
if ($status) $data_anggota->where('active', $status);
$data_anggota->where('tb_users.id >', 1);
$getdata = $data_anggota->limit($limit, $offset)->get()->getResult();

if ($search) {
    $data_anggota->groupStart()
        ->like('username', $search)
        ->orLike('user_fullname', $search)
        ->orLike('user_phone', $search)
        ->orLike('email', $search)
        ->groupEnd();
}
if ($status) $data_anggota->where('active', $status);
$total = $data_anggota->countAllResults();

$total_aktif    = $data_anggota->where('active', 1)->get()->getNumRows();
$total_nonaktif    = $data_anggota->where('active', 0)->get()->getNumRows();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Data Anggota</h5>
        <small class="text-muted">Kelola seluruh anggota perpustakaan</small>
    </div>
    <a href="<?php echo site_url('signup') ?>"
        class="btn btn-primary"
        title="Tambah Anggota">
        <i class="bi bi-plus-circle me-2"></i>
        Tambah Anggota
    </a>
</div>
<div class="row g-3 mb-4">
    <?php
    ?>
    <div class="col-6 col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div>
                    <h6>Total Member</h6>
                    <div class="fs-4 fw-bold"><?= $total ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-check fs-3"></i>
                </div>
                <div>
                    <h6>Member Aktif</h6>
                    <div class="fs-4 fw-bold"><?= $total_aktif ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-person-exclamation fs-3"></i>
                </div>
                <div>
                    <h6>Member Tidak Aktif</h6>
                    <div class="fs-4 fw-bold"><?= $total_nonaktif ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm mb-4">
    <form method="get" action="" id="formFilter">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" name="search" placeholder="Cari nama, email, ID anggota...">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="col-12 col-md-1 d-grid">

                    <a href="?" class="btn btn-danger">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tabel Anggota -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableAnggota">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Anggota</th>
                        <th>Kontak</th>
                        <th>Tgl Daftar</th>
                        <th>Pinjam Aktif</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdata as $a): ?>
                        <tr>
                            <td class="ps-3">
                                <span class="text-muted small fw-semibold"><?= $no++ ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= avatar($a->id) ?>" class="rounded-circle" width="40" height="40" alt="avatar">
                                    <div>
                                        <div class="fw-semibold"><?= esc($a->user_fullname) ?></div>
                                        <small class="text-muted"><?= esc($a->username) ?></small> <?php echo badge(userdata(['tb_users.id' => $a->id])->role) ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-telephone me-1 text-muted"></i><?= $a->user_phone ?></div>
                                <div class="small text-muted text-truncate" style="max-width:160px">
                                    <i class="bi bi-geo-alt me-1"></i><?= esc($a->email) ?>
                                </div>
                            </td>
                            <td><small><?= date('d-M-Y', $a->created_on) ?></small></td>
                            <td>
                                <?php
                                $pinjam = model('Usermodel')->total_pinjam($a->id);

                                if ($pinjam > 0): ?>
                                    <span class="badge bg-primary"><?= $pinjam ?> buku</span>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $denda = model('Usermodel')->total_denda($a->id);

                                if (model('Usermodel')->total_denda($a->id) > 0): ?>
                                    <span class="badge bg-danger"><?= rp($denda) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo badge(($a->active == 1) ? 'active' : 'inactive') ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm mb-1 btn-outline-info" title="Detail"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/detail-anggota?code=' . $a->user_code) ?>"
                                        data-bs-title="Detail Anggota">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm mb-1 btn-outline-warning" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#dinamicModal2"
                                        data-bs-href="<?= site_url('admin/modal/update-anggota?code=' . $a->user_code) ?>"
                                        data-bs-title="Edit Anggota">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php if(userid() == 1){ ?>
                                    <a data-id="<?php echo $a->id ?>" class="btn btn-sm btn-outline-danger login_as_user mb-1" href="#" role="button" title="LOGIN"><i class="bi bi-arrow-right-square"></i></a>
                                    <?php } ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan <?= count($getdata) ?> buku</small>
        <?php echo pagination(page_url(), $total, $limit) ?>
    </div>
</div>

<!-- Modal Edit Anggota -->
<div class="modal fade" id="modalEditAnggota" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2 text-warning"></i>Edit Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="eNama">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="eEmail">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" class="form-control" id="eTelp">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="eStatus">
                            <option>Aktif</option>
                            <option>Nonaktif</option>
                            <option>Pending</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" rows="2" id="eAlamat"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning text-white"><i class="bi bi-save me-1"></i> Update</button>
            </div>
        </div>
    </div>
</div>

<script>
        jQuery(document).ready(function($) {

        $('.login_as_user').click(function(event) {
            $.ajax({
                    url: '<?php echo site_url('admin/postdata/general/login_as_user') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        user_id: $(this).data('id'),
                        csrf_myapp: $('input[name=csrf_cadangan]').val()
                    },
                })
                .done(function(result) {
                    if (result.status) {
                        Swal.fire(
                            'Berhasil',
                            result.heading,
                            result.type
                        ).then(function() {
                            location.href = '<?php echo site_url('login') ?>';
                        });

                    } else {
                        Swal.fire({
                            title: result.heading,
                            text: result.message,
                            type: 'warning',
                            showCancelButton: true,
                            allowOutsideClick: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'YA, Logout',
                            cancelButtonText: 'Batal',
                        }).then((result) => {
                            if (result.value) {
                                location.href = '<?php echo site_url('logout') ?>';
                            }
                        })
                    }

                })


        });

    });
</script>