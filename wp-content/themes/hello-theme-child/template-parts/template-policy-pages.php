<?php
/*
Template Name: Policy Template
*/
get_header();
global $post;
$Page_ID = $post->ID;
?>
<?php if (!defined("ABSPATH")) {
    echo "No Script";
    exit();
} ?>
<?php get_header(); ?>

<div class="content-wrapper p-2">
    
    
<section class="homenews-page">
    <div class="wrap latest-big-news-sec">
        <div class="post-content row">
            <div class="heading">
                <h3 class="text-center big-news-title"><?php the_title(); ?></h3>
            </div>
            <div class="col-xs-12 col-md-12 post-content-sec">                                  
                <?php the_content(); ?>            
            </div>
        </div>
    </div>

    <div class="wrap latest-big-news-sec">
        <?php echo get_field('policy-content', $group_68eb8dee88aeb); ?>
    </div>
    
</section>

<aside class="google-ads right" id="google-ads-right">
    <?php if (is_active_sidebar("google-ads-right")): ?>
        <?php dynamic_sidebar("google-ads-right"); ?>
    <?php endif; ?>
</aside>
</div>


<style>
/* Accessible hidden text */
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0,0,0,0);
  white-space: nowrap;
  border: 0;
}
</style>

<?php get_footer(); ?>
