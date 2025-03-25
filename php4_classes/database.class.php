<?php

  class DataBase {
       
        var $m_dbHost = DB_HOST;        
            
        var $m_dbName = DB_NAME;
        var $m_dbUser = DB_USER;
        var $m_dbPass = DB_PASS;
        var $tablePrefix = DB_TABLE_PREFIX;
            
        var $m_isConnected = false;
        var $m_isTransaction = false;
        var $m_conn;
        var $m_lastQueryTime;
        
        var $m_qStart = 0;
        var $m_qTime = array();
        
        function DataBase() {
        
        }
        
        function &getInstance ( )
        {

          static $oInstance = false;
          if ( $oInstance == false ) {
            $oInstance = new Database( );
          }
          return $oInstance;
        }

        function Config($host, $db, $user, $pass) {

          $this->m_dbHost = $host;
          $this->m_dbName = $db;
          $this->m_dbUser = $user;
          $this->m_dbPass = $pass;
        }
        
        function Open() {

          if (!($this->m_conn and $this->m_isConnected)) {
           
           $this->Tic();
           $this->m_conn = @mysql_connect($this->m_dbHost,$this->m_dbUser,$this->m_dbPass);
           
           if ($this->m_conn) {
              if (mysql_select_db($this->m_dbName, $this->m_conn)) {
                if (defined('CHARACTER_SET')) {
                 $odp = $this->query("SET CHARACTER SET ".CHARACTER_SET);
                } 
                $this->m_isConnected = true;
              } else {
                 trigger_error('Couldn`t find database: '.$this->m_dbName.'<br>'.mysql_error(),E_USER_ERROR);
                 $this->m_isConnected = false;
             }

           } else {
             trigger_error('Couldn`t connect to database: '.$this->m_dbName.'<br>'.mysql_error(),E_USER_ERROR);
             echo mysql_error();
             $this->m_isConnected = false;
           }
           $this->Toc('start');
          }

          return $this->m_isConnected;
        }
        
        function BeginTransaction() {
           $res = false;
           if ($this->m_isConnected) {
              $res = $this->query('START TRANSACTION');
              if ($res) {
                 $this->m_isTransaction = true;
              }
           }else {
             trigger_error('You must be connected to begin transaction\r\n<br>'.mysql_error(),E_USER_ERROR);
           }
           return $res;
        }
        function CommitTransaction() {
             $res = $this->query('COMMIT');
             if ($res) {
                $this->m_isTransaction = false;
             }
             return $res;
        }
        function RollbackTransaction() {
             $res = $this->query('ROLLBACK');
             if ($res) {
                $this->m_isTransaction = false;
             }
             return $res;
        }
        
        function query($query){
           $this->Tic();
           $res = mysql_query($query, $this->m_conn);
           $this->Toc($query);

           if (!$res) {
              echo '<br>****************************<br><b>Query Error:</b>: '.$query.'<br>'.mysql_error($this->m_conn).'<br>****************************<br>';
           }
           return $res;
        }
        
        function Count($res){
           return mysql_num_rows($res);
        }
        
        function GetTable($query) {
           
           $table = array();
           $res = $this->query($query);

           if ($res != false) {

              while ($row = mysql_fetch_assoc($res)) {
                    $table[] = $row;
              }

           }
           return $table;

        }
        
        function GetRow($query) {
          $res = $this->query($query);        
          if ($res and is_resource($res)) {
             $row = mysql_fetch_assoc($res);
             return $row;
          }  
          return false;
        }
        
        function GetLastId() {
           $lastId = mysql_insert_id($this->m_conn);
           return $lastId;

        }
        
        function Affected() {
          $hmany = mysql_affected_rows();
          return $hmany;
        }

        function InsertQuery($table, $values) {
         $a_values = array();
         $a_keys = array();
        
         $values = array_map("mysql_real_escape_string", $values);
         foreach ($values as $key=>$value) {
            $a_keys[] = $key;
            $a_values[] = $value; 
         }
         
         $keys = implode($a_keys, ',');
         $values = implode($a_values, "','");

         $query = 'INSERT INTO '.$table." ( $keys ) VALUES ( '$values')";
         $res = $this->query($query);

         if ($res) {
               return $this->GetLastId();
         }

         return false;
        }
        
        function UpdateQuery($table, $values, $id) {
         $atmp = array();
         $values = array_map("mysql_real_escape_string", $values);  
    
          foreach ($values as $key=>$value) {
            $atmp[] = "$key = '$value'"; 
          }

          $sets = implode($atmp,', ');
          
          $query = "UPDATE $table SET $sets WHERE id='$id'";
          $ret = $this->query($query);
          if ($ret) {
            $ret = $this->Affected();
          }
          return $ret;
          
        }
        
        function SelectQuery($table, $id) {
           
          $id = mysql_real_escape_string($id);  

          $query = "SELECT * FROM ".$table." WHERE id='$id'";  
          $tRet = $this->GetTable($query);
          return $tRet;
        }
        
        function __destruct() {
           if($this->m_isConnected ) {
            $this->m_isTransaction == false;

            $this->m_isConnected = false;
            mysql_close($this->m_conn);
           }

        }
        
        function Close() {
           if($this->m_isConnected ) {
            $this->m_isConnected = false;
            mysql_close($this->m_conn);
           }

        }
                
        // validators
        // allowed digits
        function CheckString($string) {
          
          return $string;
        }
        
        function Tic() {
           $mtime=microtime();
           $mtime=explode(" ",$mtime);
           $mtime=$mtime[1] + $mtime[0];
           $this->m_qStart =$mtime;
        }
        function Toc($query) {
           $mtime=microtime();
           $mtime=explode(" ",$mtime);
           $tEnd =$mtime[1] + $mtime[0];
           $all = $tEnd - $this->m_qStart;
           $all = round($all, 6);
           $this->m_qTime[] = '['.$all.']['.$query.']';
                     
        }
  }

?>
