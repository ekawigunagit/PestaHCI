<div class="container">
    <div class="promo">
        <h2>Promo Untukmu</h2>
        <!-- Slider main container -->
        <div class="swiper mt-3">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide">
                    <a href="index.php?page=promoPage">
                        <div class="card promo">
                            <img src="./images/promo/promo01.png" alt="...">
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="index.php?page=promoPage">
                        <div class="card promo">
                            <img src="./images/promo/promo01.png" alt="...">
                        </div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="index.php?page=promoPage">
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
        <h2>Katalog Produk</h2>
        <div class="row">

            <div class="col-6 col-md-6 col-lg-3 mt-4">
                <div class="card" >
                    <img src="./images/product/glass01.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mt-4">
                <div class="card">
                    <img src="./images/product/glass02.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>

            
            <div class="col-6 col-md-6 col-lg-3 mt-4">
                <div class="card">
                    <img src="./images/product/glass01.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mt-4 mb-4">
                <div class="card" >
                    <img src="./images/product/glass04.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <!--  -->
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="card" style="height: 18rem;">
                    <img src="./images/product/glass01.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="card" style="height: 18rem;">
                    <img src="./images/product/glass02.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="card" style="height: 18rem;">
                    <img src="./images/product/glass04.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="card" style="height: 18rem;">
                    <img src="./images/product/glass04.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card </p>
                    </div>
                    <!--  -->
                    <a href="#" class="link trigger-btn" data-toggle="modal" data-target="#myModal">
                        <div class="card-footer">
                            <p class="text-visit-katalog">Apply Now</p>
                        </div>
                    </a>
                </div>
            </div>



        </div>
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
