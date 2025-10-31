<?php
/**
 * Template for Contact page.
 *
 * @package TekGurus
 */
get_header();
?>
<section class="relative pt-40 pb-24 bg-cover bg-center" style="background-image: url('<?php echo esc_url( TEKGURUS_THEME_URI . '/assets/images/hero2.svg' ); ?>');">
    <div class="absolute inset-0 bg-black/75"></div>
    <div class="relative max-w-4xl mx-auto px-6 text-center space-y-6">
        <p class="eyebrow">Contact TekGurus</p>
        <h1 class="text-4xl md:text-5xl font-semibold">Let’s co-create the next evolution of your cloud journey</h1>
        <p class="text-lg text-gray-200">Share your goals and challenges. Our experts will collaborate with you to define the path forward—whether it’s modernization, automation, or managed services.</p>
    </div>
</section>
<section class="py-24 bg-dark">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12">
        <div class="space-y-6" data-animate>
            <p class="eyebrow">Talk to Our Experts</p>
            <h2 class="section-title">We’re ready to listen and lead together</h2>
            <p class="text-gray-300 leading-relaxed">Tell us about your current initiatives, desired outcomes, and timelines. A TekGurus consultant will reach out to craft a tailored engagement plan.</p>
            <div class="bg-[#0f0f0f] border border-white/10 rounded-3xl p-8 space-y-4">
                <p class="flex items-center space-x-3 text-gray-300"><span class="contact-icon">📍</span><span>7 Ibiyinka Olorunbe, VI, Lagos, Nigeria</span></p>
                <p class="flex items-center space-x-3 text-gray-300"><span class="contact-icon">✉️</span><a href="mailto:info@thetekgurus.com" class="hover:text-primary">info@thetekgurus.com</a></p>
                <p class="flex items-center space-x-3 text-gray-300"><span class="contact-icon">📞</span><a href="tel:+2349049206989" class="hover:text-primary">+234 904 920 6989</a></p>
            </div>
            <div class="grid grid-cols-2 gap-6 text-gray-300 text-sm">
                <div>
                    <h3 class="text-lg font-semibold text-light">Office Hours</h3>
                    <p class="mt-2 text-gray-400">Monday – Friday<br>09:00 – 18:00 (WAT)</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-light">Follow TekGurus</h3>
                    <p class="mt-2 text-gray-400">LinkedIn · Twitter · Instagram · YouTube</p>
                </div>
            </div>
        </div>
        <form class="bg-[#0f0f0f] border border-white/10 rounded-3xl p-8 space-y-6" data-animate>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label" for="name">Full Name</label>
                    <input id="name" type="text" class="form-input" placeholder="Jane Doe" required>
                </div>
                <div>
                    <label class="form-label" for="email">Work Email</label>
                    <input id="email" type="email" class="form-input" placeholder="you@company.com" required>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label" for="company">Company</label>
                    <input id="company" type="text" class="form-input" placeholder="Your organization" required>
                </div>
                <div>
                    <label class="form-label" for="phone">Phone</label>
                    <input id="phone" type="tel" class="form-input" placeholder="+234 000 000 0000">
                </div>
            </div>
            <div>
                <label class="form-label" for="interest">How can we help?</label>
                <select id="interest" class="form-input" required>
                    <option value="" disabled selected>Select an option</option>
                    <option>Cloud Strategy &amp; Advisory</option>
                    <option>Implementation &amp; Migration</option>
                    <option>Security &amp; Governance</option>
                    <option>Optimization &amp; Automation</option>
                    <option>Managed Services</option>
                    <option>Training &amp; Enablement</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="message">Project Details</label>
                <textarea id="message" rows="4" class="form-input" placeholder="Share goals, timelines, and success measures"></textarea>
            </div>
            <button type="submit" class="btn-primary w-full justify-center">Submit Request</button>
            <p class="text-xs text-gray-500">By submitting this form you agree to receive communications from TekGurus. You may unsubscribe at any time.</p>
        </form>
    </div>
</section>
<section class="py-24 bg-[#0a0a0a]">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6" data-animate>
            <p class="eyebrow">Visit Us</p>
            <h2 class="section-title">Our Lagos headquarters</h2>
            <p class="text-gray-300">We’re located in the heart of Victoria Island with collaboration spaces designed for innovation workshops, training sessions, and executive briefings.</p>
            <a href="mailto:info@thetekgurus.com" class="btn-secondary inline-flex">Schedule an onsite session</a>
        </div>
        <div class="relative h-80 rounded-3xl overflow-hidden border border-white/10" data-animate>
            <div class="absolute inset-0 bg-black/70 flex items-center justify-center text-gray-400">
                <p>Google Map placeholder – embed map here</p>
            </div>
        </div>
    </div>
</section>
<section class="py-24 bg-gradient-to-br from-primary/80 to-black">
    <div class="max-w-4xl mx-auto px-6 text-center space-y-6" data-animate>
        <h2 class="text-3xl md:text-4xl font-semibold">Partner with TekGurus today</h2>
        <p class="text-lg text-gray-100">Our experts are ready to help you modernize infrastructure, launch new products, and empower your teams.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="tel:+2349049206989" class="btn-primary">Call us</a>
            <a href="mailto:info@thetekgurus.com" class="btn-secondary">Email TekGurus</a>
        </div>
    </div>
</section>
<?php
get_footer();
