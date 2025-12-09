<?php if(!defined('ABSPATH')){ echo "No Script"; exit; }
global $wstheme_options,$theme_dir;
?>



<footer class="bg-zinc-900 pt-10">
    <div class="wrapper ">
        <!-- Footer Top -->
        <div class="flex flex-col md:flex-row justify-between gap-2 md:gap-10 xl:gap-5">
            <div class="shrink-0 xl:w-64">
                <a alt="Logo" href="<?php echo site_url(); ?>" class="block w-32 xl:w-52">
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2024/06/royal-patrika-logo.png" heght="185"
                        width="195" alt="Royal patrika Logo" title="Royal patrika Logo">
                </a>
            </div>
            <div class="max-w-lg">
                <p class="text-white text-sm">रॉयल पत्रिका न्यूज़पेपर को पाठकों द्वारा काफी पसंद दिया जा रहा है ,क्योंकि
                    रॉयल
                    पत्रिका लीक से
                    हटकर कुछ करने की कोशिश कर रहा है। रॉयल पत्रिका आज की आर्थिक भाग दौड़ में भी आम समस्याओं ,
                    घटनाओं, शिक्षा एवं सामाजिक बदलाव की खबरों को प्राथमिकता देता है। अपने पाठकों के भरोसे पर खरा
                    उतरने, प्रदेश व देश के प्रति अपनी जिम्मेदारी को समझते हुए रॉयल पत्रिका खबरों को प्रकाशित करता
                    है। रॉयल पत्रिका देश व समाज में आपसी भाईचारा, शांति एवं सद्भाव बनाए रखने के लिए मिशन की तरह काम
                    करता है।</p>
            </div>
            <div class="w-80 shrink-0 ftr-news-letter">
                <?php echo do_shortcode('[newsletter_form]');?>
            </div>
        </div>



        <!-- Footer Middle -->
        <div class="flex flex-col md:grid md:grid-cols-4 gap-5 border-y border-neutral-700 py-10 mt-10 divide-y divide-neutral-700 sm:divide-y-0">

            <div class="col-span-2 lg:col-span-1 space-y-3" x-data="{footer_menu: false}">
                <h3 class="text-xl text-white text-left flex justify-between gap-2" @click="footer_menu = !footer_menu">
                    <span>Important News</span><i class="fa-solid shrink-0 sm:hidden!" :class="footer_menu ? 'fa-caret-up' : 'fa-caret-down'"></i></h3>
                <div class="sm:flex flex-col" :class="footer_menu ? 'flex' : 'hidden'">
                    <?php
                  wp_nav_menu( array(
                        'theme_location' => 'footer-menu-1',
                        'container' => 'ul',
                        'sub_menu' => true,
                        'menu_class'=> 'footer-menu'
                     ) );
                   ?>
                </div>
            </div>
            <div class="col-span-2 lg:col-span-1 space-y-3" x-data="{footer_menu: false}">
                <h3 class="text-xl text-white text-left flex justify-between gap-2" @click="footer_menu = !footer_menu">
                    <span>Explore More</span><i class="fa-solid shrink-0 sm:hidden!" :class="footer_menu ? 'fa-caret-up' : 'fa-caret-down'"></i></h3>
                <div class="sm:flex flex-col" :class="footer_menu ? 'flex' : 'hidden'">
                    <?php
                  wp_nav_menu( array(
                        'theme_location' => 'footer-menu-2',
                        'container' => 'ul',
                        'sub_menu' => true,
                        'menu_class'=> 'footer-menu'
                     ) );
                   ?>
                </div>
            </div>
            <div class="col-span-2 lg:col-span-1 space-y-3" x-data="{footer_menu: false}">
                <h3 class="text-xl text-white text-left flex justify-between gap-2" @click="footer_menu = !footer_menu">
                    <span>Quick Links</span><i class="fa-solid shrink-0 sm:hidden!" :class="footer_menu ? 'fa-caret-up' : 'fa-caret-down'"></i></h3>
                <div class="sm:flex flex-col" :class="footer_menu ? 'flex' : 'hidden'">
                    <?php
                  wp_nav_menu( array(
                        'theme_location' => 'footer-menu-3',
                        'container' => 'ul',
                        'sub_menu' => true,
                        'menu_class'=> 'footer-menu'
                     ) );
                   ?>
                </div>
            </div>

            <div class="col-span-2 lg:col-span-1">
                <h3 class="text-xl text-white text-left pb-3">Get in Touch</h3>
                <div class="flex flex-col gap-2">

                    <?php if ($phone = get_theme_mod('footer_phone')): ?>
                    <div class="flex gap-2 text-neutral-300! items-center"><i class="fa-solid fa-phone shrink-0"></i><a
                            class="no-underline! text-lg font-normal! text-neutral-300! hover:text-brand! ease-in-out duration-200"
                            href="tel:<?php echo esc_html($phone); ?>"><?php echo esc_html($phone); ?></a></div>
                    <?php endif; ?>

                    <?php if ($email = get_theme_mod('footer_email')): ?>
                    <div class="flex gap-2 text-neutral-300! items-center"><i class="fa-solid fa-envelope"></i><a
                            class="no-underline! text-lg font-normal! text-neutral-300! hover:text-brand! ease-in-out duration-200"
                            href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a></div>
                    <?php endif; ?>

                    <?php if ($address = get_theme_mod('footer_address')): ?>
                    <div class="flex gap-2 text-neutral-300!"><i
                            class="fa-solid fa-location-dot translate-y-1"></i><span
                            class="text-lg font-normal!"><?php echo nl2br(esc_html($address)); ?><span></div>
                    <?php endif; ?>

                    <?php if ($hours = get_theme_mod('footer_hours')): ?>
                    <div class="flex flex-col gap-2 text-neutral-300!"><span class="text-lg"><i
                                class="fa-solid fa-clock text-base"></i>
                            Business Hours</span> <span><?php echo nl2br(esc_html($hours)); ?></span></div>
                    <?php endif; ?>

                </div>

            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="flex flex-wrap flex-col-reverse md:flex-row justify-between gap-2 items-center py-5">
            <p class="text-sm text-neutral-400 font-normal! text-center md:text-left">Copyright &copy; <span id="currentYear"></span> All rights
                reserved by Royal
                Patrika | Powered by
                <a href="https://jobresultdekho.com/"
                    class="text-sm text-neutral-200! hover:text-brand! transition font-normal! no-underline!">www.jobresultdekho.com</a>
            </p>
            <div class="flex justify-center">                  
              <div class="flex gap-2 py-2"> <?php echo do_shortcode('[social_icons style="brand"]'); ?></div>
            </div>
        </div>

    </div>






    <div class="modal fade hidden" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body modal-poup-contactform">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fas fa-close"></i></button>
                    <?php echo do_shortcode('[contact-form-7 id="acae1c1" title="Contact form 1"]');?>
                </div>
            </div>
        </div>
    </div>


</footer>
<!-- Scroll to top -->
<div class="scroll-to-top">
    <a href="#!" onclick="scrollToTop()"><i class="fa-solid fa-chevron-up"></i></a>
</div>
<!-- Scroll to top -->



<?php $timestamp = time(); ?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery.min.js?ver=<?php echo $timestamp; ?>">
</script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.min.js?ver=<?php echo $timestamp; ?>">
</script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/lightgallery.js?ver=<?php echo $timestamp; ?>">
</script>


<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/custom.js?ver=<?php echo $timestamp; ?>"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const year = new Date().getFullYear();
    document.getElementById("currentYear").textContent = year;
});



jQuery(document).ready(function($) {
    $("#custom-prev").on("click", function() {
        fpvPrevPage(); // Call existing function
    });

    $("#custom-next").on("click", function() {
        fpvNextPage(); // Call existing function
    });
});
</script>
<script>
// jQuery(document).ready(function($) {
//     $('.header-menu').addClass('owl-carousel').owlCarousel({
//         items: 5,
//         margin: 10,
//         nav: true,
//         dots: false,
//         autoplay: false,
//         autoWidth: true, // allows dynamic width
//         responsive: {
//             0: { items: 2 },
//             600: { items: 4 },
//             1000: { items: 6 }
//         }
//     });
// });
</script>

<?php wp_footer(); ?>
</body>

</html>