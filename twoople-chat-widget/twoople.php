<?php
   /*
   Plugin Name: Twoople Chat Widget
   Plugin URI: http://www.twoople.com/plugins/wordpress/v1
   Description: A plugin to add the Twoople Chat widget to the Wordpress site
   Version: 1.0.0
   Author: Twoople
   Author URI: http://www.twoople.com
   License: GPL2
   */

function twoople() {
	
  $twoople_username = get_option('twoople_widget_username');
  $twoople_position = get_option('twoople_widget_position');
  $twoople_header = get_option('twoople_widget_header');
  $twoople_style = get_option('twoople_widget_style');
  
  // DEFAULT VALUES
  if(!isset($twoople_username)) $twoople_username = 'support';
  if(!isset($twoople_position)) $twoople_position = 'bottom';
  if(!isset($twoople_header)) $twoople_header = 'Liva Chat';
  if(!isset($twoople_style)) $twoople_style = 'default';
  
  // embed script	
  echo '
		<!-- Twoople Chat Button BEGIN - http://www.twoople.com/ --> 
		<script type="text/javascript" src="http://localhost/twoople/_7/javascripts/twoople-widget.js" data-position="'.$twoople_position.'" data-style="'.$twoople_style.'" data-user="'.$twoople_username.'" data-header="' . $twoople_header . '"></script>
		<!-- Twoople Chat Button END -->';
}

add_action( 'wp_footer', 'twoople' );

// *** ADMIN

function twoople_activate_plugin() {
	// work-around to redirect to admin plugin page after plugin activiation
	add_option('twoople_do_activation_redirect', true);
}

function twoople_redirect() {
	// redirect to plugin admin page after plugin activation
	if (get_option('twoople_do_activation_redirect', false)) {
		delete_option('twoople_do_activation_redirect');
		wp_redirect(admin_url('admin.php?page=twoople-chat-widget/twoople.php'));
	}
}

add_action( 'admin_menu', 'twoople_admin_menu' );

register_activation_hook( __FILE__, 'twoople_activate_plugin' );
add_action('admin_init', 'twoople_redirect');


function twoople_admin_menu_exists( $handle, $sub = true){
  global $menu, $submenu;
  $check_menu = $sub ? $submenu : $menu;
  if( empty( $check_menu ) )
    return false;
  foreach( $check_menu as $k => $item ){
    if( $sub ){
      foreach( $item as $sm ){
        if($handle == $sm[2])
          return true;
      }
    } else {
      if( $handle == $item[2] )
        return true;
    }
  }
  return false;
}

function twoople_admin_menu() {
	$file = dirname( __FILE__ ) . '/twoople.php';
	$icon = "http://www.twoople.com/favicon.png";
	//if (! twoople_admin_menu_exists(dirname( __FILE__ ) . '/twoople.php')) {
		add_menu_page('Twoople Widget', 'Twoople Widget', 10, dirname( __FILE__ ) . '/twoople.php', '', $icon);
	//}
	add_submenu_page(dirname( __FILE__ ) . '/twoople.php', 'Settings', 'Settings', 'manage_options', dirname( __FILE__ ) . '/twoople.php', 'twoople_settings');	

}

function my_plugin_menu() {
	// deprecated code
	add_options_page( 'Twoople Widget Options', 'Twoople Widget', 'manage_options', 'twoopleWidgetPlugin', 'twoople_settings' );
}

function twoople_settings() {

  if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	// variables for the field and option names 
	$opt_name = 'twoople_widget_position';
	$hidden_field_name = 'twoople_submit_hidden';
	$data_field_name = 'twoople_widget_position';

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

		// Save the posted values in the database
		update_option( 'twoople_widget_position', $_POST['twoople_widget_position']);
		update_option( 'twoople_widget_username', $_POST['twoople_widget_username']);
		
		// If customer enters empty label, use default
		if($_POST['twoople_widget_header'] == '') {
				$label='Contact';
		} else {
				$label=stripslashes($_POST['twoople_widget_header']);
		}
		
		update_option( 'twoople_widget_header', $label);
		update_option( 'twoople_widget_style', $_POST['twoople_widget_style']);
		
		

		// Put an settings updated message on the screen

?>
<div class="updated"><p><?php _e('Settings saved. <strong><a href="' . get_site_url() . '">Visit your site</a></strong> to check your new widget settings.', 'menu-general' ); ?></p></div>
<?php

    }

    // Now display the settings editing screen
    echo '<div class="wrap">';

    // header
    echo "<div id='icon-options-general' class='icon32'><br></div><h2>" . __( 'Twoople Widget Options', 'menu-general' ) . "</h2>";

    // settings form
  
    ?>

    <?php
      // Read in existing option value from database
      $opt_position = get_option( 'twoople_widget_position' );
      $opt_style = get_option( 'twoople_widget_style' );
			$opt_header = get_option( 'twoople_widget_header' );
    ?>
	<p>Get your very own chat bar on your website within seconds! Your visitors will be just a click away from talking to you. The twoople chat widget is attached to the bottom-right corner of your website by default. Change this and other widget settings below.</p>
	
	<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<p>To configure the plugin you must have a Twoople account. If you don't have a Twoople account, <a href="http://www.twoople.com" target="_blank" title="Sign up for a free Twoople account" rel="nofollow">sign up here</a>.</p>
		<br>
		<p><h3><strong><?php _e("Your Twoople username (eg. mycompanyname)", 'menu-test' ); ?></h3></strong>
		Enter your Twoople username below. This field is mandatory. If it is not specified, the button will not appear on the site.<br><br>
		<table style="margin-left:20px">
			<tr>
				<td style="width:160px">User Name:</td>
				<td>
				http://twoople.com/<input type="text" name="twoople_widget_username" size="20" style="font-weight: bold" value="<?php echo get_option('twoople_widget_username') ?>">
				</td>
			</tr>
		</table>
		<p><h3><strong><?php _e("Appearance", 'menu-test' ); ?></strong></h3>
		Specify how the chat button appears on your site<br><br>
		<table style="margin-left:20px">
			<tr>
				<td style="width:160px">Widget Position:</td>
				<td>
					<select name="<?php echo 'twoople_widget_position'; ?>" style="width:200px" value="">
						<option <?php if ($opt_position === 'bottom') echo 'selected="true"' ?> value="bottom">Bottom (default)</option>
						<option <?php if ($opt_position === 'side') echo 'selected="true"' ?> value="side">Side</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width:160px">Widget Style:</td>
				<td>
					<select name="<?php echo 'twoople_widget_style'; ?>" style="width:200px" value="">
						<option <?php if ($opt_style === '') echo 'selected="true"' ?> value="">Default</option>
						<option <?php if ($opt_style === 'style2') echo 'selected="true"' ?> value="style2">Bubble</option>
						<option <?php if ($opt_style === 'style3') echo 'selected="true"' ?> value="style3">Ribbon</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width:160px">Button label:</td>
				<td>
					<select name="<?php echo 'twoople_widget_header'; ?>" style="width:200px" value="">
						<option <?php if ($opt_header === 'live chat') echo 'selected="true"' ?> value="live chat">Live Chat</option>
						<option <?php if ($opt_header === 'live help') echo 'selected="true"' ?> value="live help">Live Help</option>
						<option <?php if ($opt_header === 'chat now') echo 'selected="true"' ?> value="chat now">Chat Now</option>
						<option <?php if ($opt_header === 'chat with us') echo 'selected="true"' ?> value="chat with us">Chat With Us</option>
						<option <?php if ($opt_header === 'instant message us') echo 'selected="true"' ?> value="instant message us">Instant Message Us</option>
					</select>
				</td>
			</tr>
		</table>

		<br />
		<hr />
		<p class="submit">

		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>

	</form>
<?php	
	echo '</div>';
}
?>