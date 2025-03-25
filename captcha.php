<?php

  if (version_compare( phpversion(), '5.0' ) < 0) {
	  define('CLASS_DIR', 'php4_classes/');
	  define('IS_PHP5', false);
  } else {
    define('CLASS_DIR', 'php5_classes/');
    define('IS_PHP5', true);
  }
  
  // start output buffering
  ob_start();

  // start session
  session_start();

  require_once('db_params.php');

  // load class
  // connect to database 
  require_once(CLASS_DIR.'database.class.php');
  if (IS_PHP5) {
    $DB = DataBase::getInstance();
  } else {
    $DB =& DataBase::getInstance();  
  }
  require(CLASS_DIR.'captcha.class.php');
    
  // create captcha
  $captcha = new CaptchaClass();
  $captcha->ShowImage();
  
  // store string in a session
  $sid = session_id();

  $captcha->SetCaptcha($sid);  

?>
