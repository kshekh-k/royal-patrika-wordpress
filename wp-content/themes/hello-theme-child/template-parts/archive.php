<?php
/* Category Template */
defined('ABSPATH') || exit;

get_header();

global $post;
$page_id = isset($post->ID) ? $post->ID : null;
?>

<div class="wrapper lg:py-5">
    <!-- Page Heading -->
    <div class="bg-linear-to-r from-rose-100 to-rose-700 p-5 lg:p-10 flex justify-center items-center">
        <h1 class="text-2xl lg:text-3xl font-bold text-white" itemprop="headline">
            <?php single_cat_title(); ?>
        </h1>
    </div>

    <div class="grid grid-cols-12 gap-5 lg:gap-10 pt-5" itemscope itemtype="https://schema.org/CollectionPage">
        <!-- MAIN CONTENT -->
        <section class="col-span-12 md:col-span-8 lg:col-span-9">
            <!-- Breadcrumbs -->
            <div class="flex flex-wrap gap-2 items-center text-sm text-neutral-600 border-b border-neutral-200 pb-2 bradcrumbs"
                itemprop="breadcrumb" aria-label="Breadcrumb">
                <?php echo do_shortcode('[wpseo_breadcrumb]'); ?>
            </div>

            <!-- Posts -->
            <div class="pt-5">
                <?php
                $paged  = max(1, get_query_var('paged'));
                $cat_id = get_queried_object_id();

                $query = new WP_Query([
                    'post_type'      => 'post',
                    'posts_per_page' => 10,
                    'paged'          => $paged,
                    'tax_query'      => [
                        [
                            'taxonomy' => 'category',
                            'field'    => 'term_id',
                            'terms'    => $cat_id,
                        ],
                    ],
                ]);

                if ($query->have_posts()) :
                    $post_count  = 0;
                    $total_posts = $query->post_count;
                ?>

                <div class="flex flex-col sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ($query->have_posts()) : $query->the_post();
                        $post_count++;
                    ?>

                    <!-- FIRST LARGE POST -->
                    <?php if ($post_count === 1) : ?>
                    <article class="sm:col-span-2 lg:col-span-3 border-b border-neutral-200 pb-4 flex flex-col gap-4"
                        itemscope itemtype="https://schema.org/NewsArticle">

                        <h2 class="text-xl xl:text-2xl font-semibold " itemprop="headline">
                            <a href="<?php the_permalink(); ?>"
                                class="no-underline! hover:text-brand text-neutral-900 transition line-clamp-3 leading-snug max-h-16.5 overflow-hidden py-1"
                                itemprop="url"><?php the_title(); ?></a>
                        </h2>

                        <div class="flex gap-2 items-center"> <?php $post_id = get_the_ID(); ?>
                            <?php $post_location = get_field('posts_location', $post_id); ?>
                            <?php if (!empty($post_location)) : ?> <span
                                class="bg-rose-500 text-white inline-flex px-3 pt-1.5 pb-1 leading-none uppercase text-sm font-medium whitespace-nowrap"><?php echo esc_html($post_location); ?></span>
                            <?php endif; ?>
                            <p class="text-sm text-neutral-600 flex gap-2 items-center"> <span
                                    class="inline-flex gap-1 items-center"><i class="fa-regular fa-calendar"></i><time
                                        datetime="<?= get_the_date('c') ?>"
                                        itemprop="datePublished"><?= get_the_date() ?></time></span> <span
                                    class="inline-flex gap-1 items-center"><i class="fa-regular fa-clock"></i><time
                                        datetime="<?= get_the_time('c') ?>"
                                        itemprop="datePublished"><?= get_the_time() ?></time></span> </p>
                        </div>

                        <a href="<?php the_permalink(); ?>"
                            class="w-full max-h-120 flex justify-center items-center overflow-hidden">
                            <?php
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('full', [
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

                        <p class="text-neutral-700 " itemprop="description">
                            <span
                                class="inline line-clamp-2 max-h-14.5"><?php echo wp_trim_words(get_the_excerpt(), 70); ?></span>
                            <a href="<?php the_permalink(); ?>"
                                class="text-brand hover:text-blue-500 font-semibold text-sm no-underline! inline-flex gap-x-0.5 items-center ml-1">
                                Read More <i class="fa-solid fa-caret-right"></i>
                            </a>
                        </p>


                    </article>

                    <!-- NORMAL POSTS -->
                    <?php else : ?>
                    <article itemscope itemtype="https://schema.org/NewsArticle">
                        <a href="<?php the_permalink(); ?>"
                            class="flex justify-center items-center h-60 overflow-hidden bg-neutral-100 mb-2"
                            itemprop="url">
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
                        <div class="space-y-2">
                            <h2 class="text-sm font-semibold" itemprop="headline">
                                <a href="<?php the_permalink(); ?>"
                                    class="hover:text-brand transition no-underline! leading-snug line-clamp-2 block max-h-10.5 overflow-hidden pt-1">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="flex gap-2 items-center">
                                <?php $post_id = get_the_ID(); ?>
                                <?php $post_location = get_field('posts_location', $post_id); ?>
                                <?php if (!empty($post_location)) : ?> <span
                                    class="bg-rose-500 text-white inline-flex px-3 pt-1.5 pb-1 leading-none uppercase text-sm font-medium whitespace-nowrap"><?php echo esc_html($post_location); ?></span>
                                <?php endif; ?>
                                <time class="text-xs text-neutral-500" datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                            </div>
                            <p class="text-neutral-700 line-clamp-2 text-sm max-h-11 overflow-hidden py-1.5"
                                itemprop="description">
                                <?php echo wp_trim_words(get_the_excerpt(), 70); ?>
                            </p>
                        </div>
                    </article>
                    <?php endif; ?>

                    <?php
// Insert ad after every 3 posts
if ($post_count % 3 === 0 && $post_count < $total_posts) :

    $ad_index++;

    // Define ad slots in order
    $ad_slots = [
        1 => 'native-cat-page-ad-1',
        2 => 'native-cat-page-ad-2',
        3 => 'native-cat-page-ad-3',
        4 => 'native-cat-page-ad-4',
    ];

    // Pick sidebar based on position
    $current_ad_sidebar = $ad_slots[$ad_index] ?? null;
?>
                    <div class="flex justify-center items-start md:flex-1 [&>.widget]:flex-1 native-ad-wrap">
                        <?php
        if ($current_ad_sidebar && is_active_sidebar($current_ad_sidebar)) {
            dynamic_sidebar($current_ad_sidebar);
        } else {
            // Fallback image
            echo hs_ad_default_image('square', 'object-cover');
        }
        ?>
                    </div>
                    <?php endif; ?>


                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center gap-1 list-none pt-4 md:pt-8 lg:pt-14">
                    <?php
                    echo paginate_links([
                        'total'      => $query->max_num_pages,
                        'current'    => $paged,
                        'prev_text'  => '',
                        'next_text'  => '',
                    ]);
                    ?>
                </div>

                <?php else : ?>
                <p class="text-neutral-600">No posts found in this category.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- SIDEBAR -->
        <aside class="col-span-12 md:col-span-4 lg:col-span-3">
            <?php get_template_part('template-parts/sidebar-right'); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>