<?php
/**
 * WP Email Template Hook Filter
 *
 * Table Of Contents
 *
 * woo_email_header_marker_start()
 * woo_email_header_marker_end()
 * woo_email_footer_marker_start()
 * woo_email_footer_marker_end()
 * preview_wp_email_template()
 * set_content_type()
 * change_wp_mail()
 * a3_wp_admin()
 * admin_sidebar_menu_css()
 * plugin_extra_links()
 * settings_plugin_links()
 */
class WP_Email_Template_Hook_Filter
{

	public static function check_apply_template_for_woocommerce_emails() {
		global $wp_email_template_general;

		if ( $wp_email_template_general['apply_template_all_emails'] != 'yes' ) {
			return false;
		}

		if ( isset( $wp_email_template_general['apply_for_woo_emails'] ) && $wp_email_template_general['apply_for_woo_emails'] == 'yes' ) {
			return true;
		}

		return false;
	}

	public static function woo_email_header_marker_start( $email_heading='' ) {
		global $wp_email_template_general;

		if ( self::check_apply_template_for_woocommerce_emails() ) {
			ob_start();
			echo '<!--WOO_EMAIL_TEMPLATE_HEADER_START-->';
		}
	}

	public static function woo_email_header_marker_end( $email_heading='' ) {
		global $wp_email_template_general;

		if ( self::check_apply_template_for_woocommerce_emails() ) {
			echo '<!--WOO_EMAIL_TEMPLATE_HEADER_END-->';
			ob_get_clean();
			$header = WP_Email_Template_Functions::email_header($email_heading);

			if (isset($_REQUEST['preview_woocommerce_mail']) && $_REQUEST['preview_woocommerce_mail'] == 'true') {
				$template_notice = WP_Email_Template_Functions::apply_email_template_notice( __('Attention! You have selected to apply your WP Email Template to all WooCommerce Emails. Go to Settings in your WordPress admin sidebar > Email Template to customize this template or to reactivate the WooCommerce Email Template.', 'wp_email_template') );
				$header = str_replace('<!--EMAIL_TEMPLATE_NOTICE-->', $template_notice, $header);
			}


			echo $header;
		}
	}

	public static function woo_email_footer_marker_start() {
		global $wp_email_template_general;

		if ( self::check_apply_template_for_woocommerce_emails() ) {
			ob_start();
			echo '<!--WOO_EMAIL_TEMPLATE_FOOTER_START-->';
		}
	}

	public static function woo_email_footer_marker_end() {
		global $wp_email_template_general;

		if ( self::check_apply_template_for_woocommerce_emails() ) {
			echo '<!--WOO_EMAIL_TEMPLATE_FOOTER_END-->';
			ob_get_clean();
			echo WP_Email_Template_Functions::email_footer();
		}
		echo '<!--NO_USE_EMAIL_TEMPLATE-->';
	}

	public static function style_inline_h1_tag( $styles ) {

		if ( self::check_apply_template_for_woocommerce_emails() ) {

			$styles = array();
			$h1_font     = 'font:italic 26px Century Gothic, sans-serif !important; color: #000000 !important;';
			$styles['font'] = trim( $h1_font );

		}

		return $styles;
	}

	public static function style_inline_h2_tag( $styles ) {

		if ( self::check_apply_template_for_woocommerce_emails() ) {

			$styles = array();
			$h2_font     = 'font:italic 20px Century Gothic, sans-serif !important; color: #000000 !important;';
			$styles['font'] = trim( $h2_font );

		}

		return $styles;
	}

	public static function style_inline_h3_tag( $styles ) {

		if ( self::check_apply_template_for_woocommerce_emails() ) {

			$styles = array();
			$h3_font     = 'font:italic 18px Century Gothic, sans-serif !important; color: #000000 !important;';
			$styles['font'] = trim( $h3_font );

		}

		return $styles;
	}

	public static function remove_style_inline_woocommerce_tag( $styles ) {
		global $wp_email_template_general;

		if ( self::check_apply_template_for_woocommerce_emails() ) {
			$styles = array();
		}

		return $styles;
	}

	public static function preview_wp_email_template() {
		check_ajax_referer( 'preview_wp_email_template', 'security' );

		$email_heading = __('Email preview', 'wp_email_template');

		echo WP_Email_Template_Hook_Filter::preview_wp_email_content( $email_heading );

		die();
	}

	public static function preview_wp_email_content( $email_heading ) {

		$message = '<h2>'.__('WordPress Email sit amet', 'wp_email_template').'</h2>';

		$message.= wpautop(__('Ut ut est qui euismod parum. Dolor veniam tation nihil assum mazim. Possim fiant habent decima et claritatem. Erat me usus gothica laoreet consequat. Clari facer litterarum aliquam insitam dolor.

Gothica minim lectores demonstraverunt ut soluta. Sequitur quam exerci veniam aliquip litterarum. Lius videntur nisl facilisis claritatem nunc. Praesent in iusto me tincidunt iusto. Dolore lectores sed putamus exerci est. ', 'wp_email_template') );

		return WP_Email_Template_Functions::email_content($email_heading, $message, true );

	}

	public static function set_content_type( $content_type='' ) {
		global $wp_email_template_general;

		if ( $wp_email_template_general['apply_template_all_emails'] != 'yes' ) {
			return $content_type;
		}

		if ( stristr( $content_type, 'multipart') !== false ) {
			$content_type = 'multipart/alternative';
		} else {
			$content_type = 'text/html';
		}
		return $content_type;
	}

	public static function change_wp_mail( $email_data=array() ) {
		$email_heading = $email_data['subject'] ;
		if ( isset( $email_data['message'] ) && stristr( $email_data['message'], '<!--NO_USE_EMAIL_TEMPLATE-->' ) === false ) {
			$email_data['message'] = WP_Email_Template_Functions::email_content($email_heading, $email_data['message']);
		} elseif ( isset( $email_data['html'] ) && stristr( $email_data['html'], '<!--NO_USE_EMAIL_TEMPLATE-->' ) === false ) {
			$email_data['html'] = WP_Email_Template_Functions::email_content($email_heading, $email_data['html']);
		}

		return $email_data;
	}

	public static function disable_formidable_encode_subject_title( $enable_encode = 1, $subject = '' ) {
		$enable_encode = 0;

		return $enable_encode;
	}

	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', WP_EMAIL_TEMPLATE_CSS_URL . '/a3_wp_admin.css' );
	}

	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-wp-et-admin-sidebar-menu-style', WP_EMAIL_TEMPLATE_CSS_URL . '/admin_sidebar_menu.css' );
	}

	public static function plugin_extension_box( $boxes = array() ) {
		$support_box = '<a href="https://wordpress.org/support/plugin/wp-email-template" target="_blank" alt="'.__('Go to Support Forum', 'wp_email_template').'"><img src="'.WP_EMAIL_TEMPLATE_IMAGES_URL.'/go-to-support-forum.png" /></a>';
		$boxes[] = array(
			'content' => $support_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$pro_box = '<a href="'.WP_EMAIL_TEMPLATE_AUTHOR_URI.'" target="_blank" alt="'.__('WP Email Template Pro', 'wp_email_template').'"><img src="'.WP_EMAIL_TEMPLATE_IMAGES_URL.'/pro-version.png" /></a>';
		$boxes[] = array(
			'content' => $pro_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$free_wordpress_box = '<a href="https://profiles.wordpress.org/a3rev/#content-plugins" target="_blank" alt="'.__('Free WordPress Plugins', 'wp_email_template').'"><img src="'.WP_EMAIL_TEMPLATE_IMAGES_URL.'/free-wordpress-plugins.png" /></a>';

		$boxes[] = array(
			'content' => $free_wordpress_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

        $review_box = '<div style="margin-bottom: 5px; font-size: 12px;"><strong>' . __('Is this plugin is just what you needed? If so', 'wp_email_template') . '</strong></div>';
        $review_box .= '<a href="https://wordpress.org/support/view/plugin-reviews/wp-email-template?filter=5#postform" target="_blank" alt="'.__('Submit Review for Plugin on WordPress', 'wp_email_template').'"><img src="'.WP_EMAIL_TEMPLATE_IMAGES_URL.'/a-5-star-rating-would-be-appreciated.png" /></a>';

        $boxes[] = array(
            'content' => $review_box,
            'css' => 'border: none; padding: 0; background: none;'
        );

        $connect_box = '<div style="margin-bottom: 5px;">' . __('Connect with us via','wp_email_template') . '</div>';
		$connect_box .= '<a href="https://www.facebook.com/a3rev" target="_blank" alt="'.__('a3rev Facebook', 'wp_email_template').'" style="margin-right: 5px;"><img src="'.WP_EMAIL_TEMPLATE_IMAGES_URL.'/follow-facebook.png" /></a> ';
		$connect_box .= '<a href="https://twitter.com/a3rev" target="_blank" alt="'.__('a3rev Twitter', 'wp_email_template').'"><img src="'.WP_EMAIL_TEMPLATE_IMAGES_URL.'/follow-twitter.png" /></a>';

		$boxes[] = array(
			'content' => $connect_box,
			'css' => 'border-color: #3a5795;'
		);

		return $boxes;
	}

	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WP_EMAIL_TEMPLATE_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/wordpress/wp-email-template/" target="_blank">'.__('Documentation', 'wp_email_template').'</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/wp-email-template" target="_blank">'.__('Support', 'wp_email_template').'</a>';
		return $links;
	}

	public static function settings_plugin_links($actions) {
		$actions = array_merge( array( 'settings' => '<a href="admin.php?page=wp_email_template">' . __( 'Settings', 'wp_email_template' ) . '</a>' ), $actions );

		return $actions;
	}
}
?>
