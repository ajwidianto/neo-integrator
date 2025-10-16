<?php

$ModuleName = "RIWAYAT PENDIDIKAN MAHASISWA";
$table = "insertriwayatpendidikanmahasiswa";
$link1 = $_SERVER['PHP_SELF'] . '?module=' . $module . '&aksi=tambah'; // tambah data
$link2 = "index.php?module=inject&act=" . $module; // Lihat Data / Lanjutkan Sync
$link3 = $_SERVER['PHP_SELF'] . '?module=' . $module . '&aksi=kosongkan'; // Kosongkan Data
$link4 = "index.php?module=inject&act=" . $module . "&show=no"; // PAGE SYNCRON KE FEEDER
$cancel = $_SERVER['PHP_SELF'] . '?module=' . $module . '&aksi=cancel&insertid=' . $insertid; // Cancel

if (isset($_GET['insertid'])) {
    $deleteinsert = $_GET['insertid'];
} else {
    $deleteinsert = null;
}

// ACT DETECT
if (isset($_GET['aksi'])) {
    $aksi = $_GET['aksi'];
    switch ($_GET['aksi']) {
        case "cancel":
            $clear_temp = "DELETE FROM " . $table . " WHERE insertid='$deleteinsert';";
            mysqli_query($db, $clear_temp);
            break;

        case "kosongkan":
            $clear_temp = "TRUNCATE " . $table;
            mysqli_query($db, $clear_temp);
            break;

        case "tambah";
            break;
        default:
            echo $_GET['aksi'] . " Aksi Tidak ditemukan";
    }
} else {
    $aksi = null;
}

// Cek Data Existing
$query = "select * from " . $table;
$berhasil = $sudahada = $gagal = $belum = 0;
$hasil = mysqli_query($db, $query);
$jmldata = mysqli_num_rows($hasil);

if (mysqli_num_rows($hasil) > 0) {
    while ($x = mysqli_fetch_array($hasil)) {
        if (is_null($x['err_no'])) {
            $belum++;
        } else if ($x['err_no'] == 0) {
            $berhasil++;
        } else {
            $gagal++;
        }
    }
}

?>

<div class="content-wrapper container">
    <div class="page-content">
        <section class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!----------------- OPSI TEXT ------------------------------->
                            <div class="tab-pane fade show active" id="list-TEXT" role="tabpanel" aria-labelledby="list-TEXT-list">
                                <h4 class="card-title">IMPORT DATA <?php echo $ModuleName; ?></h4>
                                <ul class="pagination pagination-primary justify-content-center">
                                    <div class="buttons justify-content-center">
                                        <div class="modal-success me-1 mb-1 d-inline-block"><br>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#success">
                                                PETUNJUK PENGGUNAAN
                                            </button>
                                            <!--Success theme Modal -->
                                            <div class="modal fade text-left" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title white" id="myModalLabel110"><b>PETUNJUK INPUTAN DATA <?php echo $ModuleName; ?>.</b></h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- ISO MODAL -->
                                                            <div class="tab-pane fade show active" id="list-monday" role="tabpanel" aria-labelledby="list-monday-list">
                                                                <section class="section">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <div class="card">
                                                                                <div class="card-header">
                                                                                    <table class='table table-striped' id='table1' border='1'>
                                                                                        <tr>
                                                                                            <th>ID Aktivitas</th>
                                                                                            <th>NIM</th>
                                                                                            <th>Jenis Peran</th>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td>ID Aktivitas</td>
                                                                                            <td>Nomor Induk Mahasiswa</td>
                                                                                            <td>1 : Ketua; 2 : Anggota; 3 : Personal</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>contoh :</td>
                                                                                            <td colspan='7'>
                                                                                                <code>
                                                                                                    <pre>1903017025	3557d958-d07e-4cdb-ba82-d6c9c5ef315e	3
                                                                                                    </pre>
                                                                                                </code>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    keterangan :<br>
                                                                                    <code>Copy data di Excel, lalu pastekan di kolom bawah; pemisah antar kolom menggunakan Tab; 1 baris = 1 record<br>Untuk Mempercepat Proses, Silahkan Lakukan GETDATA MAHASISWA</code>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <a href="#"><button type="submit" class="btn btn-success ml-1" data-bs-dismiss="modal">
                                                                                    <span class="d-none d-sm-block">lANJUTKAN</span>
                                                                                </button></a>
                                                                        </div>
                                                                    </div>
                                                                </section>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </ul>
                                <!-- ISO MODAL -->
                                Untuk Cara Penggunaan, Silahkan Klik Petunjuk Penggunaan
                                <?php
                                if ($jmldata > 0) {
                                    if ($aksi != 'tambah') {
                                        echo '<div class="alert alert-light-danger color-danger">Masih ada Data Existing Belum di Sync Ke Feeder Sejumlah ' . $jmldata . ' Record (Belum Sync : ' . $belum . ', Berhasil : ' . $berhasil . ', Gagal : ' . $gagal . '). 
                    <br>Hapus Terlebih Dahulu Atau Lanjutkan Syncronisasi Untuk Melihat Data<br>
                    <a href="' . $link1 . '"><button class="btn btn-primary ml-1" >TAMBAH DATA SEBELUM SYNC </button></a>
                    <a href="' . $link2 . '"><button class="btn btn-success ml-1" >LIHAT DATA / LANJUTKAN SYNC</button></a>
                        <!-- MODAL CANCEL -->
                        <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#cancel"> KOSONGKAN </button>   
                        <!--primary theme Modal -->
                        <div class="modal fade text-left" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                    <h5 class="modal-title white" id="myModalLabel110">Konfirmasi Pembatalan</h5>
                                    </div>
                                    <div class="modal-body">
                                    <center>Anda Yakin Ingin Membatalkan  Sejumlah <br>
                                        <h5 class="modal-title black" id="myModalLabel110">' . $jmldata . ' RECORD</center></h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <span class="d-none d-sm-block"> CLOSE </span></button>
                                        <a href="' . $link3 . '"> <button type="submit" class="btn btn-danger ml-1" > <span class="d-none d-sm-block"> KOSONGKAN </span></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                                            </div>';
                                    } else { ?>
                                        <!-- KONDISI KETIKA PENAMBAHAN DATA -->
                                        <form method="POST" action="<?php echo $link1; ?>">
                                            <div class="form-group with-title mb-3">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="input"></textarea>
                                                <label>Paste Data Excel di Kolom bawah</label>
                                            </div>
                                            <button class="btn btn-outline-secondary" type="submit">Sumbit</button>
                                            <br><br>
                                        </form>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- KONDISI KETIKA KOSONG -->
                                    <form method="POST" action="<?php echo $link1; ?>">
                                        <div class="form-group with-title mb-3">
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="input"></textarea>
                                            <label>Paste Data Excel di Kolom bawah</label>
                                        </div>
                                        <button class="btn btn-outline-secondary" type="submit">Sumbit</button>
                                        <br><br>
                                    </form>
                            <?php
                                }
                            ?>

                            <?php
                            // Inisialisasi variabel sebelum digunakan
                            $nama_mata_kuliah_asal = "";
                            $sks_mata_kuliah_asal = 0;
                            $id_matkul = "";
                            $sks_mata_kuliah_diakui = 0;
                            if (isset($_POST['input'])) {

                                $no = 0;
                                $input = $_POST['input'];
                                $line = explode("\n", $input);
                                // echo "<table class='table table-striped' id='table1' border='1'><tr>
                                //     <th>Baris</th><th>NIM Lama</th><th>ID Mahasiswa</th><th>NIM Baru</th><th>NAMA</th><th>Jenis Daftar</th>
                                //     <th>Jalur Daftar</th><th>Periode</th><th>Tanggal</th><th>ID PT</th><th>Nama Prodi</th><th>ID PT Asal</th><th>Nama Prodi Asal</th>
                                //     <th>Pembiayaan</th><th>Biaya Masuk</th></tr>";
                                echo "<table class='table table-striped' id='table1' border='1'><tr>
                                    <th>Baris</th><th>NIM Lama</th><th>NIM Baru</th><th>NAMA</th><th>Jenis Daftar</th>
                                    <th>Jalur Daftar</th><th>Periode</th><th>Tanggal</th><th>SKS Diakui</th><th>Nama PT</th><th>Nama Prodi</th><th>Nama PT Asal</th><th>Nama Prodi Asal</th>
                                    <th>Pembiayaan</th><th>Biaya Masuk</th></tr>";
                                foreach ($line as $baris) {
                                    $baris = explode("\t", $baris);
                                    if (isset($baris[2])) {
                                        $no++;
                                        str_replace('"', '', $baris);
                                        $nim = $prodi = $id_mahasiswa = $nama = $baris[0];
                                        $nim = $baris[0];
                                        cariidmhs2($nim, $id_mahasiswa, $nama, $prodi);
                                        $datamahasiswa = cariidmahasiswa($nim);
                                        // $datamahasiswa = cariidmhs2($nim, $id_mahasiswa, $nama, $prodi);
                                        $nama_mahasiswa = $nama;
                                        $nim_baru = $baris[1];
                                        $id_jenis_daftar = $baris[2];
                                        $id_jalur_daftar = $baris[3];
                                        $id_periode_masuk = $baris[4];
                                        $tanggal_daftar = $baris[5];
                                        $sks_diakui = $baris[6];
                                        // $id_perguruan_tinggi = $baris[6];
                                        $id_prodi = $baris[7];
                                        $dataprodibaru = cariallidprodi($id_prodi);
                                        // $id_perguruan_tinggi_asal = $baris[8];
                                        $id_prodi_asal = $baris[8];
                                        $dataprodilama = cariallidprodi($id_prodi_asal);
                                        $id_pembiayaan= $baris[9];
                                        $biaya_masuk = $baris[10];
                                        $jenjangDanProdibaru = $dataprodibaru['nama_jenjang_pendidikan'] . " " . $dataprodibaru['nama_program_studi'];
                                        $jenjangDanProdilama = $dataprodilama['nama_jenjang_pendidikan'] . " " . $dataprodilama['nama_program_studi'];

                                        echo "<tr><td>" . $no;
                                        echo "</td><td>" . $nim;
                                        // echo "</td><td>" . $datamahasiswa['id_mahasiswa'];
                                        echo "</td><td>" . $nim_baru;
                                        echo "</td><td>" . $datamahasiswa['nama_mahasiswa'];
                                        echo "</td><td>" . $id_jenis_daftar;
                                        echo "</td><td>" . $id_jalur_daftar;
                                        echo "</td><td>" . $id_periode_masuk;
                                        echo "</td><td>" . $tanggal_daftar;
                                        echo "</td><td>" . $sks_diakui;
                                        echo "</td><td>" . $dataprodibaru['nama_perguruan_tinggi'];
                                        echo "</td><td>" . $jenjangDanProdibaru;
                                        echo "</td><td>" . $dataprodilama['nama_perguruan_tinggi'];
                                        echo "</td><td>" . $jenjangDanProdilama;
                                        echo "</td><td>" . $id_pembiayaan;
                                        echo "</td><td>" . $biaya_masuk . "</td></tr>";

                                        // Pastikan $datadosen sudah terisi sebelum bagian ini
                                        $nama_mahasiswa = $datamahasiswa['nama_mahasiswa'] ?? 'Tidak Ditemukan';
                                        $id_mahasiswa = $datamahasiswa['id_mahasiswa'] ?? 'Tidak Ditemukan';
                                        $id_perguruan_tinggi = $dataprodibaru['id_perguruan_tinggi'] ?? 'Tidak Ditemukan';
                                        $nama_perguruan_tinggi = $dataprodibaru['nama_perguruan_tinggi'] ?? 'Tidak Ditemukan';
                                        $id_prodi = $dataprodibaru['id_prodi'] ?? 'Tidak Ditemukan';
                                        $id_perguruan_tinggi_asal = $dataprodilama['id_perguruan_tinggi'] ?? 'Tidak Ditemukan';
                                        $nama_perguruan_tinggi_asal = $dataprodilama['nama_perguruan_tinggi'] ?? 'Tidak Ditemukan';
                                        $id_prodi_asal = $dataprodilama['id_prodi'] ?? 'Tidak Ditemukan';
                                        // $jenjangDanProdibaru1 = $dataprodibaru['nama_jenjang_pendidikan'] . " " . $dataprodibaru['nama_program_studi'];
                                        // $jenjangDanProdilama1 = $dataprodilama['nama_jenjang_pendidikan'] . " " . $dataprodilama['nama_program_studi'];

                                        $insert = "INSERT INTO insertriwayatpendidikanmahasiswa (
                                            nama_mahasiswa, id_mahasiswa, nim_lama, nim_baru, id_jenis_daftar, id_jalur_daftar, id_periode_masuk, tanggal_daftar, sks_diakui, id_perguruan_tinggi, nama_perguruan_tinggi,
                                            id_prodi, nama_prodi, id_perguruan_tinggi_asal, nama_perguruan_tinggi_asal, id_prodi_asal, nama_prodi_asal, id_pembiayaan, biaya_masuk, insertid
                                        ) VALUES (
                                            '$nama_mahasiswa', '$id_mahasiswa', '$nim', '$nim_baru', '$id_jenis_daftar', '$id_jalur_daftar', '$id_periode_masuk', '$tanggal_daftar', '$sks_diakui',
                                            '$id_perguruan_tinggi', '$nama_perguruan_tinggi', '$id_prodi', '$jenjangDanProdibaru', '$id_perguruan_tinggi_asal', '$nama_perguruan_tinggi_asal', '$id_prodi_asal',
                                            '$jenjangDanProdilama', '$id_pembiayaan', '$biaya_masuk', '$insertid'
                                        )";
                                        mysqli_query($db, $insert);
                                    }
                                }
                                echo "<table>";
                            ?>

                                <!-- MODAL CANCEL & PROSES BUTTONS -->
                                <ul class="pagination pagination-primary justify-content-center">
                                    <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#cancel">
                                        CANCEL
                                    </button>
                                    <div class="modal fade text-left" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title white" id="myModalLabel110">Konfirmasi Pembatalan</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <center>Anda Yakin Ingin Membatalkan  Sejumlah <br>
                                                        <h5 class="modal-title black" id="myModalLabel110"><?php echo $no; ?> RECORD</center></h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"> <span class="d-none d-sm-block"> Close </span> </button>
                                                    <a href="<?php echo $cancel; ?>"> <button type="submit" class="btn btn-danger ml-1" > <span class="d-none d-sm-block">  BATALKAN  </span></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="<?php echo $link4; ?>">
                                        <button type="submit" class="btn btn-success ml-1">
                                            <span class="d-none d-sm-block">LANJUTKAN</span>
                                        </button>
                                    </a>
                                </ul>

                                <?php
                                echo '<script>
                                    $(document).ready(function() {
                                        $("html, body").animate({
                                            scrollTop: $(document).height()
                                        }, 2000);
                                    });
                                </script>';
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
