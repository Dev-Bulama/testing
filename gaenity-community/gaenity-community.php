<?php
/**
 * Plugin Name: Gaeinity Community Suite
 * Description: Multipurpose community plugin providing resources, forums, polls, chat, and expert connections for the Gaenity business community.
 * Version: 2.1.0
 * Author: OpenAI Assistant
 * Text Domain: gaenity-community
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'GAENITY_COMMUNITY_PLUGIN_FILE' ) ) {
    define( 'GAENITY_COMMUNITY_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'Gaeinity_Community_Plugin' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-gaenity-community-plugin.php';
}

register_activation_hook( __FILE__, array( 'Gaeinity_Community_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Gaeinity_Community_Plugin', 'deactivate' ) );

global $gaenity_community_plugin;
$gaenity_community_plugin = new Gaeinity_Community_Plugin();
$gaenity_community_plugin->init();
