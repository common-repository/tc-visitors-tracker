<?php
  if (!defined('WPINC')) die('Nothing to see here!');
  
  echo '<p>'.esc_html__('This plugin logs the visitors of your homepage. The records including IP-address, browser, operating system, requested page as well as the originate page. The extended version of this plugin measures additionally the actual time a visitor looks at the page.', 'tc-visitors-tracker').'</p>';
  
  echo '<table class="form-table">';
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Plugin version:', 'tc-visitors-tracker').'</th>';
  echo '<td>';
  echo '2.0.0';
  echo '</td>';
  echo '</tr>';
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Homepage:', 'tc-visitors-tracker').'</th>';
  echo '<td>';
  echo '<a target="_blank" href="https://tech-c.net/visitors-tracker-for-wordpress/">https://tech-c.net/</a>';
  echo '</td>';
  echo '</tr>';

  echo '</table>';
?>