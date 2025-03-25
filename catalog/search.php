<?php

  $tmp_tpl = new TemplateClass(); 
  
  if (isset($_GET['p']) ) {
    $page = (int) $_GET['p'];
  } else {  $page = 1; }
  
  // search categories
  $tCategs = $g_site->UserSearchCategs($_GET['words']);
  $toTemplateC = array();
  $toTemplateC_tmp = array();
  foreach ($tCategs as $categ) {
      
      if (MOD_REWRITE) {
        $categ_address = SITE_ADDRESS.$g_categ->Mod_Rewrite_url($categ['id']);
      } else {
        $categ_address = 'index.php?'.$categ['mod_rewrite'].'&amp;categ='.$categ['id'];
      }
        
      $toTemplateC_tmp[] = array(
      '{category_url}'=>$categ_address,
      '{category_name}'=>$categ['name'],
      );
  }
  
  // get rid of duplicates
  $toTemplateC = array_unique_custom($toTemplateC_tmp);
  
  // search sites
  $tSites = $g_site->UserSearch($_GET['words']);
  
  $toTemplateFirst = array();
  $toTemplate = array();   
  $first = true; 
  
  foreach ($tSites as $site) {
  
    if ($site['featured']) {
      $tdClass = "featured";
    } else {
      $tdClass = "normal";
    }
    
    if (MOD_REWRITE) {
      $more_address = str_replace(',',WORD_SEPARATOR,$site['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$site['id'].PAGE_EXTENSION;
    } else {
      $more_address = 'index.php?'.$site['mod_rewrite'].'&amp;site='.$site['id'];
    }
    
    $toTemplate[] =  array(
        '{featured/normal}' => $tdClass,
        '{description}' => $site['description'],
        '{more address}' => $more_address,
        '{broken}' => LANG_BROKEN,
        '{title}'=> $site['title'],
        '{company}'=> $site['company'],
        '{address}'=> $site['url'],
        '{id}'=> $site['id'] );
  }

  $tpl_tmp['{words}'] = $_GET['words'];
  $tpl_tmp['{home}'] = $g_params->Get('site','site_name');
    
  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'search.tpl.php');      
  $tmp_tpl->ReplaceIn($tpl_tmp);
  $tmp_tpl->FillRowsV2('row sites', $toTemplate);
  $tmp_tpl->FillRowsV2('row categories', $toTemplateC);    
  // $tmp_tpl->IfRegion( $if_region );
  $tpl_main = $tmp_tpl->Get();  
  
  function array_unique_custom($input_arr) {
	  $tmp_unique_arr = array();
	  $tmp_str_arr = array();
	  
	  // change input array's elemnts, which are arrays, to strings
	  // to be able to use array_unique function
	  foreach ($input_arr as $tmp_arr) {
		$tmp_str = implode('***',$tmp_arr);
		$tmp_str_arr[] = $tmp_str; 
	  }
	  
	  // delete duplicates
	  $tmp_str_arr = array_unique($tmp_str_arr);
	  
	  // get keys of unique elements
	  $tmp_keys_arr = array_keys($tmp_str_arr);
	  
	  // create array with unique elements
	  foreach ($tmp_keys_arr as $tmp_key) {
	  	$tmp_unique_arr[] = $input_arr[$tmp_key];
	  }
	  
	  return $tmp_unique_arr;
  }
  
?>
