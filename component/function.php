<?php
$insertid = time();

// PDDIKTI =======================================================
function biodata($result,&$respon){
if ($result[1]["error_code"] == 0) {
foreach ($result as $key){
if (is_array($key)){
foreach ($key as $key2){
if (is_array($key2)){
foreach ($key2 as $key3){
$nama = $key3['nama_mahasiswa']; $tempat_lahir = $key3['tempat_lahir'];
$tanggal_lahir = $key3['tanggal_lahir']; $agama = $key3['nama_agama'];
$ibu = $key3['nama_ibu_kandung']; $jk = $key3['jenis_kelamin'];
$respon =  "Nama   : $nama
Tempat Lahir : $tempat_lahir
Tanggal Lahir : $tanggal_lahir
Jenis Kelamin : $jk
Nama Ibu : $ibu
Agama : $agama
";
}} }} }}else {
$respon = "</code>data idmhs tidak ditemukan</code>";
}
}

// PDDIKTI =======================================================
function cari_idmhs($result,&$respon){
if ($result[1]["error_code"] == 0) {
foreach ($result as $key){
if (is_array($key)){
foreach ($key as $key2){
if (is_array($key2)){
foreach ($key2 as $key3){
$respon = $id_mhs = $key3['id_mahasiswa'];
}} }} }}else {
$respon = "<code>data idmhs tidak ditemukan</code>";
}}

// PDDIKTI =======================================================
function riwayatpendidikan($ws,$id_mahasiswa,&$respon,&$id_registrasi_mahasiswa){
$request = $ws->prep_get("GetListRiwayatPendidikanMahasiswa","id_mahasiswa='$id_mahasiswa'");
$result = $ws->run($request);
if ($result[1]["error_code"] == 0) {
foreach ($result as $key){
if (is_array($key)){
foreach ($key as $key2){
if (is_array($key2)){
foreach ($key2 as $key3){
$nim = $key3['nim']; $nama = $key3['nama_mahasiswa'];
$jenis_daftar = $key3['nama_jenis_daftar']; $periode_masuk = $key3['nama_periode_masuk'];
$tanggal_daftar = $key3['tanggal_daftar']; $keterangan_keluar = $key3['keterangan_keluar'];
$pt = $key3['nama_perguruan_tinggi']; $prodi = $key3['nama_program_studi'];
$biaya_masuk = $key3['biaya_masuk'];
$respon = "Nama   : $nama
nim : $nim
Program Studi : $prodi
Perguruan Tinggi : $pt
Periode Masuk : $periode_masuk
Tanggal Masuk : $tanggal_daftar
Jenis Daftar : $tanggal_daftar
Jenis Keluar : $keterangan_keluar
Biaya Masuk : $biaya_masuk
";
}} }} }}else {
$respon = "<code>data idmhs tidak ditemukan</code>";
}
}

// PDDIKTI LOG=======================================================
function log_insert($act,$data_insert,$error_no,$error_desc){  
require "component/config.php";  
$data_insert = serialize($data_insert); 
$insert = "INSERT INTO log_insert (act, data, error, error_desc) VALUES 
('$act', '$data_insert', '$error_no', '$error_desc');";
mysqli_query($db, $insert);
    }

// PDDIKTI =======================================================
function get_token(&$token){    
    require "component/config.php"; 
    $data = '{   "act" : "GetToken",
        "username" : "'.$userfeeder.'",
        "password" : "'.$passfeeder.'"}';
        $curlfeeder = curl_init();
        curl_setopt_array($curlfeeder, array(
        CURLOPT_URL => $urlfeeder."/ws/live2.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array("content-type: application/json",),
        ));
      
        $response = curl_exec($curlfeeder);
        $respon = utf8_encode($response);
        $token = json_decode($respon, true);

        if($token['error_code'] == 0) {$token = $token['data']['token'];} else
        {$token=$token['error_desc'];}
        // print($token); 
    }

    

// PDDIKTI GET PERIODE LAMPAU by URL=======================================================
function get_PeriodeLampau(&$dataf){    
require "component/config.php"; 
get_token($token);
$url = $urlfeeder."/ws/profile/periodelampau";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Authorization: Bearer $token",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
$dataf = $resp;
// $dataf = $token;

    }

// PDDIKTI GET Log Syncronisasi by URL=======================================================
function get_LogSync(&$dataf){    
require "component/config.php"; 
get_token($token);
$url = $urlfeeder."/ws/profile/logsync";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
"Accept: application/json",
"Authorization: Bearer $token",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
$dataf = $resp;
// $dataf = $token;
}

// PDDIKTI GET APP Version by URL=======================================================
function get_Appversion(&$dataf){    
    require "component/config.php"; 
    get_token($token);
    $url = $urlfeeder."/ws/user/app_detail";
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
    "Accept: application/json",
    "Authorization: Bearer $token",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    curl_close($curl);
    $dataf = $resp;
    // $dataf = $token;
    }

// PDDIKTI GET APP Version by URL=======================================================
function get_CheckPoint(&$dataf){    
    require "component/config.php"; 
    get_token($token);
    $url = $urlfeeder."/ws/profile/checkpoint1";
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
    "Accept: application/json",
    "Authorization: Bearer $token",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    curl_close($curl);
    $dataf = $resp;
    // $dataf = $token;
    }

// PDDIKTI =======================================================
function getPT(&$pt){
    // $GetProfilPT='';
    ob_end_flush();
ob_implicit_flush();
require "component/config.php"; 
$request = $ws->prep_get('GetProfilPT');
        $ws_result = $ws->run($request);
if ($ws_result[1]["error_code"] == 0) {
    foreach ($ws_result as $key){
        if (is_array($key)){  foreach ($key as $key2){
        if (is_array($key2)){  foreach ($key2 as $key3){
                        $cari_huruf = array("\n","\r","'",'&amp;','&copy;','&crarr;','&dArr;','&deg;','&divide;','&eacute;','&fnof;','&frasl;','&ge;','&harr;','&hArr;','&hellip;','&larr;','&lArr;','&ldquo;','&le;','&lowast;','&lsquo;','&mdash;','&nbsp;','&ndash;','&ne;','&plusmn;','&prime;','&Prime;','&quot;','&rarr;','&rArr;','&rdquo;','&reg;','&rsquo;','&sect;','&sum;','&times;','&uarr;','&uArr;','&lt;br /&gt;','&lt;','&gt;','%','&check;','&raquo;','&laquo;');
                        $huruf_baru = array("\n","","",'&','©','?','?','°','÷','é','ƒ','?','?','?','?','…','?','?','“','?','?','','','','-','?','±','?','?','"','?','?','','®','','§','?','×','?','?','','<','>',"","✓",">>","<<");													
                        $pt = array();
                        $pt['id_pt'] = $key3['id_perguruan_tinggi'];
                        $pt['kode_pt'] = $key3['kode_perguruan_tinggi'];
                        $pt['nama_pt'] = $key3['nama_perguruan_tinggi'];
                        $pt['telp'] = $key3['telepon'];
                        $pt['faximile'] = $key3['faximile'];
                        $pt['email'] = $key3['email'];
                        $pt['sk_pendirian'] = $key3['sk_pendirian'];
                        $pt['nama_wilayah'] = $key3['nama_wilayah'];
                        $pt['tanggal_sk_pendirian'] = $key3['tanggal_sk_pendirian'];
                        $pt['website'] = $key3['website'];
        } } //IF Key2
        } } //IF Key1
    } } }

    
// GetPeriodeLampau by WS  Periode Lampau =======================================================
function getPeriodeLampau(&$pt){
    // $GetProfilPT='';
    ob_end_flush();
ob_implicit_flush();
require "component/config.php"; 
$request = $ws->prep_get('GetPeriodeLampau');
        $ws_result = $ws->run($request);
if ($ws_result[1]["error_code"] == 0) {
    foreach ($ws_result as $key){
        if (is_array($key)){  foreach ($key as $key2){
        if (is_array($key2)){  foreach ($key2 as $key3){
                        $cari_huruf = array("'",'&amp;','&copy;','&crarr;','&dArr;','&deg;','&divide;','&eacute;','&fnof;','&frasl;','&ge;','&harr;','&hArr;','&hellip;','&larr;','&lArr;','&ldquo;','&le;','&lowast;','&lsquo;','&mdash;','&nbsp;','&ndash;','&ne;','&plusmn;','&prime;','&Prime;','&quot;','&rarr;','&rArr;','&rdquo;','&reg;','&rsquo;','&sect;','&sum;','&times;','&uarr;','&uArr;','&lt;br /&gt;','&lt;','&gt;','%','&check;','&raquo;','&laquo;');
                        $huruf_baru = array("",'&','©','?','?','°','÷','é','ƒ','?','?','?','?','…','?','?','“','?','?','','','','-','?','±','?','?','"','?','?','','®','','§','?','×','?','?','','<','>',"","✓",">>","<<");													
                        $pt = array();
                        $pt['id_program_studi'] = $key3['id_program_studi'];
                        $pt['program_studi'] = $key3['program_studi'];
                        $pt['id_semester'] = $key3['id_semester'];
                        $pt['semester'] = $key3['semester'];
                        $pt['tipe_periode'] = $key3['tipe_periode'];
                        
        } } //IF Key2
        } } //IF Key1
    } } }

// GetCountRiwayatPendidikanMahasiswa =======================================================
function jmlmhs(&$jmlmhs){
require "component/config.php"; 
$request = $ws->prep_get('GetCountRiwayatPendidikanMahasiswa');
        $ws_result = $ws->run($request);
        $jmlmhs = $ws_result[1]["data"];
 }

 // GetCountAktivitas =======================================================
function jmlakm(&$jmlakm){
require "component/config.php"; 
$request = $ws->prep_get('GetCountPerkuliahanMahasiswa');
        $ws_result = $ws->run($request);
        $jmlakm = $ws_result[1]["data"];
 }

 
 // GetCountAktivitas =======================================================
function jmlakmdetail(&$jmlakm,$idprodi,$idsemester){
    require "component/config.php"; 
    $filter = "id_prodi = '".$idprodi."' and id_semester='$idsemester'";

    $request = $ws->prep_get('GetCountPerkuliahanMahasiswa',$filter,1,0);
            $ws_result = $ws->run($request);
            $jmlakm = $ws_result[1]["data"];
     }
     
     // GetCountAktivitas =======================================================
function jmldosenprodi(&$jmlakm,$idprodi,$idsemester){
    require "component/config.php"; 
    $filter = "id_prodi = '".$idprodi."' and id_periode='$idsemester'";

    $request = $ws->prep_get('GetCountAktivitasMengajarDosen',$filter,1,0);
            $ws_result = $ws->run($request);
            $jmlakm = $ws_result[1]["data"];
}

     // GetCountAktivitas =======================================================
function jmlakmsmt(&$jmlakm,$idsemester){
    require "component/config.php"; 
    $filter = "id_semester='$idsemester'";
    $request = $ws->prep_get('GetCountPerkuliahanMahasiswa',$filter,1,0);
            $ws_result = $ws->run($request);
            $jmlakm = $ws_result[1]["data"];
     }


// INSERT KELULUSAN by URL=======================================================
function lulus($id_reg,$id_jk,$tgl_keluar,$periode,$ipk,$ijazah,&$err,&$desc){    
    require "component/config.php"; 
    get_token($token);
    $url = $urlfeeder."/ws/mhslulus/add";
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
       "Accept: application/json",
       "Authorization: Bearer $token",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    curl_close($curl);
    $dataf = $resp;
    // $dataf = $token;
        }

 // GetCountAktivitas =======================================================
function jmllulus(&$jmllulus){
require "component/config.php"; 
$request = $ws->prep_get('GetCountMahasiswaLulusDO');
        $ws_result = $ws->run($request);
        $jmllulus = $ws_result[1]["data"];
 }

 // GetCountAktivitas =======================================================
function jmldosen(&$jmldosen){
    require "component/config.php"; 
    $request = $ws->prep_get('GetCountDosen');
            $ws_result = $ws->run($request);
            $jmldosen = $ws_result[1]["data"];
     }

// CARI ID IDMAHASIWA ==============================================================
function cariidmhs(&$nama,&$tanggallahir,&$id_mahasiswa){
    require "component/config.php"; 
    $nama = strtoupper($nama);
    $tanggal_lahir = $tanggallahir;
    $tanggal_lahir = date("d-m-Y", strtotime($tanggal_lahir));  
    $filter = "nama_mahasiswa='$nama' and tanggal_lahir='$tanggal_lahir' "; //cek berdasarkan nama
    $request = $ws->prep_get('GetBiodataMahasiswa',$filter,1,0);
    $ws_result = $ws->run($request);
    // $ws->view($ws_result);
    if (isset($ws_result[1]["data"][0]["id_mahasiswa"])) {
        $id_mahasiswa = $ws_result[1]["data"][0]["id_mahasiswa"];	
        } else {
            $nama = strtoupper($nama);
            $tanggal_lahir = $tanggallahir;
            $tanggal_lahir = date("d-m-Y", strtotime($tanggal_lahir));  
            $filter = "nama_mahasiswa='$nama' and tanggal_lahir='$tanggal_lahir' "; //cek berdasarkan nama
            $request = $ws->prep_get('GetBiodataMahasiswa',$filter,1,0);
            $ws_result = $ws->run($request);
            // $ws->view($ws_result);
            if (isset($ws_result[1]["data"][0]["id_mahasiswa"])) {
                $id_mahasiswa = $ws_result[1]["data"][0]["id_mahasiswa"];	
                } else {$id_mahasiswa = "<code>Tidak Ditemukan</code>";}
        }  
}


// CARI ID REG IDMAHASIWA berdasarkan NIM ==============================================================
function cariidregmhs(&$nim,&$id_reg_mhs,&$nama,&$prodi){
    require "component/config.php";
    // cariidblokal
    $query = "SELECT a.nama_mahasiswa, a.id_registrasi_mahasiswa, a.id_prodi, a.nama_program_studi FROM getmahasiswa AS a WHERE nim ='$nim'";
    $hasil = mysqli_query($db, $query);
    if(mysqli_num_rows($hasil) > 0 ){
        while($x = mysqli_fetch_array($hasil)){
            $id_reg_mhs = $x['id_registrasi_mahasiswa'];
            $nama = $x['nama_mahasiswa'];
            $prodi = $x['nama_program_studi'];
        }}
        else        //cari di WS
        {
            $filter = "nim='$nim'"; //cek berdasarkan NIM
            $request = $ws->prep_get('GetListMahasiswa',$filter,1,0);
            $ws_result = $ws->run($request);
            // $ws->view($ws_result);
            if (isset($ws_result[1]["data"][0]["id_registrasi_mahasiswa"])) {
                $id_reg_mhs = $ws_result[1]["data"][0]["id_registrasi_mahasiswa"];	
                $nama = $ws_result[1]["data"][0]["nama_mahasiswa"];	
                $prodi = $ws_result[1]["data"][0]["nama_program_studi"];	
                } else {
                    $id_reg_mhs=$nama = "<code>Tidak Ditemukan</code>";
                    
                }  
                } 
}

// CARI ID BIODATA MAHASISWA ==============================================================
function caribiomhs(&$nim,&$nama,&$jk,&$tl,&$prodi,&$status){
    require "component/config.php";
    
            $filter = "nim='$nim'"; //cek berdasarkan NIM
            $request = $ws->prep_get('GetListMahasiswa',$filter,1,0);
            $ws_result = $ws->run($request);
            // $ws->view($ws_result);
            if (isset($ws_result[1]["data"][0]["id_registrasi_mahasiswa"])) {
                $id_reg_mhs = $ws_result[1]["data"][0]["id_registrasi_mahasiswa"];	
                $status = $ws_result[1]["data"][0]["nama_status_mahasiswa"];	
                $prodi = $ws_result[1]["data"][0]["nama_program_studi"];	
                $jk = $ws_result[1]["data"][0]["jenis_kelamin"];	
                if($jk=='P'){$jk='Perempuan';}else if($jk=='L'){$jk='Laki-Laki';}else{$jk='TIDAK DIKENALI';}
                $tl = $ws_result[1]["data"][0]["tanggal_lahir"];	
                $nama = $ws_result[1]["data"][0]["nama_mahasiswa"];	
                } else {
                    $id_reg_mhs=$nama = "<code>Tidak Ditemukan</code>";
                    
                }  
                
}
// CARI ID Matakuliah ==============================================================
function cariidmk(&$kodemk){
    require "component/config.php"; 
    $kodemk = "kodemk";
    $tanggal_lahir = date("d-m-Y", strtotime($tanggal_lahir));  
    $filter = "nama_mahasiswa='$nama' and tanggal_lahir='$tanggal_lahir' "; //cek berdasarkan nama
    $request = $ws->prep_get('GetDetailMataKuliah',$filter,1,0);
    $ws_result = $ws->run($request);
    // $ws->view($ws_result);
    if (isset($ws_result[1]["data"][0]["id_mahasiswa"])) {
        $id_mahasiswa = $ws_result[1]["data"][0]["id_mahasiswa"];	
        } else {
            $nama = strtoupper($nama);
            $tanggal_lahir = $tanggallahir;
            $tanggal_lahir = date("d-m-Y", strtotime($tanggal_lahir));  
            $filter = "nama_mahasiswa='$nama' and tanggal_lahir='$tanggal_lahir' "; //cek berdasarkan nama
            $request = $ws->prep_get('GetBiodataMahasiswa',$filter,1,0);
            $ws_result = $ws->run($request);
            // $ws->view($ws_result);
            if (isset($ws_result[1]["data"][0]["id_mahasiswa"])) {
                $id_mahasiswa = $ws_result[1]["data"][0]["id_mahasiswa"];	
                } else {$id_mahasiswa = "<code>Tidak Ditemukan</code>";}
        }  
}
// CARI ID Matakuliah ==============================================================
function cariidmatkul($kode_mata_kuliah) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "kode_mata_kuliah='$kode_mata_kuliah'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetDetailMataKuliah', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_matkul = $ws_result[1]["data"][0];
        return [
            'id_matkul' => $data_matkul['id_matkul'],
            'nama_mata_kuliah' => $data_matkul['nama_mata_kuliah'],
            'sks_mata_kuliah' => $data_matkul['sks_mata_kuliah'],
            'nama_program_studi' => $data_matkul['nama_program_studi'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_matkul' => "<code>ID Mata Kuliah Tidak Ditemukan</code>",
            'nama_mata_kuliah' => "<code>Nama Mata Kuliah Tidak Ditemukan</code>",
            'sks_mata_kuliah' => "<code>SKS Tidak Ditemukan</code>",
            'nama_program_studi' => "<code>SKS Tidak Ditemukan</code>",
        ];
    }
}
// CARI ID Matakuliah ==============================================================
function cariidmatkul2($kode_mata_kuliah, $id_prodi) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "kode_mata_kuliah='$kode_mata_kuliah' and id_prodi = '$id_prodi'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetDetailMataKuliah', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_matkul = $ws_result[1]["data"][0];
        return [
            'id_matkul' => $data_matkul['id_matkul'],
            'nama_mata_kuliah' => $data_matkul['nama_mata_kuliah'],
            'sks_mata_kuliah' => $data_matkul['sks_mata_kuliah'],
            'nama_program_studi' => $data_matkul['nama_program_studi'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_matkul' => "<code>ID Mata Kuliah Tidak Ditemukan</code>",
            'nama_mata_kuliah' => "<code>Nama Mata Kuliah Tidak Ditemukan</code>",
            'sks_mata_kuliah' => "<code>SKS Tidak Ditemukan</code>",
            'nama_program_studi' => "<code>SKS Tidak Ditemukan</code>",
        ];
    }
}
// CARI ID Prodi =====================================================================
function cariidprodi($kode_program_studi) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    // $filter = "kode_program_studi='$kode_program_studi'";
    $filter = "id_prodi='$kode_program_studi'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetProdi', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_matkul = $ws_result[1]["data"][0];
        return [
            'id_prodi' => $data_matkul['id_prodi'],
            'kode_program_studii' => $data_matkul['kode_program_studi'],
            'nama_program_studi' => $data_matkul['nama_program_studi'],
            'id_jenjang_pendidikan' => $data_matkul['id_jenjang_pendidikan'],
            'nama_jenjang_pendidikan' => $data_matkul['nama_jenjang_pendidikan'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_prodi' => "<code>ID Program Studi Tidak Ditemukan</code>",
            'kode_program_studii' => "<code>Kode Program Studi Tidak Ditemukan</code>",
            'nama_program_studi' => "<code>Nama Program Studi Tidak Ditemukan</code>",
            'id_jenjang_pendidikan' => "<code>ID Jenjang Pendidikan Tidak Ditemukan</code>",
            'nama_jenjang_pendidikan' => "<code>Nama Jenjang Pendidikan Tidak Ditemukan</code>",
        ];
    }
}
// CARI ID Dosen =====================================================================
function cariiddosen($nidn) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "nidn='$nidn'";
    // $filter = "nama_dosen='$nidn'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListDosen', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_dosen = $ws_result[1]["data"][0];
        return [
            'id_dosen' => $data_dosen['id_dosen'],
            'nama_dosen' => $data_dosen['nama_dosen'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_dosen' => "<code>ID Dosen Tidak Ditemukan</code>",
            'nama_dosen' => "<code>Nama Dosen Tidak Ditemukan</code>",
        ];
    }
}
// CARI ID Registrasi Dosen ==========================================================
function cariidregdosen($nama_dosen) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "nama_dosen='$nama_dosen'";
    // $filter = "nidn='$nama_dosen'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListPenugasanDosen', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_dosen = $ws_result[1]["data"][0];
        return [
            'id_registrasi_dosen' => $data_dosen['id_registrasi_dosen'],
            'nama_dosen' => $data_dosen['nama_dosen'],
            'nama_program_studi' => $data_dosen['nama_program_studi'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_registrasi_dosen' => "<code>ID Registrasi Dosen Tidak Ditemukan</code>",
            'nama_dosen' => "<code>Nama Dosen Tidak Ditemukan</code>",
            'nama_program_studi' => "<code>Nama Program Studi Tidak Ditemukan</code>",
        ];
    }
}
// CARI ID Mahasiswa =================================================================
function cariidmahasiswa($nim) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "nim='$nim'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListMahasiswa', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_mahasiswa = $ws_result[1]["data"][0];
        return [
            'id_mahasiswa' => $data_mahasiswa['id_mahasiswa'],
            'id_registrasi_mahasiswa' => $data_mahasiswa['id_registrasi_mahasiswa'],
            'nama_mahasiswa' => $data_mahasiswa['nama_mahasiswa'],
            'nama_program_studi' => $data_mahasiswa['nama_program_studi'],
            'nama_status_mahasiswa' => $data_mahasiswa['nama_status_mahasiswa'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_mahasiswa' => "<code>ID Mahasiswa Tidak Ditemukan</code>",
            'id_registrasi_mahasiswa' => "<code>ID Reg Mahasiswa Tidak Ditemukan</code>",
            'nama_mahasiswa' => "<code>Nama Mahasiswa Tidak Ditemukan</code>",
            'nama_program_studi' => "<code>Program Studi Mahasiswa Tidak Ditemukan</code>",
            'nama_status_mahasiswa' => "<code>Status Mahasiswa Tidak Ditemukan</code>",
        ];
    }
}
// CARI ID Mahasiswa =================================================================
function cariidmahasiswa_kampuscabang($nim, $keyword) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "nim='$nim' and nama_program_studi LIKE '%$keyword%'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListMahasiswa', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_mahasiswa = $ws_result[1]["data"][0];
        return [
            'id_registrasi_mahasiswa' => $data_mahasiswa['id_registrasi_mahasiswa'],
            'nama_mahasiswa' => $data_mahasiswa['nama_mahasiswa'],
            'nama_program_studi' => $data_mahasiswa['nama_program_studi'],
            'nama_status_mahasiswa' => $data_mahasiswa['nama_status_mahasiswa'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_registrasi_mahasiswa' => "<code>ID Mahasiswa Tidak Ditemukan</code>",
            'nama_mahasiswa' => "<code>Nama Mahasiswa Tidak Ditemukan</code>",
            'nama_program_studi' => "<code>Program Studi Mahasiswa Tidak Ditemukan</code>",
            'nama_status_mahasiswa' => "<code>Status Mahasiswa Tidak Ditemukan</code>",
        ];
    }
}
// CARI Data Kelas Kuliah =====================================================================
function caridatakelaskuliah($id_kelas_kuliah) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "id_kelas_kuliah='$id_kelas_kuliah'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListKelasKuliah', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_kelas_kuliah = $ws_result[1]["data"][0];
        return [
            'id_dosen' => $data_kelas_kuliah['id_dosen'],
            'nama_dosen' => $data_kelas_kuliah['nama_dosen'],
            'nama_mata_kuliah' => $data_kelas_kuliah['nama_mata_kuliah'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_dosen' => "<code>ID Dosen Tidak Ditemukan</code>",
            'nama_dosen' => "<code>Nama Dosen Tidak Ditemukan</code>",
            'nama_mata_kuliah' => "<code>Nama Mata Kuliah Tidak Ditemukan</code>",
        ];
    }
}
// CARI Data Kelas Kuliah 2 =====================================================================
function caridatakelaskuliah2($kode_mata_kuliah, $nama_kelas_kuliah) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "kode_mata_kuliah='$kode_mata_kuliah' and nama_kelas_kuliah='$nama_kelas_kuliah'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListKelasKuliah', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_kelas_kuliah = $ws_result[1]["data"][0];
        return [
            'id_kelas_kuliah' => $data_kelas_kuliah['id_kelas_kuliah'],
            'nama_mata_kuliah' => $data_kelas_kuliah['nama_mata_kuliah'],
            'sks' => $data_kelas_kuliah['sks'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_kelas_kuliah' => "<code>ID Kelas Kuliah Tidak Ditemukan</code>",
            'nama_mata_kuliah' => "<code>Nama Mata Kuliah Tidak Ditemukan</code>",
            'sks' => "<code>SKS Tidak Ditemukan</code>",
        ];
    }
}
// CARI Data Kelas Kuliah 2 Extend =====================================================================
function caridatakelaskuliah2ext($id_kelas_kuliah) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "id_kelas_kuliah='$id_kelas_kuliah'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListKelasKuliah', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $data_kelas_kuliah = $ws_result[1]["data"][0];
        return [
            'kode_mata_kuliah' => $data_kelas_kuliah['kode_mata_kuliah'],
            'nama_mata_kuliah' => $data_kelas_kuliah['nama_mata_kuliah'],
            'nama_kelas_kuliah' => $data_kelas_kuliah['nama_kelas_kuliah'],
            'sks' => $data_kelas_kuliah['sks'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'kode_mata_kuliah' => "<code>Kode Kelas Kuliah Tidak Ditemukan</code>",
            'nama_mata_kuliah' => "<code>Nama Mata Kuliah Tidak Ditemukan</code>",
            'nama_kelas_kuliah' => "<code>Nama Kelas Kuliah Tidak Ditemukan</code>",
            'sks' => "<code>SKS Tidak Ditemukan</code>",
        ];
    }
}

// CARI ID Kelas Kuliah ==============================================================
function carikelas(&$kodemk,&$kodekelas,&$semester,$id_kelas_kuliah){
    require "component/config.php"; 
    $kodemk = "kodemk";
    $kodekelas = $kodekelas;
    $semester = $semester;
    $filter = "kode_mata_kuliah='$kodemk' and nama_kelas_kuliah='$kodekelas' and id_semester ='$semester'"; //cek berdasarkan nama
    $request = $ws->prep_get('GetDetailKelasKuliah',$filter,1,0);
    $ws_result = $ws->run($request);
    // $ws->view($ws_result);
    if (isset($ws_result[1]["data"][0]["id_kelas_kuliah"])) {
        $id_kelas_kuliah = $ws_result[1]["id_kelas_kuliah"][0]["id_kelas_kuliah"];	
        } else {
            // $nama = strtoupper($nama);
            $filter = "kode_mata_kuliah='$kodemk' and nama_kelas_kuliah='$kodekelas' and id_semester ='$semester'"; //cek berdasarkan nama
            $request = $ws->prep_get('GetBiodataMahasiswa',$filter,1,0);
            $ws_result = $ws->run($request);
             $ws->view($ws_result);
            if (isset($ws_result[1]["data"][0]["id_kelas_kuliah"])) {
                $id_kelas_kuliah = $ws_result[1]["data"][0]["id_kelas_kuliah"];	
                } else {$id_kelas_kuliah = "<code>Tidak Ditemukan</code>";}
        }  
}

// CARI Data Kelas Kuliah =====================================================================
function carilistmatakuliah($kode_mata_kuliah, $nama_program_studi) {
    require "component/config.php"; // Load konfigurasi dan koneksi web service

    // Buat filter berdasarkan kode mata kuliah
    $filter = "kode_mata_kuliah='$kode_mata_kuliah' and nama_program_studi='$nama_program_studi'";

    // Siapkan request ke web service
    $request = $ws->prep_get('GetListMataKuliah', $filter, 1, 0);

    // Jalankan request
    $ws_result = $ws->run($request);

    // Periksa apakah data ditemukan
    if (isset($ws_result[1]["data"][0])) {
        $list_mata_kuliah = $ws_result[1]["data"][0];
        return [
            'id_matkul' => $list_mata_kuliah['id_matkul'],
            'sks_mata_kuliah' => $list_mata_kuliah['sks_mata_kuliah'],
        ];
    } else {
        // Kembalikan nilai default jika tidak ditemukan
        return [
            'id_matkul' => "<code>ID Matkul Tidak Ditemukan</code>",
            'sks_mata_kuliah' => "<code>SKS Matkul Tidak Ditemukan</code>",
        ];
    }
}

// PDDIKTI =======================================================
function progress(&$statprogress,&$act){
    require "component/config.php"; 
$update = "UPDATE progress SET  keterangan='$statprogress', used='yes' WHERE act='$act' ;";
mysqli_query($db, $update);
}

// PDDIKTI =======================================================
function hapus_tanda(&$string){
    $cari_huruf = array("\\","\n","\r","'",'&amp;','&copy;','&crarr;','&dArr;','&deg;','&divide;','&eacute;','&fnof;','&frasl;','&ge;','&harr;','&hArr;','&hellip;','&larr;','&lArr;','&ldquo;','&le;','&lowast;','&lsquo;','&mdash;','&nbsp;','&ndash;','&ne;','&plusmn;','&prime;','&Prime;','&quot;','&rarr;','&rArr;','&rdquo;','&reg;','&rsquo;','&sect;','&sum;','&times;','&uarr;','&uArr;','&lt;br /&gt;','&lt;','&gt;','%','&check;','&raquo;','&laquo;');
    $huruf_baru = array("","","","",'&','©','?','?','°','÷','é','ƒ','?','?','?','?','…','?','?','“','?','?','','','','-','?','±','?','?','"','?','?','','®','','§','?','×','?','?','','<','>',"","✓",">>","<<");													
    return ucwords(strtoupper(str_ireplace($cari_huruf, $huruf_baru, $string)));
}


// System =======================================================
function sys_log($activity)
{
  require "component/config.php";
  $user       = $_SESSION['username'];
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  $sql = "INSERT INTO sys_log (user, activity, ip_address, user_agent) VALUES ('$user', '$activity', '$ip_address', '$user_agent')";
  mysqli_query($db,$sql);
  // echo $sql;
}

?>
