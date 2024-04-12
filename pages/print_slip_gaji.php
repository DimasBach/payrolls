<?php

$koneksi = mysqli_connect("localhost", "root", "", "payrolls");
if ($koneksi->connect_error) {
    die("Koneksi Gagal: " . $koneksi->connect_error);
}


$tgl_absen_1 = $_GET['tgl_absen_1'];
$tgl_absen_2 = $_GET['tgl_absen_2'];
if ($tgl_absen_1 != "" && $tgl_absen_2 != "") {
    $tgl_absen_1 = $_GET['tgl_absen_1'];
    $tgl_absen_2 = $_GET['tgl_absen_2'];
} elseif ($tgl_absen_1 != "" && $tgl_absen_2 == "") {
    $tgl_absen_2 = $tgl_absen_1;
} elseif ($tgl_absen_1 == "" && $tgl_absen_2 != "") {
    $tgl_absen_1 = $tgl_absen_2;
} else {
    $tgl_absen_1 = $_GET['tgl_absen_1'];
    $tgl_absen_2 = $_GET['tgl_absen_2'];
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
        .a{
            background-color: #ffe69c;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">

    <div class="container-fluid mt-1">
        <div class="row">

            <?php while ($baris = mysqli_fetch_assoc($hasil)) { ?>
                <!-- mulai slip gaji -->
                <div class="col-6">
                    <!-- Main content -->
                    <div class="invoice p-3 mb-3" style="border:1px solid black;">
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
                                                <td colspan="2" class="p-0 text-center fw-bold a">SLIP GAJI</td>
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
                                            <td colspan="3" class="text-center p-0 a">Pendapatan</td>
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
                                            <td colspan="3" class="a">Total Pendapatan........</td>
                                            <td class="text-end a">Rp <?php echo number_format($baris['total_gaji_harian'], 0, ',', '.') ?></td>
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
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->
            <?php } ?>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>