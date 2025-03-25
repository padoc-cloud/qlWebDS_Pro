<?php
  
  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();

  if ($g_user->Level() == AL_USER) {
  	
	  $tpl_adds_array['{error}'] = '';  
  
	  //////////////////////////////////////////////////////////
	  // fill array with language values (from language)
	  //////////////////////////////////////////////////////////
	  
	  $tpl_adds_array['{lang logout title}'] = LANG_LOGOUT_TITLE;     
	  
	  if ($g_user->Logout()) {
        	header('location: ./');
	  } else {
	  	$tpl_adds_array['{lang logout message}'] = LANG_LOGOUT_FAIL;
	  }

	  // fill template
	  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'logout.tpl.php');  
	  
  } else {
	  $tpl_adds_array['{error}'] = USER_NOT_LOGGED_ERROR;
  	  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_error.tpl.php');      
  }
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // filling form
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $tmp_tpl->ReplaceIn($tpl_adds_array);   
  $tpl_main = $tmp_tpl->Get();      
	  
?>
