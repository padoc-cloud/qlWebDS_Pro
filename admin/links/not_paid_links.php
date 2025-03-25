<?php

  $per_page = 20;
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'links_approve_links.html';
      
      $tmpErrors = array();
      
      // delete single link
      if (isset($_GET['delete_id']) ) {
          $key = $_GET['delete_id'];      
          $g_site->DeleteSite($key);
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
              
            } else if ( isset($_POST['submit_del']) ) {              

              // $update['status'] = SITE_VIEW;
              $g_site->DeleteSite($key);
              
            }
          }
        }

      // header sort
      $site_tpl['{tsort}'] = 'desc';
      $site_tpl['{dsort}'] = 'desc';
      $site_tpl['{usort}'] = 'desc';
      $ord = '';
      $sort = 'id';
      if (isset($_GET['ord']) and isset($_GET['sort']) ) {
        $ord = $_GET['ord'];
        $sort = $_GET['sort'];
        switch ($ord) {
          case 'title':
            if ($sort=='desc') {
              $site_tpl['{tsort}'] = 'asc';
              $aSort[$ord]= 'desc'; 
 
            } else {
              $aSort[$ord]= 'desc';
            }    
            break;
            
          case 'url':
            if ($sort=='desc') {
              $site_tpl['{usort}'] = 'asc';
              $aSort[$ord]= 'desc';     
            } else {
              $aSort[$ord]= 'desc';
            }    
            break;  
            
          case 'description':
            if ($sort=='desc') {
              $site_tpl['{dsort}'] = 'asc';
              $aSort[$ord]= 'desc';     
            } else {
              $aSort[$ord]= 'asc';
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
                
      $query = "SELECT COUNT(s.id) as hmany FROM $g_site->m_table s
         LEFT JOIN $g_site->m_payment p ON s.last_payment_id=p.id         
         WHERE status=".SITE_WAITING. ' AND s.last_payment_id=p.id AND (p.paid='.PM_NOT_PAID .' OR p.paid='.PM_NOT_COMPLETED .' OR p.paid='.PM_UNVERIFIED.')';
         
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
      $query = "SELECT s.*, p.paid, p.id AS payment_id, p.error, p.is_error, p.amount, p.email AS pemail, p.currency, p.payment_period, p.business, p.payment_date FROM $g_site->m_table s ".
        " LEFT JOIN $g_site->m_payment p ON s.last_payment_id=p.id  
        WHERE status=".SITE_WAITING. ' AND s.last_payment_id=p.id AND (p.paid='.PM_NOT_PAID .' OR p.paid='.PM_NOT_COMPLETED .' OR p.paid='.PM_UNVERIFIED.')'.$order_by.$limit;
        
      $table = $DB->GetTable($query);

      // end search query            
      $i=0;
      
      foreach ($table as $site ){

        $sites[$i]['{id}'] = $site['id'];
        $sites[$i]['{title}'] = $site['title'];
        $sites[$i]['{url}'] = '<a href="'.$site['url'].'" target="_blank">'.$site['url'].'</a>';
        $sites[$i]['{description}'] = $site['description'];
        $sites[$i]['{email}'] = $site['email'];  
                  
        if ($site['pr']<0) { $pr = 'N/A'; } else { $pr = $site['pr'];}
        $sites[$i]['{pr}'] = $pr;

        // link payment
        if ($site['paid_link']) {
          $sites[$i]['if_rows_paid_link'] = 1;
          
          $sites[$i]['{amount}'] = $site['amount']; 
          $sites[$i]['{currency}'] = $site['currency'];            
          $sites[$i]['{link_error}'] = $site['error']; 

          $sites[$i]['{payment_date}'] = $site['payment_date'];
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
                     
          $paid_link = 'Yes'; 
          
        } else {
          $sites[$i]['if_rows_paid_link'] = 0;
          $sites[$i]['if_rows_type'] = 1;
          $paid_link = 'No';
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
          case 9:
            $sites[$i]['{link_type}'] = LINK_TYPE9;
            break;                                              
                                                                                                                                        
        }
        
        // deep links
        $sites[$i]['if_rows_deep'] = 0;
        
        if ($site['link_type'] == LT_ADD3 or $site['link_type'] == LT_ADD5) {
           
          $sites[$i]['if_rows_deep'] = 1;
        
          $sites[$i]['{deep_links}'] = '';
          for ($j=0; $j<=5; $j++) {
            
            if ( (strlen($site['url'.$j])>0) and (strlen($site['title'.$j])>0) ) {
              $sites[$i]['{deep_links}'] .= '<a href="'.$site['url'.$j].'" target="_blank">'.$site['title'.$j].'</a>, ';
            }
          
          }
        
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
        $categories = $g_categ->GetSiteCategories($site['id']);
        $categories_in = '';
        foreach ($categories as $category) {
        
          // if (MOD_REWRITE) {
          // $categ_address = CATALOG_ADDRESS.str_replace(',',WORD_SEPARATOR,$category['mod_rewrite']).WORD_SEPARATOR.MOD_CATEG.'-'.$category['sub_id'].PAGE_EXTENSION;
          // } else {
            $categ_address = CATALOG_ADDRESS.'index.php?'.$category['mod_rewrite'].'&amp;categ='.$category['sub_id'];
          // }
          $categories_in .= ' - <a href="'.$categ_address.'" target="_blank">'.$category['name'].'</a>, ';
          
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
      $site_tpl['{lang approve_title}'] = LANG_MAYBE_PAID_LINKS;         
      
      $g_template->SetTemplate($template);
      $g_template->ReplaceIn($site_tpl);
      $g_template->FillRowsV2('row sites', $sites);
      $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }
  
?>
