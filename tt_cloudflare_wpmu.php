<?php
/*
Plugin Name: infraNET CloudFlare WPMU Free
Plugin URI: https://www.infranet.com/
Description: A plugin that works with the CloudFlare API to allow automatic adding of subdomains on WordPress Multisite.
Version: 2.0
Author: Patrick Steil of infraNET
Author URI: https://www.infranet.com/
License:  GPL2
*/


// Lets set up the plugin in wordpress.
// Hook for adding admin menus
add_action('network_admin_menu', 'cf_add_pages');
add_action('wpmu_new_blog', 'cf_wpmu_add_new',10,10);
function cf_add_pages() {
add_submenu_page( 'settings.php', 'CloudFlare WPMU - ThemeTailors.com','CloudFlare WPMU', 'manage_options','cloudflare-wpmu-settings','cf_wpmu_function');
}
function cf_wpmu_function(){
cf_wpmu_get_credentials();
if(!cURLcheckBasicFunctions()){echo "The required CURL functions are not installed on this server.";}
}
// Get the site URL with no http://www.
function GetDomain($url)
{


$nowww = preg_replace('/www\./','',$url);
$domain = parse_url($nowww);
if(!empty($domain["host"]))
{
return $domain["host"];
} else
{
return $domain["path"];
}
}
function cf_wpmu_get_credentials(){
global $wpdb;
$message = "";
$zone_url = GetDomain(get_bloginfo('wpurl'));
if($_POST){
      $option_value['cf_wpmu_email'] = $_POST['cf_wpmu_email'];
      $option_value['cf_wpmu_api_key'] = $_POST['cf_wpmu_api_key'];
      $option_value['cf_wpmu_zone'] = $_POST['cf_wpmu_zone'];
      $option_value['cf_wpmu_auto'] = $_POST['cf_wpmu_auto'];
      foreach($option_value as $key=>$val)
      {
      if($key){
      update_option($key,$val);
      }
      }
      $message = "Updated successfully.";
}

$cf_wpmu_email = get_option('cf_wpmu_email');
$cf_wpmu_api_key = get_option('cf_wpmu_api_key');
$cf_wpmu_zone = get_option('cf_wpmu_zone');
$cf_wpmu_auto = get_option('cf_wpmu_auto');
if($cf_wpmu_zone==''){$cf_wpmu_zone=$zone_url;}
// Check API credentials
if(get_option('cf_wpmu_api_key')){
$api_fail = chk_cf();
if($api_fail){
      $message = $api_fail;
   }
}
?>


<form action="<?php echo site_url();?>/wp-admin/network/settings.php?page=cloudflare-wpmu-settings" method="post">
<style>
h2 { color:#464646;font-family:Georgia,"Times New Roman","Bitstream Charter",Times,serif;
background: url("../wp-content/plugins/tt_cloudflare_wpmu/imgs/tt-logo.png") no-repeat scroll 0 15px transparent;
font-size:24px;
font-size-adjust:none;
font-stretch:normal;
font-style:italic;
font-variant:normal;
font-weight:normal;
line-height:35px;
margin:0;
padding:14px 15px 3px 40px;
text-shadow:0 1px 0 #FFFFFF;  }
#tt_cf_wpmu_2 .success {padding:0 0 0 16px;background: url("/wp-content/plugins/tt_cloudflare_wpmu/imgs/verified.png") no-repeat scroll 0 1px transparent; }
#tt_cf_wpmu_2 .error {padding:0 0 0 16px;background: url("/wp-content/plugins/tt_cloudflare_wpmu/imgs/error.png") no-repeat scroll 0 1px transparent; }
#tt_cf_wpmu_2 h3, #tt_cf_wpmu_1 h3 {height: 30px;padding: 10px 0 0 180px;background: url("/wp-content/plugins/tt_cloudflare_wpmu/imgs/cloudflare-logo.png") no-repeat scroll 0 1px transparent; }
input, td input { width: 85%; padding:8px; }
</style>

<h2><?php _e('infraNET CloudFlare WPMU - Settings');?></h2>
<?php if($message){?>
<div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);" >
<p><?php _e($message);?> </p>
</div>

<?php }?>
<table style=" width:85%;"  cellpadding="5" id="tt_cf_wpmu_1" class="widefat post fixed" >
<thead>
<tr>
<td width="40%" colspan="2"><h3><?php _e('Settings');?></h3></td>
</tr>
<tr>
<td width="40%"><?php _e('Email Address');?></td>
<td width="71%"><input type="text" name="cf_wpmu_email" value="<?php echo $cf_wpmu_email;?>" /></td>
</tr>
<tr>
<td><?php _e('Your API Key');?>   <a href="https://www.cloudflare.com/a/profile" target="_blank"><small><?php _e('(Find it here)');?></small></a></td>
<td><input type="text" name="cf_wpmu_api_key" value="<?php echo $cf_wpmu_api_key;?>" /></td>
</tr>
<tr>
<td><?php _e('Zone URL (site url, no www.)');?></td>
<td><input type="text" name="cf_wpmu_zone" value="<?php echo $cf_wpmu_zone;?>" /></td>
</tr>
<tr>
<td><?php _e('Automatically add new blogs?');?></td>
<td>
      <input type="radio" name="cf_wpmu_auto" <?php if($cf_wpmu_auto=='1'){ echo 'checked="checked"';}?>  value="1" /> <?php _e('Yes');?>  <input type="radio" name="cf_wpmu_auto" <?php if($cf_wpmu_auto=='0'){ echo 'checked="checked"';}?> value="0" /> <?php _e('No');?>
      <input type="submit" name="submit" value="<?php _e('Submit');?>" class="button-secondary action" />
</td>
</thead>
</table>
<div class="clear"></div>
<div class="tt-powered"><p>Plugin updated by  <a target="_blank" href="http://infraNET.com">infraNET</a></p></div>
<?php
}
//


function cURLcheckBasicFunctions()
{
if( !function_exists("curl_init") &&
!function_exists("curl_setopt") &&
!function_exists("curl_exec") &&
!function_exists("curl_close") ) return false;
else return true;
}
function chk_cf(){
global $wpdb;
$url = "https://www.cloudflare.com/api_json.html";
$data = array(
"a" => "stats",
"z" => get_option('cf_wpmu_zone'),
"u" => get_option('cf_wpmu_email'),
"tkn" => get_option('cf_wpmu_api_key'),
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$http_result = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
curl_close($ch);
$cloud_arr = json_decode($http_result,true);
if ($http_code != 200) {
print "Error: $error\n";
} else {
// print_r($cloud_arr);
}
if($cloud_arr['result']=='success'){
}else{return 'ERROR MSG: '.$cloud_arr['msg'];}
}
// Function to add subdomain to CloudFlare through API
function do_cf_api($arr){
$url = "https://www.cloudflare.com/api_json.html";

$data = array(
"a" => "stats",
"tkn" => get_option('cf_wpmu_api_key'),
"z" => get_option('cf_wpmu_zone'),
"email" => get_option('cf_wpmu_email')
);
if($arr){$data = array_merge($data, $arr);} // merge the arrays
$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$http_result = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
curl_close($ch);
$cloud_arr = json_decode($http_result,true);
if ($http_code != 200) {
print "Error: $error\n";
} else {
return $cloud_arr;
}
}
// Function to add blogs on creation
function cf_wpmu_add_new($blog_id, $user_id, $domain, $path){
global $wpdb;
if(get_option('cf_wpmu_auto')){
$sub_url = explode('.', $domain);
//add_subdomain(get_option('cf_wpmu_api_key'),get_option('cf_wpmu_zone'),get_option('cf_wpmu_email'),get_option('cf_wpmu_zone'),$sub_url[0]);
$cf_req = do_cf_api(array("a" => "rec_new","type" => "CNAME","content" => get_option('cf_wpmu_zone'),"name" => $sub_url[0],"ttl" => "1","service_mode" => "1"));
if($cf_req['result']=='success'){
$cf_req2 = do_cf_api(array("a" => "rec_edit","id" => $cf_req['response']['rec']['obj']['rec_id'],"type" => "CNAME","content" => get_option('cf_wpmu_zone'),"name" => $sub_url[0],"ttl" => "1","service_mode" => "1"));
}
if($cf_req2['result']=='success'){
update_option( 'tt_cf_msg', '<div class="updated">
       <p style=\'height: 30px;padding: 10px 0 0 180px;background: url("/wp-content/plugins/tt_cloudflare_wpmu/imgs/cloudflare-logo.png") no-repeat scroll 0 1px transparent;\'>Site added to CloudFlare and activated.</p>
    </div>' );
} else {
   update_option( 'tt_cf_msg', '<div class="error">
          <p style=\'height: 30px;padding: 10px 0 0 180px;\'>Error adding site ".$sub_url[0]." to CloudFlare.</p>
       </div>' );
}

}
}

// Add settings link on plugin page
function cf_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=cloudflare-wpmu-settings">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'cf_settings_link' );

function tt_cloudflare_admin_notice(){
    global $wpdb;
	echo get_option('tt_cf_msg');
	update_option( 'tt_cf_msg', '');
}

add_action('network_admin_notices', 'tt_cloudflare_admin_notice');

?>
