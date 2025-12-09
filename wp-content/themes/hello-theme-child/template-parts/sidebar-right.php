<div class="flex flex-col gap-5 ">
                <div class="flex justify-center items-center md:flex-1 [&>.widget]:flex-1 native-ad-wrap">                   
                    <!-- Native Ad Right First -->
                    <?php
                    if (is_active_sidebar('native-ads-right-first')):
                        dynamic_sidebar('native-ads-right-first');
                    else:
                        echo hs_ad_default_image(
                            'square',
                            'object-cover'
                        );
                    endif;
                    ?>                 
                </div>
                     <?php get_template_part('template-parts/top-stories'); ?>
                    <div class="flex justify-between items-center gap-2 border-b border-brand ">
                        <h3 class="text-left text-lg text-neutral-900 uppercase"><b>Top</b>
                            Videos</h3>
                        <a href="https://www.youtube.com/@royalpatrika" target="_blank"
                            class="text-sm font-semibold no-underline! py-2 flex justify-center text-center hover:text-blue-500 text-brand   transition ">
                            View All
                        </a>
                    </div>
                    <?php echo do_shortcode('[youtube_videos count="1" filter="latest" layout="grid"]'); ?>
                
                <?php get_template_part('template-parts/social-stats');
                get_template_part('template-parts/weather');
                get_template_part('template-parts/calendar');
                get_template_part('template-parts/top-categories'); ?> 
                
</div>