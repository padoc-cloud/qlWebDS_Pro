<?php

  $per_page = 30;
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'admin_user_listings.html';
      
      $tmpErrors = array();
		
      $user_id = 0;
      
      if (isset($_GET['user_id']) ) {
         $user_id = (int) $_GET['user_id'];
      } else {  
	     if (isset($_POST['p_user_id']) ) {
		 	$user_id = (int) $_POST['p_user_id'];
	     }
      }
      
      $user = $g_users->GetUser($user_id);
      
      $site_tpl['{p_user_id}'] = $user_id;
      $site_tpl['{user name}'] = $user['user'];
      
      
      // delete single link
      if (isset($_GET['delete_id']) ) {
          $key = $_GET['delete_id'];
          $g_site->DeleteSite($key);
          $g_cache->EmptyCache();
      }
            
		// save settings
        $site_cbx = array();     
        
        if (isset($_POST['site_cbx'])) {
          $site_cbx = $_POST['site_cbx']; 
        }
        
        if ( count($site_cbx) ) {
          
          foreach ($site_cbx as $key=>$value) {

            if (isset($_POST['submit_add']) ) {
              
              $update['status'] = SITE_VIEW;
              $g_site->UpdateSite($update, $key);
              $site = $g_site->GetSiteById($key);
              $g_site->SendEmail($g_params->GetParams('site_approved_email'), $site );
              
            } else if ( isset($_POST['submit_ban']) ) {
              
              $update['status'] = SITE_BANNED;
              $g_site->UpdateSite($update, $key);
              $g_cache->EmptyCache();
                            
            } else if ( isset($_POST['submit_del']) ) {              

              // $update['status'] = SITE_VIEW;
              
              $g_site->DeleteSite($key);
              $g_cache->EmptyCache();
              
            }
          }
        }

      // header sort
      $site_tpl['{tsort}'] = 'desc';
      $site_tpl['{dsort}'] = 'desc';
      $site_tpl['{usort}'] = 'desc';
      $site_tpl['{prsort}'] = 'desc';
      $site_tpl['{stsort}'] = 'desc';
      $ord = '';
      $sort = 'id';
      if (isset($_GET['ord']) and isset($_GET['sort']) ) {
        $ord = $_GET['ord'];
        $sort = $_GET['sort'];
        switch ($ord) {
          case 'title':
            if ($sort=='desc') {
              $site_tpl['{tsort}'] = 'asc';
            }
            break;
            
          case 'url':
            if ($sort=='desc') {
              $site_tpl['{usort}'] = 'asc';
            }    
            break;  
            
          case 'description':
            if ($sort=='desc') {
              $site_tpl['{dsort}'] = 'asc';
            }    
            break;                            

          case 'pr':
            if ($sort=='desc') {
              $site_tpl['{prsort}'] = 'asc';
            }    
            break;  
            
          case 'status':
            if ($sort=='desc') {
              $site_tpl['{stsort}'] = 'asc';
            }
            break;
        }
      }
      
      $sites = array();
      
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // pagging and search query
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////            
      if (isset($_GET['pg']) ) {
         $page = (int) $_GET['pg'];
      } else {  $page = 1; }
                
      $query = "SELECT COUNT(id) as hmany FROM $g_site->m_table s WHERE user_id=".$user_id;
      $row = $DB->GetRow($query);
      
      $hmany = $row['hmany'];
        
      $table = $DB->GetTable($query);
      $hmany =  ceil($hmany / $per_page);
      $pagging = '';
                
      if ($i = strpos($g_addr,'&amp;pg=')) {
        $g_addr = substr($g_addr,0,$i);
      }
                
      for($i=1;$i<=$hmany;$i++) {
        if ($page==$i) {
          $pagging .= "[$i] "; 
        } else {
          $pagging .= '<a href="'.$g_addr.'&amp;pg='.$i.'">'.$i.'</a> ';
        }
      }
      $site_tpl['{pagging}'] = $pagging;                     
      
      $begin = ($page-1)*$per_page;
      $limit = " LIMIT $begin, ".$per_page;
      $order_by = ' Order BY '.$ord.' '.$sort;              
      $query = "SELECT s.*, p.paid, p.id AS payment_id, p.error, p.is_error, p.amount, p.email AS pemail, p.currency, p.payment_period, p.business, p.payment_date 
        FROM $g_site->m_table s 
        LEFT JOIN $g_site->m_payment p ON s.last_payment_id=p.id  
        WHERE s.user_id=".$user_id.$order_by.$limit;
        
      $table = $DB->GetTable($query);
     
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // get categories
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////      
      $query = "SELECT ct.*, cn.*  FROM $g_categ->m_connection AS cn 
        LEFT JOIN $g_categ->m_table ct ON ct.id=cn.sub_id
        LEFT JOIN $g_site->m_table st ON st.id=cn.site_id  
        WHERE cn.site_id=st.id AND st.user_id=".$user_id;
            
      $all_categories = $DB->GetTable($query);

      foreach ($all_categories as $row) {
        $tCategories[$row['site_id']][] = $row;
      }
      
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // make links table
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////                  
      $i=0;
      foreach ($table as $site ) {

        $sites[$i]['{id}'] = $site['id'];
        $sites[$i]['{title}'] = $site['title'];
        $sites[$i]['{url}'] = '<a href="'.$site['url'].'" target="_blank">'.$site['url'].'</a>';
        $sites[$i]['{description}'] = $site['description'];
        $sites[$i]['{email}'] = $site['email'];
		$sites[$i]['{site_url}'] = $site['url'];
         
        if ($site['pr']<0) { $pr = 'N/A'; } else { $pr = $site['pr'];}
        $sites[$i]['{pr}'] = $pr;
        switch ($site['status']) {
          case SITE_WAITING:
        	$status = 'PENDING';
          	break;
            
          case SITE_BANNED:
        	$status = 'INACTIVE';
          	break;
            
          case SITE_UNCONFIRMED:
        	$status = 'UNCONFIRMED';
          	break;
            
          case SITE_ENDED_CHARGES:
        	$status = 'CHARGES ENDED';
          	break;
            
          case SITE_UNCONFIRMED:
        	$status = 'UNCONFIRMED';
          	break;
            
          case SITE_VIEW:
        	$status = 'ACTIVE';
          	break;
        }
        $sites[$i]['{status}'] = $status;
        
        // link payment
        if ($site['paid_link']) {
          $sites[$i]['if_rows_paid_link'] = 1;
          
          $sites[$i]['{amount}'] = $site['amount'];
          $sites[$i]['{currency}'] = $site['currency'];  
          $sites[$i]['{link_error}'] = $site['error']; 

          $sites[$i]['{payment_date}'] = $site['payment_date'];
          $sites[$i]['{business}'] = $site['business'];   
          $sites[$i]['{pemail}'] = $site['pemail'];
                                 
          if ($site['payment_period']==0) {
            $sites[$i]['{subscription}'] = 'no';
          } else {
            $sites[$i]['{subscription}'] = $site['payment_period'].' months';
          }
          
          if ($site['paid']==PM_PAID) { 
            $sites[$i]['if_rows_type'] = 2; 
          } else { 
            $sites[$i]['if_rows_type'] = 0; 
          }
                     
          $paid_link = 'yes'; 
          
        } else {
          $sites[$i]['if_rows_paid_link'] = 0;
          $sites[$i]['if_rows_type'] = 1;
          $paid_link = 'no';
        }
        
        $sites[$i]['{paid_link}'] = $paid_link;
        
        // link type
        switch ($site['link_type']) {
        
          case 1:
            $sites[$i]['{link_type}'] = LINK_TYPE1;
            break;
          case 2:
            $sites[$i]['{link_type}'] = LINK_TYPE2;
            break;
          case 3:
            $sites[$i]['{link_type}'] = LINK_TYPE3;
            break;
          case 4:
            $sites[$i]['{link_type}'] = LINK_TYPE4;
            break;
          case 5:
            $sites[$i]['{link_type}'] = LINK_TYPE5;
            break;
          case 6:
            $sites[$i]['{link_type}'] = LINK_TYPE6;
            break;
          case 7:
            $sites[$i]['{link_type}'] = LINK_TYPE7;
            break;
          case 8:
            $sites[$i]['{link_type}'] = LINK_TYPE8;
            break;                                
                                                                                                                                        
        }
        
        // note
        if (strlen($site['note'])>0) {
          $sites[$i]['if_rows_note'] = 1;
          $sites[$i]['{note}'] = $site['note'];
          
        } else {
          $sites[$i]['if_rows_note'] = 0;
        }
        
        // other info in div    
        $sites[$i]['{date}'] = $site['date'];
        $sites[$i]['{ip}'] = $site['user_ip'];
        
        // site categories
        // $categories = $g_categ->GetSiteCategories($site['id']);
        
        if (isset($tCategories[$site['id']])) {
          $categories = $tCategories[$site['id']];
        } else { $categories = array(); }
        
        $categories_in = '';
        foreach ($categories as $category) {
        
          // if (MOD_REWRITE) {
          // $categ_address = CATALOG_ADDRESS.str_replace(',',WORD_SEPARATOR,$category['mod_rewrite']).WORD_SEPARATOR.MOD_CATEG.'-'.$category['sub_id'].PAGE_EXTENSION;
          // } else {
            $categ_address = CATALOG_ADDRESS.'index.php?'.$category['mod_rewrite'].'&amp;categ='.$category['sub_id'];
          //}
          $categories_in .= ' - <a href="'.$categ_address.'" target="_blank">'.$category['name'].'</a>,';
          
        }
        $sites[$i]['{categories}'] = $categories_in;        
        
        // site info address
        if (MOD_REWRITE) {
          $more_address = CATALOG_ADDRESS.str_replace(',',WORD_SEPARATOR,$site['mod_rewrite']).WORD_SEPARATOR.MOD_SITE.'-'.$site['id'].PAGE_EXTENSION;
        } else {
          $more_address = CATALOG_ADDRESS.'index.php?'.$site['mod_rewrite'].'&amp;site='.$site['id'];
        }
        $sites[$i]['{more_address}'] = $more_address;
                                                
        $i++;
      }
      
   	  // make template 
      $my_addr = explode('&', $g_addr);
      $my_addr = $my_addr[0]."&".$my_addr[1];
      
      $site_tpl['{addr}'] = $my_addr;

  	  // language
      $site_tpl['{lang submit_add}'] = LANG_ADD;
      $site_tpl['{lang submit_ban}'] = LANG_INACTIVE;
      $site_tpl['{lang submit_del}'] = LANG_DELETE;
      $site_tpl['{lang paid_links}'] = LANG_PAID_LINKS;
      $site_tpl['{lang maybe_paid_links}'] = LANG_MAYBE_PAID_LINKS;
      $site_tpl['{lang free_links}'] = LANG_FREE_LINKS;                       
      $site_tpl['{lang active_title}'] = LANG_ACTIVE_TITLE;

      $g_template->SetTemplate($template);
      $g_template->ReplaceIn($site_tpl);
      $g_template->FillRowsV2('row sites', $sites);
      $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }
  
?>
