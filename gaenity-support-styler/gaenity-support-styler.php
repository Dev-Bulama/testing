<?php
/**
 * Plugin Name:       Gaenity Support Styler
 * Description:       Companion plugin that forces the Gaenity Support Hub assets to load so layouts stay styled everywhere, including Elementor preview.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Skillscore IT Solutions and Training
 * Author URI:        https://skillscoreitsolutions.com/
 * Text Domain:       gaenity-support-styler
 *
 * @package GaenitySupportStyler
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Gaenity_Support_Styler' ) ) {
    /**
     * Forces Gaenity Support Hub styling assets to load globally.
     */
    class Gaenity_Support_Styler {

        /**
         * Plugin version.
         */
        const VERSION = '1.0.0';

        /**
         * Singleton instance.
         *
         * @var Gaenity_Support_Styler|null
         */
        protected static $instance = null;

        /**
         * Bootstraps the plugin.
         *
         * @return Gaenity_Support_Styler
         */
        public static function instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
                self::$instance->hooks();
            }

            return self::$instance;
        }

        /**
         * Register WordPress hooks.
         */
        protected function hooks() {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 5 );
            add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'enqueue_assets' ), 5 );
            add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_assets' ), 5 );
        }

        /**
         * Enqueue the Support Hub assets with a fallback copy bundled in this helper plugin.
         */
        public function enqueue_assets() {
            $css_url = $this->resolve_asset_url( 'assets/css/support-hub.css', 'css' );
            $js_url  = $this->resolve_asset_url( 'assets/js/support-hub.js', 'js' );

            if ( $css_url ) {
                wp_register_style( 'gaenity-support-styler', $css_url, array(), self::VERSION );
                wp_enqueue_style( 'gaenity-support-styler' );
            }

            if ( $js_url ) {
                wp_register_script( 'gaenity-support-styler', $js_url, array( 'jquery' ), self::VERSION, true );
                wp_enqueue_script( 'gaenity-support-styler' );
            }
        }

        /**
         * Resolve URL for an asset, preferring the main community plugin copy and falling back to the bundled version.
         *
         * @param string $relative_path The asset path relative to the target plugin root.
         * @param string $type          Asset type for validation (css/js).
         *
         * @return string|false
         */
        protected function resolve_asset_url( $relative_path, $type ) {
            $community_path = trailingslashit( WP_PLUGIN_DIR ) . 'gaenity-community/' . $relative_path;

            if ( file_exists( $community_path ) ) {
                $url = plugins_url( '/gaenity-community/' . $relative_path );

                if ( $url ) {
                    return $url;
                }
            }

            $bundled_path = plugin_dir_path( __FILE__ ) . $relative_path;

            if ( file_exists( $bundled_path ) ) {
                return plugins_url( $relative_path, __FILE__ );
            }

            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( sprintf( 'Gaenity Support Styler could not locate %s asset: %s', $type, $relative_path ) );
            }

            return false;
        }
    }
}

Gaenity_Support_Styler::instance();
