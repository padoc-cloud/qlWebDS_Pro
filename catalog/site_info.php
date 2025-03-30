<?php
  
  $site_id = (int) $_GET['site'];
  
  $categories = $g_categ->GetSiteCategories($site_id);
  $site = $g_site->GetSiteById($site_id);
  
  if ($g_user->Level()!=AL_ADMIN) if ($site['status']!=SITE_VIEW)  {  header("location: 404.php");   }  
  
  // get templates
  $tmp_tpl = new TemplateClass(); 
  $tmp_tpl->SetTemplate(DIR_TEMPLATE.'site_info.tpl.php');
    
    if ($site['link_type'] >= LT_FEAT) {
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

      $facebook_url_disp = '';
      if(trim($site['facebook_url'])=='') {
      	$facebook_url_disp = 'do_not_disp';
      }
      $twitter_url_disp = '';
      if(trim($site['twitter_url'])=='') {
      	$twitter_url_disp = 'do_not_disp';
      }
      $youtube_url_disp = '';
      if(trim($site['youtube_url'])=='') {
      	$youtube_url_disp = 'do_not_disp';
      }
      $embedded_video_title_disp = '';
      if(trim($site['embedded_video_title'])=='') {
      	$embedded_video_title_disp = 'do_not_disp';
      }
      $embedded_video_code_disp = '';
      if(trim($site['embedded_video_code'])=='') {
      	$embedded_video_code_disp = 'do_not_disp';
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
    
    // nofollow
    if ($site['nofollow'] == 1) {
      $nofollow = 'rel="nofollow"';
    } else {
      $nofollow = '';
    }
    
	$url_val = $site['url'];
	$url_disp = '';
	$title_only_disp = 'do_not_disp';
      
    if(trim($site['url'])=='') {
    	$url_val = 'none';
      	$url_disp = 'do_not_disp';
        $title_only_disp = 'title';
	}

	$company_disp = '';
	if(trim($site['company'])=='') {
		$company_disp = 'do_not_disp';
	}
      
	$product_disp = '';
	if(trim($site['product'])=='') {
		$product_disp = 'do_not_disp';
	}
	
	$toTemplate =  array(
      '{featured/normal}' => $tdClass,
      '{description_short}' => $site['description_short'],
      '{description_short_disp}' => $description_short_disp,
      '{description}' => $site['description'],
      '{facebook_url}' => $site['facebook_url'],
      '{facebook_url_disp}'=> $facebook_url_disp,
      '{twitter_url}' => $site['twitter_url'],
      '{twitter_url_disp}'=> $twitter_url_disp,
      '{youtube_url}' => $site['youtube_url'],
      '{youtube_url_disp}'=> $youtube_url_disp,
      '{embedded_video_title}' => $site['embedded_video_title'],
      '{embedded_video_title_disp}'=> $embedded_video_title_disp,
      '{embedded_video_code}' => $site['embedded_video_code'],
      '{embedded_video_code_disp}'=> $embedded_video_code_disp,
      '{more address}' => $more_address,
      '{broken}' => LANG_BROKEN,
      '{title}'=> $site['title'],
      '{address}'=> $url_val,
      '{keywords}'=> $site['keywords'], 
      '{id}'=> $site['id'],
      '{company}'=> $site['company'],
      '{addr}'=> $site['address'],
      '{city}'=> $site['city'],
      '{state}'=> $site['state'],
      '{zip}'=> $site['zip'],
      '{country}'=> $site['country'],
      '{tel}'=> $site['tel'],
      '{fax}'=> $site['fax'],
      '{company_disp}'=> $company_disp,
      '{addr_disp}'=> $addr_disp,
      '{tel_disp}'=> $tel_disp,
      '{fax_disp}'=> $fax_disp,
      '{tel_fax_disp}'=> $tel_fax_disp,
      '{url_disp}'=> $url_disp,
      '{image_disp}'=> $image_disp,
      '{title_only_disp}'=> $title_only_disp,
      '{detail_disp}' => $detail_disp,
      '{visit_count}'=> $site['visit_count'],
      '{date}' => $site['date'],
      '{nofollow}' => $nofollow, 
      '{product}'=> $site['product'],
      '{product_disp}'=> $product_disp,
      '{lang comp_name}' => LANG_COMP_NAME,
      '{lang prod_name}' => LANG_PROD_NAME,
      '{lang visits}' => LANG_VISITS,

      '{home}' => $g_params->Get('site','site_name'),
	  '{home_url}' => $g_params->Get('site','site_address'),
      '{lang url}' => LANG_URL,
      '{lang title}' => LANG_TITLE,
      '{lang description_site_info}' => LANG_DESCRIPTION,
      '{lang description_short}' => LANG_DESCR_SHORT,       
      '{lang description}' => LANG_DESCR,
      '{lang facebook_url}' => LANG_FACEBOOK_URL,
      '{lang twitter_url}' => LANG_TWITTER_URL,
      '{lang youtube_url}' => LANG_YOUTUBE_URL,
      '{lang video_title}' => LANG_VIDEO_TITLE,
      '{lang categories}' => LANG_IN_CATEGS,
      '{lang added}' => LANG_SITE_ADDED,
      '{lang deep links}' => LANG_DEEP_LINKS, 
      '{lang keywords}' => LANG_KEYW,
      '{lang id}' => LANG_ID,	
    );  
    
    // collect PageRank
    if (COLLECT_PAGE_RANK) {
      $pr = $site['pr'];
      
      if ($pr<0) {
      
        include('catalog/pr.php');
        //$pr = getrank($site['url']);
        $pr = pagerank($site['url']);
        if (is_numeric($pr) and $pr >0) {
        
          $values['pr'] = $pr;
          $g_site->UpdateSite($values, $site_id);
          $toTemplate['{pagerank}'] = 'PageRank: '.$pr;          
      
        } else {
                  
          $values['pr'] = 0;
          $g_site->UpdateSite($values, $site_id);
          $toTemplate['{pagerank}'] = 'PageRank: '.$values['pr'];
        }
      
      } else {
        $toTemplate['{pagerank}'] = 'PageRank: '.$site['pr'];
      }
    } else {
        $toTemplate['{pagerank}'] = 'PageRank: N/A';    
    }
    
    // manage deep links
    $hm_deep_links = 0;
    $deep_links = '';
    
    if ($site['link_type'] == LT_ADD3)  { $hm_deep_links = 3; }
    else if ($site['link_type'] == LT_ADD5)  { $hm_deep_links = 5; }
    else {
    	$deep_links = ' ';
    	$toTemplate['{lang deep links}'] = ' ';
    }
    
    for ($i=1; $i <= $hm_deep_links; $i++) {
    
      if ( strlen($site['url'.$i])>0 ) { 
        $deep_links .= ' - <a href="'.$site['url'.$i].'" target="_blank"><u>'.$site['title'.$i].'</u></a>';
      }      
      
    }
    
    if (strlen($deep_links)==0)  {$deep_links = LANG_NONE; }
    $toTemplate['{deep links}'] = $deep_links;
        
    // categories
    $categories_in = '';
    foreach ($categories as $category) {
    
      if (MOD_REWRITE) {
        $categ_address = SITE_ADDRESS.$g_categ->Mod_Rewrite_url($category['sub_id']);
      } else {
        $categ_address = 'index.php?'.$category['mod_rewrite'].'&amp;categ='.$category['sub_id'];
      }
      $categories_in .= ' - <a href="'.$categ_address.'"><u>'.$category['name'].'</u></a>';
      
    }
    $toTemplate['{categories}'] = $categories_in;
    
    // advertisements
    $toTemplate['{site_info_ads}'] = $g_params->Get('ads', 'site_info');

    // admin menu
    if ($g_user->Level() == AL_ADMIN) {
      $toTemplate['{admin menu}'] = '<p class="admin_p"><strong>Admin Menu: Admin Logged In</strong></p>';
      $bIsAdmin = 1;
    } else {
      $toTemplate['{admin menu}'] = "";
      $bIsAdmin = 0;
    }

	$tpl_array['{google_analytics}'] = $g_params->Get('site', 'site_google_analytics');
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
    
    // display logo
    $logo_disp = 0;
    if (file_exists(LOGO_DIR."/".$site_id.LOGO_NAME_SUFFIX)) {
    	$logo_disp = 1;	
		$toTemplate['{logo_source}'] = LOGO_DIR."/".$site_id.LOGO_NAME_SUFFIX;
    }
    
    // count visit
    $g_site->CountVisit($site_id);
    
	$tmp_tpl->ReplaceIn($toTemplate);
    $tmp_tpl->IfRegion( array('isadmin'=>$bIsAdmin, 'show_pagerank'=>COLLECT_PAGE_RANK, 'logo_display'=>$logo_disp, 'video_display'=>$video_disp) );
    $tpl_main = $tmp_tpl->Get();

?>
