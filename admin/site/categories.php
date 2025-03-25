<?php
       
    $cCateg = new CategoriesClass();
    $aCateg = array();
    $aMoveCateg = array();

    if (isset( $_GET['id_up']) and $_GET['id_up']>0) {
      $id_up = (int) $_GET['id_up'];
      $cur_cat_row = $cCateg->GetCategory($id_up);
      $is_up = true;
    } else {
      $id_up = 0;
      $cur_cat_row = array();
      $cur_cat_row['level'] = 1;
      $is_up = false;
    } 
    
    $eMsg = '';    

    $tmpIf['show_del_categ'] = 0;
    
    // top of category
    $parent_categ_link = '<a href="index.php?mod=site&amp;inc=categories">Top</a> ';
    $currrent = $cur_cat_row;
    
    $parent_categ = '';
    $categ_link = '';
        
    while ($is_up) {
    
     if ($currrent['id_up']>0) {
       $parent_categ = $g_categ->GetCategory($currrent['id_up']);
     } else {
       $is_up = false;
     } 
          
      $categ_link = ' &raquo; <a href="index.php?mod=site&amp;inc=categories&amp;id_up='.$currrent['id'].'">'.$currrent['name'].'</a>' . $categ_link;
     
      $currrent = $parent_categ; 
    }
    $tmp_tpl['{top}'] = $parent_categ_link.$categ_link;


    if ($cur_cat_row['level']<4) { 
      
       if ($cur_cat_row['level']==3) {  $tmpIf['show_links'] = 0; } else { $tmpIf['show_links'] = 1; }
       $tmpIf['show_add'] = 1;
      
	   // delete category, part 1
       if (isset($_GET['del_id']) and !isset($_POST['submit_del'])) {
          $del_id = (int) $_GET['del_id'];
        
          if ($del_id>0) {
          	$sub_categ_count = count($cCateg->GetCountsCategs($del_id));
          	if ($sub_categ_count > 0) {
            	$eMsg .= 'The category you are about to delete contains ';
          		if ($sub_categ_count > 1) {
	            	$eMsg .= $sub_categ_count.' subcategories. Please delete them first.';
          		} else {
	            	$eMsg .= $sub_categ_count.' subcategory. Please delete it first.';
          		}
          	} else {
  	        	$CountsSitesActive = 0;
  	        	$CountsSitesPending = 0;
          		$CountsSitesActive = $cCateg->GetCountsSites_2($del_id,SITE_VIEW);
  	        	$CountsSitesPending = $cCateg->GetCountsSites_2($del_id,SITE_WAITING);
  	        	
          		if (($CountsSitesActive + $CountsSitesPending) > 0) {
	          		$tmpIf['show_del_categ'] = 1;
				    $tmpIf['show_add'] = 0;
				    
				    $del_cat = $cCateg->GetCategory($del_id);
				    $tmp_tpl['{categ_name}'] = $del_cat['name'];
				    $tmp_tpl['{categ_id}'] = $del_id;
				    
			        // get all categories for drop down box
			        $cat_table = $cCateg->GetAllCategories();
			        $i=0;
			        foreach ($cat_table as $row1) {
			        	if ($row1['id'] <> $del_id) {
					        $aMoveCateg[$i]['{move_cat_id}'] = $row1['id'];
				         	$aMoveCateg[$i]['{move_cat_name}'] = $row1['name'];
					        $i++;
			         	}
			      	}
          		} else {
		            $ok =  $cCateg->DeleteCategory($del_id);
		            if ($ok == 'OK') {
		               $eMsg .= 'Category Deleted'; 
		            } else {
		            	$eMsg .= $ok;
		            }
          		}
			      	
		    }
			    
          }
          	
       }

	   // delete category, part 2
       if (isset($_POST['submit_del'])) {
       	  $del_cat_id =  $_POST['del_cat_id'];
       	  $move_cat_id = $_POST['move_cat'];
       	  
       	  $cat_sites = $cCateg->GetSitesIDs($del_cat_id);
		  
		  foreach ($cat_sites as $row2) {
			  // check if not orphan connections
			  if ($row2['conn_id'] == $row2['sites_id']) {
		  	
		       	  if ($_POST['del_action'] == 'delete') {
		       	  		// delete listings
		
						// check if listing is connected to any other category
						$categories_in = $cCateg->GetSiteCategories($row2['conn_id']);
						if (count($categories_in) == 1) {
							//listing connected only to this category; delete it
							$g_site->DeleteSite($row2['conn_id']);
						}
		       	  } else {
		       	  		// move listings		       	  		
		       	  		// check if listing is not already connected to the moving category
		       	  		if(!$g_site->IsConnection($row2['conn_id'],$move_cat_id)) {
		       	  			//connect listing to the moving category
		       	  			$g_site->AddConnection($row2['conn_id'],$move_cat_id);
		       	  		}
		       	  }
		  	  }
	       	  
			  // delete connection
			  $g_site->DeleteConnection($row2['conn_id'],$del_cat_id);
	       	  
		  }
       	  
       	  // delete category
          $ok =  $cCateg->DeleteCategory($del_cat_id);
          if ($ok == 'OK') {
             $eMsg .= 'Category Deleted'; 
          } else {
             $eMsg .= $ok;
          }
       }
       
       // update category
       if (isset($_POST['update'])) {
        
          foreach ($_POST['update'] as $key=>$value) {
            if (strlen($_POST['name'][$key])>0) {
              $aInsert['name'] = htmlspecialchars($_POST['name'][$key]);
              $aInsert['description'] = htmlspecialchars($_POST['description'][$key]);
              $aInsert['keywords'] = htmlspecialchars($_POST['keywords'][$key]);
              
              $ok = $cCateg->UpdateCategory($aInsert, $key);
              
              if ($ok) {
                  $eMsg .= 'Category Updated'; 
              }
            }
          }
          
       } 
      
       // add category
       if (isset($_POST['submit'])) {
       
          if (strlen($_POST['new_name'])>0) {
            $aInsert['name'] = htmlspecialchars($_POST['new_name']);
            $aInsert['description'] = htmlspecialchars($_POST['new_description']);
            $aInsert['keywords'] = htmlspecialchars($_POST['new_keywords']);
            $aInsert['id_up'] = $id_up;
            $ok = $cCateg->AddCategory($aInsert);
            
            if ($ok) {
              $eMsg .= 'Added new category: '.$aInsert['name'];           
            }
          } 
        
       }   
      
       // add multiple categories
       if (isset($_POST['submit_multiple'])) {
          $iAdded = 0;
          if (strlen($_POST['new_names'])>0) {
            $names = explode("\r\n", $_POST['new_names']);
            
            foreach ($names as $name) {
              $aInsert['name'] = htmlspecialchars($name);
              $aInsert['id_up'] = $id_up;
              $ok = $cCateg->AddCategory($aInsert);
              if ($ok) {
                $iAdded++;
              }
            }
            
          }  
          
          $eMsg .= 'Added '.$iAdded.' new categories';    
       }  
           
       // get categories and show
       $table = $cCateg->GetCategories($id_up);
       $i=0;
       foreach ($table as $row) {
         $aCateg[$i]['{name}'] = $row['name'];
         $aCateg[$i]['{id}'] = $row['id'];
         $aCateg[$i]['{description}'] = $row['description'];
         $aCateg[$i]['{keywords}'] = $row['keywords'];
         $aCateg[$i]['{id_up}'] = $id_up;
         $i++;
       }
              
     } else {
      $tmpIf['show_add'] = 0;
      $tmpIf['show_links'] = 0;
     }
      
     // info
     if (strlen($eMsg)>0 ) {
       $tmp_tpl['{notifications}'] = '<div class="info">'.$eMsg.'</div>';
     } else {
       $tmp_tpl['{notifications}'] = '';
     }
     
     $g_template->SetTemplate(ADMIN_TEMPLATE_DIR.'site_categories.html');
     $g_template->IfRegion($tmpIf);
     if ($tmpIf['show_del_categ'] == 0) { 
	     $g_template->FillRowsV2('row category', $aCateg);
     } else {
	     $g_template->FillRowsV2('row move_category', $aMoveCateg);
     }
     $g_template->ReplaceIn($tmp_tpl);
     $tpl_main['{content}'] = $g_template->Get();
     
?>
