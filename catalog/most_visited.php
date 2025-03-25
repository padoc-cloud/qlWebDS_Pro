<?php
    
    // get sites
    $tSites = $g_site->MostVisited(LINK_TYPES + 1,SITES_PER_PAGE);

    $toTemplate = array();

    $i=0;
    
    foreach ($tSites as $site) {
    
      if ($site['featured']) {
        $tdClass = "featured";
      } else {
        $tdClass = "normal";
      }
      
      // site info address
      if (MOD_REWRITE) {
        $more_address = str_replace(',',WORD_SEPARATOR,$site['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$site['id'].PAGE_EXTENSION;
      } else {
        $more_address = 'index.php?'.$site['mod_rewrite'].'&amp;site='.$site['id'];
      }
      
      $company_disp = '';
      if(trim($site['company'])=='') {
      	$company_disp = 'do_not_disp';
      }
      $addr_disp = '';
      if(trim($site['address'])=='') {
      	$addr_disp = 'do_not_disp';
      }
      $tel_disp = '';
      if(trim($site['tel'])=='') {
      	$tel_disp = 'do_not_disp';
      }
      $fax_disp = '';
      if(trim($site['fax'])=='') {
      	$fax_disp = 'do_not_disp';
      }
      $tel_fax_disp = '';
      if(trim($site['tel'])=='' and trim($site['fax'])=='') {
      	$tel_fax_disp = 'do_not_disp';
      }
      $url_disp = '';
      $image_disp = '';
      $title_only_disp = 'do_not_disp';
      $detail_disp = 'do_not_disp';
      if(trim($site['url'])=='') {
      	$url_disp = 'do_not_disp';
      	$image_disp = 'do_not_disp';
        $title_only_disp = 'title';
        $detail_disp = '';
      }
      
      $toTemplate[$i] =  array(
          '{featured/normal}' => $tdClass,
          '{description}' => $site['description'],
          '{more address}' => $more_address,
          '{broken}' => LANG_BROKEN,
          '{title}'=> $site['title'], 
          '{address}'=> $site['url'], 
          '{id}'=> $site['id'] ,
          '{company}'=> $site['company'],
          '{addr}'=> $site['address'],
          '{city}'=> $site['city'],
          '{state}'=> $site['state'],
          '{zip}'=> $site['zip'],
          '{country}'=> $site['country'],
          '{tel}'=> $site['tel'],
          '{fax}'=> $site['fax'],
      	  '{visit_count}' => $site['visit_count'],
		  '{home_url}'=> $g_params->Get('site','site_address'),
          '{company_disp}'=> $company_disp,
          '{addr_disp}'=> $addr_disp,
          '{tel_disp}'=> $tel_disp,
          '{fax_disp}'=> $fax_disp,
          '{tel_fax_disp}'=> $tel_fax_disp,
          '{url_disp}'=> $url_disp,
          '{image_disp}'=> $image_disp,
          '{title_only_disp}'=> $title_only_disp,
          '{detail_disp}' => $detail_disp
          );
          
        $i++;           
    }
      
    $tmpTemplate['{lang title}'] = LANG_MOST_VISITED_TITLE;
    $tmpTemplate['{lang visits}'] = LANG_VISITS;
    
    // templates
    $g_template->SetTemplate(DIR_TEMPLATE.'most_visited.tpl.php');
	$g_template->ReplaceIn($tmpTemplate);
    $g_template->FillRowsV2('row sites', $toTemplate);

    $tpl_main = $g_template->Get();   
?>
