<?php
/**
 * Template for Insights page.
 *
 * @package TekGurus
 */
get_header();
?>
<section class="relative pt-40 pb-24 bg-cover bg-center" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero1.svg' ); ?>');">
    <div class="absolute inset-0 bg-black/70"></div>
    <div class="relative max-w-5xl mx-auto px-6 text-center space-y-6">
        <p class="eyebrow">TekGurus Insights</p>
        <h1 class="text-4xl md:text-5xl font-semibold">Ideas, stories, and playbooks for cloud-forward teams</h1>
        <p class="text-lg text-gray-200">Stay ahead with perspectives from TekGurus strategists and engineers on modern architectures, change leadership, and the future of digital experiences.</p>
    </div>
</section>
<section class="py-24 bg-dark" id="blogs">
    <div class="max-w-6xl mx-auto px-6">
        <div class="section-heading">
            <p class="eyebrow">Latest Blogs</p>
            <h2 class="section-title">Practical guidance for your next move</h2>
            <p class="section-description">Explore step-by-step recommendations, engineering playbooks, and leadership perspectives shaped by real-world engagements.</p>
        </div>
        <div class="grid gap-10 md:grid-cols-3">
            <article class="insight-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/insight1.svg' ); ?>" alt="Blog" class="insight-image">
                <div class="p-6 space-y-3">
                    <p class="text-xs uppercase tracking-widest text-primary">Cloud Strategy</p>
                    <h3 class="text-2xl font-semibold">Designing a resilient multi-cloud foundation</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Learn how TekGurus maps governance and automation to align multi-cloud adoption with business priorities.</p>
                    <a href="#" class="link-more">Read Story</a>
                </div>
            </article>
            <article class="insight-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/insight2.svg' ); ?>" alt="Blog" class="insight-image">
                <div class="p-6 space-y-3">
                    <p class="text-xs uppercase tracking-widest text-primary">Automation</p>
                    <h3 class="text-2xl font-semibold">Elevating DevOps with AI copilots</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Discover how intelligent tooling accelerates releases, improves quality, and enhances developer experience.</p>
                    <a href="#" class="link-more">Read Story</a>
                </div>
            </article>
            <article class="insight-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/insight3.svg' ); ?>" alt="Blog" class="insight-image">
                <div class="p-6 space-y-3">
                    <p class="text-xs uppercase tracking-widest text-primary">People &amp; Change</p>
                    <h3 class="text-2xl font-semibold">Building a culture of cloud fluency</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Strategies for equipping teams with the skills, rituals, and mindset needed to sustain cloud transformation.</p>
                    <a href="#" class="link-more">Read Story</a>
                </div>
            </article>
        </div>
    </div>
</section>
<section class="py-24 bg-[#0a0a0a]" id="news">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6" data-animate>
            <p class="eyebrow">News &amp; Events</p>
            <h2 class="section-title">Highlights from the TekGurus community</h2>
            <p class="text-gray-300">Follow our latest partnerships, product releases, and community engagements as we collaborate with innovators across the continent.</p>
            <ul class="space-y-4 text-gray-300">
                <li class="flex items-start space-x-3"><span class="text-primary mt-1">▹</span><span>TekGurus announces strategic partnership with regional fintech consortium.</span></li>
                <li class="flex items-start space-x-3"><span class="text-primary mt-1">▹</span><span>Upcoming webinar: Accelerating public sector digitization with secure cloud.</span></li>
                <li class="flex items-start space-x-3"><span class="text-primary mt-1">▹</span><span>Innovation lab recap: Co-creating data platforms for smart campuses.</span></li>
            </ul>
        </div>
        <div class="grid gap-8" data-animate>
            <article class="news-card">
                <h3 class="text-xl font-semibold">Webinar • April 2025</h3>
                <p class="text-gray-300 mt-2">Join TekGurus experts as we unpack hybrid operations models for regulated industries.</p>
                <a href="#" class="link-more">Save your seat</a>
            </article>
            <article class="news-card">
                <h3 class="text-xl font-semibold">Event • May 2025</h3>
                <p class="text-gray-300 mt-2">Meet us at the Lagos Digital Transformation Summit to explore real-world case studies.</p>
                <a href="#" class="link-more">Book a meeting</a>
            </article>
        </div>
    </div>
</section>
<section class="py-24 bg-dark" id="cases">
    <div class="max-w-6xl mx-auto px-6">
        <div class="section-heading">
            <p class="eyebrow">Case Studies</p>
            <h2 class="section-title">Proof of what’s possible with TekGurus</h2>
            <p class="section-description">Our engagements show how collaborative cloud strategies lead to measurable outcomes. Explore stories from finance, education, startups, and the public sector.</p>
        </div>
        <div class="grid gap-10 md:grid-cols-3">
            <article class="insight-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/insight4.svg' ); ?>" alt="Case Study" class="insight-image">
                <div class="p-6 space-y-3">
                    <p class="text-xs uppercase tracking-widest text-primary">Financial Services</p>
                    <h3 class="text-2xl font-semibold">Securing digital payments infrastructure</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">How a fintech scaled securely across regions with TekGurus’ zero-trust architecture and DevSecOps support.</p>
                    <a href="#" class="link-more">View Case</a>
                </div>
            </article>
            <article class="insight-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/insight5.svg' ); ?>" alt="Case Study" class="insight-image">
                <div class="p-6 space-y-3">
                    <p class="text-xs uppercase tracking-widest text-primary">Education</p>
                    <h3 class="text-2xl font-semibold">Modernizing learning experiences</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">A leading university partnered with TekGurus to launch a resilient digital campus powered by cloud-native services.</p>
                    <a href="#" class="link-more">View Case</a>
                </div>
            </article>
            <article class="insight-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/insight6.svg' ); ?>" alt="Case Study" class="insight-image">
                <div class="p-6 space-y-3">
                    <p class="text-xs uppercase tracking-widest text-primary">Public Sector</p>
                    <h3 class="text-2xl font-semibold">Transforming citizen services</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Discover how we helped a government agency digitize services with secure workflows and data-driven insights.</p>
                    <a href="#" class="link-more">View Case</a>
                </div>
            </article>
        </div>
    </div>
</section>
<section class="py-24 bg-gradient-to-br from-primary/80 to-black">
    <div class="max-w-4xl mx-auto px-6 text-center space-y-6" data-animate>
        <h2 class="text-3xl md:text-4xl font-semibold">Stay inspired with TekGurus insights</h2>
        <p class="text-lg text-gray-100">Subscribe to our newsletter for curated articles, event invites, and exclusive tools to power your cloud initiatives.</p>
        <form class="flex flex-col md:flex-row items-center justify-center gap-4 max-w-3xl mx-auto">
            <input type="email" placeholder="Your email address" class="w-full md:flex-1 px-5 py-3 rounded-full bg-black/70 border border-white/20 text-white focus:outline-none focus:border-primary" required>
            <button type="submit" class="btn-primary">Subscribe</button>
        </form>
    </div>
</section>
<?php
get_footer();
