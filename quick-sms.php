<?php
/*
Plugin Name: Quick SMS
PLugin URI: http://www.mutube.com/projects/wordpress/quick-sms/
Description: Allows your visitors to SMS messages direct to your mobile phone via email gateways. Many networks & countries now supported, see configuration panel for full list.
Author: Martin Fitzpatrick
Version: 2.11
Author URI: http://www.mutube.com
*/

/*  Copyright 2006  MARTIN FITZPATRICK  (email : martin.fitzpatrick@mutube.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function quicksms(){
	global $quicksms;
	
	$quicksms->display();
	
	
}

class quicksms {

	function display(){
		
		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_quicksms');

			/* If widget is public, or member is logged in */
			if( ($options['permissions']=='public') || ( is_user_logged_in()  ) )
			{


               	//Someone is sending an SMS
                if ( $_POST['quicksms-submit']=='sendsms' ) {
		
			// $options['provider'] contains the whole provider string, split to format/server
			$provider_data=explode("@",$options['provider']);
			// replace n in format string with number, stripping initial 0 (provided in format)
			$provider_data[0]=str_replace('n',ltrim($options['mobile'],0),$provider_data[0]);

	                $to=$provider_data[0] . '@' . $provider_data[1];

			$header=$options['header'];
			$header = str_replace("%blogname%", get_settings('blogname'), $header);

			global $userdata;
			get_currentuserinfo();

			$header = str_replace("%member%", $userdata->user_login, $header);
			$header = str_replace("%date%", date('l dS F Y h:i:s A'), $header);
			

                   	$subject="";
                   	$message=$header . strip_tags(stripslashes($_POST['quicksms-message']));
			$message=substr($message,0,$options['maxlength']);  //Trim message to max SMS number

                   	@mail($to, "", $message, $headers);

				?><div style="font-weight:bold;"><p>Thankyou, your SMS has been sent.</p>
				<p><small>Powered by <a href="http://www.mutube.com/projects/wordpress/quick-sms/?utm_source=quick-sms&utm_medium=plugin">Quick SMS</a></small></p></div><?php
               	} else {
               ?><div style="text-align:right;"><form id="quicksms" method="post" action=""><input name="quicksms-message" size="20"/><input type="hidden" id="quicksms-submit" name="quicksms-submit" value="sendsms"/><input value="Send SMS" name="submit" type="submit"/></form>
				<p style="text-align:right"><small>Powered by <a href="http://www.mutube.com/projects/wordpress/quick-sms/">Quick SMS</a></small></p>
				</div><?php
				}

			}

	}



		// Admin panel configuration
		function settings_control() {

			//Open data files of all networks - parse using PHP format
			$ini_array = parse_ini_file(dirname(__FILE__) . "/mobile-networks.ini", true);
		
			// Get our options and see if we're handling a form submission.
			$options = get_option('widget_quicksms');

			if ( 	( $_POST['quicksms-submit']=='options' ) && 
				( is_numeric($_POST['quicksms-maxlength'])) )
			{

				// Remember to sanitize and format use input appropriately.
				$options['provider'] = strip_tags(stripslashes($_POST['quicksms-provider']));
				$options['mobile'] = strip_tags(stripslashes($_POST['quicksms-mobile']));
				$options['permissions'] = strip_tags(stripslashes($_POST['quicksms-permissions']));
				$options['header'] = strip_tags(stripslashes($_POST['quicksms-header']));
				$options['maxlength'] = strip_tags(stripslashes($_POST['quicksms-maxlength']));

				update_option('widget_quicksms', $options);
			}

			// Be sure you format your options to be valid HTML attributes.
			$title = htmlspecialchars($options['title'], ENT_QUOTES);
			$provider = htmlspecialchars($options['provider'], ENT_QUOTES);
			$mobile = htmlspecialchars($options['mobile'], ENT_QUOTES);

			$header = htmlspecialchars($options['header'], ENT_QUOTES);
			$maxlength = intval($options['maxlength']);
			if($maxlength<=50){$maxlength=50;} else if($maxlength>8000){$maxlength=8000;}


			// Here is our little form segment. Notice that we don't need a
			// complete form. This will be embedded into the existing form.

		?>
		<div class="wrap">
		<h2>QuickSMS Options</h2>
		<div style="width:550px;margin-top:20px;">
		<form action="" method="post">
		<table>
		<tr><td><label for="quicksms-provider">Provider:</label></td><td>
		<select style="width: 200px;" id="quicksms-provider" name="quicksms-provider" title="Hover mouse over your network for activation instructions.">
                <?php
			foreach($ini_array as $country => $networks)
	                {
				   echo '<optgroup label="' . $country . '">'; 

				foreach($networks as $provider_name => $provider_detail)
				{

					$provider_name=substr($provider_name,strpos($provider_name," "));
					$provider_data=explode(":",$provider_detail);

	                      		echo '<option value="' . $provider_data[0] . '"';
	                      		if(strlen($provider_data[0])==2){echo ' style="font-weight:bold;" ';}
	                      		else if($provider_data[0]==$provider){echo ' selected="selected" ';}
	                      		echo ' title="' . $provider_data[1] .'">  ' . $provider_name . '</option>';
				}
					echo '</optgroup>';

	                }

                ?>
                </select></td></tr>
		<tr><td><label for="quicksms-mobile">Mobile:</label></td><td><input style="width: 200px;" id="quicksms-mobile" name="quicksms-mobile" type="text" value="<?php echo $mobile;?>" title="Enter your mobile number WITHOUT country code."/></td></tr>
		<tr><td><label for="quicksms-maxlength">Access:</label></td><td>
		<select name="quicksms-permissions">
		<option value="members" <?php if($options['permissions']=='members'){echo "selected";} ?>>Members</a>
		<option value="public" <?php if($options['permissions']=='public'){echo "selected";} ?>>Public</a>
		</select></td></tr>
		</table>

		<h4>Message Settings</h4>
		<table>
		<tr><td><label for="quicksms-header">Send:</label></td><td><input style="width: 200px;" id="quicksms-header" name="quicksms-header" type="text" value="<?php echo $header;?>" title="Enter %blogname% %member% %date% or any other text to be sent before your message."/></td></tr>
		<tr><td><label for="quicksms-maxlength">Max SMS Chars:</label></td><td><input style="width: 30px;" id="quicksms-maxlength" name="quicksms-maxlength" type="text" value="<?php echo $maxlength;?>" title="Enter Max number of chars allowed in SMS messages (trims to fit)."/></td></tr>
		</table>

		<input type="hidden" id="quicksms-submit" name="quicksms-submit" value="options" />
		
         <p class="submit"><input type="submit" value="Save changes &raquo;"></p>
         </form></div>
		</div>
				
                <?php
		}

		// This is the function that outputs quicksms writing-box
		function widget($args) {

			// $args is an array of strings that help widgets to conform to	
			// the active theme: before_widget, before_title, after_widget,
			// and after_title are the array keys. Default tags: li and h2.
			extract($args);
			$options = get_option('widget_quicksms');
			
			/* If widget is public, or member is logged in */
			if( ($options['permissions']=='public') || ( is_user_logged_in()  ) )
			{
				echo $before_widget . $before_title . $options['title'] . $after_title;
				$this->display();
				echo $after_widget;
			}
		}


	    function widget_control() {
			
		// Get our options and see if we're handling a form submission.
			$options = get_option('widget_quicksms');
			
			if ( $_POST['quicksms-submit']=='options' )
			{

				// Remember to sanitize and format use input appropriately.
				$options['title'] = strip_tags(stripslashes($_POST['quicksms-title']));
				update_option('widget_quicksms', $options);

			}
				
			?>
		<table>
		<tr><td><label for="quicksms-title">Title:</label></td><td><input style="width: 200px;" id="quicksms-title" name="quicksms-title" type="text" value="<?php echo $options['title'];?>" /></td></tr>
		</table>
		<input type="hidden" id="quicksms-submit" name="quicksms-submit" value="options" />
		<?php
		}

	function add_pages(){
         add_options_page("Quick SMS Options", "Quick SMS", 10, "quick-sms", array(&$this,'settings_control'));
	}
		

	// Put functions into one big function we'll call at the plugins_loaded
	// action. This ensures that all required plugin functions are defined.
	function init() {

			$options = get_option('widget_quicksms');
			if ( !is_array($options) )
			{
				
				$options = array(
					'title'=>'Quick SMS',
					'provider'=>'teleflip.com',
					'header'=>'%blogname%',
					'maxlength'=>160
				);
				
				update_option('widget_quicksms', $options);

			}

		
			if (function_exists('wp_register_sidebar_widget') )
			{   //Do Widget-specific code
				wp_register_sidebar_widget('quick-sms', 'Quick SMS', 'quicksms_widget');
				wp_register_widget_control('quick-sms', 'Quick SMS', 'quicksms_widget_control', 300, 100);
			}
			
			add_action('admin_menu', array(&$this,'add_pages'));
	
	}

}



$quicksms = new quicksms();


/*	
	SIDEBAR MODULES COMPATIBILITY KLUDGE 
	These functions are external to the class above to allow compatibility with SBM
	which does not allow calls to be passed to a class member.
	These functions are dummy passthru's for the real functions above
*/

	function quicksms_widget($args){
		global $quicksms;
		$quicksms->widget($args);
	}

	function quicksms_widget_control(){
		global $quicksms;
		$quicksms->widget_control();
	}

/*
	END DUMMY KLUDGE
*/


// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', array(&$quicksms,'init'));

?>
