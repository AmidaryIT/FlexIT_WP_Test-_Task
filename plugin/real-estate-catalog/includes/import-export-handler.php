<?php

if (!defined('ABSPATH')) {
    exit;
}

class Real_Estate_Import_Export
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_import_export_page'));
        add_action('admin_post_export_properties', array($this, 'handle_export'));
        add_action('admin_post_import_properties', array($this, 'handle_import'));
    }

    /**
     * Добавляем страницу импорта/экспорта
     */
    public function add_import_export_page()
    {
        add_submenu_page(
            'edit.php?post_type=property',
            'Импорт/Экспорт',
            'Импорт/Экспорт',
            'manage_options',
            'property-import-export',
            array($this, 'render_import_export_page')
        );
    }

    /**
     * Рендерим страницу импорта/экспорта
     */
    public function render_import_export_page()
    {
        ?>
        <div class="wrap">
            <h1>Імпорт/Експорт об'єктів нерухомості</h1>

            <div class="card">
                <h2>Експорт</h2>
                <p>Експортувати всі об'єкти нерухомості в CSV файл.</p>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <?php wp_nonce_field('export_properties_nonce', 'export_nonce'); ?>
                    <input type="hidden" name="action" value="export_properties">
                    <button type="submit" class="button button-primary">Експортувати</button>
                </form>
            </div>

            <div class="card">
                <h2>Імпорт</h2>
                <p>Імпортувати об'єкти нерухомості з CSV файлу.</p>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                    <?php wp_nonce_field('import_properties_nonce', 'import_nonce'); ?>
                    <input type="hidden" name="action" value="import_properties">
                    <input type="file" name="import_file" accept=".csv" required>
                    <button type="submit" class="button button-primary">Імпортувати</button>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Обработка экспорта
     */
    public function handle_export()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Недостатньо прав');
        }

        check_admin_referer('export_properties_nonce', 'export_nonce');

        $args = array(
            'post_type' => 'property',
            'posts_per_page' => -1
        );

        $properties = get_posts($args);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=properties-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // Записываем заголовки
        fputcsv($output, array(
            'ID',
            'Назва',
            'Координати',
            'Поверхів',
            'Тип будівлі',
            'Екологічність',
            'Район',
            'Приміщення'
        ));

        // Записываем данные
        foreach ($properties as $property) {
            $districts = wp_get_post_terms($property->ID, 'district', array('fields' => 'names'));
            $premises = get_field('premises', $property->ID);
            $premises_json = $premises ? json_encode($premises, JSON_UNESCAPED_UNICODE) : '';

            fputcsv($output, array(
                $property->ID,
                get_field('house_name', $property->ID),
                get_field('coordinates', $property->ID),
                get_field('floors', $property->ID),
                get_field('building_type', $property->ID),
                get_field('eco_rating', $property->ID),
                implode(', ', $districts),
                $premises_json
            ));
        }

        fclose($output);
        exit;
    }

    /**
     * Обработка импорта
     */
    public function handle_import()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Недостатньо прав');
        }

        check_admin_referer('import_properties_nonce', 'import_nonce');

        if (!isset($_FILES['import_file'])) {
            wp_die('Файл не завантажений');
        }

        $file = $_FILES['import_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            wp_die('Помилка завантаження файлу');
        }

        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            wp_die('Не вдалося відкрити файл');
        }

        // Пропускаем заголовки
        fgetcsv($handle);

        // Импортируем данные
        while (($data = fgetcsv($handle)) !== false) {
            $property_data = array(
                'post_type' => 'property',
                'post_title' => $data[1],
                'post_status' => 'publish'
            );

            $property_id = wp_insert_post($property_data);

            if (!is_wp_error($property_id)) {
                update_field('house_name', $data[1], $property_id);
                update_field('coordinates', $data[2], $property_id);
                update_field('floors', $data[3], $property_id);
                update_field('building_type', $data[4], $property_id);
                update_field('eco_rating', $data[5], $property_id);

                // Обрабатываем районы
                $districts = array_map('trim', explode(',', $data[6]));
                wp_set_object_terms($property_id, $districts, 'district');

                // Обрабатываем помещения
                $premises = json_decode($data[7], true);
                if ($premises) {
                    update_field('premises', $premises, $property_id);
                }
            }
        }

        fclose($handle);

        wp_redirect(admin_url('edit.php?post_type=property&page=property-import-export&imported=1'));
        exit;
    }
}

new Real_Estate_Import_Export();