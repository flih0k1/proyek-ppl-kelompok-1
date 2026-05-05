<?php
$code = get('code');
$anggota = $this->db->table('tb_users')->where('user_code', $code)->get()->getRow();
?>
<div class="text-center mb-3">
    <img id="dAvatar" src="<?php echo avatar($anggota->id, 'nama') ?>" class="rounded-circle mb-2" width="72" height="72">
    <div class="fw-bold fs-5" id="dNama"><?php echo $anggota->user_fullname ?></div>
    <div class="text-muted small" id="dId"><?php echo $anggota->username ?></div>
</div>
<ul class="list-group list-group-flush">
    <li class="list-group-item d-flex justify-content-between">
        <span class="text-muted"><i class="bi bi-envelope me-2"></i>Email</span>
        <span id="dEmail"><?php echo $anggota->email ?></span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <span class="text-muted"><i class="bi bi-telephone me-2"></i>Telepon</span>
        <span id="dTelp"><?php echo $anggota->user_phone ?></span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <span class="text-muted"><i class="bi bi-calendar me-2"></i>Tgl Daftar</span>
        <span id="dTglDaftar"><?php echo date('d M Y',$anggota->created_on) ?></span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <span class="text-muted"><i class="bi bi-bookmark me-2"></i>Pinjam Aktif</span>
        <span id="dPinjam"><?php echo model('Usermodel')->total_pinjam($anggota->id) ?></span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <span class="text-muted"><i class="bi bi-cash me-2"></i>Denda</span>
        <span id="dDenda"><?php echo model('Usermodel')->total_denda($anggota->id) ?></span> 
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <span class="text-muted"><i class="bi bi-circle-fill me-2"></i>Status</span>
        <span id="dStatus"><?php echo badge(($anggota->active == 1) ? 'active' : 'inactive') ?></span>
    </li>
</ul>