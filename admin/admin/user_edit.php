<?php

  if ($g_user->Level() == AL_ADMIN) {
  
    $isError = false;
    $eMsg = '';
    $notifications = '';
    $aValues = array();
    
    $template = ADMIN_TEMPLATE_DIR.'admin_user_edit.html';
      
    $tmpErrors = array();
      
    $form = array('real_name','email','company',
    			  'address','city','state','zip','country',
    			  'tel','fax','status');

    foreach ($form as $key) {
    	$aValues[$key] = '';	
    }
    
    if (isset($_GET['user_id']) ) {
       $user_id = (int) $_GET['user_id'];
    } else {  
	   if (isset($_POST['p_user_id']) ) {
	   		$user_id = (int) $_POST['p_user_id'];
	   }
    }

    $user = $g_users->GetUser($user_id);

    // template 
    $site_tpl['{p_user_id}'] = $user_id;
    $site_tpl['{user}'] = $user['user'];
    
    
    if (isset($_POST['submit'])) {

	  // edits
      foreach ($form as $key) {
      	switch ($key) {
      		case 'real_name':
        		if (isset($_POST[$key]) and !empty($_POST[$key])) {
                	$aValues[$key] = $_POST[$key];
        		} else {
                	$eMsg .= "'Real Name' field is required<br>";           
                	$isError = true; 
        		}
             	break;                
             	
      		case 'email':
        		if (isset($_POST[$key]) and !empty($_POST[$key])) {
	      			if ($g_site->CheckEmail($_POST[$key])) {
	                	$aValues[$key] = $_POST[$key];
	              	} else {
	                	$eMsg .= "'Email' field: incorrect email address<br>";           
	                	$isError = true; 
	              	}
        		} else {
                	$eMsg .= "'Email' field is required<br>";           
                	$isError = true; 
        		}
             	break;
             	
            default:
               	$aValues[$key] = $_POST[$key];
             	break;                
      	}
      	
      	if ($key == 'status') {
      		if ($_POST[$key] == 1) {
      			$site_tpl['{stat_act_selected}'] = 'selected';
      		} else {
      			$site_tpl['{stat_inact_selected}'] = 'selected';
      		}
      	} else {
	      	$site_tpl['{'.$key.'}'] = $_POST[$key];
      	}
      	if ($key == 'country') {
	      $country_value = $_POST[$key];
      	}
      		
      } // foreach
      
      // check for errors
      if (!$isError and $g_users->UpdateUser($aValues, $user_id) !==false) {
         $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
      } else {
         $notifications = '<div class="info">'.$eMsg.'</div>';
      }    
    
    } else {
      foreach ($form as $key) {
      	if ($key == 'status') {
      		if ($user[$key] == 1) {
      			$site_tpl['{stat_act_selected}'] = 'selected';
      		} else {
      			$site_tpl['{stat_inact_selected}'] = 'selected';
      		}
      	}
      	$site_tpl['{'.$key.'}'] = $user[$key];	
      }
      $country_value = $user['country'];
      
    }
                 
    $site_tpl['{notifications}'] = $notifications;
    $site_tpl['{lang back}'] = LANG_BACK;  
    
    $g_template->SetTemplate($template);

    // countries dropdown logic
    $coForm = new FormClass();
    $coform_fields = array('CBO|country');   
    $coForm->SetCombo('country', $g_countries, 'value', 'name');
    $country_array = array('country'=>$country_value);
    $coform_array = $coForm->MakeForm($coform_fields, $country_array);
    $site_tpl['{country}'] = $coform_array['{country}'];
    
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
  }
 
?>
