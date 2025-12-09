<div class="space-y-4">

    <!-- SECTION TITLE -->
    <h3 class="text-left text-lg text-neutral-900 pb-3 border-b border-brand uppercase">
        <b>Tech &</b> Innovation
    </h3>

    <!-- TOP IMAGE -->
    <div class="flex flex-col gap-1">


        <?php
        // Categories
        $categories = ['technology', 'business-news'];  
        $count = 3;

        // Convert category slugs to IDs
        $cat_ids = [];
        foreach ($categories as $slug) {
            $cat = get_category_by_slug($slug);
            if ($cat) {
                $cat_ids[] = $cat->term_id;
            }
        }

        // WP Query
        $q = new WP_Query([
            'post_type'           => 'post',
            'posts_per_page'      => $count,
            'category__in'        => $cat_ids,
            'ignore_sticky_posts' => 1,
        ]);
    ?>

        <?php if ($q->have_posts()): ?>
        <div class="space-y-4 divide-y divide-neutral-200">

            <?php 
        $post_index = 0; 
        while ($q->have_posts()):  
            $q->the_post();  
            $post_index++;  
            $cats = get_the_category();
            $single_cat = $cats ? $cats[0]->name : '';
        ?>

            <?php if ($post_index === 1): ?>

            <!-- FIRST POST TEMPLATE -->            
            <a href="<?php the_permalink(); ?>"
                class="flex justify-center items-center w-full h-60 lg:h-40 overflow-hidden bg-neutral-100">
                               
                    <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('large', [
                                'class' => 'object-center object-cover size-auto min-w-full min-h-full max-w-none'
                            ]);
                        } else {
                            echo hs_get_default_image(
                                'default',
                                'object-center object-cover size-auto min-w-full min-h-full max-w-none'
                            );
                        }
                        ?>
            </a>
         
            <div class="space-y-2 pb-2">
 
                <h4 class="text-sm font-semibold text-neutral-900 leading-tight">
                    <a href="<?php the_permalink(); ?>" class="no-underline! hover:text-brand transition pt-1">
                        <?php the_title(); ?>
                    </a>
                </h4>


                <div class="flex gap-2 items-center">
                    <?php if ($single_cat): ?>
                    <span
                        class="bg-rose-500 text-white inline-flex px-3 pt-1 pb-1 leading-none uppercase text-xs font-medium">
                        <?= esc_html($single_cat) ?>
                    </span>
                    <?php endif; ?>
                    <p class="text-xs text-neutral-400">
                        <?= get_the_date() ?>
                    </p>
                </div>

                <p class="text-sm text-neutral-700 line-clamp-2 overflow-hidden max-h-12" >
                    <?= wp_strip_all_tags(get_the_excerpt()) ?>
                </p>
            </div>

            <?php else: ?>

            <!-- OTHER POSTS TEMPLATE -->
            <div class="space-y-2 pb-4">

                <h4 class="text-sm font-semibold text-neutral-900">
                    <a href="<?php the_permalink(); ?>"
                        class="hover:text-brand transition no-underline! line-clamp-2 leading-snug pt-1">
                        <?php the_title(); ?>
                    </a>
                </h4>



                <p class="text-xs text-neutral-400">
                    <?= get_the_date() ?>
                </p>


                <p class="text-sm text-neutral-700 line-clamp-2 overflow-hidden max-h-8.5">
                    <?= wp_strip_all_tags(get_the_excerpt()) ?>
                </p>

            </div>

            <?php endif; ?>

            <?php endwhile; ?>

        </div>
        <?php endif; wp_reset_postdata(); ?>

    </div>