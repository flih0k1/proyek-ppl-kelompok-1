<?php
$code = get('code');

$userdata = $this->db->table('tb_users')->where('user_code', $code)->get()->getRow();
if (!$userdata) {
?>
    <center>DATA USER TIDAK VALID</center>
<?php } else { ?>
    <?php echo form_open_multipart('', array('id' => 'change_userdata')); ?>
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="<?php echo avatar($userdata->id) ?>" id="previewCover" class="rounded mb-2" style="width:100px;height:100px;object-fit:cover;">
            <div>
                <label class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-upload me-1"></i> Upload Foto Profile
                    <input type="file" accept="image/*" name="user_img" class="d-none" onchange="previewImg(this)">
                </label>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
                <label for="exampleInputEmail1">Nama Lengkap</label>
                <input type="text" name="user_fullname" class="form-control" placeholder="Nama Lengkap" value="<?php echo $userdata->user_fullname; ?>" autocomplete="off">
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
                <label for="exampleInputEmail1">Username</label>
                <input type="text" name="user_username" class="form-control" placeholder="Nama Lengkap" value="<?php echo $userdata->username; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
                <label for="exampleInputEmail1">Alamat Email</label>
                <input type="text" name="user_email" class="form-control" placeholder="Alamat Email" value="<?php echo $userdata->email; ?>" autocomplete="off">
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="mb-3">
                <label for="exampleInputEmail1">Nomor WhatsApp</label>
                <input type="text" name="user_phone" class="form-control" placeholder="Nomor WhatsApp" value="<?php echo $userdata->user_phone; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button class="btn w-100 my-2 btn-primary" style="color:#fff">Update Data Member</button>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        function previewImg(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('previewCover').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).ready(function() {
            $('#change_userdata').submit(function(event) {
                event.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                        url: '<?php echo site_url('auth/postdata/Authpost/update_profile') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
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
        });
    </script>


    <hr>
    <h6>Update Password</h6>
    <?php echo form_open('', array('id' => 'change_password')); ?>
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="mb-3">
        <label for="exampleInputEmail1">New Password</label>
        <input type="text" name="password" class="form-control" placeholder="Password">
    </div>
    <div class="mb-3">
        <button class="btn w-100 my-2 btn-primary" style="color:#fff">Update Password</button>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#change_password').submit(function(event) {
                event.preventDefault();

                $.ajax({
                        url: '<?php echo site_url('auth/postdata/Authpost/update_password') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: $('#change_password').serialize(),
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
        });
    </script>
    <?php echo form_open('', array('id' => 'update-status'));

    $bg = ($userdata->active == 1) ? 'danger' : 'success';
    $text = ($userdata->active == 1) ? 'Non-aktifkan' : 'Aktifkan';
    ?>
    <div class="mb-2">
        <input type="hidden" value="<?= $code ?>" name="code">
        <button type="submit" class="btn w-100 btn-<?php echo $bg ?>"><?php echo $text ?></button>
        <?php echo form_close(); ?>

        <script>
            $('#update-status').submit(function(e) {
                e.preventDefault();

                $.ajax({
                        url: '<?php echo site_url('auth/postdata/Authpost/update_status') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: $('#update-status').serialize()
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
            })
        </script>
    <?php } ?>