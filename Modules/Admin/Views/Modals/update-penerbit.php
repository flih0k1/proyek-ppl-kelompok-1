<?php
$id = get('id');
$pub = $this->db->table('tb_penerbit')->where('penerbit_id', $id)->get()->getRow();
echo form_open('', ['id' => 'update-penerbit']); ?>
<input type="hidden" value="<?php echo $id ?>" name="id">
    <div id="penerbit-wrapper">
        <div class="row g-2 penerbit-item mb-2">
            <div class="col-md-12">
                <label class="form-label">Nama Penerbit</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-building"></i>
                    </span>
                    <input type="text" 
                           value="<?php echo esc($pub->penerbit_nama) ?>"
                           class="form-control" 
                           placeholder="ex: Gramedia" 
                           name="penerbit_nama" 
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
$('#update-penerbit').submit(function(event) {
    event.preventDefault();

    $('#btn010s')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');

    $.ajax({
        url: '<?php echo site_url('admin/postdata/buku/update_penerbit') ?>',
        type: 'POST',
        dataType: 'json',
        data: $('#update-penerbit').serialize(),
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
