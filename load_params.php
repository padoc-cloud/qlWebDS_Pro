<?php
  
  define('DEBUG_MODE', $g_params->Get('site','debug_mode') );

  if (DEBUG_MODE) {
    error_reporting(E_ALL | E_STRICT);
  } else {
    error_reporting(0);
  }    
  
  define('MOD_CATEG', 'categ');
  define('MOD_SITE', 'more');
  define('DIR_TEMPLATE', 'templates/'.$g_params->Get('template','dir_template').'/');
  
  // site
  define('MOD_REWRITE', $g_params->Get('site', 'mod_rewrite'));
  
  $p_e = $g_params->Get('site', 'page_extension');
  if (!$p_e) {
  	$p_e = '.htm';
  }
  define('PAGE_EXTENSION', $p_e);
  
  $w_s = $g_params->Get('site', 'word_separator');
  if (!$w_s) {
  	$w_s = ',';
  }
  define('WORD_SEPARATOR', $w_s);
  
  define('USE_CACHE', $g_params->Get('site', 'use_cache'));
  define('SITES_PER_PAGE' , $g_params->Get('site','view_sites_per_page') );
  define('USE_CAPTCHA' , $g_params->Get('site','use_captcha'));
  define('CATALOG_ADDRESS' , $g_params->Get('site','site_address'));
  define('DEFAULT_CHARSET', $g_params->Get('site', 'site_charset'));
  define('ADMIN_EMAIL', $g_params->Get('site','admin_email'));
  define('DONT_SEND_EMAILS', !$g_params->Get('site','dont_send_emails'));
  define('SITE_TITLE', $g_params->Get('site', 'site_title'));
  define('SITE_NAME', $g_params->Get('site','site_name'));
  define('SITE_ADDRESS', $g_params->Get('site', 'site_address'));
  define('SUBS_ON_MAIN_PAGE', $g_params->Get('site','subs_on_main_page'));
  define('SITE_ACTIVE', !$g_params->Get('site', 'site_status'));
  define('SITE_UNDER_MAINTENANCE_MESSAGE',$g_params->Get('site', 'site_under_maintenance'));
  
  // for_user
  define('LINK_ON_TOP_CATEG', $g_params->Get('for_user', 'allow_sites_in_top_categ'));
  define('ALLOW_ADD_CATEG', $g_params->Get('for_user','allow_adding_categories'));
  define('COLLECT_PAGE_RANK', $g_params->Get('for_user','collect_pagerank'));
  define('COLLECT_META', $g_params->Get('for_user','collect_meta'));
  define('SITE_MAX_CATEG', $g_params->Get('for_user','max_categories_per_site'));
  define('LINK_INSTANTLY_APPEAR', $g_params->Get('for_user','link_instantly_appear'));
  define('PAID_LINK_INSTANTLY_APPEAR', $g_params->Get('for_user','paid_link_instantly_appear'));
  define('PAY_BEFORE_SUBMIT', $g_params->Get('for_user','pay_before_submit'));
  define('MAX_CATEG_LENGHT', $g_params->Get('for_user','max_categ_lenght'));
  define('MAX_TITLE_LENGHT', $g_params->Get('for_user','max_title_lenght'));
  define('MIN_DESCR_SHORT_LENGHT', $g_params->Get('for_user','min_description_short_lenght'));
  define('MAX_DESCR_SHORT_LENGHT', $g_params->Get('for_user','max_description_short_lenght'));
  define('MIN_DESCR_LENGHT', $g_params->Get('for_user','min_description_lenght'));
  define('MAX_DESCR_LENGHT', $g_params->Get('for_user','max_description_lenght'));
  
  // payment
  $g_payment = $g_params->GetParams('payment');
  define('CURRENCY', $g_payment['currency']);
  define('PAYMENT_PERIOD', $g_payment['payment_period']);
  define('PAYPAL_ACCOUNT', $g_payment['paypal_account']);

  if (!isset($_POST['admin_settings_submit'])) {
	  $lang_file = $g_params->Get('site','language');
	  if ($lang_file == '') {
	    $lang_file = 'lang_en.def.php';
	  }
	  require_once(LANG_DIR.$lang_file);
  }
  
?>
