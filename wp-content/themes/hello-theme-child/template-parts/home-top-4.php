<!-- Top 4 news -->
<section class="relative">
    <div class="wrapper">

        <?php
                    wp_reset_postdata();

                    // Query latest 4 posts
                    $q = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => 4,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'ignore_sticky_posts' => 1,
                    ]);

                    if ($q->have_posts()):
          ?>

        <div
            class="flex gap-5 overflow-x-auto snap-x snap-mandatory pb-3 lg:overflow-visible lg:grid lg:grid-cols-4 lg:pb-0">

            <?php while ($q->have_posts()): $q->the_post(); ?>
            <?php $cats = get_the_category(); $single_cat = '';
                if (!empty($cats)) {
                    foreach ($cats as $cat) {
                        if ($cat->slug !== 'trending-now') {
                            $single_cat = $cat->name;
                            break;
                        }
                    }
                }
            ?>

            <div class="w-64 snap-start lg:w-auto flex gap-3 shrink-0 bg-neutral-100 p-5">

                <a href="<?php the_permalink(); ?>"
                    class="flex justify-center items-center h-22 w-28 shrink-0 overflow-hidden">

                    <?php if (has_post_thumbnail()):
                        the_post_thumbnail('medium', [
                            'class' => 'object-cover h-full w-auto max-w-none',
                        ]);
                    else:
                        echo hs_get_default_image(
                            'default',
                            'object-cover h-full w-auto max-w-none'
                        );
                    endif; ?>

                </a>

                <div class="space-y-1">
                    <?php if (!empty($single_cat)): ?>
                    <span
                        class="bg-rose-500 text-white inline-flex px-2 pt-1 pb-0.5 leading-none uppercase text-xs font-medium whitespace-nowrap">
                        <?= esc_html($single_cat) ?>
                    </span>
                    <?php endif; ?>

                    <h3 class="text-sm text-left font-semibold">
                        <a href="<?php the_permalink(); ?>" class="no-underline! hover:text-brand transition line-clamp-3 
                                  leading-snug h-15 overflow-hidden pt-1">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; wp_reset_postdata(); ?>
    </div>
</section>