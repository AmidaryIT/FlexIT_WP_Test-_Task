<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$container = get_theme_mod('understrap_container_type');
?>

<?php get_template_part('sidebar-templates/sidebar', 'footerfull'); ?>

<footer class="footer mt-auto py-4" style="background-color: #003366;">
    <div class="container">
        <div class="row">
            <!-- About Us Section -->
            <div class="col-md-4 text-white">
                <h5>About Us</h5>
                <p>We offer a variety of real estate listings and services to help you find your dream property.</p>
            </div>
            <!-- Contact Information Section -->
            <div class="col-md-4 text-white">
                <h5>Contact Information</h5>
                <ul class="list-unstyled">
                    <li><i class="fas"></i> +1 (123) 456-7890</li>
                    <li><i class="fas"></i> info@example.com</li>
                    <li><i class="fas"></i> 123 Real Estate St, City, Country</li>
                </ul>
            </div>
            <!-- Social Media Section -->
            <div class="col-md-4 text-white text-md-end">
                <h5>Follow Us</h5>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook" class="me-3" style="text-decoration: none;">
                        <svg width="24" height="24" fill="white" class="social-icon"><use xlink:href="#facebook-icon"></use></svg>
                    </a>
                    <a href="#" aria-label="Twitter" class="me-3" style="text-decoration: none;">
                        <svg width="24" height="24" fill="white" class="social-icon"><use xlink:href="#twitter-icon"></use></svg>
                    </a>
                    <a href="#" aria-label="Instagram" class="me-3" style="text-decoration: none;">
                        <svg width="24" height="24" fill="white" class="social-icon"><use xlink:href="#instagram-icon"></use></svg>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="me-3" style="text-decoration: none;">
                        <svg width="24" height="24" fill="white" class="social-icon"><use xlink:href="#linkedin-icon"></use></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- SVG Icons for Social Media -->
<svg style="display: none;">
    <symbol id="facebook-icon" viewBox="0 0 24 24">
        <path d="M20.9,2H3.1A1.1,1.1,0,0,0,2,3.1V20.9A1.1,1.1,0,0,0,3.1,22h9.58V14.25h-2.6v-3h2.6V9a3.64,3.64,0,0,1,3.88-4,20.26,20.26,0,0,1,2.33.12v2.7H17.3c-1.26,0-1.5.6-1.5,1.47v1.93h3l-.39,3H15.8V22h5.1A1.1,1.1,0,0,0,22,20.9V3.1A1.1,1.1,0,0,0,20.9,2Z"></path>
    </symbol>
    <symbol id="twitter-icon" viewBox="0 0 24 24">
        <path d="M3 18.6443C7.56435 20.8078 17.1652 21.9701 19.0539 9.31123C19.715 9.01907 20.7994 8.21494 21 6.02928C20.3704 6.47935 19.1531 6.67711 17.9443 6.56341C15.9533 5.52114 11.9241 4.80102 11.7352 10.2587C9.61043 9.97449 4.99257 8.52478 3.51939 5C2.85835 7.55831 2.74503 13.4235 7.58009 16.4176C7.36761 16.7809 6.57908 17.5594 5.12478 17.7679"></path>
    </symbol>
    <symbol id="instagram-icon" viewBox="0 0 24 24">
        <path d="M8,2 L16,2 C19.3137085,2 22,4.6862915 22,8 L22,16 C22,19.3137085 19.3137085,22 16,22 L8,22 C4.6862915,22 2,19.3137085 2,16 L2,8 C2,4.6862915 4.6862915,2 8,2 Z M8,4 C5.790861,4 4,5.790861 4,8 L4,16 C4,18.209139 5.790861,20 8,20 L16,20 C18.209139,20 20,18.209139 20,16 L20,8 C20,5.790861 18.209139,4 16,4 L8,4 Z M12,17 C9.23857625,17 7,14.7614237 7,12 C7,9.23857625 9.23857625,7 12,7 C14.7614237,7 17,9.23857625 17,12 C17,14.7614237 14.7614237,17 12,17 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z M17,8 C16.4477153,8 16,7.55228475 16,7 C16,6.44771525 16.4477153,6 17,6 C17.5522847,6 18,6.44771525 18,7 C18,7.55228475 17.5522847,8 17,8 Z"></path>
    </symbol>
    <symbol id="linkedin-icon" viewBox="0 0 24 24">
        <path d="M19.959 11.719v7.379h-4.278v-6.885c0-1.73-.619-2.91-2.167-2.91-1.182 0-1.886.796-2.195 1.565-.113.275-.142.658-.142 1.043v7.187h-4.28s.058-11.66 0-12.869h4.28v1.824l-.028.042h.028v-.042c.568-.875 1.583-2.126 3.856-2.126 2.815 0 4.926 1.84 4.926 5.792zM2.421.026C.958.026 0 .986 0 2.249c0 1.235.93 2.224 2.365 2.224h.028c1.493 0 2.42-.989 2.42-2.224C4.787.986 3.887.026 2.422.026zM.254 19.098h4.278V6.229H.254v12.869z"></path>
    </symbol>
</svg>

<?php // Closing div#page from header.php. ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
