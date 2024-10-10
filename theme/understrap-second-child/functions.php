<?php
if (!defined('ABSPATH')) {
    exit;
}

function understrap_child_enqueue_styles()
{
    $theme_version = wp_get_theme()->get('Version');

    // Подключение стилей
    wp_enqueue_style('understrap-styles', get_template_directory_uri() . '/css/theme.min.css', array(), $theme_version);
    wp_enqueue_style('understrap-second-child-styles', get_stylesheet_directory_uri() . '/style.css', array('understrap-styles'), $theme_version);

    // Подключаем jQuery
    wp_enqueue_script('jquery');

    // Подключаем скрипт фильтрации
    wp_enqueue_script(
        'real-estate-catalog',
        plugins_url('/real-estate-catalog/js/real-estate-catalog.js'),
        array('jquery'),
        '1.0',
        true
    );

    // Локализация данных для скрипта
    wp_localize_script('real-estate-catalog', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('real_estate_filter_nonce')
    ));

    // Добавляем проверку загрузки скрипта
    add_action('wp_footer', function () {
        if (wp_script_is('real-estate-catalog', 'done')) {
            echo '<script>console.log("real-estate-catalog.js loaded successfully")</script>';
        }
    }, 999);
}
add_action('wp_enqueue_scripts', 'understrap_child_enqueue_styles');
// Добавление поддержки дополнительных возможностей темы
function understrap_child_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'understrap-child'),
        'footer' => __('Footer Menu', 'understrap-child'),
    ));
}
add_action('after_setup_theme', 'understrap_child_theme_setup');

// Добавление логотипа в настройки темы
function theme_custom_logo($wp_customize)
{
    $wp_customize->add_section('header_section', array(
        'title' => __('Логотип', 'your-theme-textdomain'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('header_logo', array(
        'default' => '',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_logo', array(
        'label' => __('Загрузить логотип', 'your-theme-textdomain'),
        'section' => 'header_section',
        'settings' => 'header_logo',
    )));
}
add_action('customize_register', 'theme_custom_logo');

// Создание кастомного типа записей Property
function create_property_post_type()
{
    register_post_type(
        'property',
        array(
            'labels' => array(
                'name' => __('Properties'),
                'singular_name' => __('Property')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'rewrite' => array('slug' => 'properties')
        )
    );
}
add_action('init', 'create_property_post_type');

// Использование шаблона single-property.php для одиночных записей Property
function use_single_property_template($single_template)
{
    global $post;

    if ($post->post_type == 'property') {
        $single_template = dirname(__FILE__) . '/single-property.php';
    }
    return $single_template;
}
add_filter('single_template', 'use_single_property_template');

// Обработка AJAX-фильтра для объектов недвижимости
function filter_properties()
{
    check_ajax_referer('real_estate_filter_nonce', 'nonce');

    $house_name = isset($_POST['house_name']) ? sanitize_text_field($_POST['house_name']) : '';
    $district = isset($_POST['district']) ? intval($_POST['district']) : 0;
    $floors = isset($_POST['floors']) ? intval($_POST['floors']) : 0;
    $building_type = isset($_POST['building_type']) ? sanitize_text_field($_POST['building_type']) : '';
    $eco_rating = isset($_POST['eco_rating']) ? intval($_POST['eco_rating']) : 0;

    $args = array(
        'post_type' => 'property',
        'posts_per_page' => 10,
        'meta_query' => array('relation' => 'AND'),
    );

    if ($house_name) {
        $args['s'] = $house_name;
    }

    if ($district) {
        $args['tax_query'][] = array(
            'taxonomy' => 'district',
            'field' => 'term_id',
            'terms' => $district,
        );
    }

    if ($floors) {
        $args['meta_query'][] = array(
            'key' => 'floors',
            'value' => $floors,
            'compare' => '=',
        );
    }

    if ($building_type) {
        $args['meta_query'][] = array(
            'key' => 'building_type',
            'value' => $building_type,
            'compare' => '=',
        );
    }

    if ($eco_rating) {
        $args['meta_query'][] = array(
            'key' => 'eco_rating',
            'value' => $eco_rating,
            'compare' => '=',
        );
    }

    $properties = new WP_Query($args);

    if ($properties->have_posts()) {
        ob_start();
        echo '<div class="row" style="padding-left: 40px; padding-right: 40px;">';
        while ($properties->have_posts()) {
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
                        <img src="<?php echo esc_url($property_image); ?>" class="property-image" alt="<?php the_title(); ?>"
                            style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="property-badge"
                            style="position: absolute; top: 10px; left: 10px; background: rgba(255, 255, 255, 0.8); padding: 5px; border-radius: 5px; font-size: 0.85rem;">
                            <span class="property-type" style="font-weight: bold;"><?php echo esc_html($building_type); ?></span>
                        </div>
                    </div>
                    <div class="property-details" style="padding: 15px; text-align: left;">
                        <h3 class="property-title" style="font-size: 1.25rem; margin-bottom: 10px;"><?php the_title(); ?></h3>
                        <div class="property-info">
                            <div class="info-item" style="margin-bottom: 5px;">
                                <span class="property-location">Район: <?php echo esc_html($property_location); ?></span>
                            </div>
                            <div class="info-item" style="margin-bottom: 5px;">
                                <span class="property-floors">Кількість поверхів: <?php echo esc_html($property_floors); ?></span>
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
        }
        echo '</div>';
        $output = ob_get_clean();
        wp_send_json_success($output);
    } else {
        wp_send_json_error('Об\'єкти не знайдено');
    }
    wp_die();
}
add_action('wp_ajax_filter_properties', 'filter_properties');
add_action('wp_ajax_nopriv_filter_properties', 'filter_properties');

