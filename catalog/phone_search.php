<?php

  $tmp_tpl = new TemplateClass(); 
  
  // search sites
  $tSites = $g_site->UserPhoneSearch($_GET['number']);
  
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
    	'{tel}'=>$site['tel'],
        '{id}'=> $site['id'] );
  }

  $tpl_tmp['{number}'] = $_GET['number'];
  $tpl_tmp['{home}'] = $g_params->Get('site','site_name');
    
  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'phone_search.tpl.php');      
  $tmp_tpl->ReplaceIn($tpl_tmp);
  $tmp_tpl->FillRowsV2('row sites', $toTemplate);
  $tpl_main = $tmp_tpl->Get();  

?>
