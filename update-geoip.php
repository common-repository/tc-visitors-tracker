<?php
  if (!defined('WPINC')) die('Nothing to see here!');
  
  echo '<p>'.esc_html__('This plugin uses IP2Location™ LITE IP-COUNTRY Database from IP2Location.com to determine the country of origin for an IP address. Ownership of IP addresses change hands from time to time. Therefore, this database must be also updated from time to time. The recommended update period is 1-3 months.', 'tc-visitors-tracker').'</p>';
  
  echo '<h2>'.esc_html__('Last update', 'tc-visitors-tracker').'</h2>';
  
  function tc_visitors_tracker_get_file_time($filename) {
    if (file_exists(__DIR__.'/IP2Location/'.$filename)) {
      return date("F d Y H:i:s", filemtime(__DIR__.'/IP2Location/'.$filename)).' (UTC)';
    } else {
      return 'File not exists.';
    }
  }
  
  echo '<table class="form-table">';
  
  echo '<tr>';
  echo '<th scope="row"><small>IP2LOCATION-LITE-DB1.BIN:</small></th>';
  echo '<td>';
  echo '<p>'.tc_visitors_tracker_get_file_time('IP2LOCATION-LITE-DB1.BIN').'</p>';
  echo '</td>';
  echo '</tr>';
  
  echo '<tr>';
  echo '<th scope="row"><small>IP2LOCATION-LITE-DB1.IPV6.BIN:</small></th>';
  echo '<td>';
  echo '<p>'.tc_visitors_tracker_get_file_time('IP2LOCATION-LITE-DB1.IPV6.BIN').'</p>';
  echo '</td>';
  echo '</tr>';
  
  echo '<table>';
  
  echo '<h2>'.esc_html__('Manual upload', 'tc-visitors-tracker').'</h2>';
  
  echo '<p>'.esc_html__('Here you can upload these two files by your self. Just visit the download folder with the following link:', 'tc-visitors-tracker').' <a target="_blank" href="https://download.ip2location.com/lite/">https://download.ip2location.com/lite/</a></p>';
  
  function tc_visitors_tracker_upload_file($file, $file_name) {
    $geoipdir = __DIR__.'/IP2Location/';
    if (move_uploaded_file($file, $geoipdir.$file_name.'.tmp')) {
      echo '<p style="color:green;">'.$file_name.' was successfully uploaded.</p>';
      if (file_exists($geoipdir.$file_name.'.bak')) {
        if (!unlink($geoipdir.$file_name.'.bak')) {
          $last_error = error_get_last();
          if ((is_array($last_error)) && (isset($last_error['message']))) {
            echo '<p style="color:red;">Delete file '.$file_name.'.bak. '.$last_error['message'].'</p>';
          } else {
            echo '<p style="color:red;">Delete file '.$file_name.'.bak. '.$last_error.'</p>';
          }
        }
      }
      if (file_exists($geoipdir.$file_name)) {
        if (!rename($geoipdir.$file_name, $geoipdir.$file_name.'.bak')) {
          $last_error = error_get_last();
          if ((is_array($last_error)) && (isset($last_error['message']))) {
            echo '<p style="color:red;">Rename file '.$file_name.'. '.$last_error['message'].'</p>';
          } else {
            echo '<p style="color:red;">Rename file '.$file_name.'. '.$last_error.'</p>';
          }
        }
      }
      if (!rename($geoipdir.$file_name.'.tmp', $geoipdir.$file_name)) {
        $last_error = error_get_last();
        if ((is_array($last_error)) && (isset($last_error['message']))) {
          echo '<p style="color:red;">Rename file '.$file_name.'.tmp. '.$last_error['message'].'</p>';
        } else {
          echo '<p style="color:red;">Rename file '.$file_name.'.tmp. '.$last_error.'</p>';
        }
      }
    } else {
      $last_error = error_get_last();
      if ((is_array($last_error)) && (isset($last_error['message']))) {
        echo '<p style="color:red;">File upload failed. '.$last_error['message'].'</p>';
      } else {
        echo '<p style="color:red;">File upload failed. '.$last_error.'</p>';
      }
    }  
  }

  if ((file_exists($_FILES['upload_ipv4']['tmp_name'])) && 
      (is_uploaded_file($_FILES['upload_ipv4']['tmp_name']))) {
    tc_visitors_tracker_upload_file($_FILES['upload_ipv4']['tmp_name'], 'IP2LOCATION-LITE-DB1.BIN');
  }

  if ((file_exists($_FILES['upload_ipv6']['tmp_name'])) && 
      (is_uploaded_file($_FILES['upload_ipv6']['tmp_name']))) {
    tc_visitors_tracker_upload_file($_FILES['upload_ipv6']['tmp_name'], 'IP2LOCATION-LITE-DB1.IPV6.BIN');
  }
  
  echo '<form method="post" enctype="multipart/form-data">';
  echo settings_fields('pageview_duration_stat_options_group');
  echo do_settings_sections('pageview_duration_stat_options_group');
  echo '<table class="form-table">';
  
  echo '<tr>';
  echo '<th scope="row"><small>IP2LOCATION-LITE-DB1.BIN:</small></th>';
  echo '<td>';
  echo '<input id="id_upload_ipv4" name="upload_ipv4" type="file" />';
  echo '<p class="description">'.esc_html__('This file you will find here:', 'tc-visitors-tracker').' <a target="_blank" href="https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.BIN.ZIP">https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.BIN.ZIP</a>. '.esc_html__('Extract the ZIP-file and upload IP2LOCATION-LITE-DB1.BIN above.', 'tc-visitors-tracker').'</p>';
  echo '</td>';
  echo '</tr>';
  
  echo '<tr>';
  echo '<th scope="row"><small>IP2LOCATION-LITE-DB1.IPV6.BIN:</small></th>';
  echo '<td>';
  echo '<input id="id_upload_ipv6" name="upload_ipv6" type="file" />';
  echo '<p class="description">'.esc_html__('This file you will find here:', 'tc-visitors-tracker').' <a target="_blank" href="https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.IPV6.BIN.ZIP">https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.IPV6.BIN.ZIP</a>. '.esc_html__('Extract the ZIP-file and upload IP2LOCATION-LITE-DB1.IPV6.BIN above.', 'tc-visitors-tracker').'</p>';
  echo '</td>';
  echo '</tr>';
  
  echo '</table>';
  echo submit_button(esc_html__('Upload', 'tc-visitors-tracker'));
  echo '</form>';
  
  echo '<h2>'.esc_html__('Automatic download', 'tc-visitors-tracker').'</h2>';
  
  echo '<p>'.esc_html__('Just click on the button below and these two files will be downloaded from https://download.ip2location.com/lite/ and unzipped into the respective directory.', 'tc-visitors-tracker').'</p>';

  function tc_visitors_tracker_auto_download($url, $target_file) {
    if (file_exists($target_file)) {
      if (!unlink($target_file)) {
        $last_error = error_get_last();
        if ((is_array($last_error)) && (isset($last_error['message']))) {
          echo '<p style="color:red;">Delete file '.$target_file.'. '.$last_error['message'].'</p>';
        } else {
          echo '<p style="color:red;">Delete file '.$target_file.'. '.$last_error.'</p>';
        }
      }
    }

    $output = wp_remote_get($url);
    if (is_wp_error($response)) {
      echo '<p style="color:red;">'.$target_file.' '.wp_remote_retrieve_response_message($response).'</p>';
      return;
    }
    $response = wp_remote_retrieve_body($output); 
    
    $fp = fopen($target_file, 'w+');
    if (!$fp) {
      echo '<p style="color:red;">Error on create file '.$target_file.'.</p>';
      return;
    }
    fwrite($fp, $response);
    fclose($fp);

    $zip = new ZipArchive;
    $res = $zip->open($target_file);
    if ($res === true) {
      $zip->extractTo(__DIR__.'/IP2Location/');
      $zip->close();
      echo '<p style="color:green;">'.$target_file.' download successfully.</p>';
    } else {
      echo '<p style="color:red;">'.$target_file.' unzip failed.</p>';
    }
  }
  
  if ((isset($_POST['autodownload'])) && ($_POST['autodownload'] == '1')) {
    tc_visitors_tracker_auto_download('https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.BIN.ZIP', __DIR__.'/IP2Location/IP2LOCATION-LITE-DB1.BIN.ZIP');
    tc_visitors_tracker_auto_download('https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.IPV6.BIN.ZIP', __DIR__.'/IP2Location/IP2LOCATION-LITE-DB1.IPV6.BIN.ZIP');
  }  
  
  echo '<form method="post" enctype="multipart/form-data">';
  echo settings_fields('pageview_duration_stat_options_group');
  echo do_settings_sections('pageview_duration_stat_options_group');
  echo '<input type="hidden" name="autodownload" value="1" />';
  echo submit_button(esc_html__('Download', 'tc-visitors-tracker'));
  echo '</form>';
?>