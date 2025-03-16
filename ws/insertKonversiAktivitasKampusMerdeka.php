<?php
echo "Jangan Tutup Browser Ini Hingga Proses Selesai";
$query = "SELECT * from insertkonversiaktivitaskampusmerdeka where err_no is null or err_no != '0' order by id ASC";
// echo $query;
$hasil = mysqli_query($db, $query);

if (mysqli_num_rows($hasil) > 0) {
    while ($x = mysqli_fetch_array($hasil)) {
        $id = $x['id'];

        $data = array(
            'id_semester' => $x['id_semester'],
            'id_matkul' => $x['id_matkul'],
            'id_anggota' => $x['id_anggota'],
            'id_aktivitas' => $x['id_aktivitas'],
            'sks_mata_kuliah' => $x['sks_mata_kuliah'],
            'nilai_angka' => $x['nilai_angka'],
            'nilai_indeks' => $x['nilai_indeks'],
            'nilai_huruf' => $x['nilai_huruf']
        );

        // Debugging payload sebelum dikirim
        echo "<pre>Payload yang dikirim ke API:</pre>";
        print_r($data);
        file_put_contents('1. TXT\log_payload_InsertKonversiAktivitasKampusMerdeka.txt', print_r($data, true), FILE_APPEND); // Logging payload
        file_put_contents('1. JSON\log_payload_InsertKonversiAktivitasKampusMerdeka.json', print_r($data, true), FILE_APPEND); // Logging payload
        ob_flush();
        flush();

        // Kirim request ke API
        $act = "InsertKonversiKampusMerdeka";
        $request = $ws->prep_insert($act, $data);
        print_r($request); // Debugging request
        $ws_result = $ws->run($request);

        // Debugging respons dari API
        echo "<pre>Respons dari API Neo-Feeder:</pre>";
        print_r($ws_result);
        file_put_contents('1. TXT\log_response_InsertKonversiAktivitasKampusMerdeka.txt', print_r($ws_result, true), FILE_APPEND); // Logging respons
        file_put_contents('1. JSON\log_response_InsertKonversiAktivitasKampusMerdeka.json', print_r($ws_result, true), FILE_APPEND); // Logging respons
        ob_flush();
        flush();

        $err_code = $ws_result[1]["error_code"];
        $err_desc = $ws_result[1]["error_desc"];

        if ($err_code == 0) {
            // $id_registrasi_mahasiswa = $ws_result[1]['data']["id_registrasi_mahasiswa"];
            $id_konversi_aktivitas = $ws_result[1]['data']["id_konversi_aktivitas"];
            $update = "UPDATE insertkonversiaktivitaskampusmerdeka SET err_no='$err_code', err_desc='$err_desc', id_konversi_aktivitas='$id_konversi_aktivitas' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . ". " . $id_activitas;
            print_r($statprogress);
            progress($statprogress, $act);
        } else {
            $update = "UPDATE insertkonversiaktivitaskampusmerdeka SET err_no='$err_code', err_desc='$err_desc', id_konversi_aktivitas='$id_konversi_aktivitas' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . " - " . $err_code . " - " . $err_desc;
            print_r($statprogress);
            progress($statprogress, $act);
        }

        // Tambahkan delay untuk menghindari throttling API
        // sleep(1); // Delay 1 detik
    }
} else {
    echo "data tidak ditemukan";
}

$status = "Inject Terakhir Selesai";
progress($status, $act);

echo "<script>window.close();</script>";
?>
