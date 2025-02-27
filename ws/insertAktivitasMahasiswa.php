<?php
echo "Jangan Tutup Browser Ini Hingga Proses Selesai";
$query = "SELECT * from insertaktivitasmahasiswa where err_no is null or err_no != '0' order by id ASC";
// echo $query;
$hasil = mysqli_query($db, $query);

if (mysqli_num_rows($hasil) > 0) {
    while ($x = mysqli_fetch_array($hasil)) {
        $id = $x['id'];

        $data = array(
            'program_mbkm' => $x['program_mbkm'],
            'jenis_anggota' => $x['jenis_anggota'],
            'id_jenis_aktivitas' => $x['id_jenis_aktivitas'],
            'id_prodi' => $x['id_prodi'],
            'id_semester' => $x['id_semester'],
            'judul' => $x['judul'],
            'keterangan' => $x['keterangan'],
            'lokasi' => $x['lokasi'],
            'sk_tugas' => $x['sk_tugas'],
            'tanggal_sk_tugas' => $x['tanggal_sk_tugas'],
            'tanggal_mulai' => $x['tanggal_mulai'],
            'tanggal_selesai' => $x['tanggal_selesai']
        );

        // Debugging payload sebelum dikirim
        echo "<pre>Payload yang dikirim ke API:</pre>";
        print_r($data);
        file_put_contents('1. TXT\log_payload_InsertAktivitasMahasiswa.txt', print_r($data, true), FILE_APPEND); // Logging payload
        file_put_contents('1. JSON\log_payload_InsertAktivitasMahasiswa.json', print_r($data, true), FILE_APPEND); // Logging payload
        ob_flush();
        flush();

        // Kirim request ke API
        $act = "InsertAktivitasMahasiswa";
        $request = $ws->prep_insert($act, $data);
        print_r($request); // Debugging request
        $ws_result = $ws->run($request);

        // Debugging respons dari API
        echo "<pre>Respons dari API Neo-Feeder:</pre>";
        print_r($ws_result);
        file_put_contents('1. TXT\log_response_InsertAktivitasMahasiswa.txt', print_r($ws_result, true), FILE_APPEND); // Logging respons
        file_put_contents('1. JSON\log_response_InsertAktivitasMahasiswa.json', print_r($ws_result, true), FILE_APPEND); // Logging respons
        ob_flush();
        flush();

        $err_code = $ws_result[1]["error_code"];
        $err_desc = $ws_result[1]["error_desc"];

        if ($err_code == 0) {
            // $id_registrasi_mahasiswa = $ws_result[1]['data']["id_registrasi_mahasiswa"];
            $id_aktivitas = $ws_result[1]['data']["id_aktivitas"];
            $update = "UPDATE insertaktivitasmahasiswa SET err_no='$err_code', err_desc='$err_desc', id_aktivitas='$id_aktivitas' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . ". " . $id_activitas;
            print_r($statprogress);
            progress($statprogress, $act);
        } else {
            $update = "UPDATE insertaktivitasmahasiswa SET err_no='$err_code', err_desc='$err_desc', id_aktivitas='$id_aktivitas' WHERE id=$id";
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
