<?php
echo "Jangan Tutup Browser Ini Hingga Proses Selesai";
$query = "SELECT * from deletebimbingmahasiswa where err_no is null or err_no != '0' order by id ASC";
// echo $query;
$hasil = mysqli_query($db, $query);

if (mysqli_num_rows($hasil) > 0) {
    while ($x = mysqli_fetch_array($hasil)) {
        $id = $x['id'];

        $keys = array(
            'id_bimbing_mahasiswa' => $x['id_bimbing_mahasiswa']
        );

        // Debugging payload sebelum dikirim
        echo "<pre>Payload yang dikirim ke API:</pre>";
        print_r($keys);
        file_put_contents('1. TXT\log_payload_DeleteBimbingMahasiswa.txt', print_r($keys, true), FILE_APPEND); // Logging payload
        file_put_contents('1. JSON\log_payload_DeleteBimbingMahasiswa.json', print_r($keys, true), FILE_APPEND); // Logging payload
        ob_flush();
        flush();

        // Kirim request ke API
        $act = "DeleteBimbingMahasiswa";
        $request = $ws->prep_delete($act, $keys);
        print_r($request); // Debugging request
        $ws_result = $ws->run($request);

        // Debugging respons dari API
        echo "<pre>Respons dari API Neo-Feeder:</pre>";
        print_r($ws_result);
        file_put_contents('1. TXT\log_response_DeleteBimbingMahasiswa.txt', print_r($ws_result, true), FILE_APPEND); // Logging respons
        file_put_contents('1. JSON\log_response_DeleteBimbingMahasiswa.json', print_r($ws_result, true), FILE_APPEND); // Logging respons
        ob_flush();
        flush();

        $err_code = $ws_result[1]["error_code"];
        $err_desc = $ws_result[1]["error_desc"];

        if ($err_code == 0) {
            $id_bimbing_mahasiswa_delete = $ws_result[1]['data']["id_bimbing_mahasiswa"];
            $update = "UPDATE deletebimbingmahasiswa SET err_no='$err_code', err_desc='$err_desc', id_bimbing_mahasiswa_delete='$id_bimbing_mahasiswa_delete' WHERE id=$id";
            mysqli_query($db, $update);
            $statprogress = "<br>" . $id . ". " . $id_bimbing_mahasiswa;
            print_r($statprogress);
            progress($statprogress, $act);
        } else {
            $update = "UPDATE deletebimbingmahasiswa SET err_no='$err_code', err_desc='$err_desc', id_bimbing_mahasiswa_delete='$id_bimbing_mahasiswa_delete' WHERE id=$id";
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
