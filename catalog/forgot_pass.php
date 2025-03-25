<?php
  
  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();
  
  $isError = false;
  $eMsg = '';
  
  $tpl_adds_array['{error}'] = '';  
  
  if (isset($_POST['submit'])){
  
    if (!$g_users->CheckEmail($_POST['email'])) {
  	    	$isError = true;
    		$eMsg = LANG_FP_INCORRECT_EMAIL_ERROR;
    } else {
    	$fp_user = $g_users->GetUserByEmail($_POST['email']);
        if ($fp_user) {
	      // send email to user
	      $email_from = ADMIN_EMAIL;
	      $email_to = $fp_user['email'];
	      $email_title = 'Registration Information';        
	      $email_body = 'Dear '.$fp_user['real_name'].',<br><br>';
	      $email_body .= 'We have received your request to recover your username and password.<br><br>';
	      $email_body .= 'Your Username is: '.$fp_user['user'].'<br>';
	      $email_body .= 'Your Password is: '.$fp_user['pass'].'<br><br>';
	      $email_body .= 'All the Best,'.'<br><br>';
	      $email_body .= SITE_NAME;
	      $g_users->SendEmail($email_from,$email_to,$email_title,$email_body);
    	  $eMsg = LANG_FP_SUCCESS_MESSAGE;
	      
        } else {
    		$isError = true;
    		$eMsg = LANG_FP_EMAIL_NOT_FOUND_ERROR.' <a href="?page=registration"><u>Register</u></a>';
        }
    }
  }
  	
  $tpl_adds_array['{address}'] = $g_addr;
  
  //////////////////////////////////////////////////////////
  // fill array with language values (from language)
  //////////////////////////////////////////////////////////  
  $tpl_adds_array['{lang title}'] = LANG_FP_TITLE;     
  $tpl_adds_array['{lang email}'] = LANG_FP_EMAIL;
  $tpl_adds_array['{lang button}'] = LANG_FP_BUTTON;
  $tpl_adds_array['{lang back}'] = LANG_BACK;
    
    if (strlen($eMsg)) {
      $tpl_adds_array['{error}'] = '<div class="info">'.$eMsg.'</div>';
    } else {
      $tpl_adds_array['{error}'] = '';
    }
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // filling form
  // fill template
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'forgot_pass.tpl.php');  
  	
  $tmp_tpl->ReplaceIn($tpl_adds_array);   
  $tpl_main = $tmp_tpl->Get();      
        
?>
