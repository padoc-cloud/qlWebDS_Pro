<?php

  // This file performs caching operations by managing cached files in a specified directory. It includes methods for creating, retrieving, deleting, and clearing cache files.

  class CacheClass {
    var $bck;

    function __construct($dir = '') {
      $this->CacheClass($dir);
    }
    
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
        $arDir = [];
        if (is_dir($this->bck)) {
            $entries = scandir($this->bck); // Use scandir instead of dir()
            foreach ($entries as $entry) {
                if ($entry !== '.' && $entry !== '..') {
                    $arDir[] = ['name' => $entry];
                }
            }
        }
        return $arDir;
    }
    
    function filesTree() {
      $arDir = [];
      if (is_dir($this->bck)) {
          $entries = scandir($this->bck); // Use scandir instead of dir()
          foreach ($entries as $entry) {
              $fullPath = $this->bck . $entry;
              if ($entry !== '.' && $entry !== '..' && !is_dir($fullPath)) {
                  $arDir[] = ['name' => $entry];
              }
          }
      }
      return $arDir;
    }
                
  }

?>
