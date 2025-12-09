<?php
// =================== CONFIG ===================
$date_filter = [
    'after' => '600 days ago',
    'inclusive' => true,
];

// =================== MAIN QUERY (7 POSTS) ===================
$merged_query = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 7,  // 1 (top) + 6 (below)
    'orderby' => 'rand',
    'date_query' => [$date_filter],
    'ignore_sticky_posts' => 1,
]);
?>

<?php if ($merged_query->have_posts()): ?>

<?php
    // ======================================================
    //                 👉 FIRST (TOP) FEATURED POST
    // ======================================================
    $merged_query->the_post();
    $top_cats = get_the_category();
    $top_cat = $top_cats ? $top_cats[0]->name : '';
    ?>

<div class="pb-7">
    <div class="flex gap-4 flex-col">

        <a href="<?php the_permalink(); ?>" class="w-full max-h-96 flex justify-center items-center overflow-hidden">
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



        <div class="space-y-3 flex-1">

            <h3 class="font-semibold text-xl leading-snug">
                <a href="<?php the_permalink(); ?>"
                    class="no-underline! hover:text-brand text-neutral-900 transition line-clamp-3 leading-snug max-h-15 overflow-hidden pt-1">
                    <?php the_title(); ?>
                </a>
            </h3>

            <div class="flex gap-2 items-center">
                <?php if ($top_cat): ?>
                <span
                    class="bg-rose-500 text-white inline-flex px-3 pt-1.5 pb-1 leading-none uppercase text-sm font-medium whitespace-nowrap">
                    <?= $top_cat ?>
                </span>
                <?php endif; ?>
                <p class="text-sm text-neutral-600"><?= get_the_date() ?></p>
            </div>

            <p class="text-neutral-700 line-clamp-2 max-h-16">
                <?= wp_trim_words(get_the_excerpt(), 18) ?>
            </p>
        </div>

    </div>
</div>

<?php
    // ======================================================
    //      👉 REMAINING 6 POSTS (GRID SECTION)
    // ======================================================
    ?>

<div class="md:pb-7">
    <div class="flex flex-col sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

        <?php while ($merged_query->have_posts()): $merged_query->the_post(); ?>
        <?php
                $cat_list = get_the_category();
                $cat_single = $cat_list ? $cat_list[0]->name : '';
                ?>

        <div class="space-y-2">
            <!-- Image -->
            <a href="<?php the_permalink(); ?>"
                class="flex justify-center items-center w-full h-60 lg:h-28 overflow-hidden bg-neutral-100">
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

            <!-- Title -->
            <h3 class="text-sm font-semibold">
                <a href="<?php the_permalink(); ?>"
                    class="no-underline! text-neutral-900 hover:text-brand transition line-clamp-2 leading-snug max-h-10.5 overflow-hidden pt-1">
                    <?php the_title(); ?>
                </a>
            </h3>

            <p class="text-neutral-500"><?= get_the_date() ?></p>
        </div>

        <?php endwhile; ?>

    </div>
</div>

<?php endif; ?>

<?php wp_reset_postdata(); ?>