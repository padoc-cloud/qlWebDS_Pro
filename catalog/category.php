<?php

  // If admin is logged in, and submit
  if ($g_user->Level()==AL_ADMIN) {
    $eMsg = '';
    
    // delete categories
    if (isset($_POST['delete_categories']) and isset($_POST['idc'])){
      
      // get IDs to delete 
      $delete_ids = array_keys($_POST['idc']);

      // delete
      $ret = $g_categ->DeleteCategories($delete_ids);
                
      // message
      $eMsg = '<div class="info"><p>Deleted '.$ret.' from '.count($delete_ids).' Selected Categories </p></div>';
       
    } // end delete 
    
    // update category
    if ( isset($_POST['update_category']) ) {
      // trim name
      $_POST['new_name'] = trim ($_POST['new_name']);
      
      // check length
      if ( strlen($_POST['new_name']) >0 ) {
      
        // update name
        if ($g_categ->UpdateCategory( array( 'name'=>$_POST['new_name']) , $cur_cat_row['id']) ) {
      
          $cur_cat_row['name'] = $_POST['new_name'];
          $cur_cat_row = $g_categ->GetCategory($cur_cat_row['id']);

        }
      } else {
        $eMsg = '<div class="info"><p>New Category Name is too Short!</p></div>';
      }
      
    } // end update
  }

  $toTemplate = array();

  // category menu 
  // in $tpl_sub_menu table consists with subcategory_menu_row`s, to replace {category names}
  $tSubcategories = $g_categ->GetCategories($cur_cat_row['id']);
  
  $tCountsSites = $g_categ->GetCountsSites($cur_cat_row['id']);
  $tCountsCategs = $g_categ->GetCountsCategs($cur_cat_row['id']);
  
  foreach ($tSubcategories as $row) {
     
     if (isset($tCountsSites[$row['id']])) {
        $hmany_s = $tCountsSites[$row['id']];
     } else { $hmany_s = 0;  }
     
     if (isset($tCountsCategs[$row['id']])) {
        $hmany_c = $tCountsCategs[$row['id']];
     } else { $hmany_c = 0;  }
          
     if (MOD_REWRITE) {
       $scateg_link = '<a href="'.SITE_ADDRESS.$g_categ->Mod_Rewrite_url($row['id']).'"><u>'.$row['name'].'</u></a> ['.$hmany_s.'/'.$hmany_c.']';
     } else {
       $scateg_link = '<a href="index.php?'.$row['mod_rewrite'].'&amp;categ='.$row['id'].'"><u>'.$row['name'].'</u></a> ['.$hmany_s.'/'.$hmany_c.']';
     }
     
     // admin checkbox
     if ($g_user->Level()==AL_ADMIN) { 
       $scateg_link = "<input type=\"checkbox\" name=\"idc[" . $row['id'] ."]\" > ".$scateg_link;
     }          
     $toTemplate[] =  array('{subcategory}'=>$scateg_link ) ;
  }

  $tpl_subcategories = $toTemplate;

  // top of category
  $parent_categ_link = '<a href="'.CATALOG_ADDRESS.'"><u>'.$g_params->Get('site','site_name').'</u></a>';
  $currrent = $cur_cat_row;
  $is_up = true;
  $parent_categ = '';
  $categ_link = '';
  $categ_description = $currrent['description'];
  $first_link = true;

  $sort_by = 'date';
  if (empty($sort_page) === false) {
  	$sort_page_arr = explode('-',$sort_page);
	$s_key = 0;
	
  	// i.e. 1-sort
  	if (count($sort_page_arr) == 2) {
  		$s_key = 1;
  		$s_value= 0;
  	}
  	//i.e. 2-page-1-sort
  	if (count($sort_page_arr) == 4) {
  		$s_key = 3;
  		$s_value= 2;
  	}
  	if ($s_key <> 0) {
  		if ($sort_page_arr[$s_key] == 'sort') {
  			if ($sort_page_arr[$s_value] == '1') {
  				$sort_by = 'name';
			}
			if ($sort_page_arr[$s_value] == '2') {
				$sort_by = 'title';
			}
		}
  	}
  }

  $cat_mod_rewrite_url = $g_categ->Mod_Rewrite_url($currrent['id']);
  
  while ($is_up) {
    if ($currrent['id_up']>0) {
      $parent_categ = $g_categ->GetCategory($currrent['id_up']);
    } else {
      $is_up = false;
    } 
    
    if ($first_link) {
      	  $date_sel = '';
      	  $date_sel_end = '';
      	  $name_sel = '';
      	  $name_sel_end = '';
      	  $title_sel = '';
      	  $title_sel_end = '';
      	  switch ($sort_by) {
      	  	case 'date':
      	  		$date_sel = '<strong>';
      	  		$date_sel_end = '</strong>';
      	  		break;
      	  	case 'name':
      	  		$name_sel = '<strong>';
      	  		$name_sel_end = '</strong>';
      	  		break;
      	  	case 'title':
      	  		$title_sel = '<strong>';
      	  		$title_sel_end = '</strong>';
      	  		break;
      	  }
    }
    if (MOD_REWRITE) {
  	  $cat_mod_rewrite_url = $g_categ->Mod_Rewrite_url($currrent['id']);
      $categ_link = ' &raquo; <a href="'.SITE_ADDRESS.$cat_mod_rewrite_url.'"><u>'.$currrent['name'].'</u></a>' .$categ_link;
      if($first_link) {
		  $tpl_subcateg_array['{sort_by_date}'] = '<a href="'.SITE_ADDRESS.$cat_mod_rewrite_url.'">'.$date_sel.'Date'.$date_sel_end.'</a>'; 
  		  $tpl_subcateg_array['{sort_by_name}'] = '<a href="'.SITE_ADDRESS.$cat_mod_rewrite_url.'1-sort">'.$name_sel.'Name'.$name_sel_end.'</a>'; 
  		  $tpl_subcateg_array['{sort_by_title}'] = '<a href="'.SITE_ADDRESS.$cat_mod_rewrite_url.'2-sort">'.$title_sel.'Title'.$title_sel_end.'</a>';
	      $first_link = false;
      }     
    } else {
      $categ_link = ' &raquo; <a href="index.php?'.$currrent['mod_rewrite'].'&amp;categ='.$currrent['id'].'" >'.$currrent['name'].'</a>' . $categ_link;
      if($first_link) {
		  $tpl_subcateg_array['{sort_by_date}'] = '<a href="index.php?'.$currrent['mod_rewrite'].'&amp;categ='.$currrent['id'].'" >'.$date_sel.'Date'.$date_sel_end.'</a>'; 
  		  $tpl_subcateg_array['{sort_by_name}'] = '<a href="index.php?'.$currrent['mod_rewrite'].'&amp;categ='.$currrent['id'].'&amp;sort=1" >'.$name_sel.'Name'.$name_sel_end.'</a>'; 
  		  $tpl_subcateg_array['{sort_by_title}'] = '<a href="index.php?'.$currrent['mod_rewrite'].'&amp;categ='.$currrent['id'].'&amp;sort=2" >'.$title_sel.'Title'.$title_sel_end.'</a>'; 
      	  $first_link = false;
      }     
    } 
    $currrent = $parent_categ; 
  }
  
  // add site and add category link
  // check if you can add category link
  if (!ALLOW_ADD_CATEG and ($g_user->Level()!==AL_ADMIN)) { 
    $if_region['allow_add_categ'] = 0;
  } else { $if_region['allow_add_categ'] = 1; }  

  // add site link
  $tpl_subcateg_array['{add_site}'] = LANG_ADD_SITE;
  $tpl_subcateg_array['{add_site_url}'] = SITE_ADDRESS.'index.php?adds='.$cur_cat_row['id'];
  
  $tpl_subcateg_array['{home_url}'] = $g_params->Get('site','site_address');
  
  // add category link
  if ($cur_cat_row['level']<4) {
    $tpl_subcateg_array['{add_categ}'] = LANG_ADD_CATEGORY;
    $tpl_subcateg_array['{add_categ_url}'] = SITE_ADDRESS.'index.php?addc='.$cur_cat_row['id'];    
     
  } else {
    $tpl_subcateg_array['{add_categ}'] = '';
    $tpl_subcateg_array['{add_categ_url}'] = '';  
    $if_region['allow_add_categ'] = 0;
  }

  // admin menu
  if ($g_user->Level()==AL_ADMIN) {
    $tpl_subcateg_array['{admin top menu}'] = '<form action="'.$g_addr.'" method="POST">'.$eMsg;
    
    $tpl_subcateg_array['{admin foot menu}'] = '<p class="admin_p"><strong>Admin Menu:</strong><br>Selected Categories:<input type="submit" name="delete_categories" value="Delete" class="button2"> | <input type="text" name="new_name" value="'.$cur_cat_row['name'].'"><input type="submit" name="update_category" value="Change" class="button2"></p></form>';
  } else {
    $tpl_subcateg_array['{admin top menu}'] = '';
    $tpl_subcateg_array['{admin foot menu}'] = '';  
  }

  $tpl_array['{google_analytics}'] = $g_params->Get('site', 'site_google_analytics');
  $tpl_subcateg_array['{category names}'] = $parent_categ_link . $categ_link;
  $tpl_subcateg_array['{description}'] = $categ_description;
  $tpl_array['{name}'] = $g_params->Get('site', 'site_name');
  $tpl_array['{company}'] = $g_params->Get('site', 'site_company');
  $tpl_array['{year}'] = $g_params->Get('site', 'site_year');
    $site_year = $g_params->Get('site', 'site_year');
    $curr_year = substr(date("Ymd"),0,4);
    if ($site_year == $curr_year) {
    	$tpl_array['{year_foot}'] = $curr_year;
    } else {
    	$tpl_array['{year_foot}'] = $site_year.'-'.substr($curr_year,2,2);
    }
  // $tpl_subcateg_array['{subcategories}'] = $tpl_sub_menu;
    
?>
