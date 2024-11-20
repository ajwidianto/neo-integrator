<?php
echo "Jangan Tutup Browser Ini Hingga Proses Selesai";
$query = "SELECT * from insertnilaitransfer where err_no is null or err_no != '0' order by id ASC";
// echo $query;
$hasil = mysqli_query($db, $query);

if (mysqli_num_rows($hasil) > 0) {
    while ($x = mysqli_fetch_array($hasil)) {
        $id = $x['id'];

        if (isset($_GET['tipe'])) {
            $tipe = $_GET['tipe'];
        } else {
            $tipe = "lengkap";
        }

        switch ($tipe) {
            case "minim":
                $data = array(
                    'id_registrasi_mahasiswa' => $x['id_registrasi_mahasiswa'],
                    'kode_mata_kuliah_asal' => $x['kode_mata_kuliah_asal'],
                    'nama_mata_kuliah_asal' => $x['nama_mata_kuliah_asal'],
                    'sks_mata_kuliah_asal' => $x['sks_mata_kuliah_asal'],
                    'nilai_huruf_asal' => $x['nilai_huruf_asal'],
                    'id_matkul' => $x['id_matkul'],
                    'sks_mata_kuliah_diakui' => $x['sks_mata_kuliah_diakui'],
                    'nilai_huruf_diakui' => $x['nilai_huruf_diakui'],
                    'nilai_angka_diakui' => $x['nilai_angka_diakui'],
                    'id_perguruan_tinggi' => $x['id_perguruan_tinggi'],
                    'id_semester' => $x['id_semester'],
                    'id_aktivitas' => $x['id_aktivitas'],
                );
                break;

            default:
                $data = array(
                    'id_registrasi_mahasiswa' => $x['id_registrasi_mahasiswa'],
                    'kode_mata_kuliah_asal' => $x['kode_mata_kuliah_asal'],
                    'nama_mata_kuliah_asal' => $x['nama_mata_kuliah_asal'],
                    'sks_mata_kuliah_asal' => $x['sks_mata_kuliah_asal'],
                    'nilai_huruf_asal' => $x['nilai_huruf_asal'],
                    'id_matkul' => $x['id_matkul'],
                    'sks_mata_kuliah_diakui' => $x['sks_mata_kuliah_diakui'],
                    'nilai_huruf_diakui' => $x['nilai_huruf_diakui'],
                    'nilai_angka_diakui' => $x['nilai_angka_diakui'],
                    'id_perguruan_tinggi' => $x['id_perguruan_tinggi'],
                    'id_semester' => $x['id_semester'],
                    'id_aktivitas' => $x['id_aktivitas'],
                );
        }

        // Debugging payload sebelum dikirim
        echo "<pre>Payload yang dikirim ke API:</pre>";
        print_r($data);
        ob_flush();
        flush();

        // Kirim request ke API
        $act = "InsertNilaiTransferPendidikanMahasiswa";
        $request = $ws->prep_insert($act, $data);
        $ws_result = $ws->run($request);

        // Debugging respons dari API
        echo "<pre>Respons dari API Neo-Feeder:</pre>";
        print_r($ws_result);
        ob_flush();
        flush();

        $err_code = $ws_result[1]["error_code"];
        $err_desc = $ws_result[1]["error_desc"];

        if ($err_code == 0) {
            $id_registrasi_mahasiswa = $ws_result[1]['data']["id_registrasi_mahasiswa"];
            $update = "UPDATE insertnilaitransfer SET err_no='$err_code', err_desc='$err_desc' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . ". " . $id_registrasi_mahasiswa;
            print_r($statprogress);
            progress($statprogress, $act);
        } else {
            $update = "UPDATE insertnilaitransfer SET err_no='$err_code', err_desc='$err_desc' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . " - " . $err_code . " - " . $err_desc;
            print_r($statprogress);
            progress($statprogress, $act);
        }
    }
} else {
    echo "data tidak ditemukan";
}

$status = "Inject Terakhir Selesai";
progress($status, $act);

echo "<script>window.close();</script>";
?>
