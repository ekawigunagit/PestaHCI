<?php
$idpr_en = $_GET['idpr'];
$idpr = sekuriti($idpr_en, 'decrypt');
//echo "ID :" . $idpr;
$data_promos = "SELECT *, promos.id AS pmID FROM promos LEFT JOIN category_products ON promos.category_product_id = category_products.id WHERE promos.status=1 AND promos.id='$idpr'";
$query_data_promos = mysqli_query($koneksi, $data_promos);

while ($show_data_promos = mysqli_fetch_array($query_data_promos)) {
    //start date
    $ambil_startdate = $show_data_promos['start'];
    $show_startdate = cleanTgl($ambil_startdate);

    //start date
    $ambil_enddate = $show_data_promos['end'];
    $show_enddate = cleanTgl($ambil_enddate);
?>
    <div class="promo-page">
        <div class="container">
            <div class="promo-product">
                <div class="detail-promo">
                    <h2><?php echo $show_data_promos['title_promo']; ?></h2>
                    <p>Periode <?php echo $show_startdate; ?> - <?php echo $show_enddate; ?></p>
                    <div class="sosmed">
                        <img src="./images/footer/linkidin.png">
                        <img src="./images/footer/fb2.png">
                        <img src="./images/footer/twiter.png">
                        <img src="./images/footer/whatsapp.png">
                        <img src="./images/footer/line.png">
                        <img src="./images/footer/link_copy.png">
                    </div>
                </div>
            </div>
            <div class="detail-text-product">
                <?php echo $show_data_promos['detail_promo']; ?>

                <div class="detail-list">
                    <div class="bnt-ajukan">
                        <span class="link trigger-btn" data-toggle="modal" data-target="#myModal" data-productName="<?php echo $show_data_promos['title_promo']; ?>" data-brandProduct="Promo" data-categoryName="<?php echo $show_data_promos['category_name']; ?> " data-promoID="<?php echo $show_data_promos['pmID']; ?> "><button class="btn" type="submit">Ajukan Sekarang</button></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<div class="product-lainnya">
    <div class="container">
        <h2>Promo & Penawaran Terkait</h2>

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
</div>

<!-- Modal HTML PRODUCT -->
<div id="myModal" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formPromo" action="core/code/addDataApply.php" method="post" class="submitForm" data-type="login">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_name">
                    <input type="hidden" name="brand_product">
                    <input type="hidden" name="category_name">
                    <input type="hidden" name="promo_id">
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
                        <select name="city" id="promocity_select" class="form-control">
                            <option> --Choose One-- </option>
                            <?php
                            $data_provinces = "SELECT * FROM provinces WHERE status=1 ORDER BY id ASC";
                            $query_provinces = mysqli_query($koneksi, $data_provinces);

                            while ($show_provinces = mysqli_fetch_array($query_provinces)) {
                            ?>
                                <option value="<?php echo $show_provinces['province_name']; ?>"><?php echo $show_provinces['province_name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Area</label>
                        <select name="area" id="promoarea_select" class="form-control"></select>
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