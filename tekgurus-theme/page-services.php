<?php
/**
 * Template for Services page.
 *
 * @package TekGurus
 */
get_header();
?>
<section class="relative pt-40 pb-24 bg-cover bg-center" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero3.svg' ); ?>');">
    <div class="absolute inset-0 bg-black/75"></div>
    <div class="relative max-w-5xl mx-auto px-6 text-center space-y-6">
        <p class="eyebrow">Our Services</p>
        <h1 class="text-4xl md:text-5xl font-semibold">Cloud services designed to launch, scale, and sustain innovation</h1>
        <p class="text-lg text-gray-200">TekGurus provides end-to-end expertise—from strategy and migration to automation and enablement—so your teams can innovate with clarity and confidence.</p>
    </div>
</section>
<section class="py-24 bg-dark">
    <div class="max-w-6xl mx-auto px-6 grid gap-10 md:grid-cols-2 xl:grid-cols-3">
        <article class="service-card" id="strategy" data-animate>
            <div class="service-icon">01</div>
            <h3 class="service-title">Cloud Strategy &amp; Advisory</h3>
            <p class="service-text">Shape a visionary yet pragmatic roadmap that aligns cloud investments with business outcomes. We assess readiness, design governance, and prioritize initiatives that deliver measurable value.</p>
            <a href="#strategy" class="service-link">Learn More</a>
        </article>
        <article class="service-card" id="implementation" data-animate>
            <div class="service-icon">02</div>
            <h3 class="service-title">Cloud Implementation &amp; Migration</h3>
            <p class="service-text">Execute migrations with minimal disruption. TekGurus architects resilient landing zones, modernizes applications, and orchestrates data movement with security at the center.</p>
            <a href="#implementation" class="service-link">Learn More</a>
        </article>
        <article class="service-card" id="security" data-animate>
            <div class="service-icon">03</div>
            <h3 class="service-title">Cloud Security &amp; Governance</h3>
            <p class="service-text">Implement zero-trust architectures, compliance controls, and identity frameworks that keep your ecosystems protected without slowing innovation.</p>
            <a href="#security" class="service-link">Learn More</a>
        </article>
        <article class="service-card" id="optimization" data-animate>
            <div class="service-icon">04</div>
            <h3 class="service-title">Cloud Optimization &amp; Automation</h3>
            <p class="service-text">Leverage AI-driven insights, FinOps practices, and infrastructure-as-code to reduce costs, enhance performance, and accelerate delivery cycles.</p>
            <a href="#optimization" class="service-link">Learn More</a>
        </article>
        <article class="service-card" id="managed" data-animate>
            <div class="service-icon">05</div>
            <h3 class="service-title">Managed Cloud Services</h3>
            <p class="service-text">Gain peace of mind with proactive monitoring, incident response, and lifecycle management tailored to your SLAs and business rhythms.</p>
            <a href="#managed" class="service-link">Learn More</a>
        </article>
        <article class="service-card" id="training" data-animate>
            <div class="service-icon">06</div>
            <h3 class="service-title">Cloud Training &amp; Enablement</h3>
            <p class="service-text">Upskill your teams with immersive workshops, certification pathways, and embedded coaching that build lasting cloud fluency.</p>
            <a href="#training" class="service-link">Learn More</a>
        </article>
    </div>
</section>
<section class="py-24 bg-[#0a0a0a]">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6" data-animate>
            <p class="eyebrow">How We Deliver</p>
            <h2 class="section-title">Integrated teams that move from blueprint to impact</h2>
            <p class="text-gray-300 leading-relaxed">TekGurus squads blend strategy consultants, solution architects, security experts, and change managers. We work side-by-side with your teams to execute with precision and transfer knowledge throughout the engagement.</p>
            <div class="space-y-4">
                <div class="approach-step">
                    <span class="step-index">1</span>
                    <div>
                        <h3 class="text-lg font-semibold">Collaborative Planning</h3>
                        <p class="text-gray-300 mt-1">Vision workshops translate ambition into phased roadmaps with prioritized milestones.</p>
                    </div>
                </div>
                <div class="approach-step">
                    <span class="step-index">2</span>
                    <div>
                        <h3 class="text-lg font-semibold">Engineering Excellence</h3>
                        <p class="text-gray-300 mt-1">Automation-first builds, reusable accelerators, and DevSecOps pipelines keep delivery fast and secure.</p>
                    </div>
                </div>
                <div class="approach-step">
                    <span class="step-index">3</span>
                    <div>
                        <h3 class="text-lg font-semibold">Continuous Enablement</h3>
                        <p class="text-gray-300 mt-1">Enablement playbooks, pairing sessions, and managed services ensure value long after go-live.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-[#111111] border border-white/10 rounded-3xl p-10 space-y-6" data-animate>
            <h3 class="text-2xl font-semibold">Outcomes you can expect</h3>
            <ul class="space-y-4 text-gray-300">
                <li class="flex items-start space-x-3"><span class="text-primary mt-1">▹</span><span>Accelerated modernization of critical workloads through proven patterns.</span></li>
                <li class="flex items-start space-x-3"><span class="text-primary mt-1">▹</span><span>Improved operational resilience with observability and automated remediation.</span></li>
                <li class="flex items-start space-x-3"><span class="text-primary mt-1">▹</span><span>Empowered teams ready to manage, optimize, and evolve your cloud estate.</span></li>
            </ul>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-secondary inline-flex">Schedule a strategy session</a>
        </div>
    </div>
</section>
<section class="py-24 bg-gradient-to-br from-primary/80 to-black">
    <div class="max-w-5xl mx-auto px-6 text-center space-y-6" data-animate>
        <h2 class="text-3xl md:text-4xl font-semibold">Ready to ignite your next cloud initiative?</h2>
        <p class="text-lg text-gray-100">TekGurus partners with enterprises, scale-ups, and public sector innovators to design cloud programs that fuel progress. Let’s craft a roadmap built around your goals.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">Talk to TekGurus</a>
            <a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>" class="btn-secondary">Explore our insights</a>
        </div>
    </div>
</section>
<?php
get_footer();
