<?php
/**
 * Theme setup and asset loading.
 */

define( 'TEKGURUS_VERSION', '1.0.0' );

define( 'TEKGURUS_THEME_DIR', get_template_directory() );
define( 'TEKGURUS_THEME_URI', get_template_directory_uri() );

add_action( 'after_setup_theme', 'tekgurus_setup' );
/**
 * Configure theme defaults and register support for WordPress features.
 */
function tekgurus_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'menus' );
    add_theme_support( 'bricks' );
    add_theme_support( 'align-wide' );

    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu', 'tekgurus' ),
            'mobile'  => __( 'Mobile Menu', 'tekgurus' ),
        )
    );
}

add_action( 'wp_enqueue_scripts', 'tekgurus_enqueue_assets' );
/**
 * Load frontend assets.
 */
function tekgurus_enqueue_assets() {
    wp_enqueue_style( 'tekgurus-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap', array(), null );
    wp_enqueue_script( 'tekgurus-tailwind', 'https://cdn.tailwindcss.com', array(), null, false );

    $tailwind_config = 'tailwind.config = { theme: { extend: { colors: { primary: "#e5252a", dark: "#000000", light: "#ffffff" }, fontFamily: { sans: ["Poppins", "sans-serif"] } } } };';
    wp_add_inline_script( 'tekgurus-tailwind', $tailwind_config, 'after' );

    wp_enqueue_style( 'tekgurus-style', TEKGURUS_THEME_URI . '/style.css', array( 'tekgurus-google-fonts' ), TEKGURUS_VERSION );
    wp_enqueue_script( 'tekgurus-main', TEKGURUS_THEME_URI . '/js/main.js', array(), TEKGURUS_VERSION, true );
}

add_filter( 'body_class', 'tekgurus_body_classes' );
/**
 * Add custom body classes.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function tekgurus_body_classes( $classes ) {
    $classes[] = 'bg-dark';
    $classes[] = 'text-light';
    $classes[] = 'font-sans';
    return $classes;
}

add_filter( 'nav_menu_css_class', 'tekgurus_add_nav_link_class', 10, 4 );
/**
 * Apply nav link classes to menu items for styling parity.
 *
 * @param array  $classes Existing classes.
 * @param object $item    Menu item.
 * @param object $args    Menu arguments.
 * @param int    $depth   Depth level.
 * @return array
 */
function tekgurus_add_nav_link_class( $classes, $item, $args, $depth ) {
    return array_unique( $classes );
}

add_filter( 'nav_menu_link_attributes', 'tekgurus_add_nav_link_attributes', 10, 4 );
/**
 * Add data attributes required for mega menu interactions.
 *
 * @param array  $atts  Link attributes.
 * @param object $item  Menu item object.
 * @param object $args  Menu args.
 * @param int    $depth Depth level.
 * @return array
 */
function tekgurus_add_nav_link_attributes( $atts, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        if ( 0 === $depth ) {
            $slug                    = sanitize_title( $item->title );
            $atts['data-menu-target'] = $slug;
            $atts['aria-haspopup']    = 'true';
            $atts['aria-expanded']    = 'false';
            $atts['class']            = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'nav-link';
        } else {
            $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' submenu-link' : 'submenu-link';
        }
    }

    if ( isset( $args->theme_location ) && 'mobile' === $args->theme_location ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' mobile-link' : 'mobile-link';
    }

    return $atts;
}

add_action( 'after_setup_theme', 'tekgurus_register_bricks_locations' );
/**
 * Register header and footer locations for Bricks Builder templates if the plugin is active.
 */
function tekgurus_register_bricks_locations() {
    if ( function_exists( 'bricks_register_theme_locations' ) ) {
        bricks_register_theme_locations(
            array(
                'header' => __( 'Header', 'tekgurus' ),
                'footer' => __( 'Footer', 'tekgurus' ),
            )
        );
    }
}

/**
 * Fallback mobile menu replicating static structure if no menu is assigned.
 */
function tekgurus_mobile_menu_fallback() {
    $links = array(
        array(
            'label' => __( 'Overview', 'tekgurus' ),
            'url'   => home_url( '/about/' ),
        ),
        array(
            'label' => __( 'Mission & Vision', 'tekgurus' ),
            'url'   => home_url( '/about/#mission' ),
        ),
        array(
            'label' => __( 'Leadership', 'tekgurus' ),
            'url'   => home_url( '/about/#leadership' ),
        ),
        array(
            'label' => __( 'Our Approach', 'tekgurus' ),
            'url'   => home_url( '/about/#approach' ),
        ),
        array(
            'label' => __( 'Cloud Strategy & Advisory', 'tekgurus' ),
            'url'   => home_url( '/services/#strategy' ),
        ),
        array(
            'label' => __( 'Implementation & Migration', 'tekgurus' ),
            'url'   => home_url( '/services/#implementation' ),
        ),
        array(
            'label' => __( 'Security & Governance', 'tekgurus' ),
            'url'   => home_url( '/services/#security' ),
        ),
        array(
            'label' => __( 'Optimization & Automation', 'tekgurus' ),
            'url'   => home_url( '/services/#optimization' ),
        ),
        array(
            'label' => __( 'Managed Services', 'tekgurus' ),
            'url'   => home_url( '/services/#managed' ),
        ),
        array(
            'label' => __( 'Training & Enablement', 'tekgurus' ),
            'url'   => home_url( '/services/#training' ),
        ),
        array(
            'label' => __( 'Hybrid & Multi-Cloud', 'tekgurus' ),
            'url'   => home_url( '/solutions/#hybrid' ),
        ),
        array(
            'label' => __( 'AI Automation', 'tekgurus' ),
            'url'   => home_url( '/solutions/#automation' ),
        ),
        array(
            'label' => __( 'Data Analytics', 'tekgurus' ),
            'url'   => home_url( '/solutions/#analytics' ),
        ),
        array(
            'label' => __( 'Digital Transformation', 'tekgurus' ),
            'url'   => home_url( '/solutions/#transformation' ),
        ),
        array(
            'label' => __( 'Industry Solutions', 'tekgurus' ),
            'url'   => home_url( '/solutions/#industry' ),
        ),
        array(
            'label' => __( 'Blogs', 'tekgurus' ),
            'url'   => home_url( '/insights/#blogs' ),
        ),
        array(
            'label' => __( 'News', 'tekgurus' ),
            'url'   => home_url( '/insights/#news' ),
        ),
        array(
            'label' => __( 'Case Studies', 'tekgurus' ),
            'url'   => home_url( '/insights/#cases' ),
        ),
        array(
            'label' => __( 'Contact TekGurus', 'tekgurus' ),
            'url'   => home_url( '/contact/' ),
        ),
        array(
            'label' => __( '+234 904 920 6989', 'tekgurus' ),
            'url'   => 'tel:+2349049206989',
        ),
        array(
            'label' => __( 'info@thetekgurus.com', 'tekgurus' ),
            'url'   => 'mailto:info@thetekgurus.com',
        ),
    );
    echo '<ul class="space-y-2 flex flex-col">';
    foreach ( $links as $link ) {
        echo '<li>';
        printf(
            '<a class="mobile-link" href="%1$s">%2$s</a>',
            esc_url( $link['url'] ),
            esc_html( $link['label'] )
        );
        echo '</li>';
    }
    echo '</ul>';
}
