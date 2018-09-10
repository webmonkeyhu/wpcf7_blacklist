<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://webmonkey.hu
 * @since      1.0.0
 *
 * @package    WPCF7_Blacklist
 * @subpackage WPCF7_Blacklist/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WPCF7_Blacklist
 * @subpackage WPCF7_Blacklist/public
 * @author     Webmonkey Solutions Kft. <hello@webmonkey.hu>
 */
class WPCF7_Blacklist_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wpcf7_blacklist    The ID of this plugin.
	 */
	private $wpcf7_blacklist;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wpcf7_blacklist       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wpcf7_blacklist, $version ) {

		$this->wpcf7_blacklist = $wpcf7_blacklist;
		$this->version = $version;

		add_filter('wpcf7_validate_email*', array(__CLASS__, 'validation_filter'), 20, 2 );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->wpcf7_blacklist, plugin_dir_url( __FILE__ ) . 'css/wpcf7-blacklist-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->wpcf7_blacklist, plugin_dir_url( __FILE__ ) . 'js/wpcf7-blacklist-public.js', array( 'jquery' ), $this->version, false );
	}

	public static function validation_filter($result, $tag) {
		if (!($tag->basetype == 'email' && !in_array('blacklist-disable', $tag->options))) {
			return $result;
		}

		$email = $_POST[$tag->name];
		$options = get_post_meta($_POST['_wpcf7'], 'wpcf7_blacklist_options', true);

		if (!isset($options['emails']) || empty($options['emails'])) {
			return $result;
		}

		$blacklist = self::parseBlacklist(
			$options['emails']
		);

		if (preg_match('/(?:'. implode('|', $blacklist) .')$/m', $email)) {
			$result->invalidate( $tag, __($options["message_blocked"], 'wpcf7-blacklist') );
		}

		return $result;
	}

	public static function parseBlacklist($raw) {
		$blacklist = preg_split('/\r\n|\r|\n/', $raw);

		return array_filter($blacklist);
	}

}
