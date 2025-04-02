<?php

// This file performs CAPTCHA generation and validation operations. It includes methods for creating CAPTCHA images, storing them in a database, and verifying user input against the stored CAPTCHA.

class CaptchaClass
{
    public $m_DB;
    public $m_tablePrefix;
    public $m_table;

    public $m_captchaLifetime = 950;

    public $Length;
    public $CaptchaString = ''; // Initialize to avoid undefined variable warnings
    public $ImageType;
    public $Font;
    public $CharWidth;

    public $ImageWidth;
    public $ImageHeight;

    public function __construct($length = 6, $type = 'png', $letter = '')
    {
        $this->m_DB = DataBase::getInstance();

        if (!$this->m_DB->Open()) {
            $this->eText = 'DB connection failed';
            $this->eNo   = 300001;
            $this->eFunc = '__construct';
        } else {
            $this->m_tablePrefix = $this->m_DB->tablePrefix;
            $this->m_table       = $this->m_tablePrefix . 'captcha';
        }

        $this->Length    = $length;
        $this->ImageType = $type;

        if ($letter == '') {
            $this->StringGen();
        } else {
            $this->Length        = strlen($letter);
            $this->CaptchaString = $letter;
        }

        $this->ImageWidth  = 250;
        $this->ImageHeight = 40;
    }

    public function ShowImage()
    {
        $this->SendHeader();
        $this->MakeCaptcha();
    }

    public function StringGen()
    {
        $uppercase = range('A', 'P');
        $uppercase = array_merge(range('R', 'Z'), $uppercase);

        $numeric = range(2, 9);

        $CharPool   = array_merge($uppercase, $numeric);
        $PoolLength = count($CharPool) - 1;

        for ($i = 0; $i < $this->Length; $i++) {
            $this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)];
        }
    }

    public function SendHeader()
    {
        switch ($this->ImageType) {
            case 'jpeg':
                header('Content-type: image/jpeg');
                break;
            case 'png':
                header('Content-type: image/png');
                break;
            default:
                header('Content-type: image/png');
                break;
        }
    }

    public function MakeCaptcha()
    {
        if (!function_exists('gd_info')) {
            die('GD library is not enabled. Please enable it in your PHP configuration.');
        }

        $info = gd_info();
        $tt   = false;

        if ($info['FreeType Support'] == 1) {
            $tt = true;
            $fonts = ['fonts/vera.ttf', 'fonts/adventure.ttf'];
            $font  = $fonts[rand(0, 1)];

            $noise             = 1000;
            $this->ImageWidth  = 250;
            $this->ImageHeight = 40;
        } else {
            $noise             = 200;
            $this->ImageWidth  = 150;
            $this->ImageHeight = 30;
        }

        $obraz = imagecreate($this->ImageWidth, $this->ImageHeight);

        $kolor['1'] = imagecolorallocate($obraz, rand(0, 10), rand(0, 10), rand(0, 10));
        $kolor['2'] = imagecolorallocate($obraz, 169, 169, rand(100, 120));
        $kolor['3'] = imagecolorallocate($obraz, rand(100, 140), rand(120, 140), rand(0, 10));

        $skolor = $kolor;

        shuffle($skolor);
        $kolor['1'] = array_shift($skolor);
        $kolor['2'] = array_shift($skolor);
        $kolor['3'] = array_shift($skolor);

        imagefilledrectangle($obraz, 0, 0, $this->ImageWidth, $this->ImageHeight, $kolor['1']);

        for ($i = 0; $i < $noise; $i++) {
            $y = rand(2, $this->ImageHeight - 2);
            $x = rand(10, $this->ImageWidth - 20);
            imagesetpixel($obraz, $x, $y, $kolor['2']);
        }

        if ($tt) {
            $ofs = 0;
            $x   = rand(10, 15);
            $len = strlen($this->CaptchaString);
            for ($i = 0; $i < $len; $i++) {
                $r = rand(127, 255);
                $g = rand(127, 255);
                $b = rand(127, 255);
                $y = rand(20, 37);

                $fontsize = round(rand(18, 25));
                $color    = imagecolorallocate($obraz, $r, $g, $b);
                $presign  = round(rand(0, 1));
                $angle    = round(rand(0, 25));
                if ($presign == true) {
                    $angle = -1 * $angle;
                }

                $x += $ofs;

                imagettftext($obraz, $fontsize, $angle, $x, $y, $kolor['2'], $font, $this->CaptchaString[$i]);
                $ofs = rand(30, 40);
            }
        } else {
            imagestring($obraz, 5, rand(20, 30), rand(0, 5), $this->CaptchaString, $kolor['2']);
        }

        imagepng($obraz);
        imagedestroy($obraz);
    }

    public function GetCaptchaString()
    {
        return $this->CaptchaString;
    }

    public function SetCaptcha($sid)
    {
        $this->DeleteCaptcha($sid);
        $date = date("Y-m-d H:i:s");

        return $this->m_DB->InsertQuery($this->m_table, ['date' => $date, 'sid' => $sid, 'captcha' => $this->CaptchaString]);
    }

    public function DeleteCaptcha($sid)
    {
        $ret = false;

        $query = "DELETE FROM `$this->m_table` WHERE sid='$sid' OR (NOW()-`date` > $this->m_captchaLifetime)";
        $res   = $this->m_DB->query($query);
        if ($res) {
            $ret = $this->m_DB->Affected();
        }
        return $ret;
    }

    public function CheckCaptcha($sid, $token)
    {
        $this->DeleteCaptcha('');

        $query = "SELECT * FROM `$this->m_table` WHERE sid='$sid'";
        $row   = $this->m_DB->GetRow($query);

        if ($row && count($row) > 0) {
            if (strcmp(strtolower($row['captcha']), strtolower($token)) === 0) {
                return true;
            }
        }

        return false;
    }
}
