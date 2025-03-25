<?php

  // start session
  session_start();
  
  // debug performance check class 
  include('php4_classes/debug.class.php');
  $g_debug = new DebugClass;
  
  define ('LANG_DIR', './languages/');
  define ('THUMB_DIR', './thumbnails/');
  
  define ('MAIN_CATALOG', './');

  // initialize classes
  // filter POST data
  // makes $g_cache_addr
  require_once('head.php');

  // charset
  header("Content-Type: text/html; charset=".DEFAULT_CHARSET);   

  // check if admin URL field is filled (site address)
  if ( strlen($g_params->Get('site', 'site_address'))<5 ) {
    echo 'go to <a href="admin/">admin</a> and fill in correct Site Settings (Config->Site Settings)<br><a href="http://www.qlWebScripts.com" target="_blank"><u>qlWeb Scripts</u></a>';
    exit; 
  } 

  include('users_online.php');
  
  // charsert
  header("Content-Type: text/html; charset=".DEFAULT_CHARSET);   

  // main template array  
  $tpl_array = array();
  $tpl_array['{template}']= DIR_TEMPLATE;
  $main_if_region['main'] = 0;
  $main_if_region['random_site'] = 0; 
  $main_if_region['allow_users'] = 0;
  $main_if_region['user_logged_in'] = 0;
  $main_if_region['statistics'] = 0;
  $main_if_region['users_online'] = 0;
  $main_if_region['site_active'] = SITE_ACTIVE;
  // phone search section
  $main_if_region['phone_search'] = 0;
  if ($g_params->Get('site', 'phone_search')) {
	  $main_if_region['phone_search'] = 1;
  }
  
  $isMeta = false;
  $isInfoPage = false;

  $tpl_array['{home_url}']= SITE_ADDRESS;

  $sort_page = '';
  
  $listing_paid = false;
  
  // category menu  
  if (isset($_GET['category_path']) or isset($_GET['categ'])) {
  	
    $cur_cat_row = false;
    
  	if (isset($_GET['category_path'])) {
	    
	    if (!((substr($_GET['category_path'],-1) === '/') or
		    (substr($_GET['category_path'],-5) === '-sort') or
		    (substr($_GET['category_path'],-5) === '-page'))) {
		    	$_GET['category_path'] = $_GET['category_path'].'/';
		    }
	    
		// modrewrite using category path
		if ((substr($_GET['category_path'],-1) === '/') or
		    (substr($_GET['category_path'],-5) === '-sort') or
		    (substr($_GET['category_path'],-5) === '-page')) {
			$cat_name_arr = explode('/',$_GET['category_path']);
			$sort_page = array_pop($cat_name_arr);
			if (count($cat_name_arr) > 0) {
				$cat_id_up = 0;
				foreach($cat_name_arr as $cat_name) {
					$cat_name = str_replace('-',',',$cat_name);
					$cur_cat_row = $g_categ->GetCategoryByMod_Rewrite($cat_id_up, $cat_name);
					if (!$cur_cat_row) {
						break;
					}
					$cat_id_up = $cur_cat_row['id']; 
	      			$cur_cat_id = (int) $cur_cat_row['id'];
				}
			}
		}
  	} 
	if (isset($_GET['categ'])) {
	    if (is_numeric($_GET['categ'])) {
	      $cur_cat_id = (int) $_GET['categ'];
	      $cur_cat_row = $g_categ->GetCategory($cur_cat_id);
	    }
	}
	
	if (!$cur_cat_row) {

      include('404.php');
    	
    } else {
      $tpl_subcateg = array();
      
      if (($g_user->Level()==AL_NORMAL) and USE_CACHE and $g_cache->Get($g_cache_addr) ) {
        
        $g_template->SetTemplate($g_cache->Get($g_cache_addr));
        $tpl_main = $g_template->Get();
                  
      } else {
        
        $if_region = array();

		// fills $tpl_subcategories
        include('catalog/category.php');

		// fills $tpl_sites
        include('catalog/sites.php');
      
        $g_template->SetTemplate(DIR_TEMPLATE.'subcategory.tpl.php');
        $g_template->ReplaceIn($tpl_subcateg_array); 
        $g_template->FillRowsV2('row subcategories', $tpl_subcategories);
          
        $g_template->FillRowsV2('row site_first', $tpl_site_first);
        $g_template->FillRowsV2('row sites', $tpl_sites);

        $g_template->IfRegion( $if_region );
        
        $tpl_main = $g_template->Get();   

        // save cache
        if (($g_user->Level()==AL_NORMAL) and USE_CACHE) {
          $g_cache->Make($g_cache_addr, $tpl_main);
        }   
        
      }

      // category meta tags
      $tpl_array['{title}'] = $cur_cat_row['name'];
      $tpl_array['{description}'] = $cur_cat_row['description'];
      $tpl_array['{keywords}'] = $cur_cat_row['keywords'];     
      $isMeta = true;
      
    }

  // add category
  } else if (isset($_GET['addc'])) {

    $categ_id = (int) $_GET['addc'];

	// add category form
    include('catalog/add_categ.php');

  // add site
  } else if (isset($_GET['adds'])) {    

	// add site form
    include('catalog/add_site.php');

  // site info
  } else if (isset($_GET['site'])) {
  
    if (!is_numeric($_GET['site']) ) {  

    	include('404.php');
    	
    } else {
    	  
	    include('catalog/site_info.php');
	
	    // category meta tags
	    $tpl_array['{title}'] = $site['title'];
	    $tpl_array['{description}'] = $site['description'];
	    $tpl_array['{keywords}'] = $site['keywords'];      
	    $isMeta = true;
	    
    }

  // other sites
  } else if (isset($_GET['page'])) {
    
    $site = str_replace(array(".","%","\\","/"),'', $_GET['page']);
    
    switch ($site) {
  		case 'registration':
  		case 'login':
  		case 'logout':
  		case 'forgot_pass':
  		case 'user_account':
  		case 'user_account_edit':
  		case 'listing_edit':
  		case 'user_claim_listing':
  		case 'latest_listings':
  		case 'most_visited':
  			include('catalog/'.$site.'.php');
  			break;
  		case '404':
  			echo('in the 404 case');
		    include('404.php');
  			break;
  		default:
		    $p_params = $g_params->GetParams('pages');
		    $hmany_pages = 10;
		    $InfoPageTitle = '';
		    for ($i=1; $i<=$hmany_pages; $i++) {
		      	if($p_params['page_name_'.$i] == $site) {
		    		$InfoPageTitle = $p_params['page_title_'.$i];
		    		$p_index = $i;
		    		break; 
		      	}
		    }
		    if(empty($InfoPageTitle)) {
		        include('404.php');
		    } else {
	  			include('catalog/infos.php');
			    $isInfoPage = true;
		    }
    }
    
  // paypal IPN
  } else if (isset($_GET['o'])) {
    if (strpos($_GET['o'], array('.','%') !== false )) { exit; };
    $site = str_replace('.','', $_GET['o']);
    if (file_exists('catalog/'.$site.'.php') ) {
      include('catalog/'.$site.'.php');
    }

  // pay before submission - payment made
  } else if (isset($_GET['pay'])) {
    $p_id = $_GET['id'];
    $last_pay = $g_site->GetLastPayment($p_id);
    if ($last_pay['paid'] == PM_PAID) {
    	$listing_paid = true;
	    include('catalog/add_site.php');
    } else {
	  	$tmp_tpl = new TemplateClass(); 
      	$tmp_tpl->SetTemplate(DIR_TEMPLATE.'add_site_pay_failure.tpl.php'); 
  	  	$tpl_main = $tmp_tpl->Get();      
    }
  	
  // optional registration
  } else if (isset($_GET['registration'])) {
    include('catalog/registration.php');
    
  // main
  } else if ( count($_GET)==0 ) {
  
      $g_cache_addr = 'index';
      if (($g_user->Level()==AL_NORMAL) and USE_CACHE and $g_cache->Get($g_cache_addr) ) {
        
        $g_template->SetTemplate($g_cache->Get($g_cache_addr));
        $tpl_main = $g_template->Get();
                  
      } else {    

		// makes variable $tpl_mmenu      
        include('catalog/main_menu.php');
         
        $tpl_main = $tpl_mmenu;
        
        // save cache
        if (($g_user->Level()==AL_NORMAL) and USE_CACHE) {
          $g_cache->Make($g_cache_addr, $tpl_main);
        }  

      }
      
      $main_if_region['main'] = 1;
       
	  $tpl_array['{lang statistics}'] = LANG_STATISTICS;
	  $tpl_array['{lang users online title}'] = LANG_USERS_ONLINE_TITLE;
	  $tpl_array['{lang random site title}'] = LANG_RANDOM_SITE_TITLE;
	  
      // links statistics       
  	  if ($g_params->Get('site', 'statistics_on_main_page')) {
      
		  $main_if_region['statistics'] = 1;
      	
	      $tpl_array['{hmany_active}'] = $g_site->Count(1);
	      $tpl_array['{hmany_featured}'] = $g_site->Count(2);
	      $tpl_array['{hmany_pending}'] = $g_site->Count(3);
	      $tpl_array['{hmany_new_today}'] = $g_site->Count(4);
	      $tpl_array['{hmany_approved_today}'] = $g_site->Count(5);        
	      $tpl_array['{hmany_categ}'] = $g_categ->Count();
	        
	      $tpl_array['{lang hmany_active}'] = LANG_HMANY_ACTIVE;
	      $tpl_array['{lang hmany_featured}'] = LANG_HMANY_FEATURED;
	      $tpl_array['{lang hmany_pending}'] = LANG_HMANY_PENDING;
	      $tpl_array['{lang hmany_new_today}'] = LANG_HMANY_NEW_TODAY;
	      $tpl_array['{lang hmany_approved_today}'] = LANG_HMANY_APPROVED_TODAY;        
	      $tpl_array['{lang hmany_categ}'] = LANG_HMANY_CATEG;        
      }

      // users online
  	  if ($g_params->Get('site', 'users_online')) {
      
		  $main_if_region['users_online'] = 1;

	      $tpl_array['{lang hmany_users_online}'] = LANG_HMANY_USERS_ONLINE;
	      $tpl_array['{hmany_users_online}'] = $users_online;
	        
  	  }

      // phone search section
  	  if ($g_params->Get('site', 'phone_search')) {
		  $main_if_region['phone_search'] = 1;
  	  }
  	  
	  // random site on main
      if ($g_params->Get('site', 'random_site')) {
	  	$random_site_array = $g_site->GetRandomSite();
	  	
	  	if ($random_site_array) {
	    	$main_if_region['random_site'] = 1;
	    	
	    	
	  		foreach ($random_site_array as $rs_row) {
	  			
			    // site info address    
		    	if (MOD_REWRITE) {
		      		$more_address = str_replace(',',WORD_SEPARATOR,$rs_row['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$rs_row['id'].PAGE_EXTENSION;
			    } else {
		      		$more_address = 'index.php?'.$rs_row['mod_rewrite'].'&amp;site='.$rs_row['id'];
			    }
			    
			    $tpl_array['{random_site_url}'] = $rs_row['url'];
	  			$tpl_array['{random_site_info_url}'] = $more_address;
	  			$tpl_array['{random_site_title}'] = (substr($rs_row['title'],0,20).'...');
	  			$tpl_array['{random_site_id}'] = $rs_row['id'];		    
	  		}
	  	}
	    
	  }
      
  } 

  /*
  // latest featured links    
  $toTemplateLatestFeat = array();
  $latest = $g_site->LatestFeatured();
      
  foreach ($latest as $row) {
      
    // site info address
    if (MOD_REWRITE) {
      $more_address = $row['mod_rewrite'].','.MOD_SITE.'-'.$row['id'].'.htm';
    } else {
      $more_address = 'index.php?'.$row['mod_rewrite'].'&amp;site='.$row['id'];
    }
         
    $toTemplateLatestFeat[] =  array('{title}'=>$row['title'], '{url}'=>$row['url'] ) ;
  }
  */

  // user section
  if ($g_params->Get('for_user','allow_users')) {
  	$main_if_region['allow_users'] = 1;
  }
  if ($g_user->Level() == AL_USER) {
  	$main_if_region['allow_users'] = 0;
  	$main_if_region['user_logged_in'] = 1;
  	$logged_user = $g_user->GetUser();
  	$tpl_array['{user}'] =  $logged_user['user'];
  }

  // banner rotator
  $main_if_region['banner_rotator'] = $g_params->Get('banner', 'banner_active');
  if ($main_if_region['banner_rotator']) {
    
    for ($i=1; $i<=15; $i++) {
      if ( $g_params->Get('banner', 'banner_use_'.$i) ) {
        $banners[$i] = $i;
      }
    }
    
    srand((float) microtime() * 10000000);
    $rand_keys = array_rand($banners,1);
    
    if (isset($rand_keys) and strlen($g_params->Get('banner', 'banner_'.$rand_keys)) ) {
      $tpl_array['{banner_code}'] = $g_params->Get('banner', 'banner_'.$rand_keys);
    } else {
      $main_if_region['banner_rotator'] = 0;
    }
    
  } 

  // left menu - main categories
  $main_categs = $g_categ->GetCategories(0);
  $main_categ_links = array();
  
  if ($main_categs) {
    
    foreach ($main_categs as $key=>$categ ) {
      if (MOD_REWRITE) {
        $main_categ_links[] = array('{categ link}'=>'<a href="'.SITE_ADDRESS.$g_categ->Mod_Rewrite_url($categ['id']).'" >'.$categ['name'].'</a>');
      } else {
        $main_categ_links[] = array('{categ link}'=>'<a href="index.php?'.$categ['mod_rewrite'].',&amp;categ='.$categ['id'].'" >'.$categ['name'].'</a>');
      }
    }

  }

  // left block ads
  $tpl_array['{left_block}'] = $g_params->Get('ads', 'left_block');
  
  // right block ads
  $tpl_array['{right_block}'] = $g_params->Get('ads', 'right_block');
  
  // top block banner/logo
  $tpl_array['{top_block}'] = $g_params->Get('ads', 'top_block');

  // site home description
  $tpl_array['{site_home_description}'] = $g_params->Get('site', 'site_home_description');

  // fill rest of template
  // submit site link
  if (isset($cur_cat_row['id']) and ($cur_cat_row['level']>1 or ($cur_cat_row['level']==1 and LINK_ON_TOP_CATEG))) {
    $main_if_region['add_link'] = 1;
    $tpl_array['{lang add_site}'] = LANG_ADD_SITE;
    $tpl_array['{lang add_categ}'] = LANG_ADD_CATEG;
    $tpl_array['{add_site_url}'] = 'index.php?adds='.$cur_cat_row['id'];
    $tpl_array['{add_categ_url}'] = 'index.php?addc='.$cur_cat_row['id'];
  } else {
    $main_if_region['add_link'] = 0;  
  }

  $tpl_array['{main}'] = $tpl_main;
  
  if(!SITE_ACTIVE) {
	  $tpl_array['{main}'] = SITE_UNDER_MAINTENANCE_MESSAGE;
  }
  $tpl_array['{lang latest_feat_links}'] = LANG_LATEST_FEATURED_LINKS;
 
  // meta
  if (!$isMeta) {
    $tpl_array['{title}'] = $g_params->Get('site', 'site_title');
    if ($isInfoPage) {
	    $tpl_array['{title}'] = $InfoPageTitle;
    }
    $tpl_array['{description}'] = $g_params->Get('site', 'site_description');
    $tpl_array['{google_analytics}'] = $g_params->Get('site', 'site_google_analytics');
    $tpl_array['{keywords}'] = $g_params->Get('site', 'site_keywords');
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
    $tpl_array['{address}'] = $g_params->Get('site', 'site_address');
  }
  
  $tpl_array['{page_address}'] = $g_params->Get('site', 'site_address');
  $tpl_array['{charset}'] = $g_params->Get('site', 'site_charset');

  // submission advertisements
  $tpl_array['{ad_step1}'] = $g_params->Get('ads', 'ad_step1');
  $tpl_array['{ad_step2}'] = $g_params->Get('ads', 'ad_step2');
  $tpl_array['{ad_success}'] = $g_params->Get('ads', 'ad_success');

  // other pages advertisements
  $tpl_array['{ad_login}'] = $g_params->Get('ads', 'ad_login');
  $tpl_array['{ad_forgot_pass}'] = $g_params->Get('ads', 'ad_forgot_pass');
  $tpl_array['{ad_register}'] = $g_params->Get('ads', 'ad_register');
  $tpl_array['{ad_add_category}'] = $g_params->Get('ads', 'ad_add_category');

  if ($g_params->Get('site', 'categories_on_left')) {
    $main_if_region['categories_on_left'] = 1; 
  } else { $main_if_region['categories_on_left'] = 0; }

  // main pages in the main menu
  if ($g_params->Get('pages', 'show_main_pages')) {
    $main_if_region['pages_disp'] = 1;
    $main_page_links = array();
    $p_params = $g_params->GetParams('pages');
    $hmany_pages = 10;
    
    for ($i=1; $i<=$hmany_pages; $i++) {
      	if($p_params['page_use_'.$i]) {
	      if (MOD_REWRITE) {
	        $main_page_links[] = array('{page link}'=>'<a href="'.SITE_ADDRESS.$p_params['page_name_'.$i].'.html" >'.$p_params['page_link_'.$i].'</a>');
	      } else {
	        $main_page_links[] = array('{page link}'=>'<a href="index.php?page='.$p_params['page_name_'.$i].'" >'.$p_params['page_link_'.$i].'</a>');
	      }
      	}
    }
    if (empty($main_page_links)) {
	  	$main_if_region['pages_disp'] = 0;
    }
  } else { 
  	$main_if_region['pages_disp'] = 0;
  }
  
  if (MOD_REWRITE) {
	  $tpl_array['{latest_listings}'] = 'latest_listings.htm';
	  $tpl_array['{most_visited}'] = 'most_visited.htm';
  } else {
	  $tpl_array['{latest_listings}'] = 'index.php?page=latest_listings';
  	  $tpl_array['{most_visited}'] = 'index.php?page=most_visited';
  }
  
  // CAUTION!!! Do NOT change below this line!!!
  // Removing or changing the code below might eventually make your site and/or Thumbnails STOP working!!!!
  $tpl_array['{developer}'] = DEVELOPER;
  $tpl_array['{developer_website}'] = DEVELOPER_WEBSITE;
  $tpl_array['{developer_url}'] = DEVELOPER_URL;
  $tpl_array['{script_family}'] = SCRIPT_FAMILY;
  $tpl_array['{script_group}'] = SCRIPT_GROUP;
  $tpl_array['{script_name}'] = SCRIPT_NAME;
  $tpl_array['{script_name_short}'] = SCRIPT_NAME_SHORT;
  $tpl_array['{script}'] = SCRIPT;
  $tpl_array['{full_version}'] = FULL_VERSION;
  $tpl_array['{version}'] = VERSION;
  $tpl_array['{version_number}'] = VERSION_NUMBER;
  $tpl_array['{sub_version}'] = SUB_VERSION;
  $tpl_array['{copyright}'] = COPYRIGHT;
  $tpl_array['{years}'] = YEARS;
  $tpl_array['{foot}'] = FOOT;
  $tpl_array['{thumbnail_code_url}'] = THUMBNAIL_CODE_URL;
  $tpl_array['{thumbnail_backlink}'] = THUMBNAIL_BACKLINK;
  $tpl_array['{alexa_code_url_traffic}'] = ALEXA_CODE_URL_TRAFFIC;
  $tpl_array['{alexa_code_url_java}'] = ALEXA_CODE_URL_JAVA;
  $tpl_array['{alexa_code_url_graph}'] = ALEXA_CODE_URL_GRAPH;
  $tpl_array['{wayback_machine_code_url}'] = WAYBACK_MACHINE_CODE_URL;
  $tpl_array['{license_type}'] = LICENSE_TYPE;
  $tpl_array['{brand_type}'] = BRAND_TYPE;

  $g_template->SetTemplate(DIR_TEMPLATE.'index.tpl.php');
  $g_template->IfRegion( $main_if_region );
  
  if ($g_params->Get('site', 'categories_on_left')) {
    $g_template->FillRowsV2('row categories',$main_categ_links);
  }
  
  if ($main_if_region['pages_disp']) {
    $g_template->FillRowsV2('row pages',$main_page_links);
  }
  
  $tpl_array = str_replace('src="images','src="'.SITE_ADDRESS.'images',$tpl_array);
  $tpl_array = str_replace('src="logos','src="'.SITE_ADDRESS.'logos',$tpl_array);
  // $g_template->FillRowsV2('row latest_feat', $toTemplateLatestFeat);
  $g_template->ReplaceIn($tpl_array);    
  $g_template->Show();

  require_once('foot.php'); 
  $DB->Close();

?>
