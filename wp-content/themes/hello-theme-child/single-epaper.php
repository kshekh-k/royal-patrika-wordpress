<?php
// Make sure you have the necessary WordPress functions
if (!defined('ABSPATH')) {
    echo "No Script";
    exit;
}

get_header(); 
global $post;
$current_id = $post->ID;
$post_pdf = get_field('e-paper', $current_id);
?>

<div class="content-wrapper p-2" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <aside class="google-ads left">
        <?php if (is_active_sidebar('google-ads-left')) : ?>
            <?php dynamic_sidebar('google-ads-left'); ?>
        <?php endif; ?>
    </aside>
    
    <section class="e-paper-single-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 epaper-viewer pb-3">
                    <?php if ($post_pdf) : ?>
                        <div class="pdf-viewer-container">
                            <?php echo do_shortcode('[fast_pdf_viewer url="' . esc_url($post_pdf) . '" height="800px"]'); ?>
                        </div>
                        
                        <!-- External navigation buttons (optional) -->
                        <div class="external-control mt-3 text-center">
                            <button id="external-prev" class="btn btn-secondary me-2"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                            <button id="external-next" class="btn btn-secondary me-2"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                           <!-- <button id="external-zoom-in" class="btn btn-secondary me-2">➕ Zoom In</button>
                            <button id="external-zoom-out" class="btn btn-secondary">➖ Zoom Out</button>-->
                        </div>
                    <?php else : ?>
                        <div class="alert alert-warning">
                            <p>No PDF file found. Please upload a PDF file in the 'e-paper' field.</p>
                        </div>
                    <?php endif; ?>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    // External controls for the PDF viewer
    const wrapper = document.querySelector('.fpv-wrapper');
    
    if (wrapper) {
        // Get the buttons from the PDF viewer
        const prevBtn = wrapper.querySelector('.fpv-prev');
        const nextBtn = wrapper.querySelector('.fpv-next');
        const zoomInBtn = wrapper.querySelector('.fpv-zoom-in');
        const zoomOutBtn = wrapper.querySelector('.fpv-zoom-out');
        
        // External control buttons
        const externalPrev = document.getElementById('external-prev');
        const externalNext = document.getElementById('external-next');
        const externalZoomIn = document.getElementById('external-zoom-in');
        const externalZoomOut = document.getElementById('external-zoom-out');
        
        // Connect external buttons to PDF viewer buttons
        if (externalPrev && prevBtn) {
            externalPrev.addEventListener('click', () => prevBtn.click());
        }
        
        if (externalNext && nextBtn) {
            externalNext.addEventListener('click', () => nextBtn.click());
        }
        
        if (externalZoomIn && zoomInBtn) {
            externalZoomIn.addEventListener('click', () => zoomInBtn.click());
        }
        
        if (externalZoomOut && zoomOutBtn) {
            externalZoomOut.addEventListener('click', () => zoomOutBtn.click());
        }
    }
    
    // Remove the old iframe-based script as it's no longer needed
    // The new PDF viewer uses canvas-based rendering
});
</script>

<style>
.pdf-viewer-container {
    margin: 20px 0;
}

.external-controls {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.external-controls .btn {
    margin: 0 5px;
}

.alert-warning {
    padding: 15px;
    margin: 20px 0;
    border: 1px solid #ffeaa7;
    background-color: #fdcb6e;
    color: #2d3436;
    border-radius: 4px;
}
</style>

<?php get_footer(); ?>