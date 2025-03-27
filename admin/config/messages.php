<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $template = ADMIN_TEMPLATE_DIR.'config_messages.html';
    
    // fill template
    $site_tpl['{messages}'] = "";
    
    // set template
    $g_template->SetTemplate($template);  
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();
  }
  
?>
