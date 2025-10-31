<?php
/**
 * Front page template.
 *
 * @package TekGurus
 */
get_header();
?>
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <?php
        $has_custom_content = ( function_exists( 'has_blocks' ) && has_blocks( get_the_ID() ) ) || strlen( trim( wp_strip_all_tags( get_the_content() ) ) ) > 0;
        ?>
        <?php if ( $has_custom_content ) : ?>
            <div class="container mx-auto px-6 py-12 content-area">
                <?php the_content(); ?>
            </div>
        <?php else : ?>
            <section class="hero-slider relative h-screen">
                <div class="absolute inset-0">
                    <div class="hero-slide" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero1.svg' ); ?>');"></div>
                    <div class="hero-slide" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero2.svg' ); ?>');"></div>
                    <div class="hero-slide" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero3.svg' ); ?>');"></div>
                    <div class="hero-slide" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero4.svg' ); ?>');"></div>
                </div>
                <div class="relative z-10 h-full flex items-center bg-black/50">
                    <div class="max-w-7xl mx-auto px-6">
                        <div class="max-w-2xl space-y-6 animate-fade-in">
                            <p class="uppercase tracking-[0.5em] text-sm text-gray-300"><?php esc_html_e( 'Cloud. People. Possibility.', 'tekgurus' ); ?></p>
                            <h1 class="text-4xl md:text-6xl font-semibold leading-tight"><?php esc_html_e( 'Empowering Businesses with Intelligent Cloud Solutions', 'tekgurus' ); ?></h1>
                            <p class="text-lg text-gray-200"><?php esc_html_e( 'TekGurus aligns strategy, engineering, and enablement to unlock resilient cloud experiences that scale with ambition.', 'tekgurus' ); ?></p>
                            <div class="flex flex-wrap items-center gap-4">
                                <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Explore Our Services', 'tekgurus' ); ?></a>
                                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-secondary"><?php esc_html_e( 'Schedule a Consultation', 'tekgurus' ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex items-center space-x-3">
                    <button class="slider-control" data-direction="prev" aria-label="<?php esc_attr_e( 'Previous slide', 'tekgurus' ); ?>">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <div class="slider-dots flex space-x-2"></div>
                    <button class="slider-control" data-direction="next" aria-label="<?php esc_attr_e( 'Next slide', 'tekgurus' ); ?>">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </section>
            <section class="py-24 bg-dark" id="expertise">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="section-heading">
                        <p class="eyebrow"><?php esc_html_e( 'Our Expertise', 'tekgurus' ); ?></p>
                        <h2 class="section-title"><?php esc_html_e( 'Cloud-native leadership with a human heartbeat', 'tekgurus' ); ?></h2>
                        <p class="section-description"><?php esc_html_e( 'From advisory through operations, TekGurus co-creates modern cloud foundations that are secure, adaptive, and future-ready.', 'tekgurus' ); ?></p>
                    </div>
                    <div class="grid gap-10 md:grid-cols-3">
                        <div class="card" data-animate>
                            <h3 class="card-title"><?php esc_html_e( 'Strategic Advisory', 'tekgurus' ); ?></h3>
                            <p class="card-text"><?php esc_html_e( 'Align every initiative with a clear cloud vision. We translate ambition into actionable roadmaps anchored in governance, adoption, and culture.', 'tekgurus' ); ?></p>
                        </div>
                        <div class="card" data-animate>
                            <h3 class="card-title"><?php esc_html_e( 'Engineering Excellence', 'tekgurus' ); ?></h3>
                            <p class="card-text"><?php esc_html_e( 'Deploy scalable architectures across hyperscalers. Our engineers build with automation, resilience, and performance at the core.', 'tekgurus' ); ?></p>
                        </div>
                        <div class="card" data-animate>
                            <h3 class="card-title"><?php esc_html_e( 'Enablement & Support', 'tekgurus' ); ?></h3>
                            <p class="card-text"><?php esc_html_e( 'Empower your teams with playbooks, training, and co-managed operations designed to elevate collaboration and maintain momentum.', 'tekgurus' ); ?></p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-24 bg-[#0a0a0a]">
                <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6" data-animate>
                        <p class="eyebrow"><?php esc_html_e( 'Why Choose TekGurus', 'tekgurus' ); ?></p>
                        <h2 class="section-title"><?php esc_html_e( 'We orchestrate technology and people for measurable impact', 'tekgurus' ); ?></h2>
                        <p class="text-gray-300 leading-relaxed"><?php esc_html_e( 'Our consultants embed within your teams to co-create operating models that drive sustainable transformation. TekGurus combines modern tooling with proven governance to help enterprises stay secure, agile, and connected.', 'tekgurus' ); ?></p>
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <h4 class="font-semibold text-light"><?php esc_html_e( 'Certified Cloud Architects', 'tekgurus' ); ?></h4>
                                <p class="text-gray-400 mt-2"><?php esc_html_e( 'Experts across AWS, Azure, and Google Cloud with deep sector insight.', 'tekgurus' ); ?></p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-light"><?php esc_html_e( 'Customer-Obsessed Delivery', 'tekgurus' ); ?></h4>
                                <p class="text-gray-400 mt-2"><?php esc_html_e( 'Engagement models tailored to your pace, culture, and growth trajectory.', 'tekgurus' ); ?></p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-light"><?php esc_html_e( 'Security at the Core', 'tekgurus' ); ?></h4>
                                <p class="text-gray-400 mt-2"><?php esc_html_e( 'Zero-trust frameworks and governance blueprints built into every project.', 'tekgurus' ); ?></p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-light"><?php esc_html_e( 'Innovation Accelerated', 'tekgurus' ); ?></h4>
                                <p class="text-gray-400 mt-2"><?php esc_html_e( 'Automation, AI, and DevOps practices that unlock new digital value.', 'tekgurus' ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-6" data-animate>
                        <div class="feature-panel">
                            <h3 class="text-xl font-semibold text-light"><?php esc_html_e( 'Experience-led transformation', 'tekgurus' ); ?></h3>
                            <p class="text-gray-300 mt-3"><?php esc_html_e( 'We guide stakeholders through immersive workshops, co-innovation labs, and product roadmaps that turn strategy into delivery.', 'tekgurus' ); ?></p>
                        </div>
                        <div class="feature-panel">
                            <h3 class="text-xl font-semibold text-light"><?php esc_html_e( 'Proactive managed services', 'tekgurus' ); ?></h3>
                            <p class="text-gray-300 mt-3"><?php esc_html_e( '24/7 observability and incident response, tuned to your SLAs and optimized for continuous improvement.', 'tekgurus' ); ?></p>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
<?php
get_footer();
