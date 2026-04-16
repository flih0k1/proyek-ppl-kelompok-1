<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi — Perpustakaan UAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-5">

<div class="container" style="max-width:500px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="mb-1 fw-semibold">Daftar Akun</h4>
            <p class="text-muted small mb-4">Perpustakaan Universitas Al Azhar Indonesia</p>

            <?php if (session('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="/register" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= old('nama') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= old('email') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIM / NIP</label>
                    <input type="text" name="nim_nip" class="form-control"
                           value="<?= old('nim_nip') ?>" placeholder="Opsional">
                </div>

                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control"
                           value="<?= old('no_hp') ?>" placeholder="Opsional">
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_pass" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>

            <p class="text-center mt-3 small">
                Sudah punya akun? <a href="/login">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>