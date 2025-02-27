<?php
echo "Jangan Tutup Browser Ini Hingga Proses Selesai";
$query = "SELECT * from insertrencanaevaluasi where err_no is null or err_no != '0' order by id ASC";
// echo $query;
$hasil = mysqli_query($db, $query);

if (mysqli_num_rows($hasil) > 0) {
    while ($x = mysqli_fetch_array($hasil)) {
        $id = $x['id'];

        $data = array(
            'id_matkul' => $x['id_matkul'],
            'id_jenis_evaluasi' => $x['id_jenis_evaluasi'],
            'nama_evaluasi' => $x['nama_evaluasi'],
            'deskripsi_indonesia' => $x['deskripsi_indonesia'],
            // 'deskripsi_inggris' => $x['deskripsi_inggris'],
            'nomor_urut' => $x['nomor_urut'],
            'bobot_evaluasi' => $x['bobot_evaluasi']
        );

        // Debugging payload sebelum dikirim
        echo "<pre>Payload yang dikirim ke API:</pre>";
        print_r($data);
        file_put_contents('1. TXT\log_payload_InsertRencanaEvaluasi.txt', print_r($data, true), FILE_APPEND); // Logging payload
        file_put_contents('1. JSON\log_payload_InsertRencanaEvaluasi.json', print_r($data, true), FILE_APPEND); // Logging payload
        ob_flush();
        flush();

        // Kirim request ke API
        $act = "InsertRencanaEvaluasi";
        $request = $ws->prep_insert($act, $data);
        print_r($request); // Debugging request
        $ws_result = $ws->run($request);

        // Debugging respons dari API
        echo "<pre>Respons dari API Neo-Feeder:</pre>";
        print_r($ws_result);
        file_put_contents('1. TXT\log_response_InsertRencanaEvaluasi.txt', print_r($ws_result, true), FILE_APPEND); // Logging respons
        file_put_contents('1. JSON\log_response_InsertRencanaEvaluasi.json', print_r($ws_result, true), FILE_APPEND); // Logging respons
        ob_flush();
        flush();

        $err_code = $ws_result[1]["error_code"];
        $err_desc = $ws_result[1]["error_desc"];

        if ($err_code == 0) {
            $id_rencana_evaluasi = $ws_result[1]['data']["id_rencana_evaluasi"];
            $update = "UPDATE insertrencanaevaluasi SET err_no='$err_code', err_desc='$err_desc', id_rencana_evaluasi='$id_rencana_evaluasi' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id;
            print_r($statprogress);
            progress($statprogress, $act);
        } else {
            $update = "UPDATE insertrencanaevaluasi SET err_no='$err_code', err_desc='$err_desc', id_rencana_evaluasi='$id_rencana_evaluasi' WHERE id=$id";
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
