<?php

  if ($g_user->Level() == AL_ADMIN) {

      $template = ADMIN_TEMPLATE_DIR.'tools_domain_availability_checker.html';
      $notifications = '';

	  // make template
      $g_template->SetTemplate($template);
      $g_template->ReplaceIn($site_tpl);
      $tpl_main['{content}'] = $g_template->Get();

  }

?>
