<?php
  
  $tmp_tpl = new TemplateClass(); 
  $toTemplate = array();
    
  if (isset($_GET['p']) ) {
    $page = (int) $_GET['p'];
  } else {  $page = 1; }
  
  // search sites
  $tSites = array();
  if (isset($_GET['words'])) {
    $tSites = $g_site->SearchActive($_GET['words']);
  } else { $_GET['words'] = ''; }
  
  $toTemplateFirst = array();
  $toTemplate = array();   
  $first = true; 
  
  foreach ($tSites as $site) {

    if ($site['featured']) {
      $tdClass = "featured";
    } else {
      $tdClass = "normal";
    }
    
    $edit_address = 'index.php?mod=links&inc=edit&id='. $site['id'];
    $toTemplate[] =  array(
        '{featured/normal}' => $tdClass,
        '{description}' => $site['description'],
        '{edit_address}' => $edit_address,
        '{broken}' => LANG_BROKEN,
        '{title}'=> $site['title'], 
        '{address}'=> $site['url'], 
        '{id}'=> $site['id'],
        '{site_url}'=> $site['url'] );
    }

  $tpl_tmp['{words}'] = $_GET['words'];
  $tpl_tmp['{home}'] = $g_params->Get('site','site_name');
    
  $tmp_tpl->SetTemplate(ADMIN_TEMPLATE_DIR.'links_search_active.html');      
  $tmp_tpl->ReplaceIn($tpl_tmp);
  $tmp_tpl->FillRowsV2('row sites', $toTemplate);
 
  $tpl_main['{content}'] = $tmp_tpl->Get();
   
?>
