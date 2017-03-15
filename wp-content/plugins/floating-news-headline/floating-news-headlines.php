<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
@package Floating News Headline
Plugin Name: Floating News Headline 
Plugin URI: http://awplife.com/
Description: Floating news headline plugin with preview for Wordpress
Version: 0.0.1
Author: A WP Life
Author URI: http://awplife.com/
Text Domain: FHP_TXTDM
Domain Path: /languages
*/

if ( ! class_exists( 'Floating_Headline' ) ) {

	class Floating_Headline {
		
		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}
		
		protected function _constants() {
			//Plugin Version
			define( 'FH_PLUGIN_VER', '3.0' );
			
			//Plugin Text Domain
			define("FHP_TXTDM","floating-headline" );
 
			//Plugin Name
			define( 'FH_PLUGIN_NAME', __( 'Floating News Headline', FHP_TXTDM ) );

			//Plugin Slug
			define( 'FH_PLUGIN_SLUG', 'floating_headline' );

			//Plugin Directory Path
			define( 'FH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			//Plugin Directory URL
			define( 'FH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			/**
			 * Create a key for the .htaccess secure download link.
			 * @uses    NONCE_KEY     Defined in the WP root config.php
			 */
			define( 'GG_SECURE_KEY', md5( NONCE_KEY ) );
			
		} // end of constructor function
		
		
		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks() {
			
			//Load text domain
			add_action( 'plugins_loaded', array( $this, '_load_textdomain' ) );
			
			//add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, '_fh_menu' ), 65 );
			
			//Create grid Gallery Custom Post
			add_action( 'init', array( $this, '_Floting_Headline' ));
			
			//Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, '_admin_add_meta_box' ) );
			
			add_action('save_post', array(&$this, '_fh_save_settings'));

			//Shortcode Compatibility in Text Widgets
			add_filter('widget_text', 'do_shortcode');

		} // end of hook function
		public function _load_textdomain() {
			load_plugin_textdomain( 'FHP_TXTDM', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
		
		public function _fh_menu() {
			$help_menu = add_submenu_page( 'edit.php?post_type='.FH_PLUGIN_SLUG, __( 'Docs', FHP_TXTDM ), __( 'Docs', FHP_TXTDM ), 'administrator', 'ak-doc-page', array( $this, '_fh_doc_page') );
		}
	
		
		/**
		 * Floating Headline Custom Post
		 * Create Floating Headline post type in admin dashboard.
		*/
		public function _Floting_Headline(){
			$labels = array(
				'name'                => _x( 'Floating Headline', 'Post Type General Name', FHP_TXTDM ),
				'singular_name'       => _x( 'Floating Headline', 'Post Type Singular Name', FHP_TXTDM ),
				'menu_name'           => __( 'Floating Headline', FHP_TXTDM ),
				'name_admin_bar'      => __( 'Floating Headline', FHP_TXTDM ),
				'parent_item_colon'   => __( 'Parent Item:', FHP_TXTDM ),
				'all_items'           => __( 'All Floating Headline', FHP_TXTDM ),
				'add_new_item'        => __( 'Add Floating Headline', FHP_TXTDM ),
				'add_new'             => __( 'Add Floating Headline', FHP_TXTDM ),
				'new_item'            => __( 'Floating Headline', FHP_TXTDM ),
				'edit_item'           => __( 'Edit Floating Headline', FHP_TXTDM ),
				'update_item'         => __( 'Update Floating Headline', FHP_TXTDM ),
				'search_items'        => __( 'Search Floating Headline', FHP_TXTDM ),
				'not_found'           => __( 'Floating Headline Not found', FHP_TXTDM ),
				'not_found_in_trash'  => __( 'Floating Headline Not found in Trash', FHP_TXTDM ),
			);
			$args = array(
				'label'               => __( 'Floating Headline', 'FHP_TXTDM' ),
				'description'         => __( 'Custom Post Type For Floating Headline', 'FHP_TXTDM' ),
				'labels'              => $labels,
				'supports'            => array( 'title'),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-testimonial',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,		
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'floating_headline', $args );
			
		} // end of post type function
		 /* Adds Meta Boxes
		*/
		public function _admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( '', __('Add Headline', FHP_TXTDM), array(&$this, 'fh_upload'), 'floating_headline', 'normal', 'default' );
		}
		
		public function fh_upload($post) { 
			wp_enqueue_script('jquery');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('fh-uploader.js', FH_PLUGIN_URL . 'js/fh-uploader.js', array('jquery'));
			wp_enqueue_style('fh-uploader-css', FH_PLUGIN_URL . 'css/fh-uploader.css');
			wp_enqueue_media();
			
			wp_enqueue_style( 'wp-color-picker' );
			//wp_enqueue_script( 'fh-color-picker-js',  FH_PLUGIN_URL .'js/fh-color-picker.js', array( 'jquery' ), '', true  );
			wp_enqueue_script( 'fh-color-picker-js', plugins_url('js/fh-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			?>
			<h1><?php _e('Copy Floating Headline Shortcode', FHP_TXTDM); ?></h1>
			<hr>
			<p class="input-text-wrap">
				<p><?php _e('Copy & Embed shotcode into any Page/ Post / Text Widget to display your Headline on site.', FHP_TXTDM); ?><br></p>
				<input type="text" name="shortcode" id="shortcode" value="<?php echo "[FHS id=".$post->ID."]"; ?>" readonly style="height: 60px; text-align: center; font-size: 24px; width: 25%; border: 2px dashed;" onmouseover="return pulseOff();" onmouseout="return pulseStart();">
			</p>
			<br>
			<br>
			<h1><?php _e('Floating Headline', FHP_TXTDM); ?></h1>
			<hr>
			<?php
			require_once('floating-headline-setting.php');
		}
		
		public function _fh_save_settings($post_id) {
			if(isset($_POST['fh_save_nonce'])) {
				if ( !isset( $_POST['fh_save_nonce'] ) || !wp_verify_nonce( $_POST['fh_save_nonce'], 'fh_save_settings' ) ) {
				   print 'Sorry, your nonce did not verify.';
				   exit;
				} else {
					$awl_fh_shortcode_setting = "awl_fh_settings_".$post_id;
					update_post_meta($post_id, $awl_fh_shortcode_setting, base64_encode(serialize($_POST)));
				}	
			}
		}// end save setting
		
		public function _fh_doc_page(){
			require_once('docs.php');
		}
	}
	
	
	/**
	 * Instantiates the Class
	 */
	$fh_object = new Floating_Headline();
	require_once('floating-headline-shortcode.php');
}// end of class
?>