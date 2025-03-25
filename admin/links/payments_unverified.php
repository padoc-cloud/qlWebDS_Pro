<?php

  $per_page = 30;
  $payments = array();
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'links_payments.html';
      
      $tmpErrors = array();
      
   		// save settings
        $site_cbx = array();     
        
      if (isset($_POST['links_cbx'])) {
          $site_cbx = $_POST['links_cbx']; 
      }
        
      if ( count($site_cbx) ) {
          
        foreach ($site_cbx as $key=>$value) {
          $rPayment = $g_site->GetPayment($key);
          if ($rPayment) {
            $site_id = $rPayment['site_id'];          
            
            if (isset($_POST['submit_paid']) ) {

                $pUpdate['paid'] = PM_PAID;
                $g_site->UpdatePayment($pUpdate, $key);
                
                $update['status'] = SITE_VIEW;
                $g_site->UpdateSite($update, $site_id);
                $site = $g_site->GetSiteById($site_id);
                $g_site->SendEmail($g_params->GetParams('site_approved_email'), $site );
              
            } else if ( isset($_POST['submit_ban']) ) {
              
              $update['status'] = SITE_BANNED;
              $g_site->UpdateSite($update, $key);
              
            } else if ( isset($_POST['submit_del']) ) {              

              // $update['status'] = SITE_VIEW;
              $g_site->DeleteSite($key);
              
            }
            
          } // if rPayment
          
        } // foreach
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
                
      $query = "SELECT COUNT(id) as hmany FROM $g_site->m_payment s WHERE paid=".PM_UNVERIFIED;
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

      $query = "SELECT p.*, s.url FROM $g_site->m_payment p 
        LEFT JOIN $g_site->m_table s ON s.id=p.site_id  
        WHERE p.paid=".PM_UNVERIFIED.$order_by.$limit;
                                
      $table = $DB->GetTable($query);

      // end search query            
      $i=0;
      foreach ($table as $payment ){

        $payments[$i]['{id}'] = $payment['id'];
        $payments[$i]['{date}'] = $payment['date'];
        
        $payments[$i]['if_rows_paid_link'] = 1;
        
        $payments[$i]['{currency}'] = $payment['currency'];
          
        $payments[$i]['{amount}'] = $payment['amount'];
        $payments[$i]['{url}'] = $payment['url'];
        
        // paid or not 
        if ($payment['paid']!=PM_PAID) {         
          $payments[$i]['{error}'] = $payment['error'];
          $payments[$i]['if_rows_is_error'] = 1;  
          
        } else { 
          $payments[$i]['{error}'] = '-';
          $payments[$i]['if_rows_is_error'] = 0;             
        }
        
        if ($payment['paid']==PM_PAID) { 
          $payments[$i]['if_rows_type'] = 2; 
        } else { 
          $payments[$i]['if_rows_type'] = 0; 
        }
                  
        // other info in div               
        $payments[$i]['{business}'] = $payment['business'];

        $i++;
      }
      
   	  // make template 
      $my_addr = explode('&', $g_addr);
      $my_addr = $my_addr[0]."&".$my_addr[1];
      
      $site_tpl['{addr}'] = $my_addr;
  
  	  // language
      $site_tpl['{lang paid_links}'] = LANG_PAID_LINKS;
      $site_tpl['{lang maybe_paid_links}'] = LANG_MAYBE_PAID_LINKS;
      $site_tpl['{lang free_links}'] = LANG_FREE_LINKS;                       
      $site_tpl['{lang approve_title}'] = LANG_APPROVE_TITLE;      

      $site_tpl['{lang not_paid}'] = LANG_PAYMENTS_NOT_PAID;      
      $site_tpl['{lang payments_unverified}'] = LANG_PAYMENTS_UNVERIFIED;    
      $site_tpl['{lang payments_not_completed}'] = LANG_PAYMENTS_NOT_COMPLETED;    
      $site_tpl['{lang payments_completed}'] = LANG_PAYMENTS_COMPLETED;
            
      $site_tpl['{lang payments}'] = LANG_PAYMENTS_UNVERIFIED;
      $site_tpl['{lang submit_del}'] = LANG_DELETE;      
      $site_tpl['{lang submit_paid}'] = LANG_MARK_PAID;
      $site_tpl['{lang submit_not_paid}'] = LANG_MARK_NOT_PAID;
            
      $g_template->SetTemplate($template);
      $g_template->ReplaceIn($site_tpl);
      $g_template->FillRowsV2('row sites', $payments);
      $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }
  
?>
