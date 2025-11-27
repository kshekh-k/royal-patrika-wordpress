<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag, and adds the site's header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
    <?php $timestamp = time(); ?>

    <link
        href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/owl.carousel.min.css?ver=<?php echo $timestamp; ?>"
        rel="stylesheet">

    <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/lightgallery.css?ver=<?php echo $timestamp; ?>"
        rel="stylesheet">
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/style.css?ver=<?php echo $timestamp; ?>"
        rel="stylesheet">

        
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"
/>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Hind:wght@300;400;500;600;700&family=Tiro+Devanagari+Hindi:ital@0;1&display=swap"
        rel="stylesheet">
    <?php wp_head(); ?>
    <meta name="facebook-domain-verification" content="zjji8irxabaq1ryhv2nqexm7llpsqq" />
    <?php if (is_single()) : ?>
    <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>" />
    <meta property="og:description" content="<?php echo esc_attr(get_the_excerpt()); ?>" />
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>" />
    <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:type" content="article" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr(get_the_excerpt()); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" />
    <?php endif; ?>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5527790384256550"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/ec6bb7797d.js" crossorigin="anonymous"></script>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" crossorigin="anonymous"></script>

<style>
#pageLoader.fade-out {
    opacity: 0;
    transition: opacity .5s ease;
}
</style>

<script>
window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    loader.classList.add('fade-out');

    setTimeout(() => loader.style.display = 'none', 500);
});
</script>



</head>

<body <?php body_class(); ?>>
 

<!-- Page Loader -->
<div id="pageLoader" style="
    position:fixed;
    inset:0;
    background:#ffffff;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:999999;
">
    <img src="<?php echo site_url(); ?>/wp-content/themes/hello-theme-child/assets/img/infinite-spinner.svg" width="200" height="200" alt="Loading...">
</div>



    <!-- Top bar -->
    <div class="bg-neutral-900">
        <div class="wrapper">
            <div class="flex justify-between gap-5">
                <!--Social Media Icons  -->
                <?php dynamic_sidebar('social_media_widget'); ?>

                <div class="flex justify-end py-2">
                    <?php
                            wp_nav_menu([
                            'theme_location' => 'top_menu',
                            'container'      => false,
                            'menu_class'     => 'flex divide-x divide-white/10', // optional class
                            ]);
                        ?>
                </div>

            </div>
        </div>
    </div>
    <!-- Main Header -->
    <header class="bg-white" id="header" x-data="{ sidebar: false, searchModal:false }">
        <!-- Header Top -->
        <div class="wrapper py-2 md:py-5">
            <div class="flex justify-between items-center gap-2 flex-wrap">
                <div class="shrink-0 order-1">
                    <div class="flex lg:gap-2 gap-1 items-center">
                        <button @click="sidebar = !sidebar"
                            class="hidden md:flex p-2 justify-center items-center cursor-pointer text-neutral-700 hover:bg-neutral-700 hover:text-white ease-in-out duration-200 rounded-sm"
                            aria-label="Toggle navigation">
                            <i class="fa-solid" :class="sidebar ? 'fa-close' : 'fa-bars'"></i></button>
                        <a alt="Logo" href="<?php echo site_url(); ?>" class="block w-20 sm:w-24 md:w-32 xl:w-52">
                            <img src="<?php echo site_url(); ?>/wp-content/uploads/2024/06/royal-patrika-logo.png"
                                alt="Royal Patrika Logo" title="Royal Patrika Logo">
                        </a>

                    </div>
                </div>
                <div class="w-full md:w-auto md:flex-1 order-4 md:order-2">
                    <?php if ( is_active_sidebar( 'google-ads' ) ) : ?>
                    <?php dynamic_sidebar( 'google-ads' ); ?>
                    <?php endif; ?>
                </div>
                <div class="shrink-0 order-2 md:order-3">
                    <div class="flex gap-1 items-center">
                        <i
                            class="fa-regular fa-calendar hidden! md:inline-block! text-xl lg:text-3xl xl:text-4xl text-neutral-800 shrink-0"></i>
                        <div class="flex flex-col gap-0.5">
                            <span id="hindiTime"
                                class='lg:text-xl xl:text-2xl text-neutral-900 font-semibold leading-none text-center md:text-left'></span>
                            <span id="hindiDate"
                                class='text-neutral-600 leading-none font-semibold text-xs lg:text-sm xl:text-base text-center md:text-left'></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-0.5 items-center md:hidden order-3">
                    <button @click="searchModal = true"
                        class="flex justify-center items-center p-2 hover:text-brand ease-in-out duration-200 text-neutral-700 cursor-pointer"
                        data-bs-toggle="modal" data-bs-target="#searchModal"><i
                            class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <button @click="sidebar = !sidebar"
                        class="flex p-2 justify-center items-center cursor-pointer text-neutral-700 hover:bg-neutral-700 hover:text-white ease-in-out duration-200 rounded-sm"
                        aria-label="Toggle navigation">
                        <i class="fa-solid" :class="sidebar ? 'fa-close' : 'fa-bars'"></i></button>
                </div>
            </div>
        </div>

        <nav class="border-y border-neutral-200 hidden lg:block">
            <div class="wrapper">
                <div class="flex justify-between gap-2 border-x border-neutral-200">
                    <div class="flex items-stretch flex-1 max-w-full overflow-auto xl:overflow-visible">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'container'      => 'ul',
                            'menu_class'     => 'header-menu'
                        ) );
                        ?>
                        <!-- flex divide-x divide-neutral-200 text-xl font-semibold -->
                    </div>
                    <div class="flex justify-end items-center shrink-0 ">
                        <button @click="searchModal = true"
                            class="flex justify-center items-center p-3 hover:text-brand ease-in-out duration-200 text-neutral-700 cursor-pointer"
                            data-bs-toggle="modal" data-bs-target="#searchModal"><i
                                class="fa-solid fa-magnifying-glass"></i>
                        </button>

                        <!-- <div id="real-time-clock"></div> -->
                    </div>
                </div>
            </div>
        </nav>

       

        <!-- Search Modal -->
        <!-- Overlay -->
        <div id="searchModal" class="fixed inset-0 justify-center items-center z-50 flex p-3" x-transition x-show="searchModal">
            <div class="fixed inset-0 bg-black/60" @click="searchModal = false"></div>
            <!-- Modal Box -->
            <div class="bg-white w-full max-w-lg mx-auto rounded-xl shadow-xl p-4 relative z-10 space-y-2">
                <div class="flex justify-between gap-2">
                    <!-- Close Button -->
                    <button id="closeModal" class="absolute top-3 right-3 text-gray-600 hover:text-black text-lg" @click="searchModal = false">
                        <i class="fas fa-close"></i>
                    </button>

                    <!-- Title -->
                    <h2 class="text-lg font-semibold ">Search</h2>
                </div>
                <!-- Search Form -->
                <form method="get" action="<?php echo esc_url(home_url('/')); ?>">

                    <div class="flex items-center overflow-hidden gap-2">

                        <input type="search" name="s" placeholder="Search..."
                            class="w-full px-4 py-3 focus:outline-none focus:border-brand border border-neutral-300 rounded-sm transition" required
                            value="<?php echo get_search_query(); ?>" />

                        <button class="px-5 py-3 bg-brand text-white hover:bg-brand/75 rounded-sm cursor-pointer transition">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                    </div>

                </form>

            </div>
        </div>
        <!-- Search Modal end -->

        <div id="sidebar" x-show="sidebar"
            class="sidebar fixed left-0 inset-y-0 bg-neutral-900 w-52 overflow-auto py-5 px-2 shadow-1 border-r border-neutral-300 z-50"
            x-transition:enter="transform transition duration-300" x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transform transition duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            @click.away="sidebar = false">
            <button @click="sidebar = false"
                class="absolute top-4 right-4 hover:text-brand text-white ease-in-out duration-200 cursor-pointer z-10"
                aria-label="Close navigation"><i class="fas fa-close"></i></button>

            <?php if ( wp_is_mobile() ){
                                wp_nav_menu( array(
                                    'theme_location' => 'primary',
                                    'container'      => 'ul',
                                    'menu_class'     => 'sidebar-menu'
                                ) );
                            }
            ?>
            <h2 class="text-white text-xl font-semibold px-3 ">Categories</h2>
            <ul class="divide-y divide-neutral-700 mt-2 border-t border-neutral-700 pt-3">
                <?php 
                                $categories = get_categories();
                                foreach ( $categories as $category ) {
                                    echo '<li><a class="text-white hover:text-rose-500 hover:bg-brand/5 px-3 ease-in-out duration-200 flex py-3 no-underline!" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></li>';
                                }
                ?>
            </ul>

        </div>
    </header>
     