 <div class="swiper home-top-10-news max-w-full">
     <div class="swiper-wrapper">
         <?php
            $featured_posts = get_option('sticky_posts');
            if (!empty($featured_posts)):
                $slider = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => 10,
                    'post__in' => $featured_posts,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'offset' => 2,
                    'ignore_sticky_posts' => 1,
                ]);

                if ($slider->have_posts()):
                    while ($slider->have_posts()):
                        $slider->the_post();

                        $cats = get_the_category();
                        $single_cat = '';

                        if (!empty($cats)) {
                            foreach ($cats as $cat) {
                                if ($cat->slug !== 'trending-now') {
                                    $single_cat = $cat->name;
                                    break;
                                }
                            }
                        }
            ?>

         <div class="swiper-slide relative">
             <div class="relative overflow-hidden flex items-stretch bg-neutral-200">
                 <a href="<?php the_permalink(); ?>"
                     class="flex justify-center items-center flex-1 overflow-hidden h-75 md:h-152">
                     <?php
            if (has_post_thumbnail()):
                the_post_thumbnail('large', [
                    'class' => 'object-center object-cover size-auto min-w-full min-h-full max-w-none',
                ]);
            else:
                echo hs_get_default_image(
                    'default',
                    'object-center object-cover size-auto min-w-full min-h-full max-w-none'
                );
            endif;
            ?>
                 </a>

                 <div
                     class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent p-5 pb-8 md:p-7 flex items-end">
                     <div class="relative flex-1">
                         <?php if (!empty($single_cat)): ?>
                         <span
                             class="text-xs bg-brand text-white px-2 pt-1 pb-0.5 leading-none uppercase font-medium whitespace-nowrap">
                             <?= esc_html($single_cat) ?>
                         </span>
                         <?php endif; ?>

                         <h2 class="text-white text-lg font-semibold mt-2">
                             <a href="<?php the_permalink(); ?>"
                                 class="no-underline! text-white hover:text-rose-200 transition line-clamp-2 leading-snug max-h-12 overflow-hidden block">
                                 <?php the_title(); ?>
                             </a>
                         </h2>
                         <p class="text-neutral-200 text-xs mt-1"><?php echo get_the_date(); ?>
                         </p>
                     </div>
                 </div>
             </div>
         </div>
         <?php endwhile; wp_reset_postdata(); endif; endif; ?>
     </div>
     <!-- Swiper pagination + arrows -->
     <div class="swiper-pagination"></div>
 </div>