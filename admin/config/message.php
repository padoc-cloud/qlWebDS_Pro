<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/message.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message for admin's upper left corner box
    $message = $g_site->GetAllHTML($feed);
    $message = $g_site->Between($message, '<!--start-->', '<!--end-->');

    $tpl_main['{message}'] = $message;
  }

?>
