<?php 
  
  class DebugClass {
    
    var $m_DB;
    var $m_table;
    var $m_connection;
    var $m_payment;
    var $m_tablePrefix;
    var $m_qStart;
    var $m_qTime;
    var $m_min = 0.000001;
    
    function DebugClass() {
      
      /* 
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
      }
	  */
 
    }
      
    function Tic($name) {
      $mtime=microtime();
      $mtime=explode(" ",$mtime);
      $mtime=$mtime[1] + $mtime[0];
      $this->m_qStart[$name] =$mtime;
    }
    
    function Toc($name) {
      $mtime=microtime();
      $mtime=explode(" ",$mtime);
      $tEnd =$mtime[1] + $mtime[0];
      $all = $tEnd - $this->m_qStart[$name];
      $all = round($all, 6);
      if ($all>$this->m_min) {
        $this->m_qTime[] = '['.$all.']['.$name.']';
      }
                     
    }  
   
  }
 
?>
