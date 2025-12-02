 <div class="flex justify-center items-center pt-3">
     <!-- Native Ad left First -->
     <?php if ( is_active_sidebar( "native-ads-left-first" ) ) :             
            dynamic_sidebar( "native-ads-left-first" );
            else :
                echo hs_ad_default_image(
                    'square',
                    'object-cover'
                );
            endif; 
         ?>
 </div>

 <div x-data="{ tab: 'mostview' }" class="pt-3 ">

     <!-- TAB BUTTONS -->
     <div class="flex items-center gap-4 text-sm">
         <button @click="tab = 'mostview'" :class="tab === 'mostview' ? 'text-white bg-blue-900' : 'text-neutral-500'"
             class="px-4 py-2 truncate font-semibold cursor-pointer flex-1">
             Most Viewed
         </button>

         <button @click="tab = 'popular'" :class="tab === 'popular' ? 'text-white bg-blue-900' : 'text-neutral-500'"
             class="px-4 py-2 truncate font-semibold cursor-pointer flex-1">
             Popular News
         </button>
     </div>


     <!-- ========================= -->
     <!-- MOST VIEWED TAB CONTENT -->
     <!-- ========================= -->
     <div x-show="tab === 'mostview'" x-transition class="space-y-4 divide-y divide-neutral-200 pt-5">

         <?php
                        // Get Most Viewed Posts → requires meta key: post_views_count
                        $most_viewed = new WP_Query([
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'ignore_sticky_posts' => 1,
                        ]);
                        if ($most_viewed->have_posts()):
                            $i = 1;
                            // 🔥 Start index number
                            ?>


         <?php while ($most_viewed->have_posts()):
                            $most_viewed->the_post(); ?>

         <?php
                        $cats = get_the_category();
                        $single_cat = $cats ? $cats[0]->name : '';
                        ?>
         <div class="flex gap-2 items-center pb-4 last:pb-0 divide-x divide-neutral-200">
             <span
                 class="text-7xl font-garamond font-normal text-neutral-500 leading-none size-12 text-left shrink-0 flex justify-center items-center">
                 <span class="leading-none -translate-y-1/6"><?= $i ?></span>
             </span>

             <h3 class="text-sm text-left font-semibold pl-3">
                 <a href="<?php the_permalink(); ?>"
                     class="hover:text-brand text-neutral-900 transition line-clamp-2 pt-1 no-underline! leading-snug overflow-hidden h-10">
                     <?php the_title(); ?>
                 </a>
             </h3>
         </div>
         <?php $i++; endwhile; endif; wp_reset_postdata(); ?>
     </div>


     <!-- ========================= -->
     <!-- POPULAR NEWS TAB CONTENT -->
     <!-- ========================= -->
     <div x-show="tab === 'popular'" x-transition class="space-y-4 divide-y divide-neutral-200 pt-5">

         <?php
                        // Popular → based on number of comments
                        $popular_posts = new WP_Query([
                            'post_type' => 'post',
                            'posts_per_page' => 2,
                            'orderby' => 'comment_count',
                            'order' => 'DESC',
                            'ignore_sticky_posts' => 1,
                        ]);
                        if ($popular_posts->have_posts()):
                            ?>


         <?php while ($popular_posts->have_posts()):
                            $popular_posts->the_post(); ?>

         <?php
                        $cats = get_the_category();
                        $single_cat = $cats ? $cats[0]->name : '';
                        ?>

         <div class="flex gap-3 pb-4 last:pb-0">
             <a href="<?php the_permalink(); ?>"
                 class="flex justify-center items-center h-22 w-28 shrink-0 overflow-hidden ">
                 <?php if (has_post_thumbnail()):
                                the_post_thumbnail('medium', [
                                    'class' =>
                                        'object-cover h-full w-auto max-w-none',
                                ]);
                            else:
                                echo hs_get_default_image(
                                    'default',
                                    'object-cover h-full w-auto max-w-none'
                                );
                            endif; ?>
             </a>
             <div class="space-y-1">
                 <h3 class="text-sm text-left font-semibold">
                     <a href="<?php the_permalink(); ?>"
                         class="no-underline! hover:text-brand text-neutral-900 transition line-clamp-3 leading-snug h-15 overflow-hidden pt-1">
                         <?php the_title(); ?>
                     </a>
                 </h3>

                 <div class="flex gap-2 items-center">
                     <?php if ($single_cat): ?>
                     <span
                         class="bg-blue-900 text-white inline-flex px-3 pt-1.5 pb-1 leading-none uppercase text-xs font-medium whitespace-nowrap">
                         <?= esc_html($single_cat) ?>
                     </span>
                     <?php endif; ?>
                     <p class="text-xs text-neutral-400"><?= get_the_date() ?></p>
                 </div>
             </div>
         </div>
         <?php endwhile; endif; wp_reset_postdata(); ?>
     </div>



 </div>