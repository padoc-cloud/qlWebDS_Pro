<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/message_2.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message_2 for admin's left column - Site
    $message_2 = $g_site->GetAllHTML($feed);
    $message_2 = $g_site->Between($message_2, '<!--start-->', '<!--end-->');

    $tpl_main['{message_2}'] = $message_2;
  }

?>
