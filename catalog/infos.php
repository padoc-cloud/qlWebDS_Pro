<?php
    
  // get templates
  $tmp_tpl = new TemplateClass(); 
  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'infos.tpl.php');

  $eMsg = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';
  $cntTemplate = array();
  $cntTemplate['{message}'] = '';
  $cntTemplate['{mail}'] = '';
  $cntTemplate['{phone}'] = '';
  $cntTemplate['{name}'] = '';
  $cntTemplate['{error}'] = '';
  $cntTemplate['{sel_default}'] = '';
  $cntTemplate['{sel_general}'] = '';
  $cntTemplate['{sel_advertising}'] = '';
  $cntTemplate['{sel_change}'] = '';
  $cntTemplate['{sel_problem}'] = '';
  $cntTemplate['{sel_feedback}'] = '';
  $cntTemplate['{sel_other}'] = '';
  
  $tpl_captcha_array = array();
  $captcha_error = '';

  $cntTemplate['{page title}'] = $p_params['page_title_'.$p_index];
  $cntTemplate['{page content}'] = $p_params['page_content_'.$p_index];
  	
  if($p_params['page_contact_us_'.$p_index]) {
      
  	  $cntIfRegion['iscontactus'] = 1;
	  if (USE_CAPTCHA) {
	      $tpl_captcha_array['{captcha text}'] = LANG_CAPTCHA;
	      $tpl_captcha_array['{captcha text2}'] = LANG_CAPTCHA2;
	      $tpl_captcha_array['{captcha input}'] = '<input name="captcha" maxlength="15" size="15" type="text">';
	      $tpl_captcha_array['{captcha img}'] = '<img src="captcha.php" style="border: 1px solid red;" alt="Captcha">';
	      $aIfRegion['iscaptcha'] = 1; 
	  } else {
	      $tpl_captcha_array['{captcha text}'] = '';
	      $tpl_captcha_array['{captcha text2}'] = '';
	      $tpl_captcha_array['{captcha input}'] = '';
	      $tpl_captcha_array['{captcha img}'] = '';  
	      $aIfRegion['iscaptcha'] = 0;
	  }

  		  $tpl_captcha_array['{lang required}'] = LANG_USER_REQUIRED;
	  
	  if (isset($_POST['text'])) {
	
	    $text = 'The following is a feedback from your Website:<br><br>Message: '.$_POST['text'];
	    $mail = $g_site->CheckEmail($_POST['mail']);
	    $title = $_POST['title'];
	    $bad_title = (strpos($title,'Feedback') === false);
	    if (strpos($title,'General') <> false) {
		  $cntTemplate['{sel_general}'] = 'selected="selected"';
	    } elseif (strpos($title,'Advertising') <> false) {
		  $cntTemplate['{sel_advertising}'] = 'selected="selected"';
	    } elseif (strpos($title,'Change') <> false) {
		  $cntTemplate['{sel_change}'] = 'selected="selected"';
	    } elseif (strpos($title,'Problem') <> false) {
		  $cntTemplate['{sel_problem}'] = 'selected="selected"';
	    } elseif (strpos($title,'Feedback') <> false) {
		  $cntTemplate['{sel_feedback}'] = 'selected="selected"';
	    } elseif (strpos($title,'Other') <> false) {
		  $cntTemplate['{sel_other}'] = 'selected="selected"';
	    } else {
		  $cntTemplate['{sel_default}'] = 'selected="selected"';
	    }
	    
		$name = '<br>Name: '.$_POST['name'];
		$phone = '<br>Phone Number: '.$_POST['phone'];
		$mail = '<br>'.$_POST['mail'];
		
	    $dlg = strlen($text);
	    
	    // check captcha
	    if (USE_CAPTCHA) {
	      if (!session_id()) session_start();
	      $sid = session_id();
	      $captcha = new CaptchaClass();
	      if ($captcha->CheckCaptcha($sid,$_POST['captcha'])) {
	          
	      } else {
	        $captcha_error = ADD_SITE_04;
	      }
	    }
	    
	    if($dlg and $mail and !$bad_title and empty($captcha_error)) {
	      $header = 'From: <' . $mail .'>' . "\r\n";
	      $header .= 'Reply-To: <' . $mail . '>' . "\r\n" . 'X-Mailer: PHP/4.3.9';
	      $header .= 'MIME-Version: 1.0' . "\r\n";
	      $header .= 'Content-type: text/html; charset=' .DEFAULT_CHARSET. "\r\n";
	      mail(ADMIN_EMAIL, $title, $text.$name.$phone.$mail, $header);        
	      $cntTemplate['{error}'] = '<font color="#339900">Thank You. Your message was sent.</font>'; 
	    } else {
	      if (!isset($_POST['captcha_refresh'])) {
	      	if ($bad_title) {
	      	  $cntTemplate['{error}'] = $eMsg; 
	    	  $cntTemplate['{error}'] .= '<br>'.ADD_SITE_14;
	      	}
	      	if (!$dlg) {
	      	  if (empty($cntTemplate['{error}'])) {
	      	    $cntTemplate['{error}'] = $eMsg; 
	      	  }
	    	  $cntTemplate['{error}'] .= '<br>'.ADD_SITE_13;
	      	}
	      	if (!$mail) {
	      	  if (empty($cntTemplate['{error}'])) {
	      	    $cntTemplate['{error}'] = $eMsg; 
	      	  }
	      	  $cntTemplate['{error}'] .= '<br>'.ADD_SITE_03;
	      	}
	    	if (!empty($captcha_error)){
	      	  if (empty($cntTemplate['{error}'])) {
	      	    $cntTemplate['{error}'] = $eMsg; 
	      	  }
	    	  $cntTemplate['{error}'] .= '<br>'.$captcha_error;
	    	}
	      }
		  $cntTemplate['{message}'] = $_POST['text'];
		  $cntTemplate['{mail}'] = $_POST['mail'];
		  $cntTemplate['{phone}'] = $_POST['phone'];
		  $cntTemplate['{name}'] = $_POST['name'];
	    }
	
	  } else {
		  $cntTemplate['{sel_default}'] = 'selected="selected"';
	  }
  } else {
  	  $cntIfRegion['iscontactus'] = 0;
  }
	  
  $cnt_tpl = new TemplateClass(); 
  $cnt_tpl->SetTemplate(DIR_TEMPLATE.'page.tpl.php');    
  $cnt_tpl->ReplaceIn($cntTemplate);
  $cnt_tpl->IfRegion($cntIfRegion);
  
  $toTemplate['{content}'] = $cnt_tpl->Get();
  $toTemplate['{home}'] = $g_params->Get('site','site_name');
  $tmp_tpl->ReplaceIn($toTemplate);
  if($p_params['page_contact_us_'.$p_index]) {
  	  $tmp_tpl->ReplaceIn($tpl_captcha_array);   
	  $tmp_tpl->IfRegion($aIfRegion);
  }
  $tpl_main = $tmp_tpl->Get();
  
?>
