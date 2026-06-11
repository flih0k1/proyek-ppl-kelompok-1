<?php echo form_open('', ['id' => 'new-rak']); ?>
        <div id="rak-wrapper">
            <div class="row g-2 rak-item mb-2">
                <div class="col-md-12">
                    <label class="form-label">Nama Rak</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-tag"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               placeholder="ex: A2" 
                               name="rak_nama[]" 
                               autocomplete="off">
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
    <div class="row g-2 rak-item mb-2">
        <div class="col-md-11">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-tag"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="ex: A3" 
                       name="rak_nama[]" 
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-1 d-flex">
            <button type="button" class="btn btn-danger btn-remove w-100">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>`;
    
    $('#rak-wrapper').append(html);
});

$(document).on('click', '.btn-remove', function() {
    $(this).closest('.rak-item').remove();
});

$('#new-rak').submit(function(event) {
    event.preventDefault();

    $('#btn010s')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');

    $.ajax({
        url: '<?php echo site_url('admin/postdata/buku/add_rak') ?>',
        type: 'POST',
        dataType: 'json',
        data: $('#new-rak').serialize(),
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