<?php
// Make sure you have the necessary WordPress functions
if (!defined('ABSPATH')) {
    echo "No Script";
    exit;
}

get_header(); 
global $post;
$current_id = $post->ID;
$post_link = get_permalink();
$post_location = get_field('posts_location',$current_id);
$postCategories = '';
// Outputs: https://example.com/मेरा-पोस्ट/
// echo "<pre>";
// print_r($post_location);
// echo "</pre>";

?>

<div class="wrapper py-5 ">



    <div class="flex flex-col md:grid md:grid-cols-12 gap-10">
        <section class="single-news md:col-span-8 lg:col-span-9 ">
            <div class="prose lg:prose-lg max-w-none! font-medium text-neutral-700 lg:leading-loose leading-loose">
                <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('news-article'); ?>>
                    <header class="flex flex-col gap-3">
                        <h1 class="text-xl xl:text-2xl 2xl:text-3xl font-semibold mb-0!"><?php the_title(); ?></h1>
                        <?php $postCategories = get_the_category(); ?>
                        <div class="flex flex-wrap gap-2 items-center">

                            <span
                                class="bg-rose-500 text-white inline-flex px-3 pt-1.5 pb-1 leading-none uppercase text-sm font-medium whitespace-nowrap">
                                <?php echo $post_location ;?></span>


                            <p class="text-sm text-neutral-600 flex gap-3 items-center flex-wrap my-0!">
                                <span id="news-date" class="inline-flex gap-1 items-center"><i
                                        class="fa-regular fa-calendar"></i><time datetime="<?= get_the_date('c') ?>"
                                        itemprop="datePublished"><?= get_the_date() ?></time></span>
                                <span id="news-time" class="inline-flex gap-1 items-center"><i
                                        class="fa-regular fa-clock"></i><time datetime="<?= get_the_time('c') ?>"
                                        itemprop="datePublished"><?= get_the_time() ?></time></span>
                                <span id="news-author"
                                    class="[&>a]:no-underline! [&>a]:text-neutral-600! [&>a]:hover:text-brand! inline-flex gap-1 items-center"><i
                                        class="fa-regular fa-user"></i> <?php the_author_posts_link() ?>
                                </span>
                            </p>


                        </div>

                    </header>
                    <div class="w-full max-h-120 flex justify-center items-center overflow-hidden mt-5">
                        <?php
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('large', [
                                        'class' => 'object-cover h-auto min-h-full w-auto min-w-full max-w-none my-0!'
                                    ]);
                                } else {
                                    echo hs_get_default_image(
                                        'default',
                                        'object-cover h-auto min-h-full w-auto min-w-full max-w-none my-0!'
                                    );
                                }
                            ?>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between gap-2 pt-2">
                        <div class="flex flex-col sm:flex-row gap-1 sm:gap-3 items-center">
                            <p class="text-base! my-0!">Follow us</p>
                            <div class="flex gap-0.5 sm:gap-2 justify-center md:justify-start">
                                <a class="rounded-sm text-white transition hover:opacity-85 bg-blue-700 no-underline! sm:size-7 size-6 inline-flex justify-center items-center ease-in-out duration-200"
                                    href="https://www.facebook.com/royalpatrika/" target="_blank"><i
                                        class="text-sm md:text-base fa fa-facebook"></i></a>

                                <a class="rounded-sm text-white transition hover:opacity-85 bg-linear-to-tr from-yellow-400 via-pink-500 to-purple-600 no-underline! sm:size-7 size-6 inline-flex justify-center items-center ease-in-out duration-200"
                                    href="https://www.instagram.com/royalpatrikaofficial?igsh=cjhzdTg0dzV5Mnlx"
                                    target="_blank" data-action="share/whatsapp/share"><i
                                        class="text-sm md:text-base fa fa-instagram"></i></a>
                                <a class="rounded-sm text-white transition hover:opacity-85 bg-neutral-900 no-underline! sm:size-7 size-6 inline-flex justify-center items-center ease-in-out duration-200"
                                    href="https://x.com/PatrikaRoyal" target="_blank"><i
                                        class="text-sm md:text-base fa-brands fa-x-twitter"></i></a>

                                <a class="rounded-sm text-white transition hover:opacity-85 bg-rose-700 no-underline! sm:size-7 size-6 inline-flex justify-center items-center ease-in-out duration-200 "
                                    href="https://www.youtube.com/@royalpatrika"><i
                                        class="text-sm md:text-base fa-brands fa-youtube"></i></a>

                                <a class="rounded-sm text-white transition hover:opacity-85 bg-green-500 no-underline! sm:size-7 size-5 inline-flex justify-center items-center ease-in-out duration-200"
                                    href="<?php echo 'https://wa.me/?text=' . rawurlencode(get_the_title()) . ' ' . createFriendlyURL(get_permalink()); ?>"
                                    target="_blank" data-action="share/whatsapp/share"><i
                                        class="text-sm md:text-base fa-brands fa-whatsapp"></i></a>

                                <a class="rounded-sm text-white transition hover:opacity-85 bg-blue-500 no-underline! sm:size-7 size-6 inline-flex justify-center items-center ease-in-out duration-200"
                                    href="https://t.me/royal_patrika_news" target="_blank"
                                    data-action="share/telegram/share"><i
                                        class="text-sm md:text-base fa-brands fa-telegram"></i></a>


                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-1 sm:gap-3 items-center">
                            <p class="text-base! my-0!">Share</p>
                            <div class="flex sm:gap-2 gap-1 justify-center md:justify-start">
                                <a class="hover:text-white hover:bg-brand text-brand bg-brand/10 no-underline! sm:size-7 size-6 rounded-full inline-flex justify-center items-center ease-in-out duration-200"
                                    href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>"
                                    target="_blank"><i class="text-sm md:text-base fa fa-facebook"></i></a>
                                <a class="hover:text-white hover:bg-brand text-brand bg-brand/10 no-underline! sm:size-7 size-6 rounded-full inline-flex justify-center items-center ease-in-out duration-200"
                                    href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode(get_permalink()); ?>&text=<?php echo rawurlencode(get_the_title()); ?>&hashtags=yourhashtags"
                                    target="_blank"><i class="text-sm md:text-base fa-brands fa-x-twitter"></i></a>
                                <a class="hover:text-white hover:bg-brand text-brand bg-brand/10 no-underline! sm:size-7 size-6 rounded-full inline-flex justify-center items-center ease-in-out duration-200"
                                    href="<?php echo 'https://wa.me/?text=' . rawurlencode(get_the_title()) . ' ' . createFriendlyURL(get_permalink()); ?>"
                                    target="_blank" data-action="share/whatsapp/share"><i
                                        class="text-sm md:text-base fa-brands fa-whatsapp"></i></a>
                                <a class="hover:text-white hover:bg-brand text-brand bg-brand/10 no-underline! sm:size-7 size-5 rounded-full inline-flex justify-center items-center ease-in-out duration-200"
                                    href="<?php echo 'https://t.me/share/url?url=' . rawurlencode(get_permalink()) . '&text=' . rawurlencode(get_the_title());?>"
                                    target="_blank" data-action="share/whatsapp/share"><i
                                        class="text-sm md:text-base fa-brands fa-telegram"></i></a>
                                <a class="hover:text-white hover:bg-brand text-brand bg-brand/10 no-underline! sm:size-7 size-5 rounded-full inline-flex justify-center items-center ease-in-out duration-200 "
                                    data-toggle="tooltip" title="Copy to Clipboard"
                                    href="<?php echo createFriendlyURL(get_permalink()); ?>"><i
                                        class="text-sm md:text-base fas fa fa-copy"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="news-content">
                        <?php
                        $content = apply_filters('the_content', get_the_content());
                        $acf_image = get_field('mobile_middle_ad'); // ACF image field (returns array or URL)

                        if (wp_is_mobile()) {
                            $paragraphs = explode('</p>', $content);
                            $middle = floor(count($paragraphs) / 2);

                            foreach ($paragraphs as $index => $paragraph) {
                                if (trim($paragraph)) {
                                    echo $paragraph . '</p>';
                                }

                                if ($index == $middle) {
                                    echo '<div class="mobile-inline-ad">';

                                    if ($acf_image) {
                                        // If the field returns an array
                                        $image_url = is_array($acf_image) ? $acf_image['url'] : $acf_image;
                                        echo '<img src="' . esc_url($image_url) . '" alt="Mobile Ad" style="max-width:100%; height:auto;">';
                                    } else {
                                        // Fallback: Google AdSense
                                        ?>
                        <ins class="adsbygoogle" style="display:block; text-align:center;"
                            data-ad-client="ca-pub-XXXXXXX" data-ad-slot="YYYYYYY" data-ad-format="auto"
                            data-full-width-responsive="true"></ins>
                        <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                        <?php
                                    }

                                    echo '</div>';
                                }
                            }
                        } else {
                            echo $content;
                        }
                    ?>
                    </div>


                </article>
                <?php endwhile; ?>
            </div>
            <div id="related-posts-container" class="eleven columns alpha omega pt-5 lg:pt-10">


                <h2 class="text-left text-lg text-neutral-900 uppercase border-b border-brand"><b>संबंधित</b>
                    खबरें</h2>

                <div class="grid grid-cols-3 gap-5 pt-5 related-posts-list">
                    <?php
                        // Get the categories of the current post
                        // $postCategories = get_the_category();
                        $postCatIds = '';

                        // Construct the category IDs string
                        foreach($postCategories as $catIndex => $catValue) {
                            $postCatIds .= $catValue->cat_ID;
                            if ($catIndex < (count($postCategories) - 1)) {
                                $postCatIds .= ', ';
                            }
                        }

                        // Query the posts based on the categories
                        query_posts(array(
                            'cat' => $postCatIds,
                            'posts_per_page' => 3
                        ));

                        if (have_posts()) :
                            while (have_posts()) : the_post(); ?>
                    <div class="space-y-2">
                        <a href="<?php the_permalink(); ?>" rel="bookmark"
                            title="Permanent Link to <?php the_title_attribute(); ?>"
                            class="flex justify-center items-center h-60 overflow-hidden bg-neutral-100">
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


                           <div class="space-y-2">
                                <h3 class="text-sm font-semibold"> <a href="<?php the_permalink(); ?>"
                                    class="hover:text-brand transition no-underline! leading-snug line-clamp-2 block max-h-10.5 overflow-hidden pt-1"><?php the_title(); ?> </a></h3>
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
                      
                    </div>
                    <?php endwhile;
                        endif;
                        wp_reset_query();
                        ?>
                </div>
            </div>
        </section>
        <aside class="col-span-12 md:col-span-4 lg:col-span-3 single-post-ads">
            <?php get_template_part('template-parts/sidebar-right'); ?>
        </aside>
    </div>



</div>

<?php get_footer(); ?>