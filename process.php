<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process</title>
    <meta http-equiv="refresh" content="0.2; url=/">

    <?php 

        // Deklarasi data path
        define("DATA_PATH", __DIR__."/data.txt");

        // Menambahkan fungsi yang diperlukan
        function cekAbsen($target_absen){
            $data_read = fopen(DATA_PATH, "r");
            while(!feof($data_read)){
                $temp_data = fgets($data_read);
                if($temp_data == "") return false;
                
                $siswa = explode(";",$temp_data);
                if($siswa[0] == $target_absen) return true;
            }

        }
    ?>
</head>

<body>
    <?php 
        if($_POST["action-process"] == "CREATE-DATA"){

            // Sebelum ditambahkan, data-absen harus di cek dulu agar tidak terjadi perulangan data

            if(cekAbsen($_POST["absen"])){
                echo "<script>alert('Absen sudah terdaftar');</script>";
            }else{
                $data_create = fopen(DATA_PATH,"a");
    
                // Ubah nilai yang tadi berbentuk string menjadi berbentuk float
                $_POST["nilai"] = (float)$_POST["nilai"];
    
                fwrite($data_create,$_POST["absen"].";".$_POST["nama"].";".$_POST["nilai"]."\n");
                fclose($data_create);
            }
            

        }
        else if($_POST["action-process"] == "UPDATE-DATA"){
            // Cek apakah data absen yang ingin di ubah ada atau tidak
            if(cekAbsen($_POST["target-absen"])){
                // Cek apakah absen baru sudah ada atau belum, agar tidak terjadi perulangan
                if(cekAbsen($_POST["absen-baru"])){
                    echo "<script>alert('Absen sudah terdaftar');</script>";
                }else{
                    // Jika bisa, maka buat file temporary untuk menyimpan data copy sementara dengan data yang sudah di ubah. Lalu copy seluruh data dari temporary file dan timpa ke file data.txt.

                    $temp_write = fopen("temp.txt", "w");
                    $data_read = fopen(DATA_PATH, "r");

                    while(!feof($data_read)){
                        $temp_data = fgets($data_read);
                        if($temp_data == "") break;
                        
                        $siswa = explode(";",$temp_data);
                        if($siswa[0] == $_POST["target-absen"]){

                            if($_POST["absen-baru"] == ""){
                                $_POST["absen-baru"] = $siswa[0];
                            }
                            if($_POST["nama-baru"] == ""){
                                $_POST["nama-baru"] = $siswa[1];
                            }
                            if($_POST["nilai-baru"] == ""){
                                $_POST["nilai-baru"] = $siswa[2];
                            }

                            fwrite($temp_write,$_POST["absen-baru"].";".$_POST["nama-baru"].";".(float)$_POST["nilai-baru"]."\n");
                            // Kenapa di ubah ke float? Karena di bagian nilai-baru jika diambil dari data sebelumnya maka akan ada karakter "\n" yang tertinggal. Untuk menghilangkannya maka diubah dulu menjadi float
                        }else{
                            fwrite($temp_write,$temp_data);
                        }
                    }

                    fclose($data_read);
                    fclose($temp_write);
                    
                    $temp_read = fopen("temp.txt", "r");
                    $data_rewrite = fopen(DATA_PATH,"w");

                    while(!feof($temp_read)){
                        $temp_data = fgets($temp_read);
                        if($temp_data == "") break;
                        
                        fwrite($data_rewrite,$temp_data);
                    }

                    fclose($temp_read);
                    fclose($data_rewrite);

                    // Hapus file temporary
                    unlink("temp.txt");
                    // sementara jgn dipake dulu. ada error gk jelas soalnya


                }

            }else{
                echo "<script>alert('Absen tidak terdaftar');</script>";
            }
        }else if($_POST["action-process"] == "DELETE-DATA"){
            // Untuk melakukan delete data hampir sama seperti melakukan update data, tapi bedanya kita hanya perlu menghapus data target. Cara menghapusnya adalah dengan mengcopy data.txt ke temp.txt lalu dalam perulangan kita skip data target yang ingin di hapus.

            // Cek apakah data absen yang ingin di hapus ada atau tidak
            if(cekAbsen($_POST["target-absen"])){
                // Pindahkan data dari data.txt ke temp.txt
                $temp_write = fopen("temp.txt","w");
                $data_read = fopen(DATA_PATH,"r");

                while(!feof($data_read)){
                    $temp_data = fgets($data_read);
                    if($temp_data == "") break;

                    $temp_absen = explode(";",$temp_data)[0];

                    // Jika absen tidak sama dengan target absen maka copy data
                    if($temp_absen != $_POST["target-absen"]){
                        fwrite($temp_write,$temp_data);
                    }
                }

                fclose($data_read);
                fclose($temp_write);

                // Lalu pindahkan data dari temp.txt ke data.txt yang baru
                $temp_read = fopen("temp.txt","r");
                $data_rewrite = fopen(DATA_PATH,"w");

                while(!feof($temp_read)){
                    $temp_data = fgets($temp_read);
                    if($temp_data == "") break;
                    
                    fwrite($data_rewrite,$temp_data);
                }

                fclose($temp_read);
                fclose($data_rewrite);

                unlink("temp.txt");

            }else{
                echo "<script>alert('Absen tidak terdaftar');</script>";
            }
                
        }
    ?>
</body>
</html>