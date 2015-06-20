<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WP Email Teplate Exclude Emails Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class WP_Email_Template_Exclude_Emails_Settings extends WP_Email_Tempate_Admin_UI
{

	/**
	 * @var string
	 */
	private $parent_tab = 'exclude_emails';

	/**
	 * @var array
	 */
	private $subtab_data;

	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'wp_email_template_exclude_emails';

	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wp_email_template_exclude_emails';

	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;

	/**
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * @var array
	 */
	public $form_messages = array();

	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->init_form_fields();
		//$this->subtab_init();

		$this->form_messages = array(
				'success_message'	=> __( 'Exclude Emails Settings successfully saved.', 'wp_email_template' ),
				'error_message'		=> __( 'Error: Exclude Emails Settings can not save.', 'wp_email_template' ),
				'reset_message'		=> __( 'Exclude Emails Settings successfully reseted.', 'wp_email_template' ),
			);

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );

		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'update_exclude_subject_titles' ) );

		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );

		add_action( $this->plugin_name . '_settings_' . 'exclude_by_subject_title_box' . '_start', array( $this, 'exclude_email_subject_lists' ) );

		add_action( $this->plugin_name . '_settings_' . 'help_notes_box' . '_start', array( $this, 'help_notes_container' ) );
	}

	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {

		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );

	}

	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $wp_email_template_admin_interface;

		$wp_email_template_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}

	/*-----------------------------------------------------------------------------------*/
	/* reset_default_settings()
	/* Reset default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function reset_default_settings() {
		global $wp_email_template_admin_interface;

		$wp_email_template_admin_interface->reset_settings( $this->form_fields, $this->option_name, true, true );
	}

	/*-----------------------------------------------------------------------------------*/
	/* update_exclude_subject_titles()
	/*-----------------------------------------------------------------------------------*/
	public function update_exclude_subject_titles() {
		if ( isset( $_POST['bt_save_settings'] ) && isset( $_POST['email_subjects'] ) )  {
			$email_subjects = $_POST['email_subjects'];
			$email_sent_by = $_POST['email_sent_by'];
			if ( is_array( $email_subjects ) && count( $email_subjects ) > 0 ) {
				global $wp_email_template_exclude_subject_data;

				$wp_email_template_exclude_subject_data->delete_all_subjects();

				foreach ( $email_subjects as $key => $subject_title ) {
					$subject_data = array(
						'email_sent_by' => stripslashes( trim( $email_sent_by[$key] ) ),
						'email_subject' => stripslashes( trim( $subject_title ) ),
					);
					$wp_email_template_exclude_subject_data->add_subject( $subject_data );
				}
			}
		}
	}

	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $wp_email_template_admin_interface;

		$wp_email_template_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}

	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array (
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {

		$subtab_data = array(
			'name'				=> 'exclude_emails',
			'label'				=> __( 'Exclude Emails', 'wp_email_template' ),
			'callback_function'	=> 'wp_email_template_exclude_emails_settings_form',
		);

		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;

	}

	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {

		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();

		return $subtabs_array;
	}

	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $wp_email_template_admin_interface;

		$output = '';
		$output .= $wp_email_template_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );

		return $output;
	}

	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		$preview_wp_email_template = '';
		if ( is_admin() && in_array (basename($_SERVER['PHP_SELF']), array('admin.php') ) && isset( $_GET['page'] ) && $_GET['page'] == 'wp_email_template' ) {
			$preview_wp_email_template = wp_create_nonce("preview_wp_email_template");
		}

  		// Define settings
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(

			array(
            	'name' 		=> __( "Exclude Emails by Shortcode", 'wp_email_template' ),
            	'desc' 		=> '<p>'.__("For security reason please change the Default shortcode in the box to your own unique shortcode. Just create new using the shortcode format [name_name] and Save Changes.", 'wp_email_template' ).'</p>',
                'type' 		=> 'heading',
                'class'		=> 'pro_feature_fields',
                'id'		=> 'exclude_by_shortcode_box',
                'is_box'	=> true,
           	),
			array(
				'name' 		=> __( 'Shortcode Creator', 'wp_email_template' ),
				'id' 		=> 'exclude_shortcode',
				'type' 		=> 'text',
				'default'	=> '[not_apply_email_template]'
			),

			array(
            	'name' 		=> __( "Exclude Emails by Subject Title", 'wp_email_template' ),
                'type' 		=> 'heading',
                'class'		=> 'exclude_by_subject_title pro_feature_fields',
                'id'		=> 'exclude_by_subject_title_box',
                'is_box'	=> true,
           	),

           	array(
            	'name' 		=> __( 'Help Notes', 'wp_email_template' ),
                'type' 		=> 'heading',
                'class'		=> 'pro_feature_fields',
                'id'		=> 'help_notes_box',
                'is_box'	=> true,
           	),

        ));
	}

	public function exclude_email_subject_lists() {
		global $wp_email_template_exclude_subject_data;
		$all_exclude_subject = $wp_email_template_exclude_subject_data->get_all_exclude_subjects();
?>
</table>
<h3><?php echo __( "Exclude by Subject Title", 'wp_email_template' ); ?> <a class="add-new-h2 add-new-email-subject"><?php echo __( "Add New", 'wp_email_template' ); ?></a> <a class="add-new-h2 remove-all-email-subject"><?php echo __( "Remove All", 'wp_email_template' ); ?></a></h3>
<p></p>
<style>
.exclude_email_subject_lists {
	width: 100%;
	max-width: 768px;
}
input.email_sent_by {
	width: 95%;
}
input.email_subject {
	width: 98%;
}
.remove-all-email-subject {
	color: #d54e21;
}
.exclude_email_remove {
	color: #d54e21 !important;
}
.exclude_subject_desc {
	font-size: 12px;
}
</style>
<table class="exclude_email_subject_lists">

	<thead>
		<tr>
			<th width="30%" align="left"><?php echo __( 'Email Sent by', 'wp_email_template' ); ?></th>
			<th align="left"><?php echo __( 'Email Subject Title', 'wp_email_template' ); ?></th>
			<th width="30px"></th>
		</tr>
	</thead>

	<tbody>
	<?php if ( is_array( $all_exclude_subject ) && count( $all_exclude_subject ) > 0 ) : ?>
		<?php foreach ( $all_exclude_subject as $subject_data ) : ?>
		<tr>
			<td><input class="email_sent_by" type="text" name="email_sent_by[]" value="<?php echo esc_attr( trim( $subject_data->email_sent_by ) ); ?>" /></td>
			<td><input class="email_subject" type="text" name="email_subjects[]" value="<?php echo esc_attr( trim( $subject_data->email_subject ) ); ?>" /></td>
			<td><input type="button" class="button exclude_email_remove" value="&ndash;" /></td>
		</tr>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>

</table>
<table>
<?php
	}

	public function help_notes_container() {
?>
</table>
<div class="a3rev_panel_inner help_notes_container">

<h3 style="margin-top: 0;"><?php echo __( 'Exclude by Shortode', 'wp_email_template' ); ?></h3>
<p>
<?php echo __( 'Use the template exclusion shortcode for plugin created email submission forms', 'wp_email_template' ); ?>:
<ol>
	<li><?php echo __( 'Use the Shortcode creator to create a unique exclusion shortcode.', 'wp_email_template' ); ?></li>
	<li><?php echo __( 'Copy the email template exclusion shortcode.', 'wp_email_template' ); ?></li>
	<li><?php echo __( 'Create or edit the Form and paste the exclusion shortcode into the forms message field.', 'wp_email_template' ); ?></li>
	<li><?php echo __( 'Save Changes. Note the shortcode is not visible to the user on the front end.', 'wp_email_template' ); ?></li>
	<li><?php echo __( 'The email template will not be applied to email submissions from the form.', 'wp_email_template' ); ?></li>
</ol>
</p>

<h3><?php echo __( 'Exclude by Subject Title', 'wp_email_template' ); ?></h3>
<p>
<?php echo __( 'For System (WordPress) or plugin generated email notifications', 'wp_email_template' ); ?>:
<ol>
	<li><?php echo __( 'Click the blue [ Add New ] button.', 'wp_email_template' ); ?></li>
	<li><?php echo __( 'Email Sent by field. This is only for your reference, example WordPress or plugin name.', 'wp_email_template' ); ?></li>
	<li><?php echo __( "Email Subject Title field. Enter the email subject title. Example 'Password Recovery'.", 'wp_email_template' ); ?></li>
	<li><?php echo __( 'For Dynamic Email Subject Titles enter the constant section of the title only.', 'wp_email_template' ); ?></li>
</ol>
</p>
<p>
<strong><?php echo __( 'Example', 'wp_email_template' ); ?></strong> - <?php echo __( "The Wordfence plugin sends an activity summary email to admins that has a dynamic subject title in this format 'Wordfence activity for May 18, 2015 on xxxxxxxxxx.com.au'. The constant part of the title is 'Wordfence activity for'. Enter that and the template will not be applied to any Wordfence activity summary emails.", 'wp_email_template' ); ?>
</p>
<p>
<strong><?php echo __( 'WooCommerce', 'wp_email_template' ); ?></strong><br />
<?php echo __( 'Exclude by Subject Title does not work with WooCommerce Emails due to the way WooCommerce templates are applied.', 'wp_email_template' ); ?>
</p>

</div>
<table>
<?php
	}

	public function include_script() {
	?>
<script>
(function($) {
$(document).ready(function() {

	var tr_html = '<tr><td><input class="email_sent_by" type="text" name="email_sent_by[]" value="" /></td><td><input class="email_subject" type="text" name="email_subjects[]" value="" /></td><td><input type="button" class="button exclude_email_remove" value="&ndash;" /></td></tr>';

	$('.add-new-email-subject').on( 'click', function(){
		$('.exclude_email_subject_lists tbody').append(tr_html);
	});
	$('.remove-all-email-subject').on( 'click', function(){
		$('.exclude_email_subject_lists tbody').html('');
	});

	$(document).on( 'click', '.exclude_email_remove', function(){
		$(this).parents('tr').remove();
	});

});
})(jQuery);
</script>
    <?php
	}
}

global $wp_email_template_exclude_emails_settings;
$wp_email_template_exclude_emails_settings = new WP_Email_Template_Exclude_Emails_Settings();

/**
 * wp_email_template_exclude_emails_settings_form()
 * Define the callback function to show subtab content
 */
function wp_email_template_exclude_emails_settings_form() {
	global $wp_email_template_exclude_emails_settings;
	$wp_email_template_exclude_emails_settings->settings_form();
}

?>
