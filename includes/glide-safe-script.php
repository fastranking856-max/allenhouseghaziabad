<?php

declare(strict_types=1);

/**
 * Safe Glide mount helper — include after glide.js (e.g. in foot.php).
 */
function glideSafeScript(): void
{
    static $printed = false;
    if ($printed) {
        return;
    }
    $printed = true;
    ?>
    <script>
    (function () {
        if (window.safeMountGlide) {
            return;
        }
        window.safeMountGlide = function (selector, options) {
            if (typeof Glide === 'undefined') {
                return null;
            }
            var root = document.querySelector(selector);
            if (!root) {
                return null;
            }
            var track = root.querySelector('[data-glide-el="track"]');
            if (!track) {
                return null;
            }
            var slides = track.querySelectorAll('.glide__slide');
            var count = slides.length;
            if (!count) {
                var list = track.querySelector('ul');
                count = list ? list.querySelectorAll(':scope > li').length : 0;
            }
            if (count < 1) {
                return null;
            }
            var nav = root.querySelector('[data-glide-el="controls[nav]"]');
            if (nav) {
                var maxNavDots = 4;
                if (count > maxNavDots || count <= 1) {
                    nav.style.display = 'none';
                    nav.setAttribute('aria-hidden', 'true');
                } else {
                    nav.querySelectorAll('button[data-glide-dir^="="]').forEach(function (btn, index) {
                        if (index >= count) {
                            btn.remove();
                        }
                    });
                }
            }
            var opts = JSON.parse(JSON.stringify(options || {}));
            function cap(v) {
                return typeof v === 'number' ? Math.min(v, count) : v;
            }
            if (typeof opts.perView === 'number') {
                opts.perView = cap(opts.perView);
            }
            if (opts.breakpoints) {
                Object.keys(opts.breakpoints).forEach(function (key) {
                    var bp = opts.breakpoints[key];
                    if (bp && typeof bp.perView === 'number') {
                        bp.perView = cap(bp.perView);
                    }
                });
            }
            if (count <= 1) {
                opts.autoplay = false;
                opts.type = 'slider';
            }
            try {
                var instance = new Glide(selector, opts);
                instance.mount();
                return instance;
            } catch (err) {
                console.error('Glide failed for', selector, err);
                return null;
            }
        };
    })();
    </script>
    <?php
}
