<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package MoreNews
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function morenews_body_classes($classes)
{
  // Adds a class of hfeed to non-singular pages.
  if (!is_singular()) {
    $classes[] = 'hfeed';
  }

  $first_post_full = morenews_get_option('archive_layout_first_post_full');
  if ($first_post_full) {
    $classes[] = 'archive-first-post-full';
  }

  $sticky_header = morenews_get_option('disable_sticky_header_option');
  if ($sticky_header ==  false) {
    $sticky_header_class = morenews_get_option('sticky_header_direction');
    $classes[] = $sticky_header_class . ' aft-sticky-header';
  }


  $global_site_mode_setting = morenews_get_option('global_site_mode_setting');
  $morenews_enable_site_mode_switch = morenews_get_option('enable_site_mode_switch');
  if ($morenews_enable_site_mode_switch == 'aft-enable-mode-switch') {
    if (isset($_COOKIE["morenews-stored-site-mode"])) {
      $classes[] = $_COOKIE["morenews-stored-site-mode"];
    } else {
      if (!empty($global_site_mode_setting)) {
        $classes[] = $global_site_mode_setting;
      }
    }
  } else {
    if (!empty($global_site_mode_setting)) {
      $classes[] = $global_site_mode_setting;
    }
  }

  // $global_site_mode_setting = morenews_get_option('global_site_mode_setting');

  // if (!empty($global_site_mode_setting)) {
  //   $classes[] = $global_site_mode_setting;
  // }

  $secondary_color_mode = morenews_get_option('secondary_color_mode');
  if (!empty($secondary_color_mode)) {
    $classes[] = 'aft-secondary-' . $secondary_color_mode;
  }

  $header_layout = morenews_get_option('header_layout');
  $morenews_enable_header_builder = morenews_get_option('athfb_show_checkbox_header');
  if ($morenews_enable_header_builder) {
    $classes[] =  'aft-header-layout-side';
  } else {
    if (!empty($header_layout)) {
      $classes[] = 'aft-' . $header_layout;
    }
  }

  $select_header_image_mode = morenews_get_option('select_header_image_mode');
  if ($select_header_image_mode == 'full') {
    $classes[] = 'header-image-full';
  } elseif ($select_header_image_mode == 'above') {
    $classes[] = 'header-image-above';
  } else {
    $classes[] = 'header-image-default';
  }

  $remove_gaps = morenews_get_option('remove_gaps_between_thumbs');
  if ($remove_gaps) {
    $classes[] = 'aft-no-thumbs-gap';
  }

  $global_widget_title_border = morenews_get_option('global_widget_title_border');
  if (!empty($global_widget_title_border)) {
    $classes[] = $global_widget_title_border;
  }


  global $post;

  $global_layout = morenews_get_option('global_content_layout');
  if (!empty($global_layout)) {
    $classes[] = $global_layout;
  }


  $global_alignment = morenews_get_option('global_content_alignment');
  $page_layout = $global_alignment;
  $disable_class = '';
  $frontpage_content_status = morenews_get_option('frontpage_content_status');
  if (1 != $frontpage_content_status) {
    $disable_class = 'disable-default-home-content';
  }

  // Check if single.
  if ($post && is_singular()) {
    $post_options = get_post_meta($post->ID, 'morenews-meta-content-alignment', true);
    if (!empty($post_options)) {
      $page_layout = $post_options;
    } else {
      $page_layout = $global_alignment;
    }
  }

  // Check if single.
  if ($post && is_singular()) {
    $global_single_content_mode = morenews_get_option('global_single_content_mode');
    $post_single_content_mode = get_post_meta($post->ID, 'morenews-meta-content-mode', true);
    if (!empty($post_single_content_mode)) {
      $classes[] = $post_single_content_mode;
    } else {
      $classes[] = $global_single_content_mode;
    }
  }


  // Check if single.
  if ($post && is_singular()) {
    $single_post_title_view = morenews_get_option('single_post_title_view');
    $classes[] = 'single-post-title-' . $single_post_title_view;
  }


  if (is_front_page()) {
    $frontpage_layout = morenews_get_option('frontpage_content_alignment');
    if (!empty($frontpage_layout)) {
      $page_layout = $frontpage_layout;
    }
  }

  if (!is_front_page() && is_home()) {
    $page_layout = $global_alignment;
  }

  if ($page_layout == 'align-content-right') {
    if (is_front_page() && !is_home()) {
      if (class_exists('WooCommerce')) {
        if (is_shop()) {
          if (is_active_sidebar('sidebar-1')) {
            $classes[] = 'align-content-right';
          } else {
            $classes[] = 'full-width-content';
          }
        } else {
          if (is_active_sidebar('home-sidebar-widgets')) {
            $classes[] = 'align-content-right';
          } else {
            $classes[] = 'full-width-content';
          }
        }
      } else {
        if (is_active_sidebar('home-sidebar-widgets')) {
          $classes[] = 'align-content-right';
        } else {
          $classes[] = 'full-width-content';
        }
      }
    } else {
      if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'align-content-right';
      } else {
        $classes[] = 'full-width-content';
      }
    }
  } elseif ($page_layout == 'full-width-content') {
    $classes[] = 'full-width-content';
  } else {
    if (is_front_page() && !is_home()) {

      if (class_exists('WooCommerce')) {
        if (is_shop()) {
          if (is_active_sidebar('sidebar-1')) {
            $classes[] = 'align-content-left';
          } else {
            $classes[] = 'full-width-content';
          }
        } else {
          if (is_active_sidebar('home-sidebar-widgets')) {
            $classes[] = 'align-content-left';
          } else {
            $classes[] = 'full-width-content';
          }
        }
      } else {
        if (is_active_sidebar('home-sidebar-widgets')) {
          $classes[] = 'align-content-left';
        } else {
          $classes[] = 'full-width-content';
        }
      }
    } else {
      if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'align-content-left';
      } else {
        $classes[] = 'full-width-content';
      }
    }
  }


  $banner_layout = morenews_get_option('global_site_layout_setting');

  if ($banner_layout == 'wide') {
    $classes[] = 'af-wide-layout';
  } elseif ($banner_layout == 'full') {
    $classes[] = 'af-full-layout';
  } else {
    $classes[] = 'af-boxed-layout';

    $global_topbottom_gaps = morenews_get_option('global_site_layout_topbottom_gaps');
    if ($global_topbottom_gaps) {
      $classes[] = 'aft-enable-top-bottom-gaps';
    }
  }


  return $classes;
}

add_filter('body_class', 'morenews_body_classes');


function morenews_is_elementor()
{
  global $post;
  return \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID);
}

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function morenews_pingback_header()
{
  if (is_singular() && pings_open()) {
    echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
  }
}

add_action('wp_head', 'morenews_pingback_header');

/**
 * Get posts based on filter type (category, tag, view count, comment count, etc.).
 *
 * @since MoreNews 1.0.0
 *
 * @param int    $number_of_posts Number of posts to retrieve (max 50).
 * @param int    $term_id         Category or tag ID depending on filter.
 * @param string $filterby        Filter type: 'cat', 'tag', 'view', 'comment', 'recent'.
 * @param array  $extra_args      Optional extra WP_Query args (offset, date_query etc).
 * @return WP_Query               WP_Query object.
 */
if ( ! function_exists( 'morenews_get_posts' ) ) {
  function morenews_get_posts( $number_of_posts = 5, $term_id = 0, $filterby = 'cat', $extra_args = array() ) {
      $number_of_posts = absint( $number_of_posts );
      $number_of_posts = min( $number_of_posts, 20 );  // Cap to safe max.

      $term_id = absint( $term_id );
      $filterby = sanitize_key( $filterby );

      $args = array(
          'post_type'           => 'post',
          'posts_per_page'      => $number_of_posts,
          'post_status'         => 'publish',
          'ignore_sticky_posts' => true,
          'order'               => 'DESC',
          'no_found_rows'       => true,
      );

      switch ( $filterby ) {
          case 'tag':
              if ( $term_id > 0 ) {
                  $args['tag_id'] = $term_id;
              }
              $args['orderby'] = 'date';
              break;          

          case 'comment':
              $args['orderby'] = 'comment_count';
              break;

          case 'recent':
              // Possibly use date_query
              $args['orderby'] = 'date';
              break;

          case 'cat':
          default:
              if ( $term_id > 0 ) {
                  $args['cat'] = $term_id;
              }
              $args['orderby'] = 'date';
              break;
      }

      // Merge in any extra query args
      if ( is_array( $extra_args ) && ! empty( $extra_args ) ) {
          $args = array_merge( $args, $extra_args );
      }

      /**
       * Allow overriding query args.
       *
       * @param array $args           Final WP_Query arguments.
       * @param int   $number_of_posts Passed number of posts.
       * @param int   $term_id         Term ID.
       * @param string $filterby        Filter type.
       * @param array $extra_args      Extra args passed.
       */
      $args = apply_filters( 'morenews_get_posts_args', $args, $number_of_posts, $term_id, $filterby, $extra_args );

      return new WP_Query( $args );
  }
}


/**
 * Returns no image url.
 *
 * @since  MoreNews 1.0.0
 */
if (!function_exists('morenews_post_format')) :
  function morenews_post_format($post_id)
  {
    $post_format = get_post_format($post_id);
    switch ($post_format) {
      case "image":
        $post_format = "<div class='af-post-format em-post-format'><i class='fas fa-image'></i></div>";
        break;
      case "video":
        $post_format = "<div class='af-post-format em-post-format'><i class='fas fa-play'></i></div>";

        break;
      case "gallery":
        $post_format = "<div class='af-post-format em-post-format'><i class='fas fa-images'></i></div>";
        break;
      default:
        $post_format = "";
    }

    echo wp_kses_post($post_format);
  }

endif;


if (!function_exists('morenews_get_block')) :
  /**
   *
   * @param null
   *
   * @return null
   *
   * @since MoreNews 1.0.0
   *
   */
  function morenews_get_block($block = 'grid', $section = 'post')
  {

    get_template_part('inc/hooks/blocks/block-' . $section, $block);
  }
endif;

if (!function_exists('morenews_archive_title')) :
  /**
   *
   * @param null
   *
   * @return null
   *
   * @since MoreNews 1.0.0
   *
   */

  function morenews_archive_title($title)
  {
    if (is_category()) {
      $title = single_cat_title('', false);
    } elseif (is_tag()) {
      $title = single_tag_title('', false);
    } elseif (is_author()) {
      $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_post_type_archive()) {
      $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
      $title = single_term_title('', false);
    }

    return $title;
  }

endif;
add_filter('get_the_archive_title', 'morenews_archive_title');


/* Display Breadcrumbs */
if (!function_exists('morenews_get_breadcrumb')) :

  /**
   * Simple breadcrumb.
   *
   * @since 1.0.0
   */
  function morenews_get_breadcrumb()
  {

    $enable_breadcrumbs = morenews_get_option('enable_breadcrumb');

    if (1 != $enable_breadcrumbs) {
      return;
    }
    // Bail if Home Page.
    if (is_front_page() || is_home()) {
      return;
    }

    $select_breadcrumbs = morenews_get_option('select_breadcrumb_mode');

?>
    <div class="af-breadcrumbs font-family-1 color-pad">

      <?php
      if ((function_exists('yoast_breadcrumb')) && ($select_breadcrumbs == 'yoast')) {
        yoast_breadcrumb();
      } elseif ((function_exists('rank_math_the_breadcrumbs')) && ($select_breadcrumbs == 'rankmath')) {
        rank_math_the_breadcrumbs();
      } elseif ((function_exists('bcn_display')) && ($select_breadcrumbs == 'bcn')) {
        bcn_display();
      } else {
        morenews_get_breadcrumb_trail();
      }
      ?>

    </div>
  <?php


  }

endif;
add_action('morenews_action_get_breadcrumb', 'morenews_get_breadcrumb');

/* Display Breadcrumbs */
if (!function_exists('morenews_get_breadcrumb_trail')) :

  /**
   * Simple excerpt length.
   *
   * @since 1.0.0
   */

  function morenews_get_breadcrumb_trail()
  {

    if (!function_exists('breadcrumb_trail')) {

      /**
       * Load libraries.
       */

      require_once get_template_directory() . '/lib/breadcrumb-trail/breadcrumb-trail.php';
    }

    $breadcrumb_args = array(
      'container' => 'div',
      'show_browse' => false,
    );

    breadcrumb_trail($breadcrumb_args);
  }

endif;


/**
 * Front-page main banner section layout
 */
if (!function_exists('morenews_front_page_main_section_scope')) {

  function morenews_front_page_main_section_scope()
  {

    $morenews_hide_on_blog = morenews_get_option('disable_main_banner_on_blog_archive');

    if ($morenews_hide_on_blog) {
      if (is_front_page()) {
        do_action('morenews_action_front_page_main_section');
      }
    } else {
      if (is_front_page() || is_home()) {
        do_action('morenews_action_front_page_main_section');
      }
    }
  }
}
add_action('morenews_action_front_page_main_section_scope', 'morenews_front_page_main_section_scope');


/* Display Breadcrumbs */
if (!function_exists('morenews_excerpt_length')) :

  /**
   * Simple excerpt length.
   *
   * @since 1.0.0
   */

  function morenews_excerpt_length($length)
  {

    $morenews_global_excerpt_length = morenews_get_option('global_excerpt_length');
    if (is_admin()) {
      return $length;
    }
    return $morenews_global_excerpt_length;
  }

endif;
add_filter('excerpt_length', 'morenews_excerpt_length', 999);


/* Display Breadcrumbs */
if (!function_exists('morenews_excerpt_more')) :

  /**
   * Simple excerpt more.
   *
   * @since 1.0.0
   */
  function morenews_excerpt_more($more)
  {
    if (is_admin()) {
      return $more;
    }
    global $post;
    $morenews_global_read_more_texts = morenews_get_option('global_read_more_texts');
    //return $morenews_global_read_more_texts;
    return '';
  }

endif;
add_filter('excerpt_more', 'morenews_excerpt_more');

if (!function_exists('morenews_get_the_excerpt')) :

  /**
   * Simple excerpt more with descriptive "Read More" links.
   *
   * @since 1.0.0
   */
  function morenews_get_the_excerpt($post_id)
  {

    if (empty($post_id)) {
      return;
    }

    // Get the default excerpt for the post.
    $morenews_default_excerpt = get_the_excerpt($post_id);

    // Retrieve the "Read More" text from theme options.
    $morenews_global_read_more_texts = morenews_get_option('global_read_more_texts');

    // Get the post title to make the "Read More" link more descriptive.
    $post_title = get_the_title($post_id);

    // Create a descriptive "Read More" link, making it translation-ready.
    // Use `sprintf()` to dynamically insert the translated post title.
    $morenews_read_more = sprintf(
      '<div class="aft-readmore-wrapper">
         <a href="%1$s" class="aft-readmore" aria-label="%2$s">
           %3$s <span class="screen-reader-text">%4$s</span>
         </a>
       </div>',
      esc_url(get_permalink($post_id)),  // %1$s: Link to the post.
      esc_attr(sprintf(__('Read more about %s', 'morenews'), $post_title)), // %2$s: Aria-label, translation-ready.
      esc_html($morenews_global_read_more_texts), // %3$s: The main "Read More" text.
      esc_html(sprintf(__('Read more about %s', 'morenews'), $post_title)) // %4$s: Screen-reader text, translation-ready.
    );

    // Get the global excerpt length from theme options.
    $morenews_global_excerpt_length = morenews_get_option('global_excerpt_length');

    // Split the excerpt into words and truncate based on the defined length.
    $excerpt = explode(' ', $morenews_default_excerpt, $morenews_global_excerpt_length);
    if (count($excerpt) >= $morenews_global_excerpt_length) {
      array_pop($excerpt);
      $excerpt = implode(" ", $excerpt) . '...';
    } else {
      $excerpt = implode(" ", $excerpt);
    }

    // Remove any shortcodes or unwanted characters from the excerpt.
    $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);

    // Append the "Read More" link to the excerpt.
    $excerpt = $excerpt . $morenews_read_more;

    return $excerpt;
  }

endif;


/* Display Pagination */
if (!function_exists('morenews_numeric_pagination')) :

  /**
   * Simple excerpt more.
   *
   * @since 1.0.0
   */
  function morenews_numeric_pagination()
  {



    the_posts_pagination(array(
      'mid_size' => 3,
      'prev_text' => __('Previous', 'morenews'),
      'next_text' => __('Next', 'morenews'),
    ));
  }

endif;


// /* Word read count Pagination */
// if (!function_exists('morenews_count_content_words')) :
//   /**
//    * @param $content
//    *
//    * @return string
//    */
//   function morenews_count_content_words($post_id)
//   {
//     $morenews_show_read_mins = morenews_get_option('global_show_min_read');
//     if ($morenews_show_read_mins == 'yes') {
//       $content = apply_filters('the_content', get_post_field('post_content', $post_id));
//       $morenews_read_words = morenews_get_option('global_show_min_read_number');
//       $morenews_decode_content = html_entity_decode($content);
//       $morenews_filter_shortcode = do_shortcode($morenews_decode_content);
//       $morenews_strip_tags = wp_strip_all_tags($morenews_filter_shortcode, true);
//       $morenews_count = str_word_count($morenews_strip_tags);
//       $morenews_word_per_min = (absint($morenews_count) / $morenews_read_words);
//       $morenews_word_per_min = ceil($morenews_word_per_min);

//       if (absint($morenews_word_per_min) > 0) {
//         $word_count_strings = sprintf(__("%s min read", 'morenews'), number_format_i18n($morenews_word_per_min));
//         if ('post' == get_post_type($post_id)) :
//           echo '<span class="min-read">';
//           echo esc_html($word_count_strings);
//           echo '</span>';
//         endif;
//       }
//     }
//   }

// endif;

if (!function_exists('morenews_count_content_words')) :

  function morenews_count_content_words($post_id)
  {

    $morenews_show_read_mins = morenews_get_option('global_show_min_read');
    if($morenews_show_read_mins == 'no'){
      return;
    }

    // Posts only
    if ('post' !== get_post_type($post_id)) {
      return;
    }

    // Get pre-saved reading time (best performance)
    $read_time = absint(get_post_meta($post_id, '_aft_read_time', true));

    // If not available (older posts), fallback once
    if (!$read_time) {

      // Lightweight processing (DO NOT use the_content filter)
      $content = get_post_field('post_content', $post_id);

      // Fast cleanup
      $clean_text = wp_strip_all_tags(strip_shortcodes($content), true);

      // Fast word count
      $word_count = str_word_count($clean_text);

      // Words per minute
      $wpm = absint(morenews_get_option('global_show_min_read_number'));
      if (!$wpm) {
        $wpm = 200;
      }

      $read_time = ceil($word_count / $wpm);

      // Store so it never recalculates again
      update_post_meta($post_id, '_aft_read_time', $read_time);
    }

    if ($read_time > 0) {

      // Translatable singular/plural
      $output = sprintf(
        _n(
          '%s minute read',
          '%s minutes read',
          $read_time,
          'morenews'
        ),
        number_format_i18n($read_time)
      );

      echo '<span class="min-read">' . esc_html($output) . '</span>';
    }
  }

endif;



/**
 * Check if given term has child terms
 *
 * @param Integer $term_id
 * @param String $taxonomy
 *
 * @return Boolean
 */
function morenews_list_popular_taxonomies($taxonomy = 'post_tag', $title = "Popular Tags", $number = 5)
{
  $popular_taxonomies = get_terms(array(
    'taxonomy' => $taxonomy,
    'number' => absint($number),
    'orderby' => 'count',
    'order' => 'DESC',
    'hide_empty' => true,
  ));

  $html = '';

  if (isset($popular_taxonomies) && !empty($popular_taxonomies)) :
    $html .= '<div class="aft-popular-taxonomies-lists clearfix">';
    if (!empty($title)) :
      $html .= '<span>';
      $html .= esc_html($title);
      $html .= '</span>';
    endif;
    $html .= '<ul>';
    foreach ($popular_taxonomies as $tax_term) :
      $html .= '<li>';
      $html .= '<a href="' . esc_url(get_term_link($tax_term)) . '">';
      $html .= $tax_term->name;
      $html .= '</a>';
      $html .= '</li>';
    endforeach;
    $html .= '</ul>';
    $html .= '</div>';
  endif;

  echo wp_kses_post($html);
}


/**
 * @param $post_id
 * @param string $size
 *
 * @return mixed|string
 */
function morenews_get_freatured_image_url($post_id, $size = 'large')
{
  $url = '';
  if (has_post_thumbnail($post_id)) {
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
    if (isset($thumb[0])) {
      $url = $thumb[0];
    }
  } else {
    $url = '';
  }

  return $url;
}


//Get attachment alt tag

if (!function_exists('morenews_get_img_alt')) :
  function morenews_get_img_alt($attachment_ID)
  {
    // Get ALT
    $thumb_alt = get_post_meta($attachment_ID, '_wp_attachment_image_alt', true);

    // No ALT supplied get attachment info
    if (empty($thumb_alt))
      $attachment = get_post($attachment_ID);

    // Use caption if no ALT supplied
    if (empty($thumb_alt))
      $thumb_alt = $attachment->post_excerpt;

    // Use title if no caption supplied either
    if (empty($thumb_alt))
      $thumb_alt = $attachment->post_title;

    // Return ALT
    return trim(strip_tags($thumb_alt));
  }
endif;

// Move Jetpack from the_content / the_excerpt to another position

function morenews_jptweak_remove_share()
{
  if (is_singular('post')) {
    remove_filter('the_content', 'sharing_display', 19);
    remove_filter('the_excerpt', 'sharing_display', 19);
  }
}

add_action('loop_start', 'morenews_jptweak_remove_share');


/**
 * @param $post_id
 */
function morenews_get_comments_views_share($post_id)
{

  $aft_post_type = get_post_type($post_id);

  if ($aft_post_type !== 'post') {
    return;
  }

  ?>
  <span class="aft-comment-view-share">
    <?php
    $show_comment_count = morenews_get_option('global_show_comment_count');

    if ('yes' === $show_comment_count) :

      $comment_count = get_comments_number($post_id);

      if (comments_open($post_id) || $comment_count > 0) :
    ?>
        <span class="aft-comment-count">
          <a href="<?php echo esc_url(get_comments_link($post_id)); ?>">
            <i class="far fa-comment"></i>
            <span class="aft-show-hover">
              <?php echo esc_html($comment_count); ?>
            </span>
          </a>
        </span>
      <?php
      endif;
    endif;
    // endif;


    ?>
  </span>
  <?php
}


/**
 * @param $post_id
 */
function morenews_archive_social_share_icons($post_id)
{
  if (class_exists('Jetpack') && Jetpack::is_module_active('sharedaddy')) :
    if (function_exists('sharing_display')) :
      $sharer = new Sharing_Service();
      $global = $sharer->get_global_options();
      if (in_array('index', $global['show']) && (is_home() || is_front_page() || is_archive() || is_search() || in_array(get_post_type(), $global['show']))) :
  ?>
        <div class="aft-comment-view-share">
          <span class="aft-jpshare">
            <i class="fa fa-share-alt" aria-hidden="true"></i>
            <?php sharing_display('', true); ?>
          </span>
        </div>
    <?php
      endif;
    endif;
  endif;
}

//Social share icons and comments view for single page

function morenews_single_post_social_share_icons()
{
  if (class_exists('Jetpack') && Jetpack::is_module_active('sharedaddy')) :

    $social_share_icon_opt = morenews_get_option('single_post_social_share_view');

    if ($social_share_icon_opt == 'side') {
      echo '<div class="vertical-left-right">';
    }
    ?>
    <div class="aft-social-share">
      <?php
      if (function_exists('sharing_display')) {
        sharing_display('', true);
      }
      ?>

    </div>
  <?php
    if ($social_share_icon_opt == 'side') {
      echo '</div>';
    }
  endif;
}

function morenews_single_post_commtents_view($post_id)
{
  ?>
  <div class="aft-comment-view-share">
    <?php
    $show_comment_count = morenews_get_option('global_show_comment_count');
    if ('yes' === $show_comment_count) :

      $comment_count = get_comments_number($post_id);

      if (comments_open($post_id) || $comment_count > 0) :
    ?>
        <span class="aft-comment-count">
          <a href="<?php echo esc_url(get_comments_link($post_id)); ?>">
            <i class="far fa-comment"></i>
            <span class="aft-show-hover">
              <?php echo esc_html(get_comments_number($post_id)); ?>
            </span>
          </a>
        </span>
      <?php endif;
    endif;

    ?>
  </div>
  <?php
}


/* Display Breadcrumbs */
if (!function_exists('morenews_toggle_lazy_load')) :

  /**
   * Simple excerpt more.
   *
   * @since 1.0.0
   */
  function morenews_toggle_lazy_load()
  {
    $morenews_lazy_load = morenews_get_option('global_toggle_image_lazy_load_setting');
    if ($morenews_lazy_load == 'disable') {
      add_filter('wp_lazy_loading_enabled', '__return_false');
    }
  }

endif;

add_action('wp_loaded', 'morenews_toggle_lazy_load');


add_action('init', 'morenews_disable_wp_emojis');


function morenews_disable_wp_emojis()
{
  $disable_emoji = morenews_get_option('disable_wp_emoji');
  if ($disable_emoji) {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'morenews_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'morenews_disable_emojis_remove_dns_prefetch', 10, 2);
  }
}

function morenews_disable_emojis_tinymce($plugins)
{
  if (is_array($plugins)) {
    return array_diff($plugins, array('wpemoji'));
  }
  return array();
}

function morenews_disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
  if ('dns-prefetch' === $relation_type) {
    $emoji_svg_url = 'https://s.w.org/images/core/emoji/';
    foreach ($urls as $key => $url) {
      if (strpos($url, $emoji_svg_url) !== false) {
        unset($urls[$key]);
      }
    }
  }
  return $urls;
}

if (!function_exists('morenews_author_bio_box')) :
  function morenews_author_bio_box()
  {
      if (!is_single()) {
          return;
      }

      $allowed_post_types = apply_filters('morenews_author_bio_post_types', array('post'));

      if (!in_array(get_post_type(), $allowed_post_types, true)) {
          return;
      }

      $author_id   = get_the_author_meta('ID');
      $author_name = get_the_author();
      $author_url  = get_author_posts_url($author_id);
      $website     = get_the_author_meta('user_url');

      // Get author role (optional)
      $user      = get_userdata( $author_id );
      $role_name = '';
      if ( $user && ! empty( $user->roles ) ) {
          $role_name = ucfirst( $user->roles[0] );
      }

  ?>
      <section class="morenews-author-bio">

          <?php

          
          $title = esc_html__('About the Author', 'morenews');
          morenews_render_section_title($title);
          ?>


          <div class="author-box-content">
              <div class="author-avatar">
                  <?php echo get_avatar($author_id, 96); ?>
              </div>
              <div class="author-info">
                  <h3 class="author-name">
                      <a href="<?php echo esc_url($author_url); ?>">
                          <?php echo esc_html($author_name); ?>
                      </a>
                  </h3>
                  <?php if ($role_name) : ?>
                      <p class="author-role">
                          <?php echo esc_html($role_name); ?>
                      </p>
                  <?php endif; ?>
                  <p class="author-description">
                      <?php echo esc_html(get_the_author_meta('description')); ?>
                  </p>

                  <div class="author-website-and-posts">
                  <?php if ($website) : ?>
                      
                          <a class="author-website" href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener">
                              <?php esc_html_e('Visit Website', 'morenews'); ?>
                          </a>
                    
                  <?php endif; ?>

                  <a href="<?php echo esc_url($author_url); ?>" class="author-posts-link">
                      <?php esc_html_e('View All Posts', 'morenews'); ?>
                  </a>
                  </div>

              </div>
          </div>
      </section>
<?php
  }
endif;

add_filter('the_content', 'morenews_append_author_bio');
function morenews_append_author_bio($content)
{
  
  $single_show_theme_author_bio = morenews_get_option('single_show_theme_author_bio');

  if( $single_show_theme_author_bio == false){
    return $content;
  }
  
  // Check if WP Post Author plugin has its author box active
  if (has_filter('the_content', 'awpa_add_author')) {
      return $content;
  }

  if (is_single() && in_the_loop() && is_main_query()) {
      ob_start();
      morenews_author_bio_box();
      $bio_box = ob_get_clean();
      return $content . $bio_box;
  }

  return $content;
}

/*
** Exclude pages from search results
*/
function morenews_search_results( $query ) {

  $search_archive_content_view = morenews_get_option('search_archive_content_view');

  if ($search_archive_content_view == 'all') {
    return;
  }

  // Ensure this only runs on the main query in the search context
  if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
      $query->set( 'post_type', 'post' ); // Include only posts
  }
}
add_action( 'pre_get_posts', 'morenews_search_results' );


if (!function_exists('morenews_athfb_add_custom_admin_menu')) {

  function morenews_athfb_add_custom_admin_menu($wp_admin_bar)
  {
    // Show only for admins (change capability if needed)
    if (!current_user_can('manage_options')) {
      return;
    }

    // Parent menu icon (optional)
    $afthemes_icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="50px" height="50px" viewBox="0 0 50 49" version="1.1">
    <g id="surface1">
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 22.332031 2.984375 C 18.230469 3.53125 14.289062 5.273438 11.003906 7.976562 C 7.398438 10.933594 4.695312 15.402344 3.71875 20.03125 C 3.273438 22.113281 3.089844 24.902344 3.308594 26.105469 L 3.398438 26.570312 L 4.136719 25.5 C 5.496094 23.511719 6.90625 22.234375 8.808594 21.25 C 12.152344 19.507812 16.191406 19.433594 19.675781 21.042969 C 21.144531 21.722656 22.132812 22.417969 23.28125 23.550781 C 24.800781 25.058594 25.839844 26.851562 26.457031 29.078125 C 26.71875 29.980469 26.730469 30.34375 26.789062 37.324219 C 26.863281 45.164062 26.851562 44.992188 27.496094 45.554688 C 28.175781 46.136719 29.519531 46.34375 30.238281 45.980469 C 30.695312 45.75 31.199219 45.1875 31.386719 44.75 C 31.484375 44.46875 31.523438 40.671875 31.496094 29.394531 C 31.484375 15.804688 31.496094 14.332031 31.683594 13.625 C 32.621094 10.070312 36.253906 8.023438 39.882812 9.011719 C 40.378906 9.15625 40.824219 9.230469 40.871094 9.179688 C 41.007812 9.035156 38.996094 7.292969 37.683594 6.429688 C 35.574219 5.042969 33.496094 4.128906 30.941406 3.46875 C 28.410156 2.824219 24.976562 2.628906 22.332031 2.984375 Z M 22.332031 2.984375 "/>
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 36.375 12.859375 C 35.820312 13.140625 35.4375 13.527344 35.191406 14.0625 C 34.980469 14.515625 34.957031 14.988281 34.957031 21.152344 L 34.957031 27.761719 L 37.859375 27.761719 C 41.019531 27.761719 41.21875 27.800781 41.738281 28.445312 C 42.082031 28.882812 42.269531 29.613281 42.167969 30.164062 C 42.058594 30.734375 41.355469 31.5 40.785156 31.660156 C 40.527344 31.722656 39.144531 31.78125 37.710938 31.78125 L 35.078125 31.78125 L 35.078125 44.082031 L 35.796875 43.726562 C 36.191406 43.53125 37.019531 43.046875 37.625 42.632812 C 42.785156 39.234375 46.183594 33.546875 46.949219 27.03125 C 47.257812 24.328125 46.925781 20.738281 46.121094 18.339844 C 45.554688 16.644531 44.34375 14.125 44.082031 14.125 C 44.035156 14.125 44.046875 14.332031 44.109375 14.574219 C 44.171875 14.832031 44.21875 15.367188 44.21875 15.78125 C 44.21875 16.476562 44.195312 16.546875 43.761719 16.960938 C 43.21875 17.511719 42.578125 17.707031 41.871094 17.546875 C 40.785156 17.304688 40.402344 16.804688 40.007812 15.125 C 39.796875 14.175781 39.675781 13.894531 39.304688 13.492188 C 39.070312 13.222656 38.710938 12.933594 38.523438 12.835938 C 38.066406 12.601562 36.84375 12.617188 36.375 12.859375 Z M 36.375 12.859375 "/>
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 13.464844 23.878906 C 10.585938 24.160156 7.917969 26.082031 6.78125 28.714844 C 6.152344 30.136719 5.964844 32.535156 6.359375 34.035156 C 6.964844 36.359375 8.609375 38.355469 10.746094 39.367188 C 12.128906 40.027344 12.945312 40.207031 14.5 40.195312 C 17.859375 40.195312 20.675781 38.441406 22.046875 35.496094 C 22.628906 34.265625 22.789062 33.488281 22.789062 31.964844 C 22.777344 29.6875 22 27.800781 20.441406 26.242188 C 18.625 24.414062 16.230469 23.609375 13.464844 23.878906 Z M 13.464844 23.878906 "/>
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 22 41.644531 C 20.527344 42.730469 18.949219 43.449219 16.871094 43.957031 L 15.527344 44.289062 L 16.378906 44.675781 C 18.847656 45.820312 23.097656 46.808594 23.097656 46.246094 C 23.097656 45.992188 22.714844 41.339844 22.691406 41.242188 C 22.679688 41.195312 22.367188 41.378906 22 41.644531 Z M 22 41.644531 "/>
    </g>
    </svg>';

    $parent_title  = $afthemes_icon . esc_html__('MoreNews Options', 'morenews');

    // Add parent menu
    $wp_admin_bar->add_menu(array(
      'id'    => 'morenews-menu',
      'title' => $parent_title,
      'href'  => admin_url('admin.php?page=morenews-pro'),
      'meta'  => array(
        'title'  => esc_attr__('MoreNews Options', 'morenews'), // Tooltip
        // 'target' => '_blank', // Open in new tab
      ),
    ));

    // Define submenu items
    $submenu_items = array(

      
      array(
        'id'    => 'starter-sites-submenu',
        'title' => __('Starter Sites', 'morenews'),
        'href'  => admin_url('admin.php?page=starter-sites'),
      ),
      array(
        'id'    => 'header-submenu',
        'title' => __('Header Builder', 'morenews'),
        'href'  => admin_url('customize.php?autofocus[section]=header_builder'),
      ),
      array(
        'id'    => 'banner-submenu',
        'title' => __('Front-page Banner', 'morenews'),
        'href'  => admin_url('customize.php?autofocus[section]=frontpage_main_banner_section_settings'),
      ),
      array(
        'id'    => 'footer-submenu',
        'title' => __('Footer Builder', 'morenews'),
        'href'  => admin_url('customize.php?autofocus[section]=footer_builder'),
      ),
      array(
        'id'    => 'af-speed-submenu',
        'title' => __('Speed Booster', 'morenews'),
        'href'  => admin_url('admin.php?page=af-speed'),
      ),
      array(
        'id'    => 'af-growth-submenu',
        'title' => __('Growth Tools', 'morenews'),
        'href'  => admin_url('admin.php?page=af-growth'),
      ),
      array(
        'id'    => 'upgrade-submenu',
        'title' => __('Upgrade to Pro', 'morenews'),
        'href'  => "https://afthemes.com/products/morenews-pro",
      )
    );

    // Loop and add submenu items
    foreach ($submenu_items as $item) {
      $wp_admin_bar->add_menu(array(
        'id'     => $item['id'],
        'title'  => esc_html($item['title']),
        'href'   => $item['href'],
        'parent' => 'morenews-menu',
        'meta'   => array(
          'title'  => $item['title'],
          'target' => '_blank', // Open in new tab
        ),
      ));
    }
  }

  // Hook into admin bar menu
  add_action('admin_bar_menu', 'morenews_athfb_add_custom_admin_menu', 100);
  add_action('admin_enqueue_scripts', 'morenews_admin_bar_styling');
  add_action('wp_enqueue_scripts', 'morenews_admin_bar_styling'); // Also in frontend if admin bar visible

  function morenews_admin_bar_styling()
  {
    if (is_admin_bar_showing()) {
      wp_add_inline_style(
        'admin-bar',
        '
        /* Base parent menu style */
        #wpadminbar #wp-admin-bar-morenews-menu > .ab-item {
            background-color: #007ACC !important;
            color: #fff !important;
            font-weight: bold;
            // border-radius: 3px;
            padding: 0 6px;
        }

        /* Hover, focus, active, and "hover" class from WP */
        #wpadminbar #wp-admin-bar-morenews-menu > .ab-item:hover,
        #wpadminbar #wp-admin-bar-morenews-menu.hover > .ab-item,
        #wpadminbar #wp-admin-bar-morenews-menu > .ab-item:focus,
        #wpadminbar #wp-admin-bar-morenews-menu > .ab-item:active {
            background-color: #006eb8 !important;
            color: #fff !important;
        }

        /* Visited state (rarely used in admin bar) */
        #wpadminbar #wp-admin-bar-morenews-menu > .ab-item:visited {
            color: #fff !important;
        }

        /* Icon alignment */
        // #wpadminbar #wp-admin-bar-morenews-menu img {
        //     vertical-align: middle;
        //     margin-right: 4px;
        //     color: #fff !important;
        // }
        #wpadminbar #wp-admin-bar-morenews-menu svg {
          height:20px;
          width: 20px;
          fill: #fff;
          color: #fff;
          vertical-align: middle;
          margin-right: 5px;
      }
      #wpadminbar #wp-admin-bar-morenews-menu:hover svg {
          fill: #ffcc00;
      }

      #wpadminbar ul li#wp-admin-bar-upgrade-submenu{
        background-color: #039562;
        margin-bottom: 0;
    }

    #wpadminbar ul li#wp-admin-bar-upgrade-submenu a{
        color:#ffffff;
        text-transform: uppercase;
        padding: 3px 10px;
    }
      
        '
      );
    }
  }
}