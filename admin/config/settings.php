<?php
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'config_settings.html';
        
      $cForm = new FormClass();
      
      $site_tpl = array();
      $notifications = '';
      $form = '';
      $tmpErrors = array();
            
      $form_fields = array(
        
        // page administration
        'CBO|debug_mode', 'CBO|mod_rewrite', 'CBO|word_separator','CBO|page_extension', 'CBO|use_cache', 'CBO|use_captcha', 'CBO|language', 'T-230|admin_email', 'T-230|site_address',
        'F-40|view_sites_per_page', 'F-40|subs_on_main_page', 'CBO|categories_on_left', 'TA-350-4-50|site_home_description', 'TA-350-3-50|site_notes',
		'TA-325-1-50|admin_url1', 'TA-325-1-50|admin_url2', 'TA-325-1-50|admin_url3', 'TA-325-1-50|admin_url4', 'TA-350-2-50|site_google_analytics',
        'CBO|notify_admin', 'CBO|dont_send_emails', 'CBO|random_site', 'CBO|statistics_on_main_page', 'CBO|users_online',
        'CBO|phone_search',
        'CBO|site_status', 'TA-350-2-50|site_under_maintenance',       

        // site meta
        'TA-350-5-50|site_description', 'T-350|site_keywords', 'T-350|site_title', 'T-350|site_name', 'T-350|site_company', 'T-350|site_year', 'T-100|site_charset',
        );   

      $site_text = array();
      
      // combos
      // yes/no
      $yesno = array( 
             array('value'=>1, 'name'=>'Yes'),
             array('value'=>0, 'name'=>'No') );
             
      // languages combo
      $languages = dirTree(LANG_DIR);  

      // assign combo to form       
      $cForm->SetCombo('debug_mode', $yesno, 'value', 'name');
      $cForm->SetCombo('mod_rewrite', $yesno, 'value', 'name');
      $cForm->SetCombo('use_cache', $yesno, 'value', 'name');
      $cForm->SetCombo('use_captcha', $yesno, 'value', 'name');
      $cForm->SetCombo('categories_on_left', $yesno,  'value', 'name');
      $cForm->SetCombo('notify_admin', $yesno, 'value', 'name');
      $cForm->SetCombo('dont_send_emails', $yesno, 'value', 'name');
      $cForm->SetCombo('random_site', $yesno, 'value', 'name');
      $cForm->SetCombo('statistics_on_main_page', $yesno, 'value', 'name');
      $cForm->SetCombo('users_online', $yesno, 'value', 'name');
      $cForm->SetCombo('phone_search', $yesno, 'value', 'name');
      
      $cForm->SetCombo('language', $languages, 'name');
      

      $page_ext = array( 
             array('value'=>'.htm', 'name'=>'.htm'),
             array('value'=>'.html', 'name'=>'.html') );
      $cForm->SetCombo('page_extension', $page_ext, 'value', 'name');
                  
      $word_separators = array( 
             array('value'=>',', 'name'=>'comma'),
             array('value'=>'-', 'name'=>'hyphen') );
      $cForm->SetCombo('word_separator', $word_separators, 'value', 'name');

      $site_statuses = array( 
             array('value'=>'0', 'name'=>'Site Active'),
             array('value'=>'1', 'name'=>'Site Under Maintenance') );
      $cForm->SetCombo('site_status', $site_statuses, 'value', 'name');
      
      // save settings   
      // if (isset($_POST['submit'])) {
      if (isset($_POST['admin_settings_submit'])) {
      	
          $where = array();
          foreach($form_fields as $value) {
            list($type, $key) = explode('|',$value);
            
            if (isset($_POST[$key])

			/*
			and strlen($_POST[$key])>0 
			*/

			) {
              switch ($key) {

                case 'site_address':
                  $site_url = $g_site->ParseUrl($_POST[$key]);
                  $config_update[$key] = $site_url;
                  break;

                default:
                  $config_update[$key] = $_POST[$key];

                  break;
              } // switch
            } else {
              $tmpErrors[$key] = $value;  
            } // if             
      
          } // foreach
          
          // check for errors
          if (( count($tmpErrors) === 0) and $g_params->UpdateParams('site',$config_update)!==false) {
             $g_cache->EmptyCache('../'); 
             $lang_file = $g_params->Get('site','language');
			 if ($lang_file == '') {
			 	$lang_file = 'lang_en.def.php';
			 }
			 require_once(LANG_DIR.$lang_file);
             
			 $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
             
          } else {
              $text = implode(', ', $tmpErrors);
              $notifications = '<div class="info">'.LANG_SETTING_SAVED_ERROR.'. <br>Errors: '.$text.'</div>';
          }      
      }

      // make form
      $form_array = $cForm->MakeForm($form_fields, $g_params->GetParams('site'));
      
      // populate admin custom URLs values
      for ($i=1; $i <= 4; $i++) {
    	  $site_tpl['{admin_url'.$i.'_value}'] = $g_params->Get('site','admin_url'.$i);
  	  }
            
      $site_tpl = array_merge($site_tpl, $form_array);
      
      // text
      /*   
      $site_text = array(
            
            // page administration
            'debug_mode'=>'settings_006', 'mode_rewrite'=>'settings_007', 'language'=>'settings_008', 'admin_email'=>'settings_009',
            
            //site
            'site_description'=>'settings_015', 'site_keywords'=>'settings_016', 'site_title'=>'settings_017', 'site_charset'=>'settings_018',
      );
      
      foreach ($site_text as $key=> $caption) {

           if (isset($message[$caption])) { 
            
             $site_tpl['{lang '.$key.'}'] = $message[$caption]; 
           }
      }
      */

	  $site_tpl['{lang site_home_description}'] = LANG_SITE_HOME_DESCRIPTION;
	  $site_tpl['{lang site_notes}'] = LANG_SITE_NOTES;
	  $site_tpl['{lang admin_url1}'] = LANG_ADMIN_URL1;
	  $site_tpl['{lang admin_url2}'] = LANG_ADMIN_URL2;
	  $site_tpl['{lang admin_url3}'] = LANG_ADMIN_URL3;
	  $site_tpl['{lang admin_url4}'] = LANG_ADMIN_URL4;
      $site_tpl['{lang site_name}'] = LANG_SITE_NAME;
      $site_tpl['{lang site_google_analytics}'] = LANG_SITE_GOOGLE_ANALYTICS;
      $site_tpl['{lang site_company}'] = LANG_SITE_COMPANY;
      $site_tpl['{lang site_year}'] = LANG_SITE_YEAR;
      $site_tpl['{lang site_title}'] = LANG_SITE_TITLE;      
      $site_tpl['{lang site_description}'] = LANG_DESCRIPTION; 
      $site_tpl['{lang site_keywords}'] = LANG_KEYWORDS; 
      $site_tpl['{lang site_charset}'] = LANG_CHARSET; 
      $site_tpl['{lang site_address}'] = LANG_ADDRESS; 
      $site_tpl['{lang language}'] = LANG_LANGUAGE; 
      $site_tpl['{lang debug_mode}'] = LANG_DEBUG_MODE;
      $site_tpl['{lang mod_rewrite}'] = LANG_MOD_REWRITE;
      $site_tpl['{lang page_extension}'] = LANG_PAGE_EXTENSION;
      $site_tpl['{lang word_separator}'] = LANG_WORD_SEPARATOR;
      $site_tpl['{lang use_cache}'] = LANG_USE_CACHE;
      $site_tpl['{lang use_captcha}'] = LANG_USE_CAPTCHA;
      $site_tpl['{lang view_sites_per_page}'] = LANG_SITES_PER_PAGE;
      $site_tpl['{lang admin_email}'] = LANG_ADMIN_EMAIL;
      $site_tpl['{lang subs_on_main_page}'] = LANG_SUBS_ON_MAIN_PAGE;
      $site_tpl['{lang categories_on_left}'] = LANG_CATEGORIES_ON_LEFT;                     
      $site_tpl['{lang dont_send_emails}'] = LANG_DONT_SEND_EMAILS;
      $site_tpl['{lang notify_admin}'] = LANG_NOTIFY_ADMIN;
      $site_tpl['{lang random_site}'] = LANG_RANDOM_SITE;
      $site_tpl['{lang statistics_on_main_page}'] = LANG_STATISTICS_ON_MAIN_PAGE;
      $site_tpl['{lang users_online}'] = LANG_USERS_ONLINE;
      $site_tpl['{lang phone_search}'] = LANG_PHONE_SEARCH;
      $site_tpl['{lang site_status}'] = LANG_SITE_STATUS; 
      $site_tpl['{lang site_under_maintenance}'] = LANG_SITE_UNDER_MAINTENANCE; 
      
      $site_tpl['{notifications}'] = $notifications;
      $site_tpl['{lang submit}'] = LANG_SAVE;
      $site_tpl['{site settings}'] = LANG_SITE_META;
      $site_tpl['{page administration settings}'] = LANG_SITE_SETTINGS;
      $site_tpl['{password recovery settings}'] = '';  
                    
   	  // make template   
      $g_template->SetTemplate($template);  
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get(); 
 
 } else {
       $tpl_main['{content}'] = ERROR_002;
 }
   
   // functions  
   function dirTree($dir) {
    $d = dir($dir);
    $arDir = array();

    while (false !== ($entry = $d->read())) {
      if($entry != '.' && $entry != '..') {
           $arDir[] = array('name'=>$entry);
      }
    }
    $d->close();

    return $arDir;
    
  } 
  
?>
