<?php

  if ($g_user->Level() == AL_ADMIN) {

    $feed = 'http://www.qlweb.com/message_1.php?v='.urlencode(VERSION).'&s='.urlencode($_SERVER['SERVER_NAME']);

    // get message_1 for admin's left column - Config
    $message_1 = $g_site->GetAllHTML($feed);
    $message_1 = $g_site->Between($message_1, '<!--start-->', '<!--end-->');

    $tpl_main['{message_1}'] = $message_1;
  }

?>
