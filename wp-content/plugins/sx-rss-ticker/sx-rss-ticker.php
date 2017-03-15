<?php
/*
Plugin Name: Sx Rss Ticker
Plugin URI: http://www.redweb.tn/sx-rss-ticker-wordpress-plugin
Description: Sx RSS Ticker allows you to place the contents of an RSS feed into your pages or posts. It uses the jQuery easy ticker plugin to add a news ticker like effect to the RSS feeds. In the admin we have option to add the settings for links RSS feed.
Author: Sabri El Gueder
Author URI: http://www.sabri-elgueder.tn/
Version: 2.0
Tags: rss, feed, slider, ticker, news
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;

if (!defined('WP_CONTENT_URL')) {
    $srt_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/';
} else {
    $srt_url = WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/';
}


define('SRT_VERSION', '2.0');
define('SRT_AUTHOR', 'Sabri El Gueder');
define('SRT_URL', $srt_url);

//SRT_URL

function sxrssticker_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'sx-rss-ticker', get_option('siteurl').'/wp-content/plugins/sx-rss-ticker/srt-js.js');
		wp_enqueue_style( 'sx-rss-ticker', get_option('siteurl').'/wp-content/plugins/sx-rss-ticker/srt-css.css');
	}	
}

function sxrssticker_install() 
{
	global $wpdb;
	add_option('sxrssticker_setting_s1', "up_easing_slow_2000_auto_0_1_500");
	add_option('sxrssticker_setting_s2', "down_easing_fast_4000_auto_3_0_500");
	add_option('sxrssticker_setting_s3', "up_easing_slow_2000_auto_0_1_500");	
	add_option('sxrssticker_setting_s4', "down_easing_fast_4000_auto_3_0_500");	
}

function sxrssticker_admin_options() 
{
	global $wpdb;
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-plugins" class="icon32 icon32-posts-post"></div>
		<?php
		$sxrssticker_setting_s1 = get_option('sxrssticker_setting_s1');
		$sxrssticker_setting_s2 = get_option('sxrssticker_setting_s2');
		$sxrssticker_setting_s3 = get_option('sxrssticker_setting_s3');
		$sxrssticker_setting_s4 = get_option('sxrssticker_setting_s4');
		
		$sxrssticker_setting_s1_new = explode("_", $sxrssticker_setting_s1);
		$sxrssticker_direction_1	= @$sxrssticker_setting_s1_new[0];
		$sxrssticker_easing_1		= @$sxrssticker_setting_s1_new[1];
		$sxrssticker_speed_1		= @$sxrssticker_setting_s1_new[2];
		$sxrssticker_interval_1		= @$sxrssticker_setting_s1_new[3];
		$sxrssticker_height_1		= @$sxrssticker_setting_s1_new[4];
		$sxrssticker_display_1		= @$sxrssticker_setting_s1_new[5];
		$sxrssticker_mousepause_1	= @$sxrssticker_setting_s1_new[6];
		$sxrssticker_textlength_1		= @$sxrssticker_setting_s1_new[7];
		
		$sxrssticker_setting_s2_new = explode("_", $sxrssticker_setting_s2);
		$sxrssticker_direction_2	= @$sxrssticker_setting_s2_new[0];
		$sxrssticker_easing_2		= @$sxrssticker_setting_s2_new[1];
		$sxrssticker_speed_2		= @$sxrssticker_setting_s2_new[2];
		$sxrssticker_interval_2		= @$sxrssticker_setting_s2_new[3];
		$sxrssticker_height_2		= @$sxrssticker_setting_s2_new[4];
		$sxrssticker_display_2		= @$sxrssticker_setting_s2_new[5];
		$sxrssticker_mousepause_2	= @$sxrssticker_setting_s2_new[6];
		$sxrssticker_textlength_2		= @$sxrssticker_setting_s2_new[7];
		
		$sxrssticker_setting_s3_new = explode("_", $sxrssticker_setting_s3);
		$sxrssticker_direction_3	= @$sxrssticker_setting_s3_new[0];
		$sxrssticker_easing_3		= @$sxrssticker_setting_s3_new[1];
		$sxrssticker_speed_3		= @$sxrssticker_setting_s3_new[2];
		$sxrssticker_interval_3		= @$sxrssticker_setting_s3_new[3];
		$sxrssticker_height_3		= @$sxrssticker_setting_s3_new[4];
		$sxrssticker_display_3		= @$sxrssticker_setting_s3_new[5];
		$sxrssticker_mousepause_3	= @$sxrssticker_setting_s3_new[6];
		$sxrssticker_textlength_3		= @$sxrssticker_setting_s3_new[7];
		
		$sxrssticker_setting_s4_new = explode("_", $sxrssticker_setting_s4);
		$sxrssticker_direction_4	= @$sxrssticker_setting_s4_new[0];
		$sxrssticker_easing_4		= @$sxrssticker_setting_s4_new[1];
		$sxrssticker_speed_4		= @$sxrssticker_setting_s4_new[2];
		$sxrssticker_interval_4		= @$sxrssticker_setting_s4_new[3];
		$sxrssticker_height_4		= @$sxrssticker_setting_s4_new[4];
		$sxrssticker_display_4		= @$sxrssticker_setting_s4_new[5];
		$sxrssticker_mousepause_4	= @$sxrssticker_setting_s4_new[6];
		$sxrssticker_textlength_4		= @$sxrssticker_setting_s4_new[7];
		
		if (isset($_POST['sxrssticker_form_submit']) && $_POST['sxrssticker_form_submit'] == 'yes')
		{
			check_admin_referer('sxrssticker_form_setting');
				
			$sxrssticker_direction_1	= stripslashes($_POST['sxrssticker_direction_1']);
			$sxrssticker_easing_1		= stripslashes($_POST['sxrssticker_easing_1']);
			$sxrssticker_speed_1		= stripslashes($_POST['sxrssticker_speed_1']);
			$sxrssticker_interval_1		= stripslashes($_POST['sxrssticker_interval_1']);
			$sxrssticker_height_1		= stripslashes($_POST['sxrssticker_height_1']);
			$sxrssticker_display_1		= stripslashes($_POST['sxrssticker_display_1']);
			$sxrssticker_mousepause_1	= stripslashes($_POST['sxrssticker_mousepause_1']);
			$sxrssticker_textlength_1		= stripslashes($_POST['sxrssticker_textlength_1']);
						

			$sxrssticker_direction_2	= stripslashes($_POST['sxrssticker_direction_2']);
			$sxrssticker_easing_2		= stripslashes($_POST['sxrssticker_easing_2']);
			$sxrssticker_speed_2		= stripslashes($_POST['sxrssticker_speed_2']);
			$sxrssticker_interval_2		= stripslashes($_POST['sxrssticker_interval_2']);
			$sxrssticker_height_2		= stripslashes($_POST['sxrssticker_height_2']);
			$sxrssticker_display_2		= stripslashes($_POST['sxrssticker_display_2']);
			$sxrssticker_mousepause_2	= stripslashes($_POST['sxrssticker_mousepause_2']);
			$sxrssticker_textlength_2		= stripslashes($_POST['sxrssticker_textlength_2']);
			
			$sxrssticker_direction_3	= stripslashes($_POST['sxrssticker_direction_3']);
			$sxrssticker_easing_3		= stripslashes($_POST['sxrssticker_easing_3']);
			$sxrssticker_speed_3		= stripslashes($_POST['sxrssticker_speed_3']);
			$sxrssticker_interval_3		= stripslashes($_POST['sxrssticker_interval_3']);
			$sxrssticker_height_3		= stripslashes($_POST['sxrssticker_height_3']);
			$sxrssticker_display_3		= stripslashes($_POST['sxrssticker_display_3']);
			$sxrssticker_mousepause_3	= stripslashes($_POST['sxrssticker_mousepause_3']);
			$sxrssticker_textlength_3		= stripslashes($_POST['sxrssticker_textlength_3']);
			
			$sxrssticker_direction_4	= stripslashes($_POST['sxrssticker_direction_4']);
			$sxrssticker_easing_4		= stripslashes($_POST['sxrssticker_easing_4']);
			$sxrssticker_speed_4		= stripslashes($_POST['sxrssticker_speed_4']);
			$sxrssticker_interval_4		= stripslashes($_POST['sxrssticker_interval_4']);
			$sxrssticker_height_4		= stripslashes($_POST['sxrssticker_height_4']);
			$sxrssticker_display_4		= stripslashes($_POST['sxrssticker_display_4']);
			$sxrssticker_mousepause_4	= stripslashes($_POST['sxrssticker_mousepause_4']);
			$sxrssticker_textlength_4		= stripslashes($_POST['sxrssticker_textlength_4']);
			
			$sxrssticker_setting_s1 = $sxrssticker_direction_1 . "_" . $sxrssticker_easing_1 . "_" . $sxrssticker_speed_1 . "_" . $sxrssticker_interval_1 . "_" . $sxrssticker_height_1 . "_" . $sxrssticker_display_1 . "_" . $sxrssticker_mousepause_1 . "_" . $sxrssticker_textlength_1;
			$sxrssticker_setting_s2 = $sxrssticker_direction_2 . "_" . $sxrssticker_easing_2 . "_" . $sxrssticker_speed_2 . "_" . $sxrssticker_interval_2 . "_" . $sxrssticker_height_2 . "_" . $sxrssticker_display_2 . "_" . $sxrssticker_mousepause_2 . "_" . $sxrssticker_textlength_2;
			$sxrssticker_setting_s3 = $sxrssticker_direction_3 . "_" . $sxrssticker_easing_3 . "_" . $sxrssticker_speed_3 . "_" . $sxrssticker_interval_3 . "_" . $sxrssticker_height_3 . "_" . $sxrssticker_display_3 . "_" . $sxrssticker_mousepause_3 . "_" . $sxrssticker_textlength_3;
			$sxrssticker_setting_s4 = $sxrssticker_direction_4 . "_" . $sxrssticker_easing_4 . "_" . $sxrssticker_speed_4 . "_" . $sxrssticker_interval_4 . "_" . $sxrssticker_height_4 . "_" . $sxrssticker_display_4 . "_" . $sxrssticker_mousepause_4 . "_" . $sxrssticker_textlength_4;
			
			update_option('sxrssticker_setting_s1', $sxrssticker_setting_s1 );
			update_option('sxrssticker_setting_s2', $sxrssticker_setting_s2 );
			update_option('sxrssticker_setting_s3', $sxrssticker_setting_s3 );
			update_option('sxrssticker_setting_s4', $sxrssticker_setting_s4 );
			
			?>
			<div class="updated fade">
				<p><strong><?php _e('Details successfully updated.', 'Redweb'); ?></strong></p>
			</div>
			<?php
		}
		?>
		<h2><?php _e('Sx Rss Ticker', 'Redweb'); ?></h2>
        <!--<a href="http://www.sabri-elgueder.tn/" target="_blank" tabindex="Sabri El Gueder">Sabri El Gueder</a>-->
        <hr />
		<form name="sxrssticker_form" method="post" action="">
        
        
		<h3><?php _e('Setting 1', 'Redweb'); ?></h3>
		<label for="tag-title"><?php _e('Direction', 'Redweb'); ?></label>
        <select name="sxrssticker_direction_1" id="sxrssticker_direction_1">
        	<option <?php if($sxrssticker_direction_1=='up') echo ' selected="selected"'; ?> value="up">up</option>
            <option <?php if($sxrssticker_direction_1=='down') echo ' selected="selected"'; ?> value="down">down</option>  
        </select>                  
		<p><?php _e('This property determines the direction of movement of the list.', 'Redweb'); ?></p>
 
		<label for="tag-title"><?php _e('Easing', 'Redweb'); ?></label>
        <select name="sxrssticker_easing_1" id="sxrssticker_easing_1">
        	<option <?php if($sxrssticker_easing_1=='swing') echo ' selected="selected"'; ?> value="swing">swing</option>
            <option <?php if($sxrssticker_easing_1=='easeInQuad') echo ' selected="selected"'; ?> value="easeInQuad">easeInQuad</option>
            <option <?php if($sxrssticker_easing_1=='easeOutQuad') echo ' selected="selected"'; ?> value="easeOutQuad">easeOutQuad</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutQuad') echo ' selected="selected"'; ?> value="easeInOutQuad">easeInOutQuad</option>
            <option <?php if($sxrssticker_easing_1=='easeInCubic') echo ' selected="selected"'; ?> value="easeInCubic">easeInCubic</option>
            <option <?php if($sxrssticker_easing_1=='easeOutCubic') echo ' selected="selected"'; ?> value="easeOutCubic">easeOutCubic</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutCubic') echo ' selected="selected"'; ?> value="easeInOutCubic">easeInOutCubic</option>
            <option <?php if($sxrssticker_easing_1=='easeInQuart') echo ' selected="selected"'; ?> value="easeInQuart">easeInQuart</option>
            <option <?php if($sxrssticker_easing_1=='easeOutQuart') echo ' selected="selected"'; ?> value="easeOutQuart">easeOutQuart</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutQuart') echo ' selected="selected"'; ?> value="easeInOutQuart">easeInOutQuart</option>
            <option <?php if($sxrssticker_easing_1=='easeInQuint') echo ' selected="selected"'; ?> value="easeInQuint">easeInQuint</option>
            <option <?php if($sxrssticker_easing_1=='easeOutQuint') echo ' selected="selected"'; ?> value="easeOutQuint">easeOutQuint</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutQuint') echo ' selected="selected"'; ?> value="easeInOutQuint">easeInOutQuint</option>
            <option <?php if($sxrssticker_easing_1=='easeInSine') echo ' selected="selected"'; ?> value="easeInSine">easeInSine</option>
            <option <?php if($sxrssticker_easing_1=='easeOutSine') echo ' selected="selected"'; ?> value="easeOutSine">easeOutSine</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutSine') echo ' selected="selected"'; ?> value="easeInOutSine">easeInOutSine</option>
            <option <?php if($sxrssticker_easing_1=='easeInExpo') echo ' selected="selected"'; ?> value="easeInExpo">easeInExpo</option>
            <option <?php if($sxrssticker_easing_1=='easeOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeOutExpo</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeInOutExpo</option>
            <option <?php if($sxrssticker_easing_1=='easeInCirc') echo ' selected="selected"'; ?> value="easeInCirc">easeInCirc</option>
            <option <?php if($sxrssticker_easing_1=='easeOutCirc') echo ' selected="selected"'; ?> value="easeOutCirc">easeOutCirc</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutCirc') echo ' selected="selected"'; ?> value="easeInOutCirc">easeInOutCirc</option>
            <option <?php if($sxrssticker_easing_1=='easeInElastic') echo ' selected="selected"'; ?> value="easeInElastic">easeInElastic</option>
            <option <?php if($sxrssticker_easing_1=='easeOutElastic') echo ' selected="selected"'; ?> value="easeOutElastic">easeOutElastic</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutElastic') echo ' selected="selected"'; ?> value="easeInOutElastic">easeInOutElastic</option>
            <option <?php if($sxrssticker_easing_1=='easeInBack') echo ' selected="selected"'; ?> value="easeInBack">easeInBack</option>
            <option <?php if($sxrssticker_easing_1=='easeOutBack') echo ' selected="selected"'; ?> value="easeOutBack">easeOutBack</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutBack') echo ' selected="selected"'; ?> value="easeInOutBack">easeInOutBack</option>
            <option <?php if($sxrssticker_easing_1=='easeInBounce') echo ' selected="selected"'; ?> value="easeInBounce">easeInBounce</option>
            <option <?php if($sxrssticker_easing_1=='easeOutBounce') echo ' selected="selected"'; ?> value="easeOutBounce">easeOutBounce</option>
            <option <?php if($sxrssticker_easing_1=='easeInOutBounce') echo ' selected="selected"'; ?> value="easeInOutBounce">easeInOutBounce</option>
            </select>
		<p><?php _e('The easing property allows to add some easing effects to the transition using the easing function available from the Easing plugin.', 'Redweb'); ?></p>
        
		<label for="tag-title"><?php _e('Speed of transition', 'Redweb'); ?></label>
		<input name="sxrssticker_speed_1" type="text" id="sxrssticker_speed_1" value="<?php echo $sxrssticker_speed_1; ?>" maxlength="4" />
		<p><?php _e('This property determines the speed of transition. Values: slow, medium, fast or any value in milliseconds.', 'Redweb'); ?> (Example: 'slow')</p>

		<label for="tag-title"><?php _e('Time for the next transition', 'Redweb'); ?></label>
		<input name="sxrssticker_interval_1" type="text" id="sxrssticker_interval_1" value="<?php echo $sxrssticker_interval_1; ?>" maxlength="4" />
		<p><?php _e('The time for the next transition to take place. Values: time in milliseconds.', 'Redweb'); ?> (Example: 2000)</p>

		<label for="tag-title"><?php _e('Each record height', 'Redweb'); ?></label>
		<input name="sxrssticker_height_1" type="text" id="sxrssticker_height_1" value="<?php echo $sxrssticker_height_1; ?>" maxlength="4" />
		<p><?php _e('The height of the element can be controlled by this property.', 'Redweb'); ?> (Example: 200)</p>
		<p><?php _e('If the height is set to auto, the height is automatically determined to fit the list.', 'Redweb'); ?></p>

        <label for="tag-title"><?php _e('Display records', 'Redweb'); ?></label>
		<input name="sxrssticker_display_1" type="text" id="sxrssticker_display_1" value="<?php echo $sxrssticker_display_1; ?>" maxlength="2" />
		<p><?php _e('The number of visible elements of the list can be set to this property.', 'Redweb'); ?></p>
		<p><?php _e('Values: 0 (display all) or any specific count like 1, 2, 3 etc.'); ?> </p>

		<label for="tag-title"><?php _e('Mouse pause', 'Redweb'); ?></label>
        <select name="sxrssticker_mousepause_1" id="sxrssticker_mousepause_1">
        	<option <?php if($sxrssticker_mousepause_1=='0') echo ' selected="selected"'; ?> value="0">Disable mouse pause</option>
            <option <?php if($sxrssticker_mousepause_1=='1') echo ' selected="selected"'; ?> value="1">Enable mouse pause</option>  
        </select>          
		<p><?php _e('The timer can be stopped when the mouse rolls over the element.', 'Redweb'); ?> </p>

		<label for="tag-title"><?php _e('Text length', 'Redweb'); ?></label>
		<input name="sxrssticker_textlength_1" type="text" id="sxrssticker_textlength_1" value="<?php echo $sxrssticker_textlength_1; ?>" maxlength="3" />
		<p><?php _e('Enter description text length.', 'Redweb'); ?> (Example: 500)</p>  

		<h3><?php _e('Setting 2', 'Redweb'); ?></h3>
		<label for="tag-title"><?php _e('Direction', 'Redweb'); ?></label>
        <select name="sxrssticker_direction_2" id="sxrssticker_direction_2">
        	<option <?php if($sxrssticker_direction_2=='up') echo ' selected="selected"'; ?> value="up">up</option>
            <option <?php if($sxrssticker_direction_2=='down') echo ' selected="selected"'; ?> value="down">down</option>  
        </select>                  
 
		<label for="tag-title"><?php _e('Easing', 'Redweb'); ?></label>
        <select name="sxrssticker_easing_2" id="sxrssticker_easing_2">
        	<option <?php if($sxrssticker_easing_2=='swing') echo ' selected="selected"'; ?> value="swing">swing</option>
            <option <?php if($sxrssticker_easing_2=='easeInQuad') echo ' selected="selected"'; ?> value="easeInQuad">easeInQuad</option>
            <option <?php if($sxrssticker_easing_2=='easeOutQuad') echo ' selected="selected"'; ?> value="easeOutQuad">easeOutQuad</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutQuad') echo ' selected="selected"'; ?> value="easeInOutQuad">easeInOutQuad</option>
            <option <?php if($sxrssticker_easing_2=='easeInCubic') echo ' selected="selected"'; ?> value="easeInCubic">easeInCubic</option>
            <option <?php if($sxrssticker_easing_2=='easeOutCubic') echo ' selected="selected"'; ?> value="easeOutCubic">easeOutCubic</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutCubic') echo ' selected="selected"'; ?> value="easeInOutCubic">easeInOutCubic</option>
            <option <?php if($sxrssticker_easing_2=='easeInQuart') echo ' selected="selected"'; ?> value="easeInQuart">easeInQuart</option>
            <option <?php if($sxrssticker_easing_2=='easeOutQuart') echo ' selected="selected"'; ?> value="easeOutQuart">easeOutQuart</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutQuart') echo ' selected="selected"'; ?> value="easeInOutQuart">easeInOutQuart</option>
            <option <?php if($sxrssticker_easing_2=='easeInQuint') echo ' selected="selected"'; ?> value="easeInQuint">easeInQuint</option>
            <option <?php if($sxrssticker_easing_2=='easeOutQuint') echo ' selected="selected"'; ?> value="easeOutQuint">easeOutQuint</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutQuint') echo ' selected="selected"'; ?> value="easeInOutQuint">easeInOutQuint</option>
            <option <?php if($sxrssticker_easing_2=='easeInSine') echo ' selected="selected"'; ?> value="easeInSine">easeInSine</option>
            <option <?php if($sxrssticker_easing_2=='easeOutSine') echo ' selected="selected"'; ?> value="easeOutSine">easeOutSine</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutSine') echo ' selected="selected"'; ?> value="easeInOutSine">easeInOutSine</option>
            <option <?php if($sxrssticker_easing_2=='easeInExpo') echo ' selected="selected"'; ?> value="easeInExpo">easeInExpo</option>
            <option <?php if($sxrssticker_easing_2=='easeOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeOutExpo</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeInOutExpo</option>
            <option <?php if($sxrssticker_easing_2=='easeInCirc') echo ' selected="selected"'; ?> value="easeInCirc">easeInCirc</option>
            <option <?php if($sxrssticker_easing_2=='easeOutCirc') echo ' selected="selected"'; ?> value="easeOutCirc">easeOutCirc</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutCirc') echo ' selected="selected"'; ?> value="easeInOutCirc">easeInOutCirc</option>
            <option <?php if($sxrssticker_easing_2=='easeInElastic') echo ' selected="selected"'; ?> value="easeInElastic">easeInElastic</option>
            <option <?php if($sxrssticker_easing_2=='easeOutElastic') echo ' selected="selected"'; ?> value="easeOutElastic">easeOutElastic</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutElastic') echo ' selected="selected"'; ?> value="easeInOutElastic">easeInOutElastic</option>
            <option <?php if($sxrssticker_easing_2=='easeInBack') echo ' selected="selected"'; ?> value="easeInBack">easeInBack</option>
            <option <?php if($sxrssticker_easing_2=='easeOutBack') echo ' selected="selected"'; ?> value="easeOutBack">easeOutBack</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutBack') echo ' selected="selected"'; ?> value="easeInOutBack">easeInOutBack</option>
            <option <?php if($sxrssticker_easing_2=='easeInBounce') echo ' selected="selected"'; ?> value="easeInBounce">easeInBounce</option>
            <option <?php if($sxrssticker_easing_2=='easeOutBounce') echo ' selected="selected"'; ?> value="easeOutBounce">easeOutBounce</option>
            <option <?php if($sxrssticker_easing_2=='easeInOutBounce') echo ' selected="selected"'; ?> value="easeInOutBounce">easeInOutBounce</option>
            </select>
        
		<label for="tag-title"><?php _e('Speed of transition', 'Redweb'); ?></label>
		<input name="sxrssticker_speed_2" type="text" id="sxrssticker_speed_2" value="<?php echo $sxrssticker_speed_2; ?>" maxlength="4" />

		<label for="tag-title"><?php _e('Time for the next transition', 'Redweb'); ?></label>
		<input name="sxrssticker_interval_2" type="text" id="sxrssticker_interval_2" value="<?php echo $sxrssticker_interval_2; ?>" maxlength="4" />

		<label for="tag-title"><?php _e('Each record height', 'Redweb'); ?></label>
		<input name="sxrssticker_height_2" type="text" id="sxrssticker_height_2" value="<?php echo $sxrssticker_height_2; ?>" maxlength="4" />

        <label for="tag-title"><?php _e('Display records', 'Redweb'); ?></label>
		<input name="sxrssticker_display_2" type="text" id="sxrssticker_display_2" value="<?php echo $sxrssticker_display_2; ?>" maxlength="2" />

		<label for="tag-title"><?php _e('Mouse pause', 'Redweb'); ?></label>
        <select name="sxrssticker_mousepause_2" id="sxrssticker_mousepause_2">
        	<option <?php if($sxrssticker_mousepause_2=='0') echo ' selected="selected"'; ?> value="0">Disable mouse pause</option>
            <option <?php if($sxrssticker_mousepause_2=='1') echo ' selected="selected"'; ?> value="1">Enable mouse pause</option>  
        </select>          

		<label for="tag-title"><?php _e('Text length', 'Redweb'); ?></label>
		<input name="sxrssticker_textlength_2" type="text" id="sxrssticker_textlength_2" value="<?php echo $sxrssticker_textlength_2; ?>" maxlength="3" />
        
		<h3><?php _e('Setting 3', 'Redweb'); ?></h3>
		<label for="tag-title"><?php _e('Direction', 'Redweb'); ?></label>
        <select name="sxrssticker_direction_3" id="sxrssticker_direction_3">
        	<option <?php if($sxrssticker_direction_3=='up') echo ' selected="selected"'; ?> value="up">up</option>
            <option <?php if($sxrssticker_direction_3=='down') echo ' selected="selected"'; ?> value="down">down</option>  
        </select>                  
 
		<label for="tag-title"><?php _e('Easing', 'Redweb'); ?></label>
        <select name="sxrssticker_easing_3" id="sxrssticker_easing_3">
        	<option <?php if($sxrssticker_easing_3=='swing') echo ' selected="selected"'; ?> value="swing">swing</option>
            <option <?php if($sxrssticker_easing_3=='easeInQuad') echo ' selected="selected"'; ?> value="easeInQuad">easeInQuad</option>
            <option <?php if($sxrssticker_easing_3=='easeOutQuad') echo ' selected="selected"'; ?> value="easeOutQuad">easeOutQuad</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutQuad') echo ' selected="selected"'; ?> value="easeInOutQuad">easeInOutQuad</option>
            <option <?php if($sxrssticker_easing_3=='easeInCubic') echo ' selected="selected"'; ?> value="easeInCubic">easeInCubic</option>
            <option <?php if($sxrssticker_easing_3=='easeOutCubic') echo ' selected="selected"'; ?> value="easeOutCubic">easeOutCubic</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutCubic') echo ' selected="selected"'; ?> value="easeInOutCubic">easeInOutCubic</option>
            <option <?php if($sxrssticker_easing_3=='easeInQuart') echo ' selected="selected"'; ?> value="easeInQuart">easeInQuart</option>
            <option <?php if($sxrssticker_easing_3=='easeOutQuart') echo ' selected="selected"'; ?> value="easeOutQuart">easeOutQuart</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutQuart') echo ' selected="selected"'; ?> value="easeInOutQuart">easeInOutQuart</option>
            <option <?php if($sxrssticker_easing_3=='easeInQuint') echo ' selected="selected"'; ?> value="easeInQuint">easeInQuint</option>
            <option <?php if($sxrssticker_easing_3=='easeOutQuint') echo ' selected="selected"'; ?> value="easeOutQuint">easeOutQuint</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutQuint') echo ' selected="selected"'; ?> value="easeInOutQuint">easeInOutQuint</option>
            <option <?php if($sxrssticker_easing_3=='easeInSine') echo ' selected="selected"'; ?> value="easeInSine">easeInSine</option>
            <option <?php if($sxrssticker_easing_3=='easeOutSine') echo ' selected="selected"'; ?> value="easeOutSine">easeOutSine</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutSine') echo ' selected="selected"'; ?> value="easeInOutSine">easeInOutSine</option>
            <option <?php if($sxrssticker_easing_3=='easeInExpo') echo ' selected="selected"'; ?> value="easeInExpo">easeInExpo</option>
            <option <?php if($sxrssticker_easing_3=='easeOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeOutExpo</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeInOutExpo</option>
            <option <?php if($sxrssticker_easing_3=='easeInCirc') echo ' selected="selected"'; ?> value="easeInCirc">easeInCirc</option>
            <option <?php if($sxrssticker_easing_3=='easeOutCirc') echo ' selected="selected"'; ?> value="easeOutCirc">easeOutCirc</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutCirc') echo ' selected="selected"'; ?> value="easeInOutCirc">easeInOutCirc</option>
            <option <?php if($sxrssticker_easing_3=='easeInElastic') echo ' selected="selected"'; ?> value="easeInElastic">easeInElastic</option>
            <option <?php if($sxrssticker_easing_3=='easeOutElastic') echo ' selected="selected"'; ?> value="easeOutElastic">easeOutElastic</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutElastic') echo ' selected="selected"'; ?> value="easeInOutElastic">easeInOutElastic</option>
            <option <?php if($sxrssticker_easing_3=='easeInBack') echo ' selected="selected"'; ?> value="easeInBack">easeInBack</option>
            <option <?php if($sxrssticker_easing_3=='easeOutBack') echo ' selected="selected"'; ?> value="easeOutBack">easeOutBack</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutBack') echo ' selected="selected"'; ?> value="easeInOutBack">easeInOutBack</option>
            <option <?php if($sxrssticker_easing_3=='easeInBounce') echo ' selected="selected"'; ?> value="easeInBounce">easeInBounce</option>
            <option <?php if($sxrssticker_easing_3=='easeOutBounce') echo ' selected="selected"'; ?> value="easeOutBounce">easeOutBounce</option>
            <option <?php if($sxrssticker_easing_3=='easeInOutBounce') echo ' selected="selected"'; ?> value="easeInOutBounce">easeInOutBounce</option>
            </select>
        
		<label for="tag-title"><?php _e('Speed of transition', 'Redweb'); ?></label>
		<input name="sxrssticker_speed_3" type="text" id="sxrssticker_speed_3" value="<?php echo $sxrssticker_speed_3; ?>" maxlength="4" />

		<label for="tag-title"><?php _e('Time for the next transition', 'Redweb'); ?></label>
		<input name="sxrssticker_interval_3" type="text" id="sxrssticker_interval_3" value="<?php echo $sxrssticker_interval_3; ?>" maxlength="4" />

		<label for="tag-title"><?php _e('Each record height', 'Redweb'); ?></label>
		<input name="sxrssticker_height_3" type="text" id="sxrssticker_height_3" value="<?php echo $sxrssticker_height_3; ?>" maxlength="4" />

        <label for="tag-title"><?php _e('Display records', 'Redweb'); ?></label>
		<input name="sxrssticker_display_3" type="text" id="sxrssticker_display_3" value="<?php echo $sxrssticker_display_3; ?>" maxlength="2" />

		<label for="tag-title"><?php _e('Mouse pause', 'Redweb'); ?></label>
        <select name="sxrssticker_mousepause_3" id="sxrssticker_mousepause_3">
        	<option <?php if($sxrssticker_mousepause_3=='0') echo ' selected="selected"'; ?> value="0">Disable mouse pause</option>
            <option <?php if($sxrssticker_mousepause_3=='1') echo ' selected="selected"'; ?> value="1">Enable mouse pause</option>  
        </select>          

		<label for="tag-title"><?php _e('Text length', 'Redweb'); ?></label>
		<input name="sxrssticker_textlength_3" type="text" id="sxrssticker_textlength_3" value="<?php echo $sxrssticker_textlength_3; ?>" maxlength="3" />

		<h3><?php _e('Setting 4', 'Redweb'); ?></h3>
		<label for="tag-title"><?php _e('Direction', 'Redweb'); ?></label>
        <select name="sxrssticker_direction_4" id="sxrssticker_direction_4">
        	<option <?php if($sxrssticker_direction_4=='up') echo ' selected="selected"'; ?> value="up">up</option>
            <option <?php if($sxrssticker_direction_4=='down') echo ' selected="selected"'; ?> value="down">down</option>  
        </select>                  
 
		<label for="tag-title"><?php _e('Easing', 'Redweb'); ?></label>
        <select name="sxrssticker_easing_4" id="sxrssticker_easing_4">
        	<option <?php if($sxrssticker_easing_4=='swing') echo ' selected="selected"'; ?> value="swing">swing</option>
            <option <?php if($sxrssticker_easing_4=='easeInQuad') echo ' selected="selected"'; ?> value="easeInQuad">easeInQuad</option>
            <option <?php if($sxrssticker_easing_4=='easeOutQuad') echo ' selected="selected"'; ?> value="easeOutQuad">easeOutQuad</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutQuad') echo ' selected="selected"'; ?> value="easeInOutQuad">easeInOutQuad</option>
            <option <?php if($sxrssticker_easing_4=='easeInCubic') echo ' selected="selected"'; ?> value="easeInCubic">easeInCubic</option>
            <option <?php if($sxrssticker_easing_4=='easeOutCubic') echo ' selected="selected"'; ?> value="easeOutCubic">easeOutCubic</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutCubic') echo ' selected="selected"'; ?> value="easeInOutCubic">easeInOutCubic</option>
            <option <?php if($sxrssticker_easing_4=='easeInQuart') echo ' selected="selected"'; ?> value="easeInQuart">easeInQuart</option>
            <option <?php if($sxrssticker_easing_4=='easeOutQuart') echo ' selected="selected"'; ?> value="easeOutQuart">easeOutQuart</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutQuart') echo ' selected="selected"'; ?> value="easeInOutQuart">easeInOutQuart</option>
            <option <?php if($sxrssticker_easing_4=='easeInQuint') echo ' selected="selected"'; ?> value="easeInQuint">easeInQuint</option>
            <option <?php if($sxrssticker_easing_4=='easeOutQuint') echo ' selected="selected"'; ?> value="easeOutQuint">easeOutQuint</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutQuint') echo ' selected="selected"'; ?> value="easeInOutQuint">easeInOutQuint</option>
            <option <?php if($sxrssticker_easing_4=='easeInSine') echo ' selected="selected"'; ?> value="easeInSine">easeInSine</option>
            <option <?php if($sxrssticker_easing_4=='easeOutSine') echo ' selected="selected"'; ?> value="easeOutSine">easeOutSine</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutSine') echo ' selected="selected"'; ?> value="easeInOutSine">easeInOutSine</option>
            <option <?php if($sxrssticker_easing_4=='easeInExpo') echo ' selected="selected"'; ?> value="easeInExpo">easeInExpo</option>
            <option <?php if($sxrssticker_easing_4=='easeOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeOutExpo</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutExpo') echo ' selected="selected"'; ?> value="easeOutExpo">easeInOutExpo</option>
            <option <?php if($sxrssticker_easing_4=='easeInCirc') echo ' selected="selected"'; ?> value="easeInCirc">easeInCirc</option>
            <option <?php if($sxrssticker_easing_4=='easeOutCirc') echo ' selected="selected"'; ?> value="easeOutCirc">easeOutCirc</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutCirc') echo ' selected="selected"'; ?> value="easeInOutCirc">easeInOutCirc</option>
            <option <?php if($sxrssticker_easing_4=='easeInElastic') echo ' selected="selected"'; ?> value="easeInElastic">easeInElastic</option>
            <option <?php if($sxrssticker_easing_4=='easeOutElastic') echo ' selected="selected"'; ?> value="easeOutElastic">easeOutElastic</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutElastic') echo ' selected="selected"'; ?> value="easeInOutElastic">easeInOutElastic</option>
            <option <?php if($sxrssticker_easing_4=='easeInBack') echo ' selected="selected"'; ?> value="easeInBack">easeInBack</option>
            <option <?php if($sxrssticker_easing_4=='easeOutBack') echo ' selected="selected"'; ?> value="easeOutBack">easeOutBack</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutBack') echo ' selected="selected"'; ?> value="easeInOutBack">easeInOutBack</option>
            <option <?php if($sxrssticker_easing_4=='easeInBounce') echo ' selected="selected"'; ?> value="easeInBounce">easeInBounce</option>
            <option <?php if($sxrssticker_easing_4=='easeOutBounce') echo ' selected="selected"'; ?> value="easeOutBounce">easeOutBounce</option>
            <option <?php if($sxrssticker_easing_4=='easeInOutBounce') echo ' selected="selected"'; ?> value="easeInOutBounce">easeInOutBounce</option>
            </select>

		<label for="tag-title"><?php _e('Speed of transition', 'Redweb'); ?></label>
		<input name="sxrssticker_speed_4" type="text" id="sxrssticker_speed_4" value="<?php echo $sxrssticker_speed_4; ?>" maxlength="4" />

		<label for="tag-title"><?php _e('Time for the next transition', 'Redweb'); ?></label>
		<input name="sxrssticker_interval_4" type="text" id="sxrssticker_interval_4" value="<?php echo $sxrssticker_interval_4; ?>" maxlength="4" />

		<label for="tag-title"><?php _e('Each record height', 'Redweb'); ?></label>
		<input name="sxrssticker_height_4" type="text" id="sxrssticker_height_4" value="<?php echo $sxrssticker_height_4; ?>" maxlength="4" />

        <label for="tag-title"><?php _e('Display records', 'Redweb'); ?></label>
		<input name="sxrssticker_display_4" type="text" id="sxrssticker_display_4" value="<?php echo $sxrssticker_display_4; ?>" maxlength="2" />

		<label for="tag-title"><?php _e('Mouse pause', 'Redweb'); ?></label>
        <select name="sxrssticker_mousepause_4" id="sxrssticker_mousepause_4">
        	<option <?php if($sxrssticker_mousepause_4=='0') echo ' selected="selected"'; ?> value="0">Disable mouse pause</option>
            <option <?php if($sxrssticker_mousepause_4=='1') echo ' selected="selected"'; ?> value="1">Enable mouse pause</option>  
        </select>          

		<label for="tag-title"><?php _e('Text length', 'Redweb'); ?></label>
		<input name="sxrssticker_textlength_4" type="text" id="sxrssticker_textlength_4" value="<?php echo $sxrssticker_textlength_4; ?>" maxlength="3" />
                		
	<hr />

		<div style="height:10px;"></div>
		<input type="hidden" name="sxrssticker_form_submit" value="yes"/>
		<input name="sxrssticker_submit" id="sxrssticker_submit" class="button add-new-h2" value="<?php _e('Update All Details', 'Redweb'); ?>" type="submit" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="window.open('http://www.redweb.tn/sx-rss-ticker-wordpress-plugin');" value="<?php _e('Help', 'Redweb'); ?>" type="button" />
		<?php wp_nonce_field('sxrssticker_form_setting'); ?>
	
		</form>	
	  </div>
	  <h3><?php _e('Plugin configuration option', 'Redweb'); ?></h3>
		<ol>
			<li><?php _e('Add plugin in the posts or pages using short code.', 'Redweb'); ?></li>
			<li><?php _e('Add directly in to the theme using PHP code.', 'Redweb'); ?></li>
		</ol>
	  <p class="description"><?php _e('Check official website for more information', 'Redweb'); ?> 
	  <a target="_blank" href="http://www.redweb.tn/sx-rss-ticker-wordpress-plugin"><?php _e('click here', 'Redweb'); ?></a></p>
	</div>
	<?php
}

function sxrssticker() {
	$arr = array();
	extract( shortcode_atts(
		array(
			'url' => 'http://www.wordpress.org/news/feed/',
			'setting' => '1'
		), $arr )
	);		
		
	echo sxrssticker_shortcode($arr);
}

function sxrssticker_shortcode( $atts ) 
{
	global $wpdb;
	//[sx-rss-ticker url="http://redweb.tn/" setting="1"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
		
	$sxrssticker_url	 = $atts['url'];
	$sxrssticker_set	 = $atts['setting'];
	
	switch ($sxrssticker_set) {
		case 1:
			$sxrssticker_newsetting = get_option('sxrssticker_setting_s1');
			break;
		case 2:
			$sxrssticker_newsetting = get_option('sxrssticker_setting_s2');
			break;
		case 3:
			$sxrssticker_newsetting = get_option('sxrssticker_setting_s3');
			break;
		case 4:
			$sxrssticker_newsetting = get_option('sxrssticker_setting_s4');
			break;
		default:
		   $sxrssticker_newsetting = get_option('sxrssticker_setting_s1');
	}

	//$url = $sxrssticker_url;

	$sxrssticker_setting = explode("_", $sxrssticker_newsetting);
	$sxrssticker_direction	= $sxrssticker_setting[0];
	$sxrssticker_easing		= $sxrssticker_setting[1];
	$sxrssticker_speed		= $sxrssticker_setting[2];
	$sxrssticker_interval	= $sxrssticker_setting[3];
	$sxrssticker_height		= $sxrssticker_setting[4];
	$sxrssticker_display	= $sxrssticker_setting[5];
	$sxrssticker_mousepause	= $sxrssticker_setting[6];
	$sxrssticker_textlength	= $sxrssticker_setting[7];
	
	if(empty($sxrssticker_speed)){ $sxrssticker_speed = 'slow'; }
	if(!is_numeric($sxrssticker_interval)){ $sxrssticker_interval = 2000; }
	if(!is_numeric($sxrssticker_height)){ $sxrssticker_height = 'auto'; }
	if(!is_numeric($sxrssticker_display)){ $sxrssticker_display = 0; }
	if(!is_numeric($sxrssticker_mousepause)){ $sxrssticker_mousepause = 1; }
	if(!is_numeric($sxrssticker_textlength)){ $sxrssticker_textlength = 250; }
	
	$xml = "";
	$validurl = "";
	$sxrssticker = "";
	$cnt=0;
	//$content = @file_get_contents($url);
	//if (strpos($http_response_header[0], "200")) 
	//{ 
	
		$cnt = 0;
		$maxitems = 0;
		$sxrssticker_count = 0;
		$sxrssticker_html = "";

		include_once( ABSPATH . WPINC . '/feed.php' );
		define("MAGPIE_OUTPUT_ENCODING", "UTF-8");
		$rss = fetch_feed( $sxrssticker_url );
		if ( ! is_wp_error( $rss ) )
		{
			$cnt = 0;
			$maxitems = $rss->get_item_quantity( 10 ); 
			$rss_items = $rss->get_items( 0, $maxitems );
					
			if ( $maxitems > 0 )
			{
				foreach ( $rss_items as $item )
				{
/*									
					$sxrssticker_html.= '<li><a href="' . esc_url($item->get_permalink()) . '">';
					$sxrssticker_html.= $item->get_date(get_option( 'date_format' )) . ' : ';
					if ($title_length != 0) {
						$sxrssticker_html.= substr(esc_html($item->get_title()) , 0, $title_length);
					} else {
						$sxrssticker_html.= esc_html($item->get_title());
					}
					$sxrssticker_html.= "</a></li>\n";
*/										
					$sxrssticker_link = $item->get_permalink();
					$sxrssticker_title = esc_sql($item->get_title());
					$sxrssticker_text = esc_sql($item->get_description());
					//$item->get_date(get_option( 'date_format' ))
	//                if ($title_length != 0) {
	//                    $sxrssticker_html.= substr(esc_html($item->get_title()) , 0, $title_length);
	//                } else {
	//                    $sxrssticker_html.= esc_html($item->get_title());
	//                }									
					
					$sxrssticker_target = "_blank";							
					$sxrssticker_text = strip_tags(strip_shortcodes($sxrssticker_text));
					$words = explode(' ', $sxrssticker_text, $sxrssticker_textlength + 1);
					if(count($words) > $sxrssticker_textlength)
					{
						array_pop($words);
						array_push($words, '...');
						$sxrssticker_text = implode(' ', $words);
					}
					$sxrssticker_text = nl2br($sxrssticker_text);
					$sxrssticker_text = str_replace("<br>", " ", $sxrssticker_text);
					$sxrssticker_text = str_replace("<br />", " ", $sxrssticker_text);
					$sxrssticker_text = str_replace("\r\n", " ", $sxrssticker_text);
					
					$sxrssticker_html = $sxrssticker_html . "<li>"; 
					
					if($sxrssticker_title <> "" ){
						$sxrssticker_html = $sxrssticker_html . "<div>";	
						if($sxrssticker_link <> "" ){ 
							$sxrssticker_html = $sxrssticker_html . "<a href='$sxrssticker_link'>"; 
						} 
						$sxrssticker_html = $sxrssticker_html . $sxrssticker_title;
						if($sxrssticker_link <> "" ){ 
							$sxrssticker_html = $sxrssticker_html . "</a>"; 
						}
						$sxrssticker_html = $sxrssticker_html . "</div>";
					}
					
					if($sxrssticker_text <> "" ){
						$sxrssticker_html = $sxrssticker_html . "<div>$sxrssticker_text</div>";	
					}
					
					$sxrssticker_html = $sxrssticker_html . "</li>".chr(13);
					
					$sxrssticker_count++;
					$cnt++;
				}
				
				if($sxrssticker_count >= $sxrssticker_display){
					$sxrssticker_count = $sxrssticker_display;
				} else {
					$sxrssticker_count = $sxrssticker_count;
				}
				
				$sxrssticker.= '<!-- Start - Sx RSS Ticker v' . SRT_VERSION . '-->'.chr(13);
				$sxrssticker.= '<div class="srt-bloc srt-list-'.$sxrssticker_set.'"><ul class="srt-clearfix">'.chr(13);
				if(!empty($sxrssticker_html)){
					$sxrssticker.= $sxrssticker_html;
				} else {
					$sxrssticker.= '<li>' . _e('No records found.', 'Redweb') . '</li>'.chr(13);
				}
				$sxrssticker.= "</ul></div>\n".chr(13);
				$sxrssticker.= '<!-- End - Sx RSS Ticker -->'.chr(13);
								
				$sxrssticker = $sxrssticker . '<script type="text/javascript">'.chr(13);
				$sxrssticker = $sxrssticker . 'jQuery(document).ready(function(){'.chr(13);
				$sxrssticker = $sxrssticker . '	if ( jQuery( ".srt-list-'.$sxrssticker_set.'" ).length ) {'.chr(13);
				$sxrssticker = $sxrssticker . '		jQuery(".srt-list-'.$sxrssticker_set.'").easyTicker({'.chr(13);
				$sxrssticker = $sxrssticker . '			direction: "'.$sxrssticker_direction.'",'.chr(13); 
				$sxrssticker = $sxrssticker . '			easing: "'.$sxrssticker_easing.'",'.chr(13); //http://gsgd.co.uk/sandbox/jquery/easing/
				$sxrssticker = $sxrssticker . '			speed: "'.$sxrssticker_speed.'",'.chr(13); 
				$sxrssticker = $sxrssticker . '			interval: '.$sxrssticker_interval.','.chr(13);
				$sxrssticker = $sxrssticker . '			height: "'.$sxrssticker_height.'",'.chr(13); 
				$sxrssticker = $sxrssticker . '			visible: '.$sxrssticker_display.','.chr(13); 
				$sxrssticker = $sxrssticker . '			mousePause: '.$sxrssticker_mousepause.chr(13); 
				$sxrssticker = $sxrssticker . '		});'.chr(13);
				$sxrssticker = $sxrssticker . '	}	'.chr(13);
				$sxrssticker = $sxrssticker . '});'.chr(13);		
				$sxrssticker = $sxrssticker . '</script>'.chr(13);	
			}
			else
			{
				$sxrssticker = "No records found.";
			}
		}
		else 
		{ 
			$sxrssticker = "RSS url is invalid or broken";
		}
	//}
	//else 
	//{ 
	//	$sxrssticker = "RSS url is invalid or broken";
	//}
	return $sxrssticker;
}

function sxrssticker_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page( __('Sx Rss Ticker', 'Redweb'), __('Sx Rss Ticker', 'Redweb'), 
								'manage_options', 'sx-rss-ticker', 'sxrssticker_admin_options' );
	}
}

function sxrssticker_deactivation() 
{
	// No action required.
}

function sxrssticker_textdomain() 
{
	  load_plugin_textdomain( 'sx-rss-ticker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


add_action('plugins_loaded', 'sxrssticker_textdomain');
add_shortcode( 'sx-rss-ticker', 'sxrssticker_shortcode' );
register_activation_hook(__FILE__, 'sxrssticker_install');
register_deactivation_hook(__FILE__, 'sxrssticker_deactivation');
add_action('admin_menu', 'sxrssticker_add_to_menu');
add_action('wp_enqueue_scripts', 'sxrssticker_add_javascript_files');
?>