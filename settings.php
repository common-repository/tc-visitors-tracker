<?php
  if (!defined('WPINC')) die('Nothing to see here!');
  
  echo '<form method="post" action="options.php">';
  echo settings_fields('tc_visitors_tracker_options_group');
  echo do_settings_sections('tc_visitors_tracker_options_group');
  echo '<table class="form-table">';
  
  if (intval(get_option('tc_visitors_tracker_rowlimit')) < 1) {
    update_option('tc_visitors_tracker_rowlimit', 50);
  }
  $rowlimit = intval(get_option('tc_visitors_tracker_rowlimit'));
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Rows per page:', 'tc-visitors-tracker').'</th>';
  echo '<td><input type="text" name="tc_visitors_tracker_rowlimit" value="'.$rowlimit.'" />';
  echo '<p class="description">'.esc_html__('Specify the number of rows displayed on a page.', 'tc-visitors-tracker').'</p>';
  echo '</td>';
  echo '</tr>';
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Time zone:', 'tc-visitors-tracker').'</th>';
  echo '<td><select name="tc_visitors_tracker_timezone">';
  
  $timezoneIdentifiers = DateTimeZone::listIdentifiers();
  $utcTime = new DateTime('now', new DateTimeZone('UTC'));
  $tempTimezones = array();
  foreach ($timezoneIdentifiers as $timezoneIdentifier) {
    $currentTimezone = new DateTimeZone($timezoneIdentifier);
    $tempTimezones[] = array(
      'offset' => (int)$currentTimezone->getOffset($utcTime),
      'identifier' => $timezoneIdentifier
    );
  }
  usort($tempTimezones, function($a, $b) {
    return ($a['offset'] == $b['offset'])? strcmp($a['identifier'], $b['identifier']): $a['offset'] - $b['offset'];
  });
  
  if (get_option('tc_visitors_tracker_timezone') == '') {
    update_option('tc_visitors_tracker_timezone', date_default_timezone_get());
  }
  
  foreach ($tempTimezones as $tz) {
    $sign = ($tz['offset'] > 0) ? '+' : '-';
    $offset = gmdate('H:i', abs($tz['offset']));
    if ($tz['identifier'] == get_option('tc_visitors_tracker_timezone')) {
      echo '<option value="'.$tz['identifier'].'" selected="selected">&nbsp;UTC '.$sign.' '.$offset.' '.str_replace('_', ' ', $tz['identifier']).'</option>';
    } else {
      echo '<option value="'.$tz['identifier'].'">&nbsp;UTC '.$sign.' '.$offset.' '.str_replace('_', ' ', $tz['identifier']).'</option>';
    }
  }
  echo '</select>';
  echo '<p class="description">'.esc_html__('Specify the time zone in which the time should be displayed.', 'tc-visitors-tracker').'</p>';
  echo '</td>';
  echo '</tr>';
  
  if ((get_option('tc_visitors_tracker_firstday') != '1') && 
      (get_option('tc_visitors_tracker_firstday') != '7')) {
    update_option('tc_visitors_tracker_firstday', '1');
  }
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('First day of week:', 'tc-visitors-tracker').'</th>';
  echo '<td><select name="tc_visitors_tracker_firstday">';
  echo '<option value="1"';
  if (get_option('tc_visitors_tracker_firstday') == '1') {
     echo ' selected="selected"';
  }
  echo '>Monday</option>';
  echo '<option value="7"';
  if (get_option('tc_visitors_tracker_firstday') == '7') {
     echo ' selected="selected"';
  }  
  echo '>Sunday</option>';
  echo '</select>';
  echo '<p class="description">'.esc_html__('Specify the first day of the week displayed in the date input.', 'tc-visitors-tracker').'</p>';
  echo '</td>';
  echo '</tr>';
  
  $dt = new DateTime(null, new DateTimeZone(get_option('tc_visitors_tracker_timezone')));
  
  if ((get_option('tc_visitors_tracker_dateformat') != '0') && 
      (get_option('tc_visitors_tracker_dateformat') != '1')) {
    update_option('tc_visitors_tracker_dateformat', '0');
  }
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Date format:', 'tc-visitors-tracker').'</th>'; 
  echo '<td><select name="tc_visitors_tracker_dateformat">';
  echo '<option value="0"';
  if (get_option('tc_visitors_tracker_dateformat') == '0') {
     echo ' selected="selected"';
  }
  echo '>'.$dt->format('d.m.Y').'</option>';
  echo '<option value="1"';
  if (get_option('tc_visitors_tracker_dateformat') == '1') {
     echo ' selected="selected"';
  }  
  echo '>'.$dt->format('m/d/Y').'</option>';
  echo '</select>';
  echo '<p class="description">'.esc_html__('Specify the format of the date displayed in the date input.', 'tc-visitors-tracker').'</p>';
  echo '</td>';
  echo '</tr>';
  
  
  
  if ((get_option('tc_visitors_tracker_timeformat') != '0') && 
      (get_option('tc_visitors_tracker_timeformat') != '1')) {
    update_option('tc_visitors_tracker_timeformat', '0');
  }
  
  echo '<tr>';
  echo '<th scope="row">'.esc_html__('Time format:', 'tc-visitors-tracker').'</th>'; 
  echo '<td><select name="tc_visitors_tracker_timeformat">';
  echo '<option value="0"';
  if (get_option('tc_visitors_tracker_timeformat') == '0') {
     echo ' selected="selected"';
  }
  echo '>'.$dt->format('H:i:s').'</option>';
  echo '<option value="1"';
  if (get_option('tc_visitors_tracker_timeformat') == '1') {
     echo ' selected="selected"';
  }  
  echo '>'.$dt->format('h:i:s A').'</option>';
  echo '</select>';
  echo '<p class="description">'.esc_html__('Specify the format of the time displayed in the statistics.', 'tc-visitors-tracker').'</p>';
  echo '</td>';
  echo '</tr>';

  echo '</table>';
  echo submit_button();
  echo '</form>';
?>