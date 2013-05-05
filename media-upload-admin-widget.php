<?php
/**
  * Plugin Name: Media Upload Admin Widget
  * Plugin URI: http://mywebsiteadvisor.com/tools/wordpress-plugins/media-upload-admin-widget/
  * Description: Adds an Admin Widget for Drag and Drop Media Upload to the WP Admin Dashboard
  * Version:  1.0
  * Author: MyWebsiteAdvisor
  * Author URI: http://MyWebsiteAdvisor.com/
  **/



class Media_Upload_Admin_Widget{
	
	private $plugin_name = "";
	
	/**
	 * Initialize plugin
	 */
	public function __construct(){

		$this->plugin_name = basename(dirname( __FILE__ ));
		
		// add links for plugin help, donations,...
		add_filter('plugin_row_meta', array(&$this, 'add_plugin_links'), 10, 2);
			
		//hook onto wp_dashboard_setup action
		add_action('wp_dashboard_setup', array(&$this, 'add_dashboard_widget' )); 
	}
	

	
	
	/**
	 * Add links on installed plugin list
	 */
	public function add_plugin_links($links, $file) {
		if($file == plugin_basename( __FILE__ )) {
			$upgrade_url = 'http://mywebsiteadvisor.com/tools/wordpress-plugins/' . $this->plugin_name . '/';
			$links[] = '<a href="'.$upgrade_url.'" target="_blank" title="Click Here to Upgrade this Plugin!">Upgrade Plugin</a>';
			
			$install_url = admin_url()."plugins.php?page=MyWebsiteAdvisor";
			$links[] = '<a href="'.$install_url.'" target="_blank" title="Click Here to Install More Free Plugins!">More Plugins</a>';
			
			$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . $this->plugin_name . '?rate=5#postform';
			$links[] = '<a href="'.$rate_url.'" target="_blank" title="Click Here to Rate and Review this Plugin on WordPress.org">Rate This Plugin</a>';
		}
		
		return $links;
	}
	
	
	
	
	

	//build admin widget
	function build_add_media_admin_widget(){
		
		wp_enqueue_script('plupload-handlers');
		
		$form_class='media-upload-form type-form validate';
		$post_id = -1;
		?>
		<style>
		#media-items {
			width: auto;
		}
		</style>
		<form enctype="multipart/form-data" method="post" action="<?php echo admin_url('media-new.php'); ?>" class="<?php echo $form_class; ?>" id="file-form">
	
			<?php media_upload_form(); ?>
		
			<script type="text/javascript">
			var post_id = <?php echo $post_id; ?>, shortform = 3;
			</script>
			<input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
			<?php wp_nonce_field('media-form'); ?>
			<div id="media-items" class="hide-if-no-js"></div>
		</form>
	 <?php
	}
	
	
	
	//register admin widget
	function add_dashboard_widget() {
		if(current_user_can('upload_files')){
			
			wp_add_dashboard_widget(
				'profile_add_media_admin_widget', 
				'Drag and Drop Photos Here!', 
				array(&$this, 'build_add_media_admin_widget')
			);	
			
		}
	} 
	
	
}


$media_upload_admin_widget = new Media_Upload_Admin_Widget;

?>