<?php

  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();

  if ($g_user->Level() == AL_USER) {
  	
      $sites = array();
  	
  	  //////////////////////////////////////////////////////////
	  // fill array with language values (from language)
	  //////////////////////////////////////////////////////////
	  
	  $tpl_adds_array['{lang title}'] = LANG_ACCT_TITLE;     
	  $tpl_adds_array['{lang user section}'] = LANG_ACCT_USER_SECTION;
	  $tpl_adds_array['{lang user}'] = LANG_ACCT_USER;
	  $tpl_adds_array['{lang account edit}'] = LANG_ACCT_EDIT;
	  $tpl_adds_array['{lang claim listing}'] = LANG_CLAIM_LISTING;
	  $tpl_adds_array['{lang listing section}'] = LANG_ACCT_LISTING_SECTION;
	  
	  $tpl_adds_array['{lang listing date}'] = LANG_ACCT_LISTING_DATE;
	  $tpl_adds_array['{lang listing URL}'] = LANG_ACCT_LISTING_URL;
	  $tpl_adds_array['{lang listing company}'] = LANG_ACCT_LISTING_COMPANY;
	  $tpl_adds_array['{lang listing status}'] = LANG_ACCT_LISTING_STATUS;
	  $tpl_adds_array['{lang listing action}'] = LANG_ACCT_LISTING_ACTION;
      $tpl_adds_array['{lang listing edit}'] = LANG_ACCT_LISTING_EDIT;

	  $user = $g_user->GetUser();
	  $tpl_adds_array['{user value}'] = $user['user'];

      // header sort
      $tpl_adds_array['{dsort}'] = 'desc';
      $tpl_adds_array['{usort}'] = 'desc';
      $tpl_adds_array['{usort}'] = 'desc';
      $tpl_adds_array['{csort}'] = 'desc';
      $tpl_adds_array['{ssort}'] = 'desc';
      $ord = '';
      $sort = 'id';
      if (isset($_GET['ord']) and isset($_GET['sort']) ) {
        $ord = $_GET['ord'];
        $sort = $_GET['sort'];
        switch ($ord) {
          case 'date':
            if ($sort=='desc') {
              $tpl_adds_array['{dsort}'] = 'asc';
            }    
            break;
            
          case 'url':
            if ($sort=='desc') {
              $tpl_adds_array['{usort}'] = 'asc';
            }    
            break;  
            
          case 'company':
            if ($sort=='desc') {
              $tpl_adds_array['{csort}'] = 'asc';
            }    
            break;                            

          case 'status':
            if ($sort=='desc') {
              $tpl_adds_array['{ssort}'] = 'asc';
            }    
            break;
        }
      }
	  
	  // get listings
	  $query = "SELECT * FROM $g_site->m_table WHERE user_id=".$user['id']." AND STATUS IN (";
	  $query .= SITE_WAITING.",".SITE_BANNED.",".SITE_VIEW.") ORDER BY ".$ord." ".$sort;
      $table = $DB->GetTable($query);
      
      $i=0;
      foreach ($table as $site ){

        $sites[$i]['{id}'] = $site['id'];
        $sites[$i]['{date}'] = substr($site['date'],0,10);
        $sites[$i]['{url}'] = $site['url'];
        $sites[$i]['{company}'] = $site['company'];
        switch ($site['status']) {
          case SITE_WAITING:
        	$status = LANG_ACCT_LISTING_PENDING;
          	break;
            
          case SITE_BANNED:
        	$status = LANG_ACCT_LISTING_INACTIVE;
          	break;
            
          case SITE_VIEW:
        	$status = LANG_ACCT_LISTING_ACTIVE;
          	break;
        }
        $sites[$i]['{status}'] = $status;
                                                
        $i++;
      }
      
   	  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_account.tpl.php');  
	  $tmp_tpl->ReplaceIn($tpl_adds_array);
	  $tmp_tpl->FillRowsV2('row sites', $sites);
	  
  } else {
  	$tpl_adds_array['{error}'] = USER_NOT_LOGGED_ERROR;
  	$tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_error.tpl.php');  
	$tmp_tpl->ReplaceIn($tpl_adds_array);   
  }
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // filling form
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $tpl_main = $tmp_tpl->Get();      
  
?>
