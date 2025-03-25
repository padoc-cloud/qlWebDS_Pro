<?php
  
/*
  $cur_cat_id - from index.php
  $cur_cat_row from index.php
  $sort_page from index.php
*/

    // sites in top category
    if (!LINK_ON_TOP_CATEG and $cur_cat_row['level']==1) {
      $if_region['allow_add_site'] = 0;
      
    } else {
      $if_region['allow_add_site'] = 1;
    }
    
    // pagging
    $page = 1;

    if (empty($sort_page) === false) {
  		$sort_page_arr = explode('-',$sort_page);
		$p_key = 0;
	
	  	// i.e. 2-page, 2-page-1-sort
	  	if ((count($sort_page_arr) == 2) or (count($sort_page_arr) == 4)) {
	  		$p_key = 1;
	  		$p_value= 0;
	  	}
	  	if (($p_key <> 0) and ($sort_page_arr[$p_key] == 'page')) {
	  		$page = (int)$sort_page_arr[$p_value];
	  	}
    }
        
    $hmany = $g_site->CountSitesInCateg($cur_cat_id);
    $hmany =  ceil($hmany / SITES_PER_PAGE);
    if (!is_int($page) or ($page > $hmany)) {
    	$page = 1;
    }
    $pagging = ''; 
    
        if ($i = strpos($g_addr,'&amp;pg=')) {
          $g_addr = substr($g_addr,0,$i);
        }
        
        // pagging links          
        switch ($sort_by) {
      	  	case 'date':
      	  		$sorting_add = '';
      	  		break;
      	  	case 'name':
      	  		$sorting_add = $sorting_add.'1';
      	  		break;
      	  	case 'title':
      	  		$sorting_add = $sorting_add.'2';
      	  		break;
      	  	default:
      	  		$sorting_add = '';
      	}
        if (MOD_REWRITE) {
        	if (!empty($sorting_add)) {
		    	$sorting_add = '-'.$sorting_add.'-sort';
        	}
      	}
	    else {
	    	$sorting_add = '&amp;sort='.$sorting_add;
	    }
	    $cat_mod_rewrite_url = $g_categ->Mod_Rewrite_url($cur_cat_row['id']);
        for($i=1;$i<=$hmany;$i++) {
  
          if ($page==$i) {
                  
            $pagging .= " [$i] "; 
          
          } else if ($i==1) {
                    
            if (MOD_REWRITE) {
              $pagging .= '  <a href="'.SITE_ADDRESS.$cat_mod_rewrite_url.$sorting_add.'"><u>'.$i.'</u></a>'  ;
            } else {
              $pagging .= '  <a href="index.php?'.$cur_cat_row['mod_rewrite'].'&amp;categ='.$cur_cat_row['id'].$sorting_add.'"><u>'.$i.'</u></a>' ;
            }         
                    
          } else {
                  
            if (MOD_REWRITE) {
              $pagging .= '  <a href="'.SITE_ADDRESS.$cat_mod_rewrite_url.$i.'-page'.$sorting_add.'"><u>'.$i.'</u></a>'  ;
            } else {
              $pagging .= '  <a href="index.php?'.$cur_cat_row['mod_rewrite'].'&amp;categ='.$cur_cat_row['id'].'&amp;pg='.$i.$sorting_add.'"><u>'.$i.'</u></a>' ;
            }         
            // $pagging .= '<a href="'.$g_addr.'&amp;pg='.$i.'" class="ql_pagging">'.$i.'</a> ';
          }
        }
        $site_tpl['{pagging}'] = $pagging;
          
    // admin stuff
    if ($g_user->Level()==AL_ADMIN) {
    
      $del_cache = false;
      
      // delete site
      if (isset($_GET['del_site'])) {
        $id = (int) $_GET['del_site'];
        
        $g_site->DeleteSite($id);
        
        unset($_GET['del_site']);
        $del_cache = true;
      }
      
      // delete from category
      if (isset($_GET['del_from_categ'])) {
        $id = (int) $_GET['del_from_categ'];
        
        $g_site->DeleteConnection($id, $cur_cat_id);
        
        unset($_GET['del_from_categ']);
        $del_cache = true;
      }
      
      // ban site
      if (isset($_GET['ban_site'])) {
        $id = (int) $_GET['ban_site'];
        
        // $g_sites->
        unset($_GET['ban_site']);
        $del_cache = true;
      }
      
      // make new cache address
      foreach ($_GET as $key=>$value) {
        $new_addr[] = "$key=$value";
      }
      $g_cache_addr = implode('&amp;', $new_addr);
      
      // delete cache    
      if ($del_cache) { $g_cache->Delete($g_cache_addr); }
      
    }  
    
    // get sites
    $tSites = $g_site->GetSites($cur_cat_id, $page, $sort_by) ;

    // if no sites don't show sites region
    if (count($tSites)==0 ) {
      $if_region['no_pages'] = 1;
      $if_region['view_sites'] = 0;
    } else { 
      $if_region['no_pages'] = 0;
      $if_region['view_sites'] = 1;       
    }
    
    $toTemplateFirst = array();
    $toTemplate = array();

    $first = true; 
  
    $first_normal = true;
    $i=0;  
    foreach ($tSites as $site) {
    
      if ($site['featured']) {
        $tdClass = "featured";
        $sort_disp = 'do_not_disp';
      } else {
        $tdClass = "normal";
        if($first_normal) {
        	$sort_disp = 'disp';
        	$first_normal = false;
        } else {
        	$sort_disp = 'do_not_disp';
        }
      }
      
      // site info address
      if (MOD_REWRITE) {
        $more_address = SITE_ADDRESS.str_replace(',',WORD_SEPARATOR,$site['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$site['id'].PAGE_EXTENSION;
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
      // first site
      if ($first) {
        $first = false;
  
        $toTemplateFirst[0] =  array(
          '{featured/normal}' => $tdClass,
          '{description_short}' => $site['description_short'],
          '{description}' => $site['description'],
          '{facebook_url}' => $site['facebook_url'],
          '{facebook_url}' => $facebook_url_disp,
          '{twitter_url}' => $site['twitter_url'],
          '{twitter_url}' => $twitter_url_disp,
          '{youtube_url}' => $site['youtube_url'],
          '{youtube_url}' => $youtube_url_disp,
          '{embedded_video_title}' => $site['embedded_video_title'],
          '{embedded_video_title_disp}' => $embedded_video_title_disp,
          '{embedded_video_code}' => $site['embedded_video_code'],
          '{embedded_video_code_disp}' => $embedded_video_code_disp,
          '{more address}' => $more_address,
          '{broken}' => LANG_BROKEN,
          '{title}'=> $site['title'], 
          '{address}'=> $site['url'], 
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
          '{sort_disp}'=> $sort_disp,
          '{title_only_disp}'=> $title_only_disp,
          '{detail_disp}' => $detail_disp,
          '{admin menu site}' => '' );
          
        // if admin logged
        if ($g_user->Level()==AL_ADMIN) { 
            $toTemplateFirst[0]['{admin menu site}'] = '<p class="admin_p"><strong>Admin Menu:</strong><br>
              <a href="'.SITE_ADDRESS.'index.php?'.$g_cache_addr.'&amp;del_site='.$site['id'].'" class="link_admin" onClick="return confirmDelete()"><u>'.LANG_DELETE_SITE.'</u></a> &nbsp;|&nbsp; 
              <a href="'.SITE_ADDRESS.'index.php?'.$g_cache_addr.'&amp;del_from_categ='.$site['id'].'" class="link_admin" onClick="return confirmDelete()"><u>'.LANG_DELETE_FROM_CATEG.'</u></a><br>
              <a href="'.SITE_ADDRESS.'admin/index.php?mod=links&inc=edit&id='.$site['id'].'" class="link_admin"><u>'.LANG_EDIT.'</u></a> &nbsp;|&nbsp; 
              <a href="'.SITE_ADDRESS.'index.php?'.$g_cache_addr.'&amp;ban_site='.$site['id'].'" class="link_admin"><u>'.LANG_BAN.'</u></a></p>';
        }        

      // rest of the sites
      } else {

        $toTemplate[$i] =  array(
          '{featured/normal}' => $tdClass,
          '{description_short}' => $site['description_short'],
          '{description}' => $site['description'],
          '{facebook_url}' => $site['facebook_url'],
          '{facebook_url}' => $facebook_url_disp,
          '{twitter_url}' => $site['twitter_url'],
          '{twitter_url}' => $twitter_url_disp,
          '{youtube_url}' => $site['youtube_url'],
          '{youtube_url}' => $youtube_url_disp,
          '{embedded_video_title}' => $site['embedded_video_title'],
          '{embedded_video_title}' => $embedded_video_title_disp,
          '{embedded_video_code}' => $site['embedded_video_code'],
          '{embedded_video_code}' => $embedded_video_code_disp,
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
          '{company_disp}'=> $company_disp,
          '{addr_disp}'=> $addr_disp,
          '{tel_disp}'=> $tel_disp,
          '{fax_disp}'=> $fax_disp,
          '{tel_fax_disp}'=> $tel_fax_disp,
          '{url_disp}'=> $url_disp,
          '{image_disp}'=> $image_disp,
          '{sort_disp}'=> $sort_disp,
          '{title_only_disp}'=> $title_only_disp,
          '{detail_disp}' => $detail_disp,
          '{admin menu site}' => '' );
          
        // if admin logged
        if ($g_user->Level()==AL_ADMIN) { 
            $toTemplate[$i]['{admin menu site}'] = '<p class="admin_p"><strong>Admin Menu:</strong><br>
              <a href="'.SITE_ADDRESS.'index.php?'.$g_cache_addr.'&amp;del_site='.$site['id'].'" class="link_admin" onClick="return confirmDelete()"><u>'.LANG_DELETE_SITE.'</u></a> &nbsp;|&nbsp; 
              <a href="'.SITE_ADDRESS.'index.php?'.$g_cache_addr.'&amp;del_from_categ='.$site['id'].'" class="link_admin" onClick="return confirmDelete()"><u>'.LANG_DELETE_FROM_CATEG.'</u></a><br>
              <a href="'.SITE_ADDRESS.'admin/index.php?mod=links&inc=edit&id='.$site['id'].'" class="link_admin"><u>'.LANG_EDIT.'</u></a> &nbsp;|&nbsp; 
              <a href="'.SITE_ADDRESS.'index.php?'.$g_cache_addr.'&amp;ban_site='.$site['id'].'" class="link_admin"><u>'.LANG_BAN.'</u></a></p>';
        }          
    
        $i++;           
      }
      
    }
    
    // language 
    $tpl_subcateg_array['{lang page}'] = LANG_PAGE;
    $tpl_subcateg_array['{lang be_first}'] = LANG_BE_FIRST;

    // advertisement after first entry 
    $tpl_subcateg_array['{ad}'] = $g_params->Get('ads', 'after_first');
    $tpl_subcateg_array['{pagging}'] = $pagging;
    
    if ($toTemplateFirst and strlen($tpl_subcateg_array['{ad}'])>0) { $if_region['ad_no1'] = 1; } else { $if_region['ad_no1'] = 0; }
    
    // templates
    $tpl_site_first = $toTemplateFirst;   
    $tpl_sites = $toTemplate; 

?>
