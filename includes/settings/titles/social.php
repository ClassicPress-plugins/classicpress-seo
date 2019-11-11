<?php
/**
 * The social settings.
 *
 * @package    ClassicPress_SEO
 * @subpackage ClassicPress_SEO\Settings
 */

$cmb->add_field([
	'id'   => 'cpseo_social_url_facebook',
	'type' => 'text',
	'name' => esc_html__( 'Facebook Page URL', 'cpseo' ),
	'desc' => esc_html__( 'Enter your complete Facebook page URL here. eg:', 'cpseo' ) .
		'<br><code>' . htmlspecialchars( 'https://www.facebook.com/ClassicPress_SEO/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'cpseo_facebook_author_urls',
	'type' => 'text',
	'name' => esc_html__( 'Facebook Authorship', 'cpseo' ),
	'desc' => esc_html__( 'Insert personal Facebook profile URL to show Facebook Authorship when your articles are being shared on Facebook. eg:', 'cpseo' ) .
		'<br><code>' . htmlspecialchars( 'https://www.facebook.com/somebody' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'cpseo_facebook_admin_id',
	'type' => 'text',
	'name' => esc_html__( 'Facebook Admin', 'cpseo' ),
	/* translators: numeric user ID link */
	'desc' => sprintf( esc_html__( 'Enter %s. Use a comma to separate multiple IDs. Alternatively, you can enter an app ID below.', 'cpseo' ), '<a href="https://findmyfbid.com/?utm_campaign=ClassicPress+SEO" target="_blank">numeric user ID</a>' ),
]);

$cmb->add_field([
	'id'   => 'cpseo_facebook_app_id',
	'type' => 'text',
	'name' => esc_html__( 'Facebook App', 'cpseo' ),
	/* translators: numeric app ID link */
	'desc' => sprintf( esc_html__( 'Enter %s. Alternatively, you can enter a user ID above.', 'cpseo' ), '<a href="https://developers.facebook.com/apps?utm_campaign=ClassicPress+SEO" target="_blank">numeric app ID</a>' ),
]);

$cmb->add_field([
	'id'         => 'cpseo_facebook_secret',
	'type'       => 'text',
	'name'       => esc_html__( 'Facebook Secret', 'cpseo' ),
	'attributes' => [ 'type' => 'password' ],
]);

$cmb->add_field([
	'id'   => 'cpseo_social_url_twitter',
	'type' => 'text',
	'name' => esc_html__( 'Twitter Profile URL', 'cpseo' ),
	'desc' => esc_html__( 'Enter your complete Twitter Profile URL here. eg:', 'cpseo' ) .
		'<br><code>' . htmlspecialchars( 'https://twitter.com/ClassicPress_SEO/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'cpseo_twitter_author_names',
	'type' => 'text',
	'name' => esc_html__( 'Twitter Username', 'cpseo' ),
	'desc' => wp_kses_post( __( 'Enter the Twitter username of the author to add <code>twitter:creator</code> tag to posts. eg: <code>ClassicPress_SEO</code>', 'cpseo' ) ),
]);

$cmb->add_field([
	'id'   => 'cpseo_social_url_google_places',
	'type' => 'text',
	'name' => esc_html__( 'Google Places', 'cpseo' ),
	/* translators: How to find it? link */
	'desc' => sprintf( esc_html__( 'Enter full URL of your Google Places listing here. %s', 'cpseo' ), '<a href="https://developers.google.com/maps/documentation/urls/guide?utm_campaign=ClassicPress+SEO" target="_blank">How to find it?</a>' ),
]);

$cmb->add_field([
	'id'   => 'cpseo_social_url_linkedin',
	'type' => 'text',
	'name' => esc_html__( 'LinkedIn Page URL', 'cpseo' ),
	'desc' => wp_kses_post( __( 'Enter your LinkedIn profile URL (for personal blogs) or your company URL (for business blogs). eg:', 'cpseo' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.linkedin.com/company/classicpress/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'cpseo_social_url_instagram',
	'type' => 'text',
	'name' => esc_html__( 'Instagram Page URL', 'cpseo' ),
	'desc' => wp_kses_post( __( 'Enter your Instagram profile URL here. e.g: ', 'cpseo' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.instagram.com/mkbhd/' ) . '</code>',
]);

$cmb->add_field([
	'id'   => 'cpseo_social_url_youtube',
	'type' => 'text',
	'name' => esc_html__( 'Youtube Channel URL', 'cpseo' ),
	'desc' => wp_kses_post( __( 'Enter your YouTube Channel\'s URL here. e.g', 'cpseo' ) ) .
		'<br><code>' . htmlspecialchars( 'https://www.youtube.com/channel/UC2t2B_nKC5jg1Ix5rU0Bz7A' ) . '</code>',
]);

