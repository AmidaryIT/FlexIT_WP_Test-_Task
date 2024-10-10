<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$bootstrap_version = get_theme_mod('understrap_bootstrap_version', 'bootstrap4');
$navbar_type = get_theme_mod('understrap_navbar_type', 'collapse');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php do_action('wp_body_open'); ?>

    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg" style="background-color: #003366;">
            <div class="container">
                <!-- Логотип -->
                <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php if (get_theme_mod('header_logo')): ?>
                        <img src="<?php echo esc_url(get_theme_mod('header_logo')); ?>" alt="<?php bloginfo('name'); ?>"
                            class="d-inline-block align-top" style="height: 50px;">
                    <?php else: ?>
                        <img src="/path-to-default-logo.png" alt="<?php bloginfo('name'); ?>"
                            class="d-inline-block align-top" style="height: 50px;">
                    <?php endif; ?>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo esc_url(home_url('/')); ?>">Головна</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo site_url('/catalog/'); ?>">Каталог</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Про нас</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Контакти</a>
                        </li>
                    </ul>
                    <form class="d-flex ms-3">
                        <input class="form-control" type="search" placeholder="Пошук" aria-label="search">
                        <button class="btn btn-light ms-2" type="submit">Пошук</button>
                    </form>
                </div>
            </div>
        </nav>
<script>document.addEventListener("scroll", function() {
    const header = document.querySelector("header");
    const sticky = header.offsetTop;

    if (window.pageYOffset > sticky) {
        header.classList.add("fixed");
    } else {
        header.classList.remove("fixed");
    }
});</script>
    </header>