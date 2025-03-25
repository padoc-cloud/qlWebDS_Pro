<?php
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'site_menu_pages.html';
      $notifications = '';
      $tmpErrors = array();
      $hmany_pages = 10;
	    
	  // save settings
      if (isset($_POST['submit'])) {

        if (isset($_POST['show_main_pages'])) {
           $params['show_main_pages'] = 1;
        } else {
           $params['show_main_pages'] = 0;
        }

        $params['page_submit_guidelines'] = 0;
                
        if (isset($_POST['submit_guidelines'])) {
        	$params['page_submit_guidelines'] = $_POST['submit_guidelines'];
        }
        
      	for ($i=1; $i<=$hmany_pages; $i++) {
            if (isset($_POST['page_use_'.$i])) {
              $params['page_use_'.$i] = 1;
            } else {
              $params['page_use_'.$i] = 0;
            }
            if (isset($_POST['page_contact_us_'.$i])) {
              $params['page_contact_us_'.$i] = 1;
            } else {
              $params['page_contact_us_'.$i] = 0;
            }
            $params['page_name_'.$i] = sp_replace($_POST['page_name_'.$i]);
            $params['page_title_'.$i] = sp_replace($_POST['page_title_'.$i]);
            $params['page_content_'.$i] = sp_replace($_POST['page_content_'.$i]);
            $params['page_link_'.$i] = sp_replace($_POST['page_link_'.$i]);
          }
      	
          // check for errors
          if (( count($tmpErrors) === 0) and $g_params->UpdateParams('pages',$params)!==false) {
             $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
          } else {
              $text = implode(', ', $tmpErrors);
              $notifications = '<div class="info">'.LANG_SETTING_SAVED_ERROR.'. <br>Errors: '.$text.'</div>';
          }      
      }  
    
      $params = $g_params->GetParams('pages');
          
      if($params['show_main_pages']) $checked = 'checked'; else $checked = '';
      $site_tpl['{show_main_pages}'] = $checked;
      
      if($params['page_submit_guidelines'] == 0) $checked = 'checked'; else $checked = '';
      $site_tpl['{page_submit_guidelines_0}'] = $checked;
      
      for ($i=1; $i<=$hmany_pages; $i++) {
      	if($params['page_use_'.$i]) $checked = 'checked'; else $checked = '';
      	$site_tpl['{page_use_'.$i.'}'] = $checked;
      	if($params['page_contact_us_'.$i]) $checked = 'checked'; else $checked = '';
      	$site_tpl['{page_contact_us_'.$i.'}'] = $checked;
        if($params['page_submit_guidelines'] == $i) $checked = 'checked'; else $checked = '';
        $site_tpl['{page_submit_guidelines_'.$i.'}'] = $checked;
      	$site_tpl['{page_name_'.$i.'}'] = $params['page_name_'.$i];
      	$site_tpl['{page_title_'.$i.'}'] = $params['page_title_'.$i];
        $site_tpl['{page_content_'.$i.'}'] = $params['page_content_'.$i];
        $site_tpl['{page_link_'.$i.'}'] = $params['page_link_'.$i];
      }
      
	  // make template 
      $site_tpl['{notifications}'] = $notifications;
         
      $g_template->SetTemplate($template);  
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
  }

?>
