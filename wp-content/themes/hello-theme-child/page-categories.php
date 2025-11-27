<?php
/* Template Name: All Categories */
get_header();
?>

<h2>All Categories</h2>
<ul>
<?php
$categories = get_categories(['hide_empty' => false]);

foreach ($categories as $cat): ?>
    <li>
        <a href="<?php echo get_category_link($cat->term_id); ?>">
            <?php echo esc_html($cat->name); ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>

<?php get_footer(); ?>
