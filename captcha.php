<?php
  require_once('db_params.php');

  // Begin New code here - Short Description: Get the major version of PHP
  $versionParts = explode('.', phpversion());
  $majorVersion = (int)$versionParts[0];
  // End of new code

  // Modified by: 2025-03-024   - Short Description: Check if PHP version is less than 5.0 or greater than or equal to 5.0
  if (version_compare( phpversion(), '5.0' ) < 0) {  
      define('IS_PHP5' , false);
      define('CLASS_DIR', 'php4_classes/'); 
  } else if (version_compare( phpversion(), '5.0' ) >= 0) {    // modified by: 2025-03-24
     define('IS_PHP5' , true);                                // don't need to change this variable
     define('CLASS_DIR', 'php' . $majorVersion . '_classes/'); 
  }
  // End of modification

  // start output buffering
  ob_start();

  // start session
  session_start();

  // load class
  require_once(CLASS_DIR.'database.class.php');

  // connect to database 
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
