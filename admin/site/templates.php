<?php
  
  $tmp_tpl = array();
  
  $g_template->SetTemplate(ADMIN_TEMPLATE_DIR.'site_templates.html');
  $g_template->ReplaceIn($tmp_tpl);
  $tpl_main['{content}'] = $g_template->Get();

?>
