<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo-kecil.png') ?>" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('logo.png') ?>">
        <link href="<?php echo base_url('assets/backend') ?>/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
        <link href="<?php echo base_url('assets/backend') ?>/css/custom.css" rel="stylesheet" type="text/css" id="app-style" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/vendors/jquery-easy-loading/dist/jquery.loading.min.css') ?>">
    <script src="<?php echo base_url('assets/backend') ?>/libs/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/') ?>fonts/bootstrap/bootstrap-icons.css">
    <script src="<?php echo base_url('assets/vendors/jquery-easy-loading/dist/jquery.loading.min.js') ?>"></script>
    <link href="<?php echo base_url("assets/backend/libs/sweetalert2/dist/sweetalert2.min.css") ?>" rel="stylesheet">
    <link href="<?php echo base_url("assets/backend/libs/sweetalert2/dist/sweetalert2.css") ?>" rel="stylesheet">
</head>
    <script type="text/javascript" charset="utf-8" async defer>
        function updateCSRF(value) {
            return $('input[name=csrf_myapp]').val(value);
        }

        function myCSRF(value) {
            return $('input[name=csrf_cadangan]').val(value);
        }
    </script>
<body>
  <?php echo $content ?>
    <script src="<?php echo base_url('assets/backend') ?>/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/node-waves/waves.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/feather-icons/feather.min.js"></script>
    <script src="<?php echo base_url('assets/backend/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>

</body>

</html>