<?php

/**
*  Helps convert hard coded mappable fields into database programmable mappable fields
*/
class Inbound_Leads_Custom_fields {
	
	static $custom_field_map;
	
	/**
	*  initialize class
	*/
	public function __construct() {
		self::get_custom_fields();
		self::load_hooks();
	}
	
	/**
	*  load hooks and filters
	*/
	public static function load_hooks() {
		add_filter( 'wp_leads_add_lead_field' , array( __CLASS__ , 'merge_fields' ) , 99  );
	}
	
	/**
	*  Get mappable fields
	*/
	public static function get_custom_fields() {
		$settings = Inbound_Options_API::get_option( 'inbound-pro' , 'settings' , Leads_Field_Map::get_lead_fields() );
		
		self::$custom_field_map = (isset($settings['leads-custom-fields']['fields'])) ?  $settings['leads-custom-fields']['fields'] : array();

	}
	
	/**
	*  Merge the user's custom fields into the hard coded default fields
	*  @param ARRAY $mappable_fields
	*/
	public static function merge_fields( $mappable_fields ) {
		foreach( self::$custom_field_map as $key => $field ) {

			if (!isset($field['key'])) {
				continue;
			}

			/* search core field map and alter label and priority based on user setting */
			$present = false;
			foreach( $mappable_fields as $i => $f) {
				if ( isset($f['key']) && $f['key'] == $key ) {
					$mappable_fields[$i]['priority'] = (is_numeric($field['priority']) ) ? $field['priority'] : 99;
					$mappable_fields[$i]['label'] = $field['label'];
					//$mappable_fields[$i]['type'] = $field['type'];
					$present = true;
				}
			}
			
			/* if custom field detected add field to field map */
			if (!$present) {
				$mappable_fields[] = $field;
			}
		}

		return  $mappable_fields;
	}
	

}

add_action( 'init' , 'load_Inbound_Leads_Custom_fields' );
function load_Inbound_Leads_Custom_fields() {
	new Inbound_Leads_Custom_fields;
}