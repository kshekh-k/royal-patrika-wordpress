<?php

/*
 * Template Name: Home
 */
get_header();
global $post;
$Page_ID = $post->ID;
?>
<?php if (!defined('ABSPATH')) {
    echo 'No Script';
    exit();
} ?>
<?php get_header(); ?>

<?php
get_template_part('template-parts/home-trending');
get_template_part('template-parts/home-top-4');
?>

<!-- Top 14 news -->
<section class="bg-white pt-5 lg:pt-10">
    <div class="wrapper">
        <div class="md:grid md:grid-cols-12 gap-5 2xl:gap-10 flex flex-col ">
            <div class="md:col-span-9">
                <div class="md:grid md:grid-cols-3 md:grid-rows-2 gap-5 flex flex-col">
                    <div class="md:col-span-2 md:row-span-2 flex-1 md:flex-none ">
                        <!-- Slider Goes Here -->
                        <?php  get_template_part('template-parts/home-featured-slider');  ?>
                    </div>
                    <!-- Here Goes Featured 2 top -->
                    <?php get_template_part('template-parts/home-featured-top-2'); ?>
                </div>
            </div>
            <div class="md:col-span-3 space-y-4 flex flex-col">
                <div class="flex flex-col gap-2 md:flex-1">
                    <div class="flex justify-between items-center gap-2 border-b border-brand ">
                        <h3 class="text-left text-lg text-neutral-900 uppercase"><b>Top</b>
                            Videos</h3>
                        <a href="https://www.youtube.com/@royalpatrika" target="_blank"
                            class="text-sm font-semibold no-underline! py-2 flex justify-center text-center hover:text-blue-500 text-brand   transition ">
                            View All
                        </a>
                    </div>
                    <?php echo do_shortcode('[youtube_videos count="1" filter="latest" layout="grid"]'); ?>
                </div>
                <div class="flex justify-center items-center md:flex-1 [&>.widget]:flex-1 native-ad-wrap">                   
                    <!-- Native Ad Right First -->
                    <?php if ( is_active_sidebar( "native-ads-right-first" ) ) :             
                        dynamic_sidebar( "native-ads-right-first" );
                        else :
                            echo hs_ad_default_image(
                                'square',
                                'object-cover'
                            );
                        endif; 
                    ?>                 
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-5 lg:py-10">
    <div class="wrapper">
        <div class="flex flex-col sm:grid sm:grid-cols-12 gap-5 2xl:gap-10">
            <aside class="col-span-6 lg:col-span-3 flex flex-col gap-2 order-2 lg:order-1">
                <!-- Top Stories goes here -->
                <?php get_template_part('template-parts/top-stories'); ?>
                <!-- Most View & Popular News -->
                <?php get_template_part('template-parts/home-mostview-popularnews'); ?>
            </aside>
            <div class="col-span-12 lg:col-span-6 divide-y divide-neutral-200 flex flex-col gap-7 order-1 lg:order-2">
                <!-- Random Posts -->
                <?php get_template_part('template-parts/home-random-posts'); ?>
            </div>
            <aside class="col-span-6 lg:col-span-3 space-y-5 order-3">
                <!--Social Media Counters & Categories -->
                <?php
                 get_template_part('template-parts/social-stats');
                get_template_part('template-parts/top-categories');
                ?>
                <div class="space-y-4">
                    <h3 class="text-left text-lg text-neutral-900 pb-3 border-b border-brand uppercase">
                        <b>Latest</b> Reviews
                    </h3>
                    <?php echo do_shortcode('[youtube_videos count="3" filter="latest" layout="list"]'); ?>
                </div>
            </aside>
        </div>
    </div>
</section>
<section class="bg-neutral-900 py-5 md:py-10 2xl:py-20">
    <div class="wrapper">
        <?php get_template_part('template-parts/youtube-tabs'); ?>
    </div>
</section>


<section class="py-5 lg:py-10">
    <div class="wrapper">
        <div class="flex flex-col sm:grid sm:grid-cols-12 gap-5 2xl:gap-10">
            <aside class="col-span-6 lg:col-span-3 flex flex-col gap-2 order-2 lg:order-1">
                <?php 
                get_template_part('template-parts/home-tech-innovation');                              
                ?>
                 <div class="flex justify-center items-center">
                    <!-- Native Ad Right Second -->
                    <?php if ( is_active_sidebar( "native-ads-left-second" ) ) :             
                        dynamic_sidebar( "native-ads-left-second" );
                        else :
                            echo hs_ad_default_image(
                                'square',
                                'object-cover'
                            );
                        endif; 
                    ?>
                </div>
                <?php                 
                get_template_part('template-parts/home-editor-pick');                
                ?>
            </aside>
            <div class="col-span-12 lg:col-span-6 divide-y divide-neutral-200 flex flex-col gap-7  order-1 lg:order-2"> <?php   
                get_template_part('template-parts/home-latest-articles'); 
                ?>
                <div class="flex justify-center items-center">
                    <!-- Native Ad Right Second -->
                    <?php if ( is_active_sidebar( "native-ads-center" ) ) :             
                        dynamic_sidebar( "native-ads-center" );
                        else :
                            echo hs_ad_default_image(
                                'rectangle',
                                'object-cover'
                            );
                        endif; 
                    ?>
                </div>            
            
            </div>
            <aside class="col-span-6 lg:col-span-3 flex flex-col gap-5 order-3">
                <?php  get_template_part('template-parts/weather'); ?>
                <div class="flex justify-center items-center">
                    <!-- Native Ad Right Second -->
                    <?php if ( is_active_sidebar( "native-ads-right-second" ) ) :             
                        dynamic_sidebar( "native-ads-right-second" );
                        else :
                            echo hs_ad_default_image(
                                'square',
                                'object-cover'
                            );
                        endif; 
                    ?>
                </div>
                <?php get_template_part('template-parts/calendar'); ?>
                 <div class="space-y-4">
                    <h3 class="text-left text-lg text-neutral-900 pb-3 border-b border-brand uppercase">
                        <b>Poll</b>
                    </h3>
                    <?php  echo do_shortcode('[poll id="2"]');?>
                  </div>
                 <div class="space-y-4">
                    <h3 class="text-left text-lg text-neutral-900 pb-3 border-b border-brand uppercase">
                        <b>Tags</b>
                    </h3>
                   <?php get_template_part('template-parts/home-category-tags'); ?>  
                  </div>
            </aside>
        </div>
</section>


<?php get_footer(); ?>