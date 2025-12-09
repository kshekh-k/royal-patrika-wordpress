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

 
     
    <section class="py-5" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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
           
                  
                        <h3 class="text-left text-lg font-bold lg:text-xl text-neutral-900 uppercase border-b border-brand"><?php echo esc_html($cat_name); ?></h3>
                  
              
                <div class="flex flex-col md:grid md:grid-cols-2 lg:grid-cols-4 gap-5 pt-5">
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $args = array(
                        'posts_per_page' => 4,
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
                            <div class="group epaper-box border border-neutral-200 bg-white p-3 relative transition hover:shadow-1">
                                <div class="bg-brand epaper_top_right text-white p-2 flex justify-between items-center gap-5">
                                    <h6 class="text-sm"><?php echo esc_html($post_date); ?></h6>
                                    <div class="flex gap-1 flex-1 justify-between items-center">
                                    <a class="text-sm" href="<?php echo esc_url($post_pdf); ?>"><button class="epaper_download"><i class="fa fa-download"></i></button></a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($post_pdf); ?>" target="_blank" class="epaper_button_sec"><button class="epaper_download"><i class="fa fa-facebook"></i></button></a>
                                    
                                    <a href="https://api.whatsapp.com/send?text=Check%20out%20this%20PDF%20<?php echo esc_url($post_pdf); ?>" target="_blank" class="epaper_button_sec"><button class="epaper_download"><i class="fa fa-whatsapp"></i></button></a>
                                    
                                    <a href="https://x.com/intent/tweet?url=<?php echo esc_url($post_pdf); ?>&text=Check%20out%20this%20PDF" target="_blank" class="epaper_button_sec"><button class="epaper_download"><i class="fa-brands fa-x-twitter"></i></button></a>
                                </div>
                                </div>
                                <div class=" flex flex-col gap-3 p-2">
                                    <div class="epaper_img overflow-hidden bg-neutral-900">
                                        <?php if (has_post_thumbnail()) {
                                            printf('<a class="group-hover:scale-105 block group-hover:opacity-80 transition" href="%s">%s</a>', esc_url($post_link), get_the_post_thumbnail(get_the_ID(), 'full'));
                                        } ?>
                                    </div>
                                   
                                        <h3 class="text-sm font-semibold text-center"> <a title="<?php the_title_attribute(); ?>" href="<?php echo esc_url($post_link); ?>" class="group-hover:text-brand text-neutral-600 transition no-underline! "><?php the_title(); ?> </a></h3>
                                   
                                </div>
                            </div>
                            
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    ?>
                      </div>
                         <div class="flex justify-center gap-1 list-none py-4 md:py-6 lg:py-10 w-full">
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
                                'prev_text' => '',
                                'next_text' => '',
                                'add_args' => false,
                                'add_fragment' => '',
                            ));
                            ?>
                        </div>
                    <?php else : ?>
                        <p><?php esc_html_e('No posts found.', 'text-domain'); ?></p>
                    <?php endif; ?>
              
        <?php
            endforeach;
        endif;
        ?>
        </div>
    </section>

     
 

<?php get_footer(); ?>
