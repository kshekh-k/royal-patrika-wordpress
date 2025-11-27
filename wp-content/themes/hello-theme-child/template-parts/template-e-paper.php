<?php
// Make sure you have the necessary WordPress functions
if (!defined('ABSPATH')) {
    echo "No Script";
    exit;
}

/*
Template Name: E-paper
*/
get_header(); ?>

<div class="content-wrapper p-2 " id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <aside class="google-ads left">
        <?php if (is_active_sidebar('google-ads-left')) : ?>
            <?php dynamic_sidebar('google-ads-left'); ?>
        <?php endif; ?>
    </aside>
    <section class="rp-e-paper-page">
        <div class="wrapper">

        <?php
        // Get all categories of the custom taxonomy 'epaper_category'
        $epaper_categories = get_terms(array(
            'taxonomy' => 'epaper_category',
            'hide_empty' => false, // Set to true if you only want categories that have posts
        ));

        // Loop through each category
        if (!empty($epaper_categories) && !is_wp_error($epaper_categories)) :
            foreach ($epaper_categories as $epaper_category) :
                $cat_id = $epaper_category->term_id;
                $cat_name = $epaper_category->name;
        ?>
                <div class="rp-e-paper-page-heading">
                    <div class="heading">
                        <h3 class="text-center big-news-title"><?php echo esc_html($cat_name); ?></h3>
                    </div>
                </div>
                <div class="paper-post-content-dainik">
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $args = array(
                        'posts_per_page' => 3,
                        'paged' => $paged,
                        'post_type' => 'epaper',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'epaper_category',
                                'field'    => 'term_id',
                                'terms'    => $cat_id,
                            ),
                        ),
                    );
                    $paper_loop = new WP_Query($args);

                    if ($paper_loop->have_posts()) :
                        while ($paper_loop->have_posts()) : $paper_loop->the_post();
                            $post_link = get_permalink();
                            $post_date = date('j F Y', strtotime(get_the_date()));
                            $post_id = get_the_ID();
                            $post_pdf = get_field('e-paper', $post_id);
                    ?>
                            <div class="col-md-4 mb-3 epaper-box">
                                <div class="epaper_save_icon">
                                    <h6 class="epaper_date"><?php echo esc_html($post_date); ?></h6>
                                    <a class="epaper_button_sec" href="<?php echo esc_url($post_pdf); ?>"><button class="epaper_download"><i class="fa fa-download"></i></button></a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($post_pdf); ?>" target="_blank" class="epaper_button_sec"><button class="epaper_download"><i class="fa fa-facebook"></i></button></a>
                                    <br>
                                    <a href="https://api.whatsapp.com/send?text=Check%20out%20this%20PDF%20<?php echo esc_url($post_pdf); ?>" target="_blank" class="epaper_button_sec"><button class="epaper_download"><i class="fa fa-whatsapp"></i></button></a>
                                    <br>
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url($post_pdf); ?>&text=Check%20out%20this%20PDF" target="_blank" class="epaper_button_sec"><button class="epaper_download"><i class="fa fa-twitter"></i></button></a>
                                </div>
                                <div class="epaper_innner_box">
                                    <div class="epaper_img">
                                        <?php if (has_post_thumbnail()) {
                                            printf('<a href="%s">%s</a>', esc_url($post_link), get_the_post_thumbnail(get_the_ID(), 'large'));
                                        } ?>
                                    </div>
                                    <a title="<?php the_title_attribute(); ?>" href="<?php echo esc_url($post_link); ?>">
                                        <h3 class="epaper_name"><?php the_title(); ?></h3>
                                    </a>
                                </div>
                            </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    ?>
                        <div class="pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $paper_loop->max_num_pages,
                                'current' => $paged,
                                'format' => '?paged=%#%',
                                'show_all' => false,
                                'type' => 'plain',
                                'end_size' => 2,
                                'mid_size' => 1,
                                'prev_next' => true,
                                'prev_text' => sprintf('<i></i> %1$s', __('&laquo;', 'text-domain')),
                                'next_text' => sprintf('%1$s <i></i>', __('&raquo;', 'text-domain')),
                                'add_args' => false,
                                'add_fragment' => '',
                            ));
                            ?>
                        </div>
                    <?php else : ?>
                        <p><?php esc_html_e('No posts found.', 'text-domain'); ?></p>
                    <?php endif; ?>
                </div>
        <?php
            endforeach;
        endif;
        ?>
        </div>
    </section>

    <aside class="google-ads right">
        <?php if (is_active_sidebar('google-ads-right')) : ?>
            <?php dynamic_sidebar('google-ads-right'); ?>
        <?php endif; ?>
    </aside>
</div>

<?php get_footer(); ?>
