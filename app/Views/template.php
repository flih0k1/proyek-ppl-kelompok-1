<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
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


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    
</head>
<script type="text/javascript" charset="utf-8" async defer>
    function updateCSRF(value) {
        return $('input[name=csrf_myapp]').val(value);
    }

    function myCSRF(value) {
        return $('input[name=csrf_cadangan]').val(value);
    }
</script>

<style>
    .menu-arrow {
        transition: transform 0.15s;
        position: absolute;
        right: 20px;
        display: inline-block;
        /* 1. Ubah font-family ke Bootstrap Icons */
        font-family: "bootstrap-icons" !important;
        text-rendering: auto;
        line-height: 1.5rem;
        font-size: 1rem;
        /* BI biasanya terlihat sedikit lebih besar dari MDI */
        transform: translate(0, 0);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .menu-arrow:before {
        /* 2. Gunakan Unicode Bootstrap Icons (Contoh: Chevron Right) */
        content: "\f285";
    }

    li>a[aria-expanded=true]>span.menu-arrow {
        transform: rotate(90deg);
    }

    li.menuitem-active>a:not(.collapsed)>span.menu-arrow {
        transform: rotate(90deg);
    }
</style>

<body data-menu-color="light" data-sidebar="default" id="body">

    <!-- Begin page -->
    <div id="app-layout">


        <!-- Topbar Start -->
        <div class="topbar-custom">
            <div class="container-fluid">
                <div class="d-flex justify-content-between">
                    <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                        <li>
                            <button class="button-toggle-menu nav-link">
                                <i class="bi bi-list fs-3 noti-icon"></i>
                            </button>
                        </li>
                        <li class="d-none d-lg-block">
                            <h6 class="mb-0"> <?php echo howdy(userdata()->user_fullname) ?> </h6>
                        </li>
                    </ul>

                    <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                        <li class="d-none d-sm-flex">
                            <button type="button" class="btn nav-link" data-toggle="fullscreen">
                                <i class="bi bi-fullscreen align-middle fullscreen noti-icon"></i>
                            </button>
                        </li>


                        <!-- PROFILE MENU START -->
                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo avatar($userid) ?>" alt="user-image" class="rounded-circle">
                                <span class="pro-user-name ms-1">
                                    <?php echo userdata()->username ?> <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Halo... !</h6>
                                </div>

                                <!-- item-->
                                <a href="<?php echo base_url('settings') ?>" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                                    <span>Akun Saya</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <a onclick="logout_confirm()" class="dropdown-item notify-item">
                                    <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                    <span>Logout</span>
                                </a>

                            </div>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
        <!-- end Topbar -->

        <!-- Left Sidebar Start -->
        <div class="app-sidebar-menu">
            <div class="h-100" data-simplebar>

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <div class="logo-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="<?php echo base_url('assets/images/') ?>logo.png" alt="" height="60">
                            </span>
                            <span class="logo-lg">
                                <img src="<?php echo base_url('assets/images/') ?>logo.png" alt="" height="60">
                            </span>
                        </a>

                    </div>
                    <?php
                    $user_group = $this->ionAuth->getUsersGroups()->getRow();
                    if ($user_group->id == 1) {
                        $array_menu   =  config('custom')->menu_admin;
                    } elseif ($user_group->id == 2) {
                        $array_menu   =  config('custom')->menu_member;
                    } elseif ($user_group->id == 3) {
                        $array_menu   =  config('custom')->menu_pimpinan;
                    }
                    ?>
                    <?php
                    $uri     = service('uri');
                    $totalSegments = $uri->getTotalSegments();
                    $uriNow  = $uri->getSegment($totalSegments);

                    $segment = $totalSegments > 0
                        ? $uri->getSegment($totalSegments)
                        : '';
                    function isActive($segment, $menu)
                    {
                        return $segment === $menu ? 'active' : '';
                    }

                    function icon($segment, $menu, $icon, $iconActive)
                    {
                        return $segment === $menu ? $iconActive : $icon;
                    }
                    $counter  = 0;                          // agar id #collapse unik
                    ?>

                    <ul id="side-menu">
                        <?php foreach ($array_menu as $group): ?>

                            <!-- Judul kelompok menu -->
                            <li class="menu-title<?= isset($group['class']) ? ' ' . $group['class'] : '' ?>">
                                <?= htmlspecialchars($group['heading'], ENT_QUOTES, 'UTF-8') ?>
                            </li>

                            <?php foreach ($group['data'] as $item): ?>
                                <?php
                                $hasSub   = ! empty($item['submenu']);
                                $isActive = $segment;
                                $openSub  = false;

                                if ($hasSub) {
                                    /* cek apakah salah satu submenu sedang aktif */
                                    $subUrls = array_column($item['submenu'], 'url');
                                    $openSub = in_array($uriNow, $subUrls, true);

                                    /* pembuat id unik utk setiap collapse */
                                    $counter++;
                                    $collapseId = 'sidebarCollapse' . $counter;
                                }
                                ?>

                                <?php if ($hasSub): ?>
                                    <!-- ===== MENU BERSUB ===== -->
                                    <li>
                                        <a href="#<?= $collapseId ?>" data-bs-toggle="collapse" class="<?= $openSub ? 'active' : '' ?>">
                                            <i class="<?= $item['icon'] ?>"></i>
                                            <span> <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?> </span>
                                            <span class="menu-arrow"></span>
                                        </a>

                                        <div class="collapse <?= $openSub ? 'show' : '' ?>" id="<?= $collapseId ?>">
                                            <ul class="nav-second-level">
                                                <?php foreach ($item['submenu'] as $sub): ?>
                                                    <?php $subActive = $uriNow === $sub['url'] ? 'active' : ''; ?>
                                                    <li>
                                                        <a href="<?= site_url($sub['url']) ?>"
                                                            class="tp-link <?= $subActive ?>">
                                                            <?= htmlspecialchars($sub['title'], ENT_QUOTES, 'UTF-8') ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </li>

                                <?php else: ?>
                                    <!-- ===== MENU TANPA SUB ===== -->
                                    <?php
                                    $logoutAttr = ($item['title'] === 'Logout') ? 'onclick="logout_confirm()"' : '';
                                    $href       = ($item['title'] !== 'Logout')
                                        ? site_url($item['url'])
                                        : 'javascript:';
                                    ?>
                                    <li>
                                        <a href="<?= $href ?>" class="tp-link <?= $isActive ? 'active' : '' ?>" <?= $logoutAttr ?>>
                                            <i class="<?= $item['icon'] ?>"></i>
                                            <span> <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?> </span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>

                </div>
                <div class="clearfix"></div>

            </div>
        </div>
        <div class="content-page">
            <div class="content">

                <?php
                if (session('admin_userid') && (session('admin_userid') !== userid())) :
                    $getgroups = $this->ionAuth->getUsersGroups(session('admin_userid'))->getRow();
                    // echo form_hidden('csrf_cadangan', $this->security->get_csrf_hash());
                ?>
                    <div class="alert alert-danger m-1" role="alert">
                        <?php echo 'ANDA LOGIN SEBAGAI <u><b>' . strtoupper($userdata->user_fullname) . '</b></u> <a href="javascript:" id="login-back-admin" class="badge bg-success " style="color:#fff">KLIK DISINI</a> UNTUK KEMBALI KE ' . strtoupper($getgroups->name); ?>
                    </div>
                    <script type="text/javascript" charset="utf-8" async defer>
                        jQuery(document).ready(function($) {

                            $('#login-back-admin').click(function(event) {

                                $.ajax({
                                        url: '<?php echo site_url('auth/postdata/authpost/login_back_admin') ?>',
                                        type: 'post',
                                        dataType: 'json',
                                        data: {
                                            userid: 1,
                                            csrf_myapp: $('input[name=csrf_cadangan]').val()
                                        }
                                    })
                                    .done(function(data) {

                                        swal(
                                            data.heading,
                                            data.message,
                                            data.type
                                        ).then(function() {
                                            location.href =
                                                '<?php echo site_url('admin/dashboard') ?>';
                                        });

                                    });

                            });

                        });
                    </script>
                <?php endif ?>
                <div class="container-fluid">

                    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">

                        <div class="flex-grow-1">

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <?php echo esc($title) ?>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <?php echo $content ?>
                </div>
            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col fs-13 text-muted text-center">
                            &copy; <script>
                                document.write(new Date().getFullYear())
                            </script> <span class=" text-danger">❤️</span> by <a href="#!" class="text-reset fw-semibold"> <?php echo config('custom')->nama ?></a>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>
    </div>
    <div class="modal fade" id="dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dinamicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-spinner fa-spin"></i> loading ...
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modalbox" id="dinamicModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dinamicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel2">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <!-- <a href="#" data-bs-dismiss="modal">Close</a> -->
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- END wrapper -->

    <!-- Vendor -->
    <script src="<?php echo base_url('assets/backend') ?>/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url('assets/backend/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/node-waves/waves.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/libs/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="<?php echo base_url('assets/backend') ?>/js/app.js"></script>

    <script>
        $(".modal").on("show.bs.modal", function(e) {
            $(".modal").not(this).modal("hide");
        });
        $("#dinamicModal2").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load(link.attr("data-bs-href"));
            $(this).find("#myModalLabel2").text(link.attr("data-bs-title"));
        });
        $("#dinamicModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load(link.attr("data-bs-href"));
            $(this).find("#myModalLabel").text(link.attr("data-bs-title"));
        });

        function logout_confirm() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda akan keluar dari sesi dan kembali ke halaman login!",
                type: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, Logout',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.value) {
                    location.href = '<?php echo site_url('logout') ?>';
                }
            })
        }
        $(document).ready(function() {

            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#myTab button[data-bs-target="' + activeTab + '"]').tab('show');
            } else {
                $('#myTab button:first').tab('show');
            }
            $('#myTab button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                var tabId = $(e.target).data('bs-target');
                localStorage.setItem('activeTab', tabId);
            });

        });
        // $(document).ready(function() {
        //     const selectids = [
        //         "#default-select",
        //         "#default-select1",
        //         "#default-select2",
        //         "#default-select3",
        //         "#default-select4"
        //     ];
        //     selectids.forEach(id => {
        //         const $select = $(id);
        //         if ($select.length) {
        //             new Selectr($select[0]);

        //         }
        //     });
        //     const multiselectids = [
        //         "#multi-select",
        //         "#multi-select1",
        //         "#multi-select2",
        //         "#multi-select3",
        //         "#multi-select4"
        //     ];
        //     multiselectids.forEach(id => {
        //         const $multiselect = $(id);
        //         if ($multiselect.length) {
        //             new Selectr($multiselect[0], {
        //                 multiple: !0
        //             });

        //         }
        //     });
        //     // const $select = $("#default-select");

        //     // Array ID untuk datatable
        //     const datatableIds = [
        //         "#datatable_1",
        //         "#datatable_2",
        //         "#datatable_3",
        //         "#datatable_4",
        //         "#datatable_5"
        //     ];

        //     // Loop dan inisialisasi datatable jika elemen ada
        //     datatableIds.forEach(id => {
        //         const $table = $(id);
        //         if ($table.length) {
        //             const options = {
        //                 searchable: true,
        //                 fixedHeight: false
        //             };

        //             // Jika datatable_5, nonaktifkan sorting
        //             if (id === "#datatable_5") {
        //                 options.sortable = false;
        //             }

        //             new simpleDatatables.DataTable($table[0], options);
        //         }
        //     });

        //     document.querySelectorAll('.dataTable-dropdown label').forEach(function(label) {
        //         const select = label.querySelector('select');
        //         label.innerHTML = '';
        //         label.appendChild(select);
        //     });
        // });
    </script>

</body>


</html>