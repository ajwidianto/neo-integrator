<?php
          $show = $_POST['show'];
          $namatable = "insertmahasiswa";
          $act="mhsimport";
          $berhasil=$belum=$sudahada=$gagal=0;
          $query = "select * from progress where act='InsertPerkuliahanMahasiswa'";
          $log = mysqli_query($db, $query);
          while($xlog = mysqli_fetch_array($log)){
            echo '<ul class="pagination pagination-primary  justify-content-center">                      
            <div class="buttons justify-content-center">';
            echo $xlog['keterangan']." - - Last time :".$xlog['update'];
            echo '</div></ul>';
          }

          $headertable = "<div class='table-responsive'><table class='table table-striped ' border='1'><tr>
          <th>No</th><th>Nama Mahasiswa</th><th>NIM</th><th>Program Studi</th>
          <th>Tempat Tanggal Lahir</th><th>NIK</th><th>Program Studi</th>
          <th>Error</th><th>Description</th></tr>";

          $no = 1;
          if($show == 'yes'){
            $tampil = '<a href="?module=inject&act='.$act.'&show=no"><button type="button" class="btn btn-danger">
            SEMBUNYIKAN DATA <span class="badge bg-transparent"></span></button></a>';
          $query = "select * from ".$namatable;
          $hasil = mysqli_query($db, $query);
          if(mysqli_num_rows($hasil) > 0 ){
                    echo $headertable;
                    while($x = mysqli_fetch_array($hasil)){
                    if ($x['err_no'] == '0' OR $x['err_no'] == '200' ){$berhasil++;}elseif(is_null($x['err_no'])){$belum++;}else{$gagal++;}
                    echo "<tr><td><pre>" . $no++;
                    echo "</td><td><pre>" . $x['nama_mahasiswa']; echo "</td><td><pre>" . $x['nim'];
                    echo "</td><td><pre>" . $x['nama_prodi'];     echo "</td><td><pre>" . $x['Tempat_Lahir'] .", ". $x['Tanggal_Lahir'];
                    echo "</td><td><pre>" . $x['NIK'];echo "</td><td><pre>" . $x['ibu'];
                    echo "</td><td><pre>" . $x['err_no'].", ".$x['err2_no'];echo "</td><td><code>" . $x['err_desc'].", ".$x['err2_desc'] . "</code></td></tr></div>";
                    }
          } else {
                    echo "Tidak Ada Data Yang Akan Di Sync";
          }
          $total=$berhasil+$belum+$gagal; 
          }else if($show == 'berhasil'){
                    $tampil = '<a href="?module=inject&act='.$act.'&show=no"><button type="button" class="btn btn-danger">
                    SEMBUNYIKAN DATA <span class="badge bg-transparent"></span></button></a>';
                    $query = "select * from ".$namatable." where err_no='0' or err_no='200'";
                    $hasil = mysqli_query($db, $query);
                    if(mysqli_num_rows($hasil) > 0 ){
                    echo $headertable;
                    while($x = mysqli_fetch_array($hasil)){
                    // $jum
                    echo "<tr><td>" . $no++;
                    echo "</td><td><pre>" . $x['nama_mahasiswa']; echo "</td><td><pre>" . $x['nim'];
                    echo "</td><td><pre>" . $x['nama_prodi'];     echo "</td><td><pre>" . $x['Tempat_Lahir'] .", ". $x['Tanggal_Lahir'];
                    echo "</td><td><pre>" . $x['NIK'];echo "</td><td><pre>" . $x['ibu'];
                    echo "</td><td><pre>" . $x['err_no'].", ".$x['err2_no'];echo "</td><td><code>" . $x['err2_desc'].", ".$x['err_desc'] . "</code></td></tr></div>";
                    }
                    } else {
                    echo "Tidak Ada Data yang berhasil";
                    }

                          $query = "SELECT count(id) AS total, sum(err_no IS NULL) AS belum, SUM(err_no='0') AS berhasil,
                          SUM(err_no IS NOT NULL AND err_no!='0' AND err_no!='200') AS gagal FROM ".$namatable.";";
                          $hitung = mysqli_query($db, $query);
                          if(mysqli_num_rows($hitung) > 0 ){
                          $data = mysqli_num_rows($hitung);
                          while($hit = mysqli_fetch_array($hitung)){
                          $total=$hit['total'];
                          $belum=$hit['belum'];
                          $berhasil=$hit['berhasil'];
                          $gagal=$hit['gagal'];
                          }}

          }
          else if($show == 'gagal'){

                                    $tampil = '<a href="?module=inject&act='.$act.'&show=no"><button type="button" class="btn btn-danger">
                                    SEMBUNYIKAN DATA <span class="badge bg-transparent"></span></button></a>';
                                    $query = "select * from ".$namatable." where err_no!='0' and err_no!='200' and err_no is not null";
                                    $hasil = mysqli_query($db, $query);
                                    if(mysqli_num_rows($hasil) > 0 ){
                                    echo $headertable;
                                    while($x = mysqli_fetch_array($hasil)){
                                    // $jum
                                    echo "<tr><td>" . $no++;
                                    echo "</td><td><pre>" . $x['nama_mahasiswa']; echo "</td><td><pre>" . $x['nim'];
                                    echo "</td><td><pre>" . $x['nama_prodi'];     echo "</td><td><pre>" . $x['Tempat_Lahir'] .", ". $x['Tanggal_Lahir'];
                                    echo "</td><td><pre>" . $x['NIK'];echo "</td><td><pre>" . $x['ibu'];
                                    echo "</td><td><pre>" . $x['err_no'].", ".$x['err2_no'];echo "</td><td><code>" . $x['err2_desc'].", ".$x['err_desc'] . "</code></td></tr></div>";
                                    }
                                    } else {
                                    echo "Tidak Ada Data gagal";
                                    }

                                    $query = "SELECT count(id) AS total, sum(err_no IS NULL) AS belum, SUM(err_no='0') AS berhasil,
                                    SUM(err_no IS NOT NULL AND err_no!='0' AND err_no!='200') AS gagal FROM ".$namatable.";";
                                    $hitung = mysqli_query($db, $query);
                                    if(mysqli_num_rows($hitung) > 0 ){
                                    $data = mysqli_num_rows($hitung);
                                    while($hit = mysqli_fetch_array($hitung)){
                                    $total=$hit['total'];
                                    $belum=$hit['belum'];
                                    $berhasil=$hit['berhasil'];
                                    $gagal=$hit['gagal'];
                                    }}

          }
          else if($show == 'belum'){

            $tampil = '<a href="?module=inject&act='.$act.'&show=no"><button type="button" class="btn btn-danger">
            SEMBUNYIKAN DATA <span class="badge bg-transparent"></span></button></a>';
            $query = "select * from ".$namatable." where err_no is null";
            $hasil = mysqli_query($db, $query);
            if(mysqli_num_rows($hasil) > 0 ){
            echo $headertable;
            while($x = mysqli_fetch_array($hasil)){
            // $jum
            echo "<tr><td>" . $no++;
            echo "</td><td><pre>" . $x['nama_mahasiswa']; echo "</td><td><pre>" . $x['nim'];
            echo "</td><td><pre>" . $x['nama_prodi'];     echo "</td><td><pre>" . $x['Tempat_Lahir'] .", ". $x['Tanggal_Lahir'];
            echo "</td><td><pre>" . $x['NIK'];echo "</td><td><pre>" . $x['ibu'];
            echo "</td><td><pre>" . $x['err_no'].", ".$x['err2_no'];echo "</td><td><code>" . $x['err_desc'].", ".$x['err2_desc'] . "</code></td></tr></div>";
            }
            } else {
            echo "Tidak Ada yang belum di SYNC";
            }

            $query = "SELECT count(id) AS total, sum(err_no IS NULL) AS belum, SUM(err_no='0') AS berhasil,
            SUM(err_no IS NOT NULL AND err_no!='0' AND err_no!='200') AS gagal FROM ".$namatable.";";
            $hitung = mysqli_query($db, $query);
            if(mysqli_num_rows($hitung) > 0 ){
            $data = mysqli_num_rows($hitung);
            while($hit = mysqli_fetch_array($hitung)){
            $total=$hit['total'];
            $belum=$hit['belum'];
            $berhasil=$hit['berhasil'];
            $gagal=$hit['gagal'];
            }}

}
          else { //HANYA MENAMPILKAN PROSES, BUKAN DATA 
            $tampil = '<a href="?module=inject&act='.$act.'"><button type="button" class="btn btn-primary">
            Tampilkan DATA <span class="badge bg-transparent"></span></button></a>';
            $query = "SELECT count(id) AS total, sum(err_no IS NULL) AS belum, SUM(err_no='0' or err_no='200') AS berhasil,
            SUM(err_no IS NOT NULL AND err_no!='0' AND err_no!='200') AS gagal FROM ".$namatable.";";
            $hitung = mysqli_query($db, $query);
            if(mysqli_num_rows($hitung) > 0 ){
              $data = mysqli_num_rows($hitung);
            while($hit = mysqli_fetch_array($hitung)){
              $total=$hit['total'];
              $belum=$hit['belum'];
              $berhasil=$hit['berhasil'];
              $gagal=$hit['gagal'];
            }}
          }


          echo '<div class="card-body">
                        <div class="buttons">
                            <a href="?module=inject&act='.$act.'">
                            <button type="button" class="btn btn-primary">
                                Jumlah Data <span class="badge bg-transparent">'.$total.'</span>
                            </button></a>
                            <a href="?module=inject&act='.$act.'&show=belum">
                            <button type="button" class="btn btn-primary">
                                Belum Sync <span class="badge bg-transparent">'.$belum.'</span>
                            </button></a>
                            <a href="?module=inject&act='.$act.'&show=berhasil">
                            <button type="button" class="btn btn-success">
                                Berhasil <span class="badge bg-transparent">'.$berhasil.'</span>
                            </button></a>
                            
                            <button type="button" class="btn btn-success">
                                Sudah Ada <span class="badge bg-transparent">'.$sudahada.'</span>
                            </button>
                            <a href="?module=inject&act='.$act.'&show=gagal">
                            <button type="button" class="btn btn-danger">
                                Gagal Insert <span class="badge bg-transparent">'.$gagal.'</span>
                            </button></a>
                            '.$tampil.'
                            
                            </div>
                            </div>';
                            ?>