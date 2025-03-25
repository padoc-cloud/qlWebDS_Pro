<?php
    
  $eMsg = '';
               
  if (isset($_POST['submit'])) {
    
        $aInsert = array();
        if (strlen($_POST['banned_words'])>0) {
          $names = explode("\r\n", $_POST['banned_words']);
          
          foreach ($names as $name) {
            if (trim($name)) {
              $aInsert[] = $name;
            }
          }
        } 
        $ok = $g_params->UpdateParams('banned_words', $aInsert, false);
        
        $aInsert = array();
        if (strlen($_POST['banned_ips'])>0) {
          $names = explode("\r\n", $_POST['banned_ips']);
          
          foreach ($names as $name) {
            if ($name = trim($name)) {
              $aInsert[] = $name;
            }
          }
        } 
        $ok = $g_params->UpdateParams('banned_ips', $aInsert, false);
        
        $eMsg .= LANG_SETTING_SAVED;     
  }

  if ($eMsg) {
    $tmp_tpl['{notifications}'] = '<div class="info">'.$eMsg.'</div>';    
  } else { $tmp_tpl['{notifications}'] = ''; }
  
  $banned_ips = '';
  $banned_words = '';
  if (is_array($g_params->GetParams('banned_ips'))) {
    $banned_ips = implode("\r\n", $g_params->GetParams('banned_ips'));
  }
  if (is_array($g_params->GetParams('banned_words'))) {  
    $banned_words = implode("\r\n", $g_params->GetParams('banned_words'));  
  }
  
  $tmp_tpl['{banned_ips}'] = $banned_ips;
  $tmp_tpl['{banned_words}'] = $banned_words;
    
  $g_template->SetTemplate(ADMIN_TEMPLATE_DIR.'site_filter.html');
  $g_template->ReplaceIn($tmp_tpl);
  $tpl_main['{content}'] = $g_template->Get();
     
?>
