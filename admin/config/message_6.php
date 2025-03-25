<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/message_6.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message_6 for admin's top right column - Admin
    $message_6 = $g_site->GetAllHTML($feed);
    $message_6 = $g_site->Between($message_6, '<!--start-->', '<!--end-->');

    $tpl_main['{message_6}'] = $message_6;
  }

?>
