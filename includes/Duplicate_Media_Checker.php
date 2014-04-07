<?php
/**
 * Duplicate_Media_Checker.php
 * 
 * @created 4/6/14 1:33 PM
 * @author Mindshare Studios, Inc.
 * @copyright Copyright (c) 2014
 * @link http://www.mindsharelabs.com/documentation/
 * 
 */


class Duplicate_Media_Checker {

	/**
	 * @var Duplicate_Media_Checker
	 * @since 0.1
	 */
	private static $instance;

	/**
	 * Main Duplicate_Media_Checker Instance
	 *
	 * Insures that only one instance of Duplicate_Media_Checker exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since     0.1
	 * @static
	 * @staticvar array $instance
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Duplicate_Media_Checker ) ) {
			self::$instance = new Duplicate_Media_Checker;
			self::$instance->actions();
		}

		return self::$instance;
	}

	function actions() {

		add_filter('attachment_fields_to_edit', array($this, 'attachment_fields'), null, 2);
		add_filter('attachment_fields_to_save', array($this, 'save_attachment_fields'), 10, 2);
		add_action('wp_ajax_save-attachment-compat', array($this, 'save_attachment_fields_ajax'), 0, 1);
		add_filter( 'redirect_post_location', array($this, 'redirect_post_location'));
		add_action( 'admin_notices', array($this, 'my_admin_notice' ));

	}

	function attachment_fields($form_fields, $uploaded_file) {

		global $wpdb;
		$exists = false;

		$img_posts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type like 'attachment'");

		foreach($img_posts as $img) {

			if($img->post_title == $uploaded_file->post_title && $img->ID != $uploaded_file->ID) {
				$exists = true;
				$original = $img;
				break;
			}

		}

		if($exists) {

			$t = strtotime($uploaded_file->post_date);

			$form_fields["intro"]["tr"] = "
			<tr class='upload-errors' style='padding: 15px 0;'>
			    <td colspan='2' class='upload-error'>
					<span class='upload-error-label'>Duplicate file detected</span>
					<span class='upload-error-filename'>" . $uploaded_file->post_title . "</span>
					<span class='upload-error-message' style='padding-bottom: 5px;'>This file appears to be a duplicate of <a href='" . get_edit_post_link($original->ID) . "'>" . $original->post_title . "</a>,
					uploaded on " . date('n/j/y',$t) . ".</span>
					<input type='checkbox' value='1' name='attachments[" . $uploaded_file->ID . "][use_existing]' id='attachments[" . $uploaded_file->ID . "][use_existing]'/><span style='padding-left:4px;'>Use existing file</span>
					<input type='hidden' name='attachments[" . $uploaded_file->ID . "][original]' id='attachments[" . $uploaded_file->ID . "][original]' value='" . $original->ID . "'/>
			    </td>
			</tr>";
		}

		return $form_fields;

	}


	function redirect_post_location( $location ) {

		if ( isset( $_POST['attachments'][$_POST['post_ID']]['use_existing'] ) ) {
			set_transient( 'dmc-transient', 'Duplicate media file successfully removed.');
			return admin_url( "upload.php" );
		}

		return $location;
	}

	function my_admin_notice() {
		if($transient = get_transient('dmc-transient')) { ?>
			<div class="updated">
				<p><?php echo $transient; ?></p>
			</div>
		<?php }
	}

	function save_attachment_fields($post, $attachment) {

		if(isset($attachment['use_existing']) && $attachment['use_existing'] == '1') {

			$original_file = get_post( $attachment['original'] );

			if( false !== wp_delete_attachment( $post['ID'], true ) ) {

				global $wpdb;

				$wpdb->query(
					$wpdb->prepare(
						"UPDATE wp_posts SET post_content = REPLACE(post_content, '%s', '%s')",
						$post['attachment_url'], $original_file->guid
					)
				);
			}
		}

		return $post;

	}

	function save_attachment_fields_ajax() {

		$post_id = $_POST['id'];

		if($_POST['attachments'][$post_id]['use_existing'] == '1') {
			$result = wp_delete_attachment( $post_id, true );
		}

		return;

	}

}

/**
 * The main function responsible for returning the one true Duplicate_Media_Checker
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $dmc = duplicate_media_checker(); ?>
 *
 * @since 0.1
 * @return object The one true Easy_Digital_Downloads Instance
 */
function duplicate_media_checker() {
	return Duplicate_Media_Checker::instance();
}

// Get Duplicate Media Checker Running
duplicate_media_checker();
