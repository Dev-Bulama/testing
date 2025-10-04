<?php
/**
 * Plugin Name: Gaenity Support Hub
 * Description: Elegant one-page community and resource hub with polished styling and Elementor/shortcode support for the Gaenity network.
 * Version: 3.0.0
 * Author: skillscore IT solutions and training
 * Text Domain: gaenity-community
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'GAENITY_SUPPORT_HUB_FILE', __FILE__ );
define( 'GAENITY_SUPPORT_HUB_PATH', plugin_dir_path( __FILE__ ) );
define( 'GAENITY_SUPPORT_HUB_URL', plugin_dir_url( __FILE__ ) );
require_once GAENITY_SUPPORT_HUB_PATH . 'includes/class-gaenity-support-hub.php';

Gaenity_Support_Hub::instance();

register_activation_hook( __FILE__, array( 'Gaenity_Support_Hub', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Gaenity_Support_Hub', 'deactivate' ) );
