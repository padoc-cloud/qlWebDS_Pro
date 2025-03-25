<?php
  
  if ($g_user->Level() == AL_ADMIN) {
      
    $site_id = (int) $_GET['id'];
  
    // link data 
    $site = $g_site->GetSiteById($site_id);

    $values = array('title'=>'', 'title1'=>'', 'title2'=>'', 'title3'=>'', 'title4'=>'', 'title5'=>'', 'url'=>'', 'url1'=>'',
					'url2'=>'', 'url3'=>'', 'url4'=>'', 'url5'=>'', 'email'=>'',
                    'company'=>'', 'product'=>'', 'address'=>'','city'=>'','state'=>'','zip'=>'','country'=>'', 'tel'=>'','fax'=>'', 'exp_date'=>'',
                    'description_short'=>'', 'description'=>'', 'facebook_url'=>'', 'twitter_url'=>'', 'youtube_url'=>'', 'embedded_video_title'=>'', 'embedded_video_code'=>'', 'reciprocal'=>'', 'keywords'=>'', 'link_type'=>'1', 'status'=>'');
    
    $isError = false;
    $eMsg = '';
    $notifications = '';
                        
    if (isset($_POST['submit'])) {
    
		// check POST data from step 2
        $link_type = (int) $_POST['link_type'];
        
        foreach($values as $key =>$value) {
          switch ($key) {
           
            case 'url':
		      if(strlen(trim($_POST[$key]))!=0) {
	            $values[$key] = $g_site->ParseURL(trim($_POST[$key]));
    	        if (!$values[$key]) {
        	      $eMsg .= '<p>'.ADD_SITE_02.' '.$key.'</p>';
            	  $isError = true;
	            }
		      }
              break;
              
            case 'title':
              $values[$key] = trim($_POST[$key]);
                
              // check title lenght
              $len = strlen($values[$key]);
              if ( ($len<2) or ($len>MAX_TITLE_LENGHT)) {
                $eMsg .= '<p>'.LANG_ERROR_TITLE_LENGHT.'</p>';
                $isError = true;                
              }
              break;

            case 'description_short':
              $values[$key] = trim($_POST[$key]);

              // check short description lenght
              $len = strlen($values[$key]);
              if ( ($len<2) or ($len>MAX_DESCR_SHORT_LENGHT)) {
                $eMsg .= '<p>'.LANG_ERROR_DESCR_SHORT_LENGHT.'</p>';
                $isError = true;                
              }
              break;
              
            case 'description':
              $values[$key] = trim($_POST[$key]);

              // check description lenght
              $len = strlen($values[$key]);
              if ( ($len<2) or ($len>MAX_DESCR_LENGHT)) {
                $eMsg .= '<p>'.LANG_ERROR_DESCR_LENGHT.'</p>';
                $isError = true;                
              }
              break;
                     
            case 'email':

              // check email
              $values['email'] = $g_site->CheckEmail($_POST['email']);
              if ($values['email']) {
              
              } else {
                $eMsg .= '<p>'.ADD_SITE_03.'.</p>';
                $isError = true;
              }
              break; 
            case 'company':
		      if(strlen(trim($_POST[$key]))==0) {
       	        $eMsg .= '<p>'.ADD_SITE_12.'</p>';
                $isError = true;
			    $values[$key] = false;
	    	  } else {
			    $values[$key] = trim($_POST[$key]);
	    	  }
              break;

            case 'tel':
              $values['tel_digits'] = preg_replace("/[^0-9]/", "", $_POST[$key]);
              $values[$key] = $_POST[$key];
              break;
              
            case 'facebook_url':
            case 'twitter_url':
            case 'youtube_url':
            case 'embedded_video_title':
            case 'embedded_video_code':
            case 'product':
            case 'address':
            case 'city':
            case 'state':
            case 'zip':
            case 'country':
            case 'fax':
              $values[$key] = $_POST[$key];
              break;
              
            case 'exp_date':
            	if (substr($_POST[$key],2,1) === '-') {
	            	$values[$key] = substr($_POST[$key],6,4).'-'.substr($_POST[$key],0,2).'-'.substr($_POST[$key],3,2);
            	} else {
            		$values[$key] = '0000-00-00';
            	}
            	break;
              
            case 'link_type':
              $values[$key] = (int) $_POST[$key];
              $i = $values[$key];

              // check if checked payment is avaiable
              if (!isset($g_payment["ctype[$i]"])) {
                $eMsg .= '<p>'.ADD_SITE_06.'.</p>';            
                $isError = true;
              }
              break;
            
            case 'status':
              if (isset($_POST[$key])) {  
	              $values['status'] = SITE_VIEW;
              } else {
			      $site_before = $g_site->GetSiteById($site_id);
			      $values['status'] = $site_before['status'];
              }
              break;    
                                 
            default:
             if (isset($_POST[$key]) ) {
              $values[$key] = trim($_POST[$key]);
             }
             break;
             
        } // end switch  
        
      }
    }
    
    if (isset($_POST['submit']) and ($isError) ){

	  // failure step 2
      // fill input values
      foreach ($values as $key=>$value) {
        $tpl_key = '{'.$key.' value}';
        $tpl_adds_array[$tpl_key] = $values[$key];
        if($key == 'exp_date') {
        	// YYYY-MM-DD to MM-DD-YYYY
        	$tpl_adds_array[$tpl_key] = substr($values[$key],5,3).substr($values[$key],8,2).'-'.substr($values[$key],0,4); 
        }
      }
      $country_value = $values['country'];     
      $tpl_adds_array['{error}'] = '<div class="info">'.$eMsg.'</div>'; 
            
    // set step 2 template
    } else if (isset($_POST['submit']) and (!$isError) ){

	  // successfull step 2, save site
      $values['link_type'] = $link_type;
      $values['featured'] = $g_featured[$link_type];
      $values['date'] = date(DATETIME_FORMAT);
      $values['last_checked'] = date(DATETIME_FORMAT);

      // other stuff      
      /*
      $amount = $g_payment['type['.$link_type.']'];
      if ($amount>0) {
        $values['paid_link'] = 1;
      } else {
        $values['paid_link'] = 0;
      }
      $values['last_payment'] = '';
      */
      
      $site_before = $g_site->GetSiteById($site_id);
      $status_before = $site_before['status'];
      $status_now = $values['status'];
      
      // add site
      $id = $g_site->UpdateSite($values, $site_id);
      
      $notifications = '<div class="info">'.LANG_SAVED.'</div>';
      // header('Location: index.php?mod=links&inc=approve_links');
      
      // link data 
      $site = $g_site->GetSiteById($site_id);

     // send email if site approved
      if (($status_now == SITE_VIEW) and ($status_before <> SITE_VIEW)) {
              $g_site->SendEmail($g_params->GetParams('site_approved_email'), $site );
      }
       
    } 

		// change categories
        $aCategories = array();
         
        // save new category
        if (isset($_POST['change'])) {
          $x = each($_POST['change']);
          $g_site->DeleteConnection($site_id, $x['key']);
          $g_site->AddConnection($site_id, $_POST['new_category']);
        }

        // all categories combo
        $cForm = new FormClass();
        
        $aCateg = $g_categ->GetAllCategories();
          
        foreach ($aCateg as $item) {
            
          $q = '';
          if ($item['level']==2) {
            $q = ' - ';
          } else if ($item['level']==3) {
            $q = ' &nbsp;&nbsp; * ';
          }
          
          $aOptions[] = array('value'=>$item['id'], 'name'=>$q.$item['name']);  
        }
        
        // assign combo to form       
        $cForm->SetCombo('new_category', $aOptions, 'value', 'name');
    
        // site categories
        $categories = $g_categ->GetSiteCategories($site['id']);
        $categories_in = '';
        $i = 0;
        
        foreach ($categories as $category) {
        
          // if (MOD_REWRITE) {
          // $categ_address = CATALOG_ADDRESS.str_replace(',',WORD_SEPARATOR,$category['mod_rewrite']).WORD_SEPARATOR.MOD_CATEG.'-'.$category['sub_id'].PAGE_EXTENSION;
          // } else {
           	 $categ_address = CATALOG_ADDRESS.'index.php?'.$category['mod_rewrite'].'&amp;categ='.$category['sub_id'];
          // }
          
          $aCategories[$i]['{category}'] = ' - <a href="'.$categ_address.'" target="_blank">'.$category['name'].'</a>';
          
          $aCategories[$i]['{id}'] = $category['id'];
          
          $form_array = $cForm->MakeForm(array('CBO|new_category'), array('new_category'=>$category['id']) );
          $aCategories[$i]['{new_category}'] = $form_array['{new_category}'];
                              
          $i++;
          
        }

	// fill form
    if (!$isError) {
        foreach ($values as $key=>$value) {
          $tpl_key = '{'.$key.' value}';
          $tpl_adds_array[$tpl_key] = $site[$key];
	      if($key == 'exp_date') {
	        // YYYY-MM-DD to MM-DD-YYYY
	        $tpl_adds_array[$tpl_key] = substr($site[$key],5,3).substr($site[$key],8,2).'-'.substr($site[$key],0,4); 
	      }
        }
        $country_value = $site['country'];     
      
    }

    $tpl_adds_array['{original_description value}'] = $site['original_description'];
    $tpl_adds_array['{note value}'] = $site['note'];
      

      if (!$isError) {
        if ($site['link_type'] == LT_ADD3 or $site['link_type'] == LT_ADD5) {
          $tpl_adds_array['{display_links3}'] = 'style="display: ;"';
          
        } else {
          $tpl_adds_array['{display_links3}'] = 'style="display: none;"';
          $tpl_adds_array['{display_links5}'] = 'style="display: none;"';
        }
        
        if ($site['link_type'] == LT_ADD5) {
          $tpl_adds_array['{display_links5}'] = 'style="display: ;"';
        }    
      }
      
	  // payment
      $payment = $g_site->GetLastPayment($site_id); 
      
      $tmpIf['paid_link'] = 1;
      
      if ($payment == false) {
        
        $tpl_adds_array['{amount}'] = 0;
        $tpl_adds_array['{currency}'] = CURRENCY;
        $tpl_adds_array['{subscription}'] = 'No';
        $tpl_adds_array['{paid}'] = 'No';
        
      } else {
        $tpl_adds_array['{amount}'] = $payment['amount'];
        $tpl_adds_array['{currency}'] = $payment['currency'];      
        
        if ($payment['payment_period']==0) {
          $tpl_adds_array['{subscription}'] = 'No';
        } else {
          $tpl_adds_array['{subscription}'] = $payment['payment_period'].' months';
        }
            
        if ($payment['paid']==PM_PAID) { 
          $tpl_adds_array['{paid}'] = 'Yes'; 
        } else { 
          $tpl_adds_array['{paid}'] = 'No'; 
        }      
      }
      
	  // links types
      $tpl_adds_array['{display_links5}'] = 'style="display: ;"';
      $tpl_adds_array['{display_links3}'] = 'style="display: ;"';
                      
      // check active link types
      $k = 0;
      for ($i=1; $i<=LINK_TYPES; $i++) {
      
        if (!isset($g_payment["ctype[$i]"]) ) { // ctype[] - checkbox  if active link type
        
          $tpl_adds_array["{style type[$i]}"] = 'style="display: none;"'; 
          
        } else {
          
          if ($k==1) {
            $tpl_adds_array["{style type[$i]}"] = 'class="colored"';
          } else {
            $tpl_adds_array["{style type[$i]}"] = '';
          }
          $k = 1 - $k;
        }
      }

	  // fill language template
      $tpl_adds_array['{link type1}'] = LINK_TYPE1;
      $tpl_adds_array['{link type2}'] = LINK_TYPE2;
      $tpl_adds_array['{link type3}'] = LINK_TYPE3;
      $tpl_adds_array['{link type4}'] = LINK_TYPE4;
      $tpl_adds_array['{link type5}'] = LINK_TYPE5;
      $tpl_adds_array['{link type6}'] = LINK_TYPE6;
      $tpl_adds_array['{link type7}'] = LINK_TYPE7;
      $tpl_adds_array['{link type8}'] = LINK_TYPE8;
      $tpl_adds_array['{link type9}'] = LINK_TYPE9;

      $tpl_adds_array['{deep_link_1}'] = LANG_DEEP_LINK_1;
      $tpl_adds_array['{deep_link_2}'] = LANG_DEEP_LINK_2;
      $tpl_adds_array['{deep_link_3}'] = LANG_DEEP_LINK_3;
      $tpl_adds_array['{deep_link_4}'] = LANG_DEEP_LINK_4;
      $tpl_adds_array['{deep_link_5}'] = LANG_DEEP_LINK_5;

      $tpl_adds_array['{edit}'] = LANG_EDIT;
      $tpl_adds_array['{accept}'] = LANG_ADD; 
      $tpl_adds_array['{add site}'] = LANG_ADD_SITE;     
      $tpl_adds_array['{title}'] = LANG_TITLE;
      $tpl_adds_array['{url}'] = LANG_URL;
      $tpl_adds_array['{description}'] = LANG_DESCR;
      $tpl_adds_array['{description_short}'] = LANG_DESCR_SHORT;
      $tpl_adds_array['{facebook_url}'] = LANG_FACEBOOK_URL;
      $tpl_adds_array['{twitter_url}'] = LANG_TWITTER_URL;
      $tpl_adds_array['{youtube_url}'] = LANG_YOUTUBE_URL;
      $tpl_adds_array['{embedded_video_title}'] = LANG_EMBEDDED_VIDEO_TITLE;
      $tpl_adds_array['{embedded_video_code}'] = LANG_EMBEDDED_VIDEO_CODE;
      $tpl_adds_array['{original_description}'] = LANG_ORIGINAL_DESCR;
      $tpl_adds_array['{keywords}'] = LANG_KEYW;        
      $tpl_adds_array['{email}'] = LANG_EMAIL;
	  $tpl_adds_array['{lang company}'] = LANG_COMPANY;
	  $tpl_adds_array['{lang comp_name}'] = LANG_COMP_NAME;
	  $tpl_adds_array['{lang product}'] = LANG_PRODUCT;
	  $tpl_adds_array['{lang address}'] = LANG_ADDR;
	  $tpl_adds_array['{lang city}'] = LANG_CITY;
	  $tpl_adds_array['{lang state}'] = LANG_STATE;
	  $tpl_adds_array['{lang zip}'] = LANG_ZIP;
	  $tpl_adds_array['{lang country}'] = LANG_COUNTRY;
	  $tpl_adds_array['{lang tel}'] = LANG_TEL;
	  $tpl_adds_array['{lang fax}'] = LANG_FAX;
	  $tpl_adds_array['{lang exp_date}'] = LANG_EXP_DATE;
	  $tpl_adds_array['{reciprocal}'] = LANG_RECIPROCAL;
      $tpl_adds_array['{note}'] = LANG_NOTE_FROM_USER;     
      $tpl_adds_array['{save}'] = LANG_SAVE;
      $tpl_adds_array['{cancel}'] = LANG_CANCEL;      
      $tpl_adds_array['{back}'] = LANG_BACK;
      
      // payment lang
      $tpl_adds_array['{lang amount}'] = LANG_AMOUNT;
      $tpl_adds_array['{lang subscription}'] = LANG_SUBSCR;
      $tpl_adds_array['{lang paid}'] = LANG_IS_PAID;
      $tpl_adds_array['{lang payment}'] = LANG_PAYMENT;

      $tpl_adds_array['{notifications}'] = $notifications;
      
      if ($isError) {
        $tpl_adds_array['{error}'] = '<div class="info">'.$eMsg.'</div>';    
      } else { $tpl_adds_array['{error}'] = ''; }
  
    // active checkbox
    if ($site['status'] == SITE_VIEW) {
      $tpl_adds_array['{active}'] = ' checked ';
    } else {
      $tpl_adds_array['{active}'] = '';
    }
      
	  // link type check
      $tpl_adds_array['{ch1}'] = '';
      $tpl_adds_array['{ch2}'] = '';
      $tpl_adds_array['{ch3}'] = '';
      $tpl_adds_array['{ch4}'] = '';
      $tpl_adds_array['{ch5}'] = '';
      $tpl_adds_array['{ch6}'] = '';
      $tpl_adds_array['{ch7}'] = '';
      $tpl_adds_array['{ch8}'] = '';
      $tpl_adds_array['{ch9}'] = '';
                        
      $tpl_adds_array['{ch'.$site['link_type'].'}'] = 'checked';
    
    $tpl_adds_array['{disp3}'] = 'none';
    $tpl_adds_array['{disp5}'] = 'none';
    if (LT_ADD3 == $site['link_type']) { $tpl_adds_array['{disp3}'] = ''; }
    if (LT_ADD5 == $site['link_type']) { $tpl_adds_array['{disp3}'] = ''; $tpl_adds_array['{disp5}'] = ''; }
    

    // countries dropdown logic
    $coForm = new FormClass();
    $coform_fields = array('CBO|country');   
    $coForm->SetCombo('country', $g_countries, 'value', 'name');
    $country_array = array('country'=>$country_value);
    $coform_array = $coForm->MakeForm($coform_fields, $country_array);
    $tpl_adds_array['{country}'] = $coform_array['{country}'];
    
	// set template
    $g_template->SetTemplate(ADMIN_TEMPLATE_DIR.'edit_site.html');  
    $g_template->FillRowsV2('row change_categ', $aCategories);    
    $g_template->ReplaceIn($tpl_adds_array);
    $g_template->IfRegion($tmpIf);      
  
    $tpl_main['{content}'] = $g_template->Get();  
   
  }  else {
       $tpl_main['{content}'] = ERROR_002;
  }
  
?>
