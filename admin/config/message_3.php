<?php

  if ($g_user->Level() == AL_ADMIN) {
    
    $feed = 'http://www.qlweb.com/message_3.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message_3 for admin's left column - Listings
    $message_3 = $g_site->GetAllHTML($feed);
    $message_3 = $g_site->Between($message_3, '<!--start-->', '<!--end-->');

    $tpl_main['{message_3}'] = $message_3;
  }

?>
