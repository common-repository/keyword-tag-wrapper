<?php
/*
Plugin Name: Bridge SEO Tag Wrap Keywords
Plugin URI: http://www.bridgeseo.com/wordpress-plugins/seo-tag-wrap-keywords-wordpress-plugin
Description: Allows wp admins to bold, strong, em, i, or custom tag wrap keywords in all posts/pages for SEO enhancement.
Author: Bridge SEO
Version: 1.0
Author URI: http://www.bridgeseo.com/
*/
define(KT_BSEO, "KT_BSEO");
define(KT_BSEO_DB_VERSION, "1");		// change it when a database update is needed.
if ( is_admin() ){ // admin actions
  add_action('plugins_loaded', 'kt_bseo_activate');
}

if ( !defined('WP_CONTENT_URL')){
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}




function bseo_tag_keyword($text,$ReturnOrEcho=0){
   ///this function cal also be called from within a template page file
    global $wpdb;
    $kt_bseo_enabled = get_option('kt_bseo_enabled');
		if($kt_bseo_enabled == "1"){
        $query = "SELECT keyword, keyword_wrap_tag, keyword_wrap_tag_class, max_num_instances FROM ".$wpdb->prefix."kt_bseo_keywords WHERE is_active = '1' order by length(keyword) desc ";
    		$keywords_rs = $wpdb->get_results($query);
    		$spacer_replace = false;
				foreach ( (array) $keywords_rs as $kw_row_data ) {
    		  $kw = trim(stripslashes($kw_row_data->keyword));
    		  		  
    			$kw_wrapper 	= $kw_row_data->keyword_wrap_tag;///this will be the tag...ex: 'strong','b','i'
    			
    			$kw_wrapper_class 	= $kw_row_data->keyword_wrap_tag_class;
    			if($kw_wrapper_class != ""){
    			  $kwClass = " class=\"$kw_wrapper_class\"";
    			}else{
    			  $kwClass = "";
    			}
    			
    			
					$kw_wrapper_b = "<$kw_wrapper$kwClass>";
    			$kw_wrapper_e = "</$kw_wrapper>";
    			$max_num_instances = $kw_row_data->max_num_instances; ////ex: 3
    			
					////here we check to see how many words are in the keyword.  To prevent potential nested tags.  
					//if you have 2 keywords: "I Love SEO" and "LOVE", both keywords words will get tagged resulting in LOVE being double nested:
					//"<b>I <b>Love</b> SEO</b>"
					//to prevent this, we replace the spaces in I Love SEO with I~KWTSPACE~Love~KWTSPACE~SEO and then Love will not get tagged again
					//so if your keyword has 3 or more words in it we run a different pre_replace on it and then at the end, we will take out all
					//instances of ~KWTSPACE~
					$kw_spaces_cnt = substr_count($kw, ' ');
					if($kw_spaces_cnt > 1){
					   $pattern = "/( |[^$kw_wrapper_b]>)($kw)([^A-Za-z0-9])/ie";
				     $replacement = "'$1$kw_wrapper_b'.str_replace(' ','~KWTSPACE~','$2').'$kw_wrapper_e$3'";
				     $text = preg_replace($pattern, $replacement, $text,$max_num_instances);
						 $spacer_replace = true;
					}else{
					   $text = preg_replace("/( |[^$kw_wrapper_b]>)($kw)([^A-Za-z0-9])/i", "$1$kw_wrapper_b$2$kw_wrapper_e$3", $text,$max_num_instances);
    			}
    		 
				}///end foreach
				if($spacer_replace == true){
				  $text = str_replace("~KWTSPACE~"," ",$text);
		    }
		}///end of enabled 
		if($ReturnOrEcho == 0){
		   return $text;
    }else if($ReturnOrEcho == 1){
		   echo $text;
		}
}

function kt_bseo_options_admin_panel() {
  if ( is_admin() ){ // admin actions  
	  $kt_bseo_plugin_path = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
		echo '<script src="'.$kt_bseo_plugin_path.'/_scripts/javascript.js" type="text/javascript"></script>';

		process_kt_bseo_settings_panel_update();
		
		echo "<div class=\"wrap\">";
		echo "<h2>Bridge SEO  - Keyword Tag Wrapper v1.1</h2>";
		echo "<br />Developed by <a href=\"http://www.bridgeseo.com/\" target=\"_blank\">Bridge SEO</a>.<br />";	
	  echo "For information and updates, please visit:";
		echo "<a target=\"_blank\" href=\"http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper\">http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper</a>.<br />";
    echo "To offer Feature Suggestions or to Report Bugs, please visit:";
		echo "<a target=\"_blank\" href=\"http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-feedback\">http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-feedback</a>.<br />";
   	echo "<a target=\"_blank\" href=\"http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-faq\">FAQs</a> | ";
		echo "<a target=\"_blank\" href=\"http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-change-log\">Change Log</a>";
		echo "<br /><br />";
		
		$kt_bseo_enabled = get_option('kt_bseo_enabled');
		if($kt_bseo_enabled == 0){
		  echo "<font style=\"color:red\">This plugin is currently disabled, you can enable it in the \"Set Bridge SEO Keyword Tag Wrapper Settings\" section at the bottom of this page.</font><br /><br />";
		}
		
		echo "</div><!-- wrap -->";
		
		
		
		 echo "<div class=\"postbox\">";
		   add_new_keyword();
		 echo "</div>";

		echo "<div class=\"postbox\">";
		  delete_keyword();
		echo "</div>";
		
		echo "<div class=\"postbox\">";
		  kt_bseo_settings_panel();
		echo "</div>";
		
		////////////////Professional Assistance
		echo "
		<div style=\"border: 1px solid rgb(161, 161, 161); margin: 10px 0pt; padding: 10px; background: rgb(226, 243, 255) none repeat scroll 0% 0%;\">
	 <h4 style=\"margin: 0pt 0pt 10px;\">Need a professional SEO consultation about your website or a custom WordPress theme?</h4>
	<p style=\"font-size: 11px;\">If you are interested in hiring a professional SEO company, please contact us at 
	 <a target=\"_blank\" href=\"http://www.bridgeseo.com/free-seo-evaluation/\">Bridge SEO</a>. Bridge SEO is also highly experienced in developing custom 
	 WordPress themes.  If you are interested in receiving a quote for a custom theme, please <a href=\"http://www.bridgeseo.com/free-web-development-quote/\" target=\"_blank\">fill out our Free Quote request form</a>.
	</p>
</div>
		";
		
		
	}//end admin
}


function kt_bseo_options_page() {
    if (function_exists('add_options_page')) {
        add_options_page('SEO Tag Keywords', 'SEO Tag Keywords', 8, basename(__FILE__), 'kt_bseo_options_admin_panel');
    }
}


add_filter('the_content', 'bseo_tag_keyword');
add_action('admin_menu', 'kt_bseo_options_page');



function add_new_keyword(){
  global $wpdb;
	$message = '&nbsp;';
	if(isset($_POST['new_keyword'])) {
	 if($_POST['new_keyword'] != ''){
	   $keyword = strtolower(trim(strip_tags($_POST['new_keyword'])));
	   ///first we check to see if the keyword is already in use
		 $query = "SELECT keyword_id FROM ".$wpdb->prefix."kt_bseo_keywords WHERE keyword = '$keyword' ";
		 $keywords_rs = $wpdb->get_results($query);
     $kw_count = $wpdb->num_rows;
	   //////////////////////////
		 if($kw_count == 0){
  		 $keyword_wrap_tag = $_POST['keyword_wrap_tag'];
			 $keyword_wrap_tag_class = $_POST['new_keyword_class'];
  	   $max_num_instances = $_POST['max_num_instances'];
       $sql = "INSERT INTO ".$wpdb->prefix."kt_bseo_keywords(keyword,keyword_wrap_tag,keyword_wrap_tag_class,max_num_instances) values ('$keyword','$keyword_wrap_tag','$keyword_wrap_tag_class','$max_num_instances')";
  		 $result = $wpdb->query($sql);
			 $message = "<br /><br />Keyword [$keyword] Added<br />";
		 }else{
		   $message = "<br /><br /><font style=\"color:red\">New keyword [$keyword]  is already in use</font><br />";
		 }
	 }else{//not blank
	   $message = "<br /><br /><font style=\"color:red\">New keyword is blank</font><br />";
	 }
	}//end isset
	
	
	$default_tag = get_option('kt_bseo_default_tag');
	$default_tag_class = get_option('kt_bseo_default_tag_class');
	$default_max_ins = get_option('kt_bseo_default_max_num_instances');
	
	////create tag options for select dropdown
	$tag_options = '';
	//$tags = array('strong','b','i','em','h1','h2','h3','h4','h5','h6');
	$tags = explode("\r\n",get_option('kt_bseo_tag_options_list')); 
	foreach($tags as $key => $value){
	  if($value == $default_tag){$sel = " selected";}else{$sel = "";}
	  $tag_options .= "<option value=\"$value\"$sel>$value</option>";
	}//end foreach tag type to make dropdown
	
	////create max occorence options for select dropdown
	$moi = 1;
	$mo_options = '';
	while($moi < 21){
	  if($moi == $default_max_ins){$sel = " selected";}else{$sel = "";}
	  $mo_options .= "<option value=\"$moi\"$sel>$moi</option>";
		$moi++;
	}
	
	$form = "
	 <h4 class=\"hndle\"><span>Add New Keyword</span></h4>
	 <div style=\"margin-top:20px;margin-bottom:10px;padding:10px;border:1px solid #ccc;background-color:#eee;font-size:8pt;line-height:10pt;\">
	 Keywords are not case sensitive, all your case variations will be tagged.  For purposes of consistency, all keywords are listed below in lower case.
	 For example, if you add a keyword of 'website' and select 'b' as the tag; both \"Website\" and \"website\" will be wrapped in the b tag. 
	 Keywords are processed in order of the longest keyword string to the smallest; for example the keyword \"great seo experts\" will be processed before \"seo expert\".<br />
	 <br />We recommend 3 as the value for <i>Tagged Occurence Max</i>.  This value represents 
	 the number of times the tag will be added around the keyword in the currently viewed post or page.  Tag wrapping keywords too many times can 
	 have negative SEO impact.
	 <span style=\"font-weight:bold;\">$message</span>
	 </div>
	 <div style=\"padding-left:15px;\">
  	 <form name=\"AddNewKeywordForm\" method=\"post\" action=\"\">
  	   <label>Keyword:</label><input name=\"new_keyword\" type=\"text\" value=\"\" /><br />
  		 <label>Tag:</label>
  		 <select name=\"keyword_wrap_tag\">$tag_options</select><br />
				<label>Keyword Class:</label><input name=\"new_keyword_class\" type=\"text\" value=\"$default_tag_class\" />*optional<br />
  			<label>Tagged Occurence Max:</label>
  		 <select name=\"max_num_instances\">$mo_options</select><br /><br />
  		 <input style=\"margin-left:100px;\" type=\"submit\" value=\"Add New Keyword\" /><br /><br />
  	 </form>
		 </div>
	";
  echo $form;
}

?>
<?php
function delete_keyword(){
   global $wpdb;
	 $message = "&nbsp;";
	 if(isset($_POST["delete_keyword_id"])){
	    $delete_keyword_id = $_POST["delete_keyword_id"];
			if(is_numeric($delete_keyword_id)){
			  $sql = "DELETE FROM ".$wpdb->prefix."kt_bseo_keywords WHERE keyword_id = '$delete_keyword_id' LIMIT 1 ";
		    $result = $wpdb->query($sql);
				$message = "<br /><br />Keyword [".$_POST["delete_keyword"]."] Deleted<br />";
	    }else{
			  $message = "<br /><br /><font style=\"color:red\">Error deleting keyword</font><br />";
			}
	 }////end if delete posted
	 
	 
	 echo "<h4 class=\"hndle\"><span>Current Keywords</span></h4>";
	 echo "<div style=\"margin-top:20px;margin-bottom:10px;padding:10px;border:1px solid #ccc;background-color:#eee;font-size:8pt;line-height:10pt;\">
	 Click on DELETE to remove the keyword from the system. To UPDATE, delete current keyword and add again with new settings.
	 <span style=\"font-weight:bold;\">$message</span>
	 </div>";
	 
	 ////loop through current keywords
   $query = "SELECT keyword_id,keyword, keyword_wrap_tag, keyword_wrap_tag_class, max_num_instances FROM ".$wpdb->prefix."kt_bseo_keywords WHERE is_active = '1' order by keyword ASC ";
	 $keywords_rs = $wpdb->get_results($query);
	 
	 echo "<table class=\"widefat post fixed\" cellspacing=\"0\">";
	 echo "<thead><tr><th>Keyword</th><th>Tag</th><th>Tag Class</th><th>Occurence Max</th><th>Delete</th></tr></thead>";
	 foreach ( (array) $keywords_rs as $kw_row_data ) {
	   $keyword_id = $kw_row_data->keyword_id;
		 $keyword = $kw_row_data->keyword;
		 $keyword_wrap_tag = $kw_row_data->keyword_wrap_tag;
		 $keyword_wrap_tag_class = $kw_row_data->keyword_wrap_tag_class;
		 if($keyword_wrap_tag_class == ''){$keyword_wrap_tag_class = '-';}
		 $max_num_instances = $kw_row_data->max_num_instances;
		 echo "<tr><td>$keyword</td><td>$keyword_wrap_tag</td><td>$keyword_wrap_tag_class</td>".
		      "<td>$max_num_instances</td><td><a href=\"javascript:void(0);\" onclick=\"SubmitKWDeleter('$keyword_id','$keyword');return false;\">DELETE</a></td></tr>";
	 }///foreach
	 echo "<tfoot><tr><th>Keyword</th><th>Tag</th><th>Class</th><th>Occurence Max</th><th>Delete</th></tr></tfoot>";
	 echo "</table>";
	 $form = "
	   <form name=\"DeleteKeywordForm\" method=\"post\" action=\"\">
		 <input type=\"hidden\" name=\"delete_keyword_id\" id=\"delete_keyword_id\" value=\"\" />
		 <input type=\"hidden\" name=\"delete_keyword\" id=\"delete_keyword\" value=\"\" />
		 </form>
		 
	 ";
	 echo $form;
	 }
?>
<?php

function process_kt_bseo_settings_panel_update(){
 if(isset($_POST['ud_default_tag_options_list'])) {
	  ////////////////
		///////////////
		$ud_default_tag = $_POST["ud_default_tag"];
		$ud_default_tag_class = $_POST["ud_default_tag_class"];
		$ud_default_max_num_instances = $_POST["ud_default_max_num_instances"];
		$ud_default_tag_options_list = trim($_POST["ud_default_tag_options_list"]);
		
		if($ud_default_tag != ""){
		  update_option('kt_bseo_default_tag',$ud_default_tag);
	  }
		update_option('kt_bseo_default_tag_class',$ud_default_tag_class);///can be blank
	  
		if($ud_default_max_num_instances != ''){
		  update_option('kt_bseo_default_max_num_instances',$ud_default_max_num_instances);
	  }
		if($ud_default_tag_options_list != ""){
		  update_option('kt_bseo_tag_options_list',$ud_default_tag_options_list);
		}
		if(isset($_POST["ud_kt_bseo_enabled"])){
		   update_option('kt_bseo_enabled','1');
		}else{
		   update_option('kt_bseo_enabled','0');
		}
		///////////////		
		/////////////////
	
	}//end isset post update_kt_bseo_settings
}

function kt_bseo_settings_panel(){
 ///update is handled in previous function and is called before other panels are called so updates effect all sections
	///get the current default settings and the list for the Tag dropdown
	$default_tag = get_option('kt_bseo_default_tag');
	$default_tag_class = get_option('kt_bseo_default_tag_class');
	$default_max_ins = get_option('kt_bseo_default_max_num_instances');
	$default_tag_options_list = get_option('kt_bseo_tag_options_list');
	$kt_bseo_enabled = get_option('kt_bseo_enabled');
	///////////////////////////////////
	////create the tags dropdown for selecting the default
	////create tag options for select dropdown
	$tag_options = '';
	//$tags = array('strong','b','i','em','h1','h2','h3','h4','h5','h6');
	$tags = explode("\r\n",get_option('kt_bseo_tag_options_list')); 
	foreach($tags as $key => $value){
	  if($value == $default_tag){$sel = " selected";}else{$sel = "";}
	  $tag_options .= "<option value=\"$value\"$sel>$value</option>";
	}//end foreach tag type to make dropdown
	
	
	////create max occorence options for select dropdown
	$moi = 1;
	$mo_options = '';
	while($moi < 21){
	  if($moi == $default_max_ins){$sel = " selected";}else{$sel = "";}
	  $mo_options .= "<option value=\"$moi\"$sel>$moi</option>";
		$moi++;
	}
	if($kt_bseo_enabled == "1"){
	  $enabled_chkd = " checked";
	}else{
	  $enabled_chkd = "";
	}
	////////////////////////////////
	////Build the Settings Form
	$form = "
	 <h4 class=\"hndle\"><span>Set Bridge SEO Keyword Tag Wrapper Settings</span></h4>
	 <div style=\"margin-top:20px;margin-bottom:10px;padding:10px;border:1px solid #ccc;background-color:#eee;font-size:8pt;line-height:10pt;\">
	 In this section you can edit the Default Settings for the adding new keywords section above.  This includes adding new html tags to the Tag select drop down.  When 
	 adding tags to this list, make sure to include just the text, there is no need for &gt; or &lt; symbols.  Adding content here other than just 
	 the name of the html tag may cause adverse effects to your site.
	 <span style=\"font-weight:bold;\">$message</span>
	 </div>
	 <div style=\"padding-left:15px;\">
  	 <form name=\"UpdateDefaultSettingsForm\" method=\"post\" action=\"\">
  	   <label>Enable Plugin</label><input type=\"checkbox\" name=\"ud_kt_bseo_enabled\" value=\"1\"$enabled_chkd /><br /><br />
			 <label>Tags For DropDown: <font style=\"color:red\">Only 1 tag per line</font></label><br /><textarea name=\"ud_default_tag_options_list\">$default_tag_options_list</textarea><br /><br />
  		 <label>Default Tag:</label><select name=\"ud_default_tag\">$tag_options</select><br />
			 <label>Default Keyword Class:</label><input name=\"ud_default_tag_class\" type=\"text\" value=\"$default_tag_class\" />*optional<br />
  			<label>Default Tagged Occurence Max:</label>
  		 <select name=\"ud_default_max_num_instances\">$mo_options</select><br /><br />
  		 <input style=\"margin-left:100px;\" type=\"submit\" value=\"Update Settings\" /><br /><br />
  	 </form>
		 </div>
	";
  echo $form;
	
	////end form
	
	
}
?>
<?php



function kt_bseo_activate() {		
	return kt_bseo_init_settings();
}



function kt_bseo_create_tables() {
  global $wpdb;

	$table_name = $wpdb->prefix.'kt_bseo_keywords';
	$version = get_option('kt_bseo_version');
	
	if($wpdb->get_var("show tables like '$table_name'") != $table_name || $version < KT_BSEO_DB_VERSION) {
		$sql = "CREATE TABLE $table_name (
	              keyword_id int(11) NOT NULL auto_increment,  
	              keyword varchar(100) NOT NULL,
	              keyword_wrap_tag varchar(30) NULL,
								keyword_wrap_tag_class varchar(40) NULL,
								max_num_instances int(11) DEFAULT '3',
	              is_active int(11) DEFAULT '1',
	              PRIMARY KEY(keyword_id)
            	);
				";		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
    
		////create default data
		$sql = "INSERT INTO ".$wpdb->prefix."kt_bseo_keywords(keyword,keyword_wrap_tag,max_num_instances) values ('first keyword','strong','3')";
  	$result = $wpdb->query($sql);
		
		return true;
	}
	return false;
}



function kt_bseo_init_settings() {
	$version = get_option('kt_bseo_version');
	if ($version < KT_BSEO_DB_VERSION) {
		if (kt_bseo_create_tables()) {
			update_option('kt_bseo_version',KT_BSEO_DB_VERSION);
		}
	}
	////here we create the default options if there are none set
	$default_tag = get_option('kt_bseo_default_tag');
	$default_tag_class = get_option('kt_bseo_default_tag_class');
	$default_max_ins = get_option('kt_bseo_default_max_num_instances');
	$tag_options = get_option('kt_bseo_tag_options_list');
	
	$kt_bseo_enabled = get_option('kt_bseo_enabled');
	
	if($default_tag == ""){
	  update_option('kt_bseo_default_tag','strong');
	}
	if($default_tag_class == ""){
	  update_option('kt_bseo_default_tag_class','');
	}
	if($default_max_ins == ""){
	  update_option('kt_bseo_default_max_num_instances','3');
	}
	if($kt_bseo_enabled == ""){
	  update_option('kt_bseo_enabled','0');
	}
	
	
	if($tag_options == ""){
	  $default_options_list = "b\r
strong\r
i\r
em";
update_option('kt_bseo_tag_options_list',$default_options_list);
	}//end tag_options is blank
	
}

?>