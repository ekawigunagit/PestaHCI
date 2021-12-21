<?php
if ((cekstring($_GET['ctpr']))||(cekstring($_GET['ctbr']))||(cekstring($_GET['pagenow']))) {
    echo "Akses Error";
    exit;
}
$ip = $_SERVER['REMOTE_ADDR'];
$operator = isset($_GET['ctpr']) ? "?ctpr=" . $_GET['ctpr'] . "&" : "?";
if (isset($_GET['ctbr'])) {
    $operator .= 'ctbr=' . $_GET['ctbr'] . '&';
} 
// echo $operator; exit;
$data_promohci = "SELECT * FROM temp_locations WHERE ip_visitor='" . $ip . "' ORDER BY id ASC";
// echo $data_promohci; exit;
$query_promohci = mysqli_query($koneksi, $data_promohci);
$row = mysqli_fetch_row($query_promohci);
?>
<div class="why-wrapper">
    <div class="container">
        <div class="why-item-all">
            <div class="swiper2">
                <div class="swiper-wrapper">
                    <?php
                    $data_category = "SELECT *, category_products.id AS ctID FROM category_products LEFT JOIN products ON category_products.id = products.category_product_id WHERE category_products.status=1 AND products.id IS NOT NULL 
                    GROUP BY category_products.id ORDER BY category_products.id ASC";
                    // echo $data_category; exit;
                    $query_data_category = mysqli_query($koneksi, $data_category);
                    while ($show_data_category = mysqli_fetch_array($query_data_category)) {

                    ?>
                        <div class="swiper-slide chooseProduct" data-ct="<?php echo sekuriti($show_data_category['ctID'], 'encrypt'); ?>">
                            <div class="col why-item<?php echo isset($_GET['ctpr']) && sekuriti($_GET['ctpr'], 'decrypt') == $show_data_category['ctID'] ? ' active-item' : ''; ?>">
                                <span><img src="./images/iconcategory/<?php echo $show_data_category['images_category']; ?>" alt=""></span>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
</div>

<div class="item-brand">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="swiper3">
                    <div class="swiper-wrapper justify-content-center">
                        <?php
                        if (isset($_GET['ctpr']) && $_GET['ctpr'] != "") {
                            $ambilbrand = sekuriti($_GET['ctpr'], 'decrypt');
                            // echo $ambilbrand; exit;
                            $data_brand = "SELECT *, brands.id AS brID FROM brands LEFT JOIN products on brands.id = products.brand_id WHERE brands.status=1 AND brands.category_product_id=$ambilbrand AND products.id IS NOT NULL GROUP BY brands.id ORDER BY brands.id ASC";
                            //echo $data_brand; exit;
                            $query_data_brand = mysqli_query($koneksi, $data_brand);
                            // echo "asdfafdsfadfassafsffadsdfsafdsafasdfasdfasdfasfsf"; 
                            while ($show_data_brand = mysqli_fetch_array($query_data_brand)) {
                        ?>
                                <div class="swiper-slide chooseBrand" data-br="<?php echo sekuriti($show_data_brand['brID'], 'encrypt'); ?>" data-ct="<?php echo $_GET['ctpr']; ?>">
                                    <div class="item-brand-list<?php echo isset($_GET['ctbr']) && sekuriti($_GET['ctbr'], 'decrypt') == $show_data_brand['brID'] ? ' active-logo' : ''; ?>">
                                        <img src="./images/logobrand/<?php echo $show_data_brand['image_brand']; ?>" alt="">
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
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
                    <?php
                    $data_promohci = "SELECT * FROM promos WHERE status=1 ORDER BY id ASC";
                    $query_promohci = mysqli_query($koneksi, $data_promohci);

                    while ($show_promohci = mysqli_fetch_array($query_promohci)) {
                        $ambilidpromo = $show_promohci['id'];
                        $ambilidpromo_en = sekuriti($ambilidpromo, 'encrypt');

                    ?>
                        <div class="swiper-slide">
                            <a href="index.php?page=detailpromoPage&idpr=<?php echo $ambilidpromo_en; ?>">
                                <div class="card promo">
                                    <img src="./images/promo/<?php echo $show_promohci['image_promo']; ?>" alt="..." class="card-img-top">
                                </div>
                            </a>
                        </div>

                    <?php
                    }
                    ?>

                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="katalog-product">
            <?php
            // cek status page
            if (isset($_GET['pagenow']) && $_GET['pagenow'] != "") {
                $pagenow_en = $_GET['pagenow'];
                // $pagenow = base64_decode("$pagenow_en");
                $pagenow = sekuriti($pagenow_en, 'decrypt');
            } else {
                $pagenow = 1;
            }

            // sekuriti('6', 'encrypt');

            $batas = 16;
            $posisi = ($pagenow - 1) * $batas;
            $previous_page = $pagenow - 1;
            // $previous_page_en = base64_encode("$previous_page");
            $previous_page_en = sekuriti($previous_page, 'encrypt');
            $next_page = $pagenow + 1;
            // $next_page_en = base64_encode("$next_page");
            $next_page_en = sekuriti($next_page, 'encrypt');
            $adjacents = "2";

            $count_data_product = "SELECT * FROM products WHERE status=1";
            if (isset($_GET['ctpr'])) {
                $count_data_product .= " AND category_product_id =" . sekuriti($_GET['ctpr'], 'decrypt');
            }
            //echo $count_data_product; exit;
            if ((isset($_GET['ctpr']) && (isset($_GET['ctbr'])))) {
                $count_data_product .= " AND brand_id =" . sekuriti($_GET['ctbr'], 'decrypt');
            }
            // echo "CTPR :" . $_GET['ctpr'] . "ctbr : " . $_GET['ctbr']; exit;
            $query_count_product = mysqli_query($koneksi, $count_data_product);
            $total_data_product = mysqli_num_rows($query_count_product);
            $total_data_page = ceil($total_data_product / $batas);
            // $total_data_page_en = base64_encode("$total_data_page");
            $total_data_page_en = sekuriti($total_data_page, 'encrypt');


            $data_product = "SELECT *, products.id AS pdID FROM products LEFT JOIN category_products ON products.category_product_id = category_products.id WHERE products.status=1";
            if (isset($_GET['ctpr'])) {
                $data_product .= " AND category_product_id =" . sekuriti($_GET['ctpr'], 'decrypt');
            }
            if ((isset($_GET['ctpr']) && (isset($_GET['ctbr'])))) {
                $data_product .= " AND brand_id =" . sekuriti($_GET['ctbr'], 'decrypt');
            }
            $data_product .= " ORDER BY products.id ASC LIMIT $posisi,$batas";

            // echo "Brand : " . $count_data_product . " <br />Product : " . $data_product ; exit;

            $query_product = mysqli_query($koneksi, $data_product);
            //echo $query_product; exit;
            $second_last = $total_data_page - 1; // total page minus 1 untuk menentukan
            // $second_last_en = base64_encode("$second_last");
            $second_last_en = sekuriti($second_last, 'encrypt');
            // $second_last_en = base64_encode("$second_last"); 
            $linkaddress = "index.php";
            //echo $total_data_page; exit;
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
                                    <a class='page-link' href='" . $linkaddress . $operator . "pagenow=$previous_page_en'>Previous</a>
                                </li>
                                ";
                        }

                        for ($i = 1; $i <= $total_data_page; $i++) {
                            if ($i == $pagenow) {
                                echo "
                                <li class='page-item active'>
                                    <a class='page-link'>" . $pagenow . "</a>
                                </li>
                                ";
                            } else if ($i > $pagenow) {
                                $pagenow_en = sekuriti($i, 'encrypt');
                                // echo $pagenow_en; exit;
                                echo "
                                <li class='page-item'>
                                    <a class='page-link' href='" . $linkaddress . $operator . "pagenow=$pagenow_en'>" . $i . "</a>
                                </li>
                                ";
                            } else if ($i < $pagenow) {
                                $pagenow_en = sekuriti($i, 'encrypt');
                                // echo $pagenow_en; exit;
                                echo "
                                <li class='page-item'>
                                    <a class='page-link' href='" . $linkaddress . $operator . "pagenow=$pagenow_en'>" . $i . "</a>
                                </li>
                                ";
                            }
                        }
                        ?>
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
                                    <a class='page-link' href='$linkaddress?pagenow=$next_page_en'>Next</a>
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
                        // echo "ID :" . $show_product['id'];
                    ?>
                        <div class="col-6 col-md-6 col-lg-3 mt-4">
                            <div class="card katalog-item h-100">
                                <img src="./images/product/<?php echo $show_product['image_product']; ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <p class="card-text"><?php echo $show_product['product_name']; ?></p>
                                </div>
                                <span class="link trigger-btn" data-toggle="modal" data-target="#myModal" data-productName="<?php echo $show_product['product_name']; ?>" data-brandProduct="<?php echo $show_product['brand_product']; ?>" data-productID="<?php echo $show_product['pdID']; ?> " data-categoryName="<?php echo $show_product['category_name']; ?> ">
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
             <!-- Paging Bawah -->
            <div class="row custome_paging justify-content-center mt-5">
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
                                            <a class='page-link' href='" . $linkaddress . $operator . "pagenow=$previous_page_en'>Previous</a>
                                        </li>
                                        ";
                        }

                        for ($i = 1; $i <= $total_data_page; $i++) {
                            if ($i == $pagenow) {
                                echo "
                                        <li class='page-item active'>
                                            <a class='page-link'>" . $pagenow . "</a>
                                        </li>
                                        ";
                            } else if ($i > $pagenow) {
                                $pagenow_en = sekuriti($i, 'encrypt');
                                // echo $pagenow_en; exit;
                                echo "
                                        <li class='page-item'>
                                            <a class='page-link' href='" . $linkaddress . $operator . "pagenow=$pagenow_en'>" . $i . "</a>
                                        </li>
                                        ";
                            } else if ($i < $pagenow) {
                                $pagenow_en = sekuriti($i, 'encrypt');
                                // echo $pagenow_en; exit;
                                echo "
                                        <li class='page-item'>
                                            <a class='page-link' href='" . $linkaddress . $operator . "pagenow=$pagenow_en'>" . $i . "</a>
                                        </li>
                                        ";
                            }
                        }
                        ?>
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
                                            <a class='page-link' href='$linkaddress?pagenow=$next_page_en'>Next</a>
                                        </li>
                                        ";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

    <!-- Modal HTML PRODUCT -->
    <div id="myModal" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="formProduct" action="core/code/addDataApply.php" method="post" class="submitForm" data-type="login">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="product_name">
                        <input type="hidden" name="brand_product">
                        <input type="hidden" name="product_id">
                        <input type="hidden" name="category_name">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input name="name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input name="phone" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <select name="city" id="productcity_select" class="form-control">
                                <option> --Choose One-- </option>
                                <?php
                                $data_provinces = "SELECT * FROM provinces WHERE status=1 ORDER BY id ASC";
                                $query_provinces = mysqli_query($koneksi, $data_provinces);

                                while ($show_provinces = mysqli_fetch_array($query_provinces)) {
                                ?>
                                    <option value="<?php echo $show_provinces['province_name']; ?>">
                                        <?php echo $show_provinces['province_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Area</label>
                            <select name="area" class="form-control" id="productarea_select"></select>
                        </div>
                        <div class="form-group">
                            <label>Pilih untuk kesempatan memenangkan hadiah</label>
                            <select name="hadiah" class="form-control">
                                <option> --Choose One-- </option>
                                <option>Sharp Kirei III SJ-N162D</option>
                                <option>Sepeda Gunung MTB 26 Inch CASSINI T-300</option>
                                <option>Sepeda Lipat Folding Bike Atlantis 20 Inch 7 Speed</option>
                                <option>Evercoss Etab Plus M80 RAM 2/16</option>
                                <option>Xiaomi Redmi 9A 2/32</option>
                                <option>Skechers F SH 3198 001 55</option>
                                <option>Giordano F GD GA02098T 94 47</option>
                                <option>Springbed Comforta SUPERFIT Super Silver 100x200</option>
                                <option>JTR Oda Sofa Bed Minimalis</option>
                                <option>Mesin Cuci Sharp 65MW</option>
                            </select>
                        </div>
                        <!-- <div class="form-group">
                        <div class="clearfix">
                            <label><a href="#myModalForgotPassword" class="pull-right text-muted trigger-btn" data-dismiss="modal" data-toggle="modal"><small>Forgot Password ?</small></a></label>
                        </div>
                    </div> -->
                    </div>
                    <div class="modal-footer">
                        <!-- <img src="images/items/ellipsis.gif" width="20%" id="loading-img" alt="loading-img"> -->
                        <!-- <div class="system_error"></div><br /> -->
                        <div class="tc-form">
                            <a href="index.php?page=hadiahPage" target="_blank">Term & Condition</a>
                        </div>
                        <!-- <label class="checkbox-inline pull-left"><a href="#myModalRegist" class="trigger-btn" data-toggle="modal">Register</a></label> -->
                        <input type="submit" class="btn btn-primary pull-right" value="Apply Now">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal HTML LOCATION -->
    <?php if (!$row) : ?>
        <div id="myModalLocation" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form action="core/code/addLocation.php" method="post" class="submitForm">
                        <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                        <div class="modal-header">
                            <h4 class="modal-title">Pilih Lokasi Anda</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>City</label>
                                <select name="location_city" id="city_select" class="form-control">
                                    <option> --Choose One-- </option>
                                    <?php
                                    $data_provinces = "SELECT * FROM provinces WHERE status=1 ORDER BY id ASC";
                                    $query_provinces = mysqli_query($koneksi, $data_provinces);

                                    while ($show_provinces = mysqli_fetch_array($query_provinces)) {
                                    ?>

                                        <option value="<?php echo $show_provinces['province_name']; ?>">
                                            <?php echo $show_provinces['province_name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <select name="location_area" class="form-control" id="area_select"></select>
                            </div>
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
    <?php endif; ?>