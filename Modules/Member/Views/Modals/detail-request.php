<?php 

$code = get('code');
$datarequest = $this->db->table('tb_request')
    ->join('tb_users', 'tb_users.id = tb_request.request_userid', 'left')
    ->where('request_userid', userid())
    ->where('request_code', $code)
    ->get()->getRow();

if (!$datarequest): ?>
    <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
        <i class="bi bi-exclamation-circle fs-1 mb-3"></i>
        <div class="fw-semibold">Request buku tidak ditemukan</div>
    </div>
<?php else: ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                style="width:75px;height:75px;">
                <i class="bi bi-book fs-3"></i>
            </div>
            <h5 class="fw-bold mb-1"><?= esc($datarequest->request_buku_judul) ?></h5>
            <div class="text-muted small">
                <?= esc($datarequest->request_buku_penulis) ?> 
                (<?= esc($datarequest->request_buku_tahun ?: '-') ?>)
            </div>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-sm align-middle mb-0">
                <tbody>
                    <tr>
                        <th class="text-muted" style="width:40%;">Status</th>
                        <td><?= badge($datarequest->request_status) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal</th>
                        <td><?= date('d M Y, H:i', strtotime($datarequest->request_date_add)) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Member</th>
                        <td><?= esc($datarequest->user_fullname) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">No. Telpon</th>
                        <td><?= esc($datarequest->user_phone) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <div class="small text-muted fw-semibold mb-2">Alasan Pengajuan</div>
            <div class="p-3 rounded-3 bg-light border small" style="line-height:1.7;">
                <?= nl2br(esc($datarequest->request_desc)) ?>
            </div>
        </div>

        <?php if (!empty($datarequest->request_balasan)): ?>
        <div class="p-3 rounded-3 border border-info border-opacity-25 bg-info bg-opacity-10 mb-3">
            <div class="fw-semibold text-info mb-1">
                <i class="bi bi-chat-dots me-1"></i> Tanggapan Admin
            </div>
            <div class="small text-dark" style="line-height:1.6;">
                <?= nl2br(esc($datarequest->request_balasan)) ?>
            </div>
        </div>
        <?php endif; ?>
    
    </div>
</div>

<?php endif; ?>