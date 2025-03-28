<?php

$l_error = false;
$backup_file = $backup_dir . SCRIPT . '_' . date('Y-m-d_H.i.s') . '.logos.zip';
$zip = new ZipArchive;

$logo_dir = MAIN_CATALOG . LOGO_DIR;
$open_result = $zip->open($backup_file, ZipArchive::CREATE);

if ($open_result === TRUE) {
    if (is_dir($logo_dir)) {
        $entries = scandir($logo_dir); // Use scandir instead of dir()

        foreach ($entries as $entry) {
            if ($entry !== '.' && $entry !== '..') {
                $full_path = $logo_dir . '/' . $entry;

                if (!is_dir($full_path)) {
                    if (!$zip->addFile($full_path, $entry)) {
                        $notifications = '<div class="info">' . LANG_LOGOS_BACKUP_ERROR1 . ' ' . $entry . '</div>';
                        $l_error = true;
                        break;
                    }
                }
            }
        }
    } else {
        $notifications = '<div class="info">' . LANG_BACKUP_ERROR1 . ': Directory not found.</div>';
        $l_error = true;
    }

    $zip->close();

    if (!$l_error) {
        $notifications = '<div class="info">' . LANG_LOGOS_BACKUP_CREATED . '</div>';
    }
} else {
    $notifications = '<div class="info">' . LANG_BACKUP_ERROR1 . '</div>';
}

?>