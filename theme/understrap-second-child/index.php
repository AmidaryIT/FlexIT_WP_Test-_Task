<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

get_header();
$container = get_theme_mod('understrap_container_type');
?>

<div class="main-section-wrapper">
    <section class="main-section p-4" style="background-color: #194775;">
        <div class="main-section-overlay"></div>
        <div class="<?php echo esc_attr($container); ?>">
            <!-- Hero Content -->
            <div class="hero-content text-center text-white">
                <h1 class="section-title">Знайди житло своєї мрії</h1>
                <p class="hero-subtitle">Довірте пошук професіоналам з багаторічним досвідом</p>
                <a href="<?php echo site_url('/catalog/'); ?>" class="btn btn-primary">Переглянути пропозиції</a>
            </div>
        </div>
    </section>
    <!-- Advantages Grid -->
    <div class="advantages-grid mt-4">
        <h2 class="text-center">Чому обирають саме нас</h2>
        <div class="row g-4">
            <?php 
            $advantages = [
                ['title' => 'Безпека угоди', 'description' => 'Гарантуємо юридичну чистоту та повний супровід кожної угоди'],
                ['title' => 'Швидкий пошук', 'description' => 'Підбір найкращих варіантів під ваші критерії за 24 години'],
                ['title' => 'Надійність', 'description' => '10+ років досвіду та більше 1000 задоволених клієнтів'],
                ['title' => 'Індивідуальний підхід', 'description' => 'Розуміємо потреби кожного клієнта та пропонуємо оптимальні рішення'],
                ['title' => 'Прозорість співпраці', 'description' => 'Чітке дотримання домовленостей та повна звітність'],
                ['title' => 'Професійна команда', 'description' => 'З досвідом у різних сферах нерухомості']
            ];
            foreach ($advantages as $advantage) : ?>
                <div class="col-md-4">
                    <div class="advantage-card text-center">
                        <svg class="advantage-icon" style="width: 30px; height: 30px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="advantage-title"><?php echo $advantage['title']; ?></h3>
                        <p class="advantage-description"><?php echo $advantage['description']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<div class="widget-area">
            <?php the_widget('Real_Estate_Filter_Widget'); ?>
        </div>

    <!-- Testimonials Gallery -->
<div class="testimonials-gallery mt-5" style="background-color: #194775; color: white;">
    <h2 class="text-center">Відгуки наших клієнтів</h2>
    <div class="row g-4">
        <?php 
        if (have_rows('testimonial_images')) :
            while (have_rows('testimonial_images')) : the_row(); 
                $image = get_sub_field('image');
                $text = get_sub_field('text');
                $name = get_sub_field('name');
                ?>
                <div class="col-md-4">
                    <div class="testimonial-card text-center">
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($name); ?>" class="testimonial-image rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <p class="testimonial-text"><?php echo esc_html($text); ?></p>
                        <h5 class="testimonial-name"><?php echo esc_html($name); ?></h5>
                    </div>
                </div>
            <?php endwhile; 
        else : ?>
            <p class="text-center text-white">Відгуків поки що немає.</p>
        <?php endif; ?>
    </div>
</div>




<?php
get_footer();
