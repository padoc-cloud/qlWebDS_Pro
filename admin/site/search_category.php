<?php

  $tmp_tpl = new TemplateClass(); 
  
  $toTemplateFirst = array();
  $toTemplate = array();
  $tCategs = false;
  
  if (isset($_GET['p']) ) {
    $page = (int) $_GET['p'];
  } else { $page = 1; }
  
  // search categories
  if (isset($_GET['words'])) {
	$tCategs = $g_categ->SearchCategories($_GET['words']);
  } else { $_GET['words'] = ''; }
    
  if ($tCategs) {
	// top of category
	$parent_categ_link = '<a href="index.php?mod=site&amp;inc=categories">Top</a> ';
	
  	foreach ($tCategs as $categ) {
	      
	    $is_up = true;
	    $current = $categ;
	  	$parent_categ = '';
	    $categ_link = '';
		$i = 0;	        
	    while ($is_up) {
	    	if ($current['id_up']>0) {
	    		$parent_categ = $g_categ->GetCategory($current['id_up']);
	    	} else {
	    		$is_up = false;
	    	}
	    	if ($i<>0) {
		    	$categ_link = ' &raquo; <a href="index.php?mod=site&amp;inc=categories&amp;id_up='.$current['id'].'">'.$current['name'].'</a>' . $categ_link;
	    	}
	    	$current = $parent_categ;
	    	$i++; 
	    }
	  	
	  	$toTemplate[] = array(
	      '{category_name}'=>$categ['name'],
	      '{parent_cat_path}'=>$parent_categ_link.$categ_link
	    );
	  }
  }
  
  $tpl_tmp['{words}'] = $_GET['words'];
  
  $tmp_tpl->SetTemplate(ADMIN_TEMPLATE_DIR.'site_search_category.html');      
  $tmp_tpl->ReplaceIn($tpl_tmp);
  $tmp_tpl->FillRowsV2('row category', $toTemplate);
 
  $tpl_main['{content}'] = $tmp_tpl->Get();
  
?>
