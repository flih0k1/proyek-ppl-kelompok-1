                        <?php

                        $code = get('code');
                        $datarequest = $this->db->table('tb_request')
                            ->join('tb_users', 'tb_users.id = tb_request.request_userid', 'left')
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
                                    <div class="table-responsive mb-3">
                                        <table class="table table-sm align-middle mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="text-muted">Buku</th>
                                                    <td><?= $datarequest->request_buku_judul ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">Penulis</th>
                                                    <td><?= $datarequest->request_buku_penulis ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">Tahun Terbit</th>
                                                    <td><?= $datarequest->request_buku_tahun ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">Member</th>
                                                    <td><?= esc($datarequest->user_fullname) ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">Email</th>
                                                    <td><?= esc($datarequest->email) ?></td>
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
                                    <?php echo form_open_multipart('', array('id' => 'tanggapi-request-buku')); ?>
                                    <input type="hidden" name="code" value="<?php echo $code ?>">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Status Request <span class="text-danger">*</span></label>
                                        <select name="request_status" class="form-select" id="">
                                            <option value="process">Proses Permintaan</option>
                                            <option value="cancel">Batalkan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Keterangan<span class="text-danger">*</span></label>
                                        <textarea name="request_balasan" class="form-control" rows="3" maxlength="255" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" id="btn010ds" class="btn btn-primary w-100 py-2 fw-semibold">Tanggapi Request</button>
                                    </div>
                                    <?php echo form_close() ?>
                                    <script>
                                        $('#tanggapi-request-buku').submit(function(event) {
                                            event.preventDefault();
                                            $('#btn010ds').prop('disabled', true).text('Loading...');
                                            $.ajax({
                                                    url: '<?php echo site_url('admin/postdata/Buku/tanggapi_request') ?>',
                                                    type: 'POST',
                                                    dataType: 'json',
                                                    data: $('#tanggapi-request-buku').serialize(),
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
                                        });
                                    </script>
                                <?php endif; ?>