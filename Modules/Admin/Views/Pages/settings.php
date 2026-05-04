<div class="card">
    <div class="card-header">
        <h5 class="mb-2">Settings</h5>
    </div>
    <div class="card-body">


        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab" aria-controls="profil" aria-selected="true">Profil</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">Password</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="denda-tab" data-bs-toggle="tab" data-bs-target="#denda" type="button" role="tab" aria-controls="denda" aria-selected="false">Nominal Denda</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Profile</h6>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('', array('id' => 'change_userdata')); ?>
                        <input type="hidden" name="code" value="<?php echo $userdata->user_code; ?>">
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

                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Update Password</h6>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('', array('id' => 'updatepass')); ?>
                        <div class="form-group mb-3">
                            <label for="">Password Lama</label>
                            <input type="password" class="form-control" placeholder="Password Lama" autocomplete="off" name="current_password" id="pass1">
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="">Password Baru</label>
                                    <input type="password" class="form-control" placeholder="Password Baru" autocomplete="off" name="new_password" id="pass2">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="">Ulangi Password Baru</label>
                                    <input type="password" class="form-control" placeholder="Ulangi Password Baru" autocomplete="off" name="confirm_password" id="pass3">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <input type="checkbox" id="chk1" onclick="shopass()">
                            <label for="chk1" style="font-weight:300;">Show Password</label>
                        </div>
                        <div class="form-group mb-3">
                            <button id='btn01' type="submit" class="btn btn-primary btn-md btn-block" style="color:#fff">UPDATE PASSWORD</button>
                            <button id='btn02' type="button" class="btn btn-primary btn-md btn-block" disabled>MEMPROSES</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <script>
                        function shopass() {
                            var pass1 = document.getElementById("pass1");
                            if (pass1.type === "password") {
                                pass1.type = "text";
                            } else {
                                pass1.type = "password";
                            }

                            var pass2 = document.getElementById("pass2");
                            if (pass2.type === "password") {
                                pass2.type = "text";
                            } else {
                                pass2.type = "password";
                            }

                            var pass3 = document.getElementById("pass3");
                            if (pass3.type === "password") {
                                pass3.type = "text";
                            } else {
                                pass3.type = "password";
                            }
                        }

                        $('#btn02').hide();
                        $('#updatepass').submit(function(event) {
                            event.preventDefault();
                            $('#btn01').hide();
                            $('#btn02').show();

                            $.ajax({
                                    url: '<?php echo site_url('auth/postdata/Authpost/update_password_user') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: $('#updatepass').serialize(),
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
                                    $('#btn01').show();
                                    $('#btn02').hide();
                                })
                        });

  
                    </script>
                </div>
            </div>
            <div class="tab-pane fade" id="denda" role="tabpanel" aria-labelledby="denda-tab">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Update Nominal Denda</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="">Nominal Denda / hari</label>
                            <input type="number" class="form-control" placeholder="ex: 10000" value="<?php echo option('denda')['option_desc1'] ?>" autocomplete="off" name="denda">
                        </div>
                        <div class="form-group mb-3">
                            <button id='btn01ss' type="button" onclick="update_option('denda')" class="btn btn-primary btn-md btn-block" style="color:#fff">UPDATE NOMINAL</button>
                        </div>
                    </div>
                    <script>
                    

                        function update_option(name) {
                            $('#btn01ss').prop('disabled', true).text('Loading...');
                            let value = $(`input[name=${name}]`).val();
                            $.ajax({
                                    url: '<?php echo site_url('admin/postdata/General/update_option') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        name : name,
                                        value: value,
                                        csrf_myapp: $('input[name=csrf_myapp]').val()
                                    },
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
                                    $('#btn01').show();
                                })
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// $userdata = userdata();
// 			$msg = 'Selamat Bergabung Bpk/Ibu ' . $userdata->user_fullname . ",\n\nKloning akun Laqueen International Anda telah berhasil!\n\nNama: " .  $userdata->user_fullname . "\nUsername: " .  $userdata->username . "\nPassword: " .  $userdata->user_passtext . "\n\nNikmati berbagai peluang dan keuntungan bersama komunitas kami. Silakan login untuk mulai menjelajahi fitur-fitur eksklusif yang telah kami sediakan untuk Anda. Sukses selalu bersama Laqueen!";
// 			$phone = indo_phone_format($userdata->user_phone);
// 			print_r($this->usermodel->sendNotifWA($msg, $phone));
