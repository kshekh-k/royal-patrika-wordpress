 <div class="space-y-4">
     <h3 class="text-left text-lg text-neutral-900 pb-3 border-b border-brand uppercase"><b>Top</b>
         Stories</h3>

     <?php
                    $categories = ['top-news', 'trending-now'];   
                    $count = 3; 

                   
                    $cat_ids = [];
                    foreach ($categories as $slug) {
                        $cat = get_category_by_slug($slug);
                        if ($cat) {
                            $cat_ids[] = $cat->term_id;
                        }
                    }
                    
                    $q = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => $count,
                        'category__in' => $cat_ids,
                        'ignore_sticky_posts' => 1,
                    ]);
                    if ($q->have_posts()):
                        ?>
     <div class="space-y-4 divide-y divide-neutral-200">
         <?php while ($q->have_posts()):
                            $q->the_post(); ?>

         <?php
                        $cats = get_the_category();
                        $single_cat = $cats ? $cats[0]->name : '';
                        ?>

         <div class="space-y-2 pb-4 last:pb-0">

             <h4 class="text-sm text-left font-semibold">
                 <a href="<?php the_permalink(); ?>"
                     class="hover:text-brand text-neutral-900 transition line-clamp-2 pt-1 no-underline! leading-snug overflow-hidden">
                     <?php the_title(); ?>
                 </a>
             </h4>

             <div class="flex gap-2 items-center">

                 <?php if ($single_cat): ?>
                 <span
                     class="bg-blue-900 text-white inline-flex px-3 pt-1.5 pb-1 leading-none uppercase text-xs font-medium whitespace-nowrap">
                     <?= esc_html($single_cat) ?>
                 </span>
                 <?php endif; ?>

                 <p class="text-xs text-neutral-400">
                     <?= get_the_date() ?>
                 </p>
             </div>

             <p class="text-sm text-neutral-700 line-clamp-2 overflow-hidden max-h-8.5">
                 <?= wp_strip_all_tags(get_the_excerpt()) ?>
             </p>

         </div>

         <?php  endwhile;  ?>
     </div>
     <?php endif; wp_reset_postdata(); ?>

 </div>