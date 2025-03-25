<?php

  // start session
  session_start();

  define ('LANG_DIR', './languages/');
  define ('THUMB_DIR', './thumbnails/');
  
  define ('MAIN_CATALOG', './');
  
  require_once('head.php'); 

  if (isset($_GET['site_id'])) {
  	$g_site->CountVisit($_GET['site_id']);
  }
  
  $DB->Close();

?>
