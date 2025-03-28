<?php
echo "Jangan Tutup Browser Ini Hingga Proses Selesai";
$query = "SELECT * from insertnilaitransfer where err_no is null or err_no != '0' order by id ASC";
// echo $query;
$hasil = mysqli_query($db, $query);

if (mysqli_num_rows($hasil) > 0) {
    while ($x = mysqli_fetch_array($hasil)) {
        $id = $x['id'];

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
            'id_aktivitas' => $x['id_aktivitas']
        );

        // Debugging payload sebelum dikirim
        echo "<pre>Payload yang dikirim ke API:</pre>";
        print_r($data);
        file_put_contents('1. TXT\log_payload_InsertNilaiTransfer.txt', print_r($data, true), FILE_APPEND); // Logging payload
        file_put_contents('1. JSON\log_payload_InsertNilaiTransfer.json', print_r($data, true), FILE_APPEND); // Logging payload
        ob_flush();
        flush();

        // Kirim request ke API
        $act = "InsertNilaiTransferPendidikanMahasiswa";
        $request = $ws->prep_insert($act, $data);
        print_r($request); // Debugging request
        $ws_result = $ws->run($request);

        // Debugging respons dari API
        echo "<pre>Respons dari API Neo-Feeder:</pre>";
        print_r($ws_result);
        file_put_contents('1. TXT\log_response_InsertNilaiTransfer.txt', print_r($ws_result, true), FILE_APPEND); // Logging respons
        file_put_contents('1. JSON\log_response_InsertNilaiTransfer.json', print_r($ws_result, true), FILE_APPEND); // Logging respons
        ob_flush();
        flush();

        $err_code = $ws_result[1]["error_code"];
        $err_desc = $ws_result[1]["error_desc"];

        if ($err_code == 0) {
            $id_transfer = $ws_result[1]['data']["id_transfer"];
            $update = "UPDATE insertnilaitransfer SET err_no='$err_code', err_desc='$err_desc', id_transfer='$id_transfer' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . ". " . $id_transfer;
            print_r($statprogress);
            progress($statprogress, $act);
        } else {
            $update = "UPDATE insertnilaitransfer SET err_no='$err_code', err_desc='$err_desc', id_transfer='$id_transfer' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . " - " . $err_code . " - " . $err_desc;
            print_r($statprogress);
            progress($statprogress, $act);
        }

        // Tambahkan delay untuk menghindari throttling API
        // sleep(5); // Delay 1 detik
    }
} else {
    echo "data tidak ditemukan";
}

$status = "Inject Terakhir Selesai";
progress($status, $act);

echo "<script>window.close();</script>";
?>
