<div class="why-wrapper">
    <div class="container">
        <div class="row why-item-all">
            <div class="col why-item">
                <a href="#"><img src="./images/iconcategory/handphone.png" alt=""></a>
            </div>
            <div class="col why-item">
                <a href="#"> <img src="./images/iconcategory/sofa.png" alt=""> </a>
            </div>
            <div class="col why-item active-item">
                <a href="#"> <img src="./images/iconcategory/glasses.png" alt=""></a>
            </div>
            <div class="col why-item ">
                <a href="#"><img src="./images/iconcategory/bycicle.png" alt=""> </a>
            </div>
            <div class="col why-item">
                <a href="#"><img src="./images/iconcategory/washmacine.png" alt=""></a>
            </div>
        </div>
    </div>
</div>

<div class="item-brand">
    <div class="container">
        <div class="row item-brand-all">
            <div class="col item-brand-list active-logo">
                <a href="#"> <img src="./images/logobrand/samsung.png" alt=""> </a>
            </div>
            <div class="col item-brand-list">
                <a href="#"> <img src="./images/logobrand/hitachi.png" alt=""> </a>
            </div>
            <div class="col item-brand-list">
                <a href="#"> <img src="./images/logobrand/panasonic.png" alt=""> </a>
            </div>
            <div class="col item-brand-list">
                <a href="#"> <img src="./images/logobrand/sony.png" alt=""> </a>
            </div>
            <div class="col item-brand-list">
                <a href="#"> <img src="./images/logobrand/toshiba.png" alt=""> </a>
            </div>
        </div>
    </div>
</div>

<div class="home-product">
    <div class="container">
        <div class="promo">
            <h2>Promo Untukmu</h2>
            <!-- Slider main container -->
            <div class="swiper mt-3">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div class="swiper-slide">
                        <a href="index.php?page=detailpromoPage">
                            <div class="card promo">
                                <img src="./images/promo/promo01.png" alt="...">
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="index.php?page=detailpromoPage">
                            <div class="card promo">
                                <img src="./images/promo/promo01.png" alt="...">
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="index.php?page=detailpromoPage">
                            <div class="card promo">
                                <img src="./images/promo/promo01.png" alt="...">
                            </div>
                        </a>
                    </div>
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="katalog-product">
            <?php
            // cek status page
            if (isset($_GET['pagenow']) && $_GET['pagenow'] != "") {
                $pagenow = $_GET['pagenow'];
            } else {
                $pagenow = 1;
            }


            $batas = 16;
            $posisi = ($pagenow - 1) * $batas;
            $previous_page = $pagenow - 1;
            $next_page = $pagenow + 1;
            $adjacents = "2";

            $count_data_product = "SELECT * FROM products WHERE status=1";
            $query_count_product = mysqli_query($koneksi, $count_data_product);
            $total_data_product = mysqli_num_rows($query_count_product);
            $total_data_page = ceil($total_data_product / $batas);


            $data_product = "SELECT * FROM products WHERE status=1 ORDER BY id DESC LIMIT $posisi,$batas";
            $query_product = mysqli_query($koneksi, $data_product);
            $second_last = $total_data_page - 1; // total page minus 1 untuk menentukan
            // $second_last_en = base64_encode("$second_last"); 
            $linkaddress = "index.php";
            ?>
            
            <!-- Paging atas -->
            <div class="row custome_paging justify-content-center">
                <nav aria-label="...">
                    <ul class="pagination">
                        <?php
                        // link awal
                        if ($pagenow <= 1) { // bu   
                            echo "
                                <li class='page-item disabled'>
                                    <span class='page-link'>Previous</span>
                                </li>
                                ";
                        } else if ($pagenow > 1) {
                            echo "
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$previous_page'>Previous</a>
                                </li>
                                ";
                        }

                        if ($pagenow <= 1) {
                            $pagenow1 = $pagenow + 1;
                            $pagenow2 = $pagenow + 2;
                            echo "
                                <li class='page-item active'>
                                    <a class='page-link'>" . $pagenow . "</a>
                                </li>
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$pagenow1'>" . $pagenow1 . "</a>
                                </li>
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$pagenow2'>" . $pagenow2 . "</a>
                                </li>
                                ";
                        } else if (($pagenow > 1) && ($pagenow < $total_data_page)) {
                            $pagenowMin1 = $pagenow - 1;
                            $pagenowPlus1 = $pagenow + 1;
                            echo "
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$pagenowMin1'>" . $pagenowMin1 . "</a>
                                </li>
                                <li class='page-item active'>
                                    <a class='page-link'>" . $pagenow . "</a>
                                </li>
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$pagenowPlus1'>" . $pagenowPlus1 . "</a>
                                </li>
                                ";
                        } else if ($pagenow >= $total_data_page) {
                            $pagenowMin1 = $total_data_page - 1;
                            $pagenowMin2 = $total_data_page - 2;
                            echo "
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$pagenowMin2'>" . $pagenowMin2 . "</a>
                                </li>
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$pagenowMin1'>" . $pagenowMin1 . "</a>
                                </li>
                                <li class='page-item active'>
                                    <a class='page-link'>" . $pagenow . "</a>
                                </li>
                                ";
                        }
                        ?>

                        <!-- <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active">
                            <span class="page-link">
                                2
                                <span class="sr-only">(current)</span>
                            </span>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li> -->
                        <?php
                        if ($pagenow >= $total_data_page) {
                            echo "
                                <li class='page-item disabled'>
                                    <span class='page-link'>Next</span>
                                </li>
                                ";
                        } else if ($pagenow < $total_data_page) {
                            echo "
                                <li class='page-item'>
                                    <a class='page-link' href='$linkaddress?pagenow=$next_page'>Next</a>
                                </li>
                                ";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
            <h2>Katalog Produk</h2>
            <div class="row">
                <?php
                //Tampil data
                while ($show_product = mysqli_fetch_array($query_product)) {
                ?>
                    <div class="col-6 col-md-6 col-lg-3 mt-4">
                        <div class="card katalog-item">
                            <img src="./images/product/<?php echo $show_product['image_product']; ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <p class="card-text"><?php echo $show_product['product_name']; ?></p>
                            </div>
                            <span class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                                <div class="card-footer">
                                    <p class="text-visit-katalog">Apply Now</p>
                                </div>
                            </span>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>
    </div>
    <!-- Paging -->
    <div class="row custome_paging justify-content-center">
        <nav aria-label="...">
            <ul class="pagination">
                <?php
                // link awal
                if ($pagenow <= 1) { // bu   
                    echo "
                        <li class='page-item disabled'>
                            <span class='page-link'>Previous</span>
                        </li>
                        ";
                } else if ($pagenow > 1) {
                    echo "
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$previous_page'>Previous</a>
                        </li>
                        ";
                }

                if ($pagenow <= 1) {
                    $pagenow1 = $pagenow + 1;
                    $pagenow2 = $pagenow + 2;
                    echo "
                        <li class='page-item active'>
                            <a class='page-link'>" . $pagenow . "</a>
                        </li>
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$pagenow1'>" . $pagenow1 . "</a>
                        </li>
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$pagenow2'>" . $pagenow2 . "</a>
                        </li>
                        ";
                } else if (($pagenow > 1) && ($pagenow < $total_data_page)) {
                    $pagenowMin1 = $pagenow - 1;
                    $pagenowPlus1 = $pagenow + 1;
                    echo "
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$pagenowMin1'>" . $pagenowMin1 . "</a>
                        </li>
                        <li class='page-item active'>
                            <a class='page-link'>" . $pagenow . "</a>
                        </li>
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$pagenowPlus1'>" . $pagenowPlus1 . "</a>
                        </li>
                        ";
                } else if ($pagenow >= $total_data_page) {
                    $pagenowMin1 = $total_data_page - 1;
                    $pagenowMin2 = $total_data_page - 2;
                    echo "
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$pagenowMin2'>" . $pagenowMin2 . "</a>
                        </li>
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$pagenowMin1'>" . $pagenowMin1 . "</a>
                        </li>
                        <li class='page-item active'>
                            <a class='page-link'>" . $pagenow . "</a>
                        </li>
                        ";
                }
                ?>

                <!-- <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active">
                    <span class="page-link">
                        2
                        <span class="sr-only">(current)</span>
                    </span>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li> -->
                <?php
                if ($pagenow >= $total_data_page) {
                    echo "
                        <li class='page-item disabled'>
                            <span class='page-link'>Next</span>
                        </li>
                        ";
                } else if ($pagenow < $total_data_page) {
                    echo "
                        <li class='page-item'>
                            <a class='page-link' href='$linkaddress?pagenow=$next_page'>Next</a>
                        </li>
                        ";
                }
                ?>
            </ul>
        </nav>
    </div>
</div>


<!-- Modal HTML PRODUCT -->
<div id="myModal" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="index.php?page=thankyouPage" method="post" class="submitForm" data-type="login">
                <div class="modal-header">
                    <h4 class="modal-title">Product XXX</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input name="customer_name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email_address" type="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input name="customer_phone_number" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <select name="city" class="form-control">
                            <option> --Choose One-- </option>
                            <option>Balikpapan</option>
                            <option>Bandung</option>
                            <option>Banjarmasin</option>
                            <option>Batam</option>
                            <option>Bekasi</option>
                            <option>Bengkulu</option>
                            <option>Bogor</option>
                            <option>Cirebon</option>
                            <option>Denpasar</option>
                            <option>Depok</option>
                            <option>Gorontalo</option>
                            <option>Jakarta</option>
                            <option>Jambi</option>
                            <option>Karawang</option>
                            <option>Kediri</option>
                            <option>Lampung</option>
                            <option>Makassar</option>
                            <option>Malang</option>
                            <option>Manado</option>
                            <option>Medan</option>
                            <option>Melayu Deli</option>
                            <option>Padang</option>
                            <option>Palembang</option>
                            <option>Pekanbaru</option>
                            <option>Pontianak</option>
                            <option>Semarang</option>
                            <option>Surabaya</option>
                            <option>Tangerang</option>
                            <option>Tangerang Selatan</option>
                            <option>Yogyakarta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Area</label>
                        <select name="area" class="form-control">
                            <option>Area 1</option>
                            <option>Area 2</option>
                            <option>Area 3</option>
                            <option>Area 4</option>
                            <option>Area 5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Hadiah</label>
                        <select name="hadiah" class="form-control">
                            <option>Hadiah 1</option>
                            <option>Hadiah 2</option>
                            <option>Hadiah 3</option>
                            <option>Hadiah 4</option>
                            <option>Hadiah 5</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <div class="clearfix">
                            <label><a href="#myModalForgotPassword" class="pull-right text-muted trigger-btn" data-dismiss="modal" data-toggle="modal"><small>Forgot Password ?</small></a></label>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <img src="images/items/ellipsis.gif" width="20%" id="loading-img" alt="loading-img">
                    <div class="system_error"></div><br />
                    <!-- <label class="checkbox-inline pull-left"><a href="#myModalRegist" class="trigger-btn" data-toggle="modal">Register</a></label> -->
                    <input type="submit" class="btn btn-primary pull-right" value="Apply Now">
                </div>
            </form>
        </div>
    </div>
</div>