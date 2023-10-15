



<?php 
require 'koneksi.php';
session_start();

if (!empty($_GET['aksi'] == "login")) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']); // Hash the input password using SHA-256
    $sql = "SELECT * FROM user WHERE username = ? AND password = ?";
    $row = $koneksi->prepare($sql);
    $row->execute(array($username, $password));
    $count = $row->rowCount();

    if ($count > 0) {
        $result = $row->fetch();
        $_SESSION['ADMIN'] = $result;
        // status yang diberikan 
        echo "<script>window.location='index.php';</script>";
    } else {
        echo "<script>window.location='login.php?get=failed';</script>";
    }
}
if(!empty($_GET['aksi'] == "tambah")) {
        $data[] =  $_POST["nim"];
        $data[] =  $_POST["nama"];
        $data[] =  $_POST["fakultas"];
        $data[] =  $_POST["prodi"];

        $sql = "INSERT INTO mahasiswa (nim, nama, fakultas, prodi ) VALUES ( ?,?,?,?)";
        $row = $koneksi->prepare($sql);
        $row->execute($data);

        echo "<script>window.location='index.php';</script>";

    }
    if(!empty($_GET['aksi'] == "edit")) {
        $nim =  (int)$_GET["nim"];

        $data[] =  $_POST["nama"];
        $data[] =  $_POST["fakultas"];
        $data[] =  $_POST["prodi"];
      

        $data[] = $nim;
        $sql = "UPDATE mahasiswa SET  nama = ?, fakultas = ?, prodi = ?  WHERE nim = ? ";
        $row = $koneksi->prepare($sql);
        $row->execute($data);

        echo "<script>window.location='index.php';</script>";

    }

    if(!empty($_GET['aksi'] == "hapus")) {

        $nim =  (int)$_POST["nim"]; // should be integer (id)
        $sql = "SELECT * FROM mahasiswa WHERE nim = ?";
        $row = $koneksi->prepare($sql);
        $row->execute(array($nim));
        $cek = $row->rowCount();
        if($cek > 0)
        {
            $sql_delete = "DELETE FROM mahasiswa WHERE nim = ?";
            $row_delete = $koneksi->prepare($sql_delete);
            $row_delete->execute(array($nim));
            echo "<script>window.location='index.php';</script>";
        }else{
            echo "<script>window.location='index.php';</script>";
        }
        
    }
    if (!empty($_GET['aksi'] == "edit_user")) {
        $id_user = (int) $_GET["id"];
        $username = $_POST["username"];
        $password_lama = hash('sha256', $_POST["password_lama"]);
        $password_baru = hash('sha256', $_POST["password"]);
    
        $sql_password_lama = "SELECT * FROM user WHERE id_user = ? AND password = ?";
        $row = $koneksi->prepare($sql_password_lama);
        $row->execute(array($id_user, $password_lama));
        $jumlah = $row->rowCount();
    
        if ($jumlah > 0) {
            $sql = "UPDATE user SET username = ?, password = ? WHERE id_user = ?";
            $row = $koneksi->prepare($sql);
            $row->execute([$username, $password_baru, $id_user]);
    
            echo "<script>window.location='index.php';</script>";
    
        } else {
    
            echo "<script>window.location='edit-user.php';</script>";
        
        }
    }
    
    if (!empty($_GET['aksi'] == "logout")) {
        session_destroy();
    
        echo "<script>window.location='login.php';</script>";
    }
?>