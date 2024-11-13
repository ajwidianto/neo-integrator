<?php
echo "progress get data dari feeder .......";

// $filter = "id_semester='20211'";
if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 100;
}
$filter = "";
$limit = "";
$order = "";
$act = "GetDetailKelasKuliah";

// Pastikan objek $ws sudah terinisialisasi sebelumnya
$request = $ws->prep_get($act, $filter, $limit, $order);
$ws_result = $ws->run($request);

$kosongkantabel = "TRUNCATE getkelaskuliah;";
mysqli_query($db, $kosongkantabel); // Kosongkan tabel sebelum memasukkan data baru
$no = 0;

if ($ws_result[1]["error_code"] == 0) {
    foreach ($ws_result as $key) {
        if (is_array($key)) {
            foreach ($key as $key2) {
                if (is_array($key2)) {
                    foreach ($key2 as $key3) {
                        $no++;

                        // Gunakan mysqli_real_escape_string untuk menghindari SQL Injection
                        $id_kelas_kuliah = mysqli_real_escape_string($db, $key3['id_kelas_kuliah']);
                        $id_prodi = mysqli_real_escape_string($db, $key3['id_prodi']);
                        $nama_program_studi = mysqli_real_escape_string($db, hapus_tanda($key3['nama_program_studi']));
                        $id_semester = mysqli_real_escape_string($db, $key3['id_semester']);
                        $nama_semester = mysqli_real_escape_string($db, $key3['nama_semester']);
                        $id_matkul = mysqli_real_escape_string($db, $key3['id_matkul']);
                        $kode_mata_kuliah = mysqli_real_escape_string($db, $key3['kode_mata_kuliah']);
                        $nama_mata_kuliah = mysqli_real_escape_string($db, hapus_tanda($key3['nama_mata_kuliah']));
                        $nama_kelas_kuliah = mysqli_real_escape_string($db, hapus_tanda($key3['nama_kelas_kuliah']));
                        $bahasan = mysqli_real_escape_string($db, $key3['bahasan']);
                        $tanggal_mulai_efektif = mysqli_real_escape_string($db, $key3['tanggal_mulai_efektif']);
                        $tanggal_akhir_efektif = mysqli_real_escape_string($db, $key3['tanggal_akhir_efektif']);
                        $kapasitas = mysqli_real_escape_string($db, $key3['kapasitas']);
                        $tanggal_tutup_daftar = mysqli_real_escape_string($db, $key3['tanggal_tutup_daftar']);
                        $prodi_penyelenggara = mysqli_real_escape_string($db, $key3['prodi_penyelenggara']);
                        $perguruan_tinggi_penyelenggara = mysqli_real_escape_string($db, $key3['perguruan_tinggi_penyelenggara']);

                        $id_key = $id_semester . "_" . $nama_kelas_kuliah . "_" . $kode_mata_kuliah . "_" . $id_prodi;

                        // Query untuk insert data
                        $inserdb = "INSERT INTO getkelaskuliah 
                        (id_kelas_kuliah, id_prodi, nama_program_studi, id_semester, nama_semester, 
                        id_matkul, kode_mata_kuliah, nama_mata_kuliah, nama_kelas_kuliah, 
                        bahasan, tanggal_mulai_efektif, tanggal_akhir_efektif, kapasitas, id_key) VALUES 
                        ('$id_kelas_kuliah', '$id_prodi', '$nama_program_studi', '$id_semester', '$nama_semester', 
                        '$id_matkul', '$kode_mata_kuliah', '$nama_mata_kuliah', '$nama_kelas_kuliah', 
                        '$bahasan', '$tanggal_mulai_efektif', '$tanggal_akhir_efektif', '$kapasitas', '$id_key');";

                        // Jalankan query insert dan cek error
                        if (!mysqli_query($db, $inserdb)) {
                            echo "Error: " . mysqli_error($db);
                        }

                        // Menampilkan progress setiap 1000 record
                        if ($no % 1000 == 0) {
                            $progress = "\n" . $no . " - ID Kelas Kuliah : " . $id_kelas_kuliah;
                            progress($progress, $act);
                        }
                    }
                }
            }
        }
    }
}

// Menampilkan progress akhir
$progress = "Get Data Selesai $no Record";
progress($progress, $act);

// Tutup koneksi setelah selesai
mysqli_close($db);
?>