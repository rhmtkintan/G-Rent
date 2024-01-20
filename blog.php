<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';

if ($_GET['cari']) {
    $cari = strip_tags($_GET['cari']);
    // Fetch data from the API with search query
    $url = 'http://localhost:9000/gadgets?cari=' . urlencode($cari);
    $response = file_get_contents($url);
    $data = json_decode($response);
    $apiGadgets = $data->data->gadgets;
} else {
    // Fetch all data from the API
    $url = 'http://localhost:9000/gadgets';
    $response = file_get_contents($url);
    $data = json_decode($response);
    $apiGadgets = $data->data->gadgets;
}
?>

<br>
<br>
<img src="" alt="">
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if ($_GET['cari']) {
                echo '<h4> Keyword Pencarian : ' . $cari . '</h4>';
            } else {
                echo '<h4> Semua gadget</h4>';
            }
            ?>
            <div class="row mt-3">
                <?php
                $no = 1;
                foreach ($apiGadgets as $isi) {
                ?>
                    <div class="col-sm-4">
                        <div class="card">
                            <img src="assets/image/<?php echo $isi->gambar; ?>" class="card-img-top" style="height:200px;object-fit:cover;">
                            <div class="card-body" style="background:#ddd">
                                <h5 class="card-title"><?php echo $isi->merk; ?></h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php if ($isi->status == 'Tersedia') : ?>
                                    <li class="list-group-item bg-primary text-white">
                                        <i class="fa fa-check"></i> Available
                                    </li>
                                <?php else : ?>
                                    <li class="list-group-item bg-danger text-white">
                                        <i class="fa fa-close"></i> Not Available
                                    </li>
                                <?php endif; ?>
                                <li class="list-group-item bg-dark text-white">
                                    <i class="fa fa-money"></i> Rp. <?php echo number_format($isi->harga); ?>/ day
                                </li>
                            </ul>
                            <div class="card-body">
                                <center>
                                    <a href="booking.php?id=<?php echo $isi->id_gadget; ?>" class="btn btn-success">Booking now!</a>
                                    <a href="detail.php?id=<?php echo $isi->id_gadget; ?>" class="btn btn-info">Detail</a>
                                </center>
                            </div>
                        </div>
                    </div>
                <?php $no++;
                } ?>
            </div>
        </div>
    </div>
</div>

<br>
<br>
<br>

<?php include 'footer.php'; ?>