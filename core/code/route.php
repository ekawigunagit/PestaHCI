<?PHP 
// @session_start();

    if (! isset($_GET['page']))
    {
        $halaman="./source.php";
    }
    else
    {   
        switch($_GET["page"])
        {               
        
            // case "productList":
            //     $halaman="core/modul/productList.php";
            // break;

            // case "productDetail":
            //     $halaman="core/modul/productDetail.php";
            // break;

            // case "cartPage":
            //     $halaman="core/modul/cartPage.php";
            // break;

            // case "checkoutPage":
            //     $halaman="core/modul/checkoutPage.php";
            // break;

            case "thankyouPage":
                $halaman="core/modul/thankyouPage.php";
            break;

            case "detailpromoPage":
                $halaman="core/modul/detailpromoPage.php";
            break;

            case "hadiahPage":
                $halaman="core/modul/productHadiah.php";
            break;

            case "promoPage":
                $halaman="core/modul/promoPage.php";
            break;

            case "thankyouPagePromo":
                $halaman="core/modul/thankyouPagePromo.php";
            break;

            case "gimmick":
                $halaman="core/modul/gimmickPage.php";
            break;

        }
    }

    include("$halaman");
?>