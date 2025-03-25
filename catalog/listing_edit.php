<?php
  
  $tmp_tpl = new TemplateClass(); 
  $tpl_adds_array = array();

  $isError = false;
  $eMsg = '<font color="#CC3300"><u>Please CORRECT the following Errors and Re-submit:</u></font>';
  
  $link_type = 0;
  
  $if_region['ispayment'] = 0;
  $if_region['site_new'] = 0;
  $if_region['site_old_added'] = 0;
    
  $tpl_adds_array['{error}'] = ''; 

  $listing_upgrade = false;
  
  $values = array('title'=>'','title1'=>'','title2'=>'','title3'=>'',
                  'title4'=>'','title5'=>'','url'=>'','url1'=>'',
                  'url2'=>'','url3'=>'','url4'=>'','url5'=>'','email'=>'',
                  'company'=>'','product'=>'', 
                  'address'=>'','city'=>'','state'=>'','zip'=>'','country'=>'',
                  'tel'=>'','fax'=>'',
                  'description'=>'','reciprocal'=>'','keywords'=>'','link_type'=>'1','note'=>'');

  if ($g_user->Level() == AL_USER) {
  
  	  $user = $g_user->GetUser();
  	
	  $listing_id = (int) $_GET['id'];
	  $listing = $g_site->GetSiteById($listing_id);

      $categories = $g_categ->GetSiteCategories($listing_id);
      
      $last_payment_amount = 0;
      $last_payment = $g_site->GetLastPayment($listing_id);
      if ($last_payment) {
      	$last_payment_amount = $last_payment['amount'];
      }
      
      $in_top_categ = false;
      foreach ($categories as $category) {
      	if ($category['level'] == 1) {
	      $in_top_categ = true;
      	}
      }
      	  
      $link_type = $listing['link_type'];
      
      // allow upgrade to featured listing if listing is:
      // listing is not pending
      // listing is regular
      // there is no subscription     
      if (($listing['status'] == SITE_VIEW) and 
      	  (($link_type == LT_FREE) or ($link_type == LT_NORMAL) or ($link_type == LT_RECIP)) and 
      	  ($g_params->Get('payment', 'payment_period') == 0)) {
      	  	
      	  $listing_upgrade = true;
      }
	  
	  if ($listing_upgrade) {
		  $tpl_adds_array["{payment style}"] = '';
	  } else {
		  $tpl_adds_array["{payment style}"] = 'style="display: none;"';
	  }
      
      if ($user['id'] == $listing['user_id']) {
		  
	  	if (isset($_POST['submit'])) {
		  
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// check POST data  
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		      if (isset($_POST['link_type'])) {
		         $link_type = (int) $_POST['link_type'];
		      } 
		            
		      foreach($values as $key =>$value) {
		        switch ($key) {
		          
		          case 'url1':
		          case 'url2':
		          case 'url3':
		            if (($link_type === LT_ADD5) || ($link_type === LT_ADD3)) {
		              $values[$key] = $g_site->ParseURL($_POST[$key]);
		              if (!$values[$key]) {
		                // $eMsg .= '<p>'.ADD_SITE_02.' '.$key.'</p>';
		                // $isError = true;
		              }               
		            } else { $values[$key] = ''; }  
		            break;     
		                  
		          case 'title1':
		          case 'title2':
		          case 'title3':
		            if (($link_type === LT_ADD5) || ($link_type === LT_ADD3)) {
		              $values[$key] = trim($_POST[$key]);
		              
		              // check title lenght
		              $len = strlen($values[$key]);
		              if ( ($len<3) or ($len>MAX_TITLE_LENGHT)) {
		               // $eMsg .= '<p>'.LANG_ERROR_TITLE_LENGHT.' '.$key.'</p>';
		               // $isError = true;                
		              }
		            } else { $values[$key] = ''; }
		            break;
		            
		          case 'url4':
		          case 'url5':
		            if ($link_type === LT_ADD5) {
		              $values[$key] = $g_site->ParseURL(trim($_POST[$key]));
		              if (!$values[$key]) {
		                // $eMsg .= '<p>'.ADD_SITE_02.' '.$key.'</p>';
		                // $isError = true;
		              }               
		            } else { $values[$key]=''; }
		            break;    
		                            
		          case 'title4':
		          case 'title5':
		            if ($link_type === LT_ADD5) {
		              $values[$key] = trim($_POST[$key]);
		              
		              // check title lenght
		              $len = strlen($values[$key]);
		              if ( ($len<3) or ($len>MAX_TITLE_LENGHT)) {
		              // $eMsg .= '<p>'.LANG_ERROR_TITLE_LENGHT.' '.$key.'</p>';
		              // $isError = true;                
		              }              
		            } else { $values[$key] = ''; }    
		            break;
		                      
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
		            if ( ($len<3) or ($len>MAX_TITLE_LENGHT)) {
		              $eMsg .= '<p>'.LANG_ERROR_TITLE_LENGHT.' '.$key.'</p>';
		              $isError = true;                
		            }              
		            break;

		          case 'description_short':
		            $values[$key] = trim($_POST[$key]);
		              
		            // check short description lenght
		            $len = strlen($values[$key]);
		            if ( ($len<MIN_DESCR_SHORT_LENGHT) or ($len>MAX_DESCR_SHORT_LENGHT)) {
		              $eMsg .= '<p>'.LANG_ERROR_DESCR_SHORT_LENGHT.' '.$key.'</p>';
		              $isError = true;                
		            }              
		            break;
		            
		          case 'description':
		            $values[$key] = trim($_POST[$key]);
		              
		            // check description lenght
		            $len = strlen($values[$key]);
		            if ( ($len<MIN_DESCR_LENGHT) or ($len>MAX_DESCR_LENGHT)) {
		              $eMsg .= '<p>'.LANG_ERROR_DESCR_LENGHT.' '.$key.'</p>';
		              $isError = true;                
		            }              
		            break;

		          case 'facebook_url':
		            $values[$key] = trim($_POST[$key]);
		            break;

		          case 'twitter_url':
		            $values[$key] = trim($_POST[$key]);
		            break;

		          case 'youtube_url':
		            $values[$key] = trim($_POST[$key]);
		            break;

		          case 'embedded_video_title':
		            $values[$key] = trim($_POST[$key]);
		            break;

		          case 'embedded_video_code':
		            $values[$key] = trim($_POST[$key]);
		            break;
		            
		          case 'keywords':
		            $values[$key] = trim($_POST[$key]);
		            // if ($values[$key]>=MAX_TITLE_LENGHT) {
		            // $tmp = wordwrap($values[$key], MAX_TITLE_LENGHT, "<br>");
		            // $tmp = explode('<br>', $tmp);
		            // $values[$key] = $tmp[0];
		            // }
		            break;
		                               
		          case 'email':
		            // check email
		            $values['email'] = $g_site->CheckEmail($_POST['email']);
		            if ($values['email']) {
		            } else {
		              $values[$key] = trim($_POST[$key]);
		              $eMsg .= '<p>'.ADD_SITE_03.'</p>';
		              $isError = true;
		            }            
		            break;
		             
		          case 'company':
   				    $values[$key] = $listing[$key];
		            break;
		            
		          case 'tel':
		            $values['tel_digits'] = preg_replace("/[^0-9]/", "", $_POST[$key]);
		          	$values[$key] = $_POST[$key];
		            break;
		            
		            case 'product':
		          case 'address':
		          case 'city':
		          case 'state':
		          case 'zip':
		          case 'country':
		          case 'fax':
		            $values[$key] = $_POST[$key];
		            break;
		            
		          case 'link_type':
		            if ($listing_upgrade) {
			          	if (isset($_POST[$key])) {
			              $values[$key] = (int) $_POST[$key];
			              $link_type = $values[$key];
			            }   
			
			            // check if checked payment is avaiable
			            if (!isset($g_payment["ctype[$link_type]"])) {
			              $eMsg .= '<p>'.ADD_SITE_06.'</p>';            
			              $isError = true;
			            }
		            } else { 
			            $values[$key] = (int) $listing[$key];
			            $link_type = $values[$key];
		            }
		            
		            if ($category['level']==1 and $link_type!= LT_FEAT_TOP ) {
		            
		              $eMsg .= '<p>'.ADD_SITE_06.'</p>';            
		              $isError = true;
		            }
		            break;
		          
		          case 'reciprocal':		            
		            // check if reciprocal is needed
		            if ($g_payment["crecip[$link_type]"]==1) {
		              if (!$g_site->CheckRecip($_POST[$key], $g_payment['url'] )) {
		                $eMsg .= '<p>'.ADD_SITE_07.'</p>';            
		                $isError = true;
		              } else {
		                $values[$key] = $_POST[$key];
		              }
		            }            
		            break;
		
		          default:
		           $values[$key] = trim($_POST[$key]);
		           break;
		           
		      } // end switch
		      
		    } // end for
		    
			$logo_filename = '';
		    $max_logo_size = MAX_LOGO_SIZE; // 125 kb
			
		    if (!$isError) {
		    	if (isset($_FILES) and strlen($_FILES['logo_upload']['name'])>0) {
			        if ($_FILES['logo_upload']['size']<=$max_logo_size) {
			        	
						$cUpload = new UploadClass(LOGO_DIR, 'logo_upload', 1, array('gif','jpg','jpeg','png'));
			        	
						$logo_filename = str_replace(" ","_",$values['company']).LOGO_NAME_SUFFIX;
			        	
			            $logo_filename = $cUpload->UploadPhoto($logo_filename, true); 
			                      
			            if ($logo_filename) {
			            	$logo_filename = strtolower($logo_filename);
			            	
			                if ($cUpload->ResizeImg(LOGO_DIR.'/'.$logo_filename, LOGO_DIR.'/'.$logo_filename, 468, 60)) {

							// image resized
			                } else {
					        	$eMsg .= '<p>'.ADD_SITE_11.'</p>';
					        	$isError = true;
			                }
			            } else { 
				        	$eMsg .= '<p>'.$cUpload->eName.'</p>';
				        	$isError = true;
			            }
			        } else {
			        	$eMsg .= '<p>'.ADD_SITE_10.'</p>';
			        	$isError = true;
			        }
			        
			        // something went wrong... clean up
			        if ($isError) {
			        	if(!empty($logo_filename) and file_exists(LOGO_DIR."/".$logo_filename)) {
			        		unlink(LOGO_DIR."/".$logo_filename);
			        	}
			        	$logo_filename = '';
			        }
				}
		    }
		    	 		    
		    // check link type
		    if ($link_type==0) {  $isError = true; }      
		      
		    // check reciprocal
		    if ($g_payment["crecip[$link_type]"] == 1 and !isset($values['reciprocal']) ) {
		      $eMsg .= '<p>'.ADD_SITE_07.'</p>';            
		      $isError = true;
		    } 
			          
		    // check filter
		    if ($banned_word = $g_site->CheckWords($values['title'], $values['description'], $values['keywords'], $values['url'], $g_params->GetParams('banned_words') ) ) {
		       $eMsg .= '<p>'.ADD_SITE_08.': '.$banned_word.'</p>';
		       $isError = true;      
		    }
		  
		    // check IP
		    if ($banned_word = $g_site->CheckIp($_SERVER['REMOTE_ADDR'], $g_params->GetParams('banned_ips'))) {
		       $eMsg .= '<p>'.ADD_SITE_09.'</p>';
		       $isError = true;      
		    }
		    
		  } 
		       
		  $tpl_adds_array['{address}'] = $g_addr;
		  
		  //////////////////////////////////////////////////////////
		  // fill array with language values (form language)
		  //////////////////////////////////////////////////////////
		  if ($listing_upgrade) {
			  $payment_period = $g_params->Get('payment', 'payment_period');
			  if ($payment_period>0) {
			    if ($payment_period == 1) {{$lang_period = LANG_1MONTHS; }
			    if ($payment_period == 6) {$lang_period = LANG_6MONTHS; }}
			
				else { $lang_period = LANG_12MONTHS; }
			    $tpl_adds_array['{lang subscription}'] = LANG_SUBSCRIPTION.': '.$lang_period ; 
			
			  } else {
			    $tpl_adds_array['{lang subscription}'] = LANG_NO_SUBSCRIPTION;
			  }
		  }
		  
		  // $tpl_adds_array['{category}'] = $category['name'];	  
		  $tpl_adds_array['{lang edit listing}'] = LISTING_EDIT;     
		  $tpl_adds_array['{lang title}'] = LANG_TITLE;
		  $tpl_adds_array['{max_title_lenght}'] = MAX_TITLE_LENGHT;
		  $tpl_adds_array['{lang url}'] = LANG_URL;
		  $tpl_adds_array['{lang description_short}'] = LANG_DESCR_SHORT;
		  $tpl_adds_array['{lang description}'] = LANG_DESCR;
		  $tpl_adds_array['{lang keywords}'] = LANG_KEYW;
		  $tpl_adds_array['{lang keywords note}'] = LANG_KEYW_NOTE;   
		  $tpl_adds_array['{lang email}'] = LANG_EMAIL;
		  $tpl_adds_array['{lang company}'] = LANG_COMPANY;
		  $tpl_adds_array['{lang facebook_url}'] = LANG_FACEBOOK_URL;
		  $tpl_adds_array['{lang twitter_url}'] = LANG_TWITTER_URL;
		  $tpl_adds_array['{lang youtube_url}'] = LANG_YOUTUBE_URL;
		  $tpl_adds_array['{lang embedded_video_title}'] = LANG_IMBEDDED_VIDEO_TITLE;
		  $tpl_adds_array['{lang embedded_video_code}'] = LANG_IMBEDDED_VIDEO_CODE;
		  $tpl_adds_array['{lang product}'] = LANG_PRODUCT;
		  $tpl_adds_array['{lang address}'] = LANG_ADDR;
		  $tpl_adds_array['{lang city}'] = LANG_CITY;
		  $tpl_adds_array['{lang state}'] = LANG_STATE;
		  $tpl_adds_array['{lang zip}'] = LANG_ZIP;
		  $tpl_adds_array['{lang country}'] = LANG_COUNTRY;
		  $tpl_adds_array['{lang tel}'] = LANG_TEL;
		  $tpl_adds_array['{lang fax}'] = LANG_FAX;
		  $tpl_adds_array['{lang add listing no website}'] = LANG_ADD_LISTING_NO_WEBSITE;
		  $tpl_adds_array['{lang reciprocal}'] = LANG_RECIPROCAL;
		  $tpl_adds_array['{lang note}'] = LANG_NOTE;     
		  $tpl_adds_array['{lang allow_logo}'] = LANG_ALLOW_LOGO;
  		  $tpl_adds_array['{lang upload_logo}'] = LANG_UPLOAD_LOGO;
		  $tpl_adds_array['{lang next}'] = LANG_NEXTS_BTN;
		  $tpl_adds_array['{lang submit}'] = LISTING_EDIT_SUBMIT_BTN;
		  $tpl_adds_array['{lang cancel}'] = LISTING_EDIT_CANCEL_BTN;
		  $tpl_adds_array['{lang inactivate}'] = LISTING_EDIT_INACTIVATE_BTN;
	      $tpl_adds_array['{inactivate confirmation}'] = LISTING_EDIT_INACTIVATE_CONFIRMATION;
		  $tpl_adds_array['{lang back}'] = LANG_BACK;  
		  $tpl_adds_array['{lang url}'] = LANG_URL;
		  $tpl_adds_array['{email}'] = LANG_EMAIL;  
		  
		  $tpl_adds_array['{home}'] = $g_params->Get('site','site_name'); 
		  
		  if ($listing_upgrade) {
			  
			  for ($i=1; $i<=7; $i++) {
			    if ($g_payment["crecip[$i]"] == 1) {
			      $tpl_adds_array['{is_reciprocal_'.$i.'}'] = LINK_RECIPROCAL;
			    } else {
			      $tpl_adds_array['{is_reciprocal_'.$i.'}'] = '';
			    }
			  }
			
			  for ($i=1; $i<=7; $i++) {
			    if ($g_payment["logoup[$i]"] == 1) {
			      $tpl_adds_array['{is_logo_'.$i.'}'] = 'true';
			    } else {
			      $tpl_adds_array['{is_logo_'.$i.'}'] = 'false';
			    }
			  }
		  }
		  
		  // $if_region['top_category'] = $category['level'];
		  // if ($category['level']==1) {
		  // $tpl_adds_array['{top_category_info}'] = TOP_CATEGORY_INFO;
		  // }
		          
		  // reciprocal link:
		  $recip_url = $g_payment['url'];
		  $recip_title = $g_payment['title'];
		  
		  $tpl_adds_array['{my_reciprocal value}'] = '<a href="'.$recip_url.'" target="_blank">'.$recip_title.'</a>';  
		
		  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  // process with $_POST values
		  // fill prices
		  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  if ($listing_upgrade) {
		  
		      foreach ($g_payment as $key=>$value) {
		
		        if (preg_match("/^type\[[0-".LINK_TYPES."]\]$/" , $key)) {
		      
		          $value = $value - $last_payment_amount;
		        	
		          $value = number_format((float) ($value),2);
		          
		          if ($value <= 0) {$value = 'Free';}
		
		          $tpl_adds_array['{payment '.$key.'}'] = $value;
		          
		          if ($key == 'type['.$listing['link_type'].']') {
			          $tpl_adds_array['{payment '.$key.'}'] = 'n/a';
		          }
		        
		        } else if ($key[1]=='c') {
		          // $tpl_adds_array['{style '.$key.'}'] = $value;
		        }
		      }
		  }
	    
	      //////////////////////////////////////
	      // check active link types
	      //////////////////////////////////////
		  if ($listing_upgrade) {
		  
		      $k = 0;
		      $last = $i;
		      for ($i=1; $i<=7; $i++) {
		
		        // if (!$g_payment["ctype[$i]"] or ( $category['level']==1 and $i!=LT_FEAT_TOP  ) ) { // ctype[] - checkbox  if active link type
		        // $tpl_adds_array["{style type[$i]}"] = 'style="display: none;"';		
		        // } else {

		          $tpl_adds_array["{style type[$i]}"] = '';
		      	
		          $last = $i;
		          if ($link_type==$i) {
		            $tpl_adds_array["{checked type[$i]}"] = 'checked';
		          } else {
		            $tpl_adds_array["{checked type[$i]}"] = '';
		            if ($i < 4) {
		            	$tpl_adds_array["{style type[$i]}"] = 'style="display: none;"';
		            }
		          }
		        
		          if (!$in_top_categ and $i==5) {
		            	$tpl_adds_array["{style type[$i]}"] = 'style="display: none;"';
		          }
		          
 	          	  if ($tpl_adds_array["{style type[$i]}"] === '') {
		            $tpl_adds_array["{style type[$i]}"] = 'class="colored"';
	          	  }
		          
		          $k = 1 - $k;
		        
		        // }
		      }
		    
		      // if ($link_type==0) { $tpl_adds_array["{checked type[2]}"] = 'checked'; }
		  }
	    
	      //////////////////////////////////////
	      // language link names
	      //////////////////////////////////////
	      $tpl_adds_array['{link type1}'] = LINK_TYPE1;
	      $tpl_adds_array['{link type2}'] = LINK_TYPE2;
	      $tpl_adds_array['{link type3}'] = LINK_TYPE3;
	      $tpl_adds_array['{link type4}'] = LINK_TYPE4;
	      $tpl_adds_array['{link type5}'] = LINK_TYPE5;
	      $tpl_adds_array['{link type6}'] = LINK_TYPE6;
	      $tpl_adds_array['{link type7}'] = LINK_TYPE7;
	      $tpl_adds_array['{link type8}'] = LINK_TYPE8;
	      $tpl_adds_array['{link type9}'] = LINK_TYPE9;
	                        
	      $tpl_adds_array['{currency}'] = CURRENCY;
		  
		  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  // filling forms
		  // fill input values
		  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  foreach ($values as $key=>$value) {
		    $tpl_key = '{'.$key.' value}';
		    $tpl_adds_array[$tpl_key] = $listing[$key];
		  }
          $country_value = $listing['country'];
		  
		  if ($listing['status'] == SITE_BANNED) {
			  $tpl_adds_array["{inactivate style}"] = 'style="display: none;"';
		  } else {
		  	  $tpl_adds_array["{inactivate style}"] = '';
		  }
		  
		  // fill template
		  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'listing_edit.tpl.php');  

		  // countries dropdown logic
		  $coForm = new FormClass();
		  $coform_fields = array('CBO|country');   
		  $coForm->SetCombo('country', $g_countries, 'value', 'name');
		  $country_array = array('country'=>$country_value);
		  $coform_array = $coForm->MakeForm($coform_fields, $country_array);
		  $tpl_adds_array['{country}'] = $coform_array['{country}'];
		  
		  $tmp_tpl->ReplaceIn($tpl_adds_array);   
		  // $tmp_tpl->IfRegion($aIfRegion);
		    
		  if (isset($_POST['submit']) and ($isError) ){
		  
		    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    // failure, show form and errors
		    // fill input values
		    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    foreach ($values as $key=>$value) {
		      $tpl_key = '{'.$key.' value}';
		      $tpl_adds_array[$tpl_key] = $values[$key];
		    }
            $country_value = $values['country'];
		    if (strlen($eMsg)) {
		      $tpl_adds_array['{error}'] = '<div class="info">'.$eMsg.'</div>';
		    } else {
		      $tpl_adds_array['{error}'] = '';
		    }
		          
		    // set template    
		    $tmp_tpl->SetTemplate(DIR_TEMPLATE.'listing_edit.tpl.php');  

			// countries dropdown logic
			$coForm = new FormClass();
			$coform_fields = array('CBO|country');   
			$coForm->SetCombo('country', $g_countries, 'value', 'name');
			$country_array = array('country'=>$country_value);
			$coform_array = $coForm->MakeForm($coform_fields, $country_array);
			$tpl_adds_array['{country}'] = $coform_array['{country}'];
			
		    $tmp_tpl->ReplaceIn($tpl_adds_array);   
		    // $tmp_tpl->IfRegion($aIfRegion);
		    
		  } else if (isset($_POST['submit']) and (!$isError) ){
		  
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// successful, save updates
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////		  
		    $values['link_type'] = $link_type;
		    $values['featured'] = $g_featured[$link_type];
		    $values['date'] = date(DATETIME_FORMAT);
		    $values['last_checked'] = date(DATETIME_FORMAT);
		    
		    // get pagerank    
		    if (COLLECT_PAGE_RANK) { 
		    
		      include('catalog/pr.php');
		      //$pr = trim(getrank($values['url']));
		      $pr = trim(pagerank($values['url']));
		      if (is_numeric($pr) and $pr >0) {
		        $values['pr'] = $pr;
		      }
		    }
		    
		    // nofollow?
		    // if ($link_type === LT_NOFOLLOW) {  $values['nofollow'] = 1;  }
		    
		    // is paid link?
		    $amount = 0;    
		    if ($listing_upgrade) {
		    	if ($link_type <> $listing['link_type']) {
				    $amount = $g_payment['type['.$link_type.']'] - $last_payment_amount;
		    	}
		    }

			// if ($amount>0) {
			// $values['paid_link'] = 1;
			// } else {
			// $values['paid_link'] = 0;
			// }
		    // } else {
			      $values['paid_link'] = $listing['paid_link'];
		    // }
		    
		    // other stuff     
		    if ($listing_upgrade) {
		    	$values['last_payment'] = date(DATETIME_FORMAT);
		    } else {
		    	$values['last_payment'] = $listing['last_payment'];
		    }
		    $values['user_ip'] = $_SERVER['REMOTE_ADDR'];
		
		    $values['status'] = $listing['status'];
				              
		    $values['original_description'] = $listing['original_description'];
		    
		    if ($amount>0) {
		    	$values['status'] = SITE_WAITING;
		    }
		    
		    // update listing
		    $result = $g_site->UpdateSite($values, $listing['id']);
		    
		    if ($result>0) {
			  $id = $listing['id'];
		    	
   	          $email_tpl['email_body'] = 'A listing was updated for {user_site_info_url}';
		      $email_tpl['title'] = 'Listing Updated in {my_site_url}';        
		      $adminEmail = $values;
		      $adminEmail['email'] = ADMIN_EMAIL;
		      $adminEmail['id'] = $listing['id'];
		      $g_site->SendEmail($email_tpl, $adminEmail );
		        
		      // rename logo file if uploaded
		      if (!empty($logo_filename)) {
		      	rename(LOGO_DIR.'/'.$logo_filename,LOGO_DIR.'/'.$id.LOGO_NAME_SUFFIX);
		      }
		      
		      if ($listing_upgrade) {
			      if ($amount>0) {
			      	
		   	        $email_tpl['email_body'] = 'A listing was updgraded to a Featured one for {user_site_info_url}';
				    $email_tpl['title'] = 'Listing Upgraded to a Featured One in {my_site_url}';        
				    $adminEmail = $values;
				    $adminEmail['email'] = ADMIN_EMAIL;
				    $adminEmail['id'] = $listing['id'];
				    $g_site->SendEmail($email_tpl, $adminEmail );

				    // add payment to payment table
				    $payment['site_id'] = $listing['id'];
			    	$payment['amount'] = $amount;
				    $payment['email'] = '';
				    $payment['paid'] = PM_NOT_PAID;
				    $payment['currency'] = CURRENCY;
				    $payment['payment_period'] = PAYMENT_PERIOD;
				    $payment['date'] = $values['last_payment'];
				    $payment['error'] = LANG_INFO_PAYMENT3;
				    $payment['is_error'] = 1;
				    $g_site->AddPayment($payment);
				    
			        include('catalog/payment.php');
			        $if_region['ispayment'] = 1;
			
			      } else {
			        $if_region['ispayment'] = 0;
			        
			        // $values['id'] = $id;
			        // if (LINK_INSTANTLY_APPEAR) {
			        // $g_site->SendEmail($g_params->GetParams('site_approved_email'), $values );
			        // } else {
			        // $g_site->SendEmail($g_params->GetParams('site_pending_email'), $values );          
			        // }
			                        
			      }
		      }
		      
		      $tpl_adds_array['{message}'] = LISTING_EDIT_SUCCESS;
		      $tmp_tpl->SetTemplate(DIR_TEMPLATE.'edit_message.tpl.php');
		      if ($amount>0) {
			      $tmp_tpl->SetTemplate(DIR_TEMPLATE.'edit_message_pay.tpl.php');
		      }
		      	
		      
		    } else {
		      $tpl_adds_array['{message}'] = LISTING_EDIT_FAILURE;
		      $tmp_tpl->SetTemplate(DIR_TEMPLATE.'edit_message.tpl.php');
		    }

			//countries dropdown logic
			$coForm = new FormClass();
			$coform_fields = array('CBO|country');   
			$coForm->SetCombo('country', $g_countries, 'value', 'name');
			$country_array = array('country'=>$country_value);
			$coform_array = $coForm->MakeForm($coform_fields, $country_array);
			$tpl_adds_array['{country}'] = $coform_array['{country}'];

			$tmp_tpl->ReplaceIn($tpl_adds_array);   
		    
		  }
	
	  if (isset($_POST['inactivate'])) {
	  	
		  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  // inactivate listing
		  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	  $values_in['date'] = date(DATETIME_FORMAT);
		  $values_in['last_checked'] = date(DATETIME_FORMAT);
		  $values_in['user_ip'] = $_SERVER['REMOTE_ADDR'];
		  $values_in['status'] = SITE_BANNED;
		  
		  // update listing
		  $id = $g_site->UpdateSite($values_in, $listing['id']);
		    
		  if ($id>0) {
		      
   	          $email_tpl['email_body'] = 'A listing was inactivated in {my_site_url} for:\r\n';
   	          $email_tpl['email_body'] .= 'URL: '.$listing['url'].'\r\n';
   	          $email_tpl['email_body'] .= 'Company: '.$listing['company'];
   	          $email_tpl['title'] = 'Listing Inactivated in {my_site_url}';        
		      $adminEmail = $values;
		      $adminEmail['email'] = ADMIN_EMAIL;
		      $adminEmail['id'] = 0;
		      $g_site->SendEmail($email_tpl, $adminEmail );
		        
		      $tpl_adds_array['{message}'] = LISTING_EDIT_INACTIVATE_SUCCESS;
		      
		    } else {
		      $tpl_adds_array['{message}'] = LISTING_EDIT_INACTIVATE_FAILURE;
		    }    
			// countries dropdown logic
			$coForm = new FormClass();
			$coform_fields = array('CBO|country');   
			$coForm->SetCombo('country', $g_countries, 'value', 'name');
			$country_array = array('country'=>$country_value);
			$coform_array = $coForm->MakeForm($coform_fields, $country_array);
			$tpl_adds_array['{country}'] = $coform_array['{country}'];
		    
			$tmp_tpl->SetTemplate(DIR_TEMPLATE.'edit_message.tpl.php');
	  		$tmp_tpl->ReplaceIn($tpl_adds_array);   
		    
	  }
	  	
	  // user not authorized to edit the listing
	  } else {
		$tpl_adds_array['{error}'] = LISTING_EDIT_ERROR;
	    $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_error.tpl.php');      
		$tmp_tpl->ReplaceIn($tpl_adds_array);   
	  }
	   
  // user not logged
  } else {
	  $tpl_adds_array['{error}'] = USER_NOT_LOGGED_ERROR;
  	  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'user_error.tpl.php');      
	  $tmp_tpl->ReplaceIn($tpl_adds_array);   
  }
        
  $tpl_main = $tmp_tpl->Get();
  
?>
