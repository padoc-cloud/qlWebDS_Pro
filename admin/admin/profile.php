<?php 
  
  if ($g_user->Level() == AL_ADMIN) {
  
    $isError = false;
    $eMsg = '';
    $notifications = '';
    
    $template = ADMIN_TEMPLATE_DIR.'admin_profile.html';
      
    $tmpErrors = array();
    $title = '';
    $email_body = '';
      
    // template 
    $site_tpl['{site_approved_email selected}'] = '';
    $site_tpl['{site_not_approved_email selected}'] = '';
    $site_tpl['{payment_email selected}'] = '';
      
    if (isset ($_POST['email_type']) ) {
        
      $email_type = $_POST['email_type'];
      $site_tpl['{'.$_POST['email_type'].' selected}'] = 'selected';
         
    } else {
      $email_type = 'site_approved_email';
      $site_tpl['{'.$email_type.' selected}'] = 'selected';
    } 
           
    // save settings
    if (isset($_POST['submit'])) {

      // register user
      $form = array('pass', 'user', 'email');
      
      foreach ($form as $key) {
        if (isset($_POST[$key])) {
          
          switch ($key) {
            
            case 'pass':
              if (isset($_POST['change_pass'])) {
                $isError = true;
                if (strlen($_POST[$key])>4) {
                  if (strcmp($_POST[$key], $_POST[$key.'2']) === 0) {
                  
                    $aUser[$key] = md5($_POST[$key]);  
                    $isError = false;
                    
                  } else { $eMsg .= 'Passwords do not match.<br>';  }
                } else { $eMsg .= 'Password is too short. Password must contain at least 5 characters long.<br>'; }
              }
              break;
              
            case 'user':
              $aUser[$key] = $_POST[$key];
              if (!$g_users->CheckName($_POST[$key]) ) {
                $eMsg .= 'Incorrect Username.<br>';  
                $isError = true; 
              }
              break;
                    
            case 'email':
              $aUser[$key] = $_POST[$key];
              if (!$g_users->CheckEmail($_POST[$key])) {
                $eMsg .= 'Incorrect E-mail.<br>';           
                $isError = true; 
              }
              break;                
          }
        
        } else {
          switch ($key) {
          
            case 'pass':
            case 'pass2':
              break;
              
            default:  
              $eMsg .= 'Incorrect Input.<br>';  
              $isError = true;
              break;
          }       
        }
        
      } // foreach
      
      // check for errors
      if (!$isError and $g_users->UpdateUser($aUser, $g_user->GetId()) !==false) {
         $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
      } else {
         $notifications = '<div class="info">'.$eMsg.'</div>';
      }    
                 
  } // isset post submit
    
      $params = $g_params->GetParams('ads');    
      
   	  // make template 
      // $site_tpl['{title}'] = $title;
      // $site_tpl['{email_body}'] = $email_body;
   	  // fill lang template
      // $site_tpl['{lang my_site_title}'] = LANG_MY_SITE_TITLE;
      // $site_tpl['{lang my_site_url}'] = LANG_MY_SITE_URL;
      // $site_tpl['{lang user_site_title}'] = LANG_USER_SITE_TITLE;
      // $site_tpl['{lang user_site_url}'] = LANG_USER_SITE_URL;
      // $site_tpl['{lang user_categories_links}'] = LANG_USER_CATEGORIES_LINKS;
      // $site_tpl['{lang user_site_info_url}'] = LANG_USER_SITE_INFO_LINK;
      // $site_tpl['{lang payment_email}'] = LANG_PAYMENT_RECEIVED;
      // $site_tpl['{lang site_approved_email}'] = LANG_SITE_APPROVED_EMAIL;
      
      $site_tpl['{notifications}'] = $notifications;
      if (!isset($_POST['submit'])) {
	      $aUser = $g_user->GetUser();
      } 
      $site_tpl['{user}'] = $aUser['user'];
      $site_tpl['{email}'] = $aUser['email'];
      
      $g_template->SetTemplate($template);  
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get();
          
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }
 
?>
