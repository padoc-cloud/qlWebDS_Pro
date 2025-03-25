<?php

  if ($g_user->Level() == AL_ADMIN) {
  
    $notifications = '';
  	require_once('sitemap.php');
    $backup_dir = MAIN_CATALOG.'backup/';
    $backup_date = '-';
    $backup_logos_date = '-';
  	$main_if_region['php_5'] = 0;
  	if (IS_PHP5) {
	  	$main_if_region['php_5'] = 1;
  	}
    
    if (isset($_POST['clear_pagerank'])) {
      $ret = $g_site->ClearPagerank();
      $notifications = '<div class="info">'.LANG_PAGERANK_CLEARED.'</div>';
    }
    
    if (isset($_POST['make_backup'])) {
        
       ///////////////////////////////////////////////////////////////
       // make db backup
       ///////////////////////////////////////////////////////////////
        
       $backup_file = $backup_dir.DB_NAME.'_'.SCRIPT.'_'.date('Y-m-d_H.i.s').'.sql.gz';
       $gp = gzopen($backup_file, 'w');
        
       if (!$gp) {
          $notifications = '<div class="info">'.LANG_BACKUP_ERROR1.'</div>';
          
       } else {
         
        $sql_text = "";
        $sql_text .= "# --------------------------------------------------------------------------------- #\r\n";       
        $sql_text .= "# Description: ".FULL_VERSION." MySQL Database Backup Dump\r\n";
        $sql_text .= "# Date: ".date(DATETIME_FORMAT)."\r\n";
        $sql_text .= "# Website: ".CATALOG_ADDRESS."\r\n";
        $sql_text .= "# Database Name: ".DB_NAME."\r\n";
        $sql_text .= "# --------------------------------------------------------------------------------- #\r\n\r\n";
  
        $sql_text .= "# Drop tables \r\n";
        
        $db_tables = array('captcha', 'categories', 'connection', 'params', 'payment', 'session', 'sites', 'users', 'visits');
        
        ///////////////////////////////////////////////////////////////
        // drop tables
        ///////////////////////////////////////////////////////////////
        
        foreach ($db_tables as $db_table) {
          $db_table = $DB->tablePrefix.$db_table;  
          $sql_text .= "DROP TABLE IF EXISTS `$db_table`;\r\n";
    
        }
  
        $sql_text .= "\r\n";  
        
        ///////////////////////////////////////////////////////////////  
        // create tables
        ///////////////////////////////////////////////////////////////
        
        foreach ($db_tables as $db_table) {
          $db_table = $DB->tablePrefix.$db_table;  
  
          $sql_create = $DB->GetRow("SHOW CREATE TABLE `{$db_table}`");
    
          $sql_text .= $sql_create['Create Table']." ;\r\n";
          $sql_text .= "\r\n";  
    
        }
        fwrite($gp, $sql_text);

        ///////////////////////////////////////////////////////////////
        // inserts  
        ///////////////////////////////////////////////////////////////
      
        $sql_text .= "# TABLES DUMP #\r\n";

        foreach ($db_tables as $db_table) {
        
           $db_table = $DB->tablePrefix.$db_table;  
           $query = "SELECT * FROM  " . $db_table;
           $table = $DB->GetTable($query) ;
                  
           foreach ($table as $row) {
             $a_keys = array();
             $a_values = array();
             
             $row = array_map("mysql_real_escape_string", $row);
             
             foreach ($row as $key=>$value) {
                $a_keys[] = $key;
                $a_values[] = $value; 
             }
             
             $keys = implode($a_keys, '`,`');
             $values = implode($a_values, "','");
    
             $sql_text .= 'INSERT INTO '.$db_table." ( `$keys` ) VALUES ( '$values'); \r\n";
             
           }
           fwrite($gp, $sql_text);
           $sql_text = "\r\n\r\n# --------------------------------------- #\r\n\r\n";
           
        } 

        fclose($gp);
        $notifications = '<div class="info">'.LANG_BACKUP_CREATED.'</div>';
      }
      
    } else if (isset($_POST['empty_cache'])) {
      
      ///////////////////////////////////////////////////////////////
      // empty cache
      ///////////////////////////////////////////////////////////////
          
      if ($g_cache->EmptyCache('../')) {
        $notifications = '<div class="info">'.LANG_CACHE_DELETED.'</div>';
      } else {
      
      }
    } else if (isset($_POST['make_logos_backup'])) {

      ///////////////////////////////////////////////////////////////
      // logos folder backup
      ///////////////////////////////////////////////////////////////

      include(MAIN_CATALOG.CLASS_DIR.'logo_backup.class.php');	
    	
    }

    ///////////////////////////////////////////////////////////////
    // last backup
    ///////////////////////////////////////////////////////////////
    
    $d = dir(MAIN_CATALOG.'backup/');
    $arDir = array();
    $arlDir = array();
    
    while (false !== ($entry = $d->read())) {
      if($entry != '.' && $entry != '..') {
      	// DB
        if (!is_dir($entry) and strcmp('sql.gz', substr($entry, -6))===0 ) {
         $arDir[] = $entry;
        }
        // logos
        if (!is_dir($entry) and strcmp('logos.zip', substr($entry, -9))===0 ) {
         $arlDir[] = $entry;
        }
      }
    }
    $d->close();

    $last_backup = '';
    if (count($arDir)) {
      sort($arDir);
      $last_backup = array_pop($arDir);
      // $aTmp = explode('qlweb_', $last_backup);

      $backup_date = $last_backup;
    }
    $last_logos_backup = '';
    if (count($arlDir)) {
      sort($arlDir);
      $last_logos_backup = array_pop($arlDir);

      $backup_logos_date = $last_logos_backup;
    }
    
    ///////////////////////////////////////////////////////////////  
    // language       
    ///////////////////////////////////////////////////////////////
    
    $site_tpl['{lang make_backup}'] = LANG_MAKE_BACKUP;
    $site_tpl['{lang make_logos_backup}'] = LANG_MAKE_LOGOS_BACKUP;
    $site_tpl['{lang empty_cache}'] = LANG_EMPTY_CACHE;
    $site_tpl['{lang actions}'] = LANG_ACTIONS;
    $site_tpl['{lang logos_actions}'] = LANG_LOGOS_ACTIONS;
    $site_tpl['{lang cache}'] = LANG_CACHE;
    $site_tpl['{lang backup_dir}'] = LANG_BACKUP_DIR;
    $site_tpl['{lang database_backup}'] = LANG_DATABASE_BACKUP;
    $site_tpl['{lang logos_backup}'] = LANG_LOGOS_BACKUP;
    $site_tpl['{lang download_backup}'] = LANG_DOWNLOAD_BACKUP;
    $site_tpl['{lang last_backup}'] =  LANG_LAST_BACKUP;
    $site_tpl['{lang clear_pagerank}'] = LANG_CLEAR_PAGERANK;
    
    $site_tpl['{notifications}'] =  $notifications;
    $site_tpl['{backup_date}'] =  $backup_date;
    $site_tpl['{backup_logos_date}'] =  $backup_logos_date;
    $site_tpl['{backup_dir}'] =  $backup_dir;
    $site_tpl['{download_backup_url}'] =  $backup_dir.$last_backup;
    
    $template = ADMIN_TEMPLATE_DIR.'admin_main.html';     
    $g_template->SetTemplate($template);  
  	$g_template->IfRegion( $main_if_region );
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();   

  }  else {
       $tpl_main['{content}'] = ERROR_002;
  }
     
?>
