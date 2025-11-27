<?php
get_header();
global $post;
$Page_ID = isset($post) && !empty($post) ? $post->ID : null;

if (!defined('ABSPATH')) {
    echo "No Script";
    exit;
}
?>

<div class="content-wrapper p-2">
    <aside class="google-ads left" id="google-ads-left">
        <?php if (is_active_sidebar('google-ads-left')) : ?>
            <?php dynamic_sidebar('google-ads-left'); ?>
        <?php endif; ?>
    </aside>
    
    <section class="homenews-page">
        <div class="wrapper" itemscope itemtype="https://schema.org/CollectionPage">
            <div class="breadcrumb-sec">
                <?php echo do_shortcode('[wpseo_breadcrumb]'); ?>
            </div>
            <div class="post-content row">
                <div class="heading">
                    <h1 class="text-center big-news-title" itemprop="headline"><?php single_cat_title(); ?></h1>
                </div>
                <div class="col-xs-12 col-md-12 post-content-sec">
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $cat_id = get_queried_object_id();
                    $args = array(
                        'posts_per_page' => 4,
                        'post_type' => 'post',
                        'paged' => $paged,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'category',
                                'field'    => 'term_id',
                                'terms'    => $cat_id,
                            ),
                        ),
                    );
                    $big_news_query = new WP_Query($args);

                    if ($big_news_query->have_posts()) : ?>
                        <div class="big-news-slider">
                            <?php
                            $post_count = 0;
                            while ($big_news_query->have_posts()) : $big_news_query->the_post();
                                $post_count++;
                                ?>
                                <?php if ($post_count == 1) : ?>
                                    <article class="col-sm-6 first-big-news-slide" itemscope itemtype="https://schema.org/NewsArticle">
                                        <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>" itemprop="url">
                                            <h2 class="post-title" id="post-<?php the_ID(); ?>" itemprop="headline"><?php the_title(); ?></h2>
                                        </a>
                                        <div class="news-meta">
                                            <?php $post_id = get_the_ID(); ?>
                                            <?php $post_location = get_field('posts_location', $post_id); ?>                
                                            <ul class="news-postmeta">
                                                <?php if (!empty($post_location)) : ?>
                                                    <li class="post_location"><span><?php echo esc_html($post_location); ?></span></li>
                                                <?php endif; ?>
                                                <li class="published-date"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished"><?php the_time('F jS, Y g:i a'); ?></time></li>
                                            </ul>                   
                                        </div>
                                        <p class="post content" itemprop="description"><?php the_excerpt(); ?></p>
                                        <div class="big-news-image">
                                            <a href="<?php the_permalink(); ?>" aria-label="Read more: <?php the_title_attribute(); ?>" itemprop="url">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('full', array('alt' => esc_attr(get_the_title()), 'itemprop' => 'image')); ?>
                                                <?php else : ?>
                                                    <img src="https://royalpatrika.com/wp-content/uploads/2024/07/no-image.png" alt="<?php the_title_attribute(); ?>" itemprop="image" />
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    </article>
                                <?php else : ?>
                                    <?php if ($post_count == 2) : ?>
                                        <div class="col-sm-6 other-big-news-slide">
                                    <?php endif; ?>
                                    <article class="other-big-news-item" itemscope itemtype="https://schema.org/NewsArticle">
                                        <div class="big-news-image">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>" itemprop="url">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('medium', array('alt' => esc_attr(get_the_title()), 'itemprop' => 'image')); ?>
                                                <?php else : ?>
                                                    <img src="https://royalpatrika.com/wp-content/uploads/2024/07/no-image.png" alt="<?php the_title_attribute(); ?>" itemprop="image" />
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                        <div class="big-news-other-content">
                                            <a href="<?php the_permalink(); ?>" aria-label="Read more about <?php the_title_attribute(); ?>" itemprop="url">
                                                <h2 class="post-title" id="post-id-<?php the_ID(); ?>" itemprop="headline"><?php the_title(); ?></h2>
                                            </a>
                                            <div class="news-meta">
                                                <?php $post_location = get_field('posts_location', get_the_ID()); ?>
                                                <ul class="news-postmeta">
                                                    <?php if (!empty($post_location)) : ?>
                                                        <li class="post_location"><span><?php echo esc_html($post_location); ?></span></li>
                                                    <?php endif; ?>
                                                    <li class="published-date"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished"><?php the_time('F jS, Y g:i a'); ?></time></li>
                                                </ul>
                                            </div>
                                            <p class="post content" itemprop="description"><?php echo get_the_excerpt(); ?></p>
                                        </div>
                                    </article>
                                    <?php if ($post_count == $big_news_query->post_count) : ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endwhile;
                            wp_reset_postdata(); ?>
                        </div>
                        <div class="pagination" role="navigation" aria-label="Pagination Navigation">
                            <?php
                            echo paginate_links(array(
                                'total'   => $big_news_query->max_num_pages,
                                'current' => $paged,
                                'prev_text' => __('← Prev'),
                                'next_text' => __('Next →'),
                            ));
                            ?>
                        </div>
                    <?php else : ?>
                        <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <aside class="google-ads right" id="google-ads-right">
        <?php if (is_active_sidebar('google-ads-right')) : ?>
            <?php dynamic_sidebar('google-ads-right'); ?>
        <?php endif; ?>
    </aside>
</div>

<?php get_footer(); ?>
