<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<main id="content" class="site-main">

	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<header class="page-header">
			<a href="https://royalpatrika.com/" class="news-image-link">
              <img src="https://royalpatrika.com/wp-content/uploads/2024/07/404-newspage.jpg"  alt="<?php the_title(); ?>" />
                    </a>
		</header>
	<?php endif; ?>

	<div class="page-content">
		<h2 class="error-text text-center">क्षमा करें! 
			<br><span>जो सामग्री आप खोज रहे हैं वो उपलब्ध नहीं है। 
                    <a href="/" style="color:#b40000">होम पेज पर जाएँ</a> या नीचे दी गई ट्रेंडिंग खबरें पढ़ें।</span>
		</h2>
					  <div class="singlepost-ads2">
				  <?php if (is_active_sidebar('404_page_news')) : ?>
						<?php dynamic_sidebar('404_page_news'); ?>
					<?php endif; ?>
			 </div>
	</div>

</main>
