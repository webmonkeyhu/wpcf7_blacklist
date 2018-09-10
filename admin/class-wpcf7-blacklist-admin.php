<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webmonkey.hu
 * @since      1.0.0
 *
 * @package    WPCF7_Blacklist
 * @subpackage WPCF7_Blacklist/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPCF7_Blacklist
 * @subpackage WPCF7_Blacklist/admin
 * @author     Webmonkey Solutions Kft. <hello@webmonkey.hu>
 */
class WPCF7_Blacklist_Admin {

	const MESSAGE_BLOCKED = "Your mail server is blocked.";

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
	 * @param      string    $wpcf7_blacklist       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wpcf7_blacklist, $version ) {

		$this->wpcf7_blacklist = $wpcf7_blacklist;
		$this->version = $version;

		add_filter('wpcf7_editor_panels', array(__CLASS__, 'add_conditional_panel'));
		add_action('wpcf7_save_contact_form', array(__CLASS__, 'save_list'), 10, 1);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->wpcf7_blacklist, plugin_dir_url( __FILE__ ) . 'css/wpcf7-blacklist-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->wpcf7_blacklist, plugin_dir_url( __FILE__ ) . 'js/wpcf7-blacklist-admin.js', array( 'jquery' ), $this->version, false );
	}

	public static function add_conditional_panel($panels) {
    $panels['wpcf7-blacklist-panel'] = array(
      'title' => __( 'Blacklist', 'wpcf7-blacklist' ),
      'callback' => array(__CLASS__, 'wpcf7_blacklist_editor_panel')
    );

    return $panels;
	}

	public static function wpcf7_blacklist_editor_panel($form) {
		$form_id = $_GET['post'];
		$options = get_post_meta($form_id, 'wpcf7_blacklist_options', true);

		if (!isset($options['message_blocked']) || empty($options['message_blocked'])) {
			$options['message_blocked'] = self::MESSAGE_BLOCKED;
		}

		?>
	    <div class="wpcf7-blacklist-wrapper">
	        <h3><?php echo esc_html( __( 'Email blacklist', 'wpcf7_blacklist' ) ); ?></h3>

          <textarea id="wpcf7_blacklist_options" name="wpcf7_blacklist_options[emails]" placeholder="*@gmail.com&#10;*@yahoo.com"><?php echo $options['emails']; ?></textarea>

					<h3><?php echo esc_html( __( 'Error messages', 'wpcf7_blacklist' ) ); ?></h3>

					<p class="description">
						<label for="wpcf7_blacklist_message_blocked_email"><?php _e( 'Blocked email address', 'wpcf7_blacklist' ); ?></label>
						<input type="text" name="wpcf7_blacklist_options[message_blocked]" class="large-text" value="<?php _e($options['message_blocked'], 'wpcf7-blacklist'); ?>">
					</p>
	    </div>
	<?php
	}

	public static function save_list($form)
	{
		if (!isset($_POST) || empty($_POST) || !isset($_POST['wpcf7_blacklist_options'])) {
			return;
		}

		$post_id = $form->id();

		if (!$post_id) return;

		if (!isset($options['message_blocked']) || empty($options['message_blocked'])) {
			$options['message_blocked'] = self::MESSAGE_BLOCKED;
		}

		update_post_meta($post_id, 'wpcf7_blacklist_options', $_POST['wpcf7_blacklist_options']);

    return;
	}
}
