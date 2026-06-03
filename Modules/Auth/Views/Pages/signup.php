<?php $title = 'Pendaftaran' ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <a href="<?php echo site_url() ?>" title="Perpustakaan Al Azhar">
                            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Perpustakaan Al Azhar" class="img-fluid mb-3" style="max-width: 220px;">
                        </a>
                        <h3 class="mb-1">Registrasi</h3>
                    </div>
                    <?php echo form_open('', array('id' => 'register-form')); ?>

                    <div class="mb-3">
                        <label class="form-label">Tipe User</label>
                        <select name="user_tipe" class="form-select" id="tipe">
                            <option value="2">Member</option>
                            <option value="1">Pustakawan</option>
                            <option value="3">Pimpinan</option>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input class="form-control" type="text" name="user_username" placeholder="Username" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input class="form-control" type="text" name="user_fullname" placeholder="Nama Lengkap" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Alamat Email</label>
                            <input class="form-control" type="email" name="user_email" placeholder="Alamat Email" autocomplete="new-password" onkeypress="return event.charCode != 32">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. WhatsApp</label>
                            <input id="noWA" class="form-control" type="text" name="user_phone" placeholder="No. WhatsApp" autocomplete="new-password" onkeypress="return event.charCode != 32">
                        </div>
                    </div>



                    <div class="mb-3 mt-3">
                        <label class="form-label">Password</label>
                        <div class="input-group" id="show_hide_password">
                            <input id="password" type="password" name="user_password" class="form-control" placeholder="Password" autocomplete="new-password" onkeypress="return event.charCode != 32">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">&#128065;</button>
                        </div>
                    </div>
              
                    <div class="d-grid gap-2 mt-4">
                        <button id="btn01" type="submit" class="btn btn-primary btn-lg">DAFTAR</button>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="text-center mt-4">
                        <span class="text-muted">Sudah punya Akun?</span><br>
                        <a href="<?php echo site_url('login') ?>" class="fw-bold">MASUK SEKARANG</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    togglePassword.addEventListener("click", function() {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        this.innerHTML = type === "password" ? "&#128065;" : "&#128683;";
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#register-form').submit(function(event) {
            event.preventDefault();

            $('#btn01').prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i>Loading...');

            $.ajax({
                    url: '<?php echo site_url('auth/postdata/Authpost/do_register') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: $('#register-form').serialize(),
                })
                .done(function(data) {
                    updateCSRF(data.csrf_data);
                    Swal.fire({
                        type: data.type,
                        title: data.heading,
                        html: data.message,
                        confirmButtonColor: '#dc3545'
                    }).then(function() {
                        if (data.status) {
                            location.href = '<?php echo site_url('login') ?>';
                        }
                    });
                })
                .always(function() {
                    $('#btn01').prop('disabled', false).html('<i class="bi bi-person-check me-2"></i>Daftar Sekarang');
                });
        });
    });
</script>