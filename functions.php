<?php
/** 
* Designed by : Vivek 
* Website     : http://www.wpstuffs.com
*	Version: 1.0.0
*/

/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Adding Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'just_read_viewport_meta' );
function just_read_viewport_meta() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Remove Menu support */
remove_theme_support( 'genesis-menus' );

/** Remove Site Tag Line **/
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

/** Unregister layout settings */
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar-alt' );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'inner',
	'footer'
) );
	
/** Relocate the post info function */
remove_action('genesis_before_post_content', 'genesis_post_info');
add_action('genesis_before_post_title', 'genesis_post_info');

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
if (!is_page()) {
	   $post_info = '<span class="post-day">[post_date format="d"]</span> <span class="post-year">[post_date format="M,Y"]</span>';
	   return $post_info;
}}
	
/** Customize search form input box text */
add_filter( 'genesis_search_text', 'custom_search_text' );
function custom_search_text($text) {
		return esc_attr( 'Search my blog...' );
}
 
/** Customize search form input button text */
add_filter( 'genesis_search_button_text', 'custom_search_button_text' );
function custom_search_button_text($text) {
	return esc_attr( 'Go' );
}
 
/** Add support for post formats */
add_theme_support( 'post-formats', array(
	'aside',
	'audio',
	'chat',
	'gallery',
	'image',
	'link',
	'quote',
	'status',
	'video'
) );

add_theme_support( 'genesis-post-format-images' );

/** REmovving Post info,Title and Post meta according to the post format */
add_action( 'genesis_before_post', 'just_read_remove_elements' );

function just_read_remove_elements() {
		if ( 'aside' == get_post_format() || 'quote' == get_post_format() || 'status' == get_post_format() || 'link' == get_post_format()) {
			remove_action( 'genesis_post_title', 'genesis_do_post_title' );
			remove_action( 'genesis_before_post_title', 'genesis_post_info' );
			remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
		}

		else if ( 'gallery' == get_post_format() || 'chat' == get_post_format() || 'audio' == get_post_format() || 'video' == get_post_format() || 'image' == get_post_format()) {
			remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
			add_action( 'genesis_post_title', 'genesis_do_post_title' );
			add_action( 'genesis_before_post_title', 'genesis_post_info' );
		}
		// Add back, as post has no format
		else {
			add_action( 'genesis_post_title', 'genesis_do_post_title' );
			add_action( 'genesis_before_post_title', 'genesis_post_info' );
			add_action( 'genesis_after_post_content', 'genesis_post_meta' );
		}
}

/** Footer Copyright notice */
add_filter('genesis_footer_output', 'footer_output_filter', 10, 3);
function footer_output_filter( $output, $backtotop_text, $creds_text ) {
    $creds_text = '<p>Copyright [footer_copyright] - <a href="'. trailingslashit( get_bloginfo('url') ) .'" title="'. esc_attr( get_bloginfo('name') ) .' rel="nofollow"">'.get_bloginfo('name').'</a> | design by <a href="http://www.wpstuffs.com">WPStuffs</a>';
    $output = '<div class="gototop">' . $backtotop_text . '</div>' . '<div class="creds">' . $creds_text . '</div>';
      return $output;
}

/** Add custom Favicon */
add_filter( 'genesis_pre_load_favicon', 'custom_favicon_filter' );
function custom_favicon_filter( $favicon_url ) {
	return ''. trailingslashit( get_bloginfo('url') ) .'/wp-content/themes/justread/images/favicon.png';
}