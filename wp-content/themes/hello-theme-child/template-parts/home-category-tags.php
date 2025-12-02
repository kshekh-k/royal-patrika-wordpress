<?php 
$top_cats = get_categories([
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 15,   
    'hide_empty' => true
]);
?>

<?php if (!empty($top_cats)) : ?>
    <div class="flex gap-2 flex-wrap">
        <?php foreach ($top_cats as $cat) : ?>
          
                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" 
                   class="border border-neutral-200 px-2.5 pt-2 pb-1.5 block transition text-sm leading-none text-neutral-900 hover:bg-brand hover:text-white hover:border-brand no-underline!">
                    <?php echo esc_html($cat->name); ?>
                </a>
           
        <?php endforeach; ?>
        </div>
<?php endif; ?>
