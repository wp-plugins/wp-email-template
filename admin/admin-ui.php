<?php
/* "Copyright 2012 a3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
A3rev Plugin Admin UI

TABLE OF CONTENTS

- var plugin_name
- var admin_plugin_url
- var admin_plugin_dir
- var admin_pages
- admin_plugin_url()
- admin_plugin_dir()
- admin_pages()
- plugin_extension_start()
- plugin_extension_end()
- pro_fields_before()
- pro_fields_after()
- blue_message_box()

-----------------------------------------------------------------------------------*/

class WP_Email_Tempate_Admin_UI
{
	/**
	 * @var string
	 * You must change to correct plugin name that you are working
	 */
	public $plugin_name = 'wp_email_template';

	public $google_api_key_option = 'wp_email_template_google_api_key';

	public $toggle_box_open_option = 'wp_email_template_toggle_box_open';

	public $is_free_plugin = true;

	public $version_transient = 'a3rev_wp_email_template_update_info';

	public $plugin_option_key = 'a3rev_wp_email_template_plugin';

	public $support_url = 'https://a3rev.com/forums/forum/wordpress-plugins/wp-email-template/';


	/**
	 * @var string
	 * You must change to correct class name that you are working
	 */
	public $class_name = 'WP_Email_Template';

	/**
	 * @var string
	 * You must change to correct pro plugin page url on a3rev site
	 */
	public $pro_plugin_page_url = 'http://a3rev.com/shop/wp-email-template/';

	/**
	 * @var string
	 */
	public $admin_plugin_url;

	/**
	 * @var string
	 */
	public $admin_plugin_dir;

	/**
	 * @var array
	 * You must change to correct page you want to include scripts & styles, if you have many pages then use array() : array( 'quotes-orders-mode', 'quotes-orders-rule' )
	 */
	public $admin_pages = array();
	
	
	/*-----------------------------------------------------------------------------------*/
	/* admin_plugin_url() */
	/*-----------------------------------------------------------------------------------*/
	public function admin_plugin_url() {
		if ( $this->admin_plugin_url ) return $this->admin_plugin_url;
		return $this->admin_plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/*-----------------------------------------------------------------------------------*/
	/* admin_plugin_dir() */
	/*-----------------------------------------------------------------------------------*/
	public function admin_plugin_dir() {
		if ( $this->admin_plugin_dir ) return $this->admin_plugin_dir;
		return $this->admin_plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/*-----------------------------------------------------------------------------------*/
	/* admin_pages() */
	/*-----------------------------------------------------------------------------------*/
	public function admin_pages() {
		$admin_pages = apply_filters( $this->plugin_name . '_admin_pages', $this->admin_pages );

		return (array)$admin_pages;
	}

	public function plugin_extension_boxes( $echo = false ) {

		/**
		 * extension_boxes
		 * =============================================
		 * array (
		 *		'id'				=> 'box_id'						: Enter unique your box id
		 *		'content'			=> 'html_content' 				: (required) Enter the html content to show inside the box
		 * 		'css'				=> 'custom style'				: custom style for the box container
		 * )
		 *
		 */
		$extension_boxes = apply_filters( $this->plugin_name . '_plugin_extension_boxes', array() );

		$output = '';
		if ( is_array( $extension_boxes ) && count( $extension_boxes ) > 0 ) {
			foreach ( $extension_boxes as $box ) {
				if ( ! isset( $box['id'] ) ) $box['id'] = '';
				if ( ! isset( $box['css'] ) ) $box['css'] = '';
				if ( ! isset( $box['content'] ) ) $box['content'] = '';

				$output .= '<div id="'. esc_attr( $box['id'] ) .'" class="a3_plugin_panel_extension_box" style="'. esc_attr( $box['css'] ) .'">';
				$output .= $box['content'];
				$output .= '</div>';
			}
		}

		if ( $echo )
			echo $output;
		else
			return $output;
	}

	/*-----------------------------------------------------------------------------------*/
	/* plugin_extension_start() */
	/* Start of yellow box on right for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function plugin_extension_start( $echo = true ) {
		$output = '<div id="a3_plugin_panel_container">';
		$output .= '<div id="a3_plugin_panel_upgrade_area">';
		$output .= '<div id="a3_plugin_panel_extensions">';
		$output .= $this->plugin_extension_boxes( false );
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div id="a3_plugin_panel_fields">';

		$output = apply_filters( $this->plugin_name . '_plugin_extension_start', $output );

		if ( $echo )
			echo $output;
		else
			return $output;
	}

	/*-----------------------------------------------------------------------------------*/
	/* plugin_extension_start() */
	/* End of yellow box on right for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function plugin_extension_end( $echo = true ) {
		$output = '</div>';
		$output .= '</div>';

		$output = apply_filters( $this->plugin_name . '_plugin_extension_end', $output );

		if ( $echo )
			echo $output;
		else
			return $output;

	}

	/*-----------------------------------------------------------------------------------*/
	/* upgrade_top_message() */
	/* Show upgrade top message for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function upgrade_top_message( $echo = false, $setting_id = '' ) {
		$upgrade_top_message = sprintf( '<div class="pro_feature_top_message">'
			. __( 'Advanced Settings - Upgrade to the <a href="%s" target="_blank">%s License</a> to activate these settings.', 'wp_email_template' )
			. '</div>'
			, apply_filters( $this->plugin_name . '_' . $setting_id . '_pro_plugin_page_url', apply_filters( $this->plugin_name . '_pro_plugin_page_url', $this->pro_plugin_page_url ) )
			, apply_filters( $this->plugin_name . '_' . $setting_id . '_pro_version_name', apply_filters( $this->plugin_name . '_pro_version_name', __( 'Pro Version', 'wp_email_template' ) ) )
		);

		$upgrade_top_message = apply_filters( $this->plugin_name . '_upgrade_top_message', $upgrade_top_message );

		if ( $echo ) echo $upgrade_top_message;
		else return $upgrade_top_message;

	}

	/*-----------------------------------------------------------------------------------*/
	/* pro_fields_before() */
	/* Start of yellow box on right for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function pro_fields_before( $echo = true ) {
		echo apply_filters( $this->plugin_name . '_pro_fields_before', '<div class="pro_feature_fields">'. $this->upgrade_top_message() );
	}

	/*-----------------------------------------------------------------------------------*/
	/* pro_fields_after() */
	/* End of yellow border for pro fields
	/*-----------------------------------------------------------------------------------*/
	public function pro_fields_after( $echo = true ) {
		echo apply_filters( $this->plugin_name . '_pro_fields_after', '</div>' );
	}

	/*-----------------------------------------------------------------------------------*/
	/* blue_message_box() */
	/* Blue Message Box
	/*-----------------------------------------------------------------------------------*/
	public function blue_message_box( $message = '', $width = '600px' ) {
		$message = '<div class="a3rev_blue_message_box_container" style="width:'.$width.'"><div class="a3rev_blue_message_box">' . $message . '</div></div>';
		$message = apply_filters( $this->plugin_name . '_blue_message_box', $message );

		return $message;
	}

	/*-----------------------------------------------------------------------------------*/
	/* get_version_message() */
	/* Get new version message, also include error connect
	/*-----------------------------------------------------------------------------------*/
	public function get_version_message() {
		$version_message = '';

		//Getting version number
		$version_transient = get_transient( $this->version_transient );
		if ( false !== $version_transient ) {
			$transient_timeout = '_transient_timeout_' . $this->version_transient;
			$timeout = get_option( $transient_timeout, false );
			if ( false === $timeout ) {
				$version_message = __( 'You should check now to see if have any new version is available', 'wp_email_template' );
			} elseif ( 'cannot_connect_api' == $version_transient ) {
				$version_message = sprintf( __( 'Connection Failure! Please try again. If this issue persists please create a support request on the plugin <a href="%s" target="_blank">a3rev support forum</a>.', 'wp_email_template' ), $this->support_url );
			} else {
				$version_info = explode( '||', $version_transient );
				if ( FALSE !== stristr( $version_transient, '||' )
					&& is_array( $version_info )
					&& isset( $version_info[1] ) && $version_info[1] == 'valid'
					&& version_compare( get_option('a3rev_wp_email_template_version') , $version_info[0], '<' ) ) {

						$version_message = sprintf( __( 'There is a new version <span class="a3rev-ui-new-plugin-version">%s</span> available, <a href="%s" target="_blank">update now</a> or download direct from <a href="%s" target="_blank">My Account</a> on a3rev.com', 'wp_email_template' ),
							$version_info[0],
							wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . WP_EMAIL_TEMPLATE_NAME ), 'upgrade-plugin_' . WP_EMAIL_TEMPLATE_NAME ),
							'https://a3rev.com/my-account/downloads/'
						);
				}
			}

		} else {
			$version_message = __( 'You should check now to see if have any new version is available', 'wp_email_template' );
		}

		return $version_message;
	}

}

?>
