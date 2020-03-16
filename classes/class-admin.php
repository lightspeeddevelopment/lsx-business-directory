<?php
namespace lsx\business_directory\classes;

/**
 * LSX Starter Plugin Admin Class.
 *
 * @package lsx-business-directory
 */
class Admin {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\Admin()
	 */
	protected static $instance = null;

	/**
	 * Holds the admin banner actions and filters.
	 *
	 * @var object \lsx\business_directory\classes\admin\Banners();
	 */
	public $banners;

	/**
	 * Holds the admin the post type singles.
	 *
	 * @var object \lsx\business_directory\classes\admin\Archive();
	 */
	public $archive;

	/**
	 * Holds the admin for the post type archives.
	 *
	 * @var object \lsx\business_directory\classes\admin\Single();
	 */
	public $single;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		// Enqueue scripts for all admin pages.
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		// Configure Settings page.
		add_action( 'cmb2_admin_init', array( $this, 'configure_settings_fields' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\member_directory\classes\Admin()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {
		// Load plugin admin related functionality.
		require_once LSX_BD_PATH . 'classes/admin/class-banners.php';
		$this->banners = admin\Banners::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-term-thumbnail.php';
		$this->term_thumbnail = admin\Term_Thumbnail::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-archive.php';
		$this->archive = admin\Archive::get_instance();

		require_once LSX_BD_PATH . 'classes/admin/class-single.php';
		$this->single = admin\Single::get_instance();
	}

	/**
	 * Various assest we want loaded for admin pages.
	 *
	 * @return void
	 */
	public function assets() {
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'lsx-business-directory-admin', LSX_BD_URL . 'assets/js/lsx-business-directory-admin.min.js', array( 'jquery' ), LSX_BD_VER, true );
		wp_enqueue_style( 'lsx-business-directory-admin', LSX_BD_URL . 'assets/css/lsx-business-directory-admin.css', array(), LSX_BD_VER );
	}
	/**
	 * Configure Business Directory custom fields for the Settings page.
	 *
	 * @return void
	 */
	public function configure_settings_fields() {
		$this->cmb = new_cmb2_box(
			array(
				'id'           => 'lsx_bd_settings',
				'title'        => esc_html__( 'Business Directory Settings', 'lsx-business-directory' ),
				'menu_title'   => esc_html__( 'Settings', 'lsx-business-directory' ), // Falls back to 'title' (above).
				'object_types' => array( 'options-page' ),
				'option_key'   => 'lsx-business-directory-settings', // The option key and admin menu page slug.
				'parent_slug'  => 'edit.php?post_type=business-directory', // Make options page a submenu item of the Business Directory menu.
				'capability'   => 'manage_options', // Cap required to view options-page.
			)
		);
		do_action( 'lsx_bd_settings_page', $this->cmb );
	}
}
