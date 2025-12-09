<?php if(!defined('ABSPATH')){ echo "No Script"; exit; }?>
<?php get_header(); ?>

<div class="wrapper bg-red-500">
    <aside class="google-ads left">
        <?php if (is_active_sidebar('google-ads-left')) : ?>
            <?php dynamic_sidebar('google-ads-left'); ?>
        <?php endif; ?>
    </aside>
    
    <section class="tilecompany-page">
        <div class="container">
            <div class="tilecompany-page-heading">
            
            </div>
	        <div class="post-content">
	            <?php while(have_posts()) : the_post(); ?>
	            	<?php the_title(); ?>  
	                <?php the_content();?> 
	            <?php endwhile; ?>  
	        </div>
        </div>
    </section>

    <section class="inner-content-page">
    </section>

    <aside class="google-ads right">
        <?php if (is_active_sidebar('google-ads-right')) : ?>
            <?php dynamic_sidebar('google-ads-right'); ?>
        <?php endif; ?>
    </aside>
</div>

<?php get_footer(); ?>