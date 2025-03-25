<?php

  class CaptchaClass
  {
    var $m_DB;
    var $m_tablePrefix;
    var $m_table;
    
    var $m_captchaLifetime = 950;
    
    var $Length;
    var $CaptchaString;
    var $ImageType;
    var $Font;
    var $CharWidth;
    
    var $ImageWidth;
    var $ImageHeight;
    
    function CaptchaClass($length = 6, $type = 'png', $letter = '')
    {

      if (IS_PHP5) {
        $this->m_DB = DataBase::getInstance();  
      } else {
        $this->m_DB =& DataBase::getInstance();
      }
      
      if (!$this->m_DB->Open()) {
        $this->eText = 'DB connection failed';
        $this->eNo = 300001;
        $this->eFunc = '__construct';
      } else {
        $this->m_tablePrefix = $this->m_DB->tablePrefix;
        $this->m_table = $this->m_tablePrefix.'captcha';        
      }
 
      $this->Length    = $length;
      $this->ImageType = $type;

      if ($letter == '')
      {
        $this->StringGen();
      } else  {
        $this->Length        = strlen($letter);
        $this->CaptchaString = $letter;
      }
      
      $this->ImageWidth = 250;
      $this->ImageHeight = 40;
      
    }

    function ShowImage() {
      $this->SendHeader();
      $this->MakeCaptcha();    
    }
    
    function StringGen ()
    {

      $uppercase  = range('A', 'P');
      $uppercase  = array_merge(range('R', 'Z'), $uppercase) ;
      
      $numeric    = range(2, 9);

      $CharPool   = array_merge($uppercase, $numeric);
      $PoolLength = count($CharPool) - 1;

      for ($i = 0; $i < $this->Length; $i++)
      {
        $this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)];
      }
    }

    function SendHeader ()
    {
    
      switch ($this->ImageType)
      {
        case 'jpeg': header('Content-type: image/jpeg'); break;
        case 'png':  header('Content-type: image/png');  break;
        default:     header('Content-type: image/png');  break;
      }
    }

    function MakeCaptcha ()
    {

        $info = gd_info();
        $tt = false;
        
        if ($info['FreeType Support']==1) {
          $tt = true;
          // putenv('GDFONTPATH=' . realpath('./fonts'));
          // $fonts = array('vera','adventure');
          $fonts = array('fonts/vera.ttf','fonts/adventure.ttf');
          $font = $fonts[rand(0,1)];
          
          $noise = 1000;
          $this->ImageWidth = 250;
          $this->ImageHeight = 40;          
        } else {
          $noise = 200;
          $this->ImageWidth = 150;
          $this->ImageHeight = 30;
        }
                
        $obraz = ImageCreate($this->ImageWidth, $this->ImageHeight); 
        
        $kolor['1'] = ImageColorAllocate($obraz, rand(0,10), rand(0,10), rand(0,10) ); 
        $kolor['2'] = ImageColorAllocate($obraz, 169, 169, rand(100, 120)); 
        $kolor['3'] = ImageColorAllocate($obraz, rand(100,140), rand(120,140), rand(0,10));
                 
        $skolor = $kolor;
        
        shuffle($skolor);
        $kolor['1'] = array_shift($skolor);
        $kolor['2'] = array_shift($skolor);
        $kolor['3'] = array_shift($skolor);
        
        ImageFilledRectangle($obraz, 0, 0, 250, 40, $kolor['1']);

        $y = rand(5, 10);
        for($i = 0; $i < $noise; $i++) {
            $y = rand(2, $this->ImageHeight-2);
            $x = rand(10, $this->ImageWidth-20); 
            imagesetpixel($obraz, $x, $y, $kolor['2']);
  
        }        
                  
        if ($tt) {
          $ofs = 0;
          $x = rand(10,15);
    			// set each letter with random angle, size and color
    			$len = strlen($this->CaptchaString);
    			for ($i=0;$i<$len;$i++)
    			{
    				$r = rand( 127, 255 ) ;
    				$g = rand( 127, 255 ) ;
    				$b = rand( 127, 255 ) ;
    				$y = rand( 20, 37);
    				
    				$fontsize = round( rand( 18, 25) );
    				$color = imagecolorallocate( $obraz, $r, $g, $b);
    				$presign = round( rand( 0, 1 ) );
    				$angle = round( rand( 0, 25 ) );
    				if ($presign==true) $angle = -1*$angle;
    				
            $x+= $ofs;
           
            ImageTTFText($obraz, $fontsize, $angle, $x , $y, $kolor['2'], $font, $this->CaptchaString[$i]);
            $ofs = rand(30, 40);    				
    			}        

        } else {
          imagestring($obraz, 5, rand(20,30), rand(0,5), $this->CaptchaString, $kolor['2']);
        }

        Imagepng($obraz); 
    }
      
    function GetCaptchaString ()
    {
      return $this->CaptchaString;
    }
    
    function SetCaptcha($sid) {
      $this->DeleteCaptcha($sid);
      $date = date("Y-m-d H:i:s");

      return $this->m_DB->InsertQuery($this->m_table, array('date'=>$date, 'sid'=>$sid, 'captcha'=>$this->CaptchaString));
      
    }
    
    function DeleteCaptcha($sid) {
      $ret = false;

      $query = "DELETE FROM `$this->m_table` WHERE sid='$sid' OR (NOW()-`date` > $this->m_captchaLifetime)";
      $res = $this->m_DB->query($query);        
      if ($res) {
        $ret = $this->m_DB->Affected();

      }
      return $ret;
    }
        
    function CheckCaptcha($sid,$token) {
        
        $this->DeleteCaptcha('');        
        
        $query = "SELECT * FROM `$this->m_table` WHERE sid='$sid'";
        $row = $this->m_DB->GetRow($query);
        
        if ($row and count($row)>0) {

            if (strcmp(strtolower($row['captcha']),strtolower($token) )===0) {
              return true;
            } 
        }

        return false;
        
    }  
        
  }

?>
