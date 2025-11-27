<?php
/**
 * Plugin Name: Fast PDF Viewer
 * Description: Fast, lazy-loading PDF viewer with navigation, zoom, print, and download options.
 * Version: 1.3
 * Author: Rahul Singh (Royal Patrika)
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Add shortcode
add_shortcode('fast_pdf_viewer', 'fpv_render_pdf_viewer');

// Load scripts and styles
add_action('wp_enqueue_scripts', 'fpv_enqueue_scripts');

function fpv_enqueue_scripts() {
    $plugin_url = plugin_dir_url(__FILE__);
    $plugin_dir = plugin_dir_path(__FILE__);

    // Enqueue PDF.js from CDN (more reliable)
    wp_enqueue_script('pdfjs', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js', [], '3.11.174', true);
    
    // Enqueue our custom script
    wp_enqueue_script('fpv-script', $plugin_url . 'js/fpv-script.js', ['pdfjs'], filemtime($plugin_dir . 'js/fpv-script.js'), true);
    
    // Localize script with PDF.js worker
    // wp_localize_script('fpv-script', 'fpv_vars', [
    //     'pdf_worker' => 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
    //     'plugin_url' => $plugin_url
    // ]);
    // Localize script with PDF.js worker (using jsDelivr)
        wp_localize_script('fpv-script', 'fpv_vars', [
            'pdf_worker' => 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js',
            'plugin_url' => $plugin_url
        ]);

    // Enqueue styles
    wp_enqueue_style('fpv-style', $plugin_url . 'css/fpv-style.css', [], filemtime($plugin_dir . 'css/fpv-style.css'));
}

function fpv_render_pdf_viewer($atts) {
    $atts = shortcode_atts([
        'url' => '',
        'height' => '600px',
        'width' => '100%'
    ], $atts);

    if (empty($atts['url'])) {
        return '<div class="fpv-error">PDF URL is missing.</div>';
    }

    // Generate unique ID for multiple PDFs on same page
    $unique_id = 'fpv_' . uniqid();

    ob_start();
    ?>
    <div class="fpv-wrapper" id="<?php echo esc_attr($unique_id); ?>" data-url="<?php echo esc_url($atts['url']); ?>" data-height="<?php echo esc_attr($atts['height']); ?>" style="width: <?php echo esc_attr($atts['width']); ?>;">
        <div class="fpv-toolbar">
            <button class="fpv-btn fpv-prev" type="button"><i class="fa fa-chevron-left" aria-hidden="true"></i>Prev</button>
            <span class="fpv-page-info">
                Page: <span class="page_num">1</span> / <span class="page_count">?</span>
            </span>
            <button class="fpv-btn fpv-next" type="button">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
            <button class="fpv-btn fpv-zoom-in" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i> Zoom In</button>
            <button class="fpv-btn fpv-zoom-out" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i> Zoom Out</button>
            <button class="fpv-btn fpv-print" type="button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
            <a class="fpv-btn fpv-download" href="<?php echo esc_url($atts['url']); ?>" download target="_blank"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download</a>
        </div>
        <div class="fpv-canvas-container" style="height: <?php echo esc_attr($atts['height']); ?>; overflow: auto; position: relative;">
            <div class="fpv-loader" style="display: none;">Loading PDF...</div>
            <canvas class="pdf-canvas"></canvas>
        </div>
    </div>
    <?php
    return ob_get_clean();
}