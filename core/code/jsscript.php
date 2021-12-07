<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
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
<!-- <script src="js/product.js"></script>
<script src="js/categories.js"></script>
<script src="js/cart.js"></script> -->

<script type="module">
    import Swiper from 'https://unpkg.com/swiper@7/swiper-bundle.esm.browser.min.js'

    const swiper = new Swiper('.swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
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
</script>