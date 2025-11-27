<?php
if (!defined('ABSPATH')) exit;

// Display ads in content
add_filter('the_content', 'cga_display_ads_in_content', 20);
function cga_display_ads_in_content($content) {
    // Only apply to single posts/pages in the main query
    if (!is_singular() || !in_the_loop() || !is_main_query()) {
        return $content;
    }
    
    // Skip if content is too short
    if (strlen($content) < 200) {
        return $content;
    }
    
    // Split content into paragraphs
    $paragraphs = preg_split('/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    $paragraph_count = 0;
    $total_paragraphs = 0;
    
    // Count actual paragraphs (not empty ones)
    foreach ($paragraphs as $paragraph) {
        if (strpos($paragraph, '</p>') === false && trim(strip_tags($paragraph)) !== '') {
            $total_paragraphs++;
        }
    }
    
    // Need at least 3 paragraphs to insert ads
    if ($total_paragraphs < 3) {
        return $content;
    }
    
    // Process each ad position
    $ads_inserted = array();
    
    for ($i = 1; $i <= 3; $i++) {
        $ad_type = get_option('cga_ad_type_' . $i);
        
        // Skip if ad is disabled
        if ($ad_type === 'none' || empty($ad_type)) {
            continue;
        }
        
        $ad_position = get_option('cga_ad_position_' . $i, 'middle');
        $ad_html = cga_generate_ad_html($i, $ad_type);
        
        // Skip if no ad content
        if (empty($ad_html)) {
            continue;
        }
        
        // Determine insertion position
        $insert_position = cga_calculate_insert_position($ad_position, $total_paragraphs);
        
        // Avoid duplicate positions
        if (in_array($insert_position, $ads_inserted)) {
            $insert_position = cga_find_alternative_position($insert_position, $ads_inserted, $total_paragraphs);
        }
        
        if ($insert_position > 0) {
            $ads_inserted[] = $insert_position;
            $paragraphs = cga_insert_ad_at_position($paragraphs, $ad_html, $insert_position);
            $total_paragraphs++; // Update count after insertion
        }
    }
    
    return implode('', $paragraphs);
}

// Generate HTML for ad based on type
function cga_generate_ad_html($ad_number, $ad_type) {
    $ad_html = '';
    
    if ($ad_type === 'image') {
        $image_id = get_option('cga_image_ad_' . $ad_number);
        $ad_link = get_option('cga_ad_link_' . $ad_number);
        
        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            
            if ($image_url) {
                $image_tag = '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt ?: 'Advertisement') . '" style="max-width:100%; height:auto; display:block; margin:0 auto;">';
                
                if (!empty($ad_link)) {
                    $ad_html = '<a href="' . esc_url($ad_link) . '" target="_blank" rel="nofollow sponsored">' . $image_tag . '</a>';
                } else {
                    $ad_html = $image_tag;
                }
            }
        }
    } elseif ($ad_type === 'adsense') {
        $adsense_code = get_option('cga_adsense_code_' . $ad_number);
        if (!empty($adsense_code)) {
            // Allow specific AdSense tags
            $allowed_tags = array(
                'ins' => array(
                    'class' => array(),
                    'style' => array(),
                    'data-ad-client' => array(),
                    'data-ad-slot' => array(),
                    'data-ad-format' => array(),
                    'data-full-width-responsive' => array(),
                    'data-ad-layout' => array(),
                    'data-ad-layout-key' => array()
                ),
                'script' => array(
                    'async' => array(),
                    'src' => array(),
                    'crossorigin' => array()
                )
            );
            $ad_html = wp_kses($adsense_code, $allowed_tags);
        }
    }
    
    if (!empty($ad_html)) {
        return '<div class="custom-google-ad ad-position-' . absint($ad_number) . '" style="text-align:center; margin:25px 0; padding:15px; background:#f9f9f9; border:1px solid #e0e0e0; border-radius:4px;">' . $ad_html . '</div>';
    }
    
    return '';
}

// Calculate where to insert ad based on position setting
function cga_calculate_insert_position($position, $total_paragraphs) {
    switch ($position) {
        case 'after_first':
            return 1;
        case 'after_second':
            return min(2, $total_paragraphs - 1);
        case 'after_third':
            return min(3, $total_paragraphs - 1);
        case 'middle':
            return floor($total_paragraphs / 2);
        case 'before_last':
            return max(1, $total_paragraphs - 2);
        default:
            return floor($total_paragraphs / 2);
    }
}

// Find alternative position if primary position is taken
function cga_find_alternative_position($preferred_position, $used_positions, $total_paragraphs) {
    // Try positions around the preferred one
    for ($offset = 1; $offset <= 3; $offset++) {
        $alt_pos = $preferred_position + $offset;
        if ($alt_pos <= $total_paragraphs - 1 && !in_array($alt_pos, $used_positions)) {
            return $alt_pos;
        }
        
        $alt_pos = $preferred_position - $offset;
        if ($alt_pos > 0 && !in_array($alt_pos, $used_positions)) {
            return $alt_pos;
        }
    }
    
    return 0; // No suitable position found
}

// Insert ad at specific position in paragraphs array
function cga_insert_ad_at_position($paragraphs, $ad_html, $position) {
    $current_paragraph = 0;
    $insert_index = 0;
    
    for ($i = 0; $i < count($paragraphs); $i++) {
        // Check if this is a paragraph end tag
        if (strpos($paragraphs[$i], '</p>') !== false) {
            $current_paragraph++;
            if ($current_paragraph == $position) {
                $insert_index = $i + 1;
                break;
            }
        }
    }
    
    if ($insert_index > 0) {
        array_splice($paragraphs, $insert_index, 0, $ad_html);
    }
    
    return $paragraphs;
}

// Add custom CSS for ads
add_action('wp_head', 'cga_add_custom_css');
function cga_add_custom_css() {
    ?>
    <style type="text/css">
    .custom-google-ad {
        clear: both;
        margin: 25px 0;
        padding: 15px;
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        text-align: center;
    }
    
    .custom-google-ad img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        border-radius: 4px;
    }
    
    .custom-google-ad a {
        display: inline-block;
        text-decoration: none;
    }
    
    .custom-google-ad a:hover img {
        opacity: 0.9;
        transition: opacity 0.3s ease;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .custom-google-ad {
            margin: 20px 0;
            padding: 10px;
        }
    }
    
    /* Integration with news-content wrapper */
    .news-content .custom-google-ad {
        margin: 25px auto;
        max-width: 100%;
    }
    </style>
    <?php
}