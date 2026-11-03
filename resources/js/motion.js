import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Wajbati Declarative Motion System (Phase 3 Revised)
 * Fixed page-wide background canvas sequence with smooth frame lerping,
 * adaptive scrim ramping, and progressive enhancement reveals.
 */

// 1. Hero Canvas Fixed Background Sequence
export function initHeroSequence() {
    const container = document.getElementById('hero-section') || document.getElementById('hero-sequence-container');
    const canvas = document.getElementById('hero-canvas');
    const scrim = document.getElementById('hero-scrim');
    if (!container || !canvas) return;

    const ctx = canvas.getContext('2d');
    const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isMobile = window.innerWidth < 768;
    const totalFrames = 49;
    const step = isMobile ? 2 : 1;
    const basePath = container.dataset.basePath || '/assets/hero/';
    const isAutoplay = container.dataset.autoplay === 'true';

    const images = new Map();
    let currentFrame = 1;
    let targetFrame = 1;
    let lastDrawnFrame = -1;
    let isLoopRunning = false;

    // Helper: zero-pad frame index (e.g. 1 -> "f_001.webp")
    function getFrameUrl(index) {
        const padded = String(index).padStart(3, '0');
        return `${basePath}f_${padded}.webp`;
    }

    // High-DPR Canvas Sizing
    function resizeCanvas() {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const displayWidth = window.innerWidth;
        const displayHeight = window.innerHeight;

        const targetW = Math.round(displayWidth * dpr);
        const targetH = Math.round(displayHeight * dpr);

        if (canvas.width !== targetW || canvas.height !== targetH) {
            canvas.width = targetW;
            canvas.height = targetH;
        }
    }

    // Draw frame on canvas with aspect ratio cover fit
    function drawFrame(frameIndex) {
        const img = images.get(frameIndex);
        if (!img || !img.complete || img.naturalWidth === 0) return false;

        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const displayWidth = window.innerWidth;
        const displayHeight = window.innerHeight;

        resizeCanvas();

        ctx.save();
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

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
        ctx.restore();
        return true;
    }

    // Lerp loop via requestAnimationFrame (≈0.12/frame)
    function startLerpLoop() {
        if (isLoopRunning) return;
        isLoopRunning = true;

        function loop() {
            currentFrame += (targetFrame - currentFrame) * 0.12;
            const rounded = Math.max(1, Math.min(totalFrames, Math.round(currentFrame)));

            if (rounded !== lastDrawnFrame && images.has(rounded)) {
                drawFrame(rounded);
                lastDrawnFrame = rounded;
            }

            if (Math.abs(targetFrame - currentFrame) > 0.05) {
                requestAnimationFrame(loop);
            } else {
                currentFrame = targetFrame;
                const finalRound = Math.round(currentFrame);
                if (finalRound !== lastDrawnFrame && images.has(finalRound)) {
                    drawFrame(finalRound);
                    lastDrawnFrame = finalRound;
                }
                isLoopRunning = false;
            }
        }

        requestAnimationFrame(loop);
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
            img.onerror = () => {
                console.warn(`[Hero] Failed to load frame ${index}: ${img.src}`);
                resolve(null);
            };
        });
    }

    // Size the canvas after layout (first rAF)
    requestAnimationFrame(() => {
        resizeCanvas();
        console.log('[Hero] Initial canvas size:', canvas.width, 'x', canvas.height, 'dpr:', Math.min(window.devicePixelRatio || 1, 2));
        if (scrim) {
            scrim.style.opacity = '0.30';
            const computedOpacity = window.getComputedStyle(scrim).opacity;
            console.log('[Hero] Scrim initial computed opacity:', computedOpacity);
        }
    });

    // Reduced Motion Mode: Render static final frame and set steady scrim
    if (isReducedMotion) {
        loadFrame(totalFrames).then(() => {
            drawFrame(totalFrames);
            if (scrim) scrim.style.opacity = '0.75';
            const beat1 = document.getElementById('hero-beat-1');
            const beat3 = document.getElementById('hero-beat-3');
            if (beat1) beat1.style.display = 'none';
            if (beat3) {
                beat3.style.opacity = '1';
                beat3.style.pointerEvents = 'auto';
            }
        });
        return;
    }

    // 1. Immediately preload frame 1 and draw poster as soon as it loads
    loadFrame(1).then((img) => {
        resizeCanvas();
        const success = drawFrame(1);
        lastDrawnFrame = 1;
        console.log('[Hero] Frame 1 loaded:', !!img, 'drawImage executed:', success, 'canvas size:', canvas.width, 'x', canvas.height);
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
        resizeCanvas();
        drawFrame(Math.round(currentFrame));
    }, { passive: true });

    // Copy beats elements
    const beat1 = document.getElementById('hero-beat-1');
    const beat2 = document.getElementById('hero-beat-2');
    const beat3 = document.getElementById('hero-beat-3');

    // Scroll Handler (Maps 0 -> 1.5x Viewport Height only, then holds frame 49)
    function handleScroll() {
        const scrollDistance = window.innerHeight * 1.5;
        const progress = Math.min(1, Math.max(0, window.scrollY / scrollDistance));

        targetFrame = 1 + Math.round(progress * (totalFrames - 1));
        startLerpLoop();

        // Ramp scrim opacity from 0.30 (vivid at hero top, <= 35%) up to 0.85 (settled background behind cards)
        if (scrim) {
            const scrimOpacity = 0.30 + (progress * 0.55);
            scrim.style.opacity = scrimOpacity.toFixed(3);
        }

        // Crossfade copy beats in place without separate page pinning
        if (beat1 && beat2 && beat3) {
            if (progress <= 0.25) {
                // Beat 1 active (1.0 -> 0.0)
                const p = progress / 0.25;
                beat1.style.opacity = (1 - p).toFixed(3);
                beat1.style.transform = `translateY(${-p * 15}px)`;
                beat1.style.pointerEvents = p > 0.8 ? 'none' : 'auto';

                beat2.style.opacity = '0';
                beat2.style.pointerEvents = 'none';

                beat3.style.opacity = '0';
                beat3.style.pointerEvents = 'none';
            } else if (progress <= 0.60) {
                // Beat 2 active
                beat1.style.opacity = '0';
                beat1.style.pointerEvents = 'none';

                const p = (progress - 0.25) / 0.35;
                const b2Opacity = p < 0.5 ? (p * 2) : (1 - (p - 0.5) * 2);
                beat2.style.opacity = Math.max(0, b2Opacity).toFixed(3);
                beat2.style.transform = `translateY(${10 - (p * 20)}px)`;
                beat2.style.pointerEvents = b2Opacity > 0.2 ? 'auto' : 'none';

                beat3.style.opacity = '0';
                beat3.style.pointerEvents = 'none';
            } else {
                // Beat 3 active (holds CTAs and final heading)
                beat1.style.opacity = '0';
                beat1.style.pointerEvents = 'none';

                beat2.style.opacity = '0';
                beat2.style.pointerEvents = 'none';

                const p = Math.min(1, (progress - 0.60) / 0.30);
                beat3.style.opacity = p.toFixed(3);
                beat3.style.transform = `translateY(${15 - (p * 15)}px)`;
                beat3.style.pointerEvents = 'auto';
            }
        }
    }

    if (isAutoplay) {
        // Autoplay Fallback Mode: plays once on load over 3.2s, then locks to frame 49
        const track = { frame: 1 };
        gsap.to(track, {
            frame: totalFrames,
            duration: 3.2,
            ease: 'power1.inOut',
            onUpdate: () => {
                targetFrame = Math.round(track.frame);
                startLerpLoop();
            },
            onComplete: () => {
                targetFrame = totalFrames;
                startLerpLoop();
            }
        });
    } else {
        // Natural scroll scrub (default)
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll(); // Initial run on load
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

// 4. Declarative Reveals & Staggers (Progressive Enhancement + Failsafe)
export function initReveals() {
    // Progressive enhancement: add motion class to html
    document.documentElement.classList.add('js-motion');

    const revealElements = document.querySelectorAll('[data-reveal]');
    const staggerContainers = document.querySelectorAll('[data-reveal-stagger]');

    if (!revealElements.length && !staggerContainers.length) return;

    let hasForceRevealed = false;
    function forceRevealAll() {
        if (hasForceRevealed) return;
        hasForceRevealed = true;

        if (revealElements.length) {
            gsap.to(revealElements, { opacity: 1, y: 0, duration: 0.35, overwrite: 'auto' });
        }
        staggerContainers.forEach((container) => {
            gsap.to(container.children, { opacity: 1, y: 0, duration: 0.35, overwrite: 'auto' });
        });
    }

    // Failsafe timeout: reveal everything after 1.2s so no section is ever stuck at opacity:0
    setTimeout(forceRevealAll, 1200);

    // Fast scroll & bfcache/page restore failsafes
    window.addEventListener('pageshow', forceRevealAll, { passive: true });
    window.addEventListener('scroll', () => {
        if (window.scrollY > window.innerHeight && !hasForceRevealed) {
            forceRevealAll();
        }
    }, { passive: true, once: true });

    // Single reveals via IntersectionObserver
    if (revealElements.length) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                obs.unobserve(entry.target);
                gsap.to(entry.target, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out', overwrite: 'auto' });
            });
        }, { threshold: 0.08 });

        revealElements.forEach((el) => observer.observe(el));
    }

    // Stagger containers
    if (staggerContainers.length) {
        const staggerObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                obs.unobserve(entry.target);
                const children = entry.target.children;
                gsap.to(children, { opacity: 1, y: 0, duration: 0.45, stagger: 0.06, ease: 'power2.out', overwrite: 'auto' });
            });
        }, { threshold: 0.08 });

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
