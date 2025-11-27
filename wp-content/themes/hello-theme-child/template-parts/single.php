<?php
// Make sure you have the necessary WordPress functions
if (!defined('ABSPATH')) {
    echo "No Script";
    exit;
}

get_header(); 
global $post;
$current_id = $post->ID;
$post_link = get_permalink();
$post_location = get_field('posts_location',$current_id);
$postCategories = '';
// Outputs: https://example.com/मेरा-पोस्ट/
// echo "<pre>";
// print_r($post_location);
// echo "</pre>";

?>

<div class="content-wrapper py-5 lg:py-10">
    <aside class="google-ads left">
        <?php if (is_active_sidebar('google-ads-left')) : ?>
            <?php dynamic_sidebar('google-ads-left'); ?>
        <?php endif; ?>
    </aside>
    <section class="single-page">
      <div class="wrapper">
        <div class="flex flex-col md:grid md:grid-cols-12 gap-10">
          <div class="single-news md:col-span-7" >
            <?php while (have_posts()) : the_post(); ?>
              <article id="post-<?php the_ID(); ?>" <?php post_class('news-article'); ?>>
                <header class="news-header">
                  <h1 class="news-title font-semibold text-neutral-900"><?php the_title(); ?></h1>
                  <?php $postCategories = get_the_category(); ?>
                    <div class="news-meta">
                         <ul class="news-postmeta">
                            <li id="news-location" class="post_location"><span ><?php echo $post_location ;?></span></li>
                           
                            <li id="news-date-time" class="published-date"><span><?php the_time('F jS, Y') ?> <?php the_time( 'g:i a' ); ?></span></li>
                              <li id="news-author" class="author"><span><?php the_author_posts_link() ?> </span></li>

                         </ul>                   
                    </div>
                </header>
                <div class="news-image-wrapper">
                  <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="news-image-link">
                      <?php the_post_thumbnail('full'); ?>
                    </a>
					<?php else : ?>
					<a href="<?php echo esc_url(get_permalink()); ?>" class="news-image-link">
                       <img src="https://royalpatrika.com/wp-content/uploads/2024/07/no-image.png"  alt="<?php the_title(); ?>" />
                    </a>
                  <?php endif; ?>
                </div>
                <div class="news-share">
                   <a class="fa fa-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" target="_blank"></a>
                   <a class="fa fa-twitter" href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode(get_permalink()); ?>&text=<?php echo rawurlencode(get_the_title()); ?>&hashtags=yourhashtags" target="_blank"></a>
                   <a class="fa fa-whatsapp" href="<?php echo 'https://wa.me/?text=' . rawurlencode(get_the_title()) . ' ' . createFriendlyURL(get_permalink()); ?>" target="_blank" data-action="share/whatsapp/share"></a>
					 <a class="copy_text fas fa fa-copy"  data-toggle="tooltip" title="Copy to Clipboard" href="<?php echo createFriendlyURL(get_permalink()); ?>"></a>

                        </div>
                <div class="news-content">
  <?php
    $content = apply_filters('the_content', get_the_content());
    $acf_image = get_field('mobile_middle_ad'); // ACF image field (returns array or URL)

    if (wp_is_mobile()) {
        $paragraphs = explode('</p>', $content);
        $middle = floor(count($paragraphs) / 2);

        foreach ($paragraphs as $index => $paragraph) {
            if (trim($paragraph)) {
                echo $paragraph . '</p>';
            }

            if ($index == $middle) {
                echo '<div class="mobile-inline-ad">';

                if ($acf_image) {
                    // If the field returns an array
                    $image_url = is_array($acf_image) ? $acf_image['url'] : $acf_image;
                    echo '<img src="' . esc_url($image_url) . '" alt="Mobile Ad" style="max-width:100%; height:auto;">';
                } else {
                    // Fallback: Google AdSense
                    ?>
                    <ins class="adsbygoogle"
                         style="display:block; text-align:center;"
                         data-ad-client="ca-pub-XXXXXXX"
                         data-ad-slot="YYYYYYY"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    <?php
                }

                echo '</div>';
            }
        }
    } else {
        echo $content;
    }
  ?>
</div>


              </article>
            <?php endwhile; ?>
                <div id="related-posts-container" class="eleven columns alpha omega related-post">
                    <h2>संबंधित खबरें</h2>
                    <div class="related-post-sec">

                    <?php
                    // Get the categories of the current post
                    // $postCategories = get_the_category();
                    $postCatIds = '';

                    // Construct the category IDs string
                    foreach($postCategories as $catIndex => $catValue) {
                        $postCatIds .= $catValue->cat_ID;
                        if ($catIndex < (count($postCategories) - 1)) {
                            $postCatIds .= ', ';
                        }
                    }

                    // Query the posts based on the categories
                    query_posts(array(
                        'cat' => $postCatIds,
                        'posts_per_page' => 4
                    ));

                    if (have_posts()) :
                        while (have_posts()) : the_post(); ?>
                                       <div class="related-post-item">
                                        <a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="related-post-thumbnail">
                                                    <?php the_post_thumbnail('full'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="related-post-title">
                                                <h3><?php the_title(); ?></h3>
                                            </div>
                                        </a>
                                    </div>
                        <?php endwhile;
                    endif;
                    wp_reset_query();
                    ?>
                </div>
            </div>      

          </div>
          <div class="md:col-span-5 single-post-ads">
             <div class="post-ads-title">
				<h6>विज्ञापन</h6> 
			 </div>
			  <div class="singlepost-ads1">
				  <?php if (is_active_sidebar('post_detail_page_ads1')) : ?>
						<?php dynamic_sidebar('post_detail_page_ads1'); ?>
					<?php endif; ?>
			 </div> 
			  <div class="singlepost-ads2">
				  <?php if (is_active_sidebar('post_detail_page_ads2')) : ?>
						<?php dynamic_sidebar('post_detail_page_ads2'); ?>
					<?php endif; ?>
			 </div> 
			  
          </div>
        </div>
      </div>
    </section>
    <aside class="google-ads right">
        <?php if (is_active_sidebar('google-ads-right')) : ?>
            <?php dynamic_sidebar('google-ads-right'); ?>
        <?php endif; ?>
    </aside>
</div>

<?php get_footer(); ?>
