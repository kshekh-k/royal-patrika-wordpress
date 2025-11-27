<div class="space-y-4">
    <h3 class="text-left text-lg text-neutral-900 pb-3 border-b border-brand uppercase">
        <b>Top</b> Categories
    </h3>

    <?php
    $default_img = get_template_directory_uri() . '/assets/img/no-image-default-categories.png';
 
    $latest_posts = get_posts([
        'numberposts'  => 50,
        'post_type'    => 'post',
        'post_status'  => 'publish',
    ]);

    $category_ids = [];
    foreach ($latest_posts as $post) {
        $cats = get_the_category($post->ID);
        if ($cats) {
            foreach ($cats as $cat) {
                if (!isset($category_ids[$cat->term_id])) {
                    $category_ids[$cat->term_id] = $cat;
                }
            }
        }
    }

 
    $latest_categories = array_slice($category_ids, 0, 5, true);
    ?>

    <!-- Categories list -->
    <div class="space-y-3">
        <?php foreach ($latest_categories as $cat): ?>

            <?php
            $cat_img_id = get_term_meta($cat->term_id, 'category_image_id', true);

            if (empty($cat_img_id)) {
                $cat_img_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            }

            if ($cat_img_id) {
                $cat_img_url = wp_get_attachment_image_url((int) $cat_img_id, 'large');
                if (!$cat_img_url) {
                    $cat_img_url = $default_img;
                }
            } else {
                $cat_img_url = $default_img;
            }

            $cat_link = get_category_link($cat->term_id);
            $cat_name = $cat->name;
            ?>

            <div class="relative bg-(image:--cat-image) bg-cover bg-center overflow-hidden w-full h-14 bg-neutral-100"
                 style="--cat-image:url('<?php echo esc_url($cat_img_url); ?>')">
                <a href="<?php echo esc_url($cat_link); ?>"
                   aria-label="<?php echo esc_attr($cat_name); ?> category"
                   class="text-base font-semibold text-white text-center flex justify-center items-center
                          bg-black/50 uppercase absolute inset-0 no-underline! hover:bg-brand/90 transition">
                    <span><?php echo esc_html($cat_name); ?></span>
                </a>

            </div>

        <?php endforeach; ?>
        <div class="pt-2 flex justify-center">
                    <a href="<?php echo site_url(); ?>/category/category-slug" 
                        class="text-sm font-semibold no-underline! border border-neutral-200 rounded py-2 px-3 flex justify-center text-center text-neutral-900 hover:text-brand hover:bg-brand/20 hover:border-brand/30 flex-1 transition ">
                        View All Categories
                    </a>
                </div>
        
    </div>

</div>
