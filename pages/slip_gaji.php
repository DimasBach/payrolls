<?php

$koneksi = mysqli_connect("localhost", "root", "", "payrolls");
if ($koneksi->connect_error) {
    die("Koneksi Gagal: " . $koneksi->connect_error);
}

if (isset($_POST['cari'])) {
    // Mendapatkan nilai input tanggal dari form
    $tgl_absen_1 = $_POST['tgl_absen_1'];
    $tgl_absen_2 = $_POST['tgl_absen_2'];
    if ($tgl_absen_1 != "" && $tgl_absen_2 != "") {
        $tgl_absen_1 = $_POST['tgl_absen_1'];
        $tgl_absen_2 = $_POST['tgl_absen_2'];
    } elseif ($tgl_absen_1 != "" && $tgl_absen_2 == "") {
        $tgl_absen_2 = $tgl_absen_1;
    } elseif ($tgl_absen_1 == "" && $tgl_absen_2 != "") {
        $tgl_absen_1 = $tgl_absen_2;
    } else {
        $tgl_absen_1 = $_POST['tgl_absen_1'];
        $tgl_absen_2 = $_POST['tgl_absen_2'];
    }


    // untuk tampil tabel gaji
    $sql = "SELECT 
    karyawan.id_karyawan,
    karyawan.nama_karyawan,
    detail_jabatan.nama_jabatan,
    unit_kerja.nama_unit_kerja,
    SUM(absen.lembur) AS total_lembur,
    jabatan.gaji_harian,
    jabatan.gaji_lembur,
    absen.inpo_hari_id,
    absen.keterangan_absen_id,
    SUM(absen.inpo_hari_id = 1) AS jumlah_inpo_hari_1,
    SUM(absen.inpo_hari_id = 2) AS jumlah_inpo_hari_2,
    SUM(absen.keterangan_absen_id = 1) AS jumlah_keterangan_absen_1,
    SUM(absen.keterangan_absen_id = 2) AS jumlah_keterangan_absen_2,
    SUM(absen.keterangan_absen_id = 3) AS jumlah_keterangan_absen_3,
    SUM(absen.keterangan_absen_id = 4) AS jumlah_keterangan_absen_4,
    absen.tgl_absen,
    SUM(gaji.total_gaji_harian) AS total_gaji_harian
FROM 
    gaji
INNER JOIN 
    absen ON gaji.absen_id = absen.id_absen
INNER JOIN 
    karyawan ON absen.karyawan_id = karyawan.id_karyawan
INNER JOIN 
    jabatan ON karyawan.jabatan_id = jabatan.id_jabatan
INNER JOIN 
    detail_jabatan ON jabatan.detail_jabatan_id = detail_jabatan.id_detail_jabatan
INNER JOIN 
    unit_kerja ON jabatan.unit_kerja_id = unit_kerja.id_unit_kerja
WHERE
    absen.tgl_absen BETWEEN '$tgl_absen_1' AND '$tgl_absen_2'
GROUP BY 
    karyawan.id_karyawan
";
    $hasil = mysqli_query($koneksi, $sql);
}


function terbilang($angka)
{
    $angka = abs($angka);
    $baca = array(
        "", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"
    );
    $hasil = "";
    if ($angka < 12) {
        $hasil = " " . $baca[$angka];
    } else if ($angka < 20) {
        $hasil = terbilang($angka - 10) . " Belas";
    } else if ($angka < 100) {
        $hasil = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $hasil = " Seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $hasil = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $hasil = " Seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $hasil = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $hasil = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
    } else if ($angka < 1000000000000) {
        $hasil = terbilang($angka / 1000000000) . " Miliar" . terbilang($angka % 1000000000);
    } else if ($angka < 1000000000000000) {
        $hasil = terbilang($angka / 1000000000000) . " Triliun" . terbilang($angka % 1000000000000);
    }
    return $hasil;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Invoice</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="../plugins/dropzone/min/dropzone.min.css">

    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 0 4px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="../index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item menu-open">
                            <a href="../index.html" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="widgets.php" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Data Karyawan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="jabatan.php" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Data Jabatan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="absensi.php" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Absensi
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Gaji Karyawan
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="gaji.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gaji Per-Hari</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="gaji_mingguan.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gaji Per-Minggu</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Slip Gaji
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="slip_gaji.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Slip Gaji Per-Hari</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="slip_gaji_mingguan.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Slip Gaji Per-Minggu</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="calendar.html" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                <p>
                                    Calendar
                                    <span class="badge badge-info right">2</span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Pages
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="examples/invoice.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Invoice</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/profile.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Profile</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/e-commerce.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>E-commerce</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/projects.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Projects</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/project-add.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Project Add</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/project-edit.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Project Edit</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/project-detail.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Project Detail</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/contacts.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Contacts</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/faq.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>FAQ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="examples/contact-us.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Contact us</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Slip Gaji Per-Hari</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Slip Gaji Per-Hari</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="callout callout-info">
                                <h5><i class="fas fa-info"></i> <b>NOTE:</b> Cari tanggal untuk buat Slip Gaji Per-Hari</h5>
                                <form class="form-horizontal" action="slip_gaji.php" method="POST">

                                    <div class="form-group row">
                                        <label for="tgl_absen_1" class="col-sm-2 col-form-label">Tanggal Awal</label>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control" id="tgl_absen_1" placeholder="Isi Tanggal Awal" name="tgl_absen_1">
                                        </div>
                                        <label for="tgl_absen_2" class="col-sm-2 col-form-label">Tanggal Akhir</label>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control" id="tgl_absen_2" placeholder="Isi Tanggal Akhir" name="tgl_absen_2">
                                        </div>
                                        <button type="submit" class="btn btn-primary form-control col-sm-1" name="cari">Cari</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <?php if (isset($_POST['cari'])) { ?>
                            <?php
                            while ($baris = mysqli_fetch_assoc($hasil)) {
                            ?>
                                <!-- mulai slip gaji -->
                                <div class="col-6">
                                    <!-- Main content -->
                                    <div class="invoice p-3 mb-3">
                                        <!-- title row -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="container m-0 p-0 d-flex justify-content-start">
                                                    <img src="../dist/img/kss.png" class="img-fluid mb-2" alt="..." width="100" height="100">
                                                    <div class="d-flex flex-column justify-content-center align-items-start">
                                                        <h5 class="fw-semibold p-0 m-0">CV.KARYA SEMBILANGAN SEJAHTERA</h5>
                                                        <h5 class="text-danger p-0 m-0" style="font-size:16px;">Repair - Ship Building & General Contractor</h5>
                                                        <p class="p-0 m-0" style="font-size:12px;">Office: Ds. Sembilangan - Bangkalan 69118</p>
                                                        <p class="p-0 m-0" style="font-size:12px;">081252357070 / karyasembilangansejahtera@gmail.com</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <hr class="opacity-100 p-0 m-0 " style="border-color: black;">
                                        <!-- info row -->
                                        <div class="row d-flex align-items-end">
                                            <div class="col-sm-6 py-2">
                                                <div class="row g-0 ps-2">
                                                    <p class="mb-0 col-sm-3">Nama</p>
                                                    <p class="mb-0 col-sm-1"> : </p>
                                                    <p class="mb-0 col-sm-4 fw-semibold"><?php echo $baris['id_karyawan'] ?> - <?php echo $baris['nama_karyawan'] ?></p>
                                                </div>
                                                <div class="row g-0 ps-2">
                                                    <p class="mb-0 col-sm-3">U.Kerja</p>
                                                    <p class="mb-0 col-sm-1"> : </p>
                                                    <p class="mb-0 col-sm-4"><?php echo $baris['nama_unit_kerja'] ?></p>
                                                </div>
                                                <div class="row g-0 ps-2">
                                                    <p class="mb-0 col-sm-3">Jabatan</p>
                                                    <p class="mb-0 col-sm-1"> : </p>
                                                    <p class="mb-0 col-sm-4"><?php echo $baris['nama_jabatan'] ?></p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <table class="" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" class="p-0" style="text-align: center;">Kasbon</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-1">Pokok</td>
                                                                <td class="p-1">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-1">Sisa</td>
                                                                <td class="p-1">-</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 d-flex justify-content-end py-2">
                                                <div class="col-sm-9">
                                                    <p class="m-0 text-end">
                                                        <?php
                                                        $tanggal = $tgl_absen_2;
                                                        // Gunakan fungsi date() dengan format 'l' untuk mendapatkan nama hari dalam bahasa Indonesia
                                                        $nama_hari = date('l', strtotime($tanggal));
                                                        // Kamus nama hari dalam bahasa Inggris dan Indonesia
                                                        $nama_hari_kamus = array(
                                                            'Sunday' => 'Minggu',
                                                            'Monday' => 'Senin',
                                                            'Tuesday' => 'Selasa',
                                                            'Wednesday' => 'Rabu',
                                                            'Thursday' => 'Kamis',
                                                            'Friday' => 'Jumat',
                                                            'Saturday' => 'Sabtu'
                                                        );
                                                        // Ubah nama hari dalam bahasa Indonesia menggunakan kamus
                                                        $nama_hari_indo = $nama_hari_kamus[$nama_hari];
                                                        echo $nama_hari_indo; ?>
                                                        ,
                                                        <?php echo $tanggal_diformat = date('d F Y', strtotime($tgl_absen_2)); ?>
                                                    </p>
                                                    <table class="" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" class="p-0 text-center" style="background-color: #ffe69c;">SLIP GAJI</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-1">Basic per hari</td>
                                                                <td class="p-1 text-end">Rp <?php echo number_format($baris['gaji_harian'], 0, ',', '.') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-1">U.Lembur</td>
                                                                <td class="p-1 text-end">Rp <?php echo number_format($baris['gaji_lembur'], 0, ',', '.') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="p-1">-</td>
                                                                <td class="p-1">-</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->

                                        <!-- Table row -->
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table style="width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td rowspan="10" style="writing-mode: vertical-rl;" class="text-center fw-semibold p-0">
                                                                <p>Periode:</p>
                                                                <p>
                                                                    <?php
                                                                    // Tampilkan rentang tanggal atau tanggal tunggal sesuai kondisi
                                                                    if ($tgl_absen_1 != $tgl_absen_2) {
                                                                        echo date('d', strtotime($tgl_absen_1)) . " s/d " . date('d F Y', strtotime($tgl_absen_2));
                                                                    } else {
                                                                        echo date('d F Y', strtotime($tgl_absen_1));
                                                                    }
                                                                    ?>
                                                                </p>
                                                            </td>
                                                            <td colspan="3" class="text-center p-0" style="background-color: #ffe69c;">Pendapatan</td>
                                                            <td rowspan="2" class="text-center fw-semibold p-0">JML. Total</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center ">Status</td>
                                                            <td class="text-center ">Hari</td>
                                                            <td class="text-center ">Nominal</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">Normal</td>
                                                            <!-- hari -->
                                                            <?php
                                                            if ($baris['inpo_hari_id'] == 1  && $baris['keterangan_absen_id'] == 1) {
                                                                echo "<td class='text-center'>" . $baris['jumlah_inpo_hari_1'] . "</td>";
                                                            } elseif ($baris['inpo_hari_id'] == 1  && (($baris['keterangan_absen_id'] == 2 || $baris['keterangan_absen_id'] == 3) || $baris['keterangan_absen_id'] == 4)) {
                                                                if ($baris['jumlah_keterangan_absen_2'] != "" || $baris['jumlah_keterangan_absen_3'] != "" || $baris['jumlah_keterangan_absen_4'] != "") {
                                                                    $set = 0.5;
                                                                    $simpan1 = $baris['jumlah_keterangan_absen_2'] . $set;
                                                                    $simpan2 = $baris['jumlah_keterangan_absen_3'] . $set;
                                                                    $simpan3 = $baris['jumlah_keterangan_absen_4'];
                                                                    $final = $baris['jumlah_inpo_hari_1'] - ($simpan1 + $simpan2 + $simpan3);
                                                                }
                                                                echo "<td class='text-center'>" . $final . "</td>";
                                                            } else {
                                                                $a = 0;
                                                                echo "<td class='text-center'>" . $a . "</td>";
                                                            }
                                                            ?>

                                                            <!-- nominal -->
                                                            <?php
                                                            if ($baris['inpo_hari_id'] == 1 && $baris['keterangan_absen_id'] == 1) {
                                                                $p_normal = $baris['gaji_harian'] * $baris['jumlah_inpo_hari_1'];
                                                                echo "<td class='text-end'>Rp " . number_format($p_normal, 0, ',', '.') . "</td>";
                                                            } elseif ($baris['inpo_hari_id'] == 1 && (($baris['keterangan_absen_id'] == 2 || $baris['keterangan_absen_id'] == 3) || $baris['keterangan_absen_id'] == 4)) {
                                                                $set = 1 / 2;
                                                                $simpan1 = $baris['jumlah_keterangan_absen_2'] * $set;
                                                                $simpan2 = $baris['jumlah_keterangan_absen_3'] * $set;
                                                                $simpan3 = $baris['jumlah_keterangan_absen_4'];
                                                                $final = $baris['jumlah_inpo_hari_1'] - ($simpan1 + $simpan2 + $simpan3);
                                                                $p_normal = $final * $baris['gaji_harian'];
                                                                echo "<td class='text-end'>Rp " . number_format($p_normal, 0, ',', '.') . "</td>";
                                                            } else {
                                                                echo "<td class='text-end'>Rp 0</td>";
                                                            }
                                                            ?>
                                                            <!-- jml total -->
                                                            <td rowspan="3" class="text-end ">Rp <?php echo number_format($baris['total_gaji_harian'], 0, ',', '.') ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">Hari Besar</td>
                                                            <!-- hari haribesar -->
                                                            <?php
                                                            if ($baris['inpo_hari_id'] == 2  && $baris['keterangan_absen_id'] == 1) {
                                                                echo "<td class='text-center'>" . $baris['jumlah_inpo_hari_2'] . "</td>";
                                                            } elseif ($baris['inpo_hari_id'] == 2  && (($baris['keterangan_absen_id'] == 2 || $baris['keterangan_absen_id'] == 3) || $baris['keterangan_absen_id'] == 4)) {
                                                                if ($baris['jumlah_keterangan_absen_2'] != "" || $baris['jumlah_keterangan_absen_3'] != "" || $baris['jumlah_keterangan_absen_4'] != "") {
                                                                    $set = 0.5;
                                                                    $simpan1 = $baris['jumlah_keterangan_absen_2'] . $set;
                                                                    $simpan2 = $baris['jumlah_keterangan_absen_3'] . $set;
                                                                    $simpan3 = $baris['jumlah_keterangan_absen_4'];
                                                                    $final = $baris['jumlah_inpo_hari_2'] - ($simpan1 + $simpan2 + $simpan3);
                                                                }
                                                                echo "<td class='text-center'>" . $final . "</td>";
                                                            } else {
                                                                $a = 0;
                                                                echo "<td class='text-center'>" . $a . "</td>";
                                                            }
                                                            ?>

                                                            <!-- nominal hbesar -->
                                                            <?php
                                                            if ($baris['inpo_hari_id'] == 2 && $baris['keterangan_absen_id'] == 1) {
                                                                $p_besar = $baris['gaji_harian'] * $baris['jumlah_inpo_hari_2'];
                                                                echo "<td class='text-end'>Rp " . number_format($p_besar, 0, ',', '.') . "</td>";
                                                            } elseif ($baris['inpo_hari_id'] == 2 && (($baris['keterangan_absen_id'] == 2 || $baris['keterangan_absen_id'] == 3) || $baris['keterangan_absen_id'] == 4)) {
                                                                $set = 1 / 2;
                                                                $simpan1 = $baris['jumlah_keterangan_absen_2'] * $set;
                                                                $simpan2 = $baris['jumlah_keterangan_absen_3'] * $set;
                                                                $simpan3 = $baris['jumlah_keterangan_absen_4'];
                                                                $final = $baris['jumlah_inpo_hari_2'] - ($simpan1 + $simpan2 + $simpan3);
                                                                $p_besar = $final * $baris['gaji_harian'];
                                                                echo "<td class='text-end'>Rp " . number_format($p_besar, 0, ',', '.') . "</td>";
                                                            } else {
                                                                echo "<td class='text-end'>Rp 0</td>";
                                                            }
                                                            ?>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">Lembur</td>
                                                            <!-- hari lembur -->
                                                            <td class="text-center"><?php echo $baris['total_lembur'] ?> jam</td>
                                                            <!-- nominal -->
                                                            <td class="text-end">Rp <?php $p_lembur = $baris['total_lembur'] * $baris['gaji_lembur'];
                                                                                    echo number_format($p_lembur, 0, ',', '.') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">1.</td>
                                                            <td>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">2.</td>
                                                            <td>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">3.</td>
                                                            <td>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" style="background-color: #ffe69c;">Total Pendapatan........</td>
                                                            <td class="text-end" style="background-color: #ffe69c;">Rp <?php echo number_format($baris['total_gaji_harian'], 0, ',', '.') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span class="fw-semibold text-start">Terima Bersih........</span><span class="fw-semibold fs-4 text-end">Rp <?php echo number_format($baris['total_gaji_harian'], 0, ',', '.') ?></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">Terbilang</td>
                                                            <td colspan="4" class="text-center">
                                                                ===
                                                                <?php
                                                                $angka = $baris['total_gaji_harian']; // Contoh penggunaan $baris['total_gaji_harian']

                                                                // Memanggil fungsi terbilang untuk mengonversi angka menjadi tulisan dalam bahasa Indonesia
                                                                $terbilang = terbilang($angka);

                                                                // Menampilkan hasil terbilang
                                                                echo  " " . $terbilang . " rupiah";
                                                                ?>
                                                                ===
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->

                                        <!-- /.row -->

                                        <!-- this row will not appear when printing -->
                                        <div class="row no-print pt-3">
                                            <div class="col-12">
                                                <a href="print_slip_gaji.php?tgl_absen_1=<?php echo $tgl_absen_1; ?>&tgl_absen_2=<?php echo $tgl_absen_2; ?>" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                            </div>
                                        </div>

                                        <!-- <div class="row no-print pt-3">
                                            <div class="col-12">
                                                <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-- /.invoice -->
                                </div><!-- /.col -->
                            <?php } ?>
                        <?php } ?>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer no-print">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.2.0
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>
</body>

</html>