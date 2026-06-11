<?php
$id = get('id');
$kat = $this->db->table('tb_kategori_buku')->where('kategori_id', $id)->get()->getRow();
echo form_open('', ['id' => 'update-kategori']); ?>
<input type="hidden" value="<?php echo $id ?>" name="id">
        <div id="kategori-wrapper">
            <div class="row g-2 kategori-item mb-2">
                <div class="col-md-12">
                    <label class="form-label">Nama Kategori</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-tag"></i>
                        </span>
                        <input type="text" 
                                value="<?php echo $kat->kategori_nama ?>"
                               class="form-control" 
                               placeholder="ex: Novel" 
                               name="kategori_nama" 
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
$('#update-kategori').submit(function(event) {
    event.preventDefault();

    $('#btn010s')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');

    $.ajax({
        url: '<?php echo site_url('admin/postdata/buku/update_kategori') ?>',
        type: 'POST',
        dataType: 'json',
        data: $('#update-kategori').serialize(),
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