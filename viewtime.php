<?php
  if (!defined('WPINC')) die('Nothing to see here!');

  echo '<p>'.esc_html__('The extended version of this plugin measures additionally the actual time a visitor looks at the page. The time measurement is started, when:', 'tc-visitors-tracker').'</p>';
  echo '<style>';
  echo '#tov-about li{';
  echo 'list-style:disc;';
  echo 'margin-left:30px;';
  echo '}';
  echo '</style>';
  echo '<div id="tov-about">';
  echo '<ul>';
  echo '<li>'.esc_html__('the page is opened', 'tc-visitors-tracker').'</li>';
  echo '<li>'.esc_html__('the tab with the already opened page is brought into the foreground', 'tc-visitors-tracker').'</li>';
  echo '<li>'.esc_html__('an user input occurs after the idle timeout of 60 seconds', 'tc-visitors-tracker').'</li>';
  echo '</ul>';
  echo '<p>'.esc_html__('The time measurement is stopped, when:', 'tc-visitors-tracker').'</p>';
  echo '<ul>';
  echo '<li>'.esc_html__('the tab with the opened page is no more in the foreground', 'tc-visitors-tracker').'</li>';
  echo '<li>'.esc_html__('the tab with the opened page will be closed', 'tc-visitors-tracker').'</li>';
  echo '<li>'.esc_html__('the browser is closed', 'tc-visitors-tracker').'</li>';
  echo '<li>'.esc_html__('after an idle timeout of 60 seconds without user input', 'tc-visitors-tracker').'</li>';
  echo '</ul>';
  echo '</div>';

  echo '<p>'.esc_html__('The extended version can be downloaded for a small fee, by clicking on the following button.', 'tc-visitors-tracker').'</p>';
  
  echo '<p align="center"><a target="_blank" href="https://tech-c.net/download-visitor-tracker-extended/"><img src="'.plugin_dir_url(__FILE__).'images/button.png" /></a></p>';
  
  echo '<p>'.esc_html__('After payment, you will be taken to a page with a link to download the plugin. To install, go to the «Plugins» page in your Wordpress CMS and click in the upper left corner «Add New». In the following page click on «Upload Plugin» in the upper left corner. Select the file you downloaded and click «Install Now». In the following page click on «Activate Plugin». Now you can find a new plugin at the «Plugins» page called «Visitors Tracker Extended by tech-c.net».', 'tc-visitors-tracker').'</p>';  
  
  echo '<p>'.esc_html__('The screenshot below shows how this page looks like in the extended version of this plugin.', 'tc-visitors-tracker').'</p>';  
  
  echo '<p align="center"><a rel="lytebox" target="_blank" href="'.plugin_dir_url(__FILE__).'images/screenshot.jpg"><img width="400" src="'.plugin_dir_url(__FILE__).'images/screenshot.jpg" /></a></p>';
?>