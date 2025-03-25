<?php

  // This file performs caching operations by managing cached files in a specified directory. It includes methods for creating, retrieving, deleting, and clearing cache files.

  class CacheClass {
    var $bck;
    
    function CacheClass($dir = '') {
      if (defined('CACHE_DIR')) {
        $this->bck = $dir.CACHE_DIR;
      } else { $this->bck = $dir.'cache/';}
    }
    
    function Delete($file, $dir='') {

        $ok = true;

        $link = $this->bck . $file;
        $main = $this->bck . 'index';
                
        if (file_exists($main)) {
           $ok= @unlink($main);
        }
                
        if (file_exists($link)) {
           $ok= @unlink($link);
        }
        return $ok;
        
    }
    
    function Get($file) {
        $link = $this->bck . $file;

        if (file_exists($link)) {
          return $link;
        }
 
        return false;        
    }

    function Make($file, $tpl) {
      if (strlen($tpl)>0) {
       $link = $this->bck . $file;      
        $fp = fopen($link, 'w');
        if ($fp) {
            fwrite($fp, $tpl);
            fclose($fp);
            return true;
    
        }
      }
      return false;
    }
    
    function EmptyCache($dir = '') {
      $list = $this->filesTree();
      $ok = true;
      
      foreach ($list as $row){
        $link = $this->bck . $row['name'];
        if (file_exists($link)) {
           $ok = @unlink($link);
        }

      }
      
      return $ok;
      
    }
    
    // private 
    function dirTree() {
      $d = dir($this->bck);
      $arDir = array();

      while (false !== ($entry = $d->read())) {
        if($entry != '.' && $entry != '..') {
           $arDir[] = array('name'=>$entry);
        }
      }
      $d->close();

      return $arDir;
    }
    
    function filesTree() {
      $d = dir($this->bck);
      $arDir = array();

      while (false !== ($entry = $d->read())) {
        if($entry != '.' && $entry != '..') {
          if (!is_dir($entry) ) {
           $arDir[] = array('name'=>$entry);
          }
        }
      }
      $d->close();

      return $arDir;
    } 
                
  }

?>
