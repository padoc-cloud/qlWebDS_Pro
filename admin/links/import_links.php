<?php  
  
  if ($g_user->Level() == AL_ADMIN) {
  
    $isError = false;
    $eMsg = '';
    $notifications = '';
	$csv_filename = 'listings_import.csv';

	$values = array('title'=>'', 'title1'=>'', 'title2'=>'', 'title3'=>'', 'title4'=>'', 'title5'=>'', 'url'=>'', 'url1'=>'',
	                'url2'=>'', 'url3'=>'', 'url4'=>'', 'url5'=>'', 'email'=>'', 'company'=>'', 'product'=>'', 
	                'address'=>'', 'city'=>'', 'state'=>'', 'zip'=>'', 'country'=>'', 'tel'=>'', 'fax'=>'',
	                'description_short'=>'', 'description'=>'', 'facebook_url'=>'', 'twitter_url'=>'', 'youtube_url'=>'', 
	    			'embedded_video_title'=>'', 'embedded_video_code'=>'', 
					'reciprocal'=>'', 'keywords'=>'', 'link_type'=>'1', 'note'=>'', 'id_code'=>''
					);
	
    $template = ADMIN_TEMPLATE_DIR.'links_import_links.html';

	$site_tpl['{load_results}'] = ''; 
	
    if (isset($_POST['submit'])) {
    	if (!isset($_POST['delimiter']) || (strlen($_POST['delimiter'])==0)) {
			$notifications .= '<p>Category ID List Delimiter Not Specified.</p>';
	       	$isError = true;
    	} else {
	    
		    if (isset($_FILES) && strlen($_FILES['csv_upload']['name'])>0) {
			        	
				$cUpload = new UploadClass('../cache', 'csv_upload', 1, array('csv'));
			        	
			        	
			    $csv_filename = $cUpload->UploadFile($csv_filename, true); 
			                      
			    if ($csv_filename) {
			    	
				    $row = 1;
					if (($handle = fopen("../cache/".$csv_filename, "r")) !== FALSE) {
						$site_tpl['{load_results}'] .= '<tr><td colspan="2"><table><tbody>';
						
					    while (($data = fgetcsv($handle,0, ",")) !== FALSE) {
					    	
					    	//company
					    	$company = $data[0];
					    	//url
						    $url = $data[1];
						    //long description
						    $description = $data[2];
					    	//categories
					    	$categories = $data[3];
					    	
						    if(strlen($company)==0) {
								$site_tpl['{load_results}'] .= '<tr><td colspan="2"><font color="red">Row '.$row.': ERROR: No Name</font></td></tr>';
								$row++;
								continue;
						    }
						    
						    $url = $g_site->ParseURL($url);
						    if(!$url) {
								$site_tpl['{load_results}'] .= '<tr><td colspan="2"><font color="red">Row '.$row.', Name: '.$company.' ERROR: No URL</font></td></tr>';
								$row++;
								continue;
						    }
						    
						    if(strlen($categories)==0) {
								$site_tpl['{load_results}'] .= '<tr><td colspan="2"><font color="red">Row '.$row.', Name: '.$company.' ERROR: No Categories</font></td></tr>';
								$row++;
								continue;
						    }
						    
						    //find at least one valid category
						    $cat_found = false; 
						    $categories = explode($_POST['delimiter'],$categories);
						    foreach($categories as $category) {
	          					$first_cat = $g_categ->GetCategory($category);
						    	if ($first_cat) {
						    		$cat_found = true;
						    		break;
						    	}
						    }
						    
						    if(!$cat_found) {
								$site_tpl['{load_results}'] .= '<tr><td colspan="2"><font color="red">Row '.$row.', Name: '.$company.' ERROR: No Valid Categories</font></td></tr>';
								$row++;
								continue;
						    }
						    
					        // check if listing is already in directory
					        $site_row = $g_site->GetListingByCompanyUrl($company,$url);
	     				    
							$site_tpl['{load_results}'] .= '<tr>';
	
							$new_site = false;
							
					        if (is_array($site_row)) {
								$site_tpl['{load_results}'] .= '<td>Row '.$row.', Name: '.$company.' - Site exists.</td>';
						    } else {
						    	/*
						    	 ***add site***
						    	 */
						    	
						    	$values['title'] = $company;
						    	$values['company'] = $company;
						    	$values['url'] = $url; 
						    	$values['description'] = $description;
	      						
						    	//other stuff
						    	$values['paid_link'] = 0;
							    $values['last_payment'] = date(DATETIME_FORMAT);
							    $values['user_ip'] = $_SERVER['REMOTE_ADDR'];
	      						$values['status'] = SITE_VIEW;
	    						$values['original_description'] = $values['description'];
	      						
							    $payment['amount'] = 0;
							    $payment['email'] = '';
							    $payment['paid'] = PM_NOT_PAID;
							    $payment['currency'] = CURRENCY;
							    $payment['payment_period'] = PAYMENT_PERIOD;
							    $payment['date'] = date(DATETIME_FORMAT);
							    $payment['error'] = LANG_INFO_PAYMENT3;
							    $payment['is_error'] = 1;
	      						
	    						$id = $g_site->AddSite($values, $payment, $category);
	
	    						if ($id > 0) {
									$site_tpl['{load_results}'] .= '<td>Row '.$row.', Name: '.$company.' - Site added.</td>';
	    						} else {
									$site_tpl['{load_results}'] .= '<tr><td colspan="2"><font color="red">Row '.$row.', Name: '.$company.' ERROR: '.$g_site->eText.'</font></td></tr>';
									$row++;
									continue;
	    						}
	
								$new_site = true;
						    }
						    
						    if ($new_site) {
						    	$site_id = $id;
						    } else {
						    	$site_id = $site_row['id'];
						    }
					    	
						    $site_added = false;
						    
							$site_tpl['{load_results}'] .= '<td>';
						    foreach($categories as $cat_id) {
	          					$cat = $g_categ->GetCategory($cat_id);
						    	if ($cat) {
						    		if ($new_site && ($category===$cat_id)) {
						    			//already added
							            $site_added = true;
						    		} else {
						    			//add to category
							            if ($g_site->AddConnection($site_id, $cat_id)) {
							            	$site_added = true;
							            } else {
							            	$error = $g_site->eText;
							            }
						    		}
						    		if ($site_added) {
										$site_tpl['{load_results}'] .= 'Category ID: '.$cat_id.' Site Added.<br/>';
						    		} else {
										$site_tpl['{load_results}'] .= '<font color="red">Category ID: '.$cat_id.' ERROR: '.$error.'</font><br/>';
						    		}
						    	} else {
						    		//category with this ID not found in the directory
									$site_tpl['{load_results}'] .= '<font color="red">Category ID: '.$cat_id.' ERROR: Category Not Found.</font><br/>';
						    	}
						    }
							$site_tpl['{load_results}'] .= '</td>';
					    	$site_tpl['{load_results}'] .= '</tr>';
							$row++;
					    }
					    fclose($handle);
	
			     		$site_tpl['{load_results}'] .= '</tbody></table></td></tr>';
					    
					    $notifications .= '<p>File was Successfully Loaded.</p>';
					    
					}
			    } else { 
			       	$notifications .= '<p>'.$cUpload->eName.'</p>';
			       	$isError = true;
		        }
			} else {
				$notifications .= '<p>File Name Not Specified.</p>';
		       	$isError = true;
		    }
    	}
    }
	if (!empty($notifications)) {    
	    $site_tpl['{notifications}'] = '<div class="info">'.$notifications.'</div>';
	} else {
	    $site_tpl['{notifications}'] = $notifications;
	}
      
	$site_tpl['{delimiter}'] = '~'; 
	
	$g_template->SetTemplate($template);  
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();
          
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }

 function CategoryPath($category) {
    $parent_categ = '';
    $categ_link = $category['name'];
    $is_up = true;    
    
    while ($is_up) {
    	if ($category['id_up']>0) {
       		$parent_categ = $g_categ->GetCategory($category['id_up']);
    	} else {
        	$is_up = false;
     	} 
        $categ_link = $category['name'].'->'.$categ_link;
      	$category = $parent_categ; 
    }
    return $categ_link;
 }
?>
