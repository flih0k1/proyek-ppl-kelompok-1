<?php echo form_open('', ['id' => 'new-penulis']); ?>
    <div id="penulis-wrapper">
        <div class="row g-2 penulis-item mb-2">
            <div class="col-md-12">
                <label class="form-label">Nama Penulis</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           placeholder="ex: Andrea Hirata" 
                           name="penulis_nama[]" 
                           autocomplete="off"
                           required>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-success btn-add">
        <i class="bi bi-plus-lg"></i> Tambah
    </button>
    <div class="mt-3 d-grid">
        <button id="btn010s" type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> SIMPAN
        </button>
    </div>
<?php echo form_close(); ?>

<script>
$(document).on('click', '.btn-add', function() {
    let html = `
    <div class="row g-2 penulis-item mb-2">
        <div class="col-md-11">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="ex: Tere Liye" 
                       name="penulis_nama[]" 
                       autocomplete="off"
                       required>
            </div>
        </div>
        <div class="col-md-1 d-flex">
            <button type="button" class="btn btn-danger btn-remove w-100">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>`;
    
    $('#penulis-wrapper').append(html);
});

$(document).on('click', '.btn-remove', function() {
    $(this).closest('.penulis-item').remove();
});

$('#new-penulis').submit(function(event) {
    event.preventDefault();

    $('#btn010s')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');

    $.ajax({
        url: '<?php echo site_url('admin/postdata/buku/add_penulis') ?>',
        type: 'POST',
        dataType: 'json',
        data: $('#new-penulis').serialize(),
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
