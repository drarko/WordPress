<?php


class Inbound_Mailer_Unsubscribe {

	/**
	 *  Initialize class
	 */
	public function __construct() {

		self::load_hooks();
	}

	/**
	 *  Loads hooks and filters
	 */
	public function load_hooks() {

		/* Add processing listeners  */
		add_action( 'init' , array( __class__ , 'process_unsubscribe' ) , 20 );

		/* Shortcode for displaying unsubscribe page */
		add_shortcode( 'inbound-email-unsubscribe' , array( __CLASS__, 'display_unsubscribe_page' ), 1 );

	}

	/**
	 * Display unsubscribe options
	 */
	public static function display_unsubscribe_page( $atts ) {
		global $inbound_settings;

		$usubscribe_header_text = (isset($inbound_settings['inbound-mailer']['unsubscribe-header-text'])) ? $inbound_settings['inbound-mailer']['unsubscribe-header-text'] : __( 'Unsubscribe:', 'inbound-pro');
		$usubscribe_button_text = (isset($inbound_settings['inbound-mailer']['unsubscribe-button-text'])) ? $inbound_settings['inbound-mailer']['unsubscribe-button-text'] : __( 'Unsubscribe', 'inbound-pro');
		$usubscribe_show_lists = (isset($inbound_settings['inbound-mailer']['unsubscribe-show-lists'])) ? $inbound_settings['inbound-mailer']['unsubscribe-show-lists'] : 'on';
		$mute_header_text = (isset($inbound_settings['inbound-mailer']['mute-header-text'])) ? $inbound_settings['inbound-mailer']['mute-header-text'] : __( 'Mute:', 'inbound-pro');
		$unsubscribed_confirmation_message = (isset($inbound_settings['inbound-mailer']['unsubscribe-confirmation-message'])) ? $inbound_settings['inbound-mailer']['unsubscribe-confirmation-message'] : __( 'Thank You!', 'inbound-pro');
		$comments_header_1 = (isset($inbound_settings['inbound-mailer']['unsubscribe-comments-header-1'])) ? $inbound_settings['inbound-mailer']['unsubscribe-comments-header-1'] : __( 'Please help us improve by providing us with feedback.' , 'inbound-pro' );
		$comments_header_2 = (isset($inbound_settings['inbound-mailer']['unsubscribe-comments-header-2'])) ? $inbound_settings['inbound-mailer']['unsubscribe-comments-header-2'] : __( 'Comments:' , 'inbound-pro' );
		$all_lists = (isset($inbound_settings['inbound-mailer']['unsubscribe-all-lists-label'])) ? $inbound_settings['inbound-mailer']['unsubscribe-all-lists-label'] : __( 'All Lists' , 'inbound-pro' );
		$month_1 = (isset($inbound_settings['inbound-mailer']['unsubscribe-1-months'])) ? $inbound_settings['inbound-mailer']['unsubscribe-1-months'] : __( '1 Month' , 'inbound-pro' );
		$month_3 = (isset($inbound_settings['inbound-mailer']['unsubscribe-3-months'])) ? $inbound_settings['inbound-mailer']['unsubscribe-3-months'] : __( '3 Month' , 'inbound-pro' );
		$month_6 = (isset($inbound_settings['inbound-mailer']['unsubscribe-6-months'])) ? $inbound_settings['inbound-mailer']['unsubscribe-6-months'] : __( '6 Month' , 'inbound-pro' );
		$month_12 = (isset($inbound_settings['inbound-mailer']['unsubscribe-12-months'])) ? $inbound_settings['inbound-mailer']['unsubscribe-12-months'] : __( '12 Month' , 'inbound-pro' );



		if ( isset( $_GET['unsubscribed'] ) ) {
			$confirm =  "<span class='unsubscribed-message'>". $unsubscribed_confirmation_message ."</span>";
			$unsubscribed_confirmation_message = apply_filters( 'inbound-mailer/unsubscribe/confirmation-html' , $confirm );
		}

		if ( !isset( $_GET['token'] ) ) {
			return __( 'Invalid token' , 'inbound-pro' );
		}



		/* get all lead lists */
		$lead_lists = Inbound_Leads::get_lead_lists_as_array();

		/* decode token */
		$params = self::decode_unsubscribe_token( $_GET['token'] );

		if ( !isset( $params['lead_id'] ) ) {
			return __( 'Oops. Something is wrong with the unsubscribe link. Are you logged in?' , 'inbound-pro' );
		}

		/* Begin unsubscribe html inputs */
		$html = "<form action='?unsubscribed=true' name='unsubscribe' method='post'>";
		$html .= "<input type='hidden' name='token' value='".strip_tags($_GET['token'])."' >";
		$html .= "<input type='hidden' name='action' value='inbound_unsubscribe_event' >";

		/* loop through lists and show unsubscribe inputs */
		if ( isset($params['list_ids']) && $usubscribe_show_lists == 'on' ) {
			foreach ($params['list_ids'] as $list_id ) {
				if ($list_id == '-1' || !$list_id ) {
					continue;
				}

				$html .= "<span class='unsubscribe-span'><label class='lead-list-label'><input type='checkbox' name='list_id[]' value='".$list_id."' class='lead-list-class'> " . $lead_lists[ $list_id ] . '</label></span>';

			}
		}

		$html .= "<span class='unsubscribe-span'><label class='lead-list-label'><input name='lists_all' type='checkbox' value='all' ". ( $usubscribe_show_lists == 'off' ? 'checked="true"' : '' ) ."> " . $all_lists . "</label></span>";
		$html .= "<div class='unsubscribe-div unsubsribe-comments'>";
		$html .= "	<span class='unsubscribe-comments-message'>". $comments_header_1 ."</span>";
		$html .= "	<span class='unsubscribe-comments-label'>". $comments_header_2 ."<br><textarea rows='8' cols='60' name='comments'></textarea></span>";
		$html .= "</div>";
		$html .= "<div class='unsubscribe-div unsubscribe-options'>";
		$html .= "	<span class='unsubscribe-action-label'>". $mute_header_text ."</span>";
		$html .= "	<div class='mute-buttons'>";
		$html .= "		<span class='mute-1-span'>
							<label class='unsubscribe-label'>
								<input name='mute-1' type='submit' value='". $month_1 ."' class='inbound-button-submit inbound-submit-action'>
							</label>
						</span>";
		$html .= "		<span class='mute-3-span'>
							<label class='unsubscribe-label'>
								<input name='mute-3' type='submit' value='". $month_3 ."' class='inbound-button-submit inbound-submit-action'>
							</label>
						</span>";
		$html .= "		<span class='mute-6-span'>
							<label class='unsubscribe-label'>
								<input name='mute-6' type='submit' value='". $month_6 ."' class='inbound-button-submit inbound-submit-action'>
							</label>
						</span>";
		$html .= "		<span class='mute-12-span'>
							<label class='unsubscribe-label'>
								<input name='mute-12' type='submit' value='". $month_12 ."' class='inbound-button-submit inbound-submit-action'>
							</label>
						</span>";
		$html .= "	</div>";
		$html .= "	<span class='unsubscribe-action-label'>".$usubscribe_button_text .":</span>";
		$html .= "	<div class='unsubscribe-button'>";
		$html .= "		<span class='unsub-span'>
							<label class='unsubscribe-label'>
								<input name='unsubscribe' type='submit' value='". $usubscribe_button_text ."' class='inbound-button-submit inbound-submit-action'>
							</label>
						</span>";
		$html .= "	</div>";
		$html .= "</div>";
		$html .= "</form>";

		return $html;

	}

	/**
	 *  Generates unsubscribe link given lead id and lists
	 *  @param ARRAY $params contains: lead_id (INT ), list_ids (MIXED), email_id (INT)
	 *  @return STRING $unsubscribe_link
	 */
	public static function generate_unsubscribe_link( $params ) {


		if (!isset($params['lead_id']) || !$params['lead_id']) {
			return __( '#unsubscribe-not-available-in-online-mode' , 'inbound-pro' );
		}

		if (isset($_GET['lead_lists']) && !is_array($_GET['lead_lists'])){
			$params['list_ids'] = explode( ',' , $_GET['lead_lists']);
		} else if (isset($params['list_ids']) && !is_array($params['list_ids'])) {
			$params['list_ids'] = explode( ',' , $params['list_ids']);
		}


		$args = array_merge( $params , $_GET );

		$token = Inbound_Mailer_Unsubscribe::encode_unsubscribe_token( $args );

		$settings = Inbound_Mailer_Settings::get_settings();

		if ( empty($settings['unsubscribe_page']) )  {
			$post = get_page_by_title( __( 'Unsubscribe' , 'inbound-pro' ) );
			$settings['unsubscribe_page'] =  $post->ID;
		}

		$base_url = get_permalink( $settings['unsubscribe_page']  );

		return add_query_arg( array( 'token'=>$token ) , $base_url );

	}

	/**
	 *  Encodes data into an unsubscribe token
	 *  @param ARRAY $params contains: lead_id (INT ), list_ids (MIXED), email_id (INT)
	 *  @return INT $token
	 */
	public static function encode_unsubscribe_token( $params ) {
		unset($params['doing_wp_cron']);
		$json = json_encode($params);

		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted_string =
			base64_encode(
				trim(
					mcrypt_encrypt(
						MCRYPT_RIJNDAEL_256, substr( SECURE_AUTH_KEY , 0 , 24 )  , $json, MCRYPT_MODE_ECB, $iv
					)
				)
			);

		return  str_replace(array('+', '/'), array('-', '_'), $encrypted_string);
	}

	/**
	 *  Decodes unsubscribe encoded reader id into a lead id
	 *  @param STRING $reader_id Encoded lead id.
	 *  @return ARRAY $unsubscribe array of unsubscribe data
	 */
	public static function decode_unsubscribe_token( $token ) {

		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypted_string =
			trim(
				mcrypt_decrypt(
					MCRYPT_RIJNDAEL_256 ,  substr( SECURE_AUTH_KEY , 0 , 24 )   ,  base64_decode( str_replace(array('-', '_'), array('+', '/'), $token ) ) , MCRYPT_MODE_ECB, $iv
				)
			);

		return json_decode($decrypted_string , true);

	}


	/**
	 *  Removes a list id to a leads unsubscribed list
	 *  @param INT $lead_id
	 *  @param INT $list_id
	 */
	public static function remove_stop_rule( $lead_id , $list_id ) {
		$stop_rules = get_post_meta( $lead_id , 'inbound_unsubscribed' , true );

		if ( !$stop_rules ) {
			return;
		}

		if (!isset($stop_rules[$list_id])) {
			return;
		}

		unset( $stop_rules[$list_id] );

		update_post_meta( $lead_id , 'inbound_unsubscribed' , $stop_rules );
	}

	/**
	 *  Listener & unsubscribe actions
	 */
	public static function process_unsubscribe() {

		if (!isset($_POST['action']) || $_POST['action'] != 'inbound_unsubscribe_event' ) {
			return;
		}

		/* determine if anything is selected */
		if (!isset($_POST['list_id'])) {
			return;
		}

		/* decode token */
		$params = self::decode_unsubscribe_token( $_POST['token'] );

		/* prepare all token */
		$all = (isset($_POST['lists_all']) && $_POST['lists_all']  ) ? true : false;

		/* add comments */
		$params['event_details']['comments'] = ( isset( $_POST['comments'] ) ) ? $_POST['comments'] : '';
		$params['event_details']['list_ids'] = $params['list_ids'];

		if (isset($_POST['mute-1'])) {
			self::mute_lead_emails( $params , $all , 1 );
		} else if (isset($_POST['mute-3'])) {
			self::mute_lead_emails( $params , $all , 3 );
		} else if (isset($_POST['mute-6'])) {
			self::mute_lead_emails( $params , $all , 6 );
		} else if (isset($_POST['mute-12'])) {
			self::mute_lead_emails( $params , $all , 12 );
		} else if (isset($_POST['unsubscribe'])) {
			self::unsubscribe_lead( $params , $all );
		}

	}

	/**
	 * @param $params
	 * @param bool $all
	 */
	public static function unsubscribe_lead( $params , $all = false) {
		switch ($all) {
			case true:
				self::unsubscribe_from_all_lists( $params['lead_id'] );
				break;
			default:
				/* loop through lists and unsubscribe lead */
				foreach( $params['list_ids'] as $list_id ) {
					Inbound_Leads::remove_lead_from_list( $params['lead_id'] , $list_id );
					Inbound_Mailer_Unsubscribe::add_stop_rules( $params['lead_id'] , $list_id );
					$event = $params;
					Inbound_Events::store_unsubscribe_event( $event );
				}
				break;
		}
	}

	/**
	 *  Unsubscribe lead from all lists
	 */
	public static function unsubscribe_from_all_lists( $lead_id = null ) {
		/* get all lead lists */
		$lead_lists = Inbound_Leads::get_lead_lists_as_array();

		foreach ( $lead_lists as $list_id => $label ) {
			Inbound_Leads::remove_lead_from_list( $lead_id , $list_id );
			Inbound_Mailer_Unsubscribe::add_stop_rules( $lead_id , $list_id );
			$event = $params;
			$event['list_id'] = $list_id;
			Inbound_Events::store_unsubscribe_event( $event );
		}

	}


	/**
	 * @param $params
	 * @param bool $all
	 */
	public static function mute_lead_emails( $params , $all = false, $time ) {

		switch ($all) {
			case true:
				self::mute_all_lists( $params , $time );
				break;
			default:
				self::mute_lists($params , $time );
				break;
		}
	}

	/**
	 *  Unsubscribe lead from all lists
	 */
	public static function mute_all_lists( $params , $time ) {
		/* get all lead lists */
		$params['list_ids'] = Inbound_Leads::get_lead_lists_as_array();
		self::mute_lists($params , $time );
	}

	/**
	 *  Unsubscribe lead from all lists
	 */
	public static function mute_lists( $params, $time ) {
		$wordpress_date_time =  date_i18n('Y-m-d G:i:s T');
		$dateTime = new DateTime($wordpress_date_time);
		$dateTime->modify('+'.$time.' months');
		$release_date = $dateTime->format('Y-m-d H:i');

		$event = $params;

		foreach ( $params['list_ids'] as $list_id ) {
			Inbound_Mailer_Unsubscribe::add_stop_rules( $params['lead_id'] , $list_id , $release_date );
			$event['event_details']['emails_muted_for'] = $time . ' month';
			$event['event_details']['emails_muted_until'] = $release_date;
			Inbound_Events::store_mute_event( $event );
		}

	}


	/**
	 *  Adds a list id to a leads unsubscribed list
	 *  @param INT $lead_id
	 *  @param INT $list_id
	 */
	public static function add_stop_rules( $lead_id , $list_id , $nature = 'unsubscribed' ) {
		$stop_rules = self::get_stop_rules( $lead_id );

		$stop_rules[ $list_id ] = $nature;

		update_post_meta( $lead_id , 'inbound_unsubscribed' , $stop_rules );
	}

	/**
	 *  Adds a list id to a leads unsubscribed list
	 *  @param INT $lead_id
	 *  @param INT $list_id
	 */
	public static function get_stop_rules( $lead_id ) {
		$stop_rules = get_post_meta( $lead_id , 'inbound_unsubscribed' , true );

		if ( !$stop_rules ) {
			$stop_rules = array();
		}

		return $stop_rules;
	}

}

$Inbound_Mailer_Unsubscribe = new Inbound_Mailer_Unsubscribe();
