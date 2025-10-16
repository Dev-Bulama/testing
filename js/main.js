document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');
    const heroSlides = document.querySelectorAll('.hero-slide');
    const sliderDotsContainer = document.querySelector('.slider-dots');
    const sliderControls = document.querySelectorAll('.slider-control');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenu = document.getElementById('mobile-menu');
    const megaMenus = document.querySelectorAll('.mega-menu');
    const navLinks = document.querySelectorAll('.nav-link');
    const animateElements = document.querySelectorAll('[data-animate]');

    /* Sticky header */
    const handleScroll = () => {
        if (window.scrollY > 40) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };
    handleScroll();
    window.addEventListener('scroll', handleScroll);

    /* Hero slider */
    let currentSlide = 0;
    let sliderInterval;

    const createDots = () => {
        if (!sliderDotsContainer || heroSlides.length === 0) return;
        sliderDotsContainer.innerHTML = '';
        heroSlides.forEach((_slide, index) => {
            const dot = document.createElement('button');
            dot.setAttribute('type', 'button');
            dot.className = index === 0 ? 'active' : '';
            dot.addEventListener('click', () => {
                showSlide(index);
                restartSlider();
            });
            sliderDotsContainer.appendChild(dot);
        });
    };

    const updateDots = () => {
        if (!sliderDotsContainer) return;
        const dots = sliderDotsContainer.querySelectorAll('button');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    };

    const showSlide = (index) => {
        if (heroSlides.length === 0) return;
        currentSlide = (index + heroSlides.length) % heroSlides.length;
        heroSlides.forEach((slide, slideIndex) => {
            slide.classList.toggle('active', slideIndex === currentSlide);
        });
        updateDots();
    };

    const nextSlide = () => showSlide(currentSlide + 1);
    const prevSlide = () => showSlide(currentSlide - 1);

    const startSlider = () => {
        if (heroSlides.length === 0) return;
        sliderInterval = setInterval(nextSlide, 7000);
    };

    const restartSlider = () => {
        if (!sliderInterval) return;
        clearInterval(sliderInterval);
        startSlider();
    };

    if (heroSlides.length > 0) {
        heroSlides[0].classList.add('active');
        createDots();
        startSlider();
    }

    sliderControls.forEach(control => {
        control.addEventListener('click', () => {
            const direction = control.dataset.direction;
            if (direction === 'next') {
                nextSlide();
            } else {
                prevSlide();
            }
            restartSlider();
        });
    });

    /* Mega menu interactions */
    let megaMenuTimer;

    const hideMegaMenus = () => {
        megaMenus.forEach(menu => menu.classList.remove('active'));
        navLinks.forEach(link => link.removeAttribute('aria-expanded'));
    };

    const openMegaMenu = (target) => {
        if (!target) return;
        clearTimeout(megaMenuTimer);
        megaMenus.forEach(menu => {
            const isMatch = menu.dataset.menu === target;
            menu.classList.toggle('active', isMatch);
        });
        navLinks.forEach(link => {
            const expanded = link.dataset.menuTarget === target ? 'true' : 'false';
            if (expanded === 'true') {
                link.setAttribute('aria-expanded', 'true');
            } else {
                link.removeAttribute('aria-expanded');
            }
        });
    };

    navLinks.forEach(link => {
        const target = link.dataset.menuTarget;
        if (!target) return;
        link.addEventListener('mouseenter', () => openMegaMenu(target));
        link.addEventListener('focus', () => openMegaMenu(target));
        link.addEventListener('mouseleave', () => {
            megaMenuTimer = setTimeout(hideMegaMenus, 250);
        });
    });

    megaMenus.forEach(menu => {
        menu.addEventListener('mouseenter', () => clearTimeout(megaMenuTimer));
        menu.addEventListener('mouseleave', () => {
            megaMenuTimer = setTimeout(hideMegaMenus, 150);
        });
    });

    document.querySelector('.mega-menu-wrapper')?.addEventListener('mouseleave', () => {
        megaMenuTimer = setTimeout(hideMegaMenus, 150);
    });

    document.addEventListener('click', (event) => {
        const target = event.target;
        if (!target.closest('.site-header')) {
            hideMegaMenus();
        }
    });

    /* Mobile menu */
    const toggleMobileMenu = (open) => {
        if (!mobileMenu) return;
        if (open) {
            mobileMenu.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            mobileMenu.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    };

    mobileMenuToggle?.addEventListener('click', () => toggleMobileMenu(true));
    mobileMenuClose?.addEventListener('click', () => toggleMobileMenu(false));

    mobileMenu?.addEventListener('click', (event) => {
        if (event.target === mobileMenu) {
            toggleMobileMenu(false);
        }
    });

    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => toggleMobileMenu(false));
    });

    /* Reveal on scroll */
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        animateElements.forEach(el => observer.observe(el));
    } else {
        animateElements.forEach(el => el.classList.add('visible'));
    }
});
