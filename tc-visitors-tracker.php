<?php
/*
 * Plugin Name: Visitors Tracker by tech-c.net
 * Version: 2.0.0
 * Plugin URI: https://tech-c.net/visitors-tracker-for-wordpress/
 * Description: This plugin logs visitors of your homepage.
 * Author: tech-c.net
 * Author URI: http://tech-c.net
 * Copyright: tech-c.net
 * Requires at least: 4.0
 * Tested up to: 5.0
 * Donate link: https://tech-c.net/donation.php
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('WPINC')) die('Nothing to see here!');

//============================================================================
function tc_visitors_tracker_count_page_views() {
  $ip_addr = getenv('REMOTE_ADDR');
  if ($ip_addr != '') {
    if (!class_exists('\IP2Location\Database')) {
      require_once(__DIR__.'/IP2Location.php');
    }
    if (strpos($ip_addr, ':') > 0) {
      $db = new \IP2Location\Database(__DIR__.'/IP2Location/IP2LOCATION-LITE-DB1.IPV6.BIN', \IP2Location\Database::FILE_IO);
    } else {
      $db = new \IP2Location\Database(__DIR__.'/IP2Location/IP2LOCATION-LITE-DB1.BIN', \IP2Location\Database::FILE_IO);
    }
    $temp_records = $db->lookup($ip_addr, \IP2Location\Database::ALL);
    $country_code = strtolower($temp_records['countryCode']);
    $country_name = $temp_records['countryName'];
  } else {
    $country_code = '';
    $country_name = '';
  }
  
  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    if (!class_exists('parseUserAgentStringClass')) {
      require_once(__DIR__.'/user-agent.php');
    }
    $browser = new parseUserAgentStringClass();
    $browser->includeAndroidName = false;
    $browser->includeWindowsName = true;
    $browser->includeMacOSName = false;
    $browser->parseUserAgentString($_SERVER['HTTP_USER_AGENT']);
  
    $browser_name = $browser->browsername;
    $browser_version = $browser->browserversion; 
    if ($browser_name == '') {
      $browser_version = '';   
      if ($browser->type != '') {
        if ($browser->type == 'Unknown') {
          $browser_name .= $browser->fullname;
        } else {
          $browser_name .= $browser->fullname.' ('.$browser->type.')';
        }
      } else {
        if (($browser->userAgentString == '') || ($browser->fullname != '')) {
          if ($browser->fullname != '') {
            $browser_name = $browser->fullname.' (Unknown)';
          } else {
            $browser_name = 'Unknown';
          }
        } else {
          $browser_name = '';
        }
      }
    }
  } else {
    $browser_name = '';
    $browser_version = ''; 
  }
  
  $date_utc = new DateTime(null, new DateTimeZone('UTC'));
  $utc_time = $date_utc->getTimestamp();
  
  $request = substr(esc_url_raw($_SERVER['REQUEST_URI']), 0, 1024);  
  $referer = substr(esc_url_raw($_SERVER['HTTP_REFERER']), 0, 1024);  
  
  global $wpdb;
  $log_table_name = $wpdb->prefix.'tc_visitors_tracker';
  $dbquery = $wpdb->prepare("INSERT INTO $log_table_name (rectime, ipaddr, countrycode, countryname, browsername, browserversion, osname, ispv, duration, request, referer) VALUES (FROM_UNIXTIME($utc_time), %s, %s, %s, %s, %s, %s, %d, %d, %s, %s)", $ip_addr, $country_code, $country_name, $browser_name, $browser_version, $browser->osname, 1, 0, $request, $referer);
  $wpdb->query($dbquery);
}
//============================================================================
add_action('parse_request', 'tc_visitors_tracker_parse_request');
function tc_visitors_tracker_parse_request(&$wp) {
  tc_visitors_tracker_count_page_views();
  return;
}
//============================================================================
// Show the options page
function tc_visitors_tracker_options_page() {
  $style_url = plugins_url('/css/tc-visitors-tracker.css', __FILE__);
  $style_file = __DIR__.'/css/tc-visitors-tracker.css';
  if (file_exists($style_file)) {
    wp_register_style('tc-visitors-tracker', $style_url);
    wp_enqueue_style('tc-visitors-tracker');
  }
  
  echo '<div class="wrap">';
  echo '<div id="icon-options-general" class="icon32"></div>';
  echo '<h1>Visitors Tracker by tech-c.net</h1>';
  
  $active_tab = "tab_view";
  if (isset($_GET["tab"])) {
    $active_tab = $_GET["tab"];
  }
  
  echo '<h2 class="nav-tab-wrapper">';

  echo '<a href="?page=tc_visitors_tracker_options_slug&tab=tab_view" class="nav-tab';
  if ($active_tab == 'tab_view') echo ' nav-tab-active';
  echo '">'.esc_html__('Page Views', 'tc-visitors-tracker').'</a>';

  echo '<a href="?page=tc_visitors_tracker_options_slug&tab=tab_time" class="nav-tab';
  if ($active_tab == 'tab_time') echo ' nav-tab-active';
  echo '">'.esc_html__('Time of View', 'tc-visitors-tracker').'</a>';
  
  echo '<a href="?page=tc_visitors_tracker_options_slug&tab=tab_update_geoip" class="nav-tab';
  if ($active_tab == 'tab_update_geoip') echo ' nav-tab-active';
  echo '">'.esc_html__('Update GeoIP', 'tc-visitors-tracker').'</a>';
  
  echo '<a href="?page=tc_visitors_tracker_options_slug&tab=tab_settings" class="nav-tab';
  if ($active_tab == 'tab_settings') echo ' nav-tab-active';
  echo '">'.esc_html__('Settings', 'tc-visitors-tracker').'</a>';
  
  echo '<a href="?page=tc_visitors_tracker_options_slug&tab=tab_database" class="nav-tab';
  if ($active_tab == 'tab_database') echo ' nav-tab-active';
  echo '">'.esc_html__('Database', 'tc-visitors-tracker').'</a>';
  
  echo '<a href="?page=tc_visitors_tracker_options_slug&tab=tab_about" class="nav-tab';
  if ($active_tab == 'tab_about') echo ' nav-tab-active';
  echo '">'.esc_html__('About', 'tc-visitors-tracker').'</a>';
  
  echo '</h2>';
  
  if ($active_tab == 'tab_view') {
    $file = __DIR__.'/pageviews.php';
    if (file_exists($file)) {
      include $file;
    }
  }
  
  if ($active_tab == 'tab_time') {
    $file = __DIR__.'/viewtime.php';
    if (file_exists($file)) {
      include $file;
    }
  }
  
  if ($active_tab == 'tab_update_geoip') {
    $file = __DIR__.'/update-geoip.php';
    if (file_exists($file)) {
      include $file;
    }
  }
 
  if ($active_tab == 'tab_settings') {
    $file = __DIR__.'/settings.php';
    if (file_exists($file)) {
      include $file;
    }
  }
  
  if ($active_tab == 'tab_database') {
    $file = __DIR__.'/database.php';
    if (file_exists($file)) {
      include $file;
    }
  }
  
  if ($active_tab == 'tab_about') {
    $file = __DIR__.'/about.php';
    if (file_exists($file)) {
      include $file;
    }
  }
    
  echo '</div>';
}
//============================================================================
// Register options page
add_action('admin_menu', 'tc_visitors_tracker_register_options_page');
function tc_visitors_tracker_register_options_page() {
  add_options_page('Visitors Tracker by tech-c.net', 
                   'Visitors Tracker by tech-c.net',
                   'manage_options', 
                   'tc_visitors_tracker_options_slug', 
                   'tc_visitors_tracker_options_page');
}
//============================================================================
// Register settings
add_action('admin_init', 'tc_visitors_tracker_register_settings');
function tc_visitors_tracker_register_settings() {
  register_setting('tc_visitors_tracker_options_group', 'tc_visitors_tracker_rowlimit');
  register_setting('tc_visitors_tracker_options_group', 'tc_visitors_tracker_timezone');
  register_setting('tc_visitors_tracker_options_group', 'tc_visitors_tracker_firstday');
  register_setting('tc_visitors_tracker_options_group', 'tc_visitors_tracker_dateformat');
  register_setting('tc_visitors_tracker_options_group', 'tc_visitors_tracker_timeformat');
}
//============================================================================
// Add links
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'tc_visitors_tracker_settings_link');
function tc_visitors_tracker_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=tc_visitors_tracker_options_slug&tab=tab_view">'.esc_html__('Page Views', 'tc-visitors-tracker').'</a>';
  array_push($links, $settings_link);
  $settings_link = '<a href="options-general.php?page=tc_visitors_tracker_options_slug&tab=tab_time">'.esc_html__('Time of View', 'tc-visitors-tracker').'</a>';
  array_push($links, $settings_link);
  $settings_link = '<a href="options-general.php?page=tc_visitors_tracker_options_slug&tab=tab_update_geoip">'.esc_html__('Update GeoIP', 'tc-visitors-tracker').'</a>';
  array_push($links, $settings_link);
  $settings_link = '<a href="options-general.php?page=tc_visitors_tracker_options_slug&tab=tab_settings">'.esc_html__('Settings', 'tc-visitors-tracker').'</a>';
  array_push($links, $settings_link);
  $settings_link = '<a href="options-general.php?page=tc_visitors_tracker_options_slug&tab=tab_database">'.esc_html__('Database', 'tc-visitors-tracker').'</a>';
  array_push($links, $settings_link);
  $settings_link = '<a href="options-general.php?page=tc_visitors_tracker_options_slug&tab=tab_about">'.esc_html__('About', 'tc-visitors-tracker').'</a>';
  array_push($links, $settings_link);
  return $links;
}
//============================================================================
// Specify text-domain
add_action('plugins_loaded', 'tc_visitors_tracker_textdomain');
function tc_visitors_tracker_textdomain() {
  load_plugin_textdomain('tc-visitors-tracker', false, dirname(plugin_basename(__FILE__)).'/languages/');
}
//============================================================================
// Truncate table and reload
add_action('wp_loaded', 'tc_visitors_tracker_custom_redirect');
function tc_visitors_tracker_custom_redirect() {
  if ((isset($_POST['emptydelete'])) && ($_POST['emptydelete'] == '1')) {
    global $wpdb;
    $log_table_name = $wpdb->prefix.'tc_visitors_tracker';
    $sql = 'TRUNCATE TABLE '.$log_table_name;
    $wpdb->query($sql);
    
    wp_redirect('options-general.php?page=tc_visitors_tracker_options_slug&tab=tab_database');
    exit();
  }
}
//============================================================================
// Create table on activation
register_activation_hook(__FILE__, 'tc_visitors_tracker_install');
function tc_visitors_tracker_install() {
	global $wpdb;
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	$charset_collate = $wpdb->get_charset_collate();
  $log_table_name = $wpdb->prefix.'tc_visitors_tracker';
  $sql = "CREATE TABLE IF NOT EXISTS ".$log_table_name." (";
  $sql .= "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
  $sql .= "rectime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
  $sql .= "ipaddr VARCHAR(255), ";
  $sql .= "countrycode VARCHAR(255), ";
  $sql .= "countryname VARCHAR(255), ";
  $sql .= "browsername VARCHAR(255), ";
  $sql .= "browserversion VARCHAR(255), ";
  $sql .= "osname VARCHAR(255), ";
  $sql .= "ispv TINYINT(1), ";
  $sql .= "duration INT(11), ";
  $sql .= "request VARCHAR(255), ";
  $sql .= "referer VARCHAR(255)";
  $sql .= ") ".$charset_collate.';';
	dbDelta($sql);
	add_option('tc_visitors_tracker_db_version', '1.0.0');
}
//============================================================================
// Delete table on uninstall
register_uninstall_hook(__FILE__, 'tc_visitors_tracker_uninstall');
function tc_visitors_tracker_uninstall() {
  global $wpdb;
	$log_table_name = $wpdb->prefix.'tc_visitors_tracker';
  $sql = 'DROP TABLE IF EXISTS '.$log_table_name;
  $wpdb->query($sql);
}
//============================================================================
// For testing purpose only
//add_filter('locale', 'tc_visitors_tracker_change_language');
//function tc_visitors_tracker_change_language($locale) {
//return 'de_DE';
//return 'es_ES';
//}
?>