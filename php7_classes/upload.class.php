<?php

  // This file performs file upload and management operations for the application. It includes methods for uploading, validating, resizing images, and handling file naming conflicts.

  class UploadClass {
    
    var $max_photo_size = MAX_LOGO_SIZE; 
    var $m_dir;
    var $m_name; 
    
    var $eName;
    var $m_aFilter;
    
    function UploadClass($dir, $name, $max = 1, $aFilter = array('jpg', 'png', 'gif', 'bmp', 'jpeg')) {
       $this->max_photo_size = $this->max_photo_size*$max;
       $this->m_dir = $dir;
       $this->m_name = $name; 
       $this->m_aFilter = $aFilter;
    }
  
    /* ///////////////////////////////////////////////////////////////////////////////////////////////
    // show images  
    */    
    function dirTree($dir) {

         $d = dir($dir);
      
         while (false !== ($entry = $d->read())) {
             if($entry != '.' && $entry != '..' && !is_dir($dir.'/'.$entry)) {
                if (substr($entry,0,3)=='tn_') {
                  $arDir[$entry] = $dir.'/'.$entry;
                }
             }
         }
         $d->close();
         return $arDir;
    }
    
    /* ///////////////////////////////////////////////////////////////////////////////////////////////
    // upload  
    */    
    function UploadPhoto($fname, $overwrite = false) {
        
        $dirs = explode('/', $this->m_dir);
        $tmp_dir = '';
        $etext = '';
        
        foreach ($dirs as $dir) {
          $tmp_dir .= $dir;
          if ( !is_dir($tmp_dir) ) { mkdir($tmp_dir); }
          $tmp_dir .= '/';
        }
        
        if ( !is_dir($this->m_dir) ) { mkdir($this->m_dir); }
        
        $etext = '';
         
        $sid = session_id();
        $file_ext = $this->CheckExt();
        
        if ($file_ext) {
  
         if (is_uploaded_file($_FILES[$this->m_name]['tmp_name'])) {
  
             $fileName = $_FILES[$this->m_name]['tmp_name'];
             $realName = $_FILES[$this->m_name]['name'];
             
             if (!$overwrite) {
                $fname = $this->FreeName( strtolower($fname));
             }
             
             $newName = $this->m_dir .'/'. strtolower($fname);
                        
             if($_FILES[$this->m_name]["size"]<$this->max_photo_size ){
             
                 move_uploaded_file($_FILES[$this->m_name]['tmp_name'], $newName );
                 $ret = $fname;
                 return $ret;
                 
             } else {
                 $this->eName .= "Photo was too big. <br>";
             }
             
         }  else {
            $this->eName .= "Photo wasn`t uploaded. <br>";
         }
         
        } else {
  
            $this->eName .= "Bad photo format, only JPG or PNG files are accepted. <br>";
        }
        
        return false;
    
    }
      
    function FreeName($fname) {  
      
      global $etext;
      
      $Name = $this->m_dir.'/'. $fname ;
      
      if (!file_exists($Name)) { 
        
        return $fname; 
        
      } else {
        $filename = explode(".", $fname);
        
        if (count($filename)>1) {
          
          $filenameext = $filename[count($filename)-1];
          unset( $filename[count($filename)-1] );
          $filename = implode('.', $filename);
          
        } else { 
          $filename = $filename[0];
          $filenameext = ''; 
        }
        
        for($i=1; $i<100; $i++) {
            
            $Name = $this->m_dir .'/'.$filename.'_'.$i.'.'.$filenameext  ;
            if (!file_exists($Name)) {
              return $filename.'_'.$i.'.'.$filenameext;
            } 
            
        }
        
      }
      
      $etext .= 'Change filename<br>';
      
      return false;
    }
    
    function ResizeImg($input, $output, $max_width, $max_height) {
              
          $ret = false;
          $etext = '';
          
          list ($width, $height) = getimagesize($input);
          
          if ($width>0 and $height>0) {
          
            if ($max_width && $max_height)
            {
               $pw = ($max_width/$width);
               $ph = ($max_height/$height);
    
               $delta = min($pw, $ph);
    
               $new_width = floor($width*$delta);
               $new_height = floor($height*$delta);

            } else {
            
              if ($max_width)
              {
                 $delta = ($max_width/$width);
      
                 $new_width = $max_width;
                 $new_height = floor($height*$delta);

              }        
              else
              {
                 $delta = ($max_height/$height);
      
                 $new_width = floor($width*$delta);
                 $new_height = $max_height; 
              }
              
            }
              
            $new_image = @imagecreatetruecolor($new_width, $new_height) or die("Cannot Initialize new GD image stream");
      
            $what = @getimagesize($input);
      
            $gd2er = false;
            $t = 0;
              
            switch($what['mime'])
            {
               case 'image/png' : $source = imagecreatefrompng($input) or die ($gd2er = true); $t=1; break;
               case 'image/jpeg': $source = imagecreatefromjpeg($input) or die ($gd2er = true); $t=2; break;
               case 'image/gif' : $source = imagecreatefromgif($input) or die ($gd2er = true); $t=3; break;
               case 'image/bmp' : $etext = 'BMP images not supported'; break;
      
               default: $etext .= "Unknown image type. <br> "; break;
            }
      
            if (!$gd2er)
            {
               $ret = @imagecopyresampled($new_image, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                 
               if ($ret) {
                 switch ($t) {
                   case 1: $ret = imagepng($new_image, $output); break;
                   case 2: $ret = imagejpeg($new_image, $output, 90); break;
                   case 3: $ret = imagegif($new_image, $output); break;
                 }
               }
                 
              }
              
              @imagedestroy($new_image);
              
          } else { $etext .= "Error! Bad image format. <br> ";}
          
          return $ret;
    }
         
    function CheckExt() {
    
        $file_types_array = $this->m_aFilter; // array('jpg', 'png', 'gif', 'bmp', 'jpeg');
    
        $filename = explode(".", $_FILES[$this->m_name]["name"]);
        $filenameext = strtolower($filename[count($filename)-1]);
            
        for($x=0;$x<count($file_types_array);$x++){
        
          if($filenameext==$file_types_array[$x]) {
              return $filenameext;
          }
        
        } // for
          
        return false;
    } 

    function UploadFile($fname, $overwrite = false) {
        
        $dirs = explode('/', $this->m_dir);
        $tmp_dir = '';
        $etext = '';
        
        foreach ($dirs as $dir) {
          $tmp_dir .= $dir;
          if ( !is_dir($tmp_dir) ) { mkdir($tmp_dir); }
          $tmp_dir .= '/';
        }
        
        if ( !is_dir($this->m_dir) ) { mkdir($this->m_dir); }
        
        $etext = '';
         
        $sid = session_id();
        $file_ext = $this->CheckExt();
        
        if ($file_ext) {
        
	        if (is_uploaded_file($_FILES[$this->m_name]['tmp_name'])) {
	  
	        	$fileName = $_FILES[$this->m_name]['tmp_name'];
	            $realName = $_FILES[$this->m_name]['name'];
	             
	            if (!$overwrite) {
	               $fname = $this->FreeName( strtolower($fname));
	            }
	             
	            $newName = $this->m_dir .'/'. strtolower($fname);
	                        
	            move_uploaded_file($_FILES[$this->m_name]['tmp_name'], $newName );
	            $ret = $fname;
	            return $ret;
	             
	        } else {
	            $this->eName .= "File was not uploaded. <br>";
	        }
        } else {
            $this->eName .= "Bad file format, only .csv files are accepted. <br>";
        }
        
        return false;
    }
    
  }

?>
