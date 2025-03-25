<?php

  require_once('db_params.php');
  require_once('config.php');
  
  // New code here
  $versionParts = explode('.', phpversion());
  $majorVersion = (int)$versionParts[0];
  // End of new code

  // Modified by: 2025-03-024
  if (version_compare( phpversion(), '5.0' ) < 0) {  
      define('IS_PHP5' , false);
      define('CLASS_DIR', 'php4_classes/'); 
  } else if (version_compare( phpversion(), '5.0' ) >= 0) {    // modified by: 2025-03-24
     define('IS_PHP5' , true);                                // don't need to change this variable
     define('CLASS_DIR', 'php' . $majorVersion . '_classes/'); 
  }
  
  $g_errors = array();

    // start time
    $mtime=microtime();
    $mtime=explode(" ",$mtime);
    $mtime=$mtime[1] + $mtime[0];
    $tStart=$mtime;
  
  // connect to database 
  require_once(CLASS_DIR.'database.class.php');
  if (IS_PHP5) {
    $DB = DataBase::getInstance();  
    date_default_timezone_set(date_default_timezone_get());
  } else {
    $DB =& DataBase::getInstance();
  }
  if (!$DB->Open()) {  echo 'Couldn`t connect to database'; exit;  }

  // load params
  include(CLASS_DIR.'params.class.php');
  
  $g_params = new ParamsClass();  
  include('load_params.php');
    
  include(CLASS_DIR.'grid.class.php');
  include(CLASS_DIR.'template.class.php');
  include(CLASS_DIR.'form.class.php');
  include(CLASS_DIR.'user.class.php');
  include(CLASS_DIR.'users.class.php');
  include(CLASS_DIR.'categories.class.php');
  include(CLASS_DIR.'sites.class.php');
  include(CLASS_DIR.'captcha.class.php');    
  include(CLASS_DIR.'cache.class.php');
  include(CLASS_DIR.'upload.class.php');

  $g_template = new TemplateClass;
  // $g_form = new FormClass;
  $g_user = new UserLogin( $_SERVER['REMOTE_ADDR']);
  $g_users = new UsersClass;
  $g_categ = new CategoriesClass();
  $g_site = new SitesClass();
  $g_cache = new CacheClass(MAIN_CATALOG);

  $g_user->CheckIfIsLogged();

 // security 
 if (!isset($_GET['o'])) {
  foreach ($_POST as $key => $value) {
  
   if(is_string($value)) {
   
     if (!$g_user->IsLogged()) {
       if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
          $value = stripslashes($value);
       }     
       $_POST[$key] =  htmlspecialchars($value);

     } else {
       if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
          $_POST[$key] = stripslashes($_POST[$key]);
       }
     }
   }
  }
 }
  
  $g_addr = htmlentities($_SERVER['PHP_SELF']);
  $g_cache_addr = htmlentities($_SERVER['QUERY_STRING']);
  if ($g_cache_addr) {
      $g_addr .= "?" . $g_cache_addr;
  }

  function sp_replace($in) {
    $sp_chars = array (
		'&'=>'&amp;',
		'©'=>'&copy;',
		'™'=>'&trade;',
		'®'=>'&reg;',
		'℠'=>'&#8480;',
		'•'=>'&bull;',
		'§'=>'&sect;',
		'«'=>'&laquo;',
		'»'=>'&raquo;',
		'←'=>'&larr;',
		'↑'=>'&uarr;',
		'→'=>'&rarr;',
		'↓'=>'&darr;',
		'†'=>'&dagger;',
		'€'=>'&euro;',
		'£'=>'&pound;',
		'☮'=>'&#9774;',
		'☯'=>'&#9775;',
		'½'=>'&frac12;',
		'¼'=>'&frac14;',
		'¾'=>'&frac34;'    
	);
	
  	foreach ($sp_chars as $key=>$value) {
  		$in = str_replace($key,$value,$in);
  	}
  	
  	return $in;
  }
  
 ?>
