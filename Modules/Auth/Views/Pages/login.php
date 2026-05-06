<?php esc('LOGIN'); ?>
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <a href="<?php echo site_url() ?>" title="Perpustakaan Al Azhar">
                            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Perpustakaan Al Azhar" class="img-fluid" style="max-width: 220px;">
                        </a>
                    </div>
                    <h3 class="text-center mb-2">Login</h3>
                    <?php echo form_open('', array('id' => 'login-form')); ?>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" id="username" name="authentication_id" placeholder="Username" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group" id="show_hide_password">
                            <input id="password" type="password" name="authentication_password" class="form-control" placeholder="Password" autocomplete="new-password" onkeypress="return event.charCode != 32">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">&#128065;</button>
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
                    <div class="d-grid gap-2 mb-3">
                        <button id="btn01" type="submit" class="btn btn-primary btn-lg">LOGIN</button>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="text-center mt-3">
                        <span>Belum punya Akun? <a href="<?php echo site_url('signup') ?>" class="text-decoration-underline" title="SIGN UP NOW">MENDAFTAR</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('#login-form').submit(function(event) {
            event.preventDefault();
            $('#login-form').loading();
            $('#btn01').prop('disabled', true).text('Loading...');
            $.ajax({
                    url: '<?php echo site_url('auth/postdata/Authpost/do_login') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: $('#login-form').serialize(),
                })
                .done(function(data) {
                    updateCSRF(data.csrf_data);
                    if (data.status) {
                        location.reload()
                    } else {
                        Swal.fire({
                            type: data.type,
                            title: data.heading,
                            html: data.message,
                        })
                    }
                })
                .always(function() {
                    $('#login-form').loading('stop');
                    $('#btn01').prop('disabled', false).html('<i class="bi bi-box-arrow-in-right me-2"></i>Masuk');
                });
        });
    });
</script>