<?php
$id = get('id');
$kat = $this->db->table('tb_rak_buku')->where('rak_id', $id)->get()->getRow();
echo form_open('', ['id' => 'update-rak']); ?>
<input type="hidden" value="<?php echo $id ?>" name="id">
        <div id="rak-wrapper">
            <div class="row g-2 rak-item mb-2">
                <div class="col-md-12">
                    <label class="form-label">Nama Rak</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-tag"></i>
                        </span>
                        <input type="text" 
                                value="<?php echo $kat->rak_nama ?>"
                               class="form-control" 
                               placeholder="ex: A7" 
                               name="rak_nama" 
                               autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 d-grid">
            <button id="btn010s" type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> SIMPAN
            </button>
        </div>


<?php echo form_close(); ?>

<script>
$('#update-rak').submit(function(event) {
    event.preventDefault();

    $('#btn010s')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');

    $.ajax({
        url: '<?php echo site_url('admin/postdata/buku/update_rak') ?>',
        type: 'POST',
        dataType: 'json',
        data: $('#update-rak').serialize(),
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

        $('#btn010s')
            .prop('disabled', false)
            .html('<i class="bi bi-save me-1"></i> SIMPAN');
    });
});
</script>