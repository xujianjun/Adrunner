<?php
/*
Plugin Name: Adrunner
Author: Curran Xu
Version: 1.0.1
Requires at least: 3.0.0
Tested up to: 3.4.0

Copyright 2012-2014 by Curran Xu

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License,or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not,write to the Free Software
Foundation,Inc.,51 Franklin St,Fifth Floor,Boston,MA 02110-1301 USA
*/
global $wp_version;
if((float)$wp_version >= 2.8){

include( "adrunner_class.php" );

add_action( 'widgets_init', 'adrunner_load_widgets' );

function adrunner_load_widgets() {
	register_widget('AdrunnerWidget');
}


add_action('admin_menu', 'adrunner_menu');

function adrunner_menu() {
  add_options_page('Adrunner options', 'Adrunner settings', 7, 'adrunner_options', 'adrunner_plugin_options');
  add_action( 'admin_init', 'register_adrunner_settings' );

}
function register_adrunner_settings() { // whitelist options
  register_setting( 'adrunner_options', 'adrunner_options_field' );
}
function adrunner_plugin_options(){
	?>
  <div class="wrap">
  <div id="icon-tools" class="icon32"></div>
  <h2>Adrunner setting</h2>
  <form method="post" action="options.php">
    <?php
    wp_nonce_field('update-options');
    settings_fields( 'adrunner_options' );
    $options = get_option('adrunner_options_field');
    $enable_single_show = $options["enable_single_show"];
    $single_show_title = $options["single_show_title"];
    ?>

    <table class="form-table">

      <tr valign="top">
        <th scope="row">enable single show</th>
        <td>
		<?php echo '<input name="adrunner_options_field[enable_single_show]" type="checkbox" value="1" class="code" ' . checked( 1, $enable_single_show, false ) . ' />';
		?>
		</td>
      </tr>
		<tr valign="top">
        <th scope="row">single show title</th>
        <td><input type='text'  name="adrunner_options_field[single_show_title]" value="<?php echo $single_show_title;?>"  /></td>
      </tr>
    <tr><td></td><td>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
    </td>
    </tr>
    <input type="hidden" name="action" value="update" />
  </form>
  </div>
<?php
}

add_filter("the_content", "adrunner_to_content");

function adrunner_to_content($content, $instance = array("title" => "Adrunner", "template" => 60)){
	if (!is_singular()){
		return $content;
	}
	$options = get_option('adrunner_options_field');
    $enable_single_show = $options["enable_single_show"];
    $single_show_title = $options["single_show_title"];

    $content_ext = "";
    if ($enable_single_show){
		//show widgets
		$instance = array("title" => $single_show_title, "slider" => false);
		ob_start();
		the_widget("AdrunnerWidget", $instance);
		$content_ext = ob_get_contents();
		ob_end_clean();
    }


	$content .= $content_ext;
	return $content;
}


}


