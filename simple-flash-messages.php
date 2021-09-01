<?php
	/*
	Plugin Name: Simple Flash Messages
	Plugin URI: 
	Description: Simple flash messages displayed at the top of your site with an option to close. Has nothing to do with Adobe Flash - the "flash message" here describes a style of alert familiar in web app development. Set text of message, and cookie behavior so repeat visitors won't be bothered.
	Version: 0.1
	Author: Justin Allen
	*/


	/**
	 * Build the flash message
	 *
	 */
	function sfm_flash_message() {
		// get enable / disable option
		$show_flash_message = get_option('sfm_flash_msg_display');
		$cookie_value = $_COOKIE["simple_flash_messages_display"];
		$path_limit = get_option('sfm_flash_msg_limit_path');
		$banner_style = get_option('sfm_flash_msg_style');

		switch ($banner_style) {
			case "sunrise":
				$background_color = "linear-gradient(185deg, rgba(210,128,228,1) 0%, rgba(247,204,26,1) 100%) !important";
				$text_color = "black";
				break;
			case "red":
				$background_color = "#b72726";
				$text_color = "white";
				break;
			case "yellow":
				$background_color = "#f9ef68";
				$text_color = "black";
				break;
			case "teal":
				$background_color = "#58b5ce";
				$text_color = "black";
				break;
			case "blue":
				$background_color = "#2727ce";
				$text_color = "white";
				break;
			case "purple":
				$background_color = "#d75aff";
				$text_color = "black";
				break;
		}

		// get svg x
		$svg = plugins_url('/x.svg', __FILE__);

		// if show flash option is on, and cookie value isn't set to hide
		if ($show_flash_message == 1 && $cookie_value != "hide") {
			// now, test for path limit for showing only on one page
			$uri = $_SERVER['REQUEST_URI']; // current page url

			if ($path_limit == false || $uri === $path_limit) {
				// var_dump($uri);
				// var_dump($path_limit);
				$str = '<div id="simple-flash" ';
				$str .= 'style="background: ' . $background_color . ';color: ' . $text_color . ' !important"';
				$str .= 'data-show-after='; // data attribute for JavaScript to set cookie expiration time
				$str .= get_option('sfm_flash_msg_show_after'); // show after this value, set in options
				$str .= '>';
				$flash_message = get_option('sfm_flash_msg_content');
				$str .= '<div id="close-simple-flash"><img src="' . $svg . '"></div>';
				$str .= $flash_message . '</div>';
				echo $str;				
			}
		}
	}
	add_action("__top_of_page", "sfm_flash_message");
	
	/* Conditional styles in header */
	function sfm_conditional_css() {
		// get enable / disable option
		$show_flash_message = get_option('sfm_flash_msg_display');
		$cookie_value = $_COOKIE["simple_flash_messages_display"];

		if ($show_flash_message == 1 && $cookie_value != "hide") {
			// $output = "<style>";
			// $output .= "#hot-topics { position: relative; top: 55px; }";
			// $output .= "</style>";
			echo $output;		
		}
	}
	add_action('wp_head','sfm_conditional_css');

	/**
	 * Plugin settings 
	 *
	 */

	// register the plugin settings page	
	function sfm_add_plugin_menu() {
		// add_menu_page params: page title, menu title, user capability, menu slug, callback function, icon url, menu position
		add_menu_page("Flash Messages", "Flash Messages", "manage_options", "flash-messages", "sfm_plugin_settings_page", null, 99);
	}
	add_action("admin_menu", "sfm_add_plugin_menu");

	// set up plugin settings page
	function sfm_plugin_settings_page() {
		?>
			<div class="wrap">
				<h1>Simple Flash Messages</h1>
				<p>This plugin lets you create and display simple flash messages displayed at the top of your site with an option to close. (This has nothing to do with Adobe Flash - the "flash message" here describes a style of alert familiar in web app development.) Set text of message, and cookie behavior so repeat visitors won't be bothered.</p>
				<form method="post" action="options.php">
					<?php
						settings_fields("sfm_settings");
						do_settings_sections("flash-messages");
						submit_button();
					?>
				</form>
			</div>
		<?php
	}

	// create plugin settings fields
	function sfm_flash_message_content() {
		?>
			<textarea id="flash_msg_content" name="sfm_flash_msg_content" rows="5" cols="50"><?php echo get_option('sfm_flash_msg_content') ?></textarea>
		<?php
	}	
	function sfm_flash_message_show_after() {
		?>
			<input type="text" name="sfm_flash_msg_show_after" id="flash_msg_show_after" value="<?php echo get_option('sfm_flash_msg_show_after'); ?>" />
		<?php
	}	
	function sfm_flash_message_display() {
		?>
			<input type="checkbox" name="sfm_flash_msg_display" id="flash_msg_display_checkbox" value="1" <?php checked(1, get_option('sfm_flash_msg_display'), true); ?>" />
		<?php
	}	
	function sfm_flash_message_limit_path() {
		?>
			<input type="text" name="sfm_flash_msg_limit_path" id="flash_msg_limit_path" value="<?php echo get_option('sfm_flash_msg_limit_path'); ?>" />
		<?php
	}
	function sfm_flash_message_style() {
		?>

	        <input type="radio" name="sfm_flash_msg_style" value="sunrise" <?php checked("sunrise", get_option('sfm_flash_msg_style'), true); ?>>Sunrise <br>
	        <input type="radio" name="sfm_flash_msg_style" value="red" <?php checked("red", get_option('sfm_flash_msg_style'), true); ?>>Red <br>
	        <input type="radio" name="sfm_flash_msg_style" value="yellow" <?php checked("yellow", get_option('sfm_flash_msg_style'), true); ?>>Yellow <br>
	        <input type="radio" name="sfm_flash_msg_style" value="teal" <?php checked("teal", get_option('sfm_flash_msg_style'), true); ?>>Teal <br>
	        <input type="radio" name="sfm_flash_msg_style" value="blue" <?php checked("blue", get_option('sfm_flash_msg_style'), true); ?>>Dark blue <br>
	        <input type="radio" name="sfm_flash_msg_style" value="purple" <?php checked("purple", get_option('sfm_flash_msg_style'), true); ?>>Purple <br>

		<?php
	}


	function sfm_display_flash_message_fields() {
		// display section heading and description
		add_settings_section("sfm_settings", "All Settings", null, "flash-messages");
		// display html of the fields - id, title, callback, page, section, args
		add_settings_field("sfm_flash_msg_content", "Flash message text content (text and basic HTML tags)", "sfm_flash_message_content", "flash-messages", "sfm_settings");
		add_settings_field("sfm_flash_msg_display", "Display the flash message?", "sfm_flash_message_display", "flash-messages", "sfm_settings");
		add_settings_field("sfm_flash_msg_style", "Select style option.", "sfm_flash_message_style", "flash-messages", "sfm_settings");
		add_settings_field("sfm_flash_msg_show_after", "Hide flash message for repeat visitors for how long? (In days. Zero or blank will not hide for repeat visitors.)", "sfm_flash_message_show_after", "flash-messages", "sfm_settings");
		add_settings_field("sfm_flash_msg_limit_path", "Limit to one page? Paste in relative path here, starting with '/' (just '/' for homepage).", "sfm_flash_message_limit_path", "flash-messages", "sfm_settings");
		// register settings - option group, option name
		register_setting("sfm_settings", "sfm_flash_msg_display");
		register_setting("sfm_settings", "sfm_flash_msg_style");
		register_setting("sfm_settings", "sfm_flash_msg_show_after");
		register_setting("sfm_settings", "sfm_flash_msg_limit_path");
	}

	add_action("admin_init", "sfm_display_flash_message_fields");


	/**
	 * Plugin styles and script files 
	 *
	 */
	function sfm_register_static_files() {
		wp_register_script('simple_flash_messages_cookie_js', plugins_url('/js.cookie.js', __FILE__));
		wp_register_script('simple_flash_messages_javascript', plugins_url('/simple-flash-messages.js', __FILE__));
		wp_register_style( 'sfm_styles', plugins_url('/sfm-styles.css', __FILE__), false);
	}
	add_action('init', 'sfm_register_static_files');

	function sfm_enqueue_static_files(){
		wp_enqueue_script('simple_flash_messages_cookie_js');
		wp_enqueue_script('simple_flash_messages_javascript');
		wp_enqueue_style('sfm_styles');
	}
	add_action('wp_enqueue_scripts', 'sfm_enqueue_static_files');


?>