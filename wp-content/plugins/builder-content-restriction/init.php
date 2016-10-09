<?php
/*
Plugin Name:  Builder Content Restriction
Plugin URI:   http://themify.me/addons/builder-content-restriction
Version:      1.0.4
Description:  Restrict modules and rows for specific user roles. With this addon enabled, you will see the restriction checkboxes in Row and Module option lightbox. It requires to use with a Themify theme (framework 2.1.2+) or the Builder plugin (v 1.3.1+).
Author:       Themify
Author URI:   http://themify.me/
Text Domain:  builder-content-restriction
Domain Path:  /languages

*/

class Builder_Content_Restriction {

	private static $instance = null;
	var $version;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		return null == self::$instance ? self::$instance = new self : self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @access 	private
	 * @return 	void
	 */
	private function __construct() {
		$this->constants();
		add_filter( 'themify_builder_row_fields_options', array( $this, 'add_row_user_role_option' ) );
		add_filter( 'themify_builder_module_settings_fields', array( $this, 'add_module_user_role_option' ), 10, 2 );
		add_action( 'init', array( $this, 'updater' ) );

		// Front end control
		add_filter( 'themify_builder_module_display', array( $this, 'control_module_output' ), 10, 3 );
		add_filter( 'themify_builder_row_display', array( $this, 'control_row_output' ), 10, 2 );
		add_filter( 'themify_builder_module_classes', array( $this, 'module_classes' ), 10, 4 );
		add_filter( 'themify_builder_row_classes', array( $this, 'row_classes' ), 10, 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
	}

	/**
	 * Append user role option to row module.
	 * 
	 * @param	array $options
	 * @param	array $module_name
	 * @access 	public
	 * @return 	array 
	 */
	function add_row_user_role_option( $options ) {	

		// In case of row module make type radio because row module doesn't support checkbox option type.
		$user_role_option =	array(
			'id' => 'row_user_role',
			'type' => 'checkbox',
			'label' => __( 'User Role', 'builder-content-restriction' ),
			'options' => array(
				array( 'name' => '_cr_logged_in', 'value' => __( 'Logged in users', 'builder-content-restriction' ) ),
				array( 'name' => '_cr_logged_out', 'value' => __( 'Logged out users', 'builder-content-restriction' ) ),
			),
			'before' => '<small>' . __( 'Check the user role(s) you want to show this content. Default is visible to everyone.', 'builder-content-restriction' ) . '</small>'
		);		

		foreach ( $this->get_roles() as $role => $details ) {
			$name = translate_user_role( strtolower( $details['name'] ) );
			$user_role_option['options'][] = array( 'name' => $role, 'value' => $name );
		}
		
		$options[] = $user_role_option;
		
		return $options;
	}

	/**
	 * Append user role option to modules.
	 * 
	 * @param	array $options
	 * @param	array $module_name
	 * @access 	public
	 * @return 	array
	 */
	function add_module_user_role_option( $options, $module ) {		

		// In case of modules we can use checkbox option type because they support that type.
		$user_role_option = array(
			'id' => $module->slug . '_user_role',
			'type' => 'checkbox',
			'label' => __( 'User Role', 'builder-content-restriction' ),
			'options' => array(
				array( 'name' => '_cr_logged_in', 'value' => __( 'Logged in users', 'builder-content-restriction' ) ),
				array( 'name' => '_cr_logged_out', 'value' => __( 'Logged out users', 'builder-content-restriction' ) ),
			),
			'before' => '<small>' . __( 'Check the user role(s) you want to show this content. Default is visible to everyone.', 'builder-content-restriction' ) . '</small>'
		);

		foreach ( $this->get_roles() as $role => $details ) {
			$name = translate_user_role( strtolower( $details['name'] ) );
			$user_role_option['options'][] = array( 'name' => $role, 'value' => $name );
		}

		$options[] = $user_role_option;

		return $options;
	}
	
	/**
	 * Get roles
	 * @access 	private
	 * @return 	array Return array of all registered roles.
	 */
	public function get_roles() {
		$roles = get_editable_roles();

		return $roles;
	}
	
	/**
	 * Get current roles
	 * @access 	private
	 * @return 	array Array with curent user roles.
	 */
	public function get_current_user_roles() {
		$current_user_roles = array();
		$current_user = wp_get_current_user();
		$current_user_roles = $current_user->roles;

		return $current_user_roles;
	}
	
	/**
	 * Control front end display of modules.
	 * @access 	public
	 * @return 	array
	 */
	function control_module_output( $display, $mod, $builder_id ) {

		// Check weather the front end editor is active
		if( $this->is_preview() ) {
			return true;
		}

		// Stop work if settings for module is empty. (I think it can happen but check anyway)
		if( ! isset( $mod['mod_settings'] ) ) {
			return true;
		}

		$mod_settings = $mod['mod_settings'];
		$module_name = $mod['mod_name'];
		$role_settings_key = $module_name.'_user_role';

		// Check weather role restriction option exists for a given module or not.
		if( ! array_key_exists( $role_settings_key, $mod_settings ) ) {
			return true;
		}

		// Check weather role restriction was set by user or not.
		if( '|' != $mod_settings[$role_settings_key]  ) {
			$allowed_roles = explode( '|',  $mod_settings[$role_settings_key] );

			if( ( in_array( '_cr_logged_in', $allowed_roles ) && is_user_logged_in() ) || ( in_array( '_cr_logged_out', $allowed_roles ) && ! is_user_logged_in() ) ) {
				return true;
			}

			// Compare current user roles with allowed ones. If there is a match allow module appear on front end.
			$comparision = array_intersect( $allowed_roles, $this->get_current_user_roles() );
	
			// If there is no match it means that for current user module shouldn't be shown. 
			if( empty( $comparision ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Control front end display of row module.
	 * @access 	public
	 * @return 	array
	 */
	function control_row_output( $display, $row ) {

		// Check weather the front end editor is active
		if( $this->is_preview() ) {
			return true;
		}

		// Stop work if settings for row is empty ie. wasn't set. 
		if( ! isset( $row['styling'] ) ) {
			return true;
		}

		$mod_settings = $row['styling'];

		// Check weather role restriction was set or not.
		if( ! empty( $mod_settings['row_user_role'] ) ) {

			// For row module radio type was used so value is string that I convert to array
			$allowed_roles = (array) $mod_settings['row_user_role'];

			if( ( in_array( '_cr_logged_in', $allowed_roles ) && is_user_logged_in() ) || ( in_array( '_cr_logged_out', $allowed_roles ) && ! is_user_logged_in() ) ) {
				return true;
			}

			// Compare current user roles with allowed ones. If there is a match allow module appear on front end.
			$comparision = array_intersect( $allowed_roles, $this->get_current_user_roles() );

			// If there is no match it means that for current user module shouldn't be shown. 
			if( empty( $comparision ) ) {
				return false;
			}
		}

		return true;
	}

	public function module_classes( $classes, $mod_name = null, $module_ID = null, $args = null ) {
		if( $this->is_preview() ) {
			if( isset( $args["{$mod_name}_user_role"] ) ) {
				$roles = array_filter( explode( '|', $args["{$mod_name}_user_role"] ) );
				if( ! empty( $roles ) ) {
					$classes[] = 'has-restriction';
				}
			}
		}

		return $classes;
	}

	public function row_classes( $classes, $row, $builder_id ) {
		if( $this->is_preview() ) {
			if( isset( $row['styling']['row_user_role'] ) && ! empty( $row['styling']['row_user_role'] ) ) {
				$classes[] = 'has-restriction';
			}
		}

		return $classes;
	}

	public function is_preview() {
		return class_exists( 'Themify_Builder_Model' ) && Themify_Builder_Model::is_frontend_editor_page();
	}

	public function enqueue() {
		if( $this->is_preview() ) {
			wp_enqueue_style( 'builder-content-restriction', plugins_url( 'assets/style.css', __FILE__ ) );
		}
	}

	public function updater() {
		if( class_exists( 'Themify_Builder_Updater' ) ) {
			if ( ! function_exists( 'get_plugin_data') ) 
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
			$plugin_basename = plugin_basename( __FILE__ );
			$plugin_data = get_plugin_data( trailingslashit( plugin_dir_path( __FILE__ ) ) . basename( $plugin_basename ) );
			new Themify_Builder_Updater( array(
				'name' => trim( dirname( $plugin_basename ), '/' ),
				'nicename' => $plugin_data['Name'],
				'update_type' => 'addon',
			), $this->version, trim( $plugin_basename, '/' ) );
		}
	}
}
Builder_Content_Restriction::get_instance();