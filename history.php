<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';

if (empty($_SESSION['USER'])) {
    echo '<script>alert("Harap Login");window.location="index.php"</script>';
}

// Fetch data from the API
$urlBookings = 'http://localhost:9000/bookings';
$responseBookings = file_get_contents($urlBookings);
$dataBookings = json_decode($responseBookings);
$bookings = $dataBookings->data->bookings;

// Fetch all data from the API
$urlGadgets = 'http://localhost:9000/gadgets';
$responseGadgets = file_get_contents($urlGadgets);
$dataGadgets = json_decode($responseGadgets);
$apiGadgets = $dataGadgets->data->gadgets;

?>

<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    Daftar Transaksi
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>No. </th>
                                <th>Kode Booking</th>
                                <th>Merk Gadget</th>
                                <th>Nama </th>
                                <th>Tanggal Sewa </th>
                                <th>Lama Sewa </th>
                                <th>Total Harga</th>
                                <th>Konfirmasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($bookings as $booking) {
                                // Find the corresponding gadget for the booking
                                $matchingGadget = array_filter($apiGadgets, function ($gadget) use ($booking) {
                                    return $gadget->id_gadget == $booking->id_gadget;
                                });

                                // If a matching gadget is found, use its 'merk' property, otherwise, use 'Not Found'
                                $merkGadget = !empty($matchingGadget) ? reset($matchingGadget)->merk : 'Not Found';
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?= $booking->kode_booking; ?></td>
                                    <td><?= $merkGadget; ?></td>
                                    <td><?= $booking->nama; ?></td>
                                    <td><?= $booking->tanggal; ?></td>
                                    <td><?= $booking->lama_sewa; ?> hari</td>
                                    <td>Rp. <?= number_format($booking->total_harga); ?></td>
                                    <td><?= $booking->konfirmasi_pembayaran; ?></td>
                                    <td>
                                        <a class="btn btn-primary" href="bayar.php?id=<?= $booking->id_booking; ?>" role="button">Detail</a>
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<br>
<br>

<?php include 'footer.php'; ?>