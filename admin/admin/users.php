<?php

  $per_page = 20;
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'admin_users.html';
      
      $notifications = '';
      
	  // activate user
      if (isset($_GET['activate_id']) ) {
          $key = $_GET['activate_id'];
          $aUser = array();
          $aUser['status'] = USER_ACTIVE;
          
          if ($g_users->UpdateUser($aUser, $key) !== false) {
          	$notifications = '<div class="info">User Activated</div>';
          } else {
          	$notifications = '<div class="info">User Activatation Failed</div>';
          }
      }
      
	  // deactivate user
      if (isset($_GET['deactivate_id']) ) {
          $key = $_GET['deactivate_id'];
          $aUser = array();
          $aUser['status'] = USER_INACTIVE;
          
          if ($g_users->UpdateUser($aUser, $key) !== false) {
          	$notifications = '<div class="info">User Deactivated</div>';
          } else {
          	$notifications = '<div class="info">User Deactivatation Failed</div>';
          }
      }
      
	  // delete user
      if (isset($_GET['delete_id']) ) {
          $key = $_GET['delete_id'];
          
          if ($g_users->DelUser($key) !== false) {
          	$notifications = '<div class="info">User Deleted</div>';
          } else {
          	$notifications = '<div class="info">User Deletion Failed</div>';
          }
      }
      
	  // delete user and all his/her listings
      if (isset($_GET['delete_all_id']) ) {
          $key = $_GET['delete_all_id'];
          
          if ($g_users->DelUser($key,true) !== false) {
          	$notifications = '<div class="info">User Deleted</div>';
          } else {
          	$notifications = '<div class="info">User Deletion Failed</div>';
          }
      }
      
      // header sort
      $site_tpl['{un_sort}'] = 'desc';
      $site_tpl['{rn_sort}'] = 'desc';
      $site_tpl['{dr_sort}'] = 'desc';
      $site_tpl['{e_sort}'] = 'desc';
      $ord = '';
      $sort = 'id';
      if (isset($_GET['ord']) and isset($_GET['sort']) ) {
        $ord = $_GET['ord'];
        $sort = $_GET['sort'];
        switch ($ord) {
          case 'user':
            if ($sort=='desc') {
              $site_tpl['{un_sort}'] = 'asc';
              $aSort[$ord]= 'desc'; 
 
            } else {
              $aSort[$ord]= 'desc';
            }    
            break;
            
          case 'real_name':
            if ($sort=='desc') {
              $site_tpl['{rn_sort}'] = 'asc';
              $aSort[$ord]= 'desc';     
            } else {
              $aSort[$ord]= 'desc';
            }    
            break;  

          case 'registration_date':
            if ($sort=='desc') {
              $site_tpl['{dr_sort}'] = 'asc';
              $aSort[$ord]= 'desc'; 
 
            } else {
              $aSort[$ord]= 'desc';
            }    
            break;  
            
          case 'email':
            if ($sort=='desc') {
              $site_tpl['{e_sort}'] = 'asc';
              $aSort[$ord]= 'desc';     
            } else {
              $aSort[$ord]= 'asc';
            }    
            break;                            
        
        }
        
      }
      
      $users = array();
      
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // pagging and search query 
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
      if (isset($_GET['pg']) ) {
         $page = (int) $_GET['pg'];
      } else {  $page = 1; }
                
      $query = "SELECT COUNT(id) as hmany FROM $g_user->m_table s WHERE access_level=".AL_USER;
      $row = $DB->GetRow($query);
      
      $hmany = $row['hmany'];
        
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
      $query = "SELECT * FROM ".$g_user->m_table." WHERE access_level=".AL_USER.$order_by.$limit;
        
      $table = $DB->GetTable($query);

      $i=0;
      
      foreach ($table as $user ){

        $users[$i]['{id}'] = $user['id'];
        $users[$i]['{user}'] = $user['user'];
        $users[$i]['{real_name}'] = $user['real_name'];
        $users[$i]['{registration_date}'] = $user['registration_date'];
        $users[$i]['{email}'] = $user['email'];
        
      	$query = "SELECT COUNT(id) as hmany FROM ".$g_site->m_table." WHERE user_id=".$user['id'];
      	$row = $DB->GetRow($query);
      
        $users[$i]['{no_listings}'] = $row['hmany'];
        
        // change inactive user row font to red
        if($user['status'] == USER_ACTIVE) {
        	$users[$i]['{act_inact_style}'] = 'style="color: #000000;"';
        	$users[$i]['{act_deact_label}'] = 'Deactivate';
        	$users[$i]['{act_deact_id}'] = 'deactivate_id';
        } else {
        	$users[$i]['{act_inact_style}'] = 'style="color: #ff0000;"';
        	$users[$i]['{act_deact_label}'] = 'Activate';
        	$users[$i]['{act_deact_id}'] = 'activate_id';
        }
                                                
        $i++;
        
      }
      
   	  // make template 
      $my_addr = explode('&', $g_addr);
      $my_addr = $my_addr[0]."&".$my_addr[1];
      
      $site_tpl['{addr}'] = $my_addr;
      $site_tpl['{notifications}'] = $notifications;
            
  	  // language      
      $g_template->SetTemplate($template);
      $g_template->ReplaceIn($site_tpl);
      $g_template->FillRowsV2('row users', $users);
      $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
 }
  
?>
