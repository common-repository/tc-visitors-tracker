<?php
  if (!defined('WPINC')) die('Nothing to see here!');
  
  global $wpdb;
  $log_table_name = $wpdb->prefix.'tc_visitors_tracker';
  $sql = "SHOW TABLE STATUS LIKE '".$log_table_name."'";
  $results = $wpdb->get_results($sql , ARRAY_A);
  
  if ((is_array($results)) && (is_array($results[0]))) {
    $rows = $results[0]['Rows'];
    $size = $results[0]['Data_length'] + $results[0]['Index_length'].' bytes';
  } else {
    $rows = 'Error';
    $size = 'Error';
  }
  
  echo '<table class="form-table">';
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Database table name:', 'tc-visitors-tracker').'</th>';
  echo '<td>';
  echo $log_table_name;
  echo '</td>';
  echo '</tr>';
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Rows in table:', 'tc-visitors-tracker').'</th>';
  echo '<td>';
  echo $rows;
  echo '</td>';
  echo '</tr>';
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Table size:', 'tc-visitors-tracker').'</th>';
  echo '<td>';
  echo $size;
  echo '</td>';
  echo '</tr>';
  
  echo '</table>';
  
  echo '<p>'.esc_html__('In order to empty the record table of this plugin just click on the button below and confirm.', 'tc-visitors-tracker').'</p>';

  echo '<form method="post" enctype="multipart/form-data" onsubmit="return confirm(\''.esc_html__('Are you sure you want empty the table?', 'tc-visitors-tracker').'\');">';
  echo settings_fields('pageview_duration_stat_options_group');
  echo do_settings_sections('pageview_duration_stat_options_group');
  echo '<input type="hidden" name="emptydelete" value="1" />';
  echo submit_button(esc_html__('Empty the table', 'tc-visitors-tracker'));
  echo '</form>';
?>