<section class="py-3 md:py-5 lg:py-10">
    <div class="wrapper">

        <div class="flex flex-wrap lg:flex-nowrap gap-3 justify-between items-center">
            <div
                class="bg-neutral-900 text-white relative shrink-0 order-1 after:size-6.5 lg:after:size-7.5 after:top-1/2 after:-translate-y-1/2 after:bg-neutral-900 after:absolute after:right-0 after:rotate-45 after:translate-x-1/2 w-32 lg:w-auto">
                <h2 class="text-sm lg:text-lg whitespace-nowrap px-4 py-2 relative z-10 font-light uppercase"><b
                        class="font-semibold">Trending</b> Now</h2>
            </div>
            <div class="flex gap-2 order-2 lg:order-3">
                <div
                    class="trending-button-prev size-10 rounded-full flex justify-center items-center border border-gray-200 hover:bg-brand hover:border-brand hover:text-white transition cursor-pointer">
                    <i class="fa-solid fa-angle-left"></i>
                </div>
                <div
                    class="trending-button-next size-10 rounded-full flex justify-center items-center border border-gray-200 hover:bg-brand hover:border-brand hover:text-white transition cursor-pointer">
                    <i class="fa-solid fa-angle-right"></i>
                </div>
            </div>
            <div class="swiper trending-now md:ml-5! order-3 lg:order-2 w-full lg:w-auto">
                <?php
                $trending_posts = new WP_Query([
                    'category_name' => 'trending-now',  // category slug
                    'posts_per_page' => 10,
                    'ignore_sticky_posts' => 1,
                ]);
                if ($trending_posts->have_posts()):
                    echo '<div class="swiper-wrapper">';
                    while ($trending_posts->have_posts()):
                        $trending_posts->the_post();
                        echo '<div class="swiper-slide"><a href="'
                            . get_permalink()
                            . '" class="no-underline! text-neutral-900 hover:text-brand transition truncate block font-semibold">'
                            . get_the_title()
                            . '</a></div>';
                    endwhile;
                    echo '</div>';
                else:
                    echo '<p>No trending posts found.</p>';
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>