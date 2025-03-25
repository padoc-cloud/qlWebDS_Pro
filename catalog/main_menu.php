<?php

  // Main page item
  // get template
  $main_tpl = new TemplateClass(); 
  $main_tpl->SetTemplate(DIR_TEMPLATE.'main_menu.tpl.php');
           
  $toTemplate = array();

  // 0 means top level categories  
  $categs = $g_categ->GetCategories(0);
    
  foreach ($categs as $categ) {

    // $subcategT = $g_categ->GetCategories($categ['id'], SUBS_ON_MAIN_PAGE);
    $subcategT = $g_categ->GetCategories($categ['id']);
    $hmany = 0;
    $subs = '';
    $aSub = array();
    
    foreach ($subcategT as $row) {
      
      if ($hmany < SUBS_ON_MAIN_PAGE) {
        
        if (MOD_REWRITE) {
          $aSub[$hmany]['{sub_url}'] = SITE_ADDRESS.$g_categ->Mod_Rewrite_url($row['id']);
        } else {
          $aSub[$hmany]['{sub_url}'] = 'index.php?'.$row['mod_rewrite'].'&amp;categ='.$row['id'];
        }        
        
        $aSub[$hmany]['{sub_name}'] = $row['name'];       
        
        $hmany ++; 
      } else {
        break;
      }
     
    }
    // add [list more] link if needed
    if (count($subcategT)>SUBS_ON_MAIN_PAGE) {
        if (MOD_REWRITE) {
          $aSub[$hmany]['{sub_url}'] = SITE_ADDRESS.$g_categ->Mod_Rewrite_url($categ['id']);
        } else {
          $aSub[$hmany]['{sub_url}'] = 'index.php?'.$categ['mod_rewrite'].'&amp;categ='.$categ['id'];
        }        
        
        $aSub[$hmany]['{sub_name}'] = '[list more]';       
    }

    // make subcategories
    if (count($aSub)>0) {
      $sub_tpl = new TemplateClass();
      $sub_tpl->SetTemplate(DIR_TEMPLATE.'main_menu_sub_rows.tpl.php');  
      $sub_tpl->FillRowsV2('row subcategories', $aSub);
      $subs = $sub_tpl->Get();
    } 
    
    if (MOD_REWRITE) {
      $categ_link = '<a href="'.SITE_ADDRESS.$g_categ->Mod_Rewrite_url($categ['id']).'" class="link1">'.$categ['name'].'</a>' ;
    } else {
      $categ_link = '<a href="index.php?'.$categ['mod_rewrite'].'&amp;categ='.$categ['id'].'" class="link1">'.$categ['name'].'</a>';
    }    
    
    $toTemplate[] =  array('{main categ}'=>$categ_link, '{subbcategs}'=>$subs ) ;
  } 

  // latest featured links
  $toTemplateFeatLatest = array();
  $latest = $g_site->LatestFeatured();
      
  foreach ($latest as $row) {
      
    // site info address        
    if (MOD_REWRITE) {
      $more_address = str_replace(',',WORD_SEPARATOR,$row['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$row['id'].PAGE_EXTENSION;
    } else {
      $more_address = 'index.php?'.$row['mod_rewrite'].'&amp;site='.$row['id'];
    }

    if(trim($row['url'])=='') {
    	$toTemplateFeatLatest[] =  array(
    		'{featured_image}'=>'do_not_disp',
	    	'{featured_verbiage}'=>'Details',
    		'{featured_link}'=>$row['title'],
    		'{featured_url}'=>$more_address,
    		'{info_url}'=>$more_address,
    	    '{id}'=>$row['id'],
    	    '{home_url}'=> $g_params->Get('site','site_address'),
    	    '{title}'=>(substr($row['title'],0,20).'...')
    	);
    } else {
    	$toTemplateFeatLatest[] =  array(
	        '{featured_image}'=>'',
	        '{featured_verbiage}'=>'Statistics',
    		'{featured_link}'=>$row['url'],
    		'{featured_url}'=>$row['url'],
    		'{info_url}'=>$more_address,
    	    '{id}'=>$row['id'],
		    '{home_url}'=> $g_params->Get('site','site_address'),
    	    '{title}'=>(substr($row['title'],0,20).'...')
    	);
    }
    
  } 
  
  if (count($toTemplateFeatLatest)>0) { $tmpIf['latest_feat_links'] =1 ;}
  else { $tmpIf['latest_feat_links'] = 0 ;}
    
  // latest links
  $toTemplateLatest = array();
  $latest = $g_site->Latest();
  
  foreach ($latest as $row) {
  
    // site info address    
    if (MOD_REWRITE) {
      $more_address = str_replace(',',WORD_SEPARATOR,$row['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$row['id'].PAGE_EXTENSION;
    } else {
      $more_address = 'index.php?'.$row['mod_rewrite'].'&amp;site='.$row['id'];
    }
      
    $toTemplateLatest[] =  array('{info_url}'=>$more_address, '{title}'=>$row['title'], '{short_description}'=> $row['description']);
  }

  $tmp_tpl['{site_main_ads}'] = $g_params->Get('ads', 'site_main');
  $tmp_tpl['{lang latest_links}'] = LANG_LATEST_LINKS;
  $tmp_tpl['{lang latest_featured_links}'] = LANG_LATEST_FEATURED_LINKS;
    
  if (count($toTemplateLatest)>0) { $tmpIf['latest_links'] =1 ;}
  else { $tmpIf['latest_links'] = 0 ;}
  
  // advertisement
  $main_tpl->ReplaceIn($tmp_tpl);
  $main_tpl->FillRowsV2('row rows', $toTemplate);
  $main_tpl->FillRowsV2('row latest', $toTemplateLatest);
  $main_tpl->FillRowsV2('row featured_latest', $toTemplateFeatLatest);    
  $main_tpl->IfRegion($tmpIf);  
  $tpl_mmenu = $main_tpl->Get();
 
?>
