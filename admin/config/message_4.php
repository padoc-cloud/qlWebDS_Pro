<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/message_4.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message_4 for admin's left column - Admin
    $message_4 = $g_site->GetAllHTML($feed);
    $message_4 = $g_site->Between($message_4, '<!--start-->', '<!--end-->');

    $tpl_main['{message_4}'] = $message_4;
  }

?>
