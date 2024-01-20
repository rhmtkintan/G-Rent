<?php

 require '../../koneksi/koneksi.php';

if($_GET['id'] == 'konfirmasi')
{
    $data2[] = $_POST['status'];
    $data2[] = $_POST['id_gadget'];
    $sql2 = "UPDATE `gadget` SET `status`= ? WHERE id_gadget= ?";
    $row2 = $koneksi->prepare($sql2);
    $row2->execute($data2);

    echo '<script>alert("Status gadget di pinjam");history.go(-1);</script>'; 
}