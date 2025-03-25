<?php

    // start time
    $mtime=microtime();
    $mtime=explode(" ",$mtime);
    $mtime=$mtime[1] + $mtime[0];
    $tEnd=$mtime;
    $all = $tEnd-$tStart;

 if ($g_params->Get('site','debug_mode')) {
 	
    echo '<div style="text-align:left; float:left; margin-top:15px;"><pre style="font-size:12px;">';
    echo '----------- DEBUG MODE: ON ----------------------------------'."\r\n";
    echo '----------- Time: ['.$all.'] --------------------------'."\r\n";
    echo '----------- SQL ----------------------------------'."\r\n";
         print_r($DB->m_qTime);
         $all_time = 0;
         foreach ($DB->m_qTime as $row) {
          $time = $g_site->Between($row, '[',']');
          $all_time += (float) $time;
         }
         echo 'All sql time: '.$all_time."\r\n";
         $all-= $all_time;
         echo 'All php time: '.$all."\r\n";
    echo '----------- Site ---------------------------------'."\r\n";
         print_r($g_debug->m_qTime);
    echo '----------- POST ---------------------------------'."\r\n";
         print_r($_POST);
    echo '----------- GET ---------------------------------'."\r\n";
         print_r($_GET);
    echo '----------- ERRORS ---------------------------------'."\r\n";
         print_r($g_errors);
    echo '----------- COOKIE -------------------------------'."\r\n";
         print_r($_COOKIE);
  if (isset($_FILES)) {     
    echo '----------- FILES -------------------------------'."\r\n";
         print_r($_FILES);
  }                  
  
  if (isset($g_tree)) {
    echo '----------- CZASY --------------------------------'."\r\n";
         print_r($czasy);
    echo '----------- TREE ---------------------------------'."\r\n";
         print_r($g_tree);
  }
  
  echo $g_cache->bck;
   echo '</pre></div>';
 }

?>
