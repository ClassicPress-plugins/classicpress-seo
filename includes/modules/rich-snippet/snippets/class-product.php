<?php
/**
 * The Product Class.
 *
 * @since      0.1.8
 * @package    ClassicPress_SEO
 * @subpackage ClassicPress_SEO\RichSnippet

 */

namespace ClassicPress_SEO\RichSnippet;

use ClassicPress_SEO\Helper;
use ClassicPress_SEO\Helpers\Conditional;
use ClassicPress_SEO\RichSnippet\Product_Edd;
use ClassicPress_SEO\RichSnippet\Product_WooCommerce;

defined( 'ABSPATH' ) || exit;

/**
 * Product class.
 */
class Product implements Snippet {

	/**
	 * Hold JsonLD Instance.
	 *
	 * @var JsonLD
	 */
	private $json = '';

	/**
	 * Product rich snippet.
	 *
	 * @param array  $data   Array of JSON-LD data.
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public function process( $data, $jsonld ) {
		$this->json = $jsonld;
		$sku        = Helper::get_post_meta( 'snippet_product_sku' );
		$price      = Helper::get_post_meta( 'snippet_product_price' );
		$entity     = [
			'@context'    => 'https://schema.org/',
			'@type'       => 'Product',
			'sku'         => $sku ? $sku : '',
			'name'        => $jsonld->parts['title'],
			'description' => $jsonld->parts['desc'],
			'releaseDate' => $jsonld->parts['published'],
			'offers'      => [
				'@type'           => 'Offer',
				'priceCurrency'   => Helper::get_post_meta( 'snippet_product_currency' ),
				'price'           => $price ? $price : '0',
				'url'             => get_the_permalink(),
				'priceValidUntil' => Helper::get_post_meta( 'snippet_product_price_valid' ),
				'availability'    => Helper::get_post_meta( 'snippet_product_instock' ) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			],
		];

		$brand = Helper::get_post_meta( 'snippet_product_brand' );
		if ( $brand ) {
			$entity['mpn']   = $brand;
			$entity['brand'] = [
				'@type' => 'Thing',
				'name'  => $brand,
			];
		}

		if ( Conditional::is_woocommerce_active() && is_product() ) {
			remove_action( 'wp_footer', [ WC()->structured_data, 'output_structured_data' ], 10 );
			remove_action( 'woocommerce_email_order_details', [ WC()->structured_data, 'output_email_structured_data' ], 30 );
			$product = new Product_WooCommerce;
			unset( $entity['offers'] );
			$product->set_product( $entity, $jsonld );
		}

		if ( Conditional::is_edd_active() && is_singular( 'download' ) ) {
			remove_action( 'edd_purchase_link_top', 'edd_purchase_link_single_pricing_schema', 10 );
			remove_action( 'loop_start', 'edd_microdata_wrapper_open', 10 );
			$product = new Product_Edd;
			$product->set_product( $entity, $jsonld );
		}

		return $entity;
	}

	/**
	 * Get seller.
	 *
	 * @param JsonLD $jsonld JsonLD Instance.
	 *
	 * @return array
	 */
	public static function get_seller( $jsonld ) {
		$site_url = site_url();
		$type     = Helper::get_settings( 'titles.cpseo_knowledgegraph_type' );
		$seller   = [
			'@type' => 'person' === $type ? 'Person' : 'Organization',
			'@id'   => $site_url . '/',
			'name'  => $jsonld->get_website_name(),
			'url'   => $site_url,
		];

		if ( 'company' === $type ) {
			$seller['logo'] = Helper::get_settings( 'titles.cpseo_knowledgegraph_logo' );
		}

		return $seller;
	}

	/**
	 * Set product categories.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $taxonomy   Taxonomy.
	 */
	public static function get_category( $product_id, $taxonomy ) {
		$categories = get_the_terms( $product_id, $taxonomy );
		if ( is_wp_error( $categories ) || empty( $categories ) ) {
			return;
		}

		if ( 0 === $categories[0]->parent ) {
			return $categories[0]->name;
		}

		$ancestors = get_ancestors( $categories[0]->term_id, $taxonomy );
		foreach ( $ancestors as $parent ) {
			$term       = get_term( $parent, $taxonomy );
			$category[] = $term->name;
		}
		$category[] = $categories[0]->name;

		return join( ' > ', $category );
	}
}
