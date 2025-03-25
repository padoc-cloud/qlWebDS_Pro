<?php

  require_once('db_params.php');
  
  if (version_compare( phpversion(), '5.0') < 0) {  
      define('IS_PHP5' , false);
      define('CLASS_DIR', 'php4_classes/'); 
  } else {
     define('IS_PHP5' , true);
     define('CLASS_DIR', 'php5_classes/'); 
  }
    
  // connect to database
  require_once(CLASS_DIR.'database.class.php');
  if (IS_PHP5) {
    $DB = DataBase::getInstance();  
    date_default_timezone_set(date_default_timezone_get());
  } else {
    $DB =& DataBase::getInstance();  
  }
  if (!$DB->Open()) {echo 'Couldn`t Connect to Database'; exit;}

  // load params
  define ('LANG_DIR', './languages/');
  
  include(CLASS_DIR.'params.class.php');
  $g_params = new ParamsClass();
  
  define('ADMIN_EMAIL', $g_params->Get('site', 'admin_email'));
  define('SITE_ADDRESS', $g_params->Get('site', 'site_address'));
  define('USE_CAPTCHA' , $g_params->Get('site','use_captcha'));
  
  define('MAX_CATEG_LENGHT', $g_params->Get('for_user','max_categ_lenght'));
  define('MAX_TITLE_LENGHT', $g_params->Get('for_user','max_title_lenght'));
  define('MIN_DESCR_SHORT_LENGHT', $g_params->Get('for_user','min_description_short_lenght'));
  define('MAX_DESCR_SHORT_LENGHT', $g_params->Get('for_user','max_description_short_lenght'));
  define('MIN_DESCR_LENGHT', $g_params->Get('for_user','min_description_lenght'));
  define('MAX_DESCR_LENGHT', $g_params->Get('for_user','max_description_lenght'));
  
  $lang_file = $g_params->Get('site','language');
  if ($lang_file == '') {
    $lang_file = 'lang_en.def.php';
  }
  require_once(LANG_DIR.$lang_file);
  
  $eMsg = '';
  $isError = false;

  // template
  include(CLASS_DIR.'template.class.php');
  $tmp_tpl = new TemplateClass(); 
  $tmp_tpl->SetTemplate('broken.tpl.php');
  
  $aIfRegion['done'] = 0; 
  $tpl_array['{error}'] = '';  
  $tpl_array['{comment value}'] = '';
  
  include(CLASS_DIR.'captcha.class.php');    
  
  if (USE_CAPTCHA) {
    $tpl_array['{captcha text}'] = LANG_CAPTCHA;
    $tpl_array['{captcha text2}'] = LANG_CAPTCHA2;
    $tpl_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
    $tpl_array['{captcha img}'] = '<img src="captcha.php" style="border: 1px solid red;" alt="Captcha">';
    $aIfRegion['iscaptcha'] = 1 ; 
    
  } else {
    $tpl_array['{captcha text}'] = '';
    $tpl_array['{captcha text2}'] = '';
    $tpl_array['{captcha input}'] = '';
    $tpl_array['{captcha img}'] = '';  
    $aIfRegion['iscaptcha'] = 0 ; 
  }
  
  if (isset($_POST['yes'])) {

    // check captcha
    if (USE_CAPTCHA) {
       session_start();
       $sid = session_id();
       $captcha = new CaptchaClass();
       if ($captcha->CheckCaptcha($sid,$_POST['captcha'])) {
        
       } else {
          $eMsg .= '<p>'.LANG_INVALID_CODE.'</p>';
          $isError = true;
       }
    }
  	
    // no errors, send email
    if (!$isError) {
	   $mail = ADMIN_EMAIL;
	   $id = (int) $_GET['id'];
	   $ip = $_SERVER['REMOTE_ADDR'];
	   $text = "Broken Link/Incorrect Info: ".SITE_ADDRESS."index.php?site=" . $id . "
			From IP: $ip
			Comment: $_POST[comment]";
       $header = 'From: Directory User <'. $mail . '>' . "\r\n";
       $header .= 'Reply-To: <' . $mail . '>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
       $header .= 'Content-type: text; charset=utf-8' . "";
       $dlg = strlen($mail);
       if($dlg) {     
          $ok = @mail($mail, "Broken Link/Incorrect Info", $text, $header);
       }
	   $aIfRegion['done'] = 1; 
    } else {
		$tpl_array['{comment value}'] = $_POST['comment'];
    	$tpl_array['{error}'] = '<font color="red">'.$eMsg.'</font>';  
    }
  }

  // captcha refresh
  if (isset($_POST['captcha_refresh'])) {
    $tpl_array['{comment value}'] = $_POST['comment'];
  }
  
  $tmp_tpl->ReplaceIn($tpl_array);    
  $tmp_tpl->IfRegion($aIfRegion);
  $tmp_tpl->Show();

?>
