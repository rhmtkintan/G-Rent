<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';

if (empty($_SESSION['USER'])) {
    echo '<script>alert("Harap login !");window.location="index.php"</script>';
}

$kode_booking = $_GET['id'];
$hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();

// Extract id from the URL
$id_from_url = $_GET['id'];

// Fetch data from the API using the id
$url = 'http://localhost:9000/bookings/' . $id_from_url;
$response = file_get_contents($url);
$data = json_decode($response);
$bookingFromApi = $data->data->booking;



$id = $bookingFromApi->id_gadget;
$data = $bookingFromApi->kode_booking;
$isi = $koneksi->query("SELECT * FROM gadget WHERE id_gadget = '$id'")->fetch();

$unik  = random_int(100, 999);
var_dump($response);
?>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4">

            <div class="card">
                <div class="card-body text-center">
                    <h5>Pembayaran Dapat Melalui :</h5>
                    <hr />
                    <p> <?= $info_web->no_rek; ?> </p>
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-body" style="background:#ddd">
                    <h5 class="card-title"><?php echo $isi['merk']; ?></h5>
                </div>
                <ul class="list-group list-group-flush">

                    <?php if ($isi['status'] == 'Tersedia') { ?>

                        <li class="list-group-item bg-primary text-white">
                            <i class="fa fa-check"></i> Available
                        </li>

                    <?php } else { ?>

                        <li class="list-group-item bg-danger text-white">
                            <i class="fa fa-close"></i> Not Available
                        </li>

                    <?php } ?>
                    <li class="list-group-item bg-dark text-white">
                        <i class="fa fa-money"></i> Rp. <?php echo number_format($isi['harga']); ?>/ day
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Kode Booking </td>
                            <td> :</td>
                            <td><?php echo $bookingFromApi->kode_booking; ?></td>
                        </tr>
                        <tr>
                            <td>KTP </td>
                            <td> :</td>
                            <td><?php echo $bookingFromApi->ktp; ?></td>
                        </tr>
                        <tr>
                            <td>Nama </td>
                            <td> :</td>
                            <td><?php echo $bookingFromApi->nama; ?></td>
                        </tr>
                        <tr>
                            <td>telepon </td>
                            <td> :</td>
                            <td><?php echo $bookingFromApi->no_tlp; ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Sewa </td>
                            <td> :</td>
                            <td><?php echo $bookingFromApi->tanggal;  ?></td>
                        </tr>
                        <tr>
                            <td>Lama Sewa </td>
                            <td> :</td>
                            <td><?php echo $bookingFromApi->lama_sewa; ?> hari</td>
                        </tr>
                        <tr>
                            <td>Total Harga </td>
                            <td> :</td>
                            <td>Rp. <?php echo number_format($bookingFromApi->total_harga); ?></td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td> :</td>
                            <td><?php echo  $bookingFromApi->konfirmasi_pembayaran; ?></td>
                        </tr>
                    </table>

                    <?php if ($bookingFromApi->konfirmasi_pembayaran == 'Belum Bayar') { ?>
                        <a href="konfirmasi.php?id=<?php echo $kode_booking; ?>" class="btn btn-primary float-right">Konfirmasi Pembayaran</a>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>

<?php include 'footer.php'; ?>