<?php
/**
 * The template for displaying header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description', 'display' );
$header_nav_menu = wp_nav_menu( [
	'theme_location' => 'menu-1',
	'fallback_cb' => false,
	'echo' => false,
] );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php
	// ✅ Fallback meta description (if SEO plugin does not output one)
	if ( is_single() || is_page() ) {
		$meta_desc = get_the_excerpt();
	} else {
		$meta_desc = $tagline;
	}

	// Output only if plugin hasn't already added a meta description
	if ( ! has_action( 'wp_head', 'yith_seo_meta' ) ) {
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $meta_desc ) ) . '">' . "\n";
	}
	?>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header id="site-header" class="site-header">

	<div class="site-branding">
		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		} elseif ( $site_name ) {
			?>
			<h1 class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr__( 'Home', 'hello-elementor' ); ?>" rel="home">
					<?php echo esc_html( $site_name ); ?>
				</a>
			</h1>
			<p class="site-description">
				<?php
				if ( $tagline ) {
					echo esc_html( $tagline );
				}
				?>
			</p>
		<?php } ?>
	</div>

	<?php if ( $header_nav_menu ) : ?>
		<nav class="site-navigation">
			<?php
			// PHPCS - escaped by WordPress with "wp_nav_menu"
			echo $header_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</nav>
	<?php endif; ?>
</header>
