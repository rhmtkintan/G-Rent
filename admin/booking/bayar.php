<?php
require '../../koneksi/koneksi.php';
$title_web = 'Konfirmasi';
include '../header.php';
session_start();

if (empty($_SESSION['USER'])) {
    echo '<script>alert("login dulu");window.location="index.php"</script>';
}

$kode_booking = $_GET['id'];

// Fetch data from the API
$urlBooking = 'http://localhost:9000/bookings/' . $kode_booking;
$responseBooking = file_get_contents($urlBooking);
$dataBooking = json_decode($responseBooking);
$bookingFromApi = $dataBooking->data->booking;

// Check if the booking exists
if (!$bookingFromApi) {
    echo '<script>alert("Booking tidak ditemukan");window.location="index.php"</script>';
    exit;
}
$id_booking = $bookingFromApi->id_booking;
$kode_booking = $bookingFromApi->kode_booking;



if (!empty($_POST['id_booking'])) {
    $id = $_POST['id_booking'];
    $status = $_POST['status'];

    $url = "http://localhost:9000/bookings/$id";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
        'konfirmasi_pembayaran' => $status,
    )));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
}
// Fetch payment data
$query = "SELECT * FROM pembayaran WHERE id_booking = '$id_booking'";
$result = $koneksi->query($query);


$hsl = $result->fetch();
$c = $result->rowCount();

$hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();
$id = $hasil['id_gadget'];
$isi = $koneksi->query("SELECT * FROM gadget WHERE id_gadget = '$id'")->fetch();


?>

<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h5> Detail Pembayaran</h5>
                </div>
                <div class="card-body">
                    <?php if ($c > 0) { ?>
                        <table class="table">
                            <tr>
                                <td>No Rekening</td>
                                <td> :</td>
                                <td><?= $hsl['no_rekening']; ?></td>
                            </tr>
                            <tr>
                                <td>Atas Nama </td>
                                <td> :</td>
                                <td><?= $hsl['nama_rekening']; ?></td>
                            </tr>
                            <tr>
                                <td>Nominal </td>
                                <td> :</td>
                                <td>Rp. <?= number_format($hsl['nominal']); ?></td>
                            </tr>
                            <tr>
                                <td>Tgl Transfer</td>
                                <td> :</td>
                                <td><?= $hsl['tanggal']; ?></td>
                            </tr>
                        </table>
                    <?php } else { ?>
                        <h4>Belum di bayar</h4>
                    <?php } ?>
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><?= $bookingFromApi->merk; ?></h5>
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
                        <i class="fa fa-money"></i> Rp. <?= number_format($bookingFromApi->total_harga); ?>/ day
                    </li>
                </ul>
                <div class="card-footer">
                    <a href="<?= $url; ?>admin/peminjaman/peminjaman.php?id=<?= $bookingFromApi->kode_booking; ?>" class="btn btn-success btn-md">Ubah Status Peminjaman</a>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">
                    <h5> Detail booking</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <table class="table">
                            <tr>
                                <td>Kode Booking </td>
                                <td> :</td>
                                <td><?= $bookingFromApi->kode_booking; ?></td>
                            </tr>
                            <tr>
                                <td>KTP </td>
                                <td> :</td>
                                <td><?= $bookingFromApi->ktp; ?></td>
                            </tr>
                            <tr>
                                <td>Nama </td>
                                <td> :</td>
                                <td><?= $bookingFromApi->nama; ?></td>
                            </tr>
                            <tr>
                                <td>telepon </td>
                                <td> :</td>
                                <td><?= $bookingFromApi->no_tlp; ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Sewa </td>
                                <td> :</td>
                                <td><?= $bookingFromApi->tanggal;  ?></td>
                            </tr>
                            <tr>
                                <td>Lama Sewa </td>
                                <td> :</td>
                                <td><?= $bookingFromApi->lama_sewa; ?> hari</td>
                            </tr>
                            <tr>
                                <td>Total Harga </td>
                                <td> :</td>
                                <td>Rp. <?= number_format($bookingFromApi->total_harga); ?></td>
                            </tr>
                            <tr>
                                <td>Status </td>
                                <td> :</td>
                                <td><?= $bookingFromApi->konfirmasi_pembayaran; ?></td>
                            </tr>
                            <tr>
                                <td>Status </td>
                                <td> :</td>
                                <td>
                                    <select class="form-control" name="status">
                                        <option value="Sedang di proses" <?php if ($bookingFromApi->konfirmasi_pembayaran == 'Sedang di proses') echo 'selected'; ?>>
                                            Sedang di proses
                                        </option>
                                        <option value="Pembayaran di terima" <?php if ($bookingFromApi->konfirmasi_pembayaran == 'Pembayaran di terima') echo 'selected'; ?>>
                                            Pembayaran di terima
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="id_booking" value="<?= $bookingFromApi->id_booking; ?>">
                        <button type="submit" class="btn btn-primary float-right">
                            Ubah Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>

<?php include '../footer.php'; ?>