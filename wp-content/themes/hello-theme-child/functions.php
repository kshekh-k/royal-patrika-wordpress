<?php

/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */
if (!defined('ABSPATH')) {
    exit;  // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        time()
    );

    // Tailwindcss generated CSS File
    wp_enqueue_style(
        'custom-extra-style',
        get_stylesheet_directory_uri() . '/styles.css',  // change path if needed
        ['hello-elementor-child-style'],  // load after child theme
        time()
    );
}

add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

// custom js
// function enqueue_custom_scripts() {
//     wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/assets/custom/custom-script.js', array('jquery'), time(), true);
// }
// add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

/* ----------------------------------------------
   Theme Setup
---------------------------------------------- */
function news_channel_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus([
        'primary' => __('Primary Menu', 'news-channel-theme'),
        'footer-menu-link' => __('Footer Menu', 'news-channel-theme'),
        'footer-menu-1' => __('Footer Menu 1', 'news-channel-theme'),
        'footer-menu-2' => __('Footer Menu 2', 'news-channel-theme'),
        'footer-menu-3' => __('Footer Menu 3', 'news-channel-theme'),
        'mobile-header' => __('Mobile Menu', 'news-channel-theme'),
    ]);
}

add_action('after_setup_theme', 'news_channel_theme_setup');

// Footer Menus styles

function footer_menu_li_classes($classes, $item, $args)
{
    // apply only to specific menus
    $footer_menus = ['footer-menu-1', 'footer-menu-2', 'footer-menu-3'];

    if (in_array($args->theme_location, $footer_menus)) {
        $classes[] = 'py-1';  // Tailwind classes for <li>
    }

    return $classes;
}

add_filter('nav_menu_css_class', 'footer_menu_li_classes', 10, 3);

function footer_menu_link_classes($atts, $item, $args)
{
    $footer_menus = ['footer-menu-1', 'footer-menu-2', 'footer-menu-3'];

    if (in_array($args->theme_location, $footer_menus)) {
        $atts['class'] = 'text-neutral-300! hover:text-brand! ease-in-out duration-200 no-underline!';
    }

    return $atts;
}

add_filter('nav_menu_link_attributes', 'footer_menu_link_classes', 10, 3);

/* ----------------------------------------------
   Modify MAIN UL classes (menu_class)
---------------------------------------------- */
function my_modify_main_ul_classes($args)
{
    if (isset($args['theme_location']) && $args['theme_location'] === 'primary') {
        // Desktop Header Navigation
        if ($args['menu_class'] === 'header-menu') {
            $args['menu_class'] .= ' flex divide-x divide-neutral-200 xl:text-lg font-semibold';
        }

        // Sidebar (Mobile) Navigation
        if ($args['menu_class'] === 'sidebar-menu') {
            $args['menu_class'] .= ' divide-y divide-neutral-700 border-b border-neutral-700 mb-5';
        }

        // Footer Menu
        if ($args['menu_class'] === 'footer-menu') {
            $args['menu_class'] .= ' flex flex-wrap gap-4 text-sm text-neutral-500';
        }
    }

    return $args;
}

add_filter('wp_nav_menu_args', 'my_modify_main_ul_classes');

/* ----------------------------------------------
   Add <li> classes based on depth + children + menu_class
---------------------------------------------- */
function my_primary_menu_li_classes($classes, $item, $args, $depth)
{
    if ($args->theme_location !== 'primary') {
        return $classes;
    }

    $menu_class = $args->menu_class;

    /* ================================================
       1. BASE <li> CLASS FOR ALL MENUS
    ================================================= */
    $classes[] = 'relative';

    /* ================================================
       2. HEADER MENU (Desktop Navigation)
    ================================================= */
    if (strpos($menu_class, 'header-menu') === 0) {
        // Add desktop LI border on level 0
        if ($depth === 0) {
            $classes[] = 'last:border-r last:border-neutral-200 [&.current_page_item>a]:text-brand! [&.current_page_item>a]:bg-neutral-100!';
        }

        // Parent items with submenu
        if (in_array('menu-item-has-children', $classes)) {
            if ($depth === 0) {
                $classes[] = 'group/main [&:hover>a]:text-brand';
            }
            if ($depth === 1) {
                $classes[] = 'group/sub [&:hover>a]:text-brand';
            }
            if ($depth === 2) {
                $classes[] = 'group/sub2 [&:hover>a]:text-brand';
            }
        }
    }
    /* ================================================
       3. SIDEBAR MENU (Mobile Navigation)
    ================================================= */ elseif (strpos($menu_class, 'sidebar-menu') === 0) {
        // Base LI classes for mobile
        $classes[] = 'block';

        // Parent items (expandable)
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'group mobile-parent relative';
        }

        // Level-based mobile styling
        if ($depth === 0) {
            $classes[] = '';
        } elseif ($depth === 1) {
            $classes[] = '';
        } elseif ($depth === 2) {
            $classes[] = '';
        }
    }
    /* ================================================
       4. FOOTER MENU
    ================================================= */ elseif (strpos($menu_class, 'footer-menu') === 0) {
        $classes[] = 'inline-block mr-4 py-1';
    }

    return $classes;
}

add_filter('nav_menu_css_class', 'my_primary_menu_li_classes', 10, 4);

/* ------------------------------------------------------------
   Add Alpine.js x-data attributes to <li> for SIDEBAR MENU
------------------------------------------------------------ */
function my_sidebar_menu_li_alpine_attrs($atts, $item, $args, $depth)
{
    // Only affect primary menu
    if ($args->theme_location !== 'primary') {
        return $atts;
    }

    // Only sidebar menu should get x-data attributes
    if (strpos($args->menu_class, 'sidebar-menu') !== 0) {
        return $atts;
    }

    // Only items that have children should get x-data
    $has_children = in_array('menu-item-has-children', $item->classes);

    if ($has_children) {
        // Determine Alpine variable name based on depth
        $var = match ($depth) {
            0 => 'level0',
            1 => 'level1',
            2 => 'level2',
            default => 'levelX'
        };

        // Add Alpine x-data attribute to <li>
        $atts['x-data'] = "{ {$var}: false }";
    }

    return $atts;
}

add_filter('nav_menu_item_attributes', 'my_sidebar_menu_li_alpine_attrs', 10, 4);

/* ----------------------------------------------
   Add Font Awesome icons based on depth + menu_class + Alpine Levels
---------------------------------------------- */
function my_primary_menu_add_depth_icon($item_output, $item, $depth, $args)
{
    if ($args->theme_location !== 'primary') {
        return $item_output;
    }

    // Only for parent items (has submenu)
    if (!in_array('menu-item-has-children', $item->classes)) {
        return $item_output;
    }

    /* Assign icon based on depth */
    $icon_class = match ($depth) {
        0 => 'fa-solid fa-caret-down ml-1 text-sm',
        1 => 'fa-solid fa-caret-right ml-1 text-sm',
        2 => 'fa-solid fa-caret-right ml-1 text-sm',
        default => '',
    };

    if (!$icon_class) {
        return $item_output;
    }

    

    /* Detect which menu we’re styling */
    $menu_class = $args->menu_class;

    /* =====================================================
       1) HEADER MENU  →  icon INSIDE <a>
    ===================================================== */
    if (strpos($menu_class, 'header-menu') === 0) {
        $icon_html = ' <i class="' . $icon_class . '"></i>';

        // Insert icon before </a>
        $item_output = str_replace('</a>', $icon_html . '</a>', $item_output);

        return $item_output;
    }

    /* =====================================================
       2) SIDEBAR MENU  →  icon OUTSIDE <a>, with Alpine toggle
    ===================================================== */
    if (strpos($menu_class, 'sidebar-menu') === 0) {
        // Determine correct Alpine variable based on depth
        $alpine_var = match ($depth) {
            0 => 'level0',
            1 => 'level1',
            2 => 'level2',
            default => 'levelX'
        };

        // Build new icon HTML with dynamic Alpine toggle
        $icon_html = '<button type="button" class="absolute top-0 right-0 size-12 border-l border-neutral-700 text-white" @click="' . $alpine_var . ' = !' . $alpine_var . '">
                        <i class="' . $icon_class . ' " :class="' . $alpine_var . ' ? ' . "'rotate-180'" . ' : ' . "'rotate-0'" . '"></i>
                      </button>';

        // Add icon after </a>
        $item_output = str_replace('</a>', '</a>' . $icon_html, $item_output);

        return $item_output;
    }

    /* =====================================================
       3) Fallback (if any other menu)
    ===================================================== */
    $fallback_icon = ' <i class="' . $icon_class . '"></i>';
    $item_output = str_replace('</a>', $fallback_icon . '</a>', $item_output);

    return $item_output;
}

add_filter('walker_nav_menu_start_el', 'my_primary_menu_add_depth_icon', 10, 4);

/* ----------------------------------------------
   Add <a> classes depending on menu_class
---------------------------------------------- */
function my_primary_menu_link_classes($atts, $item, $args)
{
    if ($args->theme_location !== 'primary') {
        return $atts;
    }

    $menu_class = $args->menu_class;

    // Desktop Header
    if (strpos($menu_class, 'header-menu') === 0) {
        $atts['class'] =
            'flex items-center justify-between p-2 xl:px-5 xl:py-4 text-neutral-700 hover:text-brand hover:bg-neutral-100 no-underline! ease-in-out duration-200 whitespace-nowrap';
    }
    // Sidebar / Mobile
    elseif (strpos($menu_class, 'sidebar-menu') === 0) {
        $atts['class'] =
            'flex items-center justify-between p-3 text-white hover:text-rose-500 hover:bg-brand/5 no-underline! ease-in-out duration-200';
    }
    // Fallback
    else {
        $atts['class'] =
            'px-5 py-4 text-neutral-600 hover:text-brand ease-in-out duration-200 no-underline!';
    }

    return $atts;
}

add_filter('nav_menu_link_attributes', 'my_primary_menu_link_classes', 10, 3);

/* --------------------------------------------------------------
   Add Tailwind + Alpine.js attributes to SUBMENU <ul> by depth
-------------------------------------------------------------- */
function my_primary_menu_submenu_classes($classes, $args, $depth)
{
    if ($args->theme_location !== 'primary') {
        return $classes;
    }

    // Reset WP default submenu classes
    $classes = ['sub-menu'];

    // Detect menu type
    $menu_class = $args->menu_class;

    /* =========================================================
       1) HEADER MENU (desktop – hover dropdown)
    ========================================================= */
    if (strpos($menu_class, 'header-menu') === 0) {
        if ($depth === 0) {
            $classes[] =
                'absolute left-0 top-full w-52 p-1 bg-white shadow-1 rounded-b-lg hidden group-hover/main:block z-50 divide-y divide-neutral-200';
        }

        if ($depth === 1) {
            $classes[] =
                'absolute left-[calc(100%-4px)] top-0 w-52 p-1 bg-white shadow-1 rounded-lg hidden group-hover/sub:block z-50 divide-y divide-neutral-200';
        }

        if ($depth === 2) {
            $classes[] =
                'absolute left-[calc(100%-4px)] top-0 w-52 p-1 bg-white shadow-1 rounded-lg hidden group-hover/sub2:block z-50 divide-y divide-neutral-200';
        }

        return $classes;
    }

    /* =========================================================
       2) SIDEBAR MENU (mobile – click to open using Alpine.js)
    ========================================================= */
    if (strpos($menu_class, 'sidebar-menu') === 0) {
        // Sidebar submenu UL defaults
        $classes[] = 'w-full divide-y divide-neutral-700 border-t border-neutral-700';

        // Depth-based Alpine variable name
        $alpine_var = match ($depth) {
            0 => 'level0',
            1 => 'level1',
            2 => 'level2',
            default => 'levelX'
        };

        /** Inject UL attributes for Alpine.js */
        add_filter('nav_menu_submenu_attributes', function ($atts) use ($alpine_var) {
            $atts['x-show'] = $alpine_var;
            $atts['x-collapse'] = '';  // optional transition
            return $atts;
        });

        return $classes;
    }

    /* =========================================================
       3) Fallback for any other menu
    ========================================================= */
    $classes[] = 'bg-white shadow-md rounded p-2';
    return $classes;
}

add_filter('nav_menu_submenu_css_class', 'my_primary_menu_submenu_classes', 10, 3);

/* --------------------------------------------------------------
   Support UL attribute injection (Alpine.js)
-------------------------------------------------------------- */
add_filter('nav_menu_submenu_attributes', function ($atts) {
    return $atts;
});

// Top Menu
function my_top_menu()
{
    register_nav_menus([
        'top_menu' => __('Main Menu', 'hello-theme-child'),
    ]);
}

add_action('init', 'my_top_menu');

/* ----------------------------------------------
   Add Tailwind classes to <li> in top menu
---------------------------------------------- */
function my_top_menu_li_classes($classes, $item, $args)
{
    if ($args->theme_location === 'top_menu') {
        $classes[] = 'px-0.5';
    }
    return $classes;
}

add_filter('nav_menu_css_class', 'my_top_menu_li_classes', 10, 3);

/* ----------------------------------------------
   Add Tailwind classes to <a> in top menu
---------------------------------------------- */
function my_top_menu_link_classes($atts, $item, $args)
{
    if ($args->theme_location === 'top_menu') {
        $atts['class'] = 'text-white text-center ease-in-out duration-200 text-xs sm:text-sm p-2 sm:p-3 hover:text-brand no-underline!';
    }
    return $atts;
}

add_filter('nav_menu_link_attributes', 'my_top_menu_link_classes', 10, 3);

function check_mobile_function()
{
    if (wp_is_mobile()) {
        echo '<!-- wp_is_mobile: true -->';
    } else {
        echo '<!-- wp_is_mobile: false -->';
    }
}

// Google Ads widgets
function news_header_ads_widgets()
{
    register_sidebar(array(
        'name' => __('Google Ads Header', 'news-channel-theme'),
        'id' => 'google-ads',
        'before_widget' => '<div class="max-w-md xl:max-w-xl mx-auto flex items-center justify-center [&>.wp-block-image]:mb-0! [&>.wp-block-image]:h-auto! google-ads">',
        'after_widget' => '</div>',
    ));

    register_sidebar(array(
        'name' => 'Google Ads Top',
        'id' => 'google-ads-top',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => 'Native Ads Right First',
        'id' => 'native-ads-right-first',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => 'Native Ads Left First',
        'id' => 'native-ads-left-first',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => 'Native Ads Left Second',
        'id' => 'native-ads-left-second',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => 'Native Ads Right Second',
        'id' => 'native-ads-right-second',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => 'Native Ads Center',
        'id' => 'native-ads-center',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));


    register_sidebar(array(
        'name' => 'Native Cat Page Ad 1',
        'id' => 'native-cat-page-ad-1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => 'Native Cat Page Ad 2',
        'id' => 'native-cat-page-ad-2',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => 'Native Cat Page Ad 3',
        'id' => 'native-cat-page-ad-3',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => 'Native Cat Page Ad 4',
        'id' => 'native-cat-page-ad-4',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

    
    register_sidebar(array(
        'name' => 'Google Ads Right',
        'id' => 'google-ads-right',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => 'Post detail page ads1',
        'id' => 'post_detail_page_ads1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => 'Post detail page ads2',
        'id' => 'post_detail_page_ads2',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => '404 page News',
        'id' => '404_page_news',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="trading_newstitle">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'news_header_ads_widgets');

function new_excerpt_more($more)
{
    global $post;
    $post_id = $post->ID;
    return '... <a class="reveal-full-content"  action-id ="' . $post->ID . '" href="' . get_permalink($post_id) . '">Read more</a>';
}

add_filter('excerpt_more', 'new_excerpt_more');

// e-paper custom post type
function royalpatrika_epaper_cpt()
{
    $labels = array(
        'name' => _x('E-Papers', 'Post Type General Name', 'royalpatrika'),
        'singular_name' => _x('E-Paper', 'Post Type Singular Name', 'royalpatrika'),
        'menu_name' => __('E-Papers', 'royalpatrika'),
        'name_admin_bar' => __('E-Paper', 'royalpatrika'),
        'archives' => __('E-Paper Archives', 'royalpatrika'),
        'attributes' => __('E-Paper Attributes', 'royalpatrika'),
        'parent_item_colon' => __('Parent E-Paper:', 'royalpatrika'),
        'all_items' => __('All E-Papers', 'royalpatrika'),
        'add_new_item' => __('Add New E-Paper', 'royalpatrika'),
        'add_new' => __('Add New', 'royalpatrika'),
        'new_item' => __('New E-Paper', 'royalpatrika'),
        'edit_item' => __('Edit E-Paper', 'royalpatrika'),
        'update_item' => __('Update E-Paper', 'royalpatrika'),
        'view_item' => __('View E-Paper', 'royalpatrika'),
        'view_items' => __('View E-Papers', 'royalpatrika'),
        'search_items' => __('Search E-Paper', 'royalpatrika'),
        'not_found' => __('Not found', 'royalpatrika'),
        'not_found_in_trash' => __('Not found in Trash', 'royalpatrika'),
        'featured_image' => __('Featured Image', 'royalpatrika'),
        'set_featured_image' => __('Set featured image', 'royalpatrika'),
        'remove_featured_image' => __('Remove featured image', 'royalpatrika'),
        'use_featured_image' => __('Use as featured image', 'royalpatrika'),
        'insert_into_item' => __('Insert into E-Paper', 'royalpatrika'),
        'uploaded_to_this_item' => __('Uploaded to this E-Paper', 'royalpatrika'),
        'items_list' => __('E-Papers list', 'royalpatrika'),
        'items_list_navigation' => __('E-Papers list navigation', 'royalpatrika'),
        'filter_items_list' => __('Filter E-Papers list', 'royalpatrika'),
    );
    $args = array(
        'label' => __('E-Paper', 'royalpatrika'),
        'description' => __('E-Paper custom post type', 'royalpatrika'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies' => array('epaper_category'),  // This will be the custom taxonomy
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('epaper', $args);
}

add_action('init', 'royalpatrika_epaper_cpt', 0);

function create_epaper_taxonomy()
{
    $labels = array(
        'name' => _x('E-Paper Categories', 'Taxonomy General Name', 'royalpatrika'),
        'singular_name' => _x('E-Paper Category', 'Taxonomy Singular Name', 'royalpatrika'),
        'menu_name' => __('E-Paper Categories', 'royalpatrika'),
        'all_items' => __('All Categories', 'royalpatrika'),
        'parent_item' => __('Parent Category', 'royalpatrika'),
        'parent_item_colon' => __('Parent Category:', 'royalpatrika'),
        'new_item_name' => __('New Category Name', 'royalpatrika'),
        'add_new_item' => __('Add New Category', 'royalpatrika'),
        'edit_item' => __('Edit Category', 'royalpatrika'),
        'update_item' => __('Update Category', 'royalpatrika'),
        'view_item' => __('View Category', 'royalpatrika'),
        'separate_items_with_commas' => __('Separate categories with commas', 'royalpatrika'),
        'add_or_remove_items' => __('Add or remove categories', 'royalpatrika'),
        'choose_from_most_used' => __('Choose from the most used', 'royalpatrika'),
        'popular_items' => __('Popular Categories', 'royalpatrika'),
        'search_items' => __('Search Categories', 'royalpatrika'),
        'not_found' => __('Not Found', 'royalpatrika'),
        'no_terms' => __('No categories', 'royalpatrika'),
        'items_list' => __('Categories list', 'royalpatrika'),
        'items_list_navigation' => __('Categories list navigation', 'royalpatrika'),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    );
    register_taxonomy('epaper_category', array('epaper'), $args);
}

add_action('init', 'create_epaper_taxonomy', 0);

// custom excerpt length
function custom_excerpt_length($length)
{
    return 20;  // Change this to the number of words you want
}

add_filter('excerpt_length', 'custom_excerpt_length', 999);

// customurl
function createFriendlyURL($url)
{
    // Parse the URL to get the components
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path'];

    // URL-decode the path
    $decodedPath = urldecode($path);

    // Construct the friendly URL
    $friendlyUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $decodedPath;

    return $friendlyUrl;
}

function hindi_to_english_slug($title)
{
    $hindi = array(
        'अ', 'आ', 'इ', 'ई', 'उ', 'ऊ', 'ए', 'ऐ', 'ओ', 'औ', 'क', 'ख', 'ग', 'घ', 'च', 'छ', 'ज', 'झ', 'ट', 'ठ', 'ड', 'ढ', 'त', 'थ', 'द', 'ध', 'न', 'प', 'फ', 'ब', 'भ', 'म', 'य', 'र', 'ल', 'व', 'श', 'ष', 'स', 'ह',
        'ा', 'ि', 'ी', 'ु', 'ू', 'े', 'ै', 'ो', 'ौ', 'ं', 'ः', '्'
    );

    $english = array(
        'a', 'aa', 'i', 'ii', 'u', 'uu', 'e', 'ai', 'o', 'au', 'k', 'kh', 'g', 'gh', 'ch', 'chh', 'j', 'jh', 't', 'th', 'd', 'dh', 't', 'th', 'd', 'dh', 'n', 'p', 'ph', 'b', 'bh', 'm', 'y', 'r', 'l', 'v', 'sh', 'shh', 's', 'h',
        'a', 'i', 'ii', 'u', 'uu', 'e', 'ai', 'o', 'au', 'an', 'ah', ''
    );

    $title = str_replace($hindi, $english, $title);
    return $title;
}

add_filter('sanitize_title', 'hindi_to_english_slug', 10);

// Social Media Icons


 
/**
 * =========================================
 * SOCIAL MEDIA ICONS WIDGET + SHORTCODE
 * =========================================
 */

/* -----------------------------------------
 * WIDGET CLASS
 * ----------------------------------------- */
class Social_Icons_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'social_icons_widget',
            __('Advanced Social Icons', 'hello-theme-child'),
            ['description' => __('Add social media icons', 'hello-theme-child')]
        );

        add_action('admin_footer', [$this, 'admin_inline_scripts']);
    }

    /* ---------- ADMIN FORM ---------- */
    public function form($instance) {
        $icons = $instance['icons'] ?? [];
        $name  = $this->get_field_name('icons');
        ?>

        <div class="widget-content">
            <div class="social-icons-repeatable" data-name="<?php echo esc_attr($name); ?>">

                <?php foreach ($icons as $i => $icon): ?>
                    <div class="social-icon-row">
                        <input class="widefat"
                            name="<?php echo $name . "[$i][url]"; ?>"
                            value="<?php echo esc_attr($icon['url'] ?? ''); ?>"
                            placeholder="Profile URL">

                        <input class="widefat"
                            name="<?php echo $name . "[$i][icon]"; ?>"
                            value="<?php echo esc_attr($icon['icon'] ?? ''); ?>"
                            placeholder="FontAwesome class">

                        <input class="widefat"
                            name="<?php echo $name . "[$i][title]"; ?>"
                            value="<?php echo esc_attr($icon['title'] ?? ''); ?>"
                            placeholder="Brand name (facebook, instagram)">

                        <button type="button" class="remove-icon button">Remove</button>
                        <hr>
                    </div>
                <?php endforeach; ?>

            </div>

            <button type="button" class="add-icon button button-primary">+ Add Icon</button>
        </div>
        <?php
    }

    /* ---------- SAVE ---------- */
   public function update($new, $old)
{
    $instance = [];
    $instance['icons'] = [];

    if (!empty($new['icons'])) {
        foreach ($new['icons'] as $icon) {
            if (!empty($icon['url']) && !empty($icon['icon'])) {
                $instance['icons'][] = [
                    'url'   => esc_url_raw($icon['url']),
                    'icon'  => sanitize_text_field($icon['icon']),
                    'title' => sanitize_text_field($icon['title'] ?? ''),
                ];
            }
        }
    }

    // Shortcode ke liye globally save kar rahe
    update_option('GLOBAL_SOCIAL_ICONS', $instance['icons']);

    return $instance;
}


    /* ---------- FRONTEND (PLAIN) ---------- */
    public function widget($args, $instance)
{
    echo $args['before_widget'];
    echo render_social_icons($instance['icons'] ?? [], 'plain');
    echo $args['after_widget'];
}

    /* ---------- ADMIN JS ---------- */
    public function admin_inline_scripts() {
        ?>
        <style>
            .social-icon-row { display:flex; gap:6px; margin-bottom:6px; }
        </style>

        <script>
        document.addEventListener('click', function(e){

            if (e.target.classList.contains('add-icon')) {
                const wrap = e.target.closest('.widget-content');
                const box = wrap.querySelector('.social-icons-repeatable');
                const name = box.dataset.name;
                const i = box.children.length;

                const row = document.createElement('div');
                row.className = 'social-icon-row';
                row.innerHTML = `
                    <input class="widefat" name="${name}[${i}][url]" placeholder="Profile URL">
                    <input class="widefat" name="${name}[${i}][icon]" placeholder="FontAwesome class">
                    <input class="widefat" name="${name}[${i}][title]" placeholder="Brand name">
                    <button type="button" class="remove-icon button">Remove</button>
                    <hr>
                `;
                box.appendChild(row);
            }

            if (e.target.classList.contains('remove-icon')) {
                e.target.closest('.social-icon-row').remove();
            }
        });
        </script>
        <?php
    }
}

/* -----------------------------------------
 * REGISTER WIDGET + SIDEBAR
 * ----------------------------------------- */
add_action('widgets_init', function () {
    register_widget('Social_Icons_Widget');

    register_sidebar([
        'name' => 'Social Media Widget',
        'id'   => 'social_media_widget',
    ]);
});

// Brand slug detection
function detect_brand_slug(string $title = '', string $icon_class = ''): string
{
    $title = strtolower(trim($title));
    $icon_class = strtolower($icon_class);

    // Pehle title se try karo
    if ($title) {
        return $title;
    }

    // Agar title khali hai toh icon class se detect karo
    $known = ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube', 'github', 'telegram', 'whatsapp', 'x'];

    foreach ($known as $slug) {
        if (str_contains($icon_class, $slug)) {
            return $slug;
        }
    }

    return ''; // unknown
}




/* -----------------------------------------
 * BRAND CLASS LOGIC
 * ----------------------------------------- */
function social_brand_class(string $brand_slug): string
{
    $brand_slug = strtolower(trim($brand_slug));

    $map = [
        'facebook'  => 'bg-blue-700',
        'instagram' => 'bg-linear-to-tr from-yellow-400 via-pink-500 to-purple-600',
        'twitter'   => 'bg-black',
        'linkedin'  => 'bg-blue-700',
        'youtube'   => 'bg-rose-700',
        'github'    => 'bg-black',
        'telegram'  => 'bg-blue-500',
        'whatsapp'  => 'bg-green-500',
        'x'         => 'bg-black',
    ];

    $color_class = $map[$brand_slug] ?? 'text-white';

    return $color_class . ' hover:opacity-80 transition duration-200';
}

function social_anchor_class(string $style, string $brand_slug): string
{
    $style = strtolower(trim($style));

    if ($style === 'brand') {
        return social_brand_class($brand_slug);
    }

    // plain
    return 'text-white hover:opacity-80 transition duration-200';
}

 

/* -----------------------------------------
 * RENDER FUNCTION
 * ----------------------------------------- */
function render_social_icons($icons, $style = 'plain')
{
    if (empty($icons)) return '';

    ob_start();
 

    foreach ($icons as $icon) {

        $url   = $icon['url']  ?? '';
        $icls  = $icon['icon'] ?? '';
        $title = $icon['title'] ?? '';

        // yahan se brand nikal rahe hain (title ya icon se)
        $brand_slug = detect_brand_slug($title, $icls);
        $a_class    = esc_attr(social_anchor_class($style, $brand_slug));
        $title_attr = esc_attr($title ?: ucfirst($brand_slug));

        echo '<a href="' . esc_url($url) . '"
                 target="_blank"
                 title="' . $title_attr . '"
                 aria-label="' . $title_attr . '"
                 class="no-underline! sm:size-7 size-6 inline-flex justify-center items-center transition rounded-sm ' . $a_class . '">
                <i class="text-sm md:text-base ' . esc_attr($icls) . '"></i>
              </a>';
    }

    
    return ob_get_clean();
}


/* -----------------------------------------
 * SHORTCODE (GUARANTEED)
 * ----------------------------------------- */
add_action('init', function () {
    add_shortcode('social_icons', function ($atts) {

        $atts = shortcode_atts([
            'style' => 'plain', // plain | brand
        ], $atts);

        $icons = get_option('GLOBAL_SOCIAL_ICONS');
        if (empty($icons)) {
            return '';
        }

        return render_social_icons($icons, $atts['style']);
    });
});



// End Social media Icons
function royalpatrika_customize_register($wp_customize)
{
    $wp_customize->add_section('footer_contact', [
        'title' => __('Footer Contact Info', 'royalpatrika'),
        'priority' => 200,
    ]);

    // Phone
    $wp_customize->add_setting('footer_phone');
    $wp_customize->add_control('footer_phone', [
        'label' => __('Phone', 'royalpatrika'),
        'section' => 'footer_contact',
        'type' => 'text',
    ]);

    // Email
    $wp_customize->add_setting('footer_email');
    $wp_customize->add_control('footer_email', [
        'label' => __('Email', 'royalpatrika'),
        'section' => 'footer_contact',
        'type' => 'text',
    ]);

    // Address
    $wp_customize->add_setting('footer_address');
    $wp_customize->add_control('footer_address', [
        'label' => __('Address', 'royalpatrika'),
        'section' => 'footer_contact',
        'type' => 'textarea',
    ]);

    // Business Hours
    $wp_customize->add_setting('footer_hours');
    $wp_customize->add_control('footer_hours', [
        'label' => __('Business Hours', 'royalpatrika'),
        'section' => 'footer_contact',
        'type' => 'textarea',
    ]);
}

add_action('customize_register', 'royalpatrika_customize_register');

function hs_get_slider_posts($categories = [], $count = 10)
{
    if (!is_array($categories)) {
        $categories = [$categories];
    }

    // Convert slugs → IDs
    $cat_ids = [];
    foreach ($categories as $slug) {
        if (!$slug)
            continue;
        $cat = get_category_by_slug($slug);
        if ($cat)
            $cat_ids[] = $cat->term_id;
    }

    // Query
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $count,
    ];
    if (!empty($cat_ids)) {
        $args['category__in'] = $cat_ids;
    }

    return new WP_Query($args);
}

function hs_get_right_featured_posts($categories = [], $count = 4)
{
    if (!is_array($categories)) {
        $categories = [$categories];
    }

    // Convert slugs → IDs
    $cat_ids = [];
    foreach ($categories as $slug) {
        if (!$slug)
            continue;
        $cat = get_category_by_slug($slug);
        if ($cat)
            $cat_ids[] = $cat->term_id;
    }

    // Query
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $count,
        'offset' => 10,  // skip slider posts
    ];

    if (!empty($cat_ids)) {
        $args['category__in'] = $cat_ids;
    }

    return new WP_Query($args);
}

// Increase view count on each single post view
function hs_set_post_views($postID)
{
    $key = 'post_views_count';
    $count = get_post_meta($postID, $key, true);

    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $key);
        add_post_meta($postID, $key, '0');
    } else {
        $count++;
        update_post_meta($postID, $key, $count);
    }
}

// Trigger view count
function hs_count_post_views()
{
    if (is_single()) {
        hs_set_post_views(get_the_ID());
    }
}

add_action('wp', 'hs_count_post_views');

// Ad Default Images
/*
 * Get a default image with optional classes
 * @param string $type   custom type (example: 'politics', 'sports', etc.)
 * @param string $class  tailwind or any CSS classes for <img>
 * @return string HTML <img> tag
 */
function hs_ad_default_image($type = 'default', $class = '')
{
    // Base path for all default images
    $base = get_template_directory_uri() . '/assets/img/';

    // Map category/type → default image
    $images = [
        'square' => 'promote-your-business-ad-square.png',         
        'long' => 'promote-your-business-ad-long.png',         
        'rectangle' => 'promote-your-business-ad-rectangle.png',         
    ];

    // Choose correct image
    $file = isset($images[$type]) ? $images[$type] : $images['default'];

    // Final URL
    $url = $base . $file;

    // Return image tag with classes
    return '<img src="' . esc_url($url) . '" class="' . esc_attr($class) . '" alt="default-image">';
}



// Default Images
/*
 * Get a default image with optional classes
 * @param string $type   custom type (example: 'politics', 'sports', etc.)
 * @param string $class  tailwind or any CSS classes for <img>
 * @return string HTML <img> tag
 */
function hs_get_default_image($type = 'default', $class = '')
{
    // Base path for all default images
    $base = get_template_directory_uri() . '/assets/img/';

    // Map category/type → default image
    $images = [
        'politics' => 'no-image-political.png',
        'sports' => 'no-image-sport.png',
        'health' => 'no-image-health.png',
        'education' => 'no-image-education.png',
        'default' => 'no-image-default.png',
    ];
    // http:  // localhost/royal-patrika/wp-content/uploads/2025/11/no-image-default.png
    // Choose correct image
    $file = isset($images[$type]) ? $images[$type] : $images['default'];

    // Final URL
    $url = $base . $file;

    // Return image tag with classes
    return '<img src="' . esc_url($url) . '" class="' . esc_attr($class) . '" alt="default-image">';
}

// Convert number to K, M, B format
function hs_format_count($num)
{
    $num = (int) $num;

    if ($num >= 1000000000) {
        return round($num / 1000000000, 1) . 'B';
    } else if ($num >= 1000000) {
        return round($num / 1000000, 1) . 'M';
    } else if ($num >= 1000) {
        return round($num / 1000, 1) . 'K';
    }

    return $num;
}

// Social Media
function hs_get_social_counts()
{
    // Add your IDs + API Keys here
    $yt_channel_id =  'UCOGGkMTKIeRAFl_iht0Z-cQ';
    $yt_api_key = 'AIzaSyAdrAhikG12_z-qKYh-MRYGKgCTet3rz0I';

    $fb_page_id = '639481959502611';
    $fb_token = 'EAAIpUGmWSmIBQBc5nE9PjtPOHWJtexxTzYJOmt2tZCXX0mmUFxLxuAt9FbKVYRrOakjRcscUqXSLxboZABTDSBIurKkvlpdesLJtgxVvqqKPoah5uqDT9xJXaLZBq53TT1OgZBZCxQYP7u6GKIZBL8uqePFnF5ip3SmO6YiaVSrzRUJZAQHmkucGWBhi9oPYlZBuNJgDJOrNYQKM';

    $insta_id = '639481959502611';
    $insta_token = 'EAAIpUGmWSmIBQBc5nE9PjtPOHWJtexxTzYJOmt2tZCXX0mmUFxLxuAt9FbKVYRrOakjRcscUqXSLxboZABTDSBIurKkvlpdesLJtgxVvqqKPoah5uqDT9xJXaLZBq53TT1OgZBZCxQYP7u6GKIZBL8uqePFnF5ip3SmO6YiaVSrzRUJZAQHmkucGWBhi9oPYlZBuNJgDJOrNYQKM';

    return [
        'youtube' => hs_get_youtube_subscribers($yt_channel_id, $yt_api_key),
        'facebook' => hs_get_facebook_followers($fb_page_id, $fb_token),
        'instagram' => hs_get_instagram_followers($insta_id, $insta_token),
    ];
}

// Fetch YouTube Subscribers
function hs_get_youtube_subscribers($channel_id, $api_key)
{
    $cache_key = 'hs_yt_' . $channel_id;

    if ($cached = get_transient($cache_key)) {
        return $cached;
    }

    $url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channel_id}&key={$api_key}";

    $response = wp_remote_get($url);

    if (is_wp_error($response))
        return 0;

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $count = $body['items'][0]['statistics']['subscriberCount'] ?? 0;

    // Cache for 1 hour
    set_transient($cache_key, $count, HOUR_IN_SECONDS);

    return $count;
}

// Facebook Page Followers
function hs_get_facebook_followers($page_id, $access_token)
{
    $cache_key = 'hs_fb_' . $page_id;
    if ($cached = get_transient($cache_key))
        return $cached;

    $url = "https://graph.facebook.com/$page_id?fields=fan_count&access_token=$access_token";
    $response = wp_remote_get($url);

    if (is_wp_error($response))
        return 0;

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $count = $body['fan_count'] ?? 0;

    set_transient($cache_key, $count, HOUR_IN_SECONDS);
    return $count;
}

// Instagram Business Followers
function hs_get_instagram_followers($insta_id, $access_token)
{
    $cache_key = 'hs_insta_' . $insta_id;
    if ($cached = get_transient($cache_key))
        return $cached;

    $url = "https://graph.instagram.com/$insta_id?fields=followers_count&access_token=$access_token";
    $response = wp_remote_get($url);

    if (is_wp_error($response))
        return 0;

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $count = $body['followers_count'] ?? 0;

    set_transient($cache_key, $count, HOUR_IN_SECONDS);
    return $count;
}

// Category Featured Image

/**
 * Add Category Featured Image (Term Meta)
 */
// Add field in category add/edit form
function hs_add_category_featured_image_field($term)
{
    $image_id = get_term_meta($term->term_id, 'category_image_id', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
<div class="form-field pb-5">
    <div scope="row" valign="top"><label for="category_image">Category Image</label></div>
    <div class="flex gap-3 items-center">
        <input type="hidden" name="category_image_id" id="category_image_id" value="<?php echo esc_attr($image_id); ?>">
        <img id="category_image_preview" src="<?php echo esc_url($image_url); ?>"
            style="max-width:150px; display:block; margin-bottom:10px;">
        <button type="button" class="button upload_category_image">Upload Image</button>
        <button type="button" class="button remove_category_image">Remove</button>
    </div>
</div>
<script>
jQuery(function($) {
    let frame;
    $('.upload_category_image').click(function(e) {
        e.preventDefault();
        if (frame) frame.open();
        frame = wp.media({
            title: 'Select or Upload Category Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            $('#category_image_id').val(attachment.id);
            $('#category_image_preview').attr('src', attachment.url);
        });
        frame.open();
    });

    $('.remove_category_image').click(function() {
        $('#category_image_id').val('');
        $('#category_image_preview').attr('src', '');
    });
});
</script>
<?php
}

add_action('category_add_form_fields', 'hs_add_category_featured_image_field');
add_action('category_edit_form_fields', 'hs_add_category_featured_image_field');

// Save category thumbnail
function hs_save_category_featured_image($term_id)
{
    if (isset($_POST['category_image_id'])) {
        update_term_meta($term_id, 'category_image_id', intval($_POST['category_image_id']));
    }
}

add_action('edited_category', 'hs_save_category_featured_image');
add_action('created_category', 'hs_save_category_featured_image');

/* --------------------------------------------------------
   Fetch Latest YouTube Videos using API
-------------------------------------------------------- */
function hs_get_youtube_videos($api_key, $channel_id, $max = 6)
{
    $cache_key = 'hs_yt_videos_cache';
    $cached = get_transient($cache_key);

    // Return cached results for 1 hour
    if ($cached !== false) {
        return $cached;
    }

    $url = "https://www.googleapis.com/youtube/v3/search?part=snippet,id&order=date&channelId={$channel_id}&maxResults={$max}&key={$api_key}";

    $response = wp_remote_get($url);

    if (is_wp_error($response))
        return [];

    $data = json_decode(wp_remote_retrieve_body($response), true);

    set_transient($cache_key, $data, HOUR_IN_SECONDS);

    return $data;
}

/* --------------------------------------------------------
   Get Statistics for a Single Video
-------------------------------------------------------- */
function hs_get_youtube_video_stats($api_key, $video_id)
{
    $url = "https://www.googleapis.com/youtube/v3/videos?part=statistics&id={$video_id}&key={$api_key}";
    $response = wp_remote_get($url);

    if (is_wp_error($response))
        return [];

    $data = json_decode(wp_remote_retrieve_body($response), true);

    return $data['items'][0]['statistics'] ?? [];
}

/* --------------------------------------------------------
   SHORTCODE: Display YouTube Videos Anywhere
   Usage: [youtube_videos count="6"]
-------------------------------------------------------- */

function hs_youtube_videos_shortcode($atts)
{
    $atts = shortcode_atts([
        'count'  => 6,
        'filter' => 'latest', // latest | most_viewed | most_liked | reviews | search | recent
        'layout' => 'grid', // ← NEW PARAM  
        'q'      => '',        // keyword for search
        'days'   => 7,         // for recent filter
    ], $atts);

    // $api_key     = 'AIzaSyB0SHwrWPm9VmU4k1E4EddJnnUMMScUCOQ';
    // $channel_id  = 'UCOGGkMTKIeRAFl_iht0Z-cQ';

    // Get videos based on filter
    $videos = hs_get_filtered_youtube_videos($api_key, $channel_id, $atts);

    if (empty($videos['items'])) {
        return '<p>No videos found.</p>';
    }

    ob_start();
    ?>
<?php
    foreach ($videos['items'] as $video):

    if ($video['id']['kind'] !== 'youtube#video') continue;

    $video_id = $video['id']['videoId'];
    $title    = $video['snippet']['title'];
    $thumb    = $video['snippet']['thumbnails']['medium']['url'];

    // Get stats (views, likes)
    $stats = hs_get_youtube_video_stats($api_key, $video_id);

    // Now switch layouts
    switch ($atts['layout']) {

        /** ------------------------------------------------------------------
         *  LAYOUT 1 — GRID CARD
         *  Thumbnail Top, Title Below
         * ------------------------------------------------------------------*/
        case 'grid':
            ?>
<a href="https://www.youtube.com/watch?v=<?php echo $video_id; ?>" class="block no-underline! transition group "
    target="_blank">
    <div class="bg-white overflow-hidden space-y-2">
        <div class="relative">
            <img src="<?php echo $thumb; ?>" class="object-cover" alt="<?php echo $title; ?>">
            <div
                class="flex justify-center items-center text-white flex-1 absolute inset-0 bg-black/70 group-hover:bg-brand/80 transition">
                <i class="fa-brands fa-youtube text-4xl text-rose-500 group-hover:text-white transition"></i>
            </div>
        </div>
        <h3 class="text-sm font-semibold line-clamp-2 max-h-12 pt-1 group-hover:text-brand text-neutral-900 transition">
            <?php echo $title; ?></h3>
    </div>
</a>
<?php
        break;

        /** ------------------------------------------------------------------
         *  LAYOUT 2 — LIST
         *  Small thumbnail left, text right
         * ------------------------------------------------------------------*/
        case 'list':
            ?>
<a href="https://www.youtube.com/watch?v=<?php echo $video_id; ?>" class="block no-underline! transition group "
    target="_blank">
    <div class="flex gap-3 items-start">
        <div class="relative w-32 h-20 flex justify-center items-center overflow-hidden shrink-0">
            <img src="<?php echo $thumb; ?>" class="object-cover max-w-40" alt="<?php echo $title; ?>">
            <div
                class="flex justify-center items-center text-white flex-1 absolute inset-0 bg-black/70 group-hover:bg-brand/80 transition">
                <i class="fa-brands fa-youtube text-2xl text-rose-500 group-hover:text-white transition"></i>
            </div>
        </div>
        <div class="flex-1 space-y-2">
            <h3
                class="text-xs font-semibold line-clamp-3 max-h-16 pt-1 group-hover:text-brand text-neutral-900 transition">
                <?php echo $title; ?></h3>
            <p class="text-xs text-neutral-600">
                <span><i class="fa-regular fa-eye"></i> <?php echo number_format($stats['viewCount']); ?> </span> <span
                    class="w-5 text-center inline-block">|</span>
                <span><i class="fa-regular fa-thumbs-up"></i> <?php echo number_format($stats['likeCount']); ?></span>
            </p>
        </div>
    </div>
</a>
<?php
        break;

        /** ------------------------------------------------------------------
         *  LAYOUT 3 — HORIZONTAL LARGE
         *  Big thumbnail left, full details right
         * ------------------------------------------------------------------*/
        case 'horizontal':
            ?>
<a href="https://www.youtube.com/watch?v=<?php echo $video_id; ?>" class="block">
    <div class="flex gap-4 items-start p-4 bg-gray-50 border rounded">
        <div class="w-1/2">
            <img src="<?php echo $thumb; ?>" class="w-full rounded">
        </div>
        <div class="w-1/2">
            <h3 class="text-sm font-semibold line-clamp-2 max-h-10"><?php echo $title; ?></h3>
            <p class="text-sm mb-2">
                <strong>Views:</strong> <?php echo number_format($stats['viewCount']); ?><br>
                <strong>Likes:</strong> <?php echo number_format($stats['likeCount']); ?>
            </p>
        </div>
    </div>
</a>
<?php
        break;

        /** ------------------------------------------------------------------
         *  LAYOUT 4 — STATS (No thumbnail)
         * ------------------------------------------------------------------*/
        case 'stats':
            ?>
<div class="p-4 border rounded bg-white">
    <a href="https://www.youtube.com/watch?v=<?php echo $video_id; ?>" class="block">
        <h3 class="text-sm font-semibold line-clamp-2 max-h-10"><?php echo $title; ?></h3>
        <p class="text-xs">
            ⭐ Rating: <?php echo round(($stats['likeCount'] / max($stats['viewCount'],1)) * 5, 1); ?> / 5<br>
            👁 Views: <?php echo number_format($stats['viewCount']); ?><br>
            👍 Likes: <?php echo number_format($stats['likeCount']); ?>
        </p>
    </a>
</div>
<?php
        break;

        /** ------------------------------------------------------------------
         *  LAYOUT 5 — MINIMAL
         * ------------------------------------------------------------------*/
        case 'minimal':
            ?>
<a href="https://www.youtube.com/watch?v=<?php echo $video_id; ?>" class="block">
    <?php echo esc_html($title); ?>
</a>
<?php
        break;

        /** ------------------------------------------------------------------
         *  DEFAULT LAYOUT
         * ------------------------------------------------------------------*/
        default:
            ?>
<div class="bg-white overflow-hidden">
    <img src="<?php echo $thumb; ?>" class="w-full">
    <h3 class="text-sm font-semibold line-clamp-2 max-h-10"><?php echo $title; ?></h3>
</div>
<?php
        break;
    }

endforeach; ?>


<?php
    return ob_get_clean();
}
add_shortcode('youtube_videos', 'hs_youtube_videos_shortcode');

function hs_get_filtered_youtube_videos($api_key, $channel_id, $atts)
{
    $base_url = "https://www.googleapis.com/youtube/v3/search";

    $params = [
        'key'        => $api_key,
        'channelId'  => $channel_id,
        'part'       => 'snippet',
        'type'       => 'video',
        'maxResults' => $atts['count'],
        'order'      => 'date', // default latest
    ];

    switch ($atts['filter']) {

        case 'most_viewed':
            $params['order'] = 'viewCount';
            break;

        case 'most_liked':
            $params['order'] = 'rating';
            break;

        case 'reviews':
            $params['q'] = 'review';
            break;

        case 'search':
            if (!empty($atts['q'])) {
                $params['q'] = sanitize_text_field($atts['q']);
            }
            break;

        case 'recent':
            $date = date(DATE_RFC3339, strtotime('-' . intval($atts['days']) . ' days'));
            $params['publishedAfter'] = $date;
            break;

        default:
            $params['order'] = 'date';
            break;
    }

    // Final API URL
    $url = $base_url . '?' . http_build_query($params);

    $response = wp_remote_get($url);
    if (is_wp_error($response)) return [];

    return json_decode(wp_remote_retrieve_body($response), true);
}