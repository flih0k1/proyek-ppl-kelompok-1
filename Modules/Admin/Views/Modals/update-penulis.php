<?php
$id = get('id');
$pen = $this->db->table('tb_penulis')->where('penulis_id', $id)->get()->getRow();
echo form_open('', ['id' => 'update-penulis']); ?>
<input type="hidden" value="<?php echo $id ?>" name="id">
    <div id="penulis-wrapper">
        <div class="row g-2 penulis-item mb-2">
            <div class="col-md-12">
                <label class="form-label">Nama Penulis</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" 
                           value="<?php echo esc($pen->penulis_nama) ?>"
                           class="form-control" 
                           placeholder="ex: Andrea Hirata" 
                           name="penulis_nama" 
                           autocomplete="off"
                           required>
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
$('#update-penulis').submit(function(event) {
    event.preventDefault();

    $('#btn010s')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');

    $.ajax({
        url: '<?php echo site_url('admin/postdata/buku/update_penulis') ?>',
        type: 'POST',
        dataType: 'json',
        data: $('#update-penulis').serialize(),
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
