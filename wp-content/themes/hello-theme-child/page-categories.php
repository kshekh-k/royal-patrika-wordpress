<?php
/* Template Name: All Categories */
get_header();
?>
<div class="bg-white py-5 ">
    <div class="wrapper">
        <div class="flex flex-col gap-5 ">
            <div class="bg-linear-to-r from-rose-100 to-rose-700 p-5 lg:p-10 flex justify-center items-center">
                <h1 class="text-2xl lg:text-3xl font-bold text-white">All Categories</h1>
            </div>
            <section class="relative flex-1 ">

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5 ">
                                            <?php
                        $categories = get_categories([
                            'hide_empty' => false,
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                        ]);

                        // Push "Uncategorized" to last
                        usort($categories, function ($a, $b) {
                            if ($a->slug === 'uncategorized') return 1;
                            if ($b->slug === 'uncategorized') return -1;
                            return strcmp($a->name, $b->name);
                        });

                        $default_img = get_template_directory_uri() . '/assets/img/no-image-default-categories.png';

                        foreach ($categories as $cat):
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
                        ?>

                    <div class="flex flex-col relative ">
                        <a href="<?php echo get_category_link($cat->term_id); ?>"
                            title="<?php echo esc_attr($cat->name); ?>"
                            class="flex flex-col flex-1 gap-5 p-3 items-center w-full group no-underline! text-neutral-900 hover:text-brand transition border border-neutral-200 hover:shadow-1 hover:border-brand">
                            <div
                                class="flex justify-center items-center w-full h-28 lg:h-40 overflow-hidden bg-neutral-900">
                                <img src="<?php echo esc_url($cat_img_url); ?>"
                                    alt="<?php echo esc_attr($cat->name); ?>" class="object-cover h-auto min-h-full w-auto min-w-full max-w-none group-hover:scale-105 transition group-hover:opacity-75" />
                            </div>
                            <h3 class="text-lg font-semibold leading-snug"><?php echo esc_html($cat->name); ?></h3>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>

            </section>
        </div>
    </div>
</div>
<?php get_footer(); ?>