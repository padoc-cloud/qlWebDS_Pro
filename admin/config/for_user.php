<?php

  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'config_for_user.html';
        
      $cForm = new FormClass();
      
      $site_tpl = array();
      $notifications = '';
      $form = '';
      $tmpErrors = array();
            
      $form_fields = array(

        // users restrictions
        'CBO|allow_adding_categories', 'CBO|collect_meta', 'CBO|collect_pagerank', 
        'CBO|allow_sites_in_top_categ', 'F-40|max_categories_per_site', 
        'F-40|max_categ_lenght', 'F-40|max_title_lenght', 
		'F-40|min_description_short_lenght', 'F-40|max_description_short_lenght',
        'F-40|min_description_lenght', 'F-40|max_description_lenght', 
        'CBO|link_instantly_appear', 'CBO|paid_link_instantly_appear',
        'CBO|allow_users','CBO|user_logged_to_add',
        'CBO|pay_before_submit'
      );   

      $site_text = array();
      
      // combos
      // yes/no
      $yesno = array( 
             array('value'=>1, 'name'=>'Yes'),
             array('value'=>0, 'name'=>'No') );
      
      // assign combo to form
      $cForm->SetCombo('allow_adding_categories', $yesno, 'value', 'name');
      $cForm->SetCombo('allow_sites_in_top_categ', $yesno, 'value', 'name');
      $cForm->SetCombo('collect_meta', $yesno, 'value', 'name');
      $cForm->SetCombo('collect_pagerank', $yesno, 'value', 'name');
      $cForm->SetCombo('link_instantly_appear', $yesno, 'value', 'name');
      $cForm->SetCombo('paid_link_instantly_appear', $yesno, 'value', 'name');
      $cForm->SetCombo('allow_users', $yesno, 'value', 'name');
      $cForm->SetCombo('user_logged_to_add', $yesno, 'value', 'name');
      $cForm->SetCombo('pay_before_submit', $yesno, 'value', 'name');
      
      // save settings
      if (isset($_POST['submit'])) {

          $where = array();
          foreach($form_fields as $value) {
            list($type, $key) = explode('|',$value);
            
            if (isset($_POST[$key]) ) {
            
              switch ($key) {
                default:
                  $config_update[$key] = $_POST[$key];
                  break;
              } // switch
              
            } else {
              $tmpErrors[$key] = $value;  
            } // if             
      
          } // foreach
                    
          // check for errors
          if (( count($tmpErrors) === 0) and $g_params->UpdateParams('for_user',$config_update)!==false) {
             $g_cache->EmptyCache('../'); 
             $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
          } else {
              $text = implode(', ', $tmpErrors);
              $notifications = '<div class="info">'.LANG_SETTING_SAVED_ERROR.'. <br>Errors: '.$text.'</div>';
          } 
          $g_cache->EmptyCache(MAIN_CATALOG);     
      }

      // make form
      $form_array = $cForm->MakeForm($form_fields, $g_params->GetParams('for_user'));

      $site_tpl = array_merge($site_tpl, $form_array);
      
      // text   
      $site_text = array(
            
          // page administration
          'debug_mode'=>'settings_006', 'mode_rewrite'=>'settings_007', 'language'=>'settings_008', 'admin_email'=>'settings_009',
            
          // site
          'site_description'=>'settings_015', 'site_keywords'=>'settings_016', 'site_title'=>'settings_017', 'site_charset'=>'settings_018', 'site_description_short'=>'settings_019', 
      );
      
      foreach ($site_text as $key=> $caption) {

           if (isset($message[$caption])) { 
            
             $site_tpl['{lang '.$key.'}'] = $message[$caption]; 
           }

      }

      $site_tpl['{lang allow_sites_in_top_categ}'] = LANG_ALLOW_SITES_IN_TOP_CATEG;
      $site_tpl['{lang allow_adding_categories}'] = LANG_ALLOW_ADD_CATEG;
      $site_tpl['{lang collect_pagerank}'] = LANG_COLLECT_PAGERANK;
      $site_tpl['{lang collect_meta}'] = LANG_COLLECT_META;
      $site_tpl['{lang link_instantly_appear}'] = LANG_LINK_INSTANTLY_APPEAR;
      $site_tpl['{lang paid_link_instantly_appear}'] = LANG_PAID_LINK_INSTANTLY_APPEAR;
      $site_tpl['{lang pay_before_submit}'] = LANG_PAY_BEFORE_SUBMIT;
      $site_tpl['{lang max_categories_per_site}'] = LANG_MAX_CATEG_PER_SITE;
      $site_tpl['{lang max_categ_lenght}'] = LANG_MAX_CATEG_LENGHT;
      $site_tpl['{lang max_title_lenght}'] = LANG_MAX_TITLE_LENGHT;
      $site_tpl['{lang min_description_short_lenght}'] = LANG_MIN_DESCRIPTION_SHORT_LENGHT;
      $site_tpl['{lang max_description_short_lenght}'] = LANG_MAX_DESCRIPTION_SHORT_LENGHT;
      $site_tpl['{lang min_description_lenght}'] = LANG_MIN_DESCRIPTION_LENGHT;
      $site_tpl['{lang max_description_lenght}'] = LANG_MAX_DESCRIPTION_LENGHT;
      $site_tpl['{lang allow_users}'] = LANG_ALLOW_USERS;
      $site_tpl['{lang allow_video}'] = LANG_ALLOW_VIDEO;
      $site_tpl['{lang user_logged_to_add}'] = LANG_USER_LOGGED_TO_ADD;
      
      $site_tpl['{user_logged_to_add_value}'] = $g_params->Get('for_user','allow_users');
      
      $site_tpl['{notifications}'] = $notifications;
      $site_tpl['{lang submit}'] = LANG_SAVE;
      $site_tpl['{site settings}'] = LANG_SITE_META;
                    
      // make template 
      $g_template->SetTemplate($template);  
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get(); 
 
 } else {
       $tpl_main['{content}'] = ERROR_002;
 }
  
?>
