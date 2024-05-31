<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project CRUD PHP</title>

    <!-- Line dibawah untuk menampilkan css -->
    <!-- <link rel="stylesheet" href="style.css"> -->

    <!-- Jika line di atas kadang error, pakai line ini -->
    <link href="style.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <!-- Gk tau kok bisa gitu cokkk -->
</head>
<body>
    <!-- READ DATA -->
    <?php 
        $DATA_PATH = __DIR__."/data.txt";

        $data_read = fopen($DATA_PATH, "r");

        $jumlah_siswa = 0;
        $siswa = [];
        
        while(!feof($data_read)){
            $temp_data = fgets($data_read);
            if($temp_data == "") break;
            
            $siswa[$jumlah_siswa] = explode(";",$temp_data);

            // siswa[i][0] = absen
            // siswa[i][1] = nama
            // siswa[i][2] = nilai
            $jumlah_siswa++;
        }

        fclose($data_read);

        sort($siswa);

    ?>
    <header>
        <h1>Project CRUD PHP</h1><br>
        <h3>
            Jumlah Siswa : <?php echo $jumlah_siswa ?>
        </h3>
    </header>

    <!-- Container untuk menampilkan data dari file .txt -->
    <br><br>
    <div class="READ-FIELD">
        <div class="data-container">
            <div class="data-absen"><h4>No.</h4></div>
            <div class="data-nama"><h4>Nama</h4></div>
            <div class="data-nilai"><h4>Nilai</h4></div>
        </div>
        <br>
        <?php 
            for($i = 0; $i < $jumlah_siswa; $i++){
                echo 
                "<div class='data-container'>".
                "<div class='data-absen'><p>".$siswa[$i][0]."</p></div>".
                "<div class='data-nama'><p>".$siswa[$i][1]."</p></div>".
                "<div class='data-nilai'><p>".$siswa[$i][2]."</p></div>".
                "</div>";
            }
        ?>
        
    </div>
    <br><br>

    <div class="form-container">
        <div class="form-button-container">
            <button class="form-link active" onclick="openForm(event,'CREATE-FORM')">CREATE</button>
            <button class="form-link" onclick="openForm(event,'UPDATE-FORM')">UPDATE</button>
            <button class="form-link" onclick="openForm(event,'DELETE-FORM')">DELETE</button>
        </div>

        <div id="CREATE-FORM" class="form-wrapper active">
            <form action="process.php" method="POST">
                <input type="text" name="absen" placeholder="No. Absen" required
                pattern="^[1-9][0-9]*$">
                <br>
                <input type="text" name="nama" placeholder="Nama" required
                pattern="^[a-zA-Z\s]{1,20}$"><br>
                <input type="text" name="nilai" placeholder="Nilai" required
                pattern="^[0-9]*$">
                
                <!-- Menambahkan tampilan error setelah validasi agar data (nomor absen) tidak berulang -->
                <p class="error-msg">
                    
                </p>

                <button class="submit-button" type="submit" name="action-process" value="CREATE-DATA">Buat Data</button>
            </form>
        </div>

        <div id="UPDATE-FORM" class="form-wrapper">
            <form action="process.php" method="POST">
                <!-- Cari target absen dulu -->
                <input type="text" name="target-absen" placeholder="Target Absen*" required pattern="^[1-9][0-9]*$">
                
                <p style="color: black; margin-bottom: 0.5rem; font-size : 12px">Kosongkan bagian yang tidak ingin diubah</p>

                <input type="text" name="absen-baru" placeholder="Absen Baru"
                pattern="^[1-9][0-9]*$">
                <input type="text" name="nama-baru" placeholder="Nama Baru"
                pattern="^[a-zA-Z\s]{1,20}$">
                <input type="text" name="nilai-baru" placeholder="Nilai Baru"
                pattern="^[0-9]*$">

                <button class="submit-button"  type="submit" name="action-process" value="UPDATE-DATA">Ubah Data</button>
            </form>
        </div>

        <div id="DELETE-FORM" class="form-wrapper">
            <form action="process.php" method="POST">
                
                <input type="text" name="target-absen" placeholder="Target Absen*" required pattern="^[1-9][0-9]*$">

                <button class="submit-button"  type="submit" name="action-process" value="DELETE-DATA">Hapus Data</button>
            </form>
        </div>

        
    </div>
    
    <script src="script.js"></script>
</body>
</html>