<?php

declare(strict_types=1);

function testimonialCardStyles(): void
{
    static $printed = false;
    if ($printed) {
        return;
    }
    $printed = true;
    ?>
    <style>
        .testimonial-card {
            display: flex;
            align-items: stretch;
            gap: 0.75rem;
            width: 100%;
            height: 300px;
            min-height: 300px;
            max-height: 300px;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #053b7a;
            box-sizing: border-box;
        }

        .glide.Testimonials .glide__slides {
            align-items: stretch;
        }

        .glide.Testimonials .glide__slide {
            display: flex;
            height: auto;
        }

        .testimonial-card__media {
            flex: 0 0 30%;
            max-width: 30%;
            text-align: center;
        }

        .testimonial-card__content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .testimonial-card__name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-top: 0.5rem;
        }

        .testimonial-card__body {
            flex: 1;
            min-height: 0;
            margin-top: 0.25rem;
            font-size: 16px;
            line-height: 1.5;
            color: #fff;
            overflow: hidden;
        }

        .testimonial-card__body.is-collapsed {
            max-height: 6em;
            overflow: hidden;
        }

        .testimonial-card__body.is-expanded {
            max-height: 150px;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .testimonial-card__body p,
        .testimonial-card__body span,
        .testimonial-card__body li,
        .testimonial-card__body a {
            color: #fff !important;
        }

        .testimonial-card__toggle {
            flex-shrink: 0;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            text-align: left;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .testimonial-card__toggle:hover {
            text-decoration: underline;
        }

        .testimonial-card__toggle.is-hidden {
            display: none;
        }

        @media (max-width: 640px) {
            .testimonial-card {
                flex-direction: column;
                height: auto;
                min-height: 280px;
                max-height: none;
            }

            .testimonial-card__media {
                flex: none;
                max-width: 100%;
                width: 90%;
                margin: 0 auto;
            }

            .testimonial-card__body.is-expanded {
                max-height: 180px;
            }
        }
    </style>
    <?php
}

function testimonialCardScripts(): void
{
    static $printed = false;
    if ($printed) {
        return;
    }
    $printed = true;
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const initCards = function () {
                document.querySelectorAll('.testimonial-card').forEach(function (card) {
                    const body = card.querySelector('.testimonial-card__body');
                    const toggle = card.querySelector('.testimonial-card__toggle');
                    if (!body || !toggle || body.dataset.ready === '1') {
                        return;
                    }
                    body.dataset.ready = '1';
                    body.classList.add('is-collapsed');

                    if (body.scrollHeight <= body.clientHeight + 2) {
                        toggle.classList.add('is-hidden');
                    }

                    toggle.addEventListener('click', function () {
                        const expanded = body.classList.toggle('is-expanded');
                        body.classList.toggle('is-collapsed', !expanded);
                        toggle.textContent = expanded ? 'Read less' : 'Read more...';
                    });
                });
            };

            if ('requestIdleCallback' in window) {
                requestIdleCallback(initCards, { timeout: 500 });
            } else {
                requestAnimationFrame(initCards);
            }
        });
    </script>
    <?php
}

function renderTestimonialCard(array $row): void
{
    $heading = htmlspecialchars((string) ($row['heading'] ?? $row['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $image = htmlspecialchars((string) ($row['media']['urls'] ?? $row['image'] ?? ''), ENT_QUOTES, 'UTF-8');
    $description = (string) ($row['description'] ?? '');
    ?>
    <li class="testimonial-card glide__slide">
        <div class="testimonial-card__media">
            <?php if ($image !== ''): ?>
                <img src="<?= $image ?>" alt="<?= $heading ?>" class="mb-2 mx-auto rounded-[10px] w-[130px] h-[130px] object-cover">
            <?php endif; ?>
            <?php if ($heading !== ''): ?>
                <h2 class="testimonial-card__name"><?= $heading ?></h2>
            <?php endif; ?>
        </div>
        <div class="testimonial-card__content sm:text-left text-center">
            <div class="testimonial-card__body is-collapsed"><?= $description ?></div>
            <button type="button" class="testimonial-card__toggle">Read more...</button>
        </div>
    </li>
    <?php
}
