<?php  
  
  if ($g_user->Level() == AL_ADMIN) {
  
    $isError = false;
    $eMsg = '';
    $notifications = '';
    
    $email_list = '';
	$email_list_array = array();
	    
    $template = ADMIN_TEMPLATE_DIR.'admin_emailer.html';
      
    $aValues = array();
      
    $site_tpl['{from}'] = '';
    $site_tpl['{subject}'] = '';
    $site_tpl['{email_body}'] = '';
    $site_tpl['{address_list}'] = '';
    $site_tpl['{start_id}'] = '';
    $site_tpl['{end_id}'] = '';
    
    $site_tpl['{checked_list_manual}'] = 'checked';
    $site_tpl['{checked_list_all}'] = '';
    $site_tpl['{checked_list_active}'] = '';
    $site_tpl['{checked_list_inctive}'] = '';

    $site_tpl['{checked_num_all}'] = 'checked';
    $site_tpl['{checked_num_part}'] = '';
    
    // Number of Listings in the Directory
	$query = "SELECT COUNT(id) as hmany FROM $g_site->m_table s WHERE status=".SITE_VIEW;
	$row = $DB->GetRow($query);
	$hmany_active = $row['hmany'];

	$query = "SELECT COUNT(id) as hmany FROM $g_site->m_table s WHERE status=".SITE_BANNED;
	$row = $DB->GetRow($query);
	$hmany_inactive = $row['hmany'];
	
	$hmany_all = $hmany_active + $hmany_inactive;
	
    $site_tpl['{listings_total}'] = $hmany_all;
    $site_tpl['{listings_active}'] = $hmany_active;
    $site_tpl['{listings_inactive}'] = $hmany_inactive;
      
    // save settings
    if (isset($_POST['submit'])) {

      $form = array('from','subject','to','email_number','email_body');
      
	  // edits
      foreach ($form as $key) {
      	switch ($key) {
      		case 'from':
        		if (isset($_POST[$key]) and !empty($_POST[$key])) {
	      			if ($g_site->CheckEmail($_POST[$key])) {
	                	$aValues[$key] = $_POST[$key];
	              	} else {
	                	$eMsg .= "'From' field: incorrect email address<br>";           
	                	$isError = true; 
	              	}
        		} else {
                	$eMsg .= "'From' field is required<br>";           
                	$isError = true; 
        		}
              	$site_tpl['{'.$key.'}'] = $_POST[$key];
             	break;

      		case 'subject':
        		if (isset($_POST[$key]) and !empty($_POST[$key])) {
                	$aValues[$key] = $_POST[$key];
        		} else {
                	$eMsg .= "'Subject' field is required<br>";           
                	$isError = true; 
        		}
              	$site_tpl['{'.$key.'}'] = $_POST[$key];
             	break;                
             	
      		case 'to':
        		if (isset($_POST[$key])) {
                	$aValues[$key] = $_POST[$key];
                	if ($aValues[$key] == 'list_manual') {
		        		if (isset($_POST['address_list']) and !empty($_POST['address_list'])) {
							$email_list = str_replace(' ','',$_POST['address_list']);
							$email_list = str_replace(';',',',$email_list);
							$email_list = rtrim($email_list,',');							
							$email_list_array = explode(',',$email_list);  		        			
							foreach ($email_list_array as $email_addr) {
	      						if (!$g_site->CheckEmail($email_addr)) {
				                	$eMsg .= "'To' field: this is not a valid email address: ".$email_addr."<br>";           
				                	$isError = true; 
	      						}
							}
              				$site_tpl['{address_list}'] = $_POST['address_list'];
		        		} else {
		                	$eMsg .= "A list of email addresses is required with this 'To' field selection<br>";           
		                	$isError = true; 
              				$site_tpl['{address_list}'] = '';
		        		}
                		
                	}
				    $site_tpl['{checked_list_manual}'] = '';
				    $site_tpl['{checked_list_all}'] = '';
				    $site_tpl['{checked_list_active}'] = '';
				    $site_tpl['{checked_list_inctive}'] = '';
	        		$site_tpl['{checked_'.$aValues[$key].'}'] = 'checked';
        		} else {
                	$eMsg .= "Please select a radio button for 'To' field<br>";           
                	$isError = true; 
        		}
             	break;

      		case 'email_number':
        		if (isset($_POST[$key])) {
                	$aValues[$key] = $_POST[$key];
                	if($aValues[$key] == 'num_part') {
                		$start_id = false;
                		$end_id = false;
		        		if (isset($_POST['start_id']) and !empty($_POST['start_id'])) {
							$start_id = $_POST['start_id'];
              				$site_tpl['{start_id}'] = $_POST['start_id'];
		        		} else {
		                	$eMsg .= "Please specify 'Start ID' value for 'Number of Emails to Be Sent' field<br>";           
		                	$isError = true; 
              				$site_tpl['{start_id}'] = '';
		        		}
		        		if (isset($_POST['end_id']) and !empty($_POST['end_id'])) {
							$end_id = $_POST['end_id'];
              				$site_tpl['{end_id}'] = $_POST['end_id'];
		        		} else {
		                	$eMsg .= "Please specify 'End ID' value for 'Number of Emails to Be Sent' field<br>";           
		                	$isError = true; 
              				$site_tpl['{end_id}'] = '';
		        		}
		        		if ($start_id and $end_id) {
		        			if ($start_id > $end_id) {
			                	$eMsg .= "'Start ID' cannot be greater than 'End ID' in 'Number of Emails to Be Sent' field<br>";           
			                	$isError = true; 
		        			}
		        		}
                	}
				    $site_tpl['{checked_num_all}'] = '';
				    $site_tpl['{checked_num_part}'] = '';
	        		$site_tpl['{checked_'.$aValues[$key].'}'] = 'checked';
        		} else {
                	$eMsg .= "Please select a radio button for 'Number of Emails to Be Sent' field<br>";           
                	$isError = true; 
        		}
             	break;
             	
      		case 'email_body':
        		if (isset($_POST[$key]) and !empty($_POST[$key])) {
                	$aValues[$key] = $_POST[$key];
        		} else {
                	$eMsg .= "'Email Body' field is required<br>";           
                	$isError = true; 
        		}
              	$site_tpl['{'.$key.'}'] = $_POST[$key];
             	break;                
             	
      	}
        
      } // foreach
      
      // check for errors
      if (!$isError) {
         
         
         $email_action_to = '';
         if ($aValues['to'] == 'list_manual') {
         	// do nothing; email list array already created 
         	$email_action_to .= 'Manually Entered Addresses<br>';
         } else {

         	$q_range = '';
         	$email_action_range = '';
	        if ($aValues['email_number'] == 'num_part') {
	         	$q_range = " AND ID >= ".$start_id." AND ID <=".$end_id;
	         	$email_action_range = ' From Start ID = '.$start_id.' To End ID = '.$end_id;
	        }
         	 
	        if ($aValues['to'] == 'list_all') {
	        	$listing_status = SITE_VIEW.','.SITE_ENDED_CHARGES.','.SITE_BANNED;
         		$email_action_to .= 'Active and Inactive Listings'.$email_action_range.'<br>';
	        }
	         
	        if ($aValues['to'] == 'list_active') {
	        	$listing_status = SITE_VIEW.','.SITE_ENDED_CHARGES;
         		$email_action_to .= 'Active Listings<'.$email_action_range.'br>';
	        }
	        
	        if ($aValues['to'] == 'list_inactive') {
	        	$listing_status = SITE_BANNED;
         		$email_action_to .= 'Inactive Listings'.$email_action_range.'<br>';
	        }
	        $q_where = ' WHERE STATUS in ('.$listing_status.')'.$q_range;
	        
			$query = "SELECT email FROM $g_site->m_table s ".$q_where." ORDER BY id ASC";

			$table = $DB->GetTable($query);
			
			foreach ($table as $email_row) {
				$email_list .= $email_row['email'].",";
			}
			$email_list = rtrim($email_list,',');
			$email_list_array = array();
			if (!empty($email_list)) {
				$email_list_array = explode(',',$email_list);
			}
			  		        			
         }
         
         $notifications = '<div class="info">';
         
         if (count($email_list_array) == 0) {
	         $notifications .= 'No E-mails Sent';
         } else {
			$header = 'From: <'.$aValues['from'].'>' . "\r\n";
			$header .=  'Reply-To: <'.$aValues['from'].'>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
			$header .= 'MIME-Version: 1.0' . "\r\n";
			$header .= 'Content-type: text/html; charset='.DEFAULT_CHARSET."\r\n";

			foreach ($email_list_array as $email_address) {
				$e_sent = mail($email_address, $aValues['subject'], $aValues['email_body'], $header);
			}
			
         	if (count($email_list_array) == 1) {
         		$notifications .= '1 Email Sent';
         	} else {
         		$notifications .= count($email_list_array).' E-mails Sent';
         	}
         	
         	$email_action_from = '<b>Sent From:</b> '.$aValues['from'].'<br>'; 
         	$email_action_subject = '<b>Email Subject:</b> '.$aValues['subject'].'<br>';
         	$email_action_to = '<b>Sent To:</b> '.$email_action_to; 
         	$email_action_sent = '<b>E-mails Sent:</b> '.count($email_list_array).'<br>';
         	$email_action_date = '<b>Date/Time:</b> '.date(DATETIME_FORMAT);
         	         	
         	$email_action = $email_action_from.$email_action_subject.$email_action_to.$email_action_sent.$email_action_date;

         	$emailer_params = array();
         	$emailer_params['last_email_action'] = $email_action;
         	$g_params->UpdateParams('emailer',$emailer_params);
         }
         
         $notifications .= '</div>';
      } else {
         $notifications = '<div class="info">'.$eMsg.'</div>';
      }    
                 
    } // isset post submit
    

    // Last E-mail Action
    $p = $g_params->GetParams('emailer');
    if ($p) {
	    $site_tpl['{last_action}'] = $p['last_email_action'];
    } else {
	    $site_tpl['{last_action}'] = 'No Action';
    }
      
    $site_tpl['{notifications}'] = $notifications;
      
    $g_template->SetTemplate($template);  
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();
          
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }
 
?>
