<?php
require '../../koneksi/koneksi.php';
$title_web = 'Tambah gadget';
include '../header.php';

if (empty($_SESSION['USER'])) {
    session_start();
}

if ($_GET['aksi'] == 'tambah') {
    try {
        $allowedImageTypes = array("image/gif", "image/jpeg", "image/png", "image/webp");
        $filepath = $_FILES['gambar']['tmp_name'];
        $fileType = mime_content_type($filepath);

        if (!in_array($fileType, $allowedImageTypes)) {
            throw new Exception("You can only upload JPG, PNG, GIF, or WebP files");
        }

        $dir = '../../assets/image/';
        $tmpName = $_FILES['gambar']['tmp_name'];
        $newFileName = round(microtime(true)) . '.' . pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
        $targetPath = $dir . $newFileName;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new Exception("Failed to upload the image");
        }

        $data = [
            $_POST['merk'],
            $_POST['harga'],
            $_POST['deskripsi'],
            $_POST['status'],
            $newFileName
        ];

        $sql = "INSERT INTO `gadget`( `merk`, `harga`, `deskripsi`, `status`, `gambar`) 
                VALUES (?,?,?,?,?)";

        $row = $koneksi->prepare($sql);
        $row->execute($data);

        echo '<script>alert("Success");window.location="gadget.php"</script>';
    } catch (Exception $e) {
        echo '<script>alert("' . $e->getMessage() . '");window.location="tambah.php"</script>';
    }
}

if ($_GET['aksi'] == 'edit') {
    try {
        $id = $_GET['id'];
        $gambar = $_POST['gambar_cek'];
        $data = [
            $_POST['merk'],
            $_POST['harga'],
            $_POST['deskripsi'],
            $_POST['status']
        ];

        if (isset($_FILES['gambar']) && $_FILES['gambar']["size"] > 0) {
            $allowedImageTypes = array("image/gif", "image/jpeg", "image/png", "image/webp");
            $filepath = $_FILES['gambar']['tmp_name'];
            $fileType = mime_content_type($filepath);

            if (!in_array($fileType, $allowedImageTypes)) {
                throw new Exception("You can only upload JPG, PNG, GIF, or WebP files");
            }

            $dir = '../../assets/image/';
            $tmpName = $_FILES['gambar']['tmp_name'];
            $newFileName = round(microtime(true)) . '.' . pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
            $targetPath = $dir . $newFileName;

            if (!move_uploaded_file($tmpName, $targetPath)) {
                throw new Exception("Failed to upload the image");
            }

            if (file_exists('../../assets/image/' . $gambar)) {
                unlink('../../assets/image/' . $gambar);
            }

            $data[] = $newFileName;
        } else {
            // Add checks to ensure $_POST['gambar_cek'] is set and has a valid value
            if (isset($_POST['gambar_cek']) && !empty($_POST['gambar_cek'])) {
                $data[] = $_POST['gambar_cek'];
            } else {
                throw new Exception("Invalid value for gambar_cek");
            }
        }

        $data[] = $id;

        $sql = "UPDATE gadget SET merk=?, harga=?, deskripsi=?, status=?, gambar=?
            WHERE id_gadget=?";

        $row = $koneksi->prepare($sql);
        $row->execute($data);

        echo '<script>alert("Success");window.location="gadget.php"</script>';
    } catch (Exception $e) {
        echo '<script>alert("' . $e->getMessage() . '");history.go(-1)</script>';
    }
}

if ($_GET['aksi'] == 'hapus') {
    try {
        $id = $_GET['id'];
        $gambar = $_GET['gambar'];

        unlink('../../assets/image/' . $gambar);

        $sql = "DELETE FROM gadget WHERE id_gadget = ?";
        $row = $koneksi->prepare($sql);
        $row->execute([$id]);

        echo '<script>alert("Success delete");window.location="gadget.php"</script>';
    } catch (Exception $e) {
        echo '<script>alert("' . $e->getMessage() . '");window.location="gadget.php"</script>';
    }
}
