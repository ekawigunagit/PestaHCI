<?php include "../../config.php";  ?>
<!-- <<<<<<< HEAD
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/jquery.js"></script>
<script src="js/jquery-3.6.0.slim.min.js"></script> -->

<!-- <script src="js/jquery-3.2.1.min.js"></script> -->
<script src="js/jquery-3.6.0.min.js"></script>
<script src="css/bootstrap4/popper.js"></script>
<script src="css/bootstrap4/bootstrap.min.js"></script>
<script src="css/bootstrap4/bootstrap.bundle.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/Isotope/isotope.pkgd.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/jquery.validate.min.js"></script>

<!-- <script src="js/product.js"></script>
<script src="js/categories.js"></script>
<script src="js/cart.js"></script> -->

<script type="module">
    import Swiper from './js/swiper.js';

    var swiper1 = new Swiper('.swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
        allowSlideNext: true,
        allowSlidePrev: true,
        speed: 100,
        pagination: {
            el: '.swiper-pagination',
            dynamicBullets: true,
        },
        autoplay: {
            delay: 5000,
            effect: 'fade',
            disableOnInteraction: false,
        },
        // using "ratio" endpoints
        breakpoints: {
            '@0.75': {
                slidesPerView: 1,
                effect: 'fade',
                spaceBetween: 20,
            },
            '@1.00': {
                slidesPerView: 2,
                spaceBetween: 20,
                effect: 'fade',
            },
            '@1.50': {
                slidesPerView: 2,
                effect: 'fade',
                spaceBetween: 20,

            },
        }
    });
    var swiper2 = new Swiper('.swiper2', {
        slidesPerView: 4,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        allowSlideNext: true,
        allowSlidePrev: true,
        // using "ratio" endpoints
        breakpoints: {
            '@0.75': {
                slidesPerView: 4,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                    height: 30,
                },
            },
            '@1.00': {
                slidesPerView: 5,
                spaceBetween: 20,
            },
            '@1.50': {
                slidesPerView: 5,
                spaceBetween: 0,
            },
        }
    });

    var swiper3 = new Swiper('.swiper3', {
        slidesPerView: 4,
        allowSlidePrev: true,
        breakpoints: {
            '@0.75': {
                slidesPerView: 4,
                effect: 'fade',
                spaceBetween: 20,
            },
            '@1.00': {
                slidesPerView: 5,
                spaceBetween: 20,
                effect: 'fade',
            },
            '@1.50': {
                slidesPerView: 5,
                effect: 'fade',
                spaceBetween: 20,
            },
        }
    });


    $(".why-item").on("click", function() {
        $('.why-item').removeClass("active-item");
        $(this).addClass("active-item");
    })

    $(".item-brand-list").on("click", function() {
        $('.item-brand-list').removeClass("active-logo");
        $(this).addClass("active-logo");
    })

    // window.addEventListener('load', function() {
    //     setTimeout(() => {
    //         $("#myModalLocation").show()
    //     }, 1000)
    // })

    $("#myModalLocation .close").on("click", function() {
        $("#myModalLocation").hide()
    })

    $("#city_select").on("change", function() {
        const value = $(this).val();
        if (value != '') {
            // $.get('core/code/get-districts.php?id=' + value, function(results) {
            //     const values = JSON.parse(results)
            //     let chtml;
            //     $.each(values, (key, result) => {
            //         chtml += '<option value="' + result.district_name + '">' + result.district_name + '</option>'
            //     });

            //     $("#area_select").html(chtml)
            // });
            $.ajax({
                url: 'core/code/get-districts.php?id=' + value,
                headers: {
                    'TOKEN': '<?php echo $token; ?>'
                },
                success: function(results) {
                    const values = JSON.parse(results)
                    let chtml;
                    $.each(values, (key, result) => {
                        chtml += '<option value="' + result.district_name + '">' + result.district_name + '</option>'
                    });

                    $("#area_select").html(chtml)
                }
            })
        } else {
            $("#area_select").html('')
        }
    });

    $("#productcity_select").on("change", function() {
        const value = $(this).val();
        if (value != '') {
            // $.get('core/code/get-districts.php?id=' + value, function(results) {
            //     const values = JSON.parse(results)
            //     let html;
            //     $.each(values, (key, result) => {
            //         html += '<option value="' + result.district_name + '">' + result.district_name + '</option>'
            //     });
            //     $("#productarea_select").html(html)
            // });

            $.ajax({
                url: 'core/code/get-districts.php?id=' + value,
                headers: {
                    'TOKEN': '<?php echo $token; ?>'
                },
                success: function(results) {
                    const values = JSON.parse(results)
                    let html;
                    $.each(values, (key, result) => {
                        html += '<option value="' + result.district_name + '">' + result.district_name + '</option>'
                    });
                    $("#productarea_select").html(html)
                }
            })
        } else {
            $("#productarea_select").html('')
        }
    });

    $("#promocity_select").on("change", function() {
        const value = $(this).val();
        if (value != '') {
            // $.get('core/code/get-districts.php?id=' + value, function(results) {
            //     const values = JSON.parse(results)
            //     let html;
            //     $.each(values, (key, result) => {
            //         html += '<option value="' + result.district_name + '">' + result.district_name + '</option>'
            //     });
            //     $("#promoarea_select").html(html)
            // });
            $.ajax({
                url: 'core/code/get-districts.php?id=' + value,
                headers: {
                    'TOKEN': '<?php echo $token; ?>'
                },
                success: function(results) {
                    const values = JSON.parse(results)
                    let html;
                    $.each(values, (key, result) => {
                        html += '<option value="' + result.district_name + '">' + result.district_name + '</option>'
                    });
                    $("#promoarea_select").html(html)
                }
            })
        } else {
            $("#promoarea_select").html('')
        }
    });

    $('#myModal').on('shown.bs.modal', function(event) {
        const productName = $(event.relatedTarget).data('productname')
        $("#myModal .modal-title").text(productName)
        $("input[name=product_name]").val(productName)

        const brandProduct = $(event.relatedTarget).data('brandproduct')
        $("input[name=brand_product]").val(brandProduct)

        const productID = $(event.relatedTarget).data('productid')
        $("input[name=product_id]").val(productID)

        const categoryName = $(event.relatedTarget).data('categoryname')
        $("input[name=category_name]").val(categoryName)

        const promoID = $(event.relatedTarget).data('promoid')
        $("input[name=promo_id]").val(promoID)


    })

    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            return this.optional(element) || regexp.test(value);
        },
        "Please check your input."
    );

    function storeData(event) {
        event.preventDefault()
        event.stopPropagation()
        const { target } = event
        const formData = new FormData(target)
        $(".error").remove()

        const url = target.action
        const method = target.method
        $.ajax({
            url: url,
            type: method,
            data: formData,
            statusCode: {
                422: function(error) {
                    const errors = JSON.parse(error.responseText)
                    const errorCode = errors.data.errors
                    Object.keys(errorCode).map((value, key) => {
                        errorCode[value].map((errorValue, errorKey) => {
                            $("input[name="+value+"]").after('<label class="error">'+errorValue+'</label>')
                            $("select[name="+value+"]").after('<label class="error">'+errorValue+'</label>')
                        })
                    })
                    $("input[type=submit]").show();
                }
            },
            success: function(response) {
                const result = JSON.parse(response)
                if(result.success) {
                    window.location.href = result.redirect_uri
                }
                $("input[type=submit]").show();
            },
            processData: false,
            contentType: false
        })
    }

    $(document).ready(function() {
        $('#formProduct').on('submit', function(event){
            $("input[type=submit]").hide();
            storeData(event)
        })

        $('#formPromo').on('submit', function(event){
            $("input[type=submit]").hide();
            storeData(event)
        })
        
        $(".chooseProduct").on('click', function() {
            const id = $(this).attr('data-ct')
            window.location.href = "?ctpr=" + id
        })

        $(".chooseBrand").on('click', function() {
            const br = $(this).attr('data-br')
            const ct = $(this).attr('data-ct')

            window.location.href = "?ctpr=" + ct + "&ctbr=" + br
        })
    });
</script>