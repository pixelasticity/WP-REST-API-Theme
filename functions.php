<?php
/**
 * WP REST API Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_REST_API_Theme
 */

if ( ! function_exists( 'wp_rest_api_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wp_rest_api_theme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WP REST API Theme, use a find and replace
	 * to change 'wp-rest-api-theme' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'wp-rest-api-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'wp-rest-api-theme' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wp_rest_api_theme_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'wp_rest_api_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wp_rest_api_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wp_rest_api_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'wp_rest_api_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wp_rest_api_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wp-rest-api-theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wp-rest-api-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wp_rest_api_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wp_rest_api_theme_scripts() {
	wp_enqueue_style( 'wp-rest-api-theme-style', get_stylesheet_uri() );
	
	wp_enqueue_script( 'vue-js', get_template_directory_uri() . '/js/vue.js', array(), '2.0.3', true );
	
	wp_enqueue_script( 'vue-resource-js', get_template_directory_uri() . '/js/vue-resource.js', array('vue-js'), '1.0.3', true );
	
	wp_enqueue_script( 'vue-router-js', get_template_directory_uri() . '/js/vue-router.js', array('vue-js'), '2.0.1', true );
	
	wp_enqueue_script( 'wp-rest-api-theme-js', get_template_directory_uri() . '/js/app.js', array('vue-js'), '1.0', true );

	wp_enqueue_script( 'wp-rest-api-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'wp-rest-api-theme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_rest_api_theme_scripts' );

/**
 * Prepare REST API
 */
function prepare_rest($data, $post, $request) {
	$_data = $data->data;

	// Thumbnails
	$thumbnail_id = get_post_thumbnail_id( $post->ID );
	$thumbnail_src_full = wp_get_attachment_image_src( $thumbnail_id, 'full' );

	// Author
	$author = get_the_author();
	$author_url = get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) );

	// Taxonomy
	$categories = get_the_category( $post->ID );
	$posttags = get_the_tags( $post->ID );

	// Comments
	$comments = get_comments_number( $post->ID );
	
	$_data['featured_media_src_full'] = $thumbnail_src_full[0];
	$_data['author_name']             = $author;
	$_data['author_posts_url']        = $author_url;
	$_data['category_list']           = $categories;
	$_data['tag_list']                = $posttags;
	$_data['comment_number']          = $comments;

	$data->data = $_data;
	return $data;
}
add_filter('rest_prepare_post', 'prepare_rest', 10, 3);

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
