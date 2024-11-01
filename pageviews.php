<?php
  if (!defined('WPINC')) die('Nothing to see here!');
  
  require_once(__DIR__.'/utils.php');
  
  $timezone = get_option('tc_visitors_tracker_timezone');
  if ($timezone == '') $timezone = 'UTC';

  $firstday = intval(get_option('tc_visitors_tracker_firstday'));
  if (($firstday != 1) && ($firstday != 7)) $firstday = 1;

  switch (intval(get_option('tc_visitors_tracker_dateformat'))) {
	  case 1:
      $dateformat = 'm/d/Y';
	    break;
    default:
	    $dateformat = 'd.m.Y';
	    break;
  }
  
  switch (intval(get_option('tc_visitors_tracker_timeformat'))) {
	  case 1:
      $timeformat = 'h:i:s A';
	    break;
    default:
	    $timeformat = 'H:i:s';
	    break;
  }
  
  $rowlimit = intval(get_option('tc_visitors_tracker_rowlimit'));
  if ($rowlimit < 1) $rowlimit = 50;
  
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-datepicker');
  
  $script_url = plugins_url('/js/dragscroll.js', __FILE__);
  $script_file = __DIR__.'/js/dragscroll.js';
  if (file_exists($script_file)) {
    wp_register_script('tc_visitors_tracker_dragscroll', $script_url);
    wp_enqueue_script('tc_visitors_tracker_dragscroll');
  }
  
  $style_url = plugins_url('/css/jquery-ui.css', __FILE__);
  $style_file = __DIR__.'/css/jquery-ui.css';
  if (file_exists($style_file)) {
    wp_register_style('jquery-ui', $style_url);
    wp_enqueue_style('jquery-ui');
  }
  
  $view = intval($_POST['view']);
  
  echo '<div class="filterheader">';
  echo '<div class="filterwarpper">';
  echo '<div class="filterdiv">';
  echo '<select name="view" id="id_selectview">';
  echo '<option value="0"';
  if ($view == 0) echo ' selected="selected"';
  echo '>'.esc_html__('Daily', 'tc-visitors-tracker').'</option>';
  echo '<option value="1"';
  if ($view == 1) echo ' selected="selected"';
  echo '>'.esc_html__('Monthly', 'tc-visitors-tracker').'</option>';
  echo '<option value="2"';
  if ($view == 2) echo ' selected="selected"';
  echo '>'.esc_html__('Yearly', 'tc-visitors-tracker').'</option>';
  echo '<option value="3"';
  if ($view == 3) echo ' selected="selected"';
  echo '>'.esc_html__('Total', 'tc-visitors-tracker').'</option>';
  echo '</select>';
  echo '</div>';  
  echo '</div>';
  echo '</div>';
  
  echo '<script>';
  echo 'jQuery("#id_selectview").on("change", function(){';
  
  echo 'var view = jQuery("#id_selectview").val();';
  
  echo 'if (view == 0) {';
  echo 'var d = new Date();';
  echo 'day = d.getDay();';
  echo 'if (day.length < 2) day = "0" + day;'; 
  echo 'month = d.getMonth()+1;';
  echo 'if (month.length < 2) month = "0" + month;'; 
  echo 'year = d.getFullYear();';
  echo '}';

  echo 'if (view == 1) {';
  echo 'var d = new Date();';
  echo 'day = "01";';
  echo 'month = d.getMonth()+1;';
  echo 'if (month.length < 2) month = "0" + month;'; 
  echo 'year = d.getFullYear();';
  echo '}';
  
  echo 'if (view == 2) {';
  echo 'var d = new Date();';
  echo 'day = "01";';
  echo 'month = "01";';
  echo 'year = d.getFullYear();';
  echo '}';
  
  echo 'if (view == 3) {';
  echo 'var d = new Date();';
  echo 'day = "01";';
  echo 'month = "01";';
  echo 'year = d.getFullYear();';
  echo '}';
  
  echo 'jQuery("#id_hiddendate").val(year + "-" + month + "-" + day);';
  echo 'jQuery("#id_view").val(jQuery("#id_selectview").val());';
  echo 'jQuery("#id_filterdate").submit();';
  echo '});';
  echo '</script>';
  
  if ($view == 0) {
    switch($dateformat) {
      case 'd.m.Y':
	      $pickerdateformat = 'dd.mm.yy';
	      break;
      case 'm/d/Y':
	      $pickerdateformat = 'mm/dd/yy';
	      break;
	    default:
	      $pickerdateformat = 'dd.mm.yy';
	      break;
    }

    echo '<script>';
    echo 'jQuery(function(){';
    echo 'jQuery("#id_datepicker").datepicker({';
    echo 'altField:"#id_hiddendate",';
    echo 'altFormat:"yy-mm-dd",';
    echo 'dateFormat:"'.$pickerdateformat.'",';
    echo 'regional:"en",';
    echo 'firstDay:'.$firstday.',';
    echo 'showWeek:true,';
    echo 'showAnim:"slideDown",';
    echo 'changeMonth:true,';
    echo 'changeYear:true,';
    echo 'onSelect:function(dateText,dateObj){';
    echo 'jQuery("#id_view").val(jQuery("#id_selectview").val());';
    echo 'jQuery("#id_filterdate").submit();';
    echo '}';
    echo '});';
    echo 'jQuery("#id_datepicker").datepicker("option", jQuery.datepicker.regional["en"]);';
    echo 'jQuery("#id_datepicker").datepicker("option", "dateFormat", "'.$pickerdateformat.'");';
    echo 'jQuery("#id_datepicker").datepicker("option", "firstDay", "'.$firstday.'");';
    echo '});';
    echo '</script>';
  }
    
  if (isset($_POST['hiddendate'])) {
    $hiddendate = substr($_POST['hiddendate'], 0, 10);
  } else {		
	  $date = new DateTime(null, new DateTimeZone($timezone));
    $hiddendate = $date->format('Y-m-d');
  }
  
  $d = DateTime::createFromFormat('Y-m-d', $hiddendate);
  if (!($d && $d->format('Y-m-d') == $hiddendate)) {
	  $date = new DateTime(null, new DateTimeZone($timezone));
    $hiddendate = $date->format('Y-m-d');
  }

  $d = DateTime::createFromFormat('Y-m-d', $hiddendate);
  $datepicker = $d->format($dateformat);
  
  echo '<form method="POST" id="id_filterdate">';
  echo '<input type="hidden" name="view" id="id_view" value="'.$view.'">';
  echo '<input type="hidden" name="hiddendate" id="id_hiddendate" value="'.$hiddendate.'">';
  echo '</form>';
  
  echo '<div class="filterheader">';
  echo '<div class="filterwarpper">';
  
  if ($view == 0) {
    echo '<div class="filterdiv">';
    echo '<input type="text" class="filterinput" name="datepicker" id="id_datepicker" readonly="readonly" value="'.$datepicker.'">';
    echo '</div>';
  } 
  
  if (($view == 1) || ($view == 2)) {
    echo '<div class="filterdiv">';
    echo '<select name="selectyear" id="id_selectyear">';
    $date = DateTime::createFromFormat("Y-m-d", $hiddendate);
    $year = $date->format("Y");
    for ($i = $year-5; $i <= $year+5; $i++) {
      echo '<option value="'.$i.'"';
      if ($i == $year) echo ' selected="selected"';
      echo '>'.$i.'</option>';
    }
    echo '</select>';
    echo '</div>';
    if ($view == 1) {
      echo '<div class="filterdiv">';
      echo '<select name="selectmonth" id="id_selectmonth">';
      $date = DateTime::createFromFormat("Y-m-d", $hiddendate);
      $month = $date->format("m");
      for ($i = 1; $i <= 12; $i++) {
        echo '<option value="'.$i.'"';
        if ($i == $month) echo ' selected="selected"';
        $date = DateTime::createFromFormat('!m', $i);
        echo '>'.$date->format('F').'</option>';
      }
    echo '</select>';
    echo '</div>';
    }
  }
  
  if ($view != 3) {
    echo '<div class="filterdiv">';
    echo '<button id="id_prev_day"></button>';
    echo '</div>';
  }
  
  echo '<div class="filterdiv">';
  echo '<button id="id_update"></button>';
  echo '</div>';
  
  if ($view != 3) {
    echo '<div class="filterdiv">';
    echo '<button id="id_next_day"></button>';
    echo '</div>';
  }
  
  echo '</div>';
  echo '</div>';

  echo '<script>';

  echo 'jQuery("#id_prev_day").on("click", function(){';
  if ($view == 0) {
    echo 'var date = jQuery("#id_datepicker").datepicker("getDate");';
    echo 'date.setTime(date.getTime() - 86400000);';
    echo 'jQuery("#id_datepicker").datepicker("setDate", date);';
    echo 'jQuery("#id_filterdate").submit();';
  }
  
  if ($view == 1) {
    echo 'var from = jQuery("#id_hiddendate").val();';
    echo 'var d = new Date(from);';
    echo 'month = "" + d.getMonth();';  
    echo 'day = "01";';  
    echo 'year = d.getFullYear();';
    echo 'if (month == "0") {';
    echo 'month = "12";';
    echo 'year = year - 1;';
    echo '}';
    echo 'if (month.length < 2) month = "0" + month;'; 
    echo 'jQuery("#id_hiddendate").val(year + "-" + month + "-" + day);';
    echo 'jQuery("#id_filterdate").submit();';
  }
  
  if ($view == 2) {
    echo 'var from = jQuery("#id_hiddendate").val();';
    echo 'var d = new Date(from);';
    echo 'month = "01";';
    echo 'day = "01";';  
    echo 'year = d.getFullYear()-1;';
    echo 'jQuery("#id_hiddendate").val(year + "-" + month + "-" + day);';
    echo 'jQuery("#id_filterdate").submit();';
  }
  echo '});';
  
  
  echo 'jQuery("#id_next_day").on("click", function(){';
  
  if ($view == 0) {
    echo 'var date = jQuery("#id_datepicker").datepicker("getDate");';
    echo 'date.setTime(date.getTime() + 86400000);';
    echo 'jQuery("#id_datepicker").datepicker("setDate", date);';
    echo 'jQuery("#id_filterdate").submit();';
  }
  
  if ($view == 1) {
    echo 'var from = jQuery("#id_hiddendate").val();';
    echo 'var d = new Date(from);';
    echo 'month = "" + (d.getMonth() + 2);';
    echo 'day = "01";';
    echo 'year = d.getFullYear();';
    echo 'if (month == "13") {';
    echo 'month = "01";';
    echo 'year = year + 1;';
    echo '}';
    echo 'if (month.length < 2) month = "0" + month;'; 
    echo 'jQuery("#id_hiddendate").val(year + "-" + month + "-" + day);';
    echo 'jQuery("#id_filterdate").submit();';
  }
  
  if ($view == 2) {
    echo 'var from = jQuery("#id_hiddendate").val();';
    echo 'var d = new Date(from);';
    echo 'month = "01";';
    echo 'day = "01";';
    echo 'year = d.getFullYear()+1;';
    echo 'jQuery("#id_hiddendate").val(year + "-" + month + "-" + day);';
    echo 'jQuery("#id_filterdate").submit();';
  }
  
  echo '});';
  
  echo 'jQuery("#id_update").on("click", function(){';
  echo 'jQuery("#id_filterdate").submit();';
  echo '});';
  
  if ($view == 1) {
    echo 'jQuery("#id_selectmonth").on("change", function(){';
    echo 'month = jQuery("#id_selectmonth").val();';
    echo 'if (month.length < 2) month = "0" + month;'; 
    echo 'day = "01";';
    echo 'year = jQuery("#id_selectyear").val();';
    echo 'jQuery("#id_hiddendate").val(year + "-" + month + "-" + day);';
    echo 'jQuery("#id_filterdate").submit();';
    echo '});';
  }
  
  if (($view == 1) || ($view == 2)) {
    echo 'jQuery("#id_selectyear").on("change", function(){';
    echo 'year = jQuery("#id_selectyear").val();';
    echo 'day = "01";';
    if ($view == 1) {
      echo 'month = jQuery("#id_selectmonth").val();';
      echo 'if (month.length < 2) month = "0" + month;';
    } else {
      echo 'month = "01";';
    }
    echo 'jQuery("#id_hiddendate").val(year + "-" + month + "-" + day);';
    echo 'jQuery("#id_filterdate").submit();';
    echo '});';
  }
  
  echo '</script>';
 
  $date = new DateTime($hiddendate, new DateTimeZone($timezone));
  $utcfrom = $date->format('U');
  switch ($view) {
    case 1:
      $date->modify('+1 month');
      $utcto = $date->format('U');
      break;
    case 2:
      $date->modify('+1 year');
      $utcto = $date->format('U');
      break;
    default:
      $date->modify('+1 day');
      $utcto = $date->format('U');
      break;
  }

  $start = intval($_POST['start']);

  global $wpdb;
  $log_table_name = $wpdb->prefix.'tc_visitors_tracker'; 
  
  if ($view == 0) {
    $dbquery = $wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS *,UNIX_TIMESTAMP(rectime) FROM ".$log_table_name." WHERE rectime >= FROM_UNIXTIME(%d) AND rectime < FROM_UNIXTIME(%d) AND ispv = 1 ORDER BY rectime DESC LIMIT %d,%d", $utcfrom, $utcto, $start, $rowlimit);

    $data = $wpdb->get_results($dbquery, ARRAY_A);
    
    if (!$data) {
      echo '<p style="text-align:center">'.esc_html__('No data found for this day.', 'tc-visitors-tracker').'</p>';
      return;  
    }
    
    $data2 = $wpdb->get_results("SELECT FOUND_ROWS()", ARRAY_A);
    if ((!is_array($data2)) || (!is_array($data2[0]))) {
      echo '<p style="text-align:center">'.esc_html__('No data found for this day.', 'tc-visitors-tracker').'</p>';
      return; 
    }

    if (is_numeric($data2[0]['FOUND_ROWS()'])) {
      $rows = $data2[0]['FOUND_ROWS()'];
    } else {
      $rows = 0; 
    }

    if ($rowlimit < $rows) {
      echo '<div class="filterheader">';
      echo '<div class="filterwarpper">';
      if ($start > 0) {
        echo '<div class="filterdiv">';
        echo '<button id="id_first"></button>';
        echo '</div>';
        echo '<div class="filterdiv">';
        echo '<button id="id_back"></button>';
        echo '</div>';
      }
      echo '<div class="filterdiv">';
      echo '<select id="id_pageselect"></select>';
      echo '</div>';
      if ($start < (((ceil($rows/$rowlimit)*$rowlimit))-$rowlimit)) {
        echo '<div class="filterdiv">';
        echo '<button id="id_next"></button>';
        echo '</div>';
        echo '<div class="filterdiv">';
        echo '<button id="id_last"></button>';
        echo '</div>';
      }
      echo '</div>';
      echo '</div>';
      echo '<form method="POST" id="id_pagination">';
      echo '<input type="hidden" name="hiddendate" value="'.$hiddendate.'">';
      echo '<input type="hidden" name="view" value="'.$view.'">';
      echo '<input type="hidden" name="start" id="id_start" value="0">';
      echo '</form>';
        
      echo '<script>';
      echo 'var sel2 = document.getElementById("id_pageselect");';
      echo 'for (var i = 0; i < '.$rows.'; i+='.$rowlimit.') {';
      echo 'var opt = document.createElement("option");';
      echo 'if (('.$start.' >= i) && ('.$start.' < (i+'.$rowlimit.'))) {';
      echo 'opt.value = i;';
      echo 'opt.selected = "selected"';
      echo '} else {';
      echo 'opt.value = i;';
      echo '}';
      echo 'if ((i+'.$rowlimit.') > '.$rows.') {';
      echo 'opt.text = (i+1) + " - " + '.$rows.' + " ";';
      echo '} else {';
      echo 'opt.text = (i+1) + " - " + (i+'.$rowlimit.') + " ";';
      echo '}';
      echo 'sel2.appendChild(opt);';
      echo '}';

      echo 'jQuery("#id_pageselect").on("change", function(){';
      echo 'jQuery("#id_start").val(document.getElementById("id_pageselect").value);';
      echo 'jQuery("#id_pagination").submit();';
      echo '});';
      echo 'jQuery("#id_first").on("click", function(){';
      echo 'jQuery("#id_start").val("0");';
      echo 'jQuery("#id_pagination").submit();';
      echo '});';
      echo 'jQuery("#id_back").on("click", function(){';
      echo 'jQuery("#id_start").val("'.($start-$rowlimit).'");';
      echo 'jQuery("#id_pagination").submit();';
      echo '});';
      echo 'jQuery("#id_next").on("click", function(){';
      echo 'jQuery("#id_start").val("'.($start+$rowlimit).'");';
      echo 'jQuery("#id_pagination").submit();';
      echo '});';
      echo 'jQuery("#id_last").on("click", function(){';
      echo 'jQuery("#id_start").val("'.(((ceil($rows/$rowlimit)*$rowlimit))-$rowlimit).'");';
      echo 'jQuery("#id_pagination").submit();';
      echo '});';
      echo '</script>';
    }
  } else {
    if ($view != 3) {
      $dbquery = $wpdb->prepare("SELECT DATE(rectime) as DATE, COUNT(DISTINCT ipaddr) AS totalcount FROM ".$log_table_name." WHERE rectime >= FROM_UNIXTIME(%d) AND rectime < FROM_UNIXTIME(%d) AND ispv = 1 GROUP BY DATE(rectime)", $utcfrom, $utcto);
    } else {
      $dbquery = "SELECT DATE(rectime) as DATE, COUNT(DISTINCT ipaddr) AS totalcount FROM ".$log_table_name." WHERE ispv = 1 GROUP BY DATE(rectime) DESC";
    }
    $data = $wpdb->get_results($dbquery, ARRAY_A);
  }
 
  echo '<div class="dragscroll">';
  echo '<div class="pvd_table">';
  
  if ($view == 0) {
    $base_url = plugin_dir_url(__FILE__);
    echo '<div class="pvd_thead">';
    echo '<div class="pvd_th"><abbr title="'.esc_html__('Time, relative to the specified timezone', 'tc-visitors-tracker').'">'.esc_html__('Time', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th"><abbr title="'.esc_html__('Country of IP address', 'tc-visitors-tracker').'">'.esc_html__('C.', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th">'.esc_html__('IP Address', 'tc-visitors-tracker').'</div>';
    echo '<div class="pvd_th"><abbr title="'.esc_html__('Operating system', 'tc-visitors-tracker').'">'.esc_html__('O.', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th"><abbr title="'.esc_html__('Browser and version', 'tc-visitors-tracker').'">'.esc_html__('B.', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th"><abbr title="'.esc_html__('The requested page', 'tc-visitors-tracker').'">'.esc_html__('Page', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th"><abbr title="'.esc_html__('URL of origin of the visitor', 'tc-visitors-tracker').'">'.esc_html__('Origin', 'tc-visitors-tracker').'</abbr></div>';
    echo '</div>';    
  
    foreach ($data as $record) {
    echo '<div class="pvd_tr">';
      
    echo '<div class="pvd_tt">';
    echo tc_visitors_tracker_timestamp_datetime($record['UNIX_TIMESTAMP(rectime)'], $timeformat, $timezone);
    echo '</div>';

    echo '<div class="pvd_td">';
    echo '<img class="pvd_img" src="'.$base_url.'images/country/'.$record['countrycode'].'.png" alt="'.$record['countryname'].'" title="'.$record['countryname'].'" />';
    echo '</div>';  

    echo '<div class="pvd_td">';
    echo '<a href="http://www.utrace.de/?query='.$record['ipaddr'].'" target="_blank">'.$record['ipaddr'].'</a>';
    echo '</div>';

    echo '<div class="pvd_td">';
    echo '<img class="pvd_img" src="'.$base_url.'images/os/'.tc_visitors_tracker_get_os_file(strtolower($record['osname'])).'.png" alt="'.$record['osname'].'" title="'.$record['osname'].'" />';
    echo '</div>';  

    echo '<div class="pvd_td">';
    echo '<img class="pvd_img" src="'.$base_url.'images/browser/'.tc_visitors_tracker_get_browser_file(strtolower($record['browsername'])).'.png" alt="'.$record['browsername'].' '.$record['browserversion'].'" title="'.$record['browsername'].' '.$record['browserversion'].'" />';
    echo '</div>';

    echo '<div class="pvd_td">';
    echo '<a href="'.$record['request'].'" target="_blank">'.$record['request'].'</a>';
    echo '</div>';
      
    echo '<div class="pvd_td">';
    echo '<a href="'.$record['referer'].'" target="_blank">'.$record['referer'].'</a>';
    echo '</div>';
    
    echo '</div>';  
    }
  } 
  
  if ($view == 1) {
    $month = date('m', strtotime($hiddendate));
    $year = date('Y', strtotime($hiddendate));
    $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $sum = 0;
    foreach ($data as $record) {
      $sum = $sum + $record['totalcount'];
    }

    echo '<div class="pvd_thead">';
    echo '<div class="pvd_th pvd_td_r">'.esc_html__('Day', 'tc-visitors-tracker').'</div>';
    echo '<div class="pvd_th pvd_td_r"><abbr title="'.esc_html__('Views per IP address per day', 'tc-visitors-tracker').'">'.esc_html__('Views', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th pvd_td_r">%</div>';
    echo '<div class="pvd_th">'.esc_html__('Chart', 'tc-visitors-tracker').'</div>';
    echo '</div>';
    
    for ($i = 1; $i <= $number; $i++) {
      echo '<div class="pvd_tr">';

      echo '<div class="pvd_td pvd_td_r">';
      echo $i.'.';
      echo '</div>';
      
      $totalcount = 0;
      foreach ($data as $record) {
        if ($record['DATE'] == $year.'-'.$month.'-'.sprintf('%02d', $i)) {
          $totalcount = $record['totalcount'];
          break;
        };
      }
      echo '<div class="pvd_td pvd_td_r">';
      echo $totalcount;
      echo '</div>';
      
      if ($sum > 0) {
        $percent = round(($totalcount / $sum) * 100);
      } else {
        $percent = 0;
      }
    
      echo '<div class="pvd_td pvd_td_r">';
      echo $percent.'%';
      echo '</div>';    
    
      echo '<div class="pvd_td" style="width:100%;">';
      echo '<div class="pvd_chart">';
      echo '<div class="pvd_chartblk" style="width:'.$percent.'px;">';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    
      echo '</div>';  
    }
  }

  if ($view == 2) {
    $sum = 0;
    foreach ($data as $record) {
      $sum = $sum + $record['totalcount'];
    }

    echo '<div class="pvd_thead">';
    echo '<div class="pvd_th pvd_td_r">'.esc_html__('Month', 'tc-visitors-tracker').'</div>';
    echo '<div class="pvd_th pvd_td_r"><abbr title="'.esc_html__('Views per IP address per day', 'tc-visitors-tracker').'">'.esc_html__('Views', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th pvd_td_r">%</div>';
    echo '<div class="pvd_th">'.esc_html__('Chart', 'tc-visitors-tracker').'</div>';
    echo '</div>';
    
    for ($i = 1; $i <= 12; $i++) {
      echo '<div class="pvd_tr">';

      echo '<div class="pvd_td pvd_td_r">';
      echo date('F', mktime(0, 0, 0, $i));
      echo '</div>';
      
      $totalcount = 0;
      foreach ($data as $record) {
        if (strpos($record['DATE'], $year.'-'.sprintf('%02d', $i)) === 0) {
          $totalcount = $totalcount + $record['totalcount'];
        };
      }
      echo '<div class="pvd_td pvd_td_r">';
      echo $totalcount;
      echo '</div>';
      
      if ($sum > 0) {
        $percent = round(($totalcount / $sum) * 100);
      } else {
        $percent = 0;
      }
    
      echo '<div class="pvd_td pvd_td_r">';
      echo $percent.'%';
      echo '</div>';    
    
      echo '<div class="pvd_td" style="width:100%;">';
      echo '<div class="pvd_chart">';
      echo '<div class="pvd_chartblk" style="width:'.$percent.'px;">';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    
      echo '</div>';  
    }
  }
  
  if ($view == 3) {
    $sum = 0;
    foreach ($data as $record) {
      $sum = $sum + $record['totalcount'];
    }
    
    $first_year = 0;
    $last_year = $first_year - 1;
    if (isset($data[0])) {
      $first_year = substr($data[0]['DATE'], 0, 4);
      $last_year = substr($data[count($data)-1]['DATE'], 0, 4);
    }

    echo '<div class="pvd_thead">';
    echo '<div class="pvd_th pvd_td_r">'.esc_html__('Year', 'tc-visitors-tracker').'</div>';
    echo '<div class="pvd_th pvd_td_r"><abbr title="'.esc_html__('Views per IP address per day', 'tc-visitors-tracker').'">'.esc_html__('Views', 'tc-visitors-tracker').'</abbr></div>';
    echo '<div class="pvd_th pvd_td_r">%</div>';
    echo '<div class="pvd_th">'.esc_html__('Chart', 'tc-visitors-tracker').'</div>';
    echo '</div>';
    
    for ($i = $first_year; $i <= $last_year; $i++) {
      echo '<div class="pvd_tr">';

      echo '<div class="pvd_td pvd_td_r">';
      echo $i;
      echo '</div>';
      
      $totalcount = 0;
      foreach ($data as $record) {
        if (strpos($record['DATE'], $i.'') === 0) {
          $totalcount = $totalcount + $record['totalcount'];
        };
      }
      echo '<div class="pvd_td pvd_td_r">';
      echo $totalcount;
      echo '</div>';
      
      if ($sum > 0) {
        $percent = round(($totalcount / $sum) * 100);
      } else {
        $percent = 0;
      }
    
      echo '<div class="pvd_td pvd_td_r">';
      echo $percent.'%';
      echo '</div>';    
    
      echo '<div class="pvd_td pvd_td_100">';
      echo '<div class="pvd_chart">';
      echo '<div class="pvd_chartblk" style="width:'.$percent.'px;">';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    
      echo '</div>';  
    }
  }
  
  echo '</div>';  
  echo '</div>';
?>