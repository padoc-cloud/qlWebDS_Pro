<?php
  
  if ($g_user->Level() == AL_ADMIN) {
      
      $hmany_banners = 15;
      
      $template = ADMIN_TEMPLATE_DIR.'site_banners.html';
      $notifications = '';
      
      $tmpErrors = array();
      
      // save settings
      if (isset($_POST['submit'])) {
          
          $params['banner_1'] = sp_replace($_POST['banner_1']);
          $params['banner_2'] = sp_replace($_POST['banner_2']);
          $params['banner_3'] = sp_replace($_POST['banner_3']);
          $params['banner_4'] = sp_replace($_POST['banner_4']);                              
          $params['banner_5'] = sp_replace($_POST['banner_5']);
          $params['banner_6'] = sp_replace($_POST['banner_6']); 
          $params['banner_7'] = sp_replace($_POST['banner_7']);  
          $params['banner_8'] = sp_replace($_POST['banner_8']);
          $params['banner_9'] = sp_replace($_POST['banner_9']);
          $params['banner_10'] = sp_replace($_POST['banner_10']);
          $params['banner_11'] = sp_replace($_POST['banner_11']);
          $params['banner_12'] = sp_replace($_POST['banner_12']);
          $params['banner_13'] = sp_replace($_POST['banner_13']);
          $params['banner_14'] = sp_replace($_POST['banner_14']);
          $params['banner_15'] = sp_replace($_POST['banner_15']);
          
          if (isset($_POST['banner_active'])) {
            $params['banner_active'] = 1;
          } else {
            $params['banner_active'] = 0;
          }
          
          for ($i=1; $i<=$hmany_banners; $i++) {
            if (isset($_POST['banner_use_'.$i])) {
              $params['banner_use_'.$i] = 1;
            } else {
              $params['banner_use_'.$i] = 0;
            }
          }
                       
          // check for errors
          if (( count($tmpErrors) === 0) and $g_params->UpdateParams('banner',$params)!==false) {
             $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
          } else {
              $text = implode(', ', $tmpErrors);
              $notifications = '<div class="info">'.LANG_SETTING_SAVED_ERROR.'. <br>Errors: '.$text.'</div>';
          }      
      }  
    
      $params = $g_params->GetParams('banner');    
      if ($params['banner_active']) $checked=' checked '; else $checked='';
      
      $site_tpl = array (
        '{banner_1}'=> $params['banner_1'],
        '{banner_2}'=> $params['banner_2'],
        '{banner_3}'=> $params['banner_3'],
        '{banner_4}'=> $params['banner_4'],
        '{banner_5}'=> $params['banner_5'],
        '{banner_6}'=> $params['banner_6'],
        '{banner_7}'=> $params['banner_7'],    
        '{banner_8}'=> $params['banner_8'],   
        '{banner_9}'=> $params['banner_9'],
        '{banner_10}'=> $params['banner_10'],
        '{banner_11}'=> $params['banner_11'],
        '{banner_12}'=> $params['banner_12'],
        '{banner_13}'=> $params['banner_13'],
        '{banner_14}'=> $params['banner_14'],
        '{banner_15}'=> $params['banner_15'],
        '{checked}'=>$checked,
      );      

      for ($i=1; $i<=$hmany_banners; $i++) {
        if ($g_params->Get('banner', 'banner_use_'.$i) ) $site_tpl['banner_use_'.$i.'}']= ' checked '; else $site_tpl['banner_use_'.$i.'}']= '';
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
