<?php
  
  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();
  
  $isError = false;
  $eMsg = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';
  
  $tpl_adds_array['{error}'] = '';  
  
  if (isset($_POST['submit'])){
  
    if (strpos( $_POST['user'],"'")) {
    	$isError = true;
    } else {
        if ($g_user->Login($_POST['user'], $_POST['pass'],false)) {
        	header('location: index.php?page=user_account');
        } else {
    		$isError = true;
        }
    }
    if ($isError) {
    	$eMsg = LANG_LOGIN_ERROR;
    }
  }
  	
  $tpl_adds_array['{address}'] = $g_addr;
  
  //////////////////////////////////////////////////////////
  // fill array with language values (from language)
  //////////////////////////////////////////////////////////
  
  $tpl_adds_array['{lang login title}'] = LANG_LOGIN_TITLE;     
  $tpl_adds_array['{lang user}'] = LANG_LOGIN_USER;
  $tpl_adds_array['{lang pass}'] = LANG_LOGIN_PASS;
  $tpl_adds_array['{lang login button}'] = LANG_LOGIN_BUTTON;
  $tpl_adds_array['{lang forgot pass}'] = LANG_LOGIN_FORGOT_PASS;
  $tpl_adds_array['{lang required}'] = LANG_USER_REQUIRED;
  $tpl_adds_array['{lang title}'] = LANG_FP_TITLE;
  $tpl_adds_array['{lang back}'] = LANG_BACK;

  if (isset($_POST['submit']) and ($isError) ){
  
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // failure, show form and errors 
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    if (strlen($eMsg)) {
      $tpl_adds_array['{error}'] = '<div class="info">'.$eMsg.'</div>';
    } else {
      $tpl_adds_array['{error}'] = '';
    }
    
  } 
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // filling form
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
  // fill template
  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'login.tpl.php');  
  	
  $tmp_tpl->ReplaceIn($tpl_adds_array);   
  $tpl_main = $tmp_tpl->Get();      
        
?>
