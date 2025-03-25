<?php
  
  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();

  $isError = false;
  $eMsg = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';
  
  $tpl_adds_array['{edit error}'] = '';  
  $tpl_adds_array['{user message}'] = '';  
  
  $values = array('real_name'=>'','pass'=>'','email'=>'',
                  'company'=>'','address'=>'','city'=>'','state'=>'','zip'=>'',
                  'country'=>'','tel'=>'','fax'=>'');

  $pass_confirm = '';
  
  if ($g_user->Level() == AL_USER) {
  	
	  $user = $g_user->GetUser();
	  $tpl_adds_array['{user value}'] = $user['user'];
	  $user_id = $user['id'];
  	  
	  
	  if ((isset($_POST['submit']))) {
	  
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  // check POST data  
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	      foreach($values as $key =>$value) {
	        switch ($key) {
	          case 'real_name':
	            $values[$key] = $_POST[$key];
	          	if (!$g_users->CheckRealName($values[$key])) {
	            	$eMsg .= '<p>'.ADD_USER_03.'</p>';
	          		$isError = true; 
	            }
	            break;
	            
	          case 'pass':
	            if (isset($_POST['change_pass'])) {
		          	$values[$key] = $_POST[$key];
		            $pass_confirm = $_POST['pass_confirm'];
		            if (strlen($values[$key])< 5) {
		            	$eMsg .= '<p>'.ADD_USER_04.'</p>';
		          		$isError = true; 
		            }
		            if (!$isError) {
			            if (strcmp($values[$key], $pass_confirm) === 0) {
							//OK
			            } else { 
			            	$eMsg .= '<p>'.ADD_USER_05.'</p>';
			          		$isError = true; 
			            }
		            }
	            } else {
		          	$values[$key] = $user[$key];
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
	    
	  }
	
	  $tpl_adds_array['{address}'] = $g_addr;
	  
	  //////////////////////////////////////////////////////////
	  // fill array with language values (from language)
	  //////////////////////////////////////////////////////////

	  $tpl_adds_array['{lang title}'] = LANG_USER_ACCOUNT_EDIT;     
      $tpl_adds_array['{lang required}'] = LANG_USER_REQUIRED;     
	  $tpl_adds_array['{lang user}'] = LANG_USER_EDIT_NAME;     
	  $tpl_adds_array['{lang real_name}'] = LANG_USER_REAL_NAME;
	  $tpl_adds_array['{lang pass}'] = LANG_USER_PASS;
	  $tpl_adds_array['{lang pass_confirm}'] = LANG_USER_PASS_CONFIRM;
	  $tpl_adds_array['{lang new_pass}'] = LANG_USER_NEW_PASS;
	  $tpl_adds_array['{lang new_pass_confirm}'] = LANG_USER_NEW_PASS_CONFIRM;
	  $tpl_adds_array['{lang email}'] = LANG_USER_EMAIL;
	  $tpl_adds_array['{lang company}'] = LANG_USER_COMPANY;
	  $tpl_adds_array['{lang address}'] = LANG_USER_ADDR;
	  $tpl_adds_array['{lang city}'] = LANG_USER_CITY;
	  $tpl_adds_array['{lang state}'] = LANG_USER_STATE;
	  $tpl_adds_array['{lang zip}'] = LANG_USER_ZIP;
	  $tpl_adds_array['{lang country}'] = LANG_USER_COUNTRY;
	  $tpl_adds_array['{lang tel}'] = LANG_USER_TEL;
	  $tpl_adds_array['{lang fax}'] = LANG_USER_FAX;
	  $tpl_adds_array['{lang submit}'] = LANG_USER_UPDATE_BTN;
	  $tpl_adds_array['{lang cancel}'] = LANG_USER_CANCEL_BTN;
	  $tpl_adds_array['{change pass checkbox}'] = LANG_USER_CHANGE_PASS_CHECKBOX;

	  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  // filling form
	  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    
	  if(!isset($_POST['submit'])) {
	    
	  	// fill input values
	    foreach ($values as $key=>$value) {
	      $tpl_key = '{'.$key.' value}';
	      $tpl_adds_array[$tpl_key] = $user[$key];
	    }
        $country_value = $user['country'];
	    $tpl_adds_array['{pass value}'] = '';
	    $tpl_adds_array['{pass_confirm value}'] = '';
	    
		$tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_account_edit.tpl.php');  
	    
	  } else if (isset($_POST['submit']) and ($isError) ){
	  
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    // failure, show form and errors 
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
	    // fill input values
	    foreach ($values as $key=>$value) {
	      $tpl_key = '{'.$key.' value}';
	      $tpl_adds_array[$tpl_key] = $values[$key];
	    }
        $country_value = $values['country'];
	    
	    if (isset($_POST['change_pass'])) {
		    $tpl_adds_array['{pass_confirm value}'] = $pass_confirm;
		    $tpl_adds_array['{pass value}'] = $pass_confirm;
	    } else {
		    $tpl_adds_array['{pass value}'] = '';
		    $tpl_adds_array['{pass_confirm value}'] = '';
	    }
	    
	    if (strlen($eMsg)) {
	      $tpl_adds_array['{edit error}'] = '<div class="info">'.$eMsg.'</div>';
	    } else {
	      $tpl_adds_array['{edit error}'] = '';
	    }

		$tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_account_edit.tpl.php');  
	    
	  } else if (isset($_POST['submit']) and (!$isError) ){
	  
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  // edit passed, update user
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	  	
	    // update user    
	    $id = $g_users->UpdateUser($values,$user_id);
	        
	    if ($id>0) {
	      $tpl_adds_array['{message}'] = LANG_USER_ACCOUNT_EDIT_SUCCESS;
	    } else {
	      $tpl_adds_array['{message}'] = LANG_USER_ACCOUNT_FAILURE;
	    }    
	      
		$tmp_tpl->SetTemplate(DIR_TEMPLATE.'edit_message.tpl.php');  
	  }
	  
	  
  } else {
  	  $tpl_adds_array['{error}'] = USER_NOT_LOGGED_ERROR;
  	  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_error.tpl.php');  
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
