<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/message_5.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message_5 for admin's right column - Admin
    $message_5 = $g_site->GetAllHTML($feed);
    $message_5 = $g_site->Between($message_5, '<!--start-->', '<!--end-->');

    $tpl_main['{message_5}'] = $message_5;
  }

?>
