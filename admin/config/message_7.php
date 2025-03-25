<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/message_7.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message_7 for admin's under Config Settings Paragraph - Admin
    $message_7 = $g_site->GetAllHTML($feed);
    $message_7 = $g_site->Between($message_7, '<!--start-->', '<!--end-->');

    $tpl_main['{message_7}'] = $message_7;
  }

?>
