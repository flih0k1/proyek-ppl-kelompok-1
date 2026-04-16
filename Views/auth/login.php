<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login — Perpustakaan UAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">

<div class="container" style="max-width:420px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="mb-1 fw-semibold">Perpustakaan UAI</h4>
            <p class="text-muted small mb-4">Masuk ke akun Anda</p>

            <?php if (session('success')): ?>
                <div class="alert alert-success"><?= session('success') ?></div>
            <?php endif ?>

            <?php if (session('error')): ?>
                <div class="alert alert-danger"><?= session('error') ?></div>
            <?php endif ?>

            <form action="/login" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= old('email') ?>" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>

            <p class="text-center mt-3 small">
                Belum punya akun? <a href="/register">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>