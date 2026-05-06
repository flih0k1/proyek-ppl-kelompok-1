                        <?php echo form_open_multipart('', array('id' => 'request-buku')); ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" name="request_buku_judul" class="form-control" placeholder="Masukkan judul buku lengkap" required>
                        </div>
                        <div class="row">
                            <div class="col-md-7 mb-3">
                                <label class="form-label fw-semibold small">Penulis <span class="text-danger">*</span></label>
                                <input type="text" name="request_buku_penulis" class="form-control" placeholder="Nama penulis" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label fw-semibold small">Tahun Terbit</label>
                                <input type="number" name="request_buku_tahun" class="form-control" placeholder="Contoh: 2024">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Alasan Request <span class="text-danger">*</span></label>
                            <textarea name="request_desc" class="form-control" rows="3" maxlength="255" placeholder="Mengapa buku ini perlu ditambahkan?" required></textarea>
                            <div class="form-text text-muted" style="font-size: 10.5px;">Memberikan alasan yang kuat mempermudah Admin memproses pengadaan buku.</div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" id="btn010" class="btn btn-primary w-100 py-2 fw-semibold">Ajukan Request</button>
                        </div>
                        <?php echo form_close() ?>
                        <script>
                            $('#request-buku').submit(function(event) {
                                event.preventDefault();
                                $('#btn010').prop('disabled', true).text('Loading...');
                                $.ajax({
                                        url: '<?php echo site_url('member/postdata/pinjam/request_buku') ?>',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: $('#request-buku').serialize(),
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