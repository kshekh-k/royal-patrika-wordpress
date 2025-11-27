<?php $featured_posts = get_option('sticky_posts');  
        if (!empty($featured_posts)):
        
            $first_two = new WP_Query([
                'post_type' => 'post',
                'post__in' => $featured_posts,
                'posts_per_page' => 2,
                'orderby' => 'date',
                'order' => 'DESC',
                'ignore_sticky_posts' => 1,  
            ]);

            if ($first_two->have_posts()):
                while ($first_two->have_posts()):
                    $first_two->the_post();

                
                    $cats = get_the_category();
                    $single_cat = '';

                    if (!empty($cats)) {
                        foreach ($cats as $cat) {
                            if ($cat->slug !== 'trending-now') {
                                $single_cat = $cat->name;
                                break;
                            }
                        }
                    }
            ?>
        <div class="space-y-2">
            <a href="<?php the_permalink(); ?>"
                class="flex justify-center items-center w-full h-54 overflow-hidden bg-neutral-100">
                <?php if (has_post_thumbnail()):
                the_post_thumbnail('large', [
                    'class' => 'object-cover h-auto min-h-full w-auto min-w-full max-w-none',
                ]);
            else:
                echo hs_get_default_image('default', 'object-cover h-auto min-h-full w-auto min-w-full max-w-none wp-post-image');
            endif; ?>
            </a>
            <div class="space-y-1">
                <h3 class="text-sm font-semibold">
                    <a href="<?php the_permalink(); ?>"
                        class="no-underline! text-neutral-900 hover:text-brand transition line-clamp-2 leading-snug max-h-10.5 overflow-hidden pt-1">
                        <?php the_title(); ?>
                    </a>
                </h3>
                <div class="flex gap-2 items-center">
                    <?php if (!empty($single_cat)): ?>
                    <span class="text-xs bg-brand text-white px-2 py-0.5 uppercase font-medium">
                        <?= esc_html($single_cat) ?>
                    </span>
                    <?php endif; ?>
                    <p class="text-xs text-neutral-400">
                        <?php echo get_the_date(); ?>
                    </p>
                </div>
            </div>
        </div>
<?php endwhile;  wp_reset_postdata(); endif; endif; ?>