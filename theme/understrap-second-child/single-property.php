<?php
/* Property
 * Template Post Type: property

 * The template for displaying Catalog pages
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */
defined('ABSPATH') || exit;

get_header();

// Start the loop
while (have_posts()):
    the_post();

    $house_name = get_field('house_name') ?: get_the_title();
    $coordinates = get_field('coordinates') ?: '';
    $floors = get_field('floors') ?: '';
    $building_type = get_field('building_type') ?: '';
    $eco_rating = get_field('eco_rating') ?: 0;
    $premises = get_field('premises') ?: array();

    $building_type_labels = array(
        'panel' => 'Панель',
        'brick' => 'Цегла',
        'foam' => 'Піноблок'
    );
    $building_type_label = isset($building_type_labels[$building_type]) ? $building_type_labels[$building_type] : $building_type;
    ?>

    <div class="property-single">
        <!-- Hero Section with Property Image -->
        <section class="property-hero position-relative">
            <?php if (has_post_thumbnail()): ?>
                <div class="property-hero-image" style=" 
                    height: 60vh; 
                    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('<?php echo get_the_post_thumbnail_url(null, 'full'); ?>') no-repeat center/cover;
                    border-bottom-left-radius: 15px;
                    border-bottom-right-radius: 15px;
                    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.4);
                    overflow: hidden;
                ">
                    <div class="container h-100 d-flex align-items-center">
                        <h1 class="text-white" style="margin: 0 auto; text-align: center; font-size: 2.5rem;">
                            <?php echo esc_html($house_name); ?>
                        </h1>
                    </div>
                </div>
            <?php endif; ?>

            <div class="container position-relative" style="margin-top: 10px;">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <!-- Property Quick Info -->
                        <div class="row mb-4">
                            <?php if ($coordinates): ?>
                                <div class="col-md-3">
                                    <div class="property-info-item">
                                        <i class="fas text-primary"></i>
                                        <span class="ms-2">Розташування: <?php echo esc_html($coordinates); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($floors): ?>
                                <div class="col-md-3">
                                    <div class="property-info-item">
                                        <i class="fas text-primary"></i>
                                        <span class="ms-2">Кількість поверхів: <?php echo esc_html($floors); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($building_type): ?>
                                <div class="col-md-3">
                                    <div class="property-info-item">
                                        <i class="fas text-primary"></i>
                                        <span class="ms-2">Тип будівлі: <?php echo esc_html($building_type_label); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($eco_rating): ?>
                                <div class="col-md-3">
                                    <div class="property-info-item">
                                        <i class="fas text-primary"></i>
                                        <span class="ms-2">Екологічність:
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $eco_rating ? '★' : '☆';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Property Details Section -->
        <section class="property-details py-5">
            <div class="container-fluid px-4">
                <?php if (have_rows('apartments')): ?>
                    <h2 class="section-title mb-5 text-center">Опис приміщень</h2>

                    <!-- Enhanced Swiper Gallery -->
                    <div class="enhanced-swiper-container mb-5">
                        <div class="swiper-main position-relative">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <?php
                                    $gallery_images = get_field('swiper_gallery');
                                    if ($gallery_images):
                                        foreach ($gallery_images as $image): ?>
                                            <div class="swiper-slide">
                                                <img src="<?php echo esc_url($image['url']); ?>"
                                                    alt="<?php echo esc_attr($image['alt']); ?>" class="w-100 swiper-image">
                                            </div>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </div>

                                <!-- Custom Navigation Buttons -->
                                <div class="swiper-button-prev custom-swiper-button">
                                    <div class="navigation-circle">
                                        <i class="fas"></i>
                                    </div>
                                </div>
                                <div class="swiper-button-next custom-swiper-button">
                                    <div class="navigation-circle">
                                        <i class="fas"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Cards Grid -->
                    <div class="property-cards-grid">
                        <div class="row g-4">
                            <?php while (have_rows('apartments')):
                                the_row();
                                $area = get_sub_field('area') ?? '';
                                $rooms = get_sub_field('rooms') ?? '';
                                $balcony = get_sub_field('balcony') ?? '';
                                $bathroom = get_sub_field('bathroom') ?? '';
                                ?>
                                <!-- Area Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="property-info-card h-100">
                                        <div class="card shadow-sm rounded-lg border-0">
                                            <div class="card-body d-flex flex-column">
                                                <div class="feature-icon mb-3">
                                                    <i class="fas fa-2x text-primary"></i>
                                                </div>
                                                <h5 class="card-title mb-3">Площа</h5>
                                                <p class="card-text fs-4 fw-bold mb-0"><?php echo esc_html($area); ?> м²</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rooms Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="property-info-card h-100">
                                        <div class="card shadow-sm rounded-lg border-0">
                                            <div class="card-body d-flex flex-column">
                                                <div class="feature-icon mb-3">
                                                    <i class="fas fa-2x text-primary"></i>
                                                </div>
                                                <h5 class="card-title mb-3">Кімнати</h5>
                                                <p class="card-text fs-4 fw-bold mb-0"><?php echo esc_html($rooms); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Balcony Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="property-info-card h-100">
                                        <div class="card shadow-sm rounded-lg border-0">
                                            <div class="card-body d-flex flex-column">
                                                <div class="feature-icon mb-3">
                                                    <i class="fas fa-2x text-primary"></i>
                                                </div>
                                                <h5 class="card-title mb-3">Балкон</h5>
                                                <p class="card-text fs-4 fw-bold mb-0"><?php echo $balcony ? 'Так' : 'Ні'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bathroom Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="property-info-card h-100">
                                        <div class="card shadow-sm rounded-lg border-0">
                                            <div class="card-body d-flex flex-column">
                                                <div class="feature-icon mb-3">
                                                    <i class="fas fa-2x text-primary"></i>
                                                </div>
                                                <h5 class="card-title mb-3">Санвузол</h5>
                                                <p class="card-text fs-4 fw-bold mb-0"><?php echo $bathroom ? 'Так' : 'Ні'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Swiper Dependencies -->
                    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
                    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

                    <style>
                        /* Enhanced Swiper Styles */
                        .enhanced-swiper-container {
                            margin: 0 auto;
                            max-width: 1400px;
                        }

                        .swiper-main {
                            background: #f8f9fa;
                            border-radius: 15px;
                            overflow: hidden;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                        }

                        .swiper-image {
                            height: 600px;
                            object-fit: cover;
                        }

                        /* Custom Navigation Buttons */
                        .navigation-circle {
                            width: 50px;
                            height: 50px;
                            background: rgba(255, 255, 255, 0.9);
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                            transition: all 0.3s ease;
                        }

                        .custom-swiper-button {
                            width: 50px;
                            height: 50px;
                        }

                        .swiper-button-next:after,
                        .swiper-button-prev:after {
                            display: none;
                        }

                        .navigation-circle:hover {
                            background: rgba(255, 255, 255, 1);
                            transform: scale(1.1);
                        }

                        /* Property Info Cards */
                        .property-info-card .card {
                            transition: transform 0.3s ease;
                            background: #ffffff;
                        }

                        .property-info-card .card:hover {
                            transform: translateY(-5px);
                        }

                        .feature-icon {
                            color: var(--bs-primary);
                            text-align: center;
                        }

                        .property-cards-grid {
                            max-width: 1400px;
                            margin: 0 auto;
                        }

                        .card-title {
                            color: #333;
                            font-weight: 600;
                        }

                        .card-text {
                            color: var(--bs-primary);
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            new Swiper('.swiper-container', {
                                navigation: {
                                    nextEl: '.swiper-button-next',
                                    prevEl: '.swiper-button-prev',
                                },
                                loop: true,
                                slidesPerView: 1,
                                speed: 800,
                                effect: 'fade',
                                fadeEffect: {
                                    crossFade: true
                                },
                                autoplay: {
                                    delay: 5000,
                                    disableOnInteraction: false,
                                }
                            });
                        });
                    </script>
                <?php endif; ?>
            </div>
        </section>

        <!-- Additional Property Content -->
        <section class="property-content py-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="section-title mb-4">Детальний опис</h2>
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Contact Form or Additional Info -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 mb-4">Зв'язатися з нами</h3>
                                <?php
                                if (shortcode_exists('contact-form-7')) {
                                    echo do_shortcode('[contact-form-7 id="contact-form"]');
                                } else {
                                    echo '<p>Contact form plugin is not installed.</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
endwhile; // End of the loop.

get_footer();