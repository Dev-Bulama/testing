<?php
/**
 * Template for About page.
 *
 * @package TekGurus
 */
get_header();
?>
<section class="relative pt-40 pb-24 bg-cover bg-center" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero2.svg' ); ?>');">
    <div class="absolute inset-0 bg-black/70"></div>
    <div class="relative max-w-5xl mx-auto px-6 text-center space-y-6">
        <p class="eyebrow"><?php esc_html_e( 'About TekGurus', 'tekgurus' ); ?></p>
        <h1 class="text-4xl md:text-5xl font-semibold"><?php esc_html_e( 'Human-led cloud transformation for Africa’s trailblazers', 'tekgurus' ); ?></h1>
        <p class="text-lg text-gray-200"><?php esc_html_e( 'TekGurus is a collective of strategists, architects, and change makers dedicated to building intelligent cloud ecosystems that elevate how businesses innovate, scale, and connect.', 'tekgurus' ); ?></p>
    </div>
</section>
<section class="py-24 bg-dark" id="overview">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6" data-animate>
            <p class="eyebrow"><?php esc_html_e( 'Company Overview', 'tekgurus' ); ?></p>
            <h2 class="section-title"><?php esc_html_e( 'We translate complex cloud ambitions into confident momentum', 'tekgurus' ); ?></h2>
            <p class="text-gray-300 leading-relaxed"><?php esc_html_e( 'Founded in Lagos, TekGurus partners with organizations across Africa to reimagine cloud experiences. We combine deep industry insight with modern engineering to deliver platforms that are secure, scalable, and centered on people. From strategic advisory to day-to-day operations, we guide teams toward lasting outcomes.', 'tekgurus' ); ?></p>
            <p class="text-gray-300 leading-relaxed"><?php esc_html_e( 'Our cross-functional squads activate co-creation with clients, embedding best practices while enabling your teams to become future-ready. Each engagement is tailored to your context, ensuring every milestone advances performance, trust, and innovation.', 'tekgurus' ); ?></p>
        </div>
        <div class="bg-[#0f0f0f] border border-white/10 rounded-3xl p-10 space-y-6" data-animate>
            <div>
                <h3 class="text-2xl font-semibold"><?php esc_html_e( 'Global perspective, African roots', 'tekgurus' ); ?></h3>
                <p class="text-gray-400 mt-3"><?php esc_html_e( 'We draw from worldwide cloud expertise while honoring the unique opportunities of African markets. Our work bridges regulatory realities, customer behaviors, and emerging ecosystems.', 'tekgurus' ); ?></p>
            </div>
            <div>
                <h3 class="text-2xl font-semibold"><?php esc_html_e( 'Partnership at every stage', 'tekgurus' ); ?></h3>
                <p class="text-gray-400 mt-3"><?php esc_html_e( 'TekGurus integrates with your teams, aligning leadership, engineering, and operations around shared metrics that accelerate decision making.', 'tekgurus' ); ?></p>
            </div>
        </div>
    </div>
</section>
<section class="py-24 bg-[#0a0a0a]" id="mission">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-10">
        <div class="space-y-4" data-animate>
            <p class="eyebrow"><?php esc_html_e( 'Mission', 'tekgurus' ); ?></p>
            <h2 class="text-3xl font-semibold"><?php esc_html_e( 'Empower people and platforms to co-create extraordinary value', 'tekgurus' ); ?></h2>
            <p class="text-gray-300"><?php esc_html_e( 'We exist to help businesses unlock cloud potential with confidence. Through collaborative design, resilient engineering, and guided enablement, we make the cloud work for people—securely and sustainably.', 'tekgurus' ); ?></p>
        </div>
        <div class="space-y-4" data-animate>
            <p class="eyebrow"><?php esc_html_e( 'Vision', 'tekgurus' ); ?></p>
            <h2 class="text-3xl font-semibold"><?php esc_html_e( 'Inspire Africa’s most trusted cloud-powered enterprises', 'tekgurus' ); ?></h2>
            <p class="text-gray-300"><?php esc_html_e( 'Our vision is a connected digital continent where organizations move fast, stay protected, and build experiences that matter. TekGurus leads with empathy, intelligence, and accountability to get you there.', 'tekgurus' ); ?></p>
        </div>
    </div>
</section>
<section class="py-24 bg-dark" id="values">
    <div class="max-w-6xl mx-auto px-6">
        <div class="section-heading">
            <p class="eyebrow"><?php esc_html_e( 'Core Values', 'tekgurus' ); ?></p>
            <h2 class="section-title"><?php esc_html_e( 'The principles that define TekGurus', 'tekgurus' ); ?></h2>
            <p class="section-description"><?php esc_html_e( 'Our values guide every engagement, ensuring we bring clarity, curiosity, and care to the experiences we co-create.', 'tekgurus' ); ?></p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="card" data-animate>
                <h3 class="card-title"><?php esc_html_e( 'Innovation', 'tekgurus' ); ?></h3>
                <p class="card-text"><?php esc_html_e( 'We stay on the frontier of cloud technology, continuously experimenting and translating emerging capabilities into dependable outcomes for clients.', 'tekgurus' ); ?></p>
            </div>
            <div class="card" data-animate>
                <h3 class="card-title"><?php esc_html_e( 'Connection', 'tekgurus' ); ?></h3>
                <p class="card-text"><?php esc_html_e( 'Relationships power our delivery. We invest in understanding people, cultures, and goals to design solutions that truly resonate.', 'tekgurus' ); ?></p>
            </div>
            <div class="card" data-animate>
                <h3 class="card-title"><?php esc_html_e( 'Inspiration', 'tekgurus' ); ?></h3>
                <p class="card-text"><?php esc_html_e( 'We encourage bold ideas and empower teams to lead transformation. Every project is an opportunity to inspire new possibilities.', 'tekgurus' ); ?></p>
            </div>
        </div>
    </div>
</section>
<section class="py-24 bg-[#0a0a0a]" id="leadership">
    <div class="max-w-6xl mx-auto px-6">
        <div class="section-heading">
            <p class="eyebrow"><?php esc_html_e( 'Leadership', 'tekgurus' ); ?></p>
            <h2 class="section-title"><?php esc_html_e( 'Meet the leaders guiding TekGurus forward', 'tekgurus' ); ?></h2>
            <p class="section-description"><?php esc_html_e( 'Our leadership blends enterprise experience with startup agility, supporting clients with thoughtful vision and hands-on expertise.', 'tekgurus' ); ?></p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="lead-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/leader1.svg' ); ?>" alt="<?php esc_attr_e( 'Chief Executive Officer', 'tekgurus' ); ?>" class="lead-photo">
                <div class="p-6 space-y-2">
                    <h3 class="text-xl font-semibold"><?php esc_html_e( 'Adaobi Martins', 'tekgurus' ); ?></h3>
                    <p class="text-primary text-sm uppercase tracking-widest"><?php esc_html_e( 'Chief Executive Officer', 'tekgurus' ); ?></p>
                    <p class="text-gray-300 text-sm leading-relaxed"><?php esc_html_e( 'Adaobi champions TekGurus’ mission to humanize technology adoption, guiding enterprise leaders through cloud transformation journeys.', 'tekgurus' ); ?></p>
                </div>
            </div>
            <div class="lead-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/leader2.svg' ); ?>" alt="<?php esc_attr_e( 'Chief Technology Officer', 'tekgurus' ); ?>" class="lead-photo">
                <div class="p-6 space-y-2">
                    <h3 class="text-xl font-semibold"><?php esc_html_e( 'Kunle Adebayo', 'tekgurus' ); ?></h3>
                    <p class="text-primary text-sm uppercase tracking-widest"><?php esc_html_e( 'Chief Technology Officer', 'tekgurus' ); ?></p>
                    <p class="text-gray-300 text-sm leading-relaxed"><?php esc_html_e( 'Kunle architects secure, scalable platforms across multi-cloud environments, driving technical excellence and automation strategies.', 'tekgurus' ); ?></p>
                </div>
            </div>
            <div class="lead-card" data-animate>
                <img src="<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/leader3.svg' ); ?>" alt="<?php esc_attr_e( 'Chief Experience Officer', 'tekgurus' ); ?>" class="lead-photo">
                <div class="p-6 space-y-2">
                    <h3 class="text-xl font-semibold"><?php esc_html_e( 'Tosin Ayoola', 'tekgurus' ); ?></h3>
                    <p class="text-primary text-sm uppercase tracking-widest"><?php esc_html_e( 'Chief Experience Officer', 'tekgurus' ); ?></p>
                    <p class="text-gray-300 text-sm leading-relaxed"><?php esc_html_e( 'Tosin ensures every TekGurus engagement delivers value to people first, orchestrating change management and enablement programs.', 'tekgurus' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-24 bg-dark" id="approach">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-start">
        <div class="space-y-6" data-animate>
            <p class="eyebrow"><?php esc_html_e( 'Our Approach', 'tekgurus' ); ?></p>
            <h2 class="section-title"><?php esc_html_e( 'Co-creating resilient, insight-driven cloud ecosystems', 'tekgurus' ); ?></h2>
            <p class="text-gray-300 leading-relaxed"><?php esc_html_e( 'TekGurus begins every engagement with discovery workshops that clarify the needs of your customers, teams, and stakeholders. We translate insights into blueprints that prioritize security, compliance, and accelerated delivery.', 'tekgurus' ); ?></p>
            <p class="text-gray-300 leading-relaxed"><?php esc_html_e( 'Our iterative approach means we launch quickly, learn fast, and continually optimize. Continuous enablement keeps your teams empowered long after launch.', 'tekgurus' ); ?></p>
        </div>
        <div class="space-y-6" data-animate>
            <div class="approach-step">
                <span class="step-index">01</span>
                <div>
                    <h3 class="text-xl font-semibold"><?php esc_html_e( 'Discover & Align', 'tekgurus' ); ?></h3>
                    <p class="text-gray-300 mt-2"><?php esc_html_e( 'We engage stakeholders to map business priorities, technical readiness, and success metrics.', 'tekgurus' ); ?></p>
                </div>
            </div>
            <div class="approach-step">
                <span class="step-index">02</span>
                <div>
                    <h3 class="text-xl font-semibold"><?php esc_html_e( 'Design & Engineer', 'tekgurus' ); ?></h3>
                    <p class="text-gray-300 mt-2"><?php esc_html_e( 'Our teams prototype and build modern architectures with automation, observability, and governance baked in.', 'tekgurus' ); ?></p>
                </div>
            </div>
            <div class="approach-step">
                <span class="step-index">03</span>
                <div>
                    <h3 class="text-xl font-semibold"><?php esc_html_e( 'Enable & Evolve', 'tekgurus' ); ?></h3>
                    <p class="text-gray-300 mt-2"><?php esc_html_e( 'We upskill teams, transition operations seamlessly, and iterate through continuous improvement cycles.', 'tekgurus' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
