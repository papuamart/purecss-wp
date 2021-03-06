<?php

/* Imports the main style.css and PureCSS styles */
function theme_styles() {

	wp_enqueue_style( 'pure_css', get_template_directory_uri() . '/css/pure-min.css' );
	wp_enqueue_style( 'pure_grid_css', get_template_directory_uri() . '/css/grids-responsive-min.css' );
	wp_enqueue_style( 'main_css', get_template_directory_uri() . '/style.css' );

}
add_action( 'wp_enqueue_scripts', 'theme_styles' );

/* Adds Menus and Thumbnails theme support */
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );

/* Creates the main Sidebar widget area */
function pwp_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'pure-wp' ),
		'id' => 'sidebar-1',
		'description' => __( 'The main sidebar appears on the right on each page', 'pure-wp' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

add_action( 'widgets_init', 'pwp_widgets_init' );

/* Creates the Header menu */
function pwp_navigation_menus_init() {
	register_nav_menus( array(
		'header_menu' => 'Header Navigation Menu',
		'footer_menu' => 'Footer Navigation Menu',
	) );
}
add_action( 'init', 'pwp_navigation_menus_init' );

/* Adds PureCSS classes to nav menu <li> and <a> tags output by wp_nav_menu() calls */
function pwp_navigation_menus_pure_li_a($output) {
  $output = preg_replace('/class="menu-item/', 'class="pure-menu-item', $output);
	$output = preg_replace('/a href/', 'a class="pure-menu-link" href', $output);
  return $output;
}
add_filter('wp_nav_menu', 'pwp_navigation_menus_pure_li_a');

/* Adds 'pure-form' and 'pure-form-stacked' classes to the default search widget form, and the search form displayed by get_search_form() */
function pwp_search_form_pure($output) {
	$output = preg_replace('/class="searchform"/', 'class="searchform pure-form pure-form-stacked"', $output);
	return $output;
}
add_filter('get_search_form', 'pwp_search_form_pure');

/* Adds 'pure-button' class to the default search button */
function pwp_search_button_pure($output) {
	$output = preg_replace('/id="searchsubmit"/', 'id="searchsubmit" class="pure-button"', $output);
	return $output;
}
add_filter('get_search_form', 'pwp_search_button_pure');

/* Adds 'pure-form', 'pure-form-stacked' and 'pure-button' classes to the default comments template form displayed by comments_template()*/
function pwp_comment_form_pure( $args ) {
  $args['class_form'] = 'pure-form pure-form-stacked';
	$args['class_submit'] = 'pure-button';
  return $args;
}
add_filter( 'comment_form_defaults', 'pwp_comment_form_pure' );

/* Adds 'post-excerpt' class and schema tag to excerpt <p> tags */
function pwp_excerpt_class($output) {
	$output = preg_replace('/<p/', '<p class="post-excerpt" itemprop="about"', $output);
	return $output;
}
add_filter('the_excerpt', 'pwp_excerpt_class');

/* Limits post excerpts to 20 words */
function pwp_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'pwp_excerpt_length', 999 );

/* Replaces the default [...] at the end of an excerpt with a nice arrow :) */
function pwp_excerpt_continuereading($more) {
    global $post;
    return '... <a class="continue-reading" href="'. get_permalink($post->ID) . '"> &#10148; </a>';
}
add_filter('excerpt_more', 'pwp_excerpt_continuereading');

/* Returns either the URL or the Alt text for a post's featured image */
function pwp_featured_image($post_id, $output) {

	$featured_image = get_post_thumbnail_id($post_id);

	switch ($output) {
		case 'url':
			$output = wp_get_attachment_image_src( $featured_image, 'thumbnail-size', true );
			return $output[0];
			break;
		case 'alt':
			$output = get_post_meta( $featured_image, '_wp_attachment_image_alt', true);
			return $output;
			break;
		default:
			$output = wp_get_attachment_image_src( $featured_image, 'thumbnail-size', true );
			return $output[0];
			break;
	}

}
?>
