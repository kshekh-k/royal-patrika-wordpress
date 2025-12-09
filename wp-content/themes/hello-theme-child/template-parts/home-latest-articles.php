<?php
// ----------------------------------------------
// CONFIG
// ----------------------------------------------
$categories = ['vichar-lekh', 'editorial'];  // category slugs
$post_limit = 5;

// Convert slugs → category IDs
$cat_ids = [];
foreach ($categories as $slug) {
    $cat_obj = get_category_by_slug($slug);
    if ($cat_obj) {
        $cat_ids[] = $cat_obj->term_id;
    }
}

// WP Query
$q = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => $post_limit,
    'category__in' => $cat_ids,
    'orderby' => 'date',
    'order' => 'DESC',
    'ignore_sticky_posts' => 1,
]);
?>

<?php if ($q->have_posts()): ?>
    <div class="space-y-4">
  <!-- SECTION TITLE -->
    <h3 class="text-left text-lg text-neutral-900 pb-3 border-b border-brand uppercase">
        <b>Latest</b> Articles
    </h3>
<div class="space-y-4">

    <?php while ($q->have_posts()):
        $q->the_post(); ?>

        <?php
        // Category name (first category)
        $cats = get_the_category();
        $cat_name = $cats ? $cats[0]->name : '';

        // Short description – 20–25 words (~3–4 lines)
        $short_desc = wp_trim_words(get_the_excerpt(), 25, '...');
        ?>

        <div class="flex flex-col sm:flex-row gap-4 ">
             <!-- Thumbnail -->
                <a href="<?php the_permalink(); ?>" class="flex justify-center items-center h-60 lg:h-40 sm:w-56 shrink-0 overflow-hidden bg-neutral-100">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('large', [
                                'class' => 'object-cover h-auto min-h-full w-auto min-w-full max-w-none'
                            ]);
                        } else {
                            echo hs_get_default_image(
                                'default',
                                'object-cover h-auto min-h-full w-auto min-w-full max-w-none'
                            );
                        }
                        ?>
                    </a>
           
           
            <!-- Content -->
            <div class="flex-1 space-y-3">
  <!-- Title -->
                <h3 class="text-base font-semibold">
                    <a href="<?php the_permalink(); ?>" class="no-underline! text-neutral-900 hover:text-brand transition line-clamp-2 leading-snug max-h-10.5 overflow-hidden pt-1">
                        <?php the_title(); ?>
                    </a>
                </h3>
                <!-- Category + Date -->
                <div class="flex gap-2 items-center">
                    <?php if ($cat_name): ?>
                        <span class="bg-rose-500 text-white inline-flex px-2 pt-1 pb-0.5 leading-none uppercase text-xs font-medium whitespace-nowrap">
                            <?= esc_html($cat_name); ?>
                        </span>
                    <?php endif; ?>

                    <p class="text-xs text-neutral-400"><?= get_the_date('M d, Y'); ?></p>
                </div>

              

                <!-- Description -->
                <p class="text-sm text-neutral-700 line-clamp-3 overflow-hidden max-h-14">
                    <?= esc_html($short_desc); ?>
                </p>

                  <!-- Read More -->
                   <div class="flex justify-start">
                <a href="<?php the_permalink(); ?>" 
                   class="text-brand hover:text-blue-500 transition font-semibold text-sm no-underline! block">
                    Read More 
                </a>

            </div>
            </div>

        </div>

    <?php endwhile; ?>

</div>
</div>

<?php
endif;
wp_reset_postdata();
?>
