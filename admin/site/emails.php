<?php
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'site_emails.html';
      
      $tmpErrors = array();
      $title = '';
      $email_body = '';
      $notifications = '';
      
      // template 
      $site_tpl['{site_approved_email selected}'] = '';
      $site_tpl['{site_not_approved_email selected}'] = '';
      $site_tpl['{payment_email selected}'] = '';
      $site_tpl['{site_pending_email selected}'] = '';
      
      if (isset ($_POST['email_type']) ) {
        
        $email_type = $_POST['email_type'];
        $site_tpl['{'.$_POST['email_type'].' selected}'] = 'selected';
         
      } else {
        $email_type = 'site_pending_email';
        $site_tpl['{'.$email_type.' selected}'] = 'selected';
      } 
           
      // save settings
      if (isset($_POST['save_email'])) {
          
          $title = $params['title'] = $_POST['title'];
          $email_body = $params['email_body'] = $_POST['email_body'];
          $email_type = $_POST['email_type'];
   
          // check for errors
          if (( count($tmpErrors) === 0) and $g_params->UpdateParams($email_type,$params)!==false) {
             $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
          } else {
              $text = implode(', ', $tmpErrors);
              $notifications = '<div class="info">'.LANG_SETTING_SAVED_ERROR.'. <br>Errors: '.$text.'</div>';
          }
                  
      } else {
      
        $p = $g_params->GetParams($email_type);
        $email_body = $p['email_body'];
        $title = $p['title'];
        
      } 
    
      $params = $g_params->GetParams('ads');    
  
      // make template 
      $site_tpl['{title}'] = $title;
      $site_tpl['{email_body}'] = $email_body;
      $site_tpl['{notifications}'] = $notifications;
      
      // fill lang template
      $site_tpl['{lang my_site_name}'] = LANG_MY_SITE_NAME;
      $site_tpl['{lang my_site_title}'] = LANG_MY_SITE_TITLE;
      $site_tpl['{lang my_site_url}'] = LANG_MY_SITE_URL;
      $site_tpl['{lang user_site_title}'] = LANG_USER_SITE_TITLE;
      $site_tpl['{lang user_site_url}'] = LANG_USER_SITE_URL;
      $site_tpl['{lang user_categories_links}'] = LANG_USER_CATEGORIES_LINKS;
      $site_tpl['{lang user_site_info_url}'] = LANG_USER_SITE_INFO_LINK;
      
      $site_tpl['{lang payment_email}'] = LANG_PAYMENT_RECEIVED;
      $site_tpl['{lang site_approved_email}'] = LANG_SITE_APPROVED_EMAIL;
      $site_tpl['{lang site_not_approved_email}'] = LANG_SITE_NOT_APPROVED_EMAIL;
      $site_tpl['{lang site_pending_email}'] = LANG_SITE_PENDING_EMAIL;
         
      $g_template->SetTemplate($template);  
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }
  
?>
