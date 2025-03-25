<?php
  
  // start session
  session_start();

  define ('ADMIN_TEMPLATE_DIR', 'admin_template/');
  define ('LANG_DIR', '../languages/');
  define ('MAIN_CATALOG', '../');
  
  // debug performance check class 
  include(MAIN_CATALOG.'php4_classes/debug.class.php');
  $g_debug = new DebugClass;
  
  $error = false;
  $tpl_main = array();  
    
  require_once('../head.php');

  // charset
  header("Content-Type: text/html; charset=".DEFAULT_CHARSET);  
  
  // include(MAIN_CATALOG.CLASS_DIR.'/users.class.php');  
  // $g_users = new UsersClass;
    
  if ($g_user->Level()==AL_ADMIN) {
    
    $g_modules = array('config', 'site','links','admin', 'tools', 'users', 'error');
    
    if (isset($_GET['mod'])) {
      
      // check if module exists
      $module = $_GET['mod'];
      if ( !in_array($module, $g_modules) ) {

        $module = './';
        $inc = 'info';
        $param = '001';
        
      } else {
        
        // get include file
        if (isset($_GET['inc']) and strpos($_GET['inc'], '.')===false ) {
          
          $inc = $_GET['inc'];
          if (!file_exists($module.'/'.$inc.'.php')) {
            $param = '001';   
            $error = true;
          }
          
        } else {
            $param = '001';   
            $error = true; 
        }
      
      }
    } else { $module = 'config'; $inc = 'messages';}
    
    // fill template classes  
    $hide_div = ' style="display: none;" ';  
    
    foreach ($g_modules as $m) {
      $tpl_main['{ct'.$m.'}'] = '';
      $tpl_main['{dis'.$m.'}'] = $hide_div;
    }
    
    $tpl_main['{ct'.$module.'}'] = 'active';
    $tpl_main['{dis'.$module.'}'] = '';
          
    // include module
    if ($error) {
      $include = './info.php';
    } else {   
      $include = $module.'/'.$inc.'.php';
    }
    
	// CAUTION!!! Do NOT change below this line!!!
  	// Removing or changing the code below might eventually make your site STOP working!!!
    include($include);
    include('config/message.php');
    include('config/message_1.php');
    include('config/message_2.php');
    include('config/message_3.php');
    include('config/message_4.php');
    include('config/message_5.php');
    include('config/message_6.php');
    include('config/message_7.php');
 	$tpl_main['{developer}'] = DEVELOPER;
 	$tpl_main['{developer_website}'] = DEVELOPER_WEBSITE;
 	$tpl_main['{developer_url}'] = DEVELOPER_URL;
 	$tpl_main['{script_family}'] = SCRIPT_FAMILY;
 	$tpl_main['{script_group}'] = SCRIPT_GROUP;
 	$tpl_main['{script_name}'] = SCRIPT_NAME;
 	$tpl_main['{script_name_short}'] = SCRIPT_NAME_SHORT;
 	$tpl_main['{script}'] = SCRIPT;
 	$tpl_main['{full_version}'] = FULL_VERSION;
 	$tpl_main['{version}'] = VERSION;
 	$tpl_main['{version_number}'] = VERSION_NUMBER;
 	$tpl_main['{sub_version}'] = SUB_VERSION;
 	$tpl_main['{years}'] = YEARS;
    $tpl_main['{foot}'] = FOOT;
 	$tpl_main['{license_type}'] = LICENSE_TYPE;
 	$tpl_main['{brand_type}'] = BRAND_TYPE;
 	$tpl_main['{thumbnail_code_url}'] = THUMBNAIL_CODE_URL;

    $g_template->SetTemplate(ADMIN_TEMPLATE_DIR.'index.tpl.php');
  
  // log in 
  } else {
    $tpl_main['{info}'] = '';
 	$tpl_main['{developer}'] = DEVELOPER;
 	$tpl_main['{developer_website}'] = DEVELOPER_WEBSITE;
 	$tpl_main['{developer_url}'] = DEVELOPER_URL;
 	$tpl_main['{script_family}'] = SCRIPT_FAMILY;
 	$tpl_main['{script_group}'] = SCRIPT_GROUP;
 	$tpl_main['{script_name}'] = SCRIPT_NAME;
 	$tpl_main['{script_name_short}'] = SCRIPT_NAME_SHORT;
 	$tpl_main['{script}'] = SCRIPT;
 	$tpl_main['{full_version}'] = FULL_VERSION;
 	$tpl_main['{version}'] = VERSION;
 	$tpl_main['{version_number}'] = VERSION_NUMBER;
 	$tpl_main['{sub_version}'] = SUB_VERSION;
 	$tpl_main['{years}'] = YEARS;
    $tpl_main['{foot}'] = FOOT;
 	$tpl_main['{license_type}'] = LICENSE_TYPE;
 	$tpl_main['{brand_type}'] = BRAND_TYPE;
 	$tpl_main['{thumbnail_code_url}'] = THUMBNAIL_CODE_URL;
    
    if (isset($_POST['submit'])) {
      if (strpos( $_POST['login'],"'")) { 
        $tpl_main['{info}'] = '<span style="color: #EE2222">Wrong Username or Password</span>';
      } else {
        if ($g_user->Login($_POST['login'], $_POST['password'])) {
          header('location: index.php');
        } else {
          $tpl_main['{info}'] = '<span style="color:#EE2222">Wrong Username or Password</span>';
        }
      }
      
    }
    $g_template->SetTemplate(ADMIN_TEMPLATE_DIR.'login.html');  
  }
   
  $tpl_main['{charset}'] = $g_params->Get('site', 'site_charset');
  $tpl_main['{site_address}'] = $g_params->Get('site', 'site_address');
     
  $g_template->ReplaceIn($tpl_main);
  $g_template->Show();
  
  $cCateg = new CategoriesClass();
  $cCateg->TestCache();
  require_once('../foot.php');

?>
