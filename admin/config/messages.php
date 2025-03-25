<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/messages.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);
    $template = ADMIN_TEMPLATE_DIR.'config_messages.html';
    
    // get messages for Admin Messages/News
    $messages = $g_site->GetAllHTML($feed);
    $messages = $g_site->Between($messages, '<!--start-->', '<!--end-->');
    
    // fill template
    $site_tpl['{messages}'] = $messages;
    
    // set template
    $g_template->SetTemplate($template);  
    $g_template->ReplaceIn($site_tpl);
    $tpl_main['{content}'] = $g_template->Get();
  }
  
?>
