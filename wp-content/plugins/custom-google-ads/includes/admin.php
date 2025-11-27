<?php
if (!defined('ABSPATH')) exit;

// Add admin menu
add_action('admin_menu', 'cga_add_admin_menu');
function cga_add_admin_menu() {
    add_menu_page(
        'Custom Google Ads',
        'Custom Ads',
        'manage_options',
        'custom-google-ads',
        'cga_admin_page',
        'dashicons-money-alt',
        80
    );
}

// Register settings
add_action('admin_init', 'cga_register_settings');
function cga_register_settings() {
    for ($i = 1; $i <= 3; $i++) {
        register_setting('cga_options', 'cga_ad_type_' . $i);
        register_setting('cga_options', 'cga_image_ad_' . $i);
        register_setting('cga_options', 'cga_adsense_code_' . $i);
        register_setting('cga_options', 'cga_ad_position_' . $i);
        register_setting('cga_options', 'cga_ad_link_' . $i);
    }
}

// Admin page content
function cga_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Handle form submission
    if (isset($_POST['submit'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully!</p></div>';
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p>Configure up to 3 different ad positions to be displayed within your post content.</p>
        
        <form method="post" action="options.php">
            <?php settings_fields('cga_options'); ?>
            <?php do_settings_sections('cga_options'); ?>
            
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="postbox" style="margin-bottom: 20px;">
                <div class="postbox-header">
                    <h2 class="hndle">Ad Position #<?php echo absint($i); ?></h2>
                </div>
                <div class="inside" style="padding: 20px;">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Ad Type</th>
                            <td>
                                <select name="cga_ad_type_<?php echo absint($i); ?>" class="cga-ad-type" data-row="<?php echo absint($i); ?>">
                                    <option value="none" <?php selected(get_option('cga_ad_type_' . $i), 'none'); ?>>Disabled</option>
                                    <option value="image" <?php selected(get_option('cga_ad_type_' . $i), 'image'); ?>>Image Ad</option>
                                    <option value="adsense" <?php selected(get_option('cga_ad_type_' . $i), 'adsense'); ?>>AdSense Code</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr valign="top" class="cga-image-row cga-row-<?php echo absint($i); ?>" style="<?php echo (get_option('cga_ad_type_' . $i) != 'image' ? 'display:none;' : ''); ?>">
                            <th scope="row">Image Ad</th>
                            <td>
                                <?php
                                $image_id = get_option('cga_image_ad_' . $i);
                                $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
                                ?>
                                <div class="cga-image-preview" style="margin-bottom: 10px;">
                                    <?php if ($image_url): ?>
                                        <img src="<?php echo esc_url($image_url); ?>" style="max-width: 300px; height: auto; display: block; border: 1px solid #ddd; padding: 5px;">
                                        <button type="button" class="button cga-remove-image" data-target="#cga_image_ad_<?php echo absint($i); ?>" style="margin-top: 5px;">Remove Image</button>
                                    <?php else: ?>
                                        <p>No image selected</p>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="cga_image_ad_<?php echo absint($i); ?>" id="cga_image_ad_<?php echo absint($i); ?>" value="<?php echo esc_attr($image_id); ?>">
                                <button type="button" class="button cga-upload-image" data-target="#cga_image_ad_<?php echo absint($i); ?>">Select Image</button>
                            </td>
                        </tr>
                        
                        <tr valign="top" class="cga-link-row cga-row-<?php echo absint($i); ?>" style="<?php echo (get_option('cga_ad_type_' . $i) != 'image' ? 'display:none;' : ''); ?>">
                            <th scope="row">Image Link URL (Optional)</th>
                            <td>
                                <input type="url" name="cga_ad_link_<?php echo absint($i); ?>" value="<?php echo esc_attr(get_option('cga_ad_link_' . $i)); ?>" class="large-text" placeholder="https://example.com">
                                <p class="description">If provided, the image will be clickable and link to this URL.</p>
                            </td>
                        </tr>
                        
                        <tr valign="top" class="cga-adsense-row cga-row-<?php echo absint($i); ?>" style="<?php echo (get_option('cga_ad_type_' . $i) != 'adsense' ? 'display:none;' : ''); ?>">
                            <th scope="row">AdSense Code</th>
                            <td>
                                <textarea name="cga_adsense_code_<?php echo absint($i); ?>" rows="8" cols="50" class="large-text code"><?php echo esc_textarea(get_option('cga_adsense_code_' . $i)); ?></textarea>
                                <p class="description">Paste your complete AdSense code here (including &lt;script&gt; tags).</p>
                            </td>
                        </tr>
                        
                        <tr valign="top" class="cga-position-row" style="<?php echo (get_option('cga_ad_type_' . $i) == 'none' ? 'display:none;' : ''); ?>">
                            <th scope="row">Position in Content</th>
                            <td>
                                <select name="cga_ad_position_<?php echo absint($i); ?>">
                                    <option value="after_first" <?php selected(get_option('cga_ad_position_' . $i), 'after_first'); ?>>After first paragraph</option>
                                    <option value="middle" <?php selected(get_option('cga_ad_position_' . $i), 'middle'); ?>>Middle of content</option>
                                    <option value="before_last" <?php selected(get_option('cga_ad_position_' . $i), 'before_last'); ?>>Before last paragraph</option>
                                    <option value="after_second" <?php selected(get_option('cga_ad_position_' . $i), 'after_second'); ?>>After second paragraph</option>
                                    <option value="after_third" <?php selected(get_option('cga_ad_position_' . $i), 'after_third'); ?>>After third paragraph</option>
                                </select>
                                <p class="description">Choose where this ad should appear in your content.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php endfor; ?>
            
            <?php submit_button('Save Ad Settings'); ?>
        </form>
        
        <div class="postbox" style="margin-top: 30px;">
            <div class="postbox-header">
                <h2 class="hndle">How to Use</h2>
            </div>
            <div class="inside" style="padding: 20px;">
                <ol>
                    <li><strong>Choose Ad Type:</strong> Select either "Image Ad" or "AdSense Code" for each position.</li>
                    <li><strong>Configure Ad Content:</strong>
                        <ul style="margin-left: 20px;">
                            <li>For Image Ads: Upload an image and optionally add a link URL</li>
                            <li>For AdSense: Paste your complete AdSense code</li>
                        </ul>
                    </li>
                    <li><strong>Set Position:</strong> Choose where the ad should appear in your content.</li>
                    <li><strong>Save Settings:</strong> Click "Save Ad Settings" to apply changes.</li>
                </ol>
                <p><strong>Note:</strong> Ads will automatically appear in your post content based on the positions you've configured.</p>
            </div>
        </div>
    </div>
    
    <style>
    .cga-image-preview img {
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .form-table th {
        width: 200px;
        font-weight: 600;
    }
    .postbox {
        border: 1px solid #c3c4c7;
        border-radius: 4px;
    }
    .postbox-header {
        border-bottom: 1px solid #c3c4c7;
        background: #f6f7f7;
    }
    .postbox-header h2 {
        margin: 0;
        padding: 12px 20px;
        font-size: 16px;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Handle ad type changes
        $('.cga-ad-type').change(function() {
            var adType = $(this).val();
            var rowNum = $(this).data('row');
            
            // Hide all rows for this ad position
            $('.cga-row-' + rowNum).hide();
            
            // Show relevant rows based on ad type
            if (adType == 'image') {
                $('.cga-image-row.cga-row-' + rowNum).show();
                $('.cga-link-row.cga-row-' + rowNum).show();
                $('.cga-position-row').show();
            } else if (adType == 'adsense') {
                $('.cga-adsense-row.cga-row-' + rowNum).show();
                $('.cga-position-row').show();
            } else {
                $('.cga-position-row').hide();
            }
        });
        
        // Handle image upload
        $('.cga-upload-image').click(function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            var preview = $(this).siblings('.cga-image-preview');
            
            var frame = wp.media({
                title: 'Select Ad Image',
                button: { text: 'Use this image' },
                multiple: false,
                library: { type: 'image' }
            });
            
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $(target).val(attachment.id);
                preview.html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto; display: block; border: 1px solid #ddd; padding: 5px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"><button type="button" class="button cga-remove-image" data-target="' + target + '" style="margin-top: 5px;">Remove Image</button>');
            });
            
            frame.open();
        });
        
        // Handle image removal
        $(document).on('click', '.cga-remove-image', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            var preview = $(this).parent();
            
            $(target).val('');
            preview.html('<p>No image selected</p>');
        });
    });
    </script>
    <?php
}

// Enqueue admin scripts and styles
add_action('admin_enqueue_scripts', 'cga_admin_scripts');
function cga_admin_scripts($hook) {
    if ('toplevel_page_custom-google-ads' !== $hook) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('jquery');
}