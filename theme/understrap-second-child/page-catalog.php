<?php
/* Template Name: Catalog

 * The template for displaying Catalog pages
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

defined('ABSPATH') || exit;
get_header(); ?>

<div class="catalog-wrapper">
    <!-- Hero Section -->
    <section class="hero-section"
        style="padding:2rem; padding-top: 8rem; background: linear-gradient(rgba(0, 51, 102, 0.9), rgba(0, 51, 102, 0.9)), url('<?php echo get_theme_file_uri('assets/images/real-estate-bg.jpg'); ?>') center/cover;">
        <div class="container">
            <h1 class="text-center text-white mb-4">Каталог нерухомості</h1>
            <p class="text-center text-white mb-5">Знайдіть ідеальну нерухомість для себе</p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section py-4 bg-light">
        <div class="container">
            <div class="filter-card bg-white shadow-sm rounded p-4">
                <form id="property-filter-form" class="row g-3"
                    data-nonce="<?php echo wp_create_nonce('real_estate_filter_nonce'); ?>">
                    <div class="col-md-4">
                        <label for="house_name" class="form-label fw-bold">Назва будинку</label>
                        <input type="text" class="form-control form-control-lg" id="house_name" name="house_name">
                    </div>

                    <div class="col-md-4">
                        <label for="district" class="form-label fw-bold">Район</label>
                        <?php
                        $districts = get_terms(array(
                            'taxonomy' => 'district',
                            'hide_empty' => false,
                        ));
                        if (!empty($districts)): ?>
                            <select class="form-select form-select-lg" id="district" name="district">
                                <option value="">Всі райони</option>
                                <?php foreach ($districts as $district): ?>
                                    <option value="<?php echo esc_attr($district->term_id); ?>">
                                        <?php echo esc_html($district->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <label for="floors" class="form-label fw-bold">Поверховість</label>
                        <select class="form-select form-select-lg" id="floors" name="floors">
                            <option value="">Будь-яка</option>
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Тип будівлі</label>
                        <div class="building-type-wrapper d-flex gap-3">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="type_panel" name="building_type"
                                    value="Панель">
                                <label class="form-check-label" for="type_panel">Панель</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="type_brick" name="building_type"
                                    value="Цегла">
                                <label class="form-check-label" for="type_brick">Цегла</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="type_foam" name="building_type"
                                    value="Піноблок">
                                <label class="form-check-label" for="type_foam">Піноблок</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="eco_rating" class="form-label fw-bold">Екологічність</label>
                        <select class="form-select form-select-lg" id="eco_rating" name="eco_rating">
                            <option value="">Будь-яка</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>">
                                    <?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas me-2"></i>Знайти
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<div class="container" id="property-list"></div>
</div>

<script>
    jQuery(document).ready(function ($) {
        const $form = $('#property-filter-form');
        const $propertyList = $('#property-list');
        const $propertyGallery = $('.property_gallery');

        // Функция скрытия галереи и сброса списка карточек
        const hideGalleryAndClearList = function () {
            $propertyGallery.hide(); // Скрыть галерею
            $propertyList.html(''); // Очистить список объектов
        };

        // Функция фильтрации объектов недвижимости
        const submitFilter = function (e) {
            e.preventDefault();
            hideGalleryAndClearList(); // Скрыть галерею и очистить список перед фильтрацией

            const filterData = {
                action: 'filter_properties',
                nonce: $form.data('nonce'),
                house_name: $('#house_name').val(),
                district: $('#district').val(),
                floors: $('#floors').val(),
                building_type: $('input[name="building_type"]:checked').val(),
                eco_rating: $('#eco_rating').val()
            };

            // Индикатор загрузки
            $propertyList.html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Завантаження...</span></div></div>');

            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: filterData,
                success: function (response) {
                    if (response.success) {
                        $propertyList.html(response.data);
                    } else {
                        $propertyList.html('<div>Об\'єкти не знайдено</div>');
                    }
                },
                error: function () {
                    $propertyList.html('<div>Виникла помилка при завантаженні об\'єктів</div>');
                }
            });
        };

        // Привязка функции фильтрации к событию submit формы
        $form.on('submit', submitFilter);

        // Дополнительные обработчики событий для скрытия галереи при изменении параметров
        $form.find('input, select').on('change', function () {
            hideGalleryAndClearList();
        });
    });

</script>


<section class="property_gallery">
    <div class="container" id="property-list">
        <?php
        // Начальная загрузка объектов недвижимости
        $args = array(
            'post_type' => 'property',
            'posts_per_page' => 10,
        );
        $properties = new WP_Query($args);

        if ($properties->have_posts()):
            echo '<div class="row" style="padding-left: 40px; padding-right: 40px;">';
            while ($properties->have_posts()):
                $properties->the_post();
                $property_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                $property_location = get_post_meta(get_the_ID(), 'coordinates', true);
                $property_floors = get_post_meta(get_the_ID(), 'floors', true);
                $property_eco = get_post_meta(get_the_ID(), 'eco_rating', true);
                $building_type = get_post_meta(get_the_ID(), 'building_type', true);
                ?>
                <div class="col-md-4 mb-5">
                    <div class="property-card"
                        style="border: 1px solid #ccc; border-radius: 8px; overflow: hidden; transition: transform 0.3s; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div class="property-image-wrapper" style="position: relative; height: 200px; overflow: hidden;">
                            <img src="<?php echo esc_url($property_image); ?>" class="property-image"
                                alt="<?php the_title(); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="property-badge"
                                style="position: absolute; top: 10px; left: 10px; background: rgba(255, 255, 255, 0.8); padding: 5px; border-radius: 5px; font-size: 0.85rem;">
                                <span class="property-type"
                                    style="font-weight: bold;"><?php echo esc_html($building_type); ?></span>
                            </div>
                        </div>
                        <div class="property-details" style="padding: 15px; text-align: left;">
                            <h3 class="property-title" style="font-size: 1.25rem; margin-bottom: 10px;"><?php the_title(); ?>
                            </h3>
                            <div class="property-info">
                                <div class="info-item" style="margin-bottom: 5px;">
                                    <span class="property-location">Район: <?php echo esc_html($property_location); ?></span>
                                </div>
                                <div class="info-item" style="margin-bottom: 5px;">
                                    <span class="property-floors">Кількість поверхів:
                                        <?php echo esc_html($property_floors); ?></span>
                                </div>
                                <div class="info-item" style="margin-bottom: 5px;">
                                    <span class="property-eco">Екологічність <?php echo esc_html($property_eco); ?> ★</span>
                                </div>
                            </div>
                        </div>
                        <div class="property-footer" style="padding: 10px; text-align: center;">
                            <a href="<?php echo get_permalink(); ?>" class="btn btn-primary"
                                style="padding: 8px 12px; font-size: 0.9rem;">Детальніше</a>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
            echo '</div>';
        endif;
        wp_reset_postdata();
        ?>
    </div>
</section>
<?php get_footer(); ?>