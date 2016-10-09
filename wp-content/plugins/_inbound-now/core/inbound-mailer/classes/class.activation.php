<?php

if ( !class_exists('Inbound_Mailer_Activation') ) {

class Inbound_Mailer_Activation {

	static $version_wp;
	static $version_php;
	static $version_lp;
	static $version_leads;

    /**
     * Initiate class
     */
    public function __construct() {
        self::load_hooks();
    }

    /**
     * load supporting hooks and filters
     */
    public static function load_hooks() {
        if (!is_admin()) {
            return;
        }

        /* Make sure supporting Inbound Now plugins are activated */
        add_action( 'admin_init' , array( __CLASS__ , 'require_leads' ) );

        /* Add listener for Permalink refresh */
        add_action( 'admin_init' , array( __CLASS__ , 'flush_rules' ) );

        /* Add listener for uncompleted upgrade routines */
        add_action( 'admin_init' , array( __CLASS__ , 'run_upgrade_routine_checks' ) );

		/* add http auth support for fast cgi */
		if (php_sapi_name() == 'cgi-fcgi') {
			add_action('mod_rewrite_rules', array(__CLASS__, 'add_rewrite_rules'));
		}
    }

	public static function activate() {
		self::load_static_vars();
		self::run_version_checks();
		self::activate_plugin();
		self::run_updates();
	}

	public static function deactivate() {

	}

	public static function load_static_vars() {

		self::$version_wp = '3.6';
		self::$version_php = '5.3';
		self::$version_lp = '1.3.6';
		self::$version_leads = '1.2.1';
	}

	public static function activate_plugin() {

		/* Update DB Markers for Plugin */
		self::store_version_data();

		/* Set Default Settings */
		self::set_default_settings();

		/* Activate shared components */
		self::activate_shared();

		/* Toggle activation database flag */
		Inbound_Options_API::update_option( 'inbound-email' , 'activated' , false );
	}

	/**
	*  Rebuilds permalinks
	*/
	public static function flush_rules() {

		if ( Inbound_Options_API::get_option( 'inbound-email' , 'activated' ,  true ) ) {
			return;
		}

		/* reset permalinks */
		global $wp_rewrite;
		$wp_rewrite->flush_rules();

		/* Toggle activation database flag */
		Inbound_Options_API::update_option( 'inbound-email' , 'activated' , true );

	}

	/**
	*This method loads public methods from the Inbound_Mailer_Activation_Update_Routines class and automatically runs them if they have not been run yet.
	* We use transients to store the data, which may not be the best way but I don't have to worry about save/update/create option and the auto load process
	*/

	public static function run_updates() {


		/* Get list of updaters from Inbound_Mailer_Activation_Update_Routines class */
		$updaters = get_class_methods('Inbound_Mailer_Activation_Update_Routines');

		/* Get transient list of completed update processes */
		$completed = ( get_option( 'inbound_email_completed_upgrade_routines' ) ) ?  get_option( 'inbound_email_completed_upgrade_routines' ) : array();

		/* Get the difference between the two arrays */
		$remaining = array_diff( $updaters , $completed );

		/* Loop through updaters and run updaters that have not been ran */
		foreach ( $remaining as $updater ) {

			Inbound_Mailer_Activation_Update_Routines::$updater();
			$completed[] = $updater;

		}

		/* Update this transient value with list of completed upgrade processes */
		update_option( 'inbound_email_completed_upgrade_routines' , $completed );

	}

	/**
	*  This method checks if there are upgrade routines that have not been executed yet and notifies the administror if there are
	*
	*/
	public static function run_upgrade_routine_checks() {

		/* Listen for a manual upgrade call */
		if (isset($_GET['plugin_action']) && $_GET['plugin_action'] == 'upgrade_routines' && $_GET['plugin'] =='inbound-email' ) {
			self::run_updates();
			wp_redirect(admin_url('edit.php?post_type=inbound-email'));
			exit;
		}

		/* Get list of updaters from Inbound_Mailer_Activation_Update_Routines class */
		$updaters = get_class_methods('Inbound_Mailer_Activation_Update_Routines');

		/* Get transient list of completed update processes */
		$completed = ( get_option( 'inbound_email_completed_upgrade_routines' ) ) ?  get_option( 'inbound_email_completed_upgrade_routines' ) : array();

		/* Get the difference between the two arrays */
		$remaining = array_diff( $updaters , $completed );

		if (count($remaining)>0) {
			add_action( 'admin_notices', array( __CLASS__ , 'display_upgrade_routine_notice' ) );
		}
	}

	public static function display_upgrade_routine_notice() {
		?>
		<div class="error">
			<p><?php _e( 'Inbound Email Component plugin requires  a database upgrade:', 'inbound-email' ); ?> <a href='?plugin=inbound-email&plugin_action=upgrade_routines'><?php _e('Upgrade Database Now' , 'inbound-email' ); ?></a></p>
		</div>
		<?php
	}


	/* Creates transient records of past and current version data */
	public static function store_version_data() {

		$old = get_transient('inbound_email_current_version');
		set_transient( 'inbound_email_previous_version' , $old );
		set_transient( 'inbound_email_current_version' , INBOUND_EMAIL_CURRENT_VERSION );

	}

	public static function set_default_settings() {


	}

	/**
	*  Tells Inbound Shared to run activation commands
	*/
	public static function activate_shared() {
		update_option( 'Inbound_Activate', true );
	}

	/* Aborts activation and details
	* @param ARRAY $args array of message details
	*/
	public static function abort_activation( $args ) {
		echo $args['title'] . '<br>';
		echo $args['message'] . '<br>';
		echo 'Details:<br>';
		print_r ($args['details']);
		echo '<br>';
		echo $args['solution'];

		deactivate_plugins( INBOUND_EMAIL_FILE );
		exit;
	}


	/* Checks if plugin is compatible with current server PHP version */
	public static function run_version_checks() {

		global $wp_version;

		/* Check PHP Version */
		if ( version_compare( phpversion(), self::$version_php, '<' ) ) {
			self::abort_activation(
				array(
					'title' => 'Installation aborted',
					'message' => __('Inbound Email Component plugin could not be installed' , 'landing-pages'),
					'details' => array(
									__( 'Server PHP Version' , 'landing-pages' ) => phpversion(),
									__( 'Required PHP Version' , 'landing-pages' ) => self::$version_php
								),
					'solution' => sprintf( __( 'Please contact your hosting provider to upgrade PHP to %s or greater' , 'landing-pages' ) , self::$version_php )
				)
			);
		}

		/* Check WP Version */
		if ( version_compare( $wp_version , self::$version_wp, '<' ) ) {
			self::abort_activation( array(
					'title' => 'Installation aborted',
					'message' => __('Inbound Email Component plugin could not be installed' , 'landing-pages'),
					'details' => array(
									__( 'WordPress Version' , 'landing-pages' ) => $wp_version,
									__( 'Required WordPress Version' , 'landing-pages' ) => self::$version_wp
								),
					'solution' => sprintf( __( 'Please update landing pages to version %s or greater.' , 'landing-pages' ) , self::$version_wp )
				)
			);
		}

		/* Check Landing Pages Version */
		if ( defined('LANDINGPAGES_CURRENT_VERSION') && version_compare( LANDINGPAGES_CURRENT_VERSION , self::$version_lp , '<' ) ) {
			self::abort_activation( array(
					'title' => 'Installation aborted',
					'message' => __('Inbound Email Component plugin could not be installed' , 'landing-pages'),
					'details' => array(
									__( 'Inbound Email Component Version' , 'landing-pages' ) => LANDINGPAGES_CURRENT_VERSION,
									__( 'Required Inbound Email Component Version' , 'landing-pages' ) => self::$version_lp
								),
					'solution' => sprintf( __( 'Please update Inbound Email Component to version %s or greater.' , 'landing-pages' ) , self::$version_lp )
				)
			);
		}

		/* Check Leads Version */
		if ( defined('WPL_CURRENT_VERSION') && version_compare( WPL_CURRENT_VERSION , self::$version_leads , '<' ) ) {
			self::abort_activation( array(
					'title' => 'Installation aborted',
					'message' => __('Inbound Email Component plugin could not be installed' , 'landing-pages'),
					'details' => array(
									__( 'Leads Version' , 'landing-pages' ) => WPL_CURRENT_VERSION,
									__( 'Required Leads Version' , 'landing-pages' ) => self::$version_leads
								),
					'solution' => sprintf( __( 'Please update Leads to version %s or greater.' , 'landing-pages' ) , self::$version_leads )
				)
			);
		}


	}

	/**
	*  Require Leads support.
	*/
	public static function require_leads() {
		if ( !class_exists('Inbound_Leads') ) {
			deactivate_plugins( INBOUND_EMAIL_FILE );
		}
	}


	public static function add_rewrite_rules( $rules ) {
		if (stristr($rules, '[E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]')) {
			return $rules;
		}

		$rules_array = preg_split('/$\R?^/m', $rules);

		if (count($rules_array) < 3) {
			$rules_array = explode("\n", $rules);
			$rules_array = array_filter($rules_array);
		}

		$i = 0;
		foreach ($rules_array as $key => $val) {

			if (!trim($val)) {
				continue;
			}

			if (stristr($val, "RewriteEngine On")) {
				$new_val = "RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]";
				$rules_array[$i] = $new_val;
				$i++;
			 	$rules_array[$i] = $val;
			 	$i++;
			} else {
				$rules_array[$i] = $val;
				$i++;
			}
		}

		$rules = implode("\n", $rules_array);


		return $rules;
	}
}

/* Add Activation Hook */
register_activation_hook( INBOUND_EMAIL_FILE , array( 'Inbound_Mailer_Activation' , 'activate' ) );
register_deactivation_hook( INBOUND_EMAIL_FILE , array( 'Inbound_Mailer_Activation' , 'deactivate' ) );

new Inbound_Mailer_Activation;


}
