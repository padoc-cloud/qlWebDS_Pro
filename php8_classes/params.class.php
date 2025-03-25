<?php
  
/*
parameters table name: prefix_params
*/
  
  class ParamsClass {
    
    var $m_table;
    var $m_DB;
    var $m_tablePrefix;
    var $m_params = array();
    
    var $m_paramsTable;
    var $pError = false;
    
    // errors
    var $eText;
    var $eNo;
    var $eFunc;
                    
    function ParamsClass() {
        $this->m_DB =& DataBase::getInstance();
        if (!$this->m_DB->Open()) {
          $this->eText = 'DB connection failed';
          $this->eNo = 300001;
          $this->eFunc = '__construct';
        } else {
          $this->m_tablePrefix = $this->m_DB->tablePrefix;
          $this->m_table = $this->m_tablePrefix.'params';
          
          $this->InitParams();
          
        }    
    }
  
    function InitParams() {
      
      $query = "SELECT * FROM $this->m_table";
      $table = $this->m_DB->GetTable($query);
      
      if (count($table)>0) {

        foreach ($table as $row) {
          
          $aParams = unserialize($row['params']);
          $this->m_params[$row['id']] = $aParams;     
          
          $this->m_paramsTable[$row['id']] = $row['params'];     
        }
        
      } else {
        return false;
      }
      return true;      
    }
  
    function UpdateParams($type, $aParams, $preserve=true) {

      if ($preserve and isset($this->m_paramsTable[$type])) {
        
        $oldParams = unserialize($this->m_paramsTable[$type]);
        if (is_array($oldParams)) {
          $aParams = array_merge($oldParams, $aParams);

        }
      }    

      $sParams = serialize($aParams);
      $ret = $this->m_DB->UpdateQuery($this->m_table, array('params'=>$sParams), $type);

      if ($ret === 0) {
        $ret = $this->m_DB->SelectQuery($this->m_table, $type);
        if (is_array($ret) and count($ret)===0) {
          $ret = $this->m_DB->InsertQuery($this->m_table, array('params'=>$sParams, 'id'=>$type) );

        }
        
      }

      $this->InitParams();
      return $ret;
    
    }
    
    function GetParams($type) {
      // $pTable = $this->m_DB->SelectQuery($this->m_table, $type);
      
      $pTable = $this->m_paramsTable;

      if (isset($pTable[$type])) {

        $aParams = unserialize($pTable[$type]);
        return $aParams;
        
      } else {
        return false;
      }
      
    }
    
    function Get($type, $name) {
      $this->pError= false;
      if (isset($this->m_params[$type]) and isset($this->m_params[$type][$name])) {
        return $this->m_params[$type][$name];
      } else {
        $this->pError= true; 
        return false;
      }
    }
  }

?>
