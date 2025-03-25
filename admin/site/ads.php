<?php
  
  if ($g_user->Level() == AL_ADMIN) {
  
      $template = ADMIN_TEMPLATE_DIR.'site_ads.html';
      $notifications = '';
      
      $tmpErrors = array();

      // save settings
      if (isset($_POST['submit'])) {

          $params['top_block'] = sp_replace($_POST['top_block']);
          $params['after_first'] = sp_replace($_POST['after_first']);
          $params['right_block'] = sp_replace($_POST['right_block']);
          $params['site_info'] = sp_replace($_POST['site_info']);                              
          $params['site_main'] = sp_replace($_POST['site_main']); 
          $params['left_block'] = sp_replace($_POST['left_block']);
          $params['ad_step1'] = sp_replace($_POST['ad_step1']);
          $params['ad_step2'] = sp_replace($_POST['ad_step2']);
          $params['ad_success'] = sp_replace($_POST['ad_success']);
          $params['ad_login'] = sp_replace($_POST['ad_login']);
          $params['ad_forgot_pass'] = sp_replace($_POST['ad_forgot_pass']);
          $params['ad_register'] = sp_replace($_POST['ad_register']);
          $params['ad_add_category'] = sp_replace($_POST['ad_add_category']);

          // check for errors
          if (( count($tmpErrors) === 0) and $g_params->UpdateParams('ads',$params)!==false) {
             $notifications = '<div class="info">'.LANG_SETTING_SAVED.'</div>';
          } else {
              $text = implode(', ', $tmpErrors);
              $notifications = '<div class="info">'.LANG_SETTING_SAVED_ERROR.'. <br>Errors: '.$text.'</div>';
          }      
      }  
    
      $params = $g_params->GetParams('ads');    
      $site_tpl = array (
        '{top_block}'=> $params['top_block'],
        '{after_first}'=> $params['after_first'],
        '{right_block}'=> $params['right_block'],
        '{site_info}'=> $params['site_info'],
        '{site_main}'=> $params['site_main'],
        '{left_block}'=> $params['left_block'],
        '{ad_step1}'=> $params['ad_step1'],
        '{ad_step2}'=> $params['ad_step2'],
        '{ad_success}'=> $params['ad_success'],
        '{ad_login}'=> $params['ad_login'],
        '{ad_forgot_pass}'=> $params['ad_forgot_pass'],
        '{ad_register}'=> $params['ad_register'],
        '{ad_add_category}'=> $params['ad_add_category'],
      );      
      
      // make template 
      $site_tpl['{notifications}'] = $notifications;
         
      $g_template->SetTemplate($template);  
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get();
               
  } else {
       $tpl_main['{content}'] = ERROR_002;
  }
  
?>
