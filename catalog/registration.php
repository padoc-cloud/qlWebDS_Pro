<?php

  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();

  $isError = false;
  $eMsg = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';
  
  $tpl_adds_array['{error}'] = '';

  // initialize terms checkbox
  $tpl_adds_array['{terms_checked}'] = '';

  // submit guidelines URL
  $tpl_adds_array['{submit_guidelines}'] = '';
  $page_submit_guidelines = $g_params->Get('pages','page_submit_guidelines');
  if($page_submit_guidelines <> 0) {
  	$page_name = $g_params->Get('pages','page_name_'.$page_submit_guidelines);
    if (MOD_REWRITE) {
        $tpl_adds_array['{submit_guidelines}'] = $page_name.'.html';
    } else {
        $tpl_adds_array['{submit_guidelines}'] = 'index.php?page='.$page_name;
    }
  }
  
  $values = array('user'=>'','real_name'=>'','pass'=>'','email'=>'',
                  'company'=>'','address'=>'','city'=>'','state'=>'','zip'=>'',
                  'country'=>'','tel'=>'','fax'=>'');

  $pass_confirm = '';
  
  
  if ((isset($_POST['submit']) or isset($_POST['captcha_refresh'])) ) {
  
    
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // check POST data  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      foreach($values as $key =>$value) {
        switch ($key) {
          case 'user':
            $values[$key] = $_POST[$key];
          	if (!$g_users->CheckName($values[$key])) {
            	$eMsg .= '<p>'.ADD_USER_01.'</p>';
          		$isError = true; 
            }
            if (!$isError and $g_users->NameExists($values[$key])) {
            	$eMsg .= '<p>'.ADD_USER_02.'</p>';
          		$isError = true; 
            }
            break;
                    
          case 'real_name':
            $values[$key] = $_POST[$key];
          	if (!$g_users->CheckRealName($values[$key])) {
            	$eMsg .= '<p>'.ADD_USER_03.'</p>';
          		$isError = true; 
            }
            break;
            
          case 'pass':
            $values[$key] = $_POST[$key];
            $pass_confirm = $_POST['pass_confirm'];
            if (strlen($values[$key])< 5) {
            	$eMsg .= '<p>'.ADD_USER_04.'</p>';
          		$isError = true; 
            }
            if (!$isError) {
	            if (strcmp($values[$key], $pass_confirm) === 0) {
				// OK
	            } else { 
	            	$eMsg .= '<p>'.ADD_USER_05.'</p>';
	          		$isError = true; 
	            }
            }
            break;
          	
          case 'email':
            $values[$key] = $_POST[$key];
            if (!$g_users->CheckEmail($values[$key])) {
              $eMsg .= '<p>'.ADD_USER_06.'</p>';
              $isError = true;
            }            
            break;
             
          case 'company':
          case 'address':
          case 'city':
          case 'state':
          case 'zip':
          case 'country':
          case 'tel':
          case 'fax':
            $values[$key] = $_POST[$key];
            break;
            
        } // end switch
        
      } // end for
    
    if (isset($_POST['captcha_refresh'])) {
        $isError = true;
        $eMsg = '';
    } else {
    
      // check captcha
      if (USE_CAPTCHA) {
         if (!session_id())  session_start();
         $sid = session_id();
         $captcha = new CaptchaClass();
         if (!$captcha->CheckCaptcha($sid,$_POST['captcha'])) {
            $eMsg .= '<p>'.ADD_USER_07.'</p>';
            $isError = true;
         }
      }
    }
        
  }

  $tpl_adds_array['{address}'] = $g_addr;
  
  //////////////////////////////////////////////////////////
  // fill array with language values (from language)
  //////////////////////////////////////////////////////////
  
  $tpl_adds_array['{registration title}'] = LANG_USER_REGISTRATION_TITLE;
  $tpl_adds_array['{lang required}'] = LANG_USER_REQUIRED;     
  $tpl_adds_array['{lang user}'] = LANG_USER_NAME;     
  $tpl_adds_array['{lang real_name}'] = LANG_USER_REAL_NAME;
  $tpl_adds_array['{lang pass}'] = LANG_USER_PASS;
  $tpl_adds_array['{lang pass_confirm}'] = LANG_USER_PASS_CONFIRM;
  $tpl_adds_array['{lang email}'] = LANG_USER_EMAIL;
  $tpl_adds_array['{lang company}'] = LANG_USER_COMPANY;
  $tpl_adds_array['{lang address}'] = LANG_USER_ADDR;
  $tpl_adds_array['{lang city}'] = LANG_USER_CITY;
  $tpl_adds_array['{lang state}'] = LANG_USER_STATE;
  $tpl_adds_array['{lang zip}'] = LANG_USER_ZIP;
  $tpl_adds_array['{lang country}'] = LANG_USER_COUNTRY;
  $tpl_adds_array['{lang tel}'] = LANG_USER_TEL;
  $tpl_adds_array['{lang fax}'] = LANG_USER_FAX;
  $tpl_adds_array['{lang submit}'] = LANG_USER_SUBMIT_BTN;
  $tpl_adds_array['{lang back}'] = LANG_BACK;
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // filling form
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
  if(!isset($_POST['submit'])) {
    if (USE_CAPTCHA) {
      $tpl_adds_array['{captcha text}'] = LANG_CAPTCHA;
      $tpl_adds_array['{captcha text2}'] = LANG_CAPTCHA2;
      $tpl_adds_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
      $tpl_adds_array['{captcha img}'] = '<img src="captcha.php" alt="captcha" style="border: 1px solid red;">';
      $aIfRegion['iscaptcha'] = 1 ; 

    } else {
      $tpl_adds_array['{captcha text}'] = '';
      $tpl_adds_array['{captcha text2}'] = '';
      $tpl_adds_array['{captcha input}'] = '';
      $tpl_adds_array['{captcha img}'] = '';  
      $aIfRegion['iscaptcha'] = 0 ;
    } 
    
    // fill input values
    foreach ($values as $key=>$value) {
      $tpl_key = '{'.$key.' value}';
      $tpl_adds_array[$tpl_key] = $values[$key];
    }

    $tpl_adds_array['{pass_confirm value}'] = $pass_confirm;
    $country_value = $values['country'];
    
    // fill registration template
    $tmp_tpl->SetTemplate(DIR_TEMPLATE.'registration.tpl.php');  
    $tmp_tpl->IfRegion($aIfRegion);
    
  } else if (isset($_POST['submit']) and ($isError) ){
  
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // failure, show form and errors 
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    if (USE_CAPTCHA) {
      $tpl_adds_array['{captcha text}'] = LANG_CAPTCHA;
      $tpl_adds_array['{captcha text2}'] = LANG_CAPTCHA2;
      $tpl_adds_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
      $tpl_adds_array['{captcha img}'] = '<img src="captcha.php" style="border: 1px solid red;">';
      $aIfRegion['iscaptcha'] = 1 ; 

    } else {
      $tpl_adds_array['{captcha text}'] = '';
      $tpl_adds_array['{captcha text2}'] = '';
      $tpl_adds_array['{captcha input}'] = '';
      $tpl_adds_array['{captcha img}'] = '';
      $aIfRegion['iscaptcha'] = 0 ;  
    } 

    // fill input values
    foreach ($values as $key=>$value) {
      $tpl_key = '{'.$key.' value}';
      $tpl_adds_array[$tpl_key] = $values[$key];
    }
    $tpl_adds_array['{pass_confirm value}'] = $pass_confirm;
    $tpl_adds_array['{pass value}'] = $pass_confirm;
    $country_value = $values['country'];
    
    // populate terms checkbox
    $tpl_adds_array['{terms_checked}'] = 'checked';
    
    if (strlen($eMsg)) {
      $tpl_adds_array['{error}'] = '<div class="info">'.$eMsg.'</div>';
    } else {
      $tpl_adds_array['{error}'] = '';
    }
          
    // set template    
    $tmp_tpl->SetTemplate(DIR_TEMPLATE.'registration.tpl.php');  
    $tmp_tpl->IfRegion($aIfRegion);
    
  } else if (isset($_POST['submit']) and (!$isError) ){
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // successful registration, save user
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $values['registration_date'] = date(DATETIME_FORMAT);
    $values['status'] = USER_ACTIVE;
    $values['access_level'] = AL_USER;
    
    // add user    
    $id = $g_users->RegisterUser($values);
        
    if ($id>0) {
      
      // send e-mail to admin
      if ($g_params->Get('site', 'notify_admin')) {
      	$email_from = ADMIN_EMAIL;
      	$email_to = ADMIN_EMAIL;
      	$email_title = 'New Registration in: '.SITE_ADDRESS;        
      	$email_body = 'New User Registered in: '.SITE_ADDRESS.'<br>Name: '.$values['real_name'].'<br>Username: '.$values['user'].'<br>E-mail: '.$values['email'];
        $g_users->SendEmail($email_from,$email_to,$email_title,$email_body);
      }

      // send e-mail to user
      $email_from = ADMIN_EMAIL;
      $email_to = $values['email'];
      $email_title = 'Registration Confirmation';        
      $email_body = 'Dear '.$values['real_name'].'<br><br>';
      $email_body .= 'Thank you for registering at <a href="'.SITE_ADDRESS.'">'.SITE_NAME.'</a>'.'<br><br>';
      $email_body .= 'Your user name is: '.$values['user'].'<br>';
      $email_body .= 'Your password is: ***'.substr($pass_confirm,3).'<br><br>';
      $email_body .= 'All the Best,'.'<br><br>';
      $email_body .= SITE_NAME;
      $g_users->SendEmail($email_from,$email_to,$email_title,$email_body);
      
      $tpl_adds_array['{user name}'] = $values['user'];
  	  $tpl_adds_array['{home_url}']= SITE_ADDRESS;
      
      $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_registration_confirmation.tpl.php');
       
    } else {
      $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_registration_failure.tpl.php'); 
    }    
      
  }
   
  // countries dropdown logic
  $coForm = new FormClass();
  $coform_fields = array('CBO|country');   
  $coForm->SetCombo('country', $g_countries, 'value', 'name');
  $country_array = array('country'=>$country_value);
  $coform_array = $coForm->MakeForm($coform_fields, $country_array);
  $tpl_adds_array['{country}'] = $coform_array['{country}'];
  
  $tmp_tpl->ReplaceIn($tpl_adds_array);   
  $tpl_main = $tmp_tpl->Get();      
        
?>
