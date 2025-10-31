<?php
/**
 * Template for Solutions page.
 *
 * @package TekGurus
 */
get_header();
?>
<section class="relative pt-40 pb-24 bg-cover bg-center" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero4.svg' ); ?>');">
    <div class="absolute inset-0 bg-black/75"></div>
    <div class="relative max-w-5xl mx-auto px-6 text-center space-y-6">
        <p class="eyebrow">Our Solutions</p>
        <h1 class="text-4xl md:text-5xl font-semibold">Architecting intelligent solutions for a connected future</h1>
        <p class="text-lg text-gray-200">TekGurus designs cloud solutions that balance performance, security, and agility. We align technology stacks with industry realities, unlocking transformative experiences across sectors.</p>
    </div>
</section>
<section class="py-24 bg-dark">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-start">
        <div class="space-y-6" data-animate>
            <p class="eyebrow">Solution Domains</p>
            <h2 class="section-title">Bringing clarity to complex challenges</h2>
            <p class="text-gray-300 leading-relaxed">Every solution is engineered with resilience, observability, and scale in mind. TekGurus leverages hyperscaler services, automation frameworks, and AI-driven insight to transform how organizations operate and serve customers.</p>
            <div class="grid gap-6">
                <div class="solution-highlight">
                    <h3 class="text-xl font-semibold">Human-centered innovation</h3>
                    <p class="text-gray-300">We co-design with stakeholders to ensure adoption, accessibility, and meaningful change.</p>
                </div>
                <div class="solution-highlight">
                    <h3 class="text-xl font-semibold">Security and compliance assured</h3>
                    <p class="text-gray-300">Industry-specific guardrails and governance frameworks protect your data and reputation.</p>
                </div>
            </div>
        </div>
        <div class="space-y-8" data-animate>
            <article class="solution-card" id="hybrid">
                <h3 class="text-2xl font-semibold">Hybrid &amp; Multi-Cloud Solutions</h3>
                <p class="text-gray-300 mt-3">Design unified platforms across on-premises and cloud environments. TekGurus enables seamless workload portability, centralized management, and resilient connectivity across clouds.</p>
                <ul class="solution-list">
                    <li>Landing zones and cloud centers of excellence</li>
                    <li>Interoperability and network architecture design</li>
                    <li>Compliance-driven governance models</li>
                </ul>
            </article>
            <article class="solution-card" id="automation">
                <h3 class="text-2xl font-semibold">AI-Powered Automation</h3>
                <p class="text-gray-300 mt-3">Amplify productivity with intelligent automation. We deploy AI/ML workflows, event-driven architectures, and smart assistants to streamline operations and decision making.</p>
                <ul class="solution-list">
                    <li>Automation strategy and ROI mapping</li>
                    <li>ML Ops and data pipeline modernization</li>
                    <li>AI-infused support experiences</li>
                </ul>
            </article>
            <article class="solution-card" id="analytics">
                <h3 class="text-2xl font-semibold">Data Analytics &amp; Insights</h3>
                <p class="text-gray-300 mt-3">Unlock reliable data intelligence with modern platforms. TekGurus builds unified data estates, real-time dashboards, and predictive models that empower confident decisions.</p>
                <ul class="solution-list">
                    <li>Data lakes, warehouses, and lakehouse patterns</li>
                    <li>Self-service analytics and BI enablement</li>
                    <li>Advanced analytics and visualization</li>
                </ul>
            </article>
        </div>
    </div>
</section>
<section class="py-24 bg-[#0a0a0a]">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-8" data-animate>
            <article class="solution-card" id="transformation">
                <h3 class="text-2xl font-semibold">Digital Transformation Support</h3>
                <p class="text-gray-300 mt-3">Guide cross-functional change with a structured approach that brings technology, people, and processes into alignment. We develop modernization programs that elevate customer experience and operational efficiency.</p>
                <ul class="solution-list">
                    <li>Transformation governance and PMO support</li>
                    <li>Design thinking and product strategy sprints</li>
                    <li>Change management and enablement</li>
                </ul>
            </article>
            <article class="solution-card" id="industry">
                <h3 class="text-2xl font-semibold">Industry Solutions</h3>
                <p class="text-gray-300 mt-3">We tailor frameworks for financial services, education, startups, and the public sector—anchored in compliance, security, and agility.</p>
                <ul class="solution-list">
                    <li>Regulatory-aligned cloud platforms for finance</li>
                    <li>Digital learning ecosystems for education</li>
                    <li>Launchpads for startups and innovation hubs</li>
                    <li>Civic services modernization for government</li>
                </ul>
            </article>
        </div>
        <div class="bg-[#111111] border border-white/10 rounded-3xl p-10 space-y-6" data-animate>
            <h3 class="text-2xl font-semibold">Why TekGurus solutions scale</h3>
            <p class="text-gray-300">Our modular blueprints, automation libraries, and DevSecOps culture ensure rapid delivery without compromising safety.</p>
            <div class="grid grid-cols-2 gap-6 text-sm text-gray-300">
                <div>
                    <h4 class="font-semibold text-light">Accelerated Deployment</h4>
                    <p class="mt-2 text-gray-400">Reusable assets cut delivery time while preserving quality.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-light">Lifecycle Partnership</h4>
                    <p class="mt-2 text-gray-400">We iterate alongside your teams to optimize performance and adoption.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-light">Insight-Driven Decisions</h4>
                    <p class="mt-2 text-gray-400">Observability and analytics keep leadership informed in real time.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-light">Security Embedded</h4>
                    <p class="mt-2 text-gray-400">Guardrails and policy-as-code uphold compliance across industries.</p>
                </div>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-secondary inline-flex">Discuss your solution</a>
        </div>
    </div>
</section>
<section class="py-24 bg-gradient-to-br from-primary/80 to-black">
    <div class="max-w-5xl mx-auto px-6 text-center space-y-6" data-animate>
        <h2 class="text-3xl md:text-4xl font-semibold">Let’s unlock your next horizon</h2>
        <p class="text-lg text-gray-100">Connect with TekGurus to design a roadmap tailored to your industry, customers, and strategic goals. Together we’ll build a resilient, intelligent cloud ecosystem.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">Start a conversation</a>
            <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="btn-secondary">View our services</a>
        </div>
    </div>
</section>
<?php
get_footer();
