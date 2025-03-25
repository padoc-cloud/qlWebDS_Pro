<?php
  
  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();

  $isError = false;
  $eMsg = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';
  
  $tpl_adds_array['{claim error}'] = '';  
  $tpl_adds_array['{user message}'] = '';  
  
  $values = array('id_code'=>'','email'=>'');

  if ($g_user->Level() == AL_USER) {
  	
	  if ((isset($_POST['submit']))) {
	  
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  // check POST data  
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	      foreach($values as $key =>$value) {
	        switch ($key) {
	          case 'id_code':
	            $values[$key] = $_POST[$key];
	          	if (empty($values[$key])) {
	            	$eMsg .= '<p>'.CLAIM_LISTING_01.'</p>';
	          		$isError = true; 
	            }
	            break;
	            
	          case 'email':
	            $values[$key] = $_POST[$key];
	            if (!$g_users->CheckEmail($values[$key])) {
	              $eMsg .= '<p>'.ADD_USER_06.'</p>';
	              $isError = true;
	            }            
	            break;
	             
	      } // end switch
	      
	    } // end for
	    
	  }
	
	  $tpl_adds_array['{address}'] = $g_addr;
	  
	  //////////////////////////////////////////////////////////
	  // fill array with language values (from language)
	  //////////////////////////////////////////////////////////
	  $tpl_adds_array['{lang title}'] = CLAIM_LISTING;     
      $tpl_adds_array['{lang required}'] = LANG_USER_REQUIRED;     
	  $tpl_adds_array['{lang id_code}'] = CLAIM_LISTING_CODE;     
	  $tpl_adds_array['{lang email}'] = CLAIM_LISTING_EMAIL;
	  $tpl_adds_array['{lang submit}'] = CLAIM_LISTING_UPDATE_BTN;
	  $tpl_adds_array['{lang cancel}'] = CLAIM_LISTING_CANCEL_BTN;
	  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  // filling form
	  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    
	  if(!isset($_POST['submit'])) {
	    
	  	// fill input values
	    foreach ($values as $key=>$value) {
	      $tpl_key = '{'.$key.' value}';
	      $tpl_adds_array[$tpl_key] = $values[$key];
	    }
	    
		$tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_claim_listing.tpl.php');  
	    
	  } else if (isset($_POST['submit']) and ($isError) ){
	  
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    // failure, show form and errors 
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
	    // fill input values
	    foreach ($values as $key=>$value) {
	      $tpl_key = '{'.$key.' value}';
	      $tpl_adds_array[$tpl_key] = $values[$key];
	    }
	    
	    if (strlen($eMsg)) {
	      $tpl_adds_array['{claim error}'] = '<div class="info">'.$eMsg.'</div>';
	    } else {
	      $tpl_adds_array['{claim error}'] = '';
	    }

		$tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_claim_listing.tpl.php');  
	    
	  } else if (isset($_POST['submit']) and (!$isError) ){
	  
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  // edit passed, update user
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	  	
	    // Find listing    
	    $listing = $g_site->GetListingByID_CodeEmail($values['id_code'],$values['email']);
	        
	    if ($listing and empty($listing['user_id'])) {
		  $user = $g_user->GetUser();
		  $tmp_arr['user_id'] = $user['id'];
      	  $up_res = $g_site->UpdateSite($tmp_arr,$listing['id']);
      	  if ($up_res) {
		      $tpl_adds_array['{message}'] = CLAIM_LISTING_SUCCESS.': '.$listing['title'];
      	  } else {
		      $tpl_adds_array['{message}'] = CLAIM_LISTING_FAILURE;
      	  }
	    } else {
	      $tpl_adds_array['{message}'] = CLAIM_LISTING_NOT_FOUND;
	    }    
	      
		$tmp_tpl->SetTemplate(DIR_TEMPLATE.'edit_message.tpl.php');  
	  }
	  
	  
  } else {
  	  $tpl_adds_array['{error}'] = USER_NOT_LOGGED_ERROR;
  	  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_error.tpl.php');  
  }
	  
  $tmp_tpl->ReplaceIn($tpl_adds_array);   
  $tpl_main = $tmp_tpl->Get();      
        
?>
