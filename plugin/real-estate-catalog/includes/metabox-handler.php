<?php


if (!defined('ABSPATH')) {
    exit;
}

class Real_Estate_Metabox
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_property_info_metabox'));
        add_action('manage_property_posts_columns', array($this, 'add_property_columns'));
        add_action('manage_property_posts_custom_column', array($this, 'manage_property_columns'), 10, 2);
    }

    /**
     * Добавляем метабокс с информацией о недвижимости
     */
    public function add_property_info_metabox()
    {
        add_meta_box(
            'property_info_metabox',
            'Информация об объекте',
            array($this, 'render_property_info_metabox'),
            'property',
            'side',
            'high'
        );
    }

    /**
     * Рендерим содержимое метабокса
     */
    public function render_property_info_metabox($post)
    {
        $house_name = get_field('house_name', $post->ID);
        $coordinates = get_field('coordinates', $post->ID);
        $floors = get_field('floors', $post->ID);
        $building_type = get_field('building_type', $post->ID);
        $eco_rating = get_field('eco_rating', $post->ID);
        $premises = get_field('premises', $post->ID);

        ?>
        <div class="real-estate-info-metabox">
            <p><strong>Назва:</strong> <?php echo esc_html($house_name); ?></p>
            <p><strong>Координати:</strong> <?php echo esc_html($coordinates); ?></p>
            <p><strong>Поверхів:</strong> <?php echo esc_html($floors); ?></p>
            <p><strong>Тип будівлі:</strong> <?php echo esc_html($building_type); ?></p>
            <p><strong>Екологічність:</strong> <?php echo str_repeat('★', $eco_rating); ?></p>

            <?php if ($premises): ?>
                <h4>Приміщення:</h4>
                <ul>
                    <?php foreach ($premises as $premise): ?>
                        <li>
                            <?php echo sprintf(
                                '%d м² - %d комн.%s%s',
                                $premise['area'],
                                $premise['rooms'],
                                $premise['balcony'] ? ' + балкон' : '',
                                $premise['bathroom'] ? ' + санвузел' : ''
                            ); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Добавляем колонки в список объектов
     */
    public function add_property_columns($columns)
    {
        $new_columns = array();
        foreach ($columns as $key => $value) {
            if ($key == 'title') {
                $new_columns['property_preview'] = 'Превью';
            }
            $new_columns[$key] = $value;
        }
        $new_columns['property_type'] = 'Тип будівлі';
        $new_columns['property_floors'] = 'Поверхів';
        $new_columns['property_premises'] = 'Приміщень';
        return $new_columns;
    }

    public function manage_property_columns($column, $post_id)
    {
        switch ($column) {
            case 'property_preview':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, array(80, 80), array('class' => 'property-preview-thumbnail'));
                }
                break;

            case 'property_type':
                $building_type = get_field('building_type', $post_id);
                echo esc_html($building_type);
                break;

            case 'property_floors':
                $floors = get_field('floors', $post_id);
                echo esc_html($floors);
                break;

            case 'property_premises':
                $premises = get_field('premises', $post_id);
                echo is_array($premises) ? count($premises) : '0';
                break;
        }
    }
}

new Real_Estate_Metabox();