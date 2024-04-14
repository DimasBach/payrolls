<?php

$koneksi = mysqli_connect("localhost", "root", "", "payrolls");
if ($koneksi->connect_error) {
    die("Koneksi Gagal: " . $koneksi->connect_error);
}

// untuk tampil tabel jabatan
$sql = "SELECT 
    karyawan.nama_karyawan,
    detail_jabatan.nama_jabatan,
    unit_kerja.nama_unit_kerja,
    absen.lembur,
    jabatan.gaji_harian,
    jabatan.gaji_lembur,
    absen.inpo_hari_id,
    absen.keterangan_absen_id,
    gaji.total_gaji_harian
    FROM 
    gaji
    INNER JOIN absen ON gaji.absen_id = absen.id_absen
    INNER JOIN karyawan ON absen.karyawan_id = karyawan.id_karyawan
    INNER JOIN jabatan ON karyawan.jabatan_id = jabatan.id_jabatan
    INNER JOIN unit_kerja ON jabatan.unit_kerja_id = unit_kerja.id_unit_kerja
    INNER JOIN detail_jabatan ON jabatan.detail_jabatan_id = detail_jabatan.id_detail_jabatan
";
$hasil = mysqli_query($koneksi, $sql);


// CRUD UNTUK TABEL JABATAN
// Tambah Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $nama_karyawan = $_POST['nama_karyawan'];
    $detail_jabatan_id = $_POST['detail_jabatan'];
    $unit_kerja_id = $_POST['unit_kerja'];

    // Ambil jabatan_id dari tabel jabatan berdasarkan detail_jabatan_id dan unit_kerja_id
    $sql_jabatan_id = "SELECT id_jabatan FROM jabatan WHERE detail_jabatan_id='$detail_jabatan_id' AND unit_kerja_id='$unit_kerja_id'";
    $result_jabatan_id = mysqli_query($koneksi, $sql_jabatan_id);
    $row_jabatan_id = mysqli_fetch_assoc($result_jabatan_id);
    $jabatan_id = $row_jabatan_id['id_jabatan'];

    // Insert data karyawan dengan jabatan_id yang didapatkan
    $sql_tambah = "INSERT INTO karyawan (nama_karyawan, jabatan_id) VALUES ('$nama_karyawan', '$jabatan_id')";
    if (mysqli_query($koneksi, $sql_tambah)) {
        echo "Data karyawan berhasil ditambahkan.";
        header("Location: widgets.php");
    } else {
        echo "Error: " . $sql_tambah . "<br>" . mysqli_error($koneksi);
    }
}


// Operasi Edit Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $detail_jabatan_id = $_POST['detail_jabatan'];
    $unit_kerja_id = $_POST['unit_kerja'];

    // Ambil jabatan_id dari tabel jabatan berdasarkan detail_jabatan_id dan unit_kerja_id
    $sql_jabatan_id = "SELECT id_jabatan FROM jabatan WHERE detail_jabatan_id='$detail_jabatan_id' AND unit_kerja_id='$unit_kerja_id'";
    $result_jabatan_id = mysqli_query($koneksi, $sql_jabatan_id);
    $row_jabatan_id = mysqli_fetch_assoc($result_jabatan_id);
    $jabatan_id = $row_jabatan_id['id_jabatan'];

    // Update data karyawan dengan jabatan_id yang didapatkan
    $sql_edit = "UPDATE karyawan SET nama_karyawan='$nama_karyawan', jabatan_id='$jabatan_id' WHERE id_karyawan='$id_karyawan'";
    if (mysqli_query($koneksi, $sql_edit)) {
        echo "Data karyawan berhasil diubah.";
        header("Location: widgets.php");
    } else {
        echo "Error: " . $sql_edit . "<br>" . mysqli_error($koneksi);
    }
}

// Operasi Hapus Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus'])) {
    $id_karyawan = $_POST['id_karyawan'];

    $sql_hapus = "DELETE FROM karyawan WHERE id_karyawan='$id_karyawan'";
    if (mysqli_query($koneksi, $sql_hapus)) {
        echo "Data karyawan berhasil dihapus.";
        header("Location: widgets.php");
    } else {
        echo "Error: " . $sql_hapus . "<br>" . mysqli_error($koneksi);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Gaji Per-Hari</title>

    <!-- Google Font: Source Sans Pro -->
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
                    <a href="../index.php" class="nav-link">Home</a>
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
            <a href="index.php" class="brand-link">
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
                            <a href="../index.php" class="nav-link">
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
                            <a href="slip_gaji.php" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Slip Gaji
                                </p>
                            </a>
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
                            <h1>Gaji Per-Hari</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Gaji Per-Hari</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-default">
                                        <i class="fas fa-plus"></i> Tambah Data Karyawan
                                    </a>
                                    <!-- <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div> -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Karyawan</th>
                                                <th>Jabatan</th>
                                                <th>Unit Kerja</th>
                                                <th>Gaji Normal</th>
                                                <th>Gaji HBesar</th>
                                                <th>Gaji Lembur</th>
                                                <th>Total Gaji</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($baris = mysqli_fetch_assoc($hasil)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $baris['nama_karyawan'] ?></td>
                                                    <td><?php echo $baris['nama_jabatan'] ?></td>
                                                    <td><?php echo $baris['nama_unit_kerja'] ?></td>
                                                    <?php
                                                    if ($baris['inpo_hari_id'] == 1  && $baris['keterangan_absen_id'] == 1) {
                                                        echo "<td>Rp " . number_format($baris['gaji_harian'], 0, ',', '.') . "</td>";
                                                    } elseif ($baris['inpo_hari_id'] == 1  && ($baris['keterangan_absen_id'] == 2 || $baris['keterangan_absen_id'] == 3)) {
                                                        $set = 1 / 2;
                                                        $tampil = $baris['gaji_harian'] * $set;
                                                        echo "<td>Rp " . number_format($tampil, 0, ',', '.') . "</td>";
                                                    } elseif ($baris['inpo_hari_id'] == 1  && ($baris['keterangan_absen_id'] == 4)) {
                                                        $set1 = 0;
                                                        echo "<td>Rp " . number_format($baris['gaji_harian'] * $set1, 0, ',', '.') . "</td>";
                                                    } else {
                                                        $a = 0;
                                                        echo "<td>Rp " . number_format($a, 0, ',', '.') . "</td>";
                                                    }
                                                    ?>

                                                    <?php
                                                    if ($baris['inpo_hari_id'] == 2  && $baris['keterangan_absen_id'] == 1) {
                                                        echo "<td>Rp " . number_format($baris['gaji_harian'], 0, ',', '.') . "</td>";
                                                    } elseif ($baris['inpo_hari_id'] == 2 && ($baris['keterangan_absen_id'] == 2 || $baris['keterangan_absen_id'] == 3)) {
                                                        $set = 1 / 2;
                                                        $tampil = $baris['gaji_harian'] * $set;
                                                        echo "<td>Rp " . number_format($tampil, 0, ',', '.') . "</td>";
                                                    } elseif ($baris['inpo_hari_id'] == 2 && ($baris['keterangan_absen_id'] == 4)) {
                                                        $set1 = 0;
                                                        echo "<td>Rp " . number_format($baris['gaji_harian'] * $set1, 0, ',', '.') . "</td>";
                                                    } else {
                                                        $a = 0;
                                                        echo "<td>Rp " . number_format($a, 0, ',', '.') . "</td>";
                                                    }
                                                    ?>
                                                    <td><?php echo 'Rp ' . number_format($baris['lembur']*$baris['gaji_lembur'], 0, ',', '.') ?></td>
                                                    <td><?php echo 'Rp ' . number_format($baris['total_gaji_harian'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->

            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Default Modal</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form action="widgets.php" method="post">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nama_karyawan">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="nama_karyawan" placeholder="Masukkan Nama Karyawan" name="nama_karyawan">
                                </div>
                                <div class="form-group">
                                    <label for="detail_jabatan">Jabatan</label>
                                    <select class="form-control" name="detail_jabatan">
                                        <?php
                                        $sql_detail_jabatan = "SELECT * FROM detail_jabatan";
                                        $hasil_detail_jabatan = mysqli_query($koneksi, $sql_detail_jabatan);
                                        while ($baris4 = mysqli_fetch_assoc($hasil_detail_jabatan)) {
                                        ?>
                                            <option value="<?php echo $baris4['id_detail_jabatan']; ?>"><?php echo $baris4['nama_jabatan']; ?></option>
                                        <?php }; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="unit_kerja">Unit Kerja</label>
                                    <select class="form-control" name="unit_kerja">
                                        <?php
                                        $sql_ukerja = "SELECT * FROM unit_kerja";
                                        $hasil_ukerja = mysqli_query($koneksi, $sql_ukerja);
                                        while ($baris5 = mysqli_fetch_assoc($hasil_ukerja)) {
                                        ?>
                                            <option value="<?php echo $baris5['id_unit_kerja']; ?>"><?php echo $baris5['nama_unit_kerja']; ?></option>
                                        <?php }; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="tambah">Save changes</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.2.0
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
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
    <script src="../dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- Select2 -->
    <script src="../plugins/select2/js/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/inputmask/jquery.inputmask.min.js"></script><!-- Select2 -->
    <script src="../plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- BS-Stepper -->
    <script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- dropzonejs -->
    <script src="../plugins/dropzone/min/dropzone.min.js"></script>
    <script>
        // function isiUnitKerja() {
        //     var idKaryawan = document.getElementById("nama_karyawan").value;
        //     var xhr = new XMLHttpRequest();
        //     xhr.onreadystatechange = function() {
        //         if (xhr.readyState == 4 && xhr.status == 200) {
        //             document.getElementById("unit_kerja").value = xhr.responseText;
        //         }
        //     };
        //     xhr.open("GET", "get_unit_kerja.php?id=" + idKaryawan, true);
        //     xhr.send();
        // }


        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                }
            });

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function(event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        });
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template")
        previewNode.id = ""
        var previewTemplate = previewNode.parentNode.innerHTML
        previewNode.parentNode.removeChild(previewNode)

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "/target-url", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        })

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() {
                myDropzone.enqueueFile(file)
            }
        })

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
        })

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1"
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        })

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0"
        })

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
        }
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true)
        }
        // DropzoneJS Demo Code End
    </script>
</body>

</html>