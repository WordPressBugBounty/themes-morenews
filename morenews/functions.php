<?php

/**
 * MoreNews functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MoreNews
 */

/**
 * Define Theme Constants.
 */

defined('ESHF_COMPATIBILITY_TMPL') or define('ESHF_COMPATIBILITY_TMPL', 'morenews');

/**
 * MoreNews functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MoreNews
 */

if (!function_exists('morenews_setup')) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  /**
   *
   */
  /**
   *
   */
  function morenews_setup()
  {
    /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on MoreNews, use a find and replace
         * to change 'morenews' to the name of your theme in all the template files.
         */
    // load_theme_textdomain('morenews', get_template_directory() . '/languages');

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
    add_theme_support('rtl');
    // Add featured image sizes
    add_image_size('morenews-featured', 1024, 0, false); // width, height, crop
    add_image_size('morenews-large', 825, 575, true); // width, height, crop
    add_image_size('morenews-medium', 590, 410, true); // width, height, crop



    /*
         * Enable support for Post Formats on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/post-formats/
         */
    add_theme_support('post-formats', array('image', 'video', 'gallery'));

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
      'aft-primary-nav' => esc_html__('Primary Menu', 'morenews'),
      'aft-social-nav' => esc_html__('Social Menu', 'morenews'),
      'aft-footer-nav' => esc_html__('Footer Menu', 'morenews'),
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
    add_theme_support('custom-background', apply_filters('morenews_custom_background_args', array(
      'default-color' => 'eeeeee',
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
      'flex-width' => true,
      'flex-height' => true,
    ));


    /** 
     * Add theme support for gutenberg block
     */
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('appearance-tools');
    add_theme_support('custom-spacing');
    add_theme_support('custom-units');
    add_theme_support('custom-line-height');
    add_theme_support('border');
    add_theme_support('link-color');
  }
endif;
add_action('after_setup_theme', 'morenews_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function morenews_content_width()
{
  $GLOBALS['content_width'] = apply_filters('morenews_content_width', 640);
}

add_action('after_setup_theme', 'morenews_content_width', 0);
/**
 * Filter font variants to include only necessary ones.
 *
 * @param string $font The font string (e.g., "Lato:400,300,400italic,900,700").
 * @return string Filtered font string with only the allowed variants.
 */
function morenews_filter_font_variants($font)
{
  if (strpos($font, ':') === false) {
    return $font;
  }

  list($font_name, $variants) = explode(':', $font);

  // Define allowed variants to reduce file size and improve performance.
  $allowed_variants = array('400', '700'); // Adjust as needed.
  $font_variants = explode(',', $variants);

  $filtered_variants = array_intersect($font_variants, $allowed_variants);

  return !empty($filtered_variants) ? $font_name . ':' . implode(',', $filtered_variants) : $font_name;
}

/**
 * Generate the Google Fonts URL based on theme options.
 *
 * @return string Google Fonts URL or empty string if no fonts are required.
 */
function morenews_get_fonts_url()
{
  $fonts_url = '';
  $subsets = 'latin'; // Include only the Latin subset by default.
  $theme_fonts = array();

  // Fetch theme options for fonts.
  $site_title_font = morenews_get_option('site_title_font');
  $primary_font = morenews_get_option('primary_font');
  $secondary_font = morenews_get_option('secondary_font');

  // Collect and filter font variants using the filter function.
  $theme_fonts = array_map('morenews_filter_font_variants', array($site_title_font, $primary_font, $secondary_font));

  // Remove any font marked as 'off' or empty entries.
  $theme_fonts = array_filter($theme_fonts, function ($font) {
    return !empty($font) && _x('on', '%s font: on or off', 'morenews') !== 'off';
  });

  // Remove duplicate fonts to avoid unnecessary requests.
  $unique_fonts = array_unique($theme_fonts);

  // Generate the Google Fonts URL if fonts are available.
  if (!empty($unique_fonts)) {
    $fonts_url = add_query_arg(array(
      'family'  => implode('|', $unique_fonts), // Concatenate fonts with '|'.
      'subset'  => ($subsets),
      'display' => 'swap',
    ), 'https://fonts.googleapis.com/css');
  }

  return $fonts_url;
}

/**
 * Add preconnect links for Google Fonts domains to improve performance.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type of the URLs (e.g., 'preconnect').
 * @return array Filtered URLs.
 */
function morenews_add_preconnect_links($urls, $relation_type)
{
  if ('preconnect' === $relation_type) {
    // Preconnect to Google Fonts domains.
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = 'https://fonts.gstatic.com';
  }

  return $urls;
}
add_filter('wp_resource_hints', 'morenews_add_preconnect_links', 10, 2);

/**
 * Preload Google Fonts stylesheets in the <head> for performance.
 */
function morenews_preload_google_fonts()
{
  $fonts_url = morenews_get_fonts_url();

  if ($fonts_url) {
    // Add a preload link for the font stylesheet.
    printf(
      "<link rel='preload' href='%s' as='style' onload=\"this.onload=null;this.rel='stylesheet'\" type='text/css' media='all' crossorigin='anonymous'>\n",
      esc_url($fonts_url)
    );

    // Preconnect to Google Fonts origins.
    echo "<link rel='preconnect' href='https://fonts.googleapis.com' crossorigin='anonymous'>\n";
    echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin='anonymous'>\n";
  }
}
add_action('wp_head', 'morenews_preload_google_fonts', 1);

/**
 * Enqueue the Google Fonts stylesheet in the theme's front-end.
 */
function morenews_enqueue_fonts()
{
  $fonts_url = morenews_get_fonts_url();

  if ($fonts_url) {
    wp_enqueue_style('morenews-google-fonts', $fonts_url, array(), null);
  }
}
add_action('wp_enqueue_scripts', 'morenews_enqueue_fonts');





/**
 * Load Init for Hook files.
 */
require get_template_directory() . '/inc/custom-style.php';

/**
 * Enqueue styles.
 */

add_action('wp_enqueue_scripts', 'morenews_style_files');
function morenews_style_files()
{

  $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
  $morenews_version = wp_get_theme()->get('Version');
  // wp_enqueue_style('font-awesome-v5', get_template_directory_uri() . '/assets/font-awesome/css/all' . $min . '.css');
  wp_enqueue_style('aft-icons', get_template_directory_uri() . '/assets/icons/style.css', array());
  wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
  wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/slick/css/slick' . $min . '.css');

  wp_enqueue_style('sidr', get_template_directory_uri() . '/assets/sidr/css/jquery.sidr.dark.css');

  wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/magnific-popup.css');


  /**
   * Load WooCommerce compatibility file.
   */
  if (class_exists('WooCommerce')) {
    wp_enqueue_style('morenews-woocommerce-style', get_template_directory_uri() . '/woocommerce.css');

    $font_path = WC()->plugin_url() . '/assets/fonts/';
    $inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

    wp_add_inline_style('morenews-woocommerce-style', $inline_font);
  }


  // wp_enqueue_style('morenews-style', get_stylesheet_uri());
  wp_enqueue_style('morenews-style', get_template_directory_uri() . '/style' . $min . '.css', array(), $morenews_version);
  wp_add_inline_style('morenews-style', morenews_custom_style());
}



/**
 * Enqueue scripts.
 */

function morenews_scripts()
{

  $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
  $morenews_version = wp_get_theme()->get('Version');
  wp_enqueue_script('jquery');
  wp_enqueue_script('morenews-background-script', get_template_directory_uri() . '/assets/background-script.js', array('jquery'), $morenews_version);
  wp_enqueue_script('morenews-navigation', get_template_directory_uri() . '/js/navigation.js', array(), $morenews_version, true);
  wp_enqueue_script('morenews-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), $morenews_version, true);
  wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/slick/js/slick' . $min . '.js', array('jquery'), $morenews_version, true);
  wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap' . $min . '.js', array('jquery'), $morenews_version, array(
    'in_footer' => true, // Because involves header.
    'strategy'  => 'defer',
  ));
  wp_enqueue_script('sidr', get_template_directory_uri() . '/assets/sidr/js/jquery.sidr' . $min . '.js', array('jquery'), $morenews_version, true);
  wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/jquery.magnific-popup' . $min . '.js', array('jquery'), $morenews_version, true);
  wp_enqueue_script('matchheight', get_template_directory_uri() . '/assets/jquery-match-height/jquery.matchHeight' . $min . '.js', array('jquery'), $morenews_version, true);
  wp_enqueue_script('marquee', get_template_directory_uri() . '/admin-dashboard/dist/morenews_marque_scripts.build.js', array('jquery'), $morenews_version, true);

  wp_enqueue_script('morenews-script', get_template_directory_uri() . '/admin-dashboard/dist/morenews_scripts.build.js', array('jquery'), $morenews_version, true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'morenews_scripts');


/**
 * Enqueue admin scripts and styles.
 *
 * @since MoreNews 1.0.0
 */
function morenews_admin_scripts($hook)
{
  if ('widgets.php' === $hook) {
    wp_enqueue_media();
    wp_enqueue_script('morenews-widgets', get_template_directory_uri() . '/assets/widgets.js', array('jquery'), '1.0.0', true);
  }

  wp_enqueue_style('morenews-notice', get_template_directory_uri() . '/assets/css/notice.css');
}

add_action('admin_enqueue_scripts', 'morenews_admin_scripts');

add_action('elementor/editor/before_enqueue_scripts', 'morenews_admin_scripts');



/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom Multi Author tags for this theme.
 */
require get_template_directory() . '/inc/multi-author.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-images.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/init.php';




/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
  require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Descriptions on Header Menu
 * @author AF themes
 * @param string $item_output , HTML outputp for the menu item
 * @param object $item , menu item object
 * @param int $depth , depth in menu structure
 * @param object $args , arguments passed to wp_nav_menu()
 * @return string $item_output
 */
function morenews_header_menu_desc($item_output, $item, $depth, $args)
{
  $show_primary_menu_desc = morenews_get_option('show_primary_menu_desc');
  if ($show_primary_menu_desc) {
    if (isset($args->theme_location) && 'aft-primary-nav' == $args->theme_location && $item->description)
      $item_output = str_replace('</a>', '<span class="menu-description">' . $item->description . '</span></a>', $item_output);
  }


  return $item_output;
}

add_filter('walker_nav_menu_start_el', 'morenews_header_menu_desc', 10, 4);

function morenews_menu_notitle($menu)
{
  return $menu = preg_replace('/ title=\"(.*?)\"/', '', $menu);
}
add_filter('wp_nav_menu', 'morenews_menu_notitle');
add_filter('wp_page_menu', 'morenews_menu_notitle');
add_filter('wp_list_categories', 'morenews_menu_notitle');



function morenews_print_pre($args)
{
  if ($args) {
    echo "<pre>";
    print_r($args);
    echo "</pre>";
  } else {
    echo "<pre>";
    print_r('Empty');
    echo "</pre>";
  }
}

add_action('init', 'morenews_transltion_init', 99);

function morenews_transltion_init()
{
  load_theme_textdomain('morenews', get_template_directory()  . '/languages');
}
