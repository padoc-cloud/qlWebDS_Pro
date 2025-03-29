<?php

class UploadClass
{
    public $max_photo_size;
    public $m_dir;
    public $m_name;
    public $eName = ''; // Initialize to avoid undefined variable warnings
    public $m_aFilter;

    public function __construct($dir, $name, $max = 1, $aFilter = ['jpg', 'png', 'gif', 'bmp', 'jpeg'])
    {
        $this->max_photo_size = MAX_LOGO_SIZE * $max;
        $this->m_dir = $dir;
        $this->m_name = $name;
        $this->m_aFilter = $aFilter;
    }

    public function dirTree($dir)
    {
        $arDir = []; // Initialize to avoid undefined variable warnings
        $d = dir($dir);

        while (false !== ($entry = $d->read())) {
            if ($entry != '.' && $entry != '..' && !is_dir($dir . '/' . $entry)) {
                if (substr($entry, 0, 3) == 'tn_') {
                    $arDir[$entry] = $dir . '/' . $entry;
                }
            }
        }
        $d->close();
        return $arDir;
    }

    public function UploadPhoto($fname, $overwrite = false)
    {
        $dirs = explode('/', $this->m_dir);
        $tmp_dir = '';

        foreach ($dirs as $dir) {
            $tmp_dir .= $dir;
            if (!is_dir($tmp_dir)) {
                mkdir($tmp_dir, 0777, true); // Ensure recursive directory creation
            }
            $tmp_dir .= '/';
        }

        if (!is_dir($this->m_dir)) {
            mkdir($this->m_dir, 0777, true);
        }

        $file_ext = $this->CheckExt();

        if ($file_ext) {
            if (isset($_FILES[$this->m_name]['tmp_name']) && is_uploaded_file($_FILES[$this->m_name]['tmp_name'])) {
                $fileName = $_FILES[$this->m_name]['tmp_name'];
                $realName = $_FILES[$this->m_name]['name'];

                if (!$overwrite) {
                    $fname = $this->FreeName(strtolower($fname));
                }

                $newName = $this->m_dir . '/' . strtolower($fname);

                if ($_FILES[$this->m_name]["size"] < $this->max_photo_size) {
                    move_uploaded_file($_FILES[$this->m_name]['tmp_name'], $newName);
                    return $fname;
                } else {
                    $this->eName .= "Photo was too big. <br>";
                }
            } else {
                $this->eName .= "Photo wasn`t uploaded. <br>";
            }
        } else {
            $this->eName .= "Bad photo format, only JPG or PNG files are accepted. <br>";
        }

        return false;
    }

    public function FreeName($fname)
    {
        $Name = $this->m_dir . '/' . $fname;

        if (!file_exists($Name)) {
            return $fname;
        } else {
            $filename = explode(".", $fname);

            if (count($filename) > 1) {
                $filenameext = array_pop($filename);
                $filename = implode('.', $filename);
            } else {
                $filenameext = '';
            }

            for ($i = 1; $i < 100; $i++) {
                $Name = $this->m_dir . '/' . $filename . '_' . $i . '.' . $filenameext;
                if (!file_exists($Name)) {
                    return $filename . '_' . $i . '.' . $filenameext;
                }
            }
        }

        $this->eName .= 'Change filename<br>';
        return false;
    }

    public function ResizeImg($input, $output, $max_width, $max_height)
    {
        $ret = false;
        if (!function_exists('imagecreatetruecolor')) {
            $this->eName .= "GD library is not enabled. <br>";
            return $ret;
        }

        if (!file_exists($input)) {
            $this->eName .= "Input file does not exist. <br>";
            return $ret;
        }

        list($width, $height) = getimagesize($input);

        if ($width > 0 && $height > 0) {
            $delta = min($max_width / $width, $max_height / $height);

            $new_width = floor($width * $delta);
            $new_height = floor($height * $delta);

            $new_image = imagecreatetruecolor($new_width, $new_height);

            $what = getimagesize($input);

            switch ($what['mime']) {
                case 'image/png':
                    $source = imagecreatefrompng($input);
                    break;
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($input);
                    break;
                case 'image/gif':
                    $source = imagecreatefromgif($input);
                    break;
                default:
                    $this->eName .= "Unsupported image type. <br>";
                    return $ret;
            }

            if ($source) {
                $ret = imagecopyresampled($new_image, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                if ($ret) {
                    switch ($what['mime']) {
                        case 'image/png':
                            $ret = imagepng($new_image, $output);
                            break;
                        case 'image/jpeg':
                            $ret = imagejpeg($new_image, $output, 90);
                            break;
                        case 'image/gif':
                            $ret = imagegif($new_image, $output);
                            break;
                    }
                }

                imagedestroy($source);
            }

            imagedestroy($new_image);
        } else {
            $this->eName .= "Error! Bad image format. <br>";
        }

        return $ret;
    }

    public function CheckExt()
    {
        if (!isset($_FILES[$this->m_name]["name"])) {
            return false;
        }

        $filename = explode(".", $_FILES[$this->m_name]["name"]);
        $filenameext = strtolower(end($filename));

        return in_array($filenameext, $this->m_aFilter) ? $filenameext : false;
    }

    public function UploadFile($fname, $overwrite = false)
    {
        $dirs = explode('/', $this->m_dir);
        $tmp_dir = '';

        foreach ($dirs as $dir) {
            $tmp_dir .= $dir;
            if (!is_dir($tmp_dir)) {
                mkdir($tmp_dir, 0777, true);
            }
            $tmp_dir .= '/';
        }

        if (!is_dir($this->m_dir)) {
            mkdir($this->m_dir, 0777, true);
        }

        $file_ext = $this->CheckExt();

        if ($file_ext) {
            if (isset($_FILES[$this->m_name]['tmp_name']) && is_uploaded_file($_FILES[$this->m_name]['tmp_name'])) {
                $fileName = $_FILES[$this->m_name]['tmp_name'];
                $realName = $_FILES[$this->m_name]['name'];

                if (!$overwrite) {
                    $fname = $this->FreeName(strtolower($fname));
                }

                $newName = $this->m_dir . '/' . strtolower($fname);

                move_uploaded_file($_FILES[$this->m_name]['tmp_name'], $newName);
                return $fname;
            } else {
                $this->eName .= "File was not uploaded. <br>";
            }
        } else {
            $this->eName .= "Bad file format, only .csv files are accepted. <br>";
        }

        return false;
    }
}
