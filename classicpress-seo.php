<?php
/**
 * ClassicPress SEO Plugin.
 *
 *
 * Plugin Name: ClassicPress SEO
 * Plugin URI:  https://www.classicpress.net
 * Description: SEO solution for ClassicPress (experimental).
 * Version:     0.2.2
 * Author:      ClassicPress Community
 * Author URI:  https://www.classicpress.net
 * GitHub Plugin URI: https://github.com/ClassicPress-research/classicpress-seo
 * Text Domain: cpseo
 * Domain Path: /languages/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 3.0
 * WC tested up to: 3.7
 *
 * @package   ClassicPress_SEO
 */
 

defined( 'ABSPATH' ) || exit;



/**
 * ClassicPress_SEO class.
 *
 * @class Main plugin class
 */
class ClassicPress_SEO {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '0.2.2';

	/**
	 * ClassicPress SEO database version.
	 *
	 * @var string
	 */
	public $db_version = '1';

	/**
	 * Minimum version of ClassicPress required to run ClassicPress SEO.
	 *
	 * @var string
	 */
	private $classicpress_version = '1.0.2';

	/**
	 * Minimum version of PHP required to run ClassicPress SEO.
	 *
	 * @var string
	 */
	private $php_version = '7.0';
	
	/**
	 * Holds various class instances.
	 *
	 * @var array
	 */
	private $container = [];
	
	/**
	 * Hold install error messages.
	 *
	 * @var bool
	 */
	private $messages = [];
	
	/**
	 * The single instance of the class.
	 *
	 * @var ClassicPress_SEO
	 */
	protected static $instance = null;
	
	/**
	 * Magic isset to bypass referencing plugin.
	 *
	 * @param  string $prop Property to check.
	 * @return bool
	 */
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}
	
	/**
	 * Magic getter method.
	 *
	 * @param  string $prop Property to get.
	 * @return mixed Property value or NULL if it does not exists.
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		if ( isset( $this->{$prop} ) ) {
			return $this->{$prop};
		}

		return null;
	}
	
	/**
	 * Magic setter method.
	 *
	 * @param mixed $prop  Property to set.
	 * @param mixed $value Value to set.
	 */
	public function __set( $prop, $value ) {
		if ( property_exists( $this, $prop ) ) {
			$this->$prop = $value;
			return;
		}

		$this->container[ $prop ] = $value;
	}

	/**
	 * Magic call method.
	 *
	 * @param  string $name      Method to call.
	 * @param  array  $arguments Arguments to pass when calling.
	 * @return mixed Return value of the callback.
	 */
	public function __call( $name, $arguments ) {
		$hash = [
			'plugin_dir'   => CPSEO_PATH,
			'plugin_url'   => CPSEO_PLUGIN_URL,
			'includes_dir' => CPSEO_PATH . 'includes/',
			'assets'       => CPSEO_PLUGIN_URL . 'assets/front/',
			'admin_dir'    => CPSEO_PATH . 'includes/admin/',
		];

		if ( isset( $hash[ $name ] ) ) {
			return $hash[ $name ];
		}

		return call_user_func_array( $name, $arguments );
	}
	
	/**
	 * Initialize.
	 */
	public function init() {
		
	}
	
	/**
	 * Retrieve main ClassicPress_SEO instance.
	 *
	 * Ensure only one instance is loaded or can be loaded.
	 *
	 * @see ClassicPress_SEO()
	 * @return ClassicPress_SEO
	 */
	public static function get() {
		if ( is_null( self::$instance ) && ! ( self::$instance instanceof ClassicPress_SEO ) ) {
			self::$instance = new ClassicPress_SEO();
			self::$instance->setup();
		}

		return self::$instance;
	}
	
	/**
	 * Instantiate
	 */
	private function setup() {
		if ( ! $this->requirements() ) {
			return;
		}

		// Define plugin constants.
		$this->define_constants();

		// Include required files.
		$this->includes();
		
		// Instantiate classes.
		$this->instantiate();

		// Initialize the action and filter hooks.
		$this->init_actions();

		// Loaded action.
		do_action( 'cpseo/loaded' );
	}
	
	/**
	 * Check that the ClassicPress and PHP setup meets the plugin requirements.
	 *
	 * @return bool
	 */
	private function requirements() {
	
		// Check ClassicPress version.
		if ( version_compare( get_bloginfo( 'version' ), $this->classicpress_version, '<' ) ) {
			$this->messages[] = sprintf( esc_html__( 'ClassicPress SEO requires ClassicPress version %s or above. Please update ClassicPress.', 'cpseo' ), $this->classicpress_version );
		}

		// Check PHP version.
		if ( version_compare( phpversion(), $this->php_version, '<' ) ) {
			$this->messages[] = sprintf( esc_html__( 'ClassicPress SEO requires PHP version %s or above. Please update PHP.', 'cpseo' ), $this->php_version );
		}

		if ( empty( $this->messages ) ) {
			return true;
		}

		// Auto-deactivate plugin.
		add_action( 'admin_init', [ $this, 'auto_deactivate' ] );
		add_action( 'admin_notices', [ $this, 'activation_error' ] );

		return false;
	}
	
	/**
	 * Auto-deactivate plugin if requirements are not met, and display a notice.
	 */
	public function auto_deactivate() {
		deactivate_plugins( plugin_basename( CPSEO_FILE ) );
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	/**
	 * Error notice on plugin activation.
	 */
	public function activation_error() {
		?>
		<div class="notice notice-error">
			<p><?php echo join( '<br>', $this->messages ); ?></p>
		</div>
		<?php
	}

	/**
	 * Define the plugin constants.
	 */
	private function define_constants() {
		define( 'CPSEO_VERSION', $this->version );
		define( 'CPSEO_DB_VERSION', $this->db_version );
		define( 'CPSEO_MINIMUM_PHP_VERSION', $this->php_version );
		define( 'CPSEO_FILE', __FILE__ );
		define( 'CPSEO_PATH', plugin_dir_path( CPSEO_FILE ) );
		define( 'CPSEO_BASENAME', plugin_basename( CPSEO_FILE ) );
		define( 'CPSEO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Include the required files.
	 */
	private function includes() {
		include dirname( __FILE__ ) . '/vendor/autoload.php';		
	}

	/**
	 * Instantiate classes.
	 */
	private function instantiate() {
		new \ClassicPress_SEO\Installer;

		// Setting Manager.
		$this->container['settings'] = new \ClassicPress_SEO\Settings;

		// JSON Manager.
		$this->container['json'] = new \ClassicPress_SEO\Json_Manager;

		// Notification Manager.
		$this->container['notification'] = new \ClassicPress_SEO\Notification_Center( 'cpseo_notifications' );
		$this->container['manager'] = new \ClassicPress_SEO\Module\Manager;

		// Just init without storing it in the container.
		new \ClassicPress_SEO\Common;
		
		$this->container['rewrite'] = new \ClassicPress_SEO\Rewrite;
	}

	/**
	 * Initialize ClassicPress action and filter hooks.
	 */
	private function init_actions() {

		add_action( 'init', [ $this, 'localization_setup' ] );

		// Add plugin action links.
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( CPSEO_FILE ), [ $this, 'plugin_action_links' ] );

		// Booting.
		add_action( 'plugins_loaded', [ $this, 'init' ], 14 );
		add_action( 'rest_api_init', [ $this, 'init_rest_api' ] );

		// Load admin-related functionality.
		if ( is_admin() ) {
			add_action( 'plugins_loaded', [ $this, 'init_admin' ], 15 );
		}
		
		// Frontend-only functionality.
		if ( ! is_admin() || in_array( \ClassicPress_SEO\Helpers\Param::request( 'action' ), [ 'elementor', 'elementor_ajax' ], true ) ) {
			add_action( 'plugins_loaded', [ $this, 'init_frontend' ], 15 );
		}
		
		// WP_CLI.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			add_action( 'plugins_loaded', [ $this, 'init_wp_cli' ], 20 );
		}

	}

	/**
	 * Load the REST API endpoints.
	 */
	public function init_rest_api() {
		$controllers = [
			new \ClassicPress_SEO\Rest\Admin,
			new \ClassicPress_SEO\Rest\Front,
		];

		foreach ( $controllers as $controller ) {
			$controller->register_routes();
		}
	}

	/**
	 * Initialize the admin-related functionality.
	 * Runs on 'plugins_loaded'.
	 */
	public function init_admin() {
		new \ClassicPress_SEO\Admin\Engine;
	}
	
	
	/**
	 * Initialize the frontend functionality.
	 * Runs on 'plugins_loaded'.
	 */
	public function init_frontend() {
		$this->container['frontend'] = new \ClassicPress_SEO\Frontend\Frontend;
	}

	/**
	 * Add our custom WP-CLI commands.
	 */
	public function init_wp_cli() {
		WP_CLI::add_command( 'cpseo sitemap generate', [ '\ClassicPress_SEO\CLI\Commands', 'sitemap_generate' ] );
	}


	/**
	 * Show action links on the plugin screen.
	 *
	 * @param  mixed $links Plugin Action links.
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links = [
			'<a href="' . ClassicPress_SEO\Helper::get_admin_url( 'options-general' ) . '">' . esc_html__( 'Settings', 'cpseo' ) . '</a>',
		];

		return array_merge( $links, $plugin_links );
	}

	/**
	 * Add extra links as row meta on the plugin screen.
	 *
	 * @param  mixed $links Plugin Row Meta.
	 * @param  mixed $file  Plugin Base file.
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( plugin_basename( CPSEO_FILE ) !== $file ) {
			return $links;
		}

		return $links;
	}


	/**
	 * Initialize plugin for localization.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *     - WP_LANG_DIR/plugins/cpseo-LOCALE.mo
	 *     - WP_LANG_DIR/classicpress-seo/cpseo-LOCALE.mo
	 */
	public function localization_setup() {
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'cpseo' );

		unload_textdomain( 'cpseo' );
		if ( false === load_textdomain( 'cpseo', WP_LANG_DIR . '/plugins/cpseo-' . $locale . '.mo' ) ) {
			load_textdomain( 'cpseo', WP_LANG_DIR . '/classicpress-seo/cpseo-' . $locale . '.mo' );
		}
		load_plugin_textdomain( 'cpseo', false, cpseo()->plugin_dir() . 'languages/' );

		if ( is_user_logged_in() ) {
			$this->container['json']->add( 'version', $this->version, 'classicPress' );
			$this->container['json']->add( 'ajaxurl', admin_url( 'admin-ajax.php' ), 'classicPress' );
			$this->container['json']->add( 'adminurl', admin_url( 'admin.php' ), 'classicPress' );
			$this->container['json']->add( 'security', wp_create_nonce( 'cpseo-ajax-nonce' ), 'classicPress' );
		}
	}

}


/**
 * Returns the main instance of ClassicPress_SEO to prevent the need to use globals.
 *
 * @return ClassicPress_SEO
 */
function cpseo() {
	return ClassicPress_SEO::get();
}

// Let's go.
cpseo();
