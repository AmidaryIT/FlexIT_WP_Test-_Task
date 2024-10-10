<?php

// Функция для вывода формы фильтра
function rec_display_filter()
{
    ob_start();
    ?>
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
    <div class="container" id="property-list">

    </div>
    <?php
    return ob_get_clean();
}

class Real_Estate_Filter_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'real_estate_filter_widget',
            __('Real Estate Filter', 'real-estate-catalog'),
            array('description' => __('Filter for real estate objects', 'real-estate-catalog'))
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo do_shortcode('[property_filter]');
        echo $args['after_widget'];
    }
}
