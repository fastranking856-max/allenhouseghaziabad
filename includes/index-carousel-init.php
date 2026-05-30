<?php

declare(strict_types=1);

/**
 * Initializes index-page Glide carousels (requires glide-safe-script.php in foot.php).
 */
function indexCarouselInitScript(): void
{
    static $printed = false;
    if ($printed) {
        return;
    }
    $printed = true;
    ?>
    <script>
    (function () {
        function initIndexCarousels() {
            if (typeof window.safeMountGlide !== 'function') {
                return;
            }
            var mount = window.safeMountGlide;

            mount('.heroSlider', {
                type: 'carousel',
                focusAt: 0,
                perView: 1,
                autoplay: 3500,
                animationDuration: 700,
                gap: 0,
                breakpoints: {
                    1024: { perView: 1 },
                    640: { perView: 1 }
                }
            });

            mount('.excellance-glide', {
                type: 'carousel',
                perView: 4,
                gap: 20,
                autoplay: 3500,
                animationDuration: 700,
                breakpoints: {
                    1280: { perView: 3 },
                    950: { perView: 2 },
                    640: { perView: 1 }
                }
            });

            mount('.glide-0222', {
                type: 'carousel',
                focusAt: 0,
                perView: 3,
                gap: 24,
                autoplay: 3500,
                animationDuration: 700,
                breakpoints: {
                    1280: { perView: 3, gap: 24 },
                    900: { perView: 2, gap: 20 },
                    640: { perView: 1, gap: 12 }
                }
            });

            mount('.glide-0333', {
                type: 'carousel',
                perView: 3,
                gap: 24,
                autoplay: 3500,
                animationDuration: 700,
                breakpoints: {
                    1280: { perView: 3 },
                    1024: { perView: 2 },
                    640: { perView: 1 }
                }
            });

            mount('.glide.Testimonials', {
                type: 'carousel',
                focusAt: 0,
                perView: 2,
                autoplay: 3500,
                animationDuration: 700,
                gap: 16,
                breakpoints: {
                    1024: { perView: 2, gap: 16 },
                    640: { perView: 1, gap: 12 }
                }
            });

            mount('.Images', {
                type: 'carousel',
                perView: 4,
                autoplay: 3500,
                animationDuration: 700,
                breakpoints: {
                    1024: { perView: 3 },
                    820: { perView: 2 },
                    640: { perView: 1.2 }
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initIndexCarousels, { once: true });
        } else {
            initIndexCarousels();
        }
    })();
    </script>
    <?php
}
