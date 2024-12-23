<?php

$ModuleName = "AKTIVITAS MAHASISWA";
$table = "insertnilaitransfer2";
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
                                                                                            <th>NIMxxx</th>
                                                                                            <th>Kode Matkul Asal</th>
                                                                                            <th>Nama Matkul Asal</th>
                                                                                            <th>SKS Matkul Asal</th>
                                                                                            <th>Nilai Huruf Asal</th>
                                                                                            <th>ID Matkul</th>
                                                                                            <th>SKS Matkul Diakui</th>
                                                                                            <th>Nilai Huruf Diakui</th>
                                                                                            <th>Nilai Angka Diakui</th>
                                                                                            <th>ID Perguruan Tinggi</th>
                                                                                            <th>ID Semester</th>
                                                                                            <th>ID Aktivitas</th>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td>Nomor Induk Mahasiswa</td>
                                                                                            <td>Kode Kelas Kuliah<br>CII633</td>
                                                                                            <td>Nama Kelas Kuliah<br>ANALISIS ALGORITMA</td>
                                                                                            <td>Jumlah SKS</td>
                                                                                            <td>Nilai Huruf <br> A,B,C,D,E</td>
                                                                                            <td>ID Matkul</td>
                                                                                            <td>Jumlah SKS</td>
                                                                                            <td>Nilai Huruf <br> A,B,C,D,E</td>
                                                                                            <td>Nilai Index <br> Skala 4</td>
                                                                                            <td>ID Perguruan Tinggi</td>
                                                                                            <td>ID Semester</td>
                                                                                            <td>ID Aktivitas</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>contoh :</td>
                                                                                            <td colspan='7'>
                                                                                                <code>
                                                                                                    <pre>1903017025    CII633    ANALISIS ALGORITMA    3    B+    f711fc47-76af-47a7-8876-b31bc9c598f0    3    AB    3.5    861d9a64-9495-4688-a528-899fcca1c1e8    20231    20dfc199-0d4a-43c2-8cbb-03d698aae8f0
1903017009    UKJXA2    KEWARGANEGARAAN    2    A    a60f8e03-c524-4df1-8e95-87d0970cca65    2    A    4   861d9a64-9495-4688-a528-899fcca1c1e8   20232    20dfc199-0d4a-43c2-8cbb-03d698aae8f0</pre>
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
                                echo "<table class='table table-striped' id='table1' border='1'><tr>
    <th>Baris</th><th>NIM</th><th>NAMA</th><th>ID_Reg_mhs</th><th>Kode Matkul Asal</th><th>Nama Matkul Asal</th>
    <th>SKS Matkul Asal</th><th>Nilai Huruf Asal</th><th>ID Matkul</th><th>SKS Matkul Diakui</th><th>Nilai Huruf Diakui</th><th>Nilai Angka Diakui</th><th>ID PT</th><th>ID Semester</th><th>ID Aktivitas</th></tr>";
                                foreach ($line as $baris) {
                                    $baris = explode("\t", $baris);
                                    if (isset($baris[2])) {
                                        $no++;
                                        str_replace('"', '', $baris);
                                        $nim = $prodi = $id_reg_mhs = $nama = $baris[0];
                                        // $tanggalkeluar = date("Y-m-d", strtotime($baris[2]));
                                        cariidregmhs($nim, $id_reg_mhs, $nama, $prodi);
                                        // cariidmatkul($id_matkul);
                                        $nama_mahasiswa = $nama;
                                        $kode_mata_kuliah_asal = $baris[1];
                                        // $nama_mata_kuliah_asal = $baris[2];
                                        // $sks_mata_kuliah_asal = $baris[3];
                                        $nilai_huruf_asal = $baris[2];
                                        // $id_matkul = cariidmatkul($kode_mata_kuliah_asal);
                                        $data_matkul = cariidmatkul($kode_mata_kuliah_asal);
                                        // $sks_mata_kuliah_diakui = $baris[5];
                                        $nilai_huruf_diakui = $baris[3];
                                        $nilai_angka_diakui = isset($baris[4]) ? $baris[4] : '';
                                        $id_perguruan_tinggi = $baris[5];
                                        $id_semester = $baris[6];
                                        // $id_aktivitas = $baris[7];
                                        // $id_jenis_keluar = $baris[1];
                                        // $id_periode_keluar = $baris[3];
                                        // $ipk = isset($baris[4]) ? $baris[4] : '';
                                        // $nomor_ijazah = isset($baris[5]) ? $baris[5] : '';
                                        // $nomor_sk_yudisium = isset($baris[6]) ? $baris[6] : '';
                                        // $tanggal_sk_yudisium = isset($baris[7]) ? date("Y-m-d", strtotime($baris[7])) : '';

                                        echo "<tr><td>" . $no;
                                        echo "</td><td>" . $nim;
                                        echo "</td><td>" . $nama;
                                        echo "</td><td>" . $id_reg_mhs;
                                        echo "</td><td>" . $kode_mata_kuliah_asal;
                                        echo "</td><td>" . $data_matkul['nama_mata_kuliah'];
                                        echo "</td><td>" . $data_matkul['sks_mata_kuliah'];
                                        echo "</td><td>" . $nilai_huruf_asal;
                                        echo "</td><td>" . $data_matkul['id_matkul'];
                                        echo "</td><td>" . $data_matkul['sks_mata_kuliah'];
                                        echo "</td><td>" . $nilai_huruf_diakui;
                                        echo "</td><td>" . $nilai_angka_diakui;
                                        echo "</td><td>" . $id_perguruan_tinggi;
                                        echo "</td><td>" . $id_semester . "</td></tr>";
                                        // echo "</td><td>" . $id_aktivitas . "</td></tr>";
                                        // echo "</td><td>" . $id_jenis_keluar;
                                        // echo "</td><td>" . $tanggalkeluar;
                                        // echo "</td><td>" . $id_periode_keluar;
                                        // echo "</td><td>" . $ipk;
                                        // echo "</td><td>" . $nomor_ijazah;
                                        // echo "</td><td>" . $nomor_sk_yudisium;
                                        // echo "</td><td>" . $tanggal_sk_yudisium . "</td></tr>";
                                        
                                        // Pastikan $data_matkul sudah terisi sebelum bagian ini
                                        $nama_mata_kuliah_asal = $data_matkul['nama_mata_kuliah'] ?? 'Tidak Ditemukan';
                                        $sks_mata_kuliah_asal = $data_matkul['sks_mata_kuliah'] ?? 0;
                                        $id_matkul = $data_matkul['id_matkul'] ?? 'Tidak Ditemukan';
                                        $sks_mata_kuliah_diakui = $sks_mata_kuliah_asal; // Jika SKS diakui sama dengan SKS asal
                                        $nama_program_studi = $data_matkul['nama_program_studi'];

                                        $insert = "INSERT INTO insertnilaitransfer2 (
                                            id_registrasi_mahasiswa, nim, nama_mahasiswa, kode_mata_kuliah_asal, nama_mata_kuliah_asal, 
                                            sks_mata_kuliah_asal, nilai_huruf_asal, id_matkul, sks_mata_kuliah_diakui, nilai_huruf_diakui, nilai_angka_diakui, 
                                            id_perguruan_tinggi, id_semester, nama_program_studi, insertid
                                        ) VALUES (
                                            '$id_reg_mhs', '$nim', '$nama_mahasiswa', '$kode_mata_kuliah_asal', '$nama_mata_kuliah_asal', 
                                            '$sks_mata_kuliah_asal', '$nilai_huruf_asal', '$id_matkul', '$sks_mata_kuliah_diakui', '$nilai_huruf_diakui', 
                                            '$nilai_angka_diakui', '$id_perguruan_tinggi', '$id_semester', '$nama_program_studi', '$insertid'
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
