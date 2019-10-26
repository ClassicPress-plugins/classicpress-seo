<?php
/**
 * Metabox - Rich Snippet Tab
 *
 * @package    ClassicPress_SEO
 * @subpackage ClassicPress_SEO\RichSnippet
 */

use ClassicPress_SEO\Helper;
use ClassicPress_SEO\Helpers\WordPress;

if ( ! Helper::has_cap( 'onpage_snippet' ) ) {
	return;
}

$post_type = WordPress::get_post_type();

if ( ( class_exists( 'WooCommerce' ) && 'product' === $post_type ) || ( class_exists( 'Easy_Digital_Downloads' ) && 'download' === $post_type ) ) {

	$cmb->add_field([
		'id'      => 'cpseo_woocommerce_notice',
		'type'    => 'notice',
		'what'    => 'info',
		'content' => esc_html__( 'ClassicPress SEO automatically inserts additional Rich Snippet meta data for WooCommerce products. You can set the Rich Snippet Type to "None" to disable this feature and just use the default data added by WooCommerce.', 'cpseo' ),
	]);

	$cmb->add_field([
		'id'      => 'cpseo_rich_snippet',
		'type'    => 'radio_inline',
		'name'    => esc_html__( 'Rich Snippet Type', 'cpseo' ),
		/* translators: link to title setting screen */
		'desc'    => sprintf( wp_kses_post( __( 'Rich Snippets help you stand out in SERPs.', 'cpseo' ) ) ),
		'options' => [
			'off'     => esc_html__( 'None', 'cpseo' ),
			'product' => esc_html__( 'Product', 'cpseo' ),
		],
		'default' => Helper::get_settings( "titles.cpseo_pt_{$post_type}_default_rich_snippet" ),
	]);

	return;
}

$cmb->add_field([
	'id'      => 'cpseo_rich_snippet',
	'type'    => 'select',
	'name'    => esc_html__( 'Rich Snippet Type', 'cpseo' ),
	/* translators: link to title setting screen */
	'desc'    => sprintf( wp_kses_post( __( 'Rich Snippets help you stand out in SERPs.', 'cpseo' ) ) ),
	'options' => Helper::choices_rich_snippet_types( esc_html__( 'None', 'cpseo' ) ),
	'default' => Helper::get_settings( "titles.cpseo_pt_{$post_type}_default_rich_snippet" ),
]);

// Common fields.
$cmb->add_field([
	'id'         => 'cpseo_snippet_shortcode',
	'name'       => esc_html__( 'Shortcode', 'cpseo' ),
	'type'       => 'text',
	'desc'       => esc_html__( 'Copy & paste this shortcode in the content.', 'cpseo' ),
	'dep'        => [ [ 'cpseo_rich_snippet', 'off,article,review', '!=' ] ],
	'attributes' => [
		'readonly' => 'readonly',
		'value'    => '[cpseo_rich_snippet]',
	],
]);

$cmb->add_field([
	'id'         => 'cpseo_snippet_name',
	'type'       => 'text',
	'name'       => esc_html__( 'Headline', 'cpseo' ),
	'dep'        => [ [ 'cpseo_rich_snippet', 'off', '!=' ] ],
	'attributes' => [ 'placeholder' => Helper::get_settings( "titles.cpseo_pt_{$post_type}_default_snippet_name", '' ) ],
	'classes'    => 'cpseo-supports-variables',
]);

$cmb->add_field([
	'id'         => 'cpseo_snippet_desc',
	'type'       => 'textarea',
	'name'       => esc_html__( 'Description', 'cpseo' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
		'placeholder'     => Helper::get_settings( "titles.cpseo_pt_{$post_type}_default_snippet_desc", '' ),
	],
	'classes'    => 'cpseo-supports-variables',
	'dep'        => [ [ 'cpseo_rich_snippet', 'off,book,local', '!=' ] ],
]);

$cmb->add_field([
	'id'         => 'cpseo_snippet_url',
	'type'       => 'text_url',
	'name'       => esc_html__( 'URL', 'cpseo' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
		'data-rule-url'   => true,
	],
	'classes'    => 'cpseo-validate-field',
	'dep'        => [ [ 'cpseo_rich_snippet', 'book,event,local,music' ] ],
]);

$cmb->add_field([
	'id'         => 'cpseo_snippet_author',
	'type'       => 'text',
	'name'       => esc_html__( 'Author', 'cpseo' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
	],
	'dep'        => [ [ 'cpseo_rich_snippet', 'book' ] ],
]);

include_once 'article.php';
include_once 'book.php';
include_once 'course.php';
include_once 'event.php';
include_once 'job-posting.php';
include_once 'local.php';
include_once 'music.php';
include_once 'product.php';
include_once 'recipe.php';
include_once 'restaurant.php';
include_once 'video.php';
include_once 'person.php';
include_once 'review.php';
include_once 'software.php';
include_once 'service.php';