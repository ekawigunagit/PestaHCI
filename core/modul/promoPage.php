<div class="promo-wrapper">
    <div class="container">
        <div class="row promo-card-wrapper">
            <?php
            $data_promohci = "SELECT * FROM promos WHERE status=1 ORDER BY id DESC";
            $query_promohci = mysqli_query($koneksi, $data_promohci);

            while ($show_promohci = mysqli_fetch_array($query_promohci)) {
                //start date
                $ambil_startdate=$show_promohci['start'];
                $startdate_tgl=substr($ambil_startdate, 8, 2);
                $startdate_bln=substr($ambil_startdate, 5, 2);
                $startdate_bln=$nama_bln[$startdate_bln];
                $startdate_thn=substr($ambil_startdate, 0, 4);
                $show_startdate=$startdate_tgl . " " . $startdate_bln . " " . $startdate_thn; 

                //start date
                $ambil_enddate=$show_promohci['end'];
                $enddate_tgl=substr($ambil_enddate, 8, 2);
                $enddate_bln=substr($ambil_enddate, 5, 2);
                $enddate_bln=$nama_bln[$enddate_bln];
                $enddate_thn=substr($ambil_enddate, 0, 4);
                $show_enddate=$enddate_tgl . " " . $enddate_bln . " " . $enddate_thn;
            ?>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100 ">
                    <img src="./images/promo/<?php echo $show_promohci['image_promo']; ?>" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $show_promohci['title_promo']; ?></h5>
                        <p class="card-text">Periode <?php echo $show_startdate; ?> - <?php echo $show_enddate; ?></p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage?idpr=<?php echo $show_promohci['id']; ?>"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            <!-- <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100 ">
                    <img src="./images/promo/promo02.jpg" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Promo Bunga Bisa 0% di TransLiving</h5>
                        <p class="card-text">Periode 24 Desember 2021 - 31 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100">
                    <img src="./images/promo/promo03.jpg" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Bayar Cicilan di Blibli Dapat Cashback Rp30.000!</h5>
                        <p class="card-text">Periode 6 Desember 2021 - 31 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100">
                    <img src="./images/promo/promo04.jpg" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Promo Bunga Bisa 0% buat XIAOMI 11T & 11T Pro</h5>
                        <p class="card-text">Periode 4 Desember 2021 - 12 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100">
                    <img src="./images/promo/promo05.jpg" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Kini Dapat Bunga 0% Saat Belanja Kebutuhan Rumah di Caption Home</h5>
                        <p class="card-text">Periode 3 Desember 2021 - 31 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100">
                    <img src="./images/promo/promo06.png" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Waktunya Belanja Produk Wakai, Meizo, Little Things She Needs, Paul &
                            Frank, SAGA</h5>
                        <p class="card-text">Periode 3 Desember 2021 - 31 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100">
                    <img src="./images/promo/promo07.jpg" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Awas Serangan HEBAT! Hemat Belanja Akhir Tahun</h5>
                        <p class="card-text">Periode 1 Desember 2021 - 31 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100">
                    <img src="./images/promo/promo08.jpg" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Bayar Cicilan Lewat LinkAja Dapat Cashback Rp3.000</h5>
                        <p class="card-text">Periode 1 Desember 2021 - 31 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 promo-card">
                <div class="card h-100">
                    <img src="./images/promo/promo09.jpg" class="card-img-top-1" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">iPhone 13 Lagi Promo Bunga Rendah Mulai 1,99% + Gratis Biaya Admin!</h5>
                        <p class="card-text">Periode 19 November 2021 - 31 Desember 2021</p>
                    </div>
                    <div class="card-footer-promo">
                        <a href="index.php?page=detailpromoPage"><button class="btn">Lihat</button></a>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>