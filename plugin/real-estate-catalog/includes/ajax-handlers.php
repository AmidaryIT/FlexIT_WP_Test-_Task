<?php
function real_estate_filter_properties()
{
    check_ajax_referer('real_estate_filter_nonce', 'nonce');

    $args = array(
        'post_type' => 'property',
        'posts_per_page' => -1,
        'meta_query' => array(),
        'tax_query' => array()
    );

    if (!empty($_POST['house_name'])) {
        $args['s'] = sanitize_text_field($_POST['house_name']);
    }

    if (!empty($_POST['district'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'district',
            'field' => 'term_id',
            'terms' => intval($_POST['district']),
        );
    }

    if (!empty($_POST['floors'])) {
        $args['meta_query'][] = array(
            'key' => 'floors',
            'value' => intval($_POST['floors']),
            'compare' => '='
        );
    }

    if (!empty($_POST['building_type'])) {
        $args['meta_query'][] = array(
            'key' => 'building_type',
            'value' => sanitize_text_field($_POST['building_type']),
            'compare' => '='
        );
    }

    if (!empty($_POST['eco_rating'])) {
        $args['meta_query'][] = array(
            'key' => 'eco_rating',
            'value' => intval($_POST['eco_rating']),
            'compare' => '='
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output = '<div class="property-card mb-4 p-3 border rounded">';
            $output .= '<h3 class="property-title">' . get_the_title() . '</h3>';
            $output .= '<div class="property-content">' . get_the_content() . '</div>';
            $output .= '</div>';
            echo $output;
        }
    } else {
        echo '<p>Немає результатів для вибраних фільтрів.</p>';
    }

    wp_die();
}

add_action('wp_ajax_real_estate_filter_properties', 'real_estate_filter_properties');
add_action('wp_ajax_nopriv_real_estate_filter_properties', 'real_estate_filter_properties');
