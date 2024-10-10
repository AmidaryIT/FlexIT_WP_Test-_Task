<?php
/**
 * Plugin Name: Real Estate Catalog
 * Version: 1.0.0
 * Author: Danylo
 */

if (!defined('ABSPATH')) {
    exit;
}

class Real_Estate_Plugin
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->define_constants();
        $this->init_hooks();
        $this->include_files();
    }

    private function define_constants()
    {
        define('REAL_ESTATE_VERSION', '1.0.0');
        define('REAL_ESTATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('REAL_ESTATE_PLUGIN_URL', plugin_dir_url(__FILE__));
    }

    private function init_hooks()
    {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomy'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('widgets_init', array($this, 'register_shortcodes_and_widgets'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    private function include_files()
    {
        require_once REAL_ESTATE_PLUGIN_DIR . 'includes/ajax-handlers.php';
        require_once REAL_ESTATE_PLUGIN_DIR . 'includes/shortcode-widget.php';
    }

    public function register_post_type()
    {
        $labels = array(
            'name' => 'Об\'єкти нерухомості',
            'singular_name' => 'Об\'єкти нерухомості',
            'menu_name' => 'Нерухомість',
            'add_new' => 'Додати новий',
            'add_new_item' => 'Додати новий об\'єкт',
            'edit_item' => 'Редагувати об\'єкт',
            'new_item' => 'Новий об\'єкт',
            'view_item' => 'Переглянути об\'єкт',
            'search_items' => 'Шукати об\'єкти',
            'not_found' => 'Об\'єктів не знайдено',
            'not_found_in_trash' => 'У кошику немає об\'єктів'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'property'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-building',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
        );

        register_post_type('property', $args);
    }

    public function register_taxonomy()
    {
        $labels = array(
            'name' => 'Райони',
            'singular_name' => 'Район',
            'search_items' => 'Шукати райони',
            'all_items' => 'Усі райони',
            'parent_item' => 'Батьківський район',
            'parent_item_colon' => 'Батьківський район:',
            'edit_item' => 'Редагувати район',
            'update_item' => 'Оновити район',
            'add_new_item' => 'Додати новий район',
            'new_item_name' => 'Назва нового району',
            'menu_name' => 'Райони'
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'district')
        );

        register_taxonomy('district', array('property'), $args);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'real-estate-catalog',
            REAL_ESTATE_PLUGIN_URL . '/js/real-estate-catalog.js',
            array('jquery'),
            REAL_ESTATE_VERSION,
            true
        );

        wp_localize_script('real-estate-catalog', 'realEstateAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('real_estate_filter_nonce')
        ));
    }

    public function admin_enqueue_scripts($hook)
    {
        if ('post.php' === $hook || 'post-new.php' === $hook) {
            wp_enqueue_style(
                'real-estate-admin',
                REAL_ESTATE_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                REAL_ESTATE_VERSION
            );
        }
    }

    public function activate()
    {
        $districts = array('Центральный', 'Северный', 'Южный', 'Восточный', 'Западный');
        foreach ($districts as $district) {
            if (!term_exists($district, 'district')) {
                wp_insert_term($district, 'district');
            }
        }

        flush_rewrite_rules();
    }

    public function deactivate()
    {
        flush_rewrite_rules();
    }

    public function register_shortcodes_and_widgets()
    {
        require_once REAL_ESTATE_PLUGIN_DIR . 'includes/shortcode-widget.php';
        add_shortcode('property_filter', 'rec_display_filter');
        register_widget('Real_Estate_Filter_Widget');
    }
}

// Initialize the plugin
Real_Estate_Plugin::get_instance();