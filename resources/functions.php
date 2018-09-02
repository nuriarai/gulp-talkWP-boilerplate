<?php

/**
 * wcbcn2018 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wcbcn2018
 */

if (!function_exists('wcbcn2018_setup')) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wcbcn2018_setup()
{
		/*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on wcbcn2018, use a find and replace
     * to change 'wcbcn2018' to the name of your theme in all the template files.
     */
    load_theme_textdomain('wcbcn2018', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

		/*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

		/*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'menu-1' => esc_html__('Primary', 'wcbcn2018'),
    ));

		/*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

		// Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('wcbcn2018_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

		// Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support('custom-logo', array(
        'height' => 250,
        'width' => 250,
        'flex-width' => true,
        'flex-height' => true,
    ));

    $dist_dir = get_theme_root_uri() . "/" . wp_get_theme() . "/dist";
    $domain_name = wp_get_theme(); //default to theme's name (style.css), change with your themes name
    $domain_name = 'xerradavcbcn2018';
    /* environement */
    // Set your environment/url pairs
    $environments = array(
        'local' => 'local.' . $domain_name . '.com',
        'staging' => 'staging.' . $domain_name . '.com',
        'production' => $domain_name . '.com'
    );

    // Get the hostname
    $http_host = $_SERVER['HTTP_HOST'];
    // Loop through $environments to see if there’s a match
    foreach ($environments as $environment => $hostname) {
        //echo $hostname . " wwwww";
        if (stripos($http_host, $hostname) !== false) {
            define('ENVIRONMENT', $environment);
            break;
        }
    }

    if (ENVIRONMENT == 'local') {
        define('ASSETSDIR', get_template_directory_uri() . '/assets');
    } else {
        define('ASSETSDIR', $dist_dir . '/assets');
    }
}
endif;
add_action('after_setup_theme', 'wcbcn2018_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wcbcn2018_content_width()
{
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('wcbcn2018_content_width', 640);
}
add_action('after_setup_theme', 'wcbcn2018_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wcbcn2018_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'wcbcn2018'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'wcbcn2018'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'wcbcn2018_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function wcbcn2018_scripts()
{
    wp_enqueue_style('wcbcn2018-style', get_stylesheet_uri());

    if (ENVIRONMENT == 'local') {
        wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.css');
    } else {
        wp_enqueue_style('main', ASSETSDIR . '/css/main.min.css');
    }

    if (ENVIRONMENT == 'local') {
        wp_enqueue_script('wcbcn2018-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20151215', true);

        wp_enqueue_script('wcbcn2018-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true);

        wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main.js', array(), '20151215', true);

    } else {
        wp_enqueue_script('main', ASSETSDIR . '/js/main.min.js', array(), '20151215', true);
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'wcbcn2018_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

