<?php
/**
 * Header template.
 *
 * @package TekGurus
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header fixed top-0 left-0 w-full z-50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-20">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center space-x-3">
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/logo/tekgurus-logo.svg' ); ?>" alt="<?php esc_attr_e( 'TekGurus logo', 'tekgurus' ); ?>" class="h-12 w-auto object-contain">
                <span class="text-xl font-semibold tracking-wide uppercase"><?php bloginfo( 'name' ); ?></span>
            </a>
            <nav class="hidden lg:flex items-center space-x-8">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'flex items-center space-x-8',
                        'container'      => false,
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav>
            <div class="hidden lg:block">
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Talk to Our Experts', 'tekgurus' ); ?></a>
            </div>
            <button class="lg:hidden text-light" id="mobile-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle navigation', 'tekgurus' ); ?>">
                <span class="sr-only"><?php esc_html_e( 'Open Menu', 'tekgurus' ); ?></span>
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
    <div class="mega-menu-wrapper hidden lg:block">
        <div class="max-w-7xl mx-auto px-6">
            <div class="relative">
                <div class="mega-menu" data-menu="about">
                    <div class="grid grid-cols-4 gap-8">
                        <div class="col-span-2 space-y-4">
                            <h3 class="text-2xl font-semibold"><?php esc_html_e( 'Discover TekGurus', 'tekgurus' ); ?></h3>
                            <p class="text-sm leading-relaxed text-gray-200"><?php esc_html_e( 'We build resilient cloud ecosystems that move with the pace of your ambitions, pairing advanced engineering with human-centered insight.', 'tekgurus' ); ?></p>
                            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="link-more"><?php esc_html_e( 'Explore our story', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1 space-y-3">
                            <a href="<?php echo esc_url( home_url( '/about/#mission' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Mission & Vision', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/about/#values' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Core Values', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/about/#leadership' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Leadership', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/about/#approach' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Our Approach', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1">
                            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero1.svg' ); ?>" alt="<?php esc_attr_e( 'About TekGurus', 'tekgurus' ); ?>" class="rounded-xl w-full h-full object-cover">
                        </div>
                    </div>
                </div>
                <div class="mega-menu" data-menu="services">
                    <div class="grid grid-cols-4 gap-8">
                        <div class="col-span-2 space-y-4">
                            <h3 class="text-2xl font-semibold"><?php esc_html_e( 'Services crafted for modern teams', 'tekgurus' ); ?></h3>
                            <p class="text-sm leading-relaxed text-gray-200"><?php esc_html_e( 'From strategy to enablement, TekGurus orchestrates every layer of your cloud journey with clarity, security, and measurable impact.', 'tekgurus' ); ?></p>
                            <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="link-more"><?php esc_html_e( 'View all services', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1 space-y-3">
                            <a href="<?php echo esc_url( home_url( '/services/#strategy' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Cloud Strategy & Advisory', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/services/#implementation' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Implementation & Migration', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/services/#security' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Security & Governance', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/services/#optimization' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Optimization & Automation', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/services/#managed' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Managed Cloud Services', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/services/#training' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Training & Enablement', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1">
                            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero2.svg' ); ?>" alt="<?php esc_attr_e( 'TekGurus services', 'tekgurus' ); ?>" class="rounded-xl w-full h-full object-cover">
                        </div>
                    </div>
                </div>
                <div class="mega-menu" data-menu="solutions">
                    <div class="grid grid-cols-4 gap-8">
                        <div class="col-span-2 space-y-4">
                            <h3 class="text-2xl font-semibold"><?php esc_html_e( 'Solutions engineered for scale', 'tekgurus' ); ?></h3>
                            <p class="text-sm leading-relaxed text-gray-200"><?php esc_html_e( 'Activate hybrid agility, AI-powered automation, and data-rich insights that accelerate transformation across your organization.', 'tekgurus' ); ?></p>
                            <a href="<?php echo esc_url( home_url( '/solutions/' ) ); ?>" class="link-more"><?php esc_html_e( 'Discover our solutions', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1 space-y-3">
                            <a href="<?php echo esc_url( home_url( '/solutions/#hybrid' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Hybrid & Multi-Cloud', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/solutions/#automation' ) ); ?>" class="submenu-link"><?php esc_html_e( 'AI-Powered Automation', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/solutions/#analytics' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Data Analytics & Insights', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/solutions/#transformation' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Digital Transformation', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/solutions/#industry' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Industry Solutions', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1">
                            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero3.svg' ); ?>" alt="<?php esc_attr_e( 'TekGurus solutions', 'tekgurus' ); ?>" class="rounded-xl w-full h-full object-cover">
                        </div>
                    </div>
                </div>
                <div class="mega-menu" data-menu="insights">
                    <div class="grid grid-cols-4 gap-8">
                        <div class="col-span-2 space-y-4">
                            <h3 class="text-2xl font-semibold"><?php esc_html_e( 'Insights to keep you ahead', 'tekgurus' ); ?></h3>
                            <p class="text-sm leading-relaxed text-gray-200"><?php esc_html_e( 'Explore thought leadership, success stories, and practical guides from TekGurus strategists and engineers.', 'tekgurus' ); ?></p>
                            <a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>" class="link-more"><?php esc_html_e( 'See latest perspectives', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1 space-y-3">
                            <a href="<?php echo esc_url( home_url( '/insights/#blogs' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Blogs', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/insights/#news' ) ); ?>" class="submenu-link"><?php esc_html_e( 'News & Events', 'tekgurus' ); ?></a>
                            <a href="<?php echo esc_url( home_url( '/insights/#cases' ) ); ?>" class="submenu-link"><?php esc_html_e( 'Case Studies', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1">
                            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero4.svg' ); ?>" alt="<?php esc_attr_e( 'TekGurus insights', 'tekgurus' ); ?>" class="rounded-xl w-full h-full object-cover">
                        </div>
                    </div>
                </div>
                <div class="mega-menu" data-menu="contact">
                    <div class="grid grid-cols-4 gap-8">
                        <div class="col-span-2 space-y-4">
                            <h3 class="text-2xl font-semibold"><?php esc_html_e( 'Connect with TekGurus', 'tekgurus' ); ?></h3>
                            <p class="text-sm leading-relaxed text-gray-200"><?php esc_html_e( 'We partner with teams to architect meaningful change. Share your goals and let’s shape the next chapter together.', 'tekgurus' ); ?></p>
                            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="link-more"><?php esc_html_e( 'Start a conversation', 'tekgurus' ); ?></a>
                        </div>
                        <div class="col-span-1 space-y-3 text-sm">
                            <p class="submenu-link"><?php esc_html_e( '7 Ibiyinka Olorunbe, VI, Lagos', 'tekgurus' ); ?></p>
                            <p class="submenu-link"><a href="mailto:info@thetekgurus.com" class="hover:underline">info@thetekgurus.com</a></p>
                            <p class="submenu-link"><a href="tel:+2349049206989" class="hover:underline">+234 904 920 6989</a></p>
                        </div>
                        <div class="col-span-1">
                            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero1.svg' ); ?>" alt="<?php esc_attr_e( 'Contact TekGurus', 'tekgurus' ); ?>" class="rounded-xl w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div id="mobile-menu" class="fixed inset-0 bg-black/90 backdrop-blur hidden z-40">
    <div class="px-6 py-8 space-y-8">
        <div class="flex items-center justify-between">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center space-x-3">
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/logo/tekgurus-logo.svg' ); ?>" alt="<?php esc_attr_e( 'TekGurus logo', 'tekgurus' ); ?>" class="h-10 w-auto object-contain">
                <span class="text-lg font-semibold uppercase"><?php bloginfo( 'name' ); ?></span>
            </a>
            <button id="mobile-menu-close" aria-label="<?php esc_attr_e( 'Close navigation', 'tekgurus' ); ?>" class="text-light">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-6">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'mobile',
                    'menu_class'     => 'space-y-2 flex flex-col',
                    'container'      => false,
                    'fallback_cb'    => 'tekgurus_mobile_menu_fallback',
                )
            );
            ?>
        </div>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary w-full justify-center"><?php esc_html_e( 'Talk to Our Experts', 'tekgurus' ); ?></a>
    </div>
</div>
<main id="main" class="pt-20">
