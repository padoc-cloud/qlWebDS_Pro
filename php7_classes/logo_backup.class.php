<?php

      $l_error = false; 
      $backup_file = $backup_dir.SCRIPT.'_'.date('Y-m-d_H.i.s').'.logos.zip';
      $zip = new ZipArchive;
	  $open_result = $zip->open($backup_file,ZipArchive::CREATE);
	  if ($open_result === TRUE) {
	  	
	    $d = dir(MAIN_CATALOG.LOGO_DIR);
	    $arl_Dir = array();
	
	    while (false !== ($entry = $d->read())) {
	      if($entry != '.' && $entry != '..') {
	        if (!is_dir($entry)) {
				if (!$zip->addFile(MAIN_CATALOG.LOGO_DIR.'/'.$entry,$entry)) {
      				$notifications = '<div class="info">'.LANG_LOGOS_BACKUP_ERROR1.' '.$entry.'</div>';
      				$l_error = true;
					break;
				}
	        }
	      }
	    }
	    $d->close();
	    $zip->close();
	    if(!$l_error) {
	        $notifications = '<div class="info">'.LANG_LOGOS_BACKUP_CREATED.'</div>';
	    }
	  } else {
      	$notifications = '<div class="info">'.LANG_BACKUP_ERROR1.'</div>';
	  }

?>
