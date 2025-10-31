<?php
/**
 * Footer template.
 *
 * @package TekGurus
 */
?>
</main>
<section class="py-24 bg-dark" id="deliver">
    <div class="max-w-7xl mx-auto px-6">
        <div class="section-heading">
            <p class="eyebrow"><?php esc_html_e( 'What We Deliver', 'tekgurus' ); ?></p>
            <h2 class="section-title"><?php esc_html_e( 'Cloud programs that accelerate progress', 'tekgurus' ); ?></h2>
            <p class="section-description"><?php esc_html_e( 'Our multidisciplinary team partners with you to design, deploy, and evolve secure platforms with measurable value at every stage.', 'tekgurus' ); ?></p>
        </div>
        <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-4">
            <div class="stat-card" data-animate>
                <h3 class="text-4xl font-semibold text-primary">90%</h3>
                <p class="mt-3 text-gray-300"><?php esc_html_e( 'Faster release cycles through automated CI/CD and DevOps practices.', 'tekgurus' ); ?></p>
            </div>
            <div class="stat-card" data-animate>
                <h3 class="text-4xl font-semibold text-primary">40%</h3>
                <p class="mt-3 text-gray-300"><?php esc_html_e( 'Average cost optimization achieved across managed cloud engagements.', 'tekgurus' ); ?></p>
            </div>
            <div class="stat-card" data-animate>
                <h3 class="text-4xl font-semibold text-primary">99.99%</h3>
                <p class="mt-3 text-gray-300"><?php esc_html_e( 'Resilience engineered with multi-region architectures and governance.', 'tekgurus' ); ?></p>
            </div>
            <div class="stat-card" data-animate>
                <h3 class="text-4xl font-semibold text-primary">24/7</h3>
                <p class="mt-3 text-gray-300"><?php esc_html_e( 'Partnership that extends beyond go-live with continuous improvement cycles.', 'tekgurus' ); ?></p>
            </div>
        </div>
    </div>
</section>
<section class="py-20 bg-[#0f0f0f]">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-wrap items-center justify-between gap-10 opacity-70" data-animate>
            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/aws-logo.svg' ); ?>" alt="<?php esc_attr_e( 'AWS Partner', 'tekgurus' ); ?>" class="h-12 object-contain">
            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/azure-logo.svg' ); ?>" alt="<?php esc_attr_e( 'Azure Partner', 'tekgurus' ); ?>" class="h-12 object-contain">
            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/gcp-logo.svg' ); ?>" alt="<?php esc_attr_e( 'Google Cloud Partner', 'tekgurus' ); ?>" class="h-12 object-contain">
            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/tekgurus-logo.svg' ); ?>" alt="<?php esc_attr_e( 'TekGurus Alliance', 'tekgurus' ); ?>" class="h-12 object-contain">
        </div>
    </div>
</section>
<footer class="bg-black border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-4 gap-10">
        <div class="space-y-4">
            <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/logo/tekgurus-logo.svg' ); ?>" alt="<?php esc_attr_e( 'TekGurus logo', 'tekgurus' ); ?>" class="h-14 w-auto object-contain">
            <p class="text-gray-400"><?php esc_html_e( 'TekGurus designs cloud experiences that empower people, streamline operations, and unleash innovation for Africa’s leading organizations.', 'tekgurus' ); ?></p>
        </div>
        <div>
            <h4 class="footer-title"><?php esc_html_e( 'Company', 'tekgurus' ); ?></h4>
            <ul class="footer-links">
                <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'tekgurus' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'tekgurus' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/solutions/' ) ); ?>"><?php esc_html_e( 'Solutions', 'tekgurus' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>"><?php esc_html_e( 'Insights', 'tekgurus' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'tekgurus' ); ?></a></li>
            </ul>
        </div>
        <div>
            <h4 class="footer-title"><?php esc_html_e( 'Contact', 'tekgurus' ); ?></h4>
            <ul class="footer-links">
                <li><?php esc_html_e( '7 Ibiyinka Olorunbe, VI, Lagos, Nigeria', 'tekgurus' ); ?></li>
                <li><a href="mailto:info@thetekgurus.com">info@thetekgurus.com</a></li>
                <li><a href="tel:+2349049206989">+234 904 920 6989</a></li>
            </ul>
        </div>
        <div>
            <h4 class="footer-title"><?php esc_html_e( 'Follow', 'tekgurus' ); ?></h4>
            <div class="flex space-x-4 text-gray-400">
                <a href="#" aria-label="LinkedIn" class="social-link">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4.98 3.5a2.5 2.5 0 11-.01 5.001 2.5 2.5 0 01-.01-5.001zM3 9h4v12H3zM9 9h3.8v1.71h.05c.53-1.82 2.05-3.75 3.75-3.75 4.01 0 4.75 2.64 4.75 6.07V21H17v-5.4c0-1.29-.03-2.95-1.8-2.95-1.8 0-2.07 1.4-2.07 2.85V21H9z" />
                    </svg>
                </a>
                <a href="#" aria-label="Twitter" class="social-link">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.22 4.22 0 001.84-2.33 8.4 8.4 0 01-2.67 1.03 4.2 4.2 0 00-7.15 3.83A11.92 11.92 0 013 5.16a4.2 4.2 0 001.3 5.6 4.15 4.15 0 01-1.9-.52v.05a4.2 4.2 0 003.37 4.12 4.3 4.3 0 01-1.1.15c-.27 0-.54-.03-.8-.07a4.21 4.21 0 003.92 2.91A8.45 8.45 0 012 19.54a11.89 11.89 0 006.44 1.89c7.73 0 11.96-6.41 11.96-11.97 0-.18 0-.36-.01-.54A8.54 8.54 0 0024 5.5a8.4 8.4 0 01-2.54.7z" />
                    </svg>
                </a>
                <a href="#" aria-label="Instagram" class="social-link">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm10 2c1.66 0 3 1.34 3 3v10c0 1.66-1.34 3-3 3H7c-1.66 0-3-1.34-3-3V7c0-1.66 1.34-3 3-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2c1.66 0 3 1.34 3 3s-1.34 3-3 3a3 3 0 110-6zm4.5-.75a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z" />
                    </svg>
                </a>
                <a href="#" aria-label="YouTube" class="social-link">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M21.8 8s-.2-1.4-.8-2c-.8-.8-1.7-.8-2.1-.9C15.7 4.7 12 4.7 12 4.7h0s-3.7 0-6.9.4c-.4.1-1.3.1-2.1.9-.6.6-.8 2-.8 2S2 9.6 2 11.2v1.6C2 14.4 2.2 16 2.2 16s.2 1.4.8 2c.8.8 1.8.7 2.3.8 1.7.2 7 .4 7 .4s3.7 0 6.9-.4c.4-.1 1.3-.1 2.1-.9.6-.6.8-2 .8-2s.2-1.6.2-3.2v-1.6c0-1.6-.2-3.2-.2-3.2zM10 14.7V9.3l5 2.7-5 2.7z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="border-t border-white/5 py-6 text-center text-gray-500 text-sm">© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'tekgurus' ); ?></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
