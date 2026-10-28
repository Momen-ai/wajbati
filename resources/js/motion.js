import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Wajbati Declarative Motion System
 * Powered by GSAP 3, ScrollTrigger & IntersectionObserver.
 * Adheres strictly to WCAG AA, prefers-reduced-motion, and 60fps performance budgets.
 */

// 1. Hero Canvas Scroll Sequence
export function initHeroSequence() {
    const container = document.getElementById('hero-sequence-container');
    const canvas = document.getElementById('hero-canvas');
    if (!container || !canvas) return;

    const ctx = canvas.getContext('2d');
    const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isMobile = window.innerWidth < 768;
    const totalFrames = 49;
    const step = isMobile ? 2 : 1;
    const basePath = container.dataset.basePath || '/assets/hero/';

    const images = new Map();
    let currentFrameIndex = 1;
    let rafId = null;

    // Helper: zero-pad frame index (e.g. 1 -> "f_001.webp")
    function getFrameUrl(index) {
        const padded = String(index).padStart(3, '0');
        return `${basePath}f_${padded}.webp`;
    }

    // High-DPR Canvas Sizing & Cover Drawing
    function drawFrame(frameIndex) {
        const img = images.get(frameIndex);
        if (!img || !img.complete || img.naturalWidth === 0) return;

        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const displayWidth = container.clientWidth;
        const displayHeight = container.clientHeight;

        if (canvas.width !== displayWidth * dpr || canvas.height !== displayHeight * dpr) {
            canvas.width = displayWidth * dpr;
            canvas.height = displayHeight * dpr;
            ctx.scale(dpr, dpr);
        }

        const imgWidth = img.naturalWidth;
        const imgHeight = img.naturalHeight;
        const imgRatio = imgWidth / imgHeight;
        const canvasRatio = displayWidth / displayHeight;

        let renderWidth, renderHeight, offsetX, offsetY;

        if (canvasRatio > imgRatio) {
            renderWidth = displayWidth;
            renderHeight = displayWidth / imgRatio;
            offsetX = 0;
            offsetY = (displayHeight - renderHeight) / 2;
        } else {
            renderHeight = displayHeight;
            renderWidth = displayHeight * imgRatio;
            offsetX = (displayWidth - renderWidth) / 2;
            offsetY = 0;
        }

        ctx.clearRect(0, 0, displayWidth, displayHeight);
        ctx.drawImage(img, offsetX, offsetY, renderWidth, renderHeight);
    }

    function scheduleDraw(frameIndex) {
        if (currentFrameIndex === frameIndex && images.has(frameIndex)) return;
        currentFrameIndex = frameIndex;
        if (rafId) cancelAnimationFrame(rafId);
        rafId = requestAnimationFrame(() => drawFrame(currentFrameIndex));
    }

    // Load an image and cache it
    function loadFrame(index) {
        if (images.has(index)) return Promise.resolve(images.get(index));
        return new Promise((resolve) => {
            const img = new Image();
            img.src = getFrameUrl(index);
            img.onload = () => {
                images.set(index, img);
                resolve(img);
            };
            img.onerror = () => resolve(null);
        });
    }

    // Reduced Motion Mode: Render static endpoint
    if (isReducedMotion) {
        loadFrame(totalFrames).then(() => {
            drawFrame(totalFrames);
            container.classList.add('reduced-motion-active');
        });
        return;
    }

    // 1. Immediately preload frame 1 and draw poster
    loadFrame(1).then(() => {
        drawFrame(1);
    });

    // 2. Preload first 8 frames eagerly
    const eagerFrames = [];
    for (let i = 1; i <= Math.min(8, totalFrames); i += step) {
        eagerFrames.push(loadFrame(i));
    }

    // 3. Progressively load remainder in idle time
    Promise.all(eagerFrames).then(() => {
        const idleLoad = () => {
            for (let i = 9; i <= totalFrames; i += step) {
                loadFrame(i);
            }
        };

        if ('requestIdleCallback' in window) {
            requestIdleCallback(idleLoad, { timeout: 2000 });
        } else {
            setTimeout(idleLoad, 300);
        }
    });

    // Handle Window Resize
    window.addEventListener('resize', () => {
        drawFrame(currentFrameIndex);
    }, { passive: true });

    // GSAP ScrollTrigger Sequence Timeline
    const track = { frame: 1 };
    const beat1 = document.getElementById('hero-beat-1');
    const beat2 = document.getElementById('hero-beat-2');
    const beat3 = document.getElementById('hero-beat-3');

    const heroTimeline = gsap.timeline({
        scrollTrigger: {
            trigger: container,
            start: 'top top',
            end: isMobile ? '+=160%' : '+=250%',
            pin: true,
            scrub: 0.4,
            onUpdate: (self) => {
                const targetFrame = Math.max(1, Math.min(totalFrames, Math.round(track.frame)));
                scheduleDraw(targetFrame);
            }
        }
    });

    // Animate frame track
    heroTimeline.to(track, {
        frame: totalFrames,
        ease: 'none',
        duration: 1
    }, 0);

    // Synchronize Copy Beats
    // Beat 1: frames 1-12 (0.0 to ~0.25)
    if (beat1) {
        heroTimeline.fromTo(beat1, { opacity: 1, y: 0 }, { opacity: 0, y: -20, ease: 'power1.out', duration: 0.25 }, 0.05);
    }

    // Beat 2: frames 13-28 (~0.26 to ~0.58)
    if (beat2) {
        heroTimeline.fromTo(beat2, { opacity: 0, y: 25 }, { opacity: 1, y: 0, ease: 'power2.out', duration: 0.15 }, 0.28)
                    .to(beat2, { opacity: 0, y: -20, ease: 'power1.in', duration: 0.15 }, 0.52);
    }

    // Beat 3: frames 29-49 (~0.60 to 1.0)
    if (beat3) {
        heroTimeline.fromTo(beat3, { opacity: 0, y: 30 }, { opacity: 1, y: 0, ease: 'power2.out', duration: 0.2 }, 0.62);
    }
}

// 2. Cursor Spotlight Cards (21st.dev style)
export function initSpotlights() {
    const cards = document.querySelectorAll('.spotlight-card, [data-spotlight]');
    if (!cards.length) return;

    cards.forEach((card) => {
        let isMoving = false;

        card.addEventListener('pointermove', (e) => {
            if (isMoving) return;
            isMoving = true;
            requestAnimationFrame(() => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
                isMoving = false;
            });
        }, { passive: true });
    });
}

// 3. Count-up Numeric Animation
export function initCountUp() {
    const elements = document.querySelectorAll('[data-count-up]');
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            obs.unobserve(entry.target);

            const el = entry.target;
            const targetVal = parseFloat(el.getAttribute('data-count-up')) || 0;
            const isCurrency = el.textContent.includes('₪') || el.textContent.includes('ILS');
            const currencySymbol = isCurrency ? (el.textContent.includes('₪') ? '₪' : 'ILS') : '';

            const counter = { val: 0 };
            gsap.to(counter, {
                val: targetVal,
                duration: 1.4,
                ease: 'power2.out',
                onUpdate: () => {
                    const formatted = Math.floor(counter.val).toLocaleString();
                    el.textContent = currencySymbol ? `${currencySymbol} ${formatted}` : formatted;
                }
            });
        });
    }, { threshold: 0.2 });

    elements.forEach((el) => observer.observe(el));
}

// 4. Declarative Reveals & Staggers
export function initReveals() {
    // Single reveals
    const revealElements = document.querySelectorAll('[data-reveal]');
    if (revealElements.length) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                obs.unobserve(entry.target);
                gsap.fromTo(entry.target,
                    { opacity: 0, y: 20 },
                    { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
                );
            });
        }, { threshold: 0.1 });

        revealElements.forEach((el) => observer.observe(el));
    }

    // Stagger containers
    const staggerContainers = document.querySelectorAll('[data-reveal-stagger]');
    if (staggerContainers.length) {
        const staggerObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                obs.unobserve(entry.target);
                const children = entry.target.children;
                gsap.fromTo(children,
                    { opacity: 0, y: 20 },
                    { opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out' }
                );
            });
        }, { threshold: 0.1 });

        staggerContainers.forEach((container) => staggerObserver.observe(container));
    }
}

// 5. Magnetic CTA Buttons
export function initMagnetic() {
    const magnetics = document.querySelectorAll('[data-magnetic]');
    if (!magnetics.length || window.innerWidth < 768) return;

    magnetics.forEach((btn) => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = (e.clientX - rect.left - rect.width / 2) * 0.25;
            const y = (e.clientY - rect.top - rect.height / 2) * 0.25;
            gsap.to(btn, { x, y, duration: 0.3, ease: 'power2.out' });
        });

        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.4)' });
        });
    });
}

// 6. Sticky Navbar Scroll Condensation
export function initStickyNav() {
    const navbar = document.querySelector('.navbar.sticky-top');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 40) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    }, { passive: true });
}

// Global Bootstrap of Motion System
export function initMotion() {
    initHeroSequence();
    initSpotlights();
    initCountUp();
    initReveals();
    initMagnetic();
    initStickyNav();
}

document.addEventListener('DOMContentLoaded', () => {
    initMotion();
});
