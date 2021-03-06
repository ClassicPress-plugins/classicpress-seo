<?php
/**
 * Metabox - Local Rich Snippet
 *
 * @package    Classic_SEO
 * @subpackage Classic_SEO\RichSnippet
 */

$local = [ [ 'cpseo_rich_snippet', 'local,restaurant' ] ];

$cmb->add_field([
	'id'   => 'cpseo_snippet_local_address',
	'type' => 'address',
	'name' => esc_html__( 'Address', 'cpseo' ),
	'dep'  => $local,
]);

$cmb->add_field([
	'id'      => 'cpseo_snippet_local_phone',
	'type'    => 'text',
	'name'    => esc_html__( 'Phone Number', 'cpseo' ),
	'classes' => 'cmb-row-33',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'         => 'cpseo_snippet_local_price_range',
	'type'       => 'text',
	'name'       => esc_html__( 'Price Range', 'cpseo' ),
	'classes'    => 'cmb-row-33 cpseo-validate-field',
	'attributes' => [
		'data-rule-regex'       => 'true',
		'data-validate-pattern' => '^\${1,4}$',
		'data-msg-regex'        => esc_html__( 'Insert $ / $$ / $$$ / $$$$.', 'cpseo' ),
	],
	'dep'        => $local,
]);

$cmb->add_field([
	'id'      => 'cpseo_snippet_local_opens',
	'type'    => 'text_time',
	'name'    => esc_html__( 'Opening Time', 'cpseo' ),
	'classes' => 'cmb-row-50',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'      => 'cpseo_snippet_local_closes',
	'type'    => 'text_time',
	'name'    => esc_html__( 'Closing Time', 'cpseo' ),
	'classes' => 'cmb-row-50',
	'dep'     => $local,
]);

$cmb->add_field([
	'id'                => 'cpseo_snippet_local_opendays',
	'type'              => 'multicheck_inline',
	'name'              => esc_html__( 'Open Days', 'cpseo' ),
	'options'           => [
		'monday'    => esc_html__( 'Monday', 'cpseo' ),
		'tuesday'   => esc_html__( 'Tuesday', 'cpseo' ),
		'wednesday' => esc_html__( 'Wednesday', 'cpseo' ),
		'thursday'  => esc_html__( 'Thursday', 'cpseo' ),
		'friday'    => esc_html__( 'Friday', 'cpseo' ),
		'saturday'  => esc_html__( 'Saturday', 'cpseo' ),
		'sunday'    => esc_html__( 'Sunday', 'cpseo' ),
	],
	'select_all_button' => false,
	'dep'               => $local,
]);
