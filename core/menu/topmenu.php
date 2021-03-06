<!-- Header -->

<header class="header">
    <div class="header_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header_content d-flex flex-row align-items-center justify-content-start">
                        <div class="logo">
                            <a href="<?php echo $baseURL; ?>">
                                <img src="images/logoPesta.png">
                            </a>
                        </div>
                        <nav class="main_nav">
                            <ul>
                                <li class="active"><a href="<?php echo $baseURL; ?>">Home</a>

                                </li>
                                <li class="hassubs">
                                    <a href="">Categories</a>
                                    <ul>
                                        <?php
                                        $data_category = "SELECT *, category_products.id AS ctID FROM category_products LEFT JOIN products ON category_products.id = products.category_product_id WHERE category_products.status=1 AND products.id IS NOT NULL 
                                        GROUP BY category_products.id ORDER BY category_products.id ASC";
                                        // echo $data_category; exit;
                                        $query_data_category = mysqli_query($koneksi, $data_category);
                                        while ($show_data_category = mysqli_fetch_array($query_data_category)) {

                                        ?>
                                            <li class="page_menu_item menu_mm"><a href="?ctpr=<?php echo sekuriti($show_data_category['ctID'], 'encrypt'); ?>"><?php echo $show_data_category['category_name']; ?><i class="fa fa-angle-down"></i></a></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <li class="hassubs">
                                    <a href="">Cities</a>
                                    <ul>
                                        <?php
                                        $data_menucities = "SELECT * FROM provinces WHERE status=1 ORDER BY province_name ASC";
                                        $query_data_menucities = mysqli_query($koneksi, $data_menucities);

                                        while ($show_data_menucities = mysqli_fetch_array($query_data_menucities)) {
                                        ?>
                                            <li class="Cities"><a href="<?php echo $baseURL; ?>"><?php echo $show_data_menucities['province_name']; ?></a></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <li><a href="?page=promoPage">Our Promo</a></li>
                                <li><a href="?page=hadiahPage">Hadiah Pesta</a></li>
                                <li><a href="?page=gimmick">Game Pesta</a></li>
                            </ul>
                        </nav>
                        <div class="header_extra ml-auto">
                            <!-- <div class="shopping_cart">
                                <a href="index.php?page=cartPage">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 489 489" style="enable-background:new 0 0 489 489;" xml:space="preserve">
                                        <g>
                                            <path d="M440.1,422.7l-28-315.3c-0.6-7-6.5-12.3-13.4-12.3h-57.6C340.3,42.5,297.3,0,244.5,0s-95.8,42.5-96.6,95.1H90.3
                                                c-7,0-12.8,5.3-13.4,12.3l-28,315.3c0,0.4-0.1,0.8-0.1,1.2c0,35.9,32.9,65.1,73.4,65.1h244.6c40.5,0,73.4-29.2,73.4-65.1
                                                C440.2,423.5,440.2,423.1,440.1,422.7z M244.5,27c37.9,0,68.8,30.4,69.6,68.1H174.9C175.7,57.4,206.6,27,244.5,27z M366.8,462
                                                H122.2c-25.4,0-46-16.8-46.4-37.5l26.8-302.3h45.2v41c0,7.5,6,13.5,13.5,13.5s13.5-6,13.5-13.5v-41h139.3v41
                                                c0,7.5,6,13.5,13.5,13.5s13.5-6,13.5-13.5v-41h45.2l26.9,302.3C412.8,445.2,392.1,462,366.8,462z"/>
                                        </g>
                                    </svg>
                                    <div>Cart <span>(0)</span></div>
                                </a>
                            </div> -->
                            <div class="search">
                                <div class="search_icon">
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 475.084 475.084" style="enable-background:new 0 0 475.084 475.084;" xml:space="preserve">
                                        <g>
                                            <path d="M464.524,412.846l-97.929-97.925c23.6-34.068,35.406-72.047,35.406-113.917c0-27.218-5.284-53.249-15.852-78.087
                                            c-10.561-24.842-24.838-46.254-42.825-64.241c-17.987-17.987-39.396-32.264-64.233-42.826
                                            C254.246,5.285,228.217,0.003,200.999,0.003c-27.216,0-53.247,5.282-78.085,15.847C98.072,26.412,76.66,40.689,58.673,58.676
                                            c-17.989,17.987-32.264,39.403-42.827,64.241C5.282,147.758,0,173.786,0,201.004c0,27.216,5.282,53.238,15.846,78.083
                                            c10.562,24.838,24.838,46.247,42.827,64.234c17.987,17.993,39.403,32.264,64.241,42.832c24.841,10.563,50.869,15.844,78.085,15.844
                                            c41.879,0,79.852-11.807,113.922-35.405l97.929,97.641c6.852,7.231,15.406,10.849,25.693,10.849
                                            c9.897,0,18.467-3.617,25.694-10.849c7.23-7.23,10.848-15.796,10.848-25.693C475.088,428.458,471.567,419.889,464.524,412.846z
                                                M291.363,291.358c-25.029,25.033-55.148,37.549-90.364,37.549c-35.21,0-65.329-12.519-90.36-37.549
                                            c-25.031-25.029-37.546-55.144-37.546-90.36c0-35.21,12.518-65.334,37.546-90.36c25.026-25.032,55.15-37.546,90.36-37.546
                                            c35.212,0,65.331,12.519,90.364,37.546c25.033,25.026,37.548,55.15,37.548,90.36C328.911,236.214,316.392,266.329,291.363,291.358z
                                            " />
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="hamburger"><i class="fa fa-bars" aria-hidden="true"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Panel -->
    <div class="search_panel trans_300">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="search_panel_content d-flex flex-row align-items-center justify-content-end">
                        <form action="#">
                            <input type="text" class="search_input" placeholder="Search" required="required">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social -->
    <!-- <div class="header_social">
        <ul>
            <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
        </ul>
    </div> -->
</header>

<!-- Menu -->

<div class="menu menu_mm trans_300">
    <div class="menu_container menu_mm">
        <div class="page_menu_content">

            <div class="page_menu_search menu_mm">
                <form action="#">
                    <input type="search" required="required" class="page_menu_search_input menu_mm" placeholder="Search for products...">
                </form>
            </div>
            <ul class="page_menu_nav menu_mm">
                <li class="page_menu_item menu_mm">
                    <a href="<?php echo $baseURL; ?>">Home<i class="fa fa-angle-down"></i></a>
                </li>
                <li class="page_menu_item has-children menu_mm">
                    <a href="#">Categories<i class="fa fa-angle-down"></i></a>
                    <ul class="page_menu_selection menu_mm">
                        <?php
                        $data_menucategory = "SELECT * FROM category_products WHERE status=1 ORDER BY category_name ASC";
                        $query_data_menucategory = mysqli_query($koneksi, $data_menucategory);

                        while ($show_data_menucategory = mysqli_fetch_array($query_data_menucategory)) {
                        ?>
                            <li class="page_menu_item menu_mm"><a href="<?php echo $baseURL; ?>"><?php echo $show_data_menucategory['category_name']; ?><i class="fa fa-angle-down"></i></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="page_menu_item has-children menu_mm">
                    <a href="#">Cities<i class="fa fa-angle-down"></i></a>
                    <ul class="page_menu_selection menu_mm">
                        <?php
                        $data_menucities = "SELECT * FROM provinces WHERE status=1 ORDER BY province_name ASC";
                        $query_data_menucities = mysqli_query($koneksi, $data_menucities);

                        while ($show_data_menucities = mysqli_fetch_array($query_data_menucities)) {
                        ?>
                            <li class="page_menu_item menu_mm"><a href="<?php echo $baseURL; ?>"><?php echo $show_data_menucities['province_name']; ?><i class="fa fa-angle-down"></i></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="page_menu_item menu_mm"><a href="?page=promoPage">Our Promo<i class="fa fa-angle-down"></i></a></li>
                <li class="page_menu_item menu_mm"><a href="?page=hadiahPage">Hadiah Pesta<i class="fa fa-angle-down"></i></a></li>
                <li class="page_menu_item menu_mm"><a href="?page=gimmick">Game Pesta<i class="fa fa-angle-down"></i></a></li>
            </ul>
        </div>
    </div>

    <div class="menu_close"><i class="fa fa-times" aria-hidden="true"></i></div>

    <!-- <div class="menu_social">
        <ul>
            <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
        </ul>
    </div> -->
</div>