<?php

require '../koneksi/koneksi.php';
$title_web = 'Dashboard';
include 'header.php';
if (empty($_SESSION['USER'])) {
    session_start();
}


if (!empty($_POST['nama_rental'])) {
    $nama_rental = $_POST['nama_rental'];
    $no_hp = htmlspecialchars($_POST['telp']);
    $email = htmlspecialchars($_POST['email']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_rek = htmlspecialchars($_POST['no_rek']);

    $url = "http://localhost:9000/info";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
        'nama_rental' => $nama_rental,
        'telp' => $no_hp,
        'email' => $email,
        'alamat' => $alamat,
        'no_rek' => $no_rek,
    )));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo '<script>alert("Update Data Info Website Gagal !");window.location="index.php"</script>';
    } else {
        echo '<script>alert("Update Data Info Website Berhasil !");window.location="index.php"</script>';
    }
}

$url = 'http://localhost:9000/info';
$response = file_get_contents($url);
$data = json_decode($response);
$contactApi = $data->data->contact;

if (!empty($_POST['nama_pengguna'])) {
    $data[] =  htmlspecialchars($_POST["nama_pengguna"]);
    $data[] =  htmlspecialchars($_POST["username"]);
    $data[] =  md5($_POST["password"]);
    $data[] =  $_SESSION['USER']['id_login'];
    $sql = "UPDATE login SET nama_pengguna = ?, username = ?, password = ? WHERE id_login = ? ";
    $row = $koneksi->prepare($sql);
    $row->execute($data);
    echo '<script>alert("Update Data Profil Berhasil !");window.location="index.php"</script>';
    exit;
}
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Info Website
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php
                        $sql = "SELECT * FROM infoweb WHERE id = 1";
                        $row = $koneksi->prepare($sql);
                        $row->execute();
                        $edit = $row->fetch(PDO::FETCH_OBJ);
                        ?>
                        <div class="form-group">
                            <label for="">Nama rental</label>
                            <input type="text" class="form-control" value="<?= $info_web->nama_rental; ?>" name="nama_rental" id="nama_rental" placeholder="" />
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" value="<?= $info_web->email; ?>" name="email" id="email" placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Telp</label>
                                    <input type="text" class="form-control" value="<?= $info_web->telp; ?>" name="telp" id="telp" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" placeholder=""><?= $info_web->alamat; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">No rek</label>
                            <textarea class="form-control" name="no_rek" id="no_rek" placeholder=""><?= $info_web->no_rek; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Profil Admin
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php
                        $id =  $_SESSION["USER"]["id_login"];
                        $sql = "SELECT * FROM login WHERE id_login = ?";
                        $row = $koneksi->prepare($sql);
                        $row->execute(array($id));
                        $edit_profil = $row->fetch(PDO::FETCH_OBJ);
                        ?>
                        <div class="form-group">
                            <label for="">Nama Pengguna</label>
                            <input type="text" class="form-control" value="<?= $edit_profil->nama_pengguna; ?>" name="nama_pengguna" id="nama_pengguna" placeholder="" />
                        </div>
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" required class="form-control" value="<?= $edit_profil->username; ?>" name="username" id="username" placeholder="" />
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" required class="form-control" value="" name="password" id="password" placeholder="" />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>