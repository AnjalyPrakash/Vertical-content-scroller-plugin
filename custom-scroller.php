<?php
/*
Plugin Name: Vertical Content Scroller
Description: Scroll custom content vertically with customizable speed for a dynamic user experience.
Version: 1.0
Author: PottersWheel Media
*/

function custom_content_scroller_enqueue_scripts() {
    if (!is_admin()) { 
        wp_enqueue_style('custom-content-scroller-style', plugins_url('custom-content-scroller.css', __FILE__));
        wp_enqueue_script('jquery'); 
        wp_enqueue_script('custom-content-scroller-script', plugins_url('custom-content-scroller.js', __FILE__), array('jquery'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'custom_content_scroller_enqueue_scripts');


function custom_content_scroller_add_admin_menu() {
    add_menu_page('Content Scroller Settings', 'Vertical Content Scroller', 'manage_options', 'content_scroller_settings', 'custom_content_scroller_settings_page');
}
add_action('admin_menu', 'custom_content_scroller_add_admin_menu');

function custom_content_scroller_register_settings() {
    register_setting('content_scroller_settings_group', 'content_scroller_speed');
    for ($i = 1; $i <= 12; $i++) {
        register_setting('content_scroller_settings_group', "content_scroller_content_$i", 'wp_kses_post');
        register_setting('content_scroller_settings_group', "content_scroller_url_$i", 'esc_url_raw');
    }
}
add_action('admin_init', 'custom_content_scroller_register_settings');

function custom_content_scroller_settings_page() {
    ?>
    <div class="wrap">
        <h2>Vertical Content Scroller Settings</h2>
        <h3>use ShortCode :- vertical_custom_content_scroller</h3>
        <form method="post" action="options.php">
            <?php settings_fields('content_scroller_settings_group'); ?>
            <?php do_settings_sections('content_scroller_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Scroll Speed (ms)</th>
                    <td><input type="number" name="content_scroller_speed" value="<?php echo esc_attr(get_option('content_scroller_speed', '3000')); ?>" /></td>
                </tr>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                <tr valign="top">
                    <th scope="row">Content <?php echo $i; ?> URL</th>
                    <td><input type="url" name="content_scroller_url_<?php echo $i; ?>" value="<?php echo esc_attr(get_option("content_scroller_url_$i")); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Content <?php echo $i; ?></th>
                    <td><textarea name="content_scroller_content_<?php echo $i; ?>" rows="4" cols="50"><?php echo esc_textarea(get_option("content_scroller_content_$i")); ?></textarea></td>
                </tr>
                <?php endfor; ?>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
function custom_content_scroller_shortcode($atts) {
    $speed = get_option('content_scroller_speed', '3000'); // Default speed if not set
    if (!is_numeric($speed) || $speed <= 0) {
        $speed = 3000; // Set a default fallback speed
    }
    ob_start();
    ?>
    <div class='custom-content-scroller' data-speed='<?php echo esc_attr($speed); ?>'>
        <div class='scroller-content'>
            <?php for ($i = 1; $i <= 12; $i++):
                $url = get_option("content_scroller_url_$i");
                $content = get_option("content_scroller_content_$i");
                if (!empty($content)): ?>
                    <div>
                        <?php if (!empty($url)): ?>
                            <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($content); ?></a>
                        <?php else: ?>
                            <?php echo esc_html($content); ?>
                        <?php endif; ?>
                    </div>
                <?php endif;
            endfor; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}


add_shortcode('vertical_custom_content_scroller', 'custom_content_scroller_shortcode');
